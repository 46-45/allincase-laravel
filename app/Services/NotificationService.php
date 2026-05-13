<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private static $firebaseApp = null;
    private static $initialized = false;

    /**
     * Save notification to DB and send FCM push notification.
     */
    public static function sendPushNotification(
        int $userId,
        string $title,
        string $body,
        ?string $notifType = null,
        ?int $refId = null,
        array $data = []
    ): void {
        // Save to DB
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
            'type' => $notifType,
            'ref_id' => $refId,
        ]);

        // Send FCM
        self::sendFcm($userId, $title, $body, $data);
    }

    private static function sendFcm(int $userId, string $title, string $body, array $data): void
    {
        $credPath = config('services.firebase.credentials_path');

        if (!$credPath || !file_exists($credPath)) {
            Log::warning("Firebase credentials not found. Push notifications disabled.");
            return;
        }

        try {
            $tokens = DeviceToken::where('user_id', $userId)->pluck('fcm_token')->toArray();

            if (empty($tokens)) {
                return;
            }

            // Use Google API to send FCM
            $accessToken = self::getFirebaseAccessToken($credPath);
            if (!$accessToken) {
                return;
            }

            $projectId = self::getProjectId($credPath);

            foreach ($tokens as $fcmToken) {
                self::sendFcmV1($accessToken, $projectId, $fcmToken, $title, $body, $data);
            }
        } catch (\Exception $e) {
            Log::error("FCM send error for user {$userId}: " . $e->getMessage());
        }
    }

    private static function getProjectId(string $credPath): string
    {
        $cred = json_decode(file_get_contents($credPath), true);
        return $cred['project_id'] ?? '';
    }

    private static function getFirebaseAccessToken(string $credPath): ?string
    {
        try {
            $cred = json_decode(file_get_contents($credPath), true);

            $now = time();
            $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $payload = base64_encode(json_encode([
                'iss' => $cred['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ]));

            $signature = '';
            openssl_sign(
                "{$header}.{$payload}",
                $signature,
                $cred['private_key'],
                OPENSSL_ALGO_SHA256
            );
            $signature = base64_encode($signature);

            $jwt = "{$header}.{$payload}.{$signature}";

            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Firebase auth error: " . $e->getMessage());
            return null;
        }
    }

    private static function sendFcmV1(string $accessToken, string $projectId, string $token, string $title, string $body, array $data): void
    {
        try {
            $strData = [];
            foreach ($data as $k => $v) {
                $strData[$k] = (string) $v;
            }

            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => $strData,
                        'android' => [
                            'priority' => 'high',
                            'notification' => [
                                'sound' => 'default',
                                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            ],
                        ],
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning("FCM send failed for token: " . substr($token, 0, 20) . "...");
            }
        } catch (\Exception $e) {
            Log::error("FCM V1 send error: " . $e->getMessage());
        }
    }
}
