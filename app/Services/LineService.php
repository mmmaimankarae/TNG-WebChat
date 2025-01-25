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
        $response = $this->client->get("https://api-data.line.me/v2/bot/message/{$messageId}/content", [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
            ],
        ]);

        return $response->getBody()->getContents();
    }
}