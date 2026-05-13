<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function getSnapToken(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'client') {
            return response()->json(['detail' => 'Akses hanya untuk Client'], 403);
        }

        $request->validate(['case_id' => 'required|integer']);

        $case = LegalCase::find($request->case_id);
        if (!$case) {
            return response()->json(['detail' => 'Case tidak ditemukan'], 404);
        }
        if ((int) $case->client_id !== (int) $user->id) {
            return response()->json(['detail' => 'Bukan case Anda'], 403);
        }
        if (!in_array($case->status, ['matched', 'waiting_payment'])) {
            return response()->json(['detail' => 'Case tidak dalam status menunggu pembayaran'], 400);
        }

        $snapData = PaymentService::createSnapToken($case, $user, $case->payment_id);

        $case->update([
            'payment_id' => $snapData['order_id'],
            'status' => 'waiting_payment',
        ]);

        return response()->json([
            'snap_token' => $snapData['snap_token'],
            'order_id' => $case->payment_id,
            'total_price' => (float) $case->total_price,
            'is_production' => config('services.midtrans.is_production'),
        ]);
    }

    public function webhook(Request $request)
    {
        $data = $request->all();

        $orderId = $data['order_id'] ?? '';
        $statusCode = $data['status_code'] ?? '';
        $grossAmount = $data['gross_amount'] ?? '';
        $signatureKey = $data['signature_key'] ?? '';
        $transactionStatus = $data['transaction_status'] ?? '';
        $paymentType = $data['payment_type'] ?? '';

        // Verify signature
        if (!PaymentService::verifyWebhookSignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning("Invalid webhook signature for order {$orderId}");
            return response()->json(['detail' => 'Invalid signature'], 400);
        }

        Log::info("Webhook received: order={$orderId}, status={$transactionStatus}");

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            PaymentService::handlePaymentSuccess($orderId, $paymentType);
        } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
            Log::info("Payment failed/cancelled for order {$orderId}: {$transactionStatus}");
        }

        return response()->json(['status' => 'ok']);
    }

    public function finish()
    {
        return response()->json(['message' => 'Pembayaran selesai. Silakan kembali ke aplikasi.']);
    }

    public function simulateSuccess(int $caseId)
    {
        if (config('app.env') === 'production') {
            return response()->json(['detail' => 'Not available in production'], 400);
        }

        $case = LegalCase::find($caseId);
        if (!$case) {
            return response()->json(['detail' => 'Case tidak ditemukan'], 404);
        }

        if (!in_array($case->status, ['matched', 'waiting_payment'])) {
            return response()->json(['detail' => "Case status is '{$case->status}', expected 'waiting_payment'"], 400);
        }

        PaymentService::handlePaymentSuccess($case->payment_id ?? "SIM-{$caseId}", 'simulated');

        return response()->json(['status' => 'ok', 'message' => "Case {$caseId} marked as PAID"]);
    }
}
