<?php

namespace App\Exports;

use App\Models\CoverageRequest;

class CoverageExport
{
    public static function download()
    {
        $filename = 'all_coverage_requests_'.date('Y-m-d_His').'.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        $handle = fopen('php://output', 'w');

        // CSV Headers
        fputcsv($handle, [
            'ID',
            'Kode PRA',
            'Brand',
            'ISP Name',
            'Pelanggan',
            'Alamat',
            'Phone',
            'Longlat',
            'Layanan',
            'Paket',
            'Bandwidth',
            'Area',
            'ODP',
            'GPON',
            'Status',
            'Created At',
        ]);

        $requests = CoverageRequest::orderBy('created_at', 'desc')->get();

        foreach ($requests as $req) {
            fputcsv($handle, [
                $req->req_id,
                $req->kode_pra,
                $req->brand,
                $req->isp_name,
                $req->cust_name,
                $req->cust_add,
                $req->phone,
                $req->longlat,
                $req->layanan,
                $req->paket,
                $req->bandwidth,
                $req->area,
                $req->odp,
                $req->gpon,
                $req->status,
                $req->created_at,
            ]);
        }

        fclose($handle);
        exit;
    }
}
