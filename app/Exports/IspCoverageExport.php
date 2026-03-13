<?php

namespace App\Exports;

use App\Models\CoverageRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IspCoverageExport
{
    public static function download(int $userId): StreamedResponse
    {
        $filename = 'coverage_requests_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($userId) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Brand',
                'ISP Name',
                'Area',
                'Layanan',
                'Paket',
                'Kode PRA',
                'Phone',
                'Customer Name',
                'Customer Address',
                'Long/Lat',
                'Bandwidth',
                'ODP',
                'GPON',
                'Status',
                'Tanggal',
            ]);

            $no = 0;
            CoverageRequest::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->chunk(500, function ($requests) use ($handle, &$no) {
                    foreach ($requests as $req) {
                        $no++;
                        fputcsv($handle, [
                            $no,
                            $req->brand,
                            $req->isp_name,
                            $req->area,
                            $req->layanan,
                            $req->paket,
                            $req->kode_pra,
                            $req->phone,
                            $req->cust_name,
                            $req->cust_add,
                            $req->longlat,
                            $req->bandwidth,
                            $req->odp,
                            $req->gpon,
                            $req->status,
                            $req->created_at->format('d/m/Y'),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
