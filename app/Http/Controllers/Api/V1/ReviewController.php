<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use App\Models\Review;
use App\Models\LawyerProfile;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'client') {
            return response()->json(['detail' => 'Akses hanya untuk Client'], 403);
        }

        $request->validate([
            'case_id' => 'required|integer',
            'is_satisfied' => 'required|boolean',
            'comment' => 'nullable|string',
        ]);

        $case = LegalCase::find($request->case_id);
        if (!$case) {
            return response()->json(['detail' => 'Case tidak ditemukan'], 404);
        }
        if ($case->client_id !== $user->id) {
            return response()->json(['detail' => 'Bukan case Anda'], 403);
        }
        if ($case->status !== 'completed') {
            return response()->json(['detail' => 'Review hanya bisa diberikan setelah case selesai'], 400);
        }
        if (!$case->lawyer_id) {
            return response()->json(['detail' => 'Case tidak memiliki lawyer'], 400);
        }

        // Check duplicate
        if (Review::where('case_id', $request->case_id)->exists()) {
            return response()->json(['detail' => 'Review sudah pernah diberikan untuk case ini'], 409);
        }

        $review = Review::create([
            'case_id' => $request->case_id,
            'client_id' => $user->id,
            'lawyer_id' => $case->lawyer_id,
            'is_satisfied' => $request->is_satisfied,
            'comment' => $request->comment,
        ]);

        // Update lawyer rating
        $this->updateLawyerRating($case->lawyer_id);

        return response()->json($review, 201);
    }

    private function updateLawyerRating(int $lawyerUserId): void
    {
        $reviews = Review::where('lawyer_id', $lawyerUserId)->get();

        if ($reviews->isEmpty()) {
            return;
        }

        $satisfiedCount = $reviews->where('is_satisfied', true)->count();
        $avg = round(($satisfiedCount / $reviews->count()) * 5, 2);

        LawyerProfile::where('user_id', $lawyerUserId)
            ->update(['rating_avg' => $avg]);
    }
}
