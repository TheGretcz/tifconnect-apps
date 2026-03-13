<?php

namespace App\Exports;

use App\Models\Area;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AreaExport
{
    public static function download(): StreamedResponse
    {
        $filename = 'area_data_'.date('Ymd_His').'.csv';

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

            // Data
            Area::orderBy('sto')->chunk(500, function ($areas) use ($handle) {
                foreach ($areas as $area) {
                    fputcsv($handle, [
                        $area->sto,
                        $area->nama_sto,
                        $area->area,
                        $area->regional,
                        $area->branch,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
