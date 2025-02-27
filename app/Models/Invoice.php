<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Nosql;

class Invoice extends Model
{   
    private $nosql;

    public function __construct() {
        $this->nosql = new Nosql();
    }

    public function setInvoice(Request $request) {
        try {
            $shipLoCode = $this->setShipLocation($request);

            $quotaCode = null;
            if($request->invoiceQuotaCode) {
                $quotaCode = $request->invoiceQuotaCode . '-' . $request->quotaVersion;
            }

            $insert = DB::table('INVOICE')->insert([
                'InvoiceCode' => $request->invoiceCode,
                'InvoiceTaskCode' => $request->invoiceTaskCode,
                'InvoiceQuotaCode' => $quotaCode,
                'InvoiceCusCode' => $request->invoiceCusCode,
                'InvoiceEmpCode' => $request->invoiceEmpCode,
                'InvoiceCreated' => Carbon::now(),
                'InvoiceShipLoCode' => $shipLoCode,
                'InvoiceShipTypeCode' => $request->shipOption,
                'InvoiceShipDate' => $request->invoiceShipDate,
                'InvoiceReceiverName' => $request->invoiceReceiverName,
                'InvoiceReceiverPhone' => $request->invoiceReceiverPhone,
                'InvoiceWeight' => $request->invoiceWeight,
                'InvoiceNote' => $request->invoiceNote,
                'InvoiceValue' => $request->invoiceValue,
                'InvoiceForkLift' => $request->invoiceForkLift,
            ]);

            if($insert) {
                $this->setInvoiceProdList($request);
            }
        } catch (\Exception $e) {
            \Log::error('Insert Error (m.Invoice): ' . $e->getMessage());
            return false;
        }
    }

    private function setShipLocation(Request $request) {
        try {
            $setLo = true;
            $max = 800;
            $lat = $request->shipLoLat;
            $long = $request->shipLoLong;
            if($lat && $long) {
                $setLo = $this->checkLocation($lat && $long);
            }

            if($setLo === true) {
                if($request->shipAddr > $max) {
                    $addr = substr($request->shipAddr, 0, $max);
                    $addr2 = substr($request->shipAddr, $max);
                }

                $insert = DB::table('SHIP_LOCATION')->insertGetId([
                    'ShipLoLat' => $request->ShipLoLat,
                    'ShipLoLong' => $request->ShipLoLong,
                    'ShipLoAddr' => $addr ?? null,
                    'ShipLoAddr2' => $addr2 ?? null,
                ]);
            } else {
                $insert = $setLo;
            }
            return $insert;
        } catch (\Exception $e) {
            \Log::error('Insert Error (m.Invoice): ' . $e->getMessage());
            return false;
        }
    }

    private function checkLocation($long, $lat) {
        try {
            $check = DB::table('SHIP_LOCATION')
                ->where('ShipLoLat', $lat)
                ->where('ShipLoLong', $long)
                ->first();

            if($check) {
                return $check->ShipLoCode;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Check Error (m.Invoice): ' . $e->getMessage());
            return false;
        }
    }

    private function setInvoiceProdList(Request $request) {
        try {
            $productList = $this->getProductList($request->invoiceQuotaCode, $request->quotaVersion);
            if($productList) {
                $seq = 1;
                foreach($productList as $product) {
                    $insert = DB::table('INVOICE_PRODUCT_LIST')->insert([
                        'InvoiceProdListInvoiceCode' => $request->invoiceCode,
                        'InvoiceProdListSeq' => $seq++,
                        'Quantity' => $product['quantity'],
                        'InvoiceProdListProdCode' => $product['code'],
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Insert Error (m.Invoice): ' . $e->getMessage());
            return false;
        }
    }

    private function getProductList($documentId, $documentVersion) {
        try {
            $productList = $this->nosql->findMessages(env('MONGO_COLLECTION_OCR'), 
                ['document_id' => $documentId, 'document_version' => (int) $documentVersion]);
            return $productList[0]['items'];
        } catch (\Exception $e) {
            \Log::error('Find Error (m.Invoice): ' . $e->getMessage());
            return false;
        }
    }
}