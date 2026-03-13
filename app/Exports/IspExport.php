<?php

namespace App\Exports;

use App\Models\Isp;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IspExport
{
    public static function download(): StreamedResponse
    {
        $filename = 'isp_data_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($handle, [
                'isp_code',
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

            // Data
            Isp::orderBy('isp_code')->chunk(500, function ($isps) use ($handle) {
                foreach ($isps as $isp) {
                    fputcsv($handle, [
                        $isp->isp_code,
                        $isp->isp_brand,
                        $isp->isp_name,
                        $isp->area,
                        $isp->ba,
                        $isp->ca,
                        $isp->sid,
                        $isp->vlan,
                        $isp->layanan,
                        $isp->created_date,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
