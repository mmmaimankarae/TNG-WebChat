<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Tasks;
use App\Http\Controllers\controlGetInfo\tasksInfo;

class ApiController extends Controller
{
    private Tasks $tasksModel;
    
    public function __construct(Tasks $tasksModel)
    {
        $this->tasksModel = $tasksModel;
    }

    public function quotation(Request $request)
    {
        $empCode = $request->input('userName');
        $taskCode = $request->input('taskCode');
        if ($empCode !== tasksInfo::getLastEmp($taskCode)) {
            $this->tasksModel->updateStatus($taskCode, $taskStatus, $empCode);
        }

        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
            'quotaOption' => 'required|string',
        ]);

        $file = $request->file('file');

        /* กำหนด API ของ Python ที่ใช้ OCR */
        $apiUrl = 'http://127.0.0.1:3500/ocr';

        try {
            $response = Http::attach(
                'file', file_get_contents($file->getPathname()), $file->getClientOriginalName()
            )->post($apiUrl, [
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
            \Log::error('Error occurred while sending file to Python: ' . $e->getMessage());
            return redirect()->back()->withErrors(['quotaError' => 'เกิดข้อผิดพลาด โปรดลองใหม่อีกครั้ง'])->withInput()->with('select', true);
        }
    }

    public function uploadImages(Request $request)
    {   
        if ($request->input('file_path')) {
            $path = $request->input('file_path');
            $file_path = (Storage::path($path));
        } else {
            /* อัปโหลดไฟล์ไปยัง Storage */
            $file = $request->file('file');
            $path = $file->store('public/uploads/' . $request->input('branchCode'));
            $file_path = (Storage::path($path));
        }

        /* กำหนด API ของ Python ที่ใช้ OCR */
        $apiUrl = 'http://127.0.0.1:3500/send-image';

        try {
            //dd($file_path, $request->replyId, $request->replyName, $request->cusCode, $request->taskCode, $request->userId, $request->userName, $request->taskStatus, $request->branchCode);
            $response = Http::asForm()->post($apiUrl, [
                'file_path' => $file_path,
                'replyId' => $request->replyId,
                'replyName' => $request->replyName,
                'cusCode' => $request->cusCode,
                'taskCode' => $request->taskCode,
                'userId' => $request->userId,
                'userName' => $request->userName,
                'taskStatus' => $request->taskStatus,
                'branchCode' => $request->branchCode,
                'messageType' => $request->messageType,
            ]);

            // ตรวจสอบสถานะการตอบกลับ
            if ($response->successful() && $response->json('status') == 'success') {
                return redirect()->back()->withInput()->with('select', true);
            } else {
                return redirect()->back()->withErrors(['quotaError' => 'เกิดข้อผิดพลาด โปรดลองใหม่อีกครั้ง'])->withInput()->with('select', true);
            }
        } catch (\Exception $e) {
            \Log::error('Error occurred while sending file to Python: ' . $e->getMessage());
            return redirect()->back()->withErrors(['quotaError' => 'เกิดข้อผิดพลาด โปรดลองใหม่อีกครั้ง'])->withInput()->with('select', true);
        }
    }
}
