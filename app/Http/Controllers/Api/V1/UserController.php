<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Services\FileService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        if ($request->has('full_name')) {
            $user->full_name = $request->full_name;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        $user->save();

        return response()->json($this->formatUser($user));
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['file' => 'required|file']);

        $user = $request->user();
        $avatarUrl = FileService::saveAvatar($request->file('file'));
        $user->update(['avatar_url' => $avatarUrl]);

        return response()->json($this->formatUser($user));
    }

    public function registerDeviceToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'device_type' => 'sometimes|string|max:20',
        ]);

        $user = $request->user();

        $existing = DeviceToken::where('user_id', $user->id)
            ->where('fcm_token', $request->fcm_token)
            ->first();

        if (!$existing) {
            DeviceToken::create([
                'user_id' => $user->id,
                'fcm_token' => $request->fcm_token,
                'device_type' => $request->device_type ?? 'android',
            ]);
        }

        return response()->noContent();
    }

    public function removeDeviceToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string']);

        $user = $request->user();

        DeviceToken::where('user_id', $user->id)
            ->where('fcm_token', $request->fcm_token)
            ->delete();

        return response()->noContent();
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->update([
            'is_active' => false,
            'email' => "deleted_{$user->id}@deleted.com",
            'phone' => null,
        ]);

        return response()->noContent();
    }

    private function formatUser($user): array
    {
        return [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar_url' => $user->avatar_url,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'created_at' => $user->created_at->toIso8601String(),
        ];
    }
}
