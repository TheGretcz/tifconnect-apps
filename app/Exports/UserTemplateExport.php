<?php

namespace App\Exports;

class UserTemplateExport
{
    public static function download()
    {
        $filename = 'template_users_admin.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        $handle = fopen('php://output', 'w');

        // CSV Headers
        fputcsv($handle, [
            'role',
            'password',
            'pic_isp',
            'isp_brand',
            'isp_name',
            'area',
        ]);

        // Example Row
        fputcsv($handle, [
            'ISP',
            'TIFConnect2026',
            'John Doe',
            'MAXINDO',
            'Maxindo Jaya',
            'JABODETABEK',
        ]);

        fclose($handle);
        exit;
    }
}
