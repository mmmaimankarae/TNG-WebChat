<?php
namespace App\Http\Controllers\controlMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tasks;
use App\Http\Controllers\controlMainPage\sendMsg;

class controlStatus extends Controller
{
    public function quota(Request $request) {
        $quotaOption = $request->input('quotaOption');
        if ($quotaOption === 'AI') {

        } elseif ($quotaOption === 'image') {
            $request->merge(['select' => 'true']);
            sendMsg::sendMessage($request);
        }
    }
}