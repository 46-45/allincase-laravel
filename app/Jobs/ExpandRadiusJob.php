<?php

namespace App\Jobs;

use App\Models\LegalCase;
use App\Services\MatchingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpandRadiusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $caseId)
    {
    }

    public function handle(): void
    {
        $case = LegalCase::find($this->caseId);

        if (!$case || $case->status !== 'pending') {
            return;
        }

        $nextLevel = $case->current_radius_level + 1;

        if ($nextLevel > 3) {
            Log::info("Case {$case->id} reached max radius level");
            return;
        }

        $lawyers = MatchingService::findLawyersForCase($case, $nextLevel);

        if (!empty($lawyers)) {
            $case->update([
                'current_radius_level' => $nextLevel,
                'radius_expanded_at' => now(),
            ]);
            MatchingService::sendOffersToLawyers($case, $lawyers);

            // Schedule next expansion
            dispatch(new self($case->id))->delay(now()->addHours(3));
            Log::info("Case {$case->id} expanded to level {$nextLevel}");
        } else {
            // Try next level immediately
            $case->update(['current_radius_level' => $nextLevel]);
            dispatch(new self($case->id))->delay(now()->addMinutes(5));
        }
    }
}
