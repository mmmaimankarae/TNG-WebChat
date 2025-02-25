<?php

namespace App\Http\Controllers\controlGetInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Nosql;

class amountInfo extends Controller
{
    protected $nosql;

    public function __construct(Nosql $nosql)
    {
        $this->nosql = $nosql;
    }

    private function getCollections($collectionName)
    {
        return $this->nosql->getCollection($collectionName);
    }

    public function amountInfo($taskCode)
    {
        $quota = $this->getQuota($taskCode);
        $documents = [];

        foreach ($quota as $q) {
            $documents = [];
            $quotaInfo = $this->getquotaInfo($q->QuotaCode);
            if ($quotaInfo) {
                $documents[] = [
                    'quotaCode' => $quotaInfo['document_id'],
                    'version' => $quotaInfo['document_version'],
                    'itemsQty' => count($quotaInfo['items']),
                    'amount' => $quotaInfo['total_amount'],
                    'quotaPath' => $q->QuotaPath
                ];
            }
        }

        return $documents;
    }

    private function getQuota($taskCode)
    {
        try {
            return DB::table('QUOTATION')
                ->select(['QuotaCode', 'QuotaPath'])
                ->where('QuotaTaskCode', $taskCode)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Find Error (c.controlGetInfo.getQuota): ' . $e->getMessage());
            return [];
        }
    }

    private function getquotaInfo($quotaCode)
    {
        $collection = $this->getCollections(env('MONGO_COLLECTION_OCR'));

        // แยกค่า document_id และ version จาก quotaCode
        $lastDashPos = strrpos($quotaCode, '-');
        $documentId = substr($quotaCode, 0, $lastDashPos);
        $version = (int) substr($quotaCode, $lastDashPos + 1);

        return $collection->findOne([
            'document_id' => $documentId,
            'document_version' => $version,
            'invoice' => null
        ]);
    }
}

