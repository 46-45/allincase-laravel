<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    /**
     * Send OTP to email
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Delete old OTPs for this email
        EmailOtp::where('email', $email)->delete();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save to DB (valid 5 minutes)
        EmailOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send email
        try {
            Mail::raw("Kode verifikasi Allincase Anda: {$otp}\n\nKode ini berlaku selama 5 menit.\nJangan bagikan kode ini kepada siapapun.", function ($message) use ($email) {
                $message->to($email)
                    ->subject('Kode Verifikasi Allincase');
            });

            Log::info("OTP sent to {$email}");
        } catch (\Exception $e) {
            Log::error("Failed to send OTP to {$email}: " . $e->getMessage());
            return response()->json(['detail' => 'Gagal mengirim email. Coba lagi.'], 500);
        }

        return response()->json(['message' => 'OTP berhasil dikirim ke email']);
    }

    /**
     * Verify OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $record = EmailOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('is_verified', false)
            ->first();

        if (!$record) {
            return response()->json(['detail' => 'Kode OTP tidak valid'], 400);
        }

        if ($record->isExpired()) {
            return response()->json(['detail' => 'Kode OTP sudah expired. Silakan kirim ulang.'], 400);
        }

        // Mark as verified
        $record->update(['is_verified' => true]);

        return response()->json(['message' => 'Email berhasil diverifikasi']);
    }
}
