<?php

namespace App\Exports;

class CoverageTemplateExport
{
    public static function download()
    {
        $filename = 'template_coverage_admin.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        $handle = fopen('php://output', 'w');

        // CSV Headers (Required for import)
        fputcsv($handle, [
            'brand',
            'isp_name',
            'area',
            'layanan',
            'paket',
            'phone',
            'customer_name',
            'customer_address',
            'longlat',
            'bandwidth',
            'kode_pra',
            'status',
            'odp',
            'gpon',
        ]);

        // Example Row
        fputcsv($handle, [
            'TELKOM',
            'Telkom Indonesia',
            'JABODETABEK',
            'Vula',
            'Standar',
            '08123456789',
            'Ahmad Fauzi',
            'Jl. Merdeka No. 10',
            '-6.1234, 106.1234',
            '50 Mbps',
            'TELKOM0001',
            'PROCESSING',
            'ODP-JKT-01',
            'GPON-01',
        ]);

        fclose($handle);
        exit;
    }
}
