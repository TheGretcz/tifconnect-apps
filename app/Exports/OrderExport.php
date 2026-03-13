<?php

namespace App\Exports;

use App\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderExport
{
    public static function download(): StreamedResponse
    {
        $filename = 'data_order_'.date('Ymd_His').'.csv';

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

            // Data
            Order::orderBy('id')->chunk(500, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->layanan,
                        $order->teritory,
                        $order->paket,
                        $order->no_order,
                        $order->olo,
                        $order->sto,
                        $order->nd,
                        $order->status_order,
                        $order->keterangan,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
