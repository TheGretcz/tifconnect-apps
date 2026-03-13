<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IspPurchaseOrderExport
{
    public static function download(int $userId): StreamedResponse
    {
        $filename = 'my_purchase_orders_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($userId) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'PO ID',
                'PO Number',
                'Kode PRA',
                'Brand',
                'ISP Name',
                'Customer',
                'Address',
                'Phone',
                'Longlat',
                'Area',
                'Layanan',
                'Bandwidth',
                'ODP',
                'GPON',
                'STO',
                'Branch',
                'Regional',
                'No Order Admin',
                'No Order Input Admin',
                'Reason Cancel',
                'Category Cancel',
                'Tanggal',
            ]);

            $no = 0;
            PurchaseOrder::with('areaInfo')->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->chunk(500, function ($pos) use ($handle, &$no) {
                    foreach ($pos as $po) {
                        $no++;
                        fputcsv($handle, [
                            $no,
                            $po->no_order,
                            $po->po_number,
                            $po->kode_pra,
                            $po->brand,
                            $po->isp_name,
                            $po->cust_name,
                            $po->cust_add,
                            $po->phone,
                            $po->longlat,
                            $po->area,
                            $po->layanan,
                            $po->bandwidth,
                            $po->odp,
                            $po->gpon,
                            $po->sto,
                            $po->areaInfo->branch ?? '-',
                            $po->areaInfo->regional ?? '-',
                            $po->admin_no_order,
                            $po->admin_no_order_input,
                            $po->reason_cancel,
                            $po->category_cancel,
                            $po->created_at->format('d/m/Y'),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
