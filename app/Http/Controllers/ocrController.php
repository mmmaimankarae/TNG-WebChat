<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OcrController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
            'quotaOption' => 'required|string',
        ]);

        // อัปโหลดไฟล์ไปยัง Storage
        $file = $request->file('file');

        // กำหนด API ของ Python ที่ใช้ OCR
        $pythonOcrApiUrl = 'http://127.0.0.1:3500/ocr';

        // ส่งไฟล์ไปให้ Python
        try {
            $response = Http::attach(
                'file', file_get_contents($file->getPathname()), $file->getClientOriginalName()
            )->post($pythonOcrApiUrl, [
                'quotaOption' => $request->quotaOption,
                'replyId' => $request->replyId,
                'replyName' => $request->replyName,
                'cusCode' => $request->cusCode,
                'taskCode' => $request->taskCode,
                'userId' => $request->userId,
                'userName' => $request->userName,
                'taskStatus' => $request->taskStatus,
                'branchCode' => $request->branchCode,
            ]);

            // ตรวจสอบสถานะการตอบกลับ
            if ($response->successful() && $response->json('status') == 'success') {
                return redirect()->back()->withInput()->with('select', true);
            } else {
                return redirect()->back()->withErrors(['quotaError' => 'เกิดข้อผิดพลาด โปรดลองใหม่อีกครั้ง'])->withInput()->with('select', true);
            }
        } catch (\Exception $e) {
            //dd($e->getMessage());
            \Log::error('Error occurred while sending file to Python: ' . $e->getMessage());
            return redirect()->back()->withErrors(['quotaError' => 'เกิดข้อผิดพลาด โปรดลองใหม่อีกครั้ง'])->withInput()->with('select', true);
        }
    }
}
