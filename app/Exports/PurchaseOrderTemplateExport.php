<?php

namespace App\Exports;

use Symfony\Component\HttpFoundation\StreamedResponse;

class PurchaseOrderTemplateExport
{
    public static function download(): StreamedResponse
    {
        $filename = 'template_purchase_orders.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'po_number',
                'kode_pra',
                'brand',
                'cust_name',
                'cust_add',
                'phone',
                'longlat',
                'area',
                'layanan',
                'paket',
                'bandwidth',
                'odp',
                'wilayah',
                'branch',
                'regional',
                'gpon',
                'admin_no_order',
                'admin_no_order_input',
                'reason_cancel',
                'category_cancel',
            ]);

            // Example rows
            for ($i = 1; $i <= 5; $i++) {
                fputcsv($handle, [
                    $i,
                    'PO-NUM-'.(1000 + $i),
                    'PRA-X-'.$i,
                    'BRAND_X',
                    'Customer '.$i,
                    'Jl. Contoh Alamat No. '.$i,
                    '0812345678'.$i,
                    '-6.123, 106.123',
                    'JABODETABEK',
                    'Bitstream',
                    'Retail '.$i,
                    '100 Mbps',
                    'ODP-XXX-0'.$i,
                    'JTN',
                    'BEKASI',
                    'JABOTABEK',
                    'GPON-XXX-0'.$i,
                    'ADM-00'.$i,
                    'INPUT-00'.$i,
                    '',
                    '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
