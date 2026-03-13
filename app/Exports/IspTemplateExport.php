<?php

namespace App\Exports;

use Symfony\Component\HttpFoundation\StreamedResponse;

class IspTemplateExport
{
    public static function download(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'isp_brand',
                'isp_name',
                'area',
                'ba',
                'ca',
                'sid',
                'vlan',
                'layanan',
                'created_date',
            ]);

            // Example rows
            for ($i = 1; $i <= 5; $i++) {
                fputcsv($handle, [
                    'Brand '.$i,
                    'ISP Name '.$i,
                    $i % 2 == 0 ? 'JABAR' : 'JABODETABEK',
                    'BA-'.(1000 + $i),
                    'CA-'.(2000 + $i),
                    'SID-'.(3000 + $i),
                    'VLAN-'.(4000 + $i),
                    'Bitstream',
                    '2026-02-24',
                ]);
            }

            fclose($handle);
        }, 'isp_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
