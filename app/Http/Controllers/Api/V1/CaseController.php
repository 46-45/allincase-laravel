<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use App\Models\CaseLawyerOffer;
use App\Services\CaseService;
use App\Services\MatchingService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CaseController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'client') {
            return response()->json(['detail' => 'Akses hanya untuk Client'], 403);
        }

        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'meeting_lat' => 'required|numeric',
            'meeting_lng' => 'required|numeric',
            'meeting_address' => 'required|string',
            'meeting_datetime' => 'required|date|after:now',
            'detail_notes' => 'nullable|string',
        ], [
            'meeting_datetime.after' => 'Waktu pertemuan harus di masa depan',
        ]);

        $case = CaseService::createCase(
            $user->id,
            $request->category_id,
            $request->meeting_lat,
            $request->meeting_lng,
            $request->meeting_address,
            $request->meeting_datetime,
            $request->detail_notes
        );

        // Run matching
        MatchingService::runInitialMatching($case);

        return response()->json($case->fresh(), 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'client') {
            $cases = LegalCase::where('client_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        } elseif ($user->role === 'lawyer') {
            $cases = LegalCase::where('lawyer_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        } else {
            $cases = LegalCase::orderByDesc('created_at')->get();
        }

        return response()->json($cases);
    }

    public function show(Request $request, int $caseId)
    {
        $user = $request->user();

        $case = LegalCase::with(['client', 'lawyer.lawyerProfile', 'category'])
            ->find($caseId);

        if (!$case) {
            return response()->json(['detail' => 'Case tidak ditemukan'], 404);
        }

        // Access control
        if ($user->role === 'client' && (int) $case->client_id !== (int) $user->id) {
            return response()->json(['detail' => 'Bukan case Anda'], 403);
        }
        if ($user->role === 'lawyer' && (int) $case->lawyer_id !== (int) $user->id) {
            return response()->json(['detail' => 'Bukan case Anda'], 403);
        }

        return response()->json($this->buildCaseDetail($case));
    }

    public function accept(Request $request, int $caseId)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        $case = CaseService::lawyerAcceptCase($caseId, $user->id);
        return response()->json($case);
    }

    public function reject(Request $request, int $caseId)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        CaseService::lawyerRejectCase($caseId, $user->id);
        return response()->noContent();
    }

    public function cancel(Request $request, int $caseId)
    {
        $user = $request->user();
        if ($user->role !== 'client') {
            return response()->json(['detail' => 'Akses hanya untuk Client'], 403);
        }

        $case = CaseService::clientCancelCase($caseId, $user->id);
        return response()->json($case);
    }

    public function complete(Request $request, int $caseId)
    {
        $user = $request->user();
        if ($user->role !== 'client') {
            return response()->json(['detail' => 'Akses hanya untuk Client'], 403);
        }

        $case = CaseService::clientCompleteCase($caseId, $user->id);
        return response()->json($case);
    }

    public function incoming(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'lawyer') {
            return response()->json(['detail' => 'Akses hanya untuk Lawyer'], 403);
        }

        $caseIds = CaseLawyerOffer::where('lawyer_id', $user->id)
            ->where('status', 'sent')
            ->pluck('case_id');

        $cases = LegalCase::whereIn('id', $caseIds)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($cases);
    }

    private function buildCaseDetail(LegalCase $case): array
    {
        return [
            'id' => $case->id,
            'case_number' => $case->case_number,
            'client_id' => $case->client_id,
            'lawyer_id' => $case->lawyer_id,
            'category_id' => $case->category_id,
            'meeting_lat' => $case->meeting_lat,
            'meeting_lng' => $case->meeting_lng,
            'meeting_address' => $case->meeting_address,
            'meeting_datetime' => $case->meeting_datetime?->toIso8601String(),
            'meeting_sub_district' => $case->meeting_sub_district,
            'meeting_city_district' => $case->meeting_city_district,
            'meeting_province' => $case->meeting_province,
            'current_radius_level' => $case->current_radius_level,
            'distance_km' => $case->distance_km,
            'base_price' => $case->base_price,
            'service_fee' => $case->service_fee,
            'total_price' => $case->total_price,
            'status' => $case->status,
            'detail_notes' => $case->detail_notes,
            'payment_id' => $case->payment_id,
            'payment_method' => $case->payment_method,
            'paid_at' => $case->paid_at?->toIso8601String(),
            'completed_at' => $case->completed_at?->toIso8601String(),
            'cancelled_at' => $case->cancelled_at?->toIso8601String(),
            'created_at' => $case->created_at?->toIso8601String(),
            'category_name' => $case->category?->name,
            'client_name' => $case->client?->full_name,
            'client_phone' => $case->client?->phone,
            'lawyer_name' => $case->lawyer?->full_name,
            'lawyer_avatar' => $case->lawyer?->avatar_url,
            'lawyer_rating' => $case->lawyer?->lawyerProfile?->rating_avg,
        ];
    }
}
