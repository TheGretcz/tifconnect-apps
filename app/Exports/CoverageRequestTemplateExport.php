<?php

namespace App\Exports;

use Symfony\Component\HttpFoundation\StreamedResponse;

class CoverageRequestTemplateExport
{
    public static function download(): StreamedResponse
    {
        $filename = 'template_coverage_requests.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'area',
                'layanan',
                'paket',
                'phone',
                'customer_name',
                'customer_address',
                'longlat',
                'bandwidth',
            ]);

            // Example row
            fputcsv($handle, [
                'JABODETABEK',
                'Bitstream',
                'Standar',
                '08123456789',
                'John Doe',
                'Jl. Contoh No. 123',
                '-6.123456, 106.123456',
                '100 Mbps',
            ]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
