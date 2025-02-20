<?php

namespace App\Services;

use GuzzleHttp\Client;

class LineService
{
    protected $client;
    protected $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->accessToken = env('CHANNEL_ACCESS_TOKEN');
    }

    public function getImage($messageId)
    {
        try {
            $response = $this->client->get("https://api-data.line.me/v2/bot/message/{$messageId}/content", [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                ],
            ]);
        
            if ($response->getStatusCode() === 200) {
                $content = $response->getBody()->getContents();
            } else {
                $content = '';
            }
        } catch (\Exception $e) {
            \Log::error('Get Image Error: ' . $e->getMessage());
            $content = '';
        }
        
        return $content;
    }

    public function sendMessage($userId, $message)
    {
        try {
            $response = $this->client->post('https://api.line.me/v2/bot/message/push', [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $userId,
                    'messages' => [
                        [
                            'type' => 'text',
                            'text' => $message,
                        ],
                    ],
                ],
            ]);

            return $response;
        } catch (\Exception $e) {
            \Log::error('Send message Error (service): ' . $e->getMessage());
            return null;
        }
    }

    
    public function sendImageMessage($userId, $originalContentUrl, $previewImageUrl)
    {
        try {
            \Log::info("Sending Image to LINE:", [
                'originalContentUrl' => $originalContentUrl,
                'previewImageUrl' => $previewImageUrl
            ]);
            $response = $this->client->post('https://api.line.me/v2/bot/message/push', [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $userId,
                    'messages' => [
                        [
                            'type' => 'image',
                            'originalContentUrl' => $originalContentUrl,
                            'previewImageUrl' => $previewImageUrl,
                        ],
                    ],
                ],
            ]);

            return $response;
        } catch (\Exception $e) {
            \Log::error('Send Image Error (service): ' . $e->getMessage());
            return null;
        }
    }

    public function quoteMessage($userId, $message, $quote)
    {
        try {
            $response = $this->client->post('https://api.line.me/v2/bot/message/push', [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $userId,
                    'messages' => [
                        [
                            'type' => 'text',
                            'text' => $message,
                            'quoteToken' => $quote,
                        ],
                    ],
                ],
            ]);
            
            return $response;
        } catch (\Exception $e) {
            \Log::error('Quote message Error (service): ' . $e->getMessage());
            return null;
        }
    }
}