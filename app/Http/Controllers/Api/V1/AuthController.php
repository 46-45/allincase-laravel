<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:15',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['detail' => $validator->errors()->first()], 422);
        }

        // Check email uniqueness
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['detail' => 'Email sudah terdaftar'], 409);
        }

        // Check phone uniqueness
        $phone = preg_replace('/\D/', '', $request->phone);
        if (User::where('phone', $phone)->exists()) {
            return response()->json(['detail' => 'Nomor telepon sudah terdaftar'], 409);
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'is_active' => true,
        ]);

        return $this->createTokenResponse($user, 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['detail' => $validator->errors()->first()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['detail' => 'Email atau password salah'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['detail' => 'Akun tidak aktif. Hubungi admin.'], 401);
        }

        return $this->createTokenResponse($user);
    }

    public function refresh(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['detail' => $validator->errors()->first()], 422);
        }

        $rt = RefreshToken::where('token', $request->refresh_token)
            ->where('is_revoked', false)
            ->first();

        if (!$rt) {
            return response()->json(['detail' => 'Refresh token tidak ditemukan atau sudah dicabut'], 401);
        }

        if ($rt->expires_at->isPast()) {
            return response()->json(['detail' => 'Refresh token sudah expired'], 401);
        }

        $user = User::find($rt->user_id);
        if (!$user || !$user->is_active) {
            return response()->json(['detail' => 'User tidak valid'], 401);
        }

        // Revoke old token
        $rt->update(['is_revoked' => true]);

        return $this->createTokenResponse($user);
    }

    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['detail' => $validator->errors()->first()], 422);
        }

        RefreshToken::where('token', $request->refresh_token)
            ->update(['is_revoked' => true]);

        return response()->noContent();
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar_url' => $user->avatar_url,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'created_at' => $user->created_at->toIso8601String(),
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['detail' => $validator->errors()->first()], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['detail' => 'Password lama tidak sesuai'], 400);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->noContent();
    }

    private function createTokenResponse(User $user, int $statusCode = 200)
    {
        $accessToken = JWTAuth::fromUser($user);

        // Create refresh token
        $refreshTokenStr = Str::random(64);
        $expiresAt = Carbon::now()->addDays(config('jwt.refresh_ttl', 7));

        RefreshToken::create([
            'user_id' => $user->id,
            'token' => $refreshTokenStr,
            'expires_at' => $expiresAt,
            'is_revoked' => false,
        ]);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshTokenStr,
            'token_type' => 'bearer',
        ], $statusCode);
    }
}
