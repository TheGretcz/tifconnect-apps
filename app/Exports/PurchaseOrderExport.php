<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PurchaseOrderExport
{
    public static function download(): StreamedResponse
    {
        $filename = 'purchase_orders_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'No Order (System)',
                'PO Number',
                'Kode PRA',
                'Brand',
                'ISP Name',
                'Area',
                'Layanan',
                'Paket',
                'Customer Name',
                'Address',
                'Phone',
                'Longlat',
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
                'Status Order',
                'Keterangan',
                'Tanggal',
            ]);

            $no = 0;
            PurchaseOrder::with('areaInfo')
                ->leftJoin('orders', 'purchase_orders.admin_no_order', '=', 'orders.no_order')
                ->select('purchase_orders.*', 'orders.status_order as order_status_val', 'orders.keterangan as order_keterangan_val')
                ->orderBy('purchase_orders.created_at', 'desc')
                ->chunk(500, function ($pos) use ($handle, &$no) {
                    foreach ($pos as $po) {
                        $no++;
                        fputcsv($handle, [
                            $no,
                            $po->no_order,
                            $po->po_number,
                            $po->kode_pra,
                            $po->brand,
                            $po->joined_isp_name ?? $po->isp_name,
                            $po->area,
                            $po->layanan,
                            $po->paket,
                            $po->cust_name,
                            $po->cust_add,
                            $po->phone,
                            $po->longlat,
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
                            $po->order_status_val ?? '-',
                            $po->order_keterangan_val ?? '-',
                            $po->created_at->format('d/m/Y H:i'),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
