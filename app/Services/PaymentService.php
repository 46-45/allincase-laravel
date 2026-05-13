<?php

namespace App\Services;

use App\Models\LegalCase;
use App\Models\User;
use App\Models\Disbursement;
use App\Models\LawyerProfile;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class PaymentService
{
    public static function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production');
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;
    }

    /**
     * Create Midtrans Snap token for payment.
     */
    public static function createSnapToken(LegalCase $case, User $client, ?string $existingOrderId = null): array
    {
        self::configureMidtrans();

        $orderId = $existingOrderId ?: 'AIC-' . $case->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $case->total_price,
            ],
            'customer_details' => [
                'first_name' => $client->full_name,
                'email' => $client->email,
                'phone' => $client->phone ?? '',
            ],
            'callbacks' => [
                'finish' => config('app.url') . '/api/v1/payments/finish',
            ],
            'expiry' => [
                'unit' => 'hour',
                'duration' => 24,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return [
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ];
        } catch (\Exception $e) {
            // If existing order_id was rejected, try with a new one
            $newOrderId = 'AIC-' . $case->id . '-' . time();
            $params['transaction_details']['order_id'] = $newOrderId;
            $snapToken = Snap::getSnapToken($params);
            return [
                'snap_token' => $snapToken,
                'order_id' => $newOrderId,
            ];
        }
    }

    /**
     * Verify Midtrans webhook signature.
     */
    public static function verifyWebhookSignature(string $orderId, string $statusCode, string $grossAmount, string $receivedSignature): bool
    {
        $serverKey = config('services.midtrans.server_key');
        $raw = $orderId . $statusCode . $grossAmount . $serverKey;
        $expected = hash('sha512', $raw);
        return $expected === $receivedSignature;
    }

    /**
     * Handle payment success from webhook.
     */
    public static function handlePaymentSuccess(string $orderId, string $paymentMethod): void
    {
        $case = LegalCase::where('payment_id', $orderId)->first();

        if (!$case) {
            Log::warning("Case not found for order_id: {$orderId}");
            return;
        }

        if (!in_array($case->status, ['matched', 'waiting_payment'])) {
            Log::info("Case {$case->id} already processed, status: {$case->status}");
            return;
        }

        $case->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $paymentMethod,
        ]);

        // Notify client
        NotificationService::sendPushNotification(
            $case->client_id,
            'Pembayaran Berhasil!',
            'Pembayaran Anda berhasil. Silakan hubungi lawyer melalui chat.',
            'payment_success',
            $case->id,
            ['case_id' => (string) $case->id, 'type' => 'payment_success']
        );

        // Notify lawyer
        if ($case->lawyer_id) {
            NotificationService::sendPushNotification(
                $case->lawyer_id,
                'Pembayaran Diterima!',
                'Klien sudah melakukan pembayaran. Silakan hubungi klien.',
                'payment_success',
                $case->id,
                ['case_id' => (string) $case->id, 'type' => 'payment_received']
            );
        }

        Log::info("Case {$case->id} marked as PAID");
    }

    /**
     * Create disbursement record when case is completed.
     */
    public static function createDisbursementRecord(LegalCase $case): Disbursement
    {
        $lawyerProfile = LawyerProfile::where('user_id', $case->lawyer_id)->first();

        return Disbursement::create([
            'case_id' => $case->id,
            'lawyer_id' => $case->lawyer_id,
            'amount' => $case->total_price,
            'platform_cut' => 0,
            'bank_name' => $lawyerProfile?->bank_name,
            'bank_account_number' => $lawyerProfile?->bank_account_number,
            'bank_account_name' => $lawyerProfile?->bank_account_name,
            'status' => 'pending',
        ]);
    }
}
