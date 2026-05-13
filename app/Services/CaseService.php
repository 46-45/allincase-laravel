<?php

namespace App\Services;

use App\Models\LegalCase;
use App\Models\CaseLawyerOffer;
use App\Models\LawyerProfile;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CaseService
{
    public static function generateCaseNumber(): string
    {
        $now = now();
        $rand = rand(1000, 9999);
        return 'AIC-' . $now->format('Ymd') . '-' . $rand;
    }

    public static function createCase(
        int $clientId,
        int $categoryId,
        float $meetingLat,
        float $meetingLng,
        string $meetingAddress,
        string $meetingDatetime,
        ?string $detailNotes = null
    ): LegalCase {
        $geoData = GeoService::reverseGeocode($meetingLat, $meetingLng);

        $case = LegalCase::create([
            'case_number' => self::generateCaseNumber(),
            'client_id' => $clientId,
            'category_id' => $categoryId,
            'meeting_lat' => $meetingLat,
            'meeting_lng' => $meetingLng,
            'meeting_address' => $meetingAddress,
            'meeting_datetime' => $meetingDatetime,
            'meeting_sub_district' => $geoData['sub_district'] ?? null,
            'meeting_city_district' => $geoData['city_district'] ?? null,
            'meeting_province' => $geoData['province'] ?? null,
            'detail_notes' => $detailNotes,
            'status' => 'pending',
            'current_radius_level' => 1,
        ]);

        Log::info("Case created: {$case->case_number} for client {$clientId}");
        return $case;
    }

    public static function lawyerAcceptCase(int $caseId, int $lawyerUserId): LegalCase
    {
        $case = LegalCase::findOrFail($caseId);

        if ($case->status !== 'pending') {
            abort(400, 'Case sudah tidak tersedia');
        }

        // Check offer exists
        $offer = CaseLawyerOffer::where('case_id', $caseId)
            ->where('lawyer_id', $lawyerUserId)
            ->where('status', 'sent')
            ->first();

        if (!$offer) {
            abort(403, 'Anda tidak memiliki offer untuk case ini');
        }

        // Check lawyer doesn't have active case
        $activeCase = LegalCase::where('lawyer_id', $lawyerUserId)
            ->whereIn('status', ['matched', 'waiting_payment', 'paid', 'in_progress'])
            ->first();

        if ($activeCase) {
            abort(400, 'Anda masih memiliki case aktif');
        }

        // Get lawyer profile
        $lawyerProfile = LawyerProfile::where('user_id', $lawyerUserId)->first();
        if (!$lawyerProfile) {
            abort(400, 'Profil lawyer tidak ditemukan');
        }

        // Calculate distance
        $distanceKm = 0.0;
        if ($lawyerProfile->last_lat && $lawyerProfile->last_lng) {
            $distanceKm = GeoService::haversineDistance(
                (float) $lawyerProfile->last_lat,
                (float) $lawyerProfile->last_lng,
                (float) $case->meeting_lat,
                (float) $case->meeting_lng
            );
        }

        // Calculate price
        $pricing = PricingService::getActivePricing();
        if (!$pricing) {
            abort(400, 'Konfigurasi harga belum diatur');
        }

        $priceData = PricingService::calculatePrice($distanceKm, $pricing);

        // Update case
        $case->update([
            'lawyer_id' => $lawyerUserId,
            'status' => 'matched',
            'lawyer_lat_on_accept' => $lawyerProfile->last_lat,
            'lawyer_lng_on_accept' => $lawyerProfile->last_lng,
            'distance_km' => $priceData['distance_km'],
            'base_price' => $priceData['base_price'],
            'service_fee' => $priceData['service_fee'],
            'total_price' => $priceData['total_price'],
        ]);

        // Update offer
        $offer->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        // Expire other offers
        CaseLawyerOffer::where('case_id', $caseId)
            ->where('status', 'sent')
            ->where('lawyer_id', '!=', $lawyerUserId)
            ->update(['status' => 'expired']);

        // Set lawyer unavailable
        $lawyerProfile->update(['is_available' => false]);

        // Notify client
        NotificationService::sendPushNotification(
            $case->client_id,
            'Lawyer Ditemukan!',
            'Lawyer telah menerima request Anda. Silakan lakukan pembayaran.',
            'case_matched',
            $case->id,
            ['case_id' => (string) $case->id, 'type' => 'case_matched']
        );

        Log::info("Case {$case->id} accepted by lawyer {$lawyerUserId}");
        return $case->fresh();
    }

    public static function lawyerRejectCase(int $caseId, int $lawyerUserId): void
    {
        $offer = CaseLawyerOffer::where('case_id', $caseId)
            ->where('lawyer_id', $lawyerUserId)
            ->where('status', 'sent')
            ->first();

        if (!$offer) {
            abort(404, 'Offer tidak ditemukan');
        }

        $offer->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        Log::info("Case {$caseId} rejected by lawyer {$lawyerUserId}");
    }

    public static function clientCancelCase(int $caseId, int $clientUserId): LegalCase
    {
        $case = LegalCase::findOrFail($caseId);

        if ((int) $case->client_id !== (int) $clientUserId) {
            abort(403, 'Bukan case Anda');
        }

        if (!in_array($case->status, ['pending', 'matched', 'waiting_payment'])) {
            abort(400, 'Case tidak dapat dibatalkan pada status ini');
        }

        $case->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Re-enable lawyer availability
        if ($case->lawyer_id) {
            LawyerProfile::where('user_id', $case->lawyer_id)
                ->update(['is_available' => true]);

            NotificationService::sendPushNotification(
                $case->lawyer_id,
                'Case Dibatalkan',
                'Klien membatalkan request konsultasi.',
                'case_cancelled',
                $case->id
            );
        }

        return $case->fresh();
    }

    public static function clientCompleteCase(int $caseId, int $clientUserId): LegalCase
    {
        $case = LegalCase::findOrFail($caseId);

        if ((int) $case->client_id !== (int) $clientUserId) {
            abort(403, 'Bukan case Anda');
        }

        if (!in_array($case->status, ['paid', 'in_progress'])) {
            abort(400, 'Case belum dalam status yang bisa diselesaikan');
        }

        $case->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($case->lawyer_id) {
            $lawyerProfile = LawyerProfile::where('user_id', $case->lawyer_id)->first();
            if ($lawyerProfile) {
                $lawyerProfile->update([
                    'is_available' => true,
                    'total_cases' => $lawyerProfile->total_cases + 1,
                    'total_earned' => (float) $lawyerProfile->total_earned + (float) $case->total_price,
                ]);
            }

            // Create disbursement record
            PaymentService::createDisbursementRecord($case);

            NotificationService::sendPushNotification(
                $case->lawyer_id,
                'Case Selesai!',
                'Klien telah menyelesaikan sesi konsultasi.',
                'case_completed',
                $case->id
            );
        }

        Log::info("Case {$case->id} completed by client {$clientUserId}");
        return $case->fresh();
    }
}
