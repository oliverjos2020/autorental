<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class FirebaseNotificationService
{
    protected $projectId;
    protected $credentialsPath;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->credentialsPath = env('FIREBASE_CREDENTIALS_PATH');
    }

    /**
     * Send notification via Firebase Cloud Messaging
     *
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendNotification($fcmToken, $title, $body, $data = [])
    {
        try {
            if (!$fcmToken) {
                Log::warning('FCM token is empty');
                return false;
            }

            $accessToken = $this->getAccessToken();
            
            if (!$accessToken) {
                Log::error('Failed to get Firebase access token');
                return false;
            }

            $message = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        ]
                    ],
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10',
                        ],
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                                'badge' => 1,
                            ]
                        ]
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post(
                "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
                $message
            );

            if ($response->successful()) {
                Log::info('FCM notification sent successfully', ['token' => substr($fcmToken, 0, 20) . '...']);
                return true;
            } else {
                Log::error('FCM notification failed', ['response' => $response->json()]);
                return false;
            }
        } catch (Exception $e) {
            Log::error('FCM notification exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to multiple tokens
     *
     * @param array $fcmTokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return int Number of successful sends
     */
    public function sendNotificationToMultiple($fcmTokens, $title, $body, $data = [])
    {
        $successCount = 0;
        
        foreach ($fcmTokens as $token) {
            if ($this->sendNotification($token, $title, $body, $data)) {
                $successCount++;
            }
        }

        return $successCount;
    }

    /**
     * Get Firebase access token using service account credentials
     *
     * @return string|null
     */
    protected function getAccessToken()
    {
        try {
            $credentialsPath = storage_path('app/' . $this->credentialsPath);
            
            if (!file_exists($credentialsPath)) {
                Log::error('Firebase credentials file not found at: ' . $credentialsPath);
                return null;
            }

            $credentials = json_decode(file_get_contents($credentialsPath), true);

            $now = time();
            $payload = [
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/cloud-platform',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ];

            $jwt = $this->createJWT($payload, $credentials['private_key']);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('Failed to get access token', ['response' => $response->json()]);
            return null;
        } catch (Exception $e) {
            Log::error('Error getting Firebase access token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create JWT token for Firebase authentication
     *
     * @param array $payload
     * @param string $privateKey
     * @return string
     */
    protected function createJWT($payload, $privateKey)
    {
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        $signatureInput = $headerEncoded . '.' . $payloadEncoded;

        openssl_sign($signatureInput, $signature, $privateKey, 'SHA256');
        $signatureEncoded = $this->base64UrlEncode($signature);

        return $signatureInput . '.' . $signatureEncoded;
    }

    /**
     * Base64 URL encode
     *
     * @param string $data
     * @return string
     */
    protected function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
