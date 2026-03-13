<?php

namespace App\Exports;

use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderTemplateExport
{
    public static function download(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($handle, [
                'LAYANAN',
                'TERITORY',
                'PAKET',
                'NO ORDER',
                'OLO',
                'STO',
                'ND',
                'STATUS ORDER',
                'KETERANGAN',
            ]);

            // Example Row
            fputcsv($handle, [
                'INTERNET',
                '2',
                'PAKET_EXAMPLE',
                'ORD-00001',
                'OLO_EXAMPLE',
                'STO_EXAMPLE',
                'ND_EXAMPLE',
                'IN PROGRESS',
                'Keterangan contoh',
            ]);

            fclose($handle);
        }, 'order_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
