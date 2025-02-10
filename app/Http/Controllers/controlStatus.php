<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Http\Controllers\sendMsg;

class controlStatus extends Controller
{
    public function quota(Request $req) {
        $quotaOption = $req->input('quotaOption');
        if ($quotaOption === 'AI') {

        } elseif ($quotaOption === 'image') {
            $req->merge(['select' => 'true']);
            sendMsg::sendMessage($req);
        }
    }
}