<?php

namespace App\Services;

use App\Models\LegalCase;
use App\Models\LawyerProfile;
use App\Models\CaseLawyerOffer;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MatchingService
{
    /**
     * Find available lawyers for a case at the given radius level.
     *
     * Level 1: same sub_district as meeting point
     * Level 2: same city_district as meeting point
     * Level 3: same province as meeting point
     */
    public static function findLawyersForCase(LegalCase $case, int $level): array
    {
        // Get lawyer IDs that already rejected/expired for this case
        $rejectedIds = CaseLawyerOffer::where('case_id', $case->id)
            ->whereIn('status', ['rejected', 'expired'])
            ->pluck('lawyer_id')
            ->toArray();

        // Get lawyer IDs that already have an active case
        $activeIds = LegalCase::whereIn('status', ['matched', 'waiting_payment', 'paid', 'in_progress'])
            ->whereNotNull('lawyer_id')
            ->pluck('lawyer_id')
            ->toArray();

        $excludedIds = array_unique(array_merge($rejectedIds, $activeIds));

        // Build query
        $query = LawyerProfile::query()
            ->join('users', 'lawyer_profiles.user_id', '=', 'users.id')
            ->where('lawyer_profiles.is_available', true)
            ->where('users.is_active', true)
            ->select('lawyer_profiles.*');

        if (!empty($excludedIds)) {
            $query->whereNotIn('lawyer_profiles.user_id', $excludedIds);
        }

        // Apply radius level filter
        if ($level === 1 && $case->meeting_sub_district) {
            $query->where('lawyer_profiles.sub_district', $case->meeting_sub_district);
        } elseif ($level === 2 && $case->meeting_city_district) {
            $query->where('lawyer_profiles.city_district', $case->meeting_city_district);
        } elseif ($level === 3 && $case->meeting_province) {
            $query->where('lawyer_profiles.province', $case->meeting_province);
        } else {
            return [];
        }

        $lawyers = $query->get();

        // Filter by category
        $filtered = [];
        foreach ($lawyers as $lawyer) {
            $specIds = $lawyer->specialization_ids;
            if (in_array($case->category_id, $specIds)) {
                $filtered[] = $lawyer;
            }
        }

        return $filtered;
    }

    /**
     * Create offer records and send FCM notifications to matched lawyers.
     */
    public static function sendOffersToLawyers(LegalCase $case, array $lawyers): void
    {
        foreach ($lawyers as $lawyerProfile) {
            // Check if offer already exists
            $existing = CaseLawyerOffer::where('case_id', $case->id)
                ->where('lawyer_id', $lawyerProfile->user_id)
                ->first();

            if ($existing) {
                continue;
            }

            // Create offer record
            CaseLawyerOffer::create([
                'case_id' => $case->id,
                'lawyer_id' => $lawyerProfile->user_id,
                'status' => 'sent',
            ]);

            // Send FCM notification
            NotificationService::sendPushNotification(
                $lawyerProfile->user_id,
                'Ada Kasus Baru!',
                'Ada permintaan konsultasi baru di area Anda. Segera cek!',
                'new_case',
                $case->id,
                ['case_id' => (string) $case->id, 'type' => 'new_case']
            );
        }

        Log::info("Sent offers for case {$case->id} to " . count($lawyers) . " lawyers at level {$case->current_radius_level}");
    }

    /**
     * Run matching when a new case is created.
     * Tries level 1 first, then cascades up if empty.
     */
    public static function runInitialMatching(LegalCase $case): void
    {
        for ($level = 1; $level <= 3; $level++) {
            $lawyers = self::findLawyersForCase($case, $level);

            if (!empty($lawyers)) {
                $case->update(['current_radius_level' => $level]);
                self::sendOffersToLawyers($case, $lawyers);

                // Schedule radius expansion after 3 hours (via Laravel queue)
                dispatch(new \App\Jobs\ExpandRadiusJob($case->id))
                    ->delay(now()->addHours(3));

                Log::info("Case {$case->id} matched at level {$level}, expansion scheduled");
                return;
            }
        }

        // No lawyers found at any level
        Log::warning("No lawyers found for case {$case->id} at any level");
        dispatch(new \App\Jobs\ExpandRadiusJob($case->id))
            ->delay(now()->addHours(3));
    }
}
