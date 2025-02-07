<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LineService;

class sendMsg extends Controller
{
    private $lineService;

    public function __construct(LineService $lineService)
    {
        $this->lineService = $lineService;
    }

    public function sendMessage(Request $request)
    {
        $userId = $request->input('userId');
        $message = $request->input('message');

        $response = $this->lineService->sendMessage($userId, $message);

        if ($response->isSucceeded()) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => $response->getRawBody()], 500);
        }
    }
}