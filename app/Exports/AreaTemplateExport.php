<?php

namespace App\Exports;

use Symfony\Component\HttpFoundation\StreamedResponse;

class AreaTemplateExport
{
    public static function download(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($handle, [
                'STO',
                'NAMA STO',
                'AREA',
                'REGIONAL',
                'BRANCH',
            ]);

            // Example Row
            fputcsv($handle, [
                'STO_EXAMPLE',
                'NAMA_STO_EXAMPLE',
                'AREA_EXAMPLE',
                'REGIONAL_EXAMPLE',
                'BRANCH_EXAMPLE',
            ]);

            fclose($handle);
        }, 'area_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
