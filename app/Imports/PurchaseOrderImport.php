<?php

namespace App\Imports;

use App\Models\PurchaseOrder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderImport
{
    public static function import(UploadedFile $file): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            throw new \Exception('Tidak bisa membuka file.');
        }

        // Read header row
        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            throw new \Exception('File CSV kosong.');
        }

        // Trim BOM and whitespace from headers
        $headers = array_map(function ($h) {
            return strtolower(trim(preg_replace('/[\x{FEFF}]/u', '', $h)));
        }, $headers);

        // ISP Name is now automated based on brand
        $requiredHeaders = ['brand', 'cust_name', 'cust_add', 'phone'];
        foreach ($requiredHeaders as $required) {
            if (! in_array($required, $headers)) {
                fclose($handle);
                throw new \Exception("Header '{$required}' tidak ditemukan dalam file CSV.");
            }
        }

        $results = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'failed' => 0, 'total_rows' => 0];
        $imported = 0;
        $lineNumber = 1;

        // Preload ISP names into a map for efficiency
        $ispMap = \App\Models\Isp::pluck('isp_name', 'isp_brand')->toArray();

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;
            $results['total_rows']++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                $results['skipped']++;

                continue;
            }

            try {
                // Ensure column counts match headers exactly
                if (count($row) > count($headers)) {
                    $row = array_slice($row, 0, count($headers));
                } elseif (count($row) < count($headers)) {
                    $row = array_pad($row, count($headers), '');
                }

                $data = array_combine($headers, $row);

                // Trim all data values
                $data = array_map(function ($value) {
                    return trim($value);
                }, $data);

                // Validation
                if (strlen($data['cust_name']) === 0 || strlen($data['phone']) === 0 || strlen($data['brand']) === 0) {
                    $results['skipped']++;

                    continue;
                }

                $noOrder = ! empty($data['no_order']) ? $data['no_order'] : PurchaseOrder::generateNoOrder();
                $ispName = $ispMap[$data['brand']] ?? '-';

                if (! empty($data['no_order'])) {
                    $matchAttributes = ['no_order' => $data['no_order']];
                } else {
                    $matchAttributes = [
                        'cust_name' => $data['cust_name'],
                        'phone' => $data['phone'],
                        'brand' => $data['brand'],
                        'po_number' => $data['po_number'] ?: '-',
                        'kode_pra' => $data['kode_pra'] ?: '-',
                    ];
                }

                $po = PurchaseOrder::updateOrCreate(
                    $matchAttributes,
                    [
                        'user_id' => Auth::id(),
                        'po_number' => $data['po_number'] ?? null,
                        'no_order' => $noOrder,
                        'kode_pra' => $data['kode_pra'] ?? null,
                        'brand' => $data['brand'],
                        'isp_name' => $ispName,
                        'area' => ! empty($data['area']) ? $data['area'] : '-',
                        'layanan' => ! empty($data['layanan']) ? $data['layanan'] : '-',
                        'paket' => ! empty($data['paket']) ? $data['paket'] : '-',
                        'cust_name' => $data['cust_name'],
                        'cust_add' => $data['cust_add'],
                        'phone' => $data['phone'],
                        'longlat' => $data['longlat'] ?? null,
                        'bandwidth' => $data['bandwidth'] ?? null,
                        'odp' => $data['odp'] ?? null,
                        'gpon' => $data['gpon'] ?? null,
                        'sto' => $data['wilayah'] ?? $data['sto'] ?? null,
                        'branch' => $data['branch'] ?? null,
                        'regional' => $data['regional'] ?? null,
                        'admin_no_order' => $data['admin_no_order'] ?? null,
                        'admin_no_order_input' => $data['admin_no_order_input'] ?? null,
                        'reason_cancel' => $data['reason_cancel'] ?? null,
                        'category_cancel' => $data['category_cancel'] ?? null,
                    ]
                );

                if ($po->wasRecentlyCreated) {
                    $results['created']++;
                } else {
                    $results['updated']++;
                }
                $imported++;

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Import Error at row $lineNumber: ".$e->getMessage());
                $results['failed']++;

                continue;
            }
        }

        fclose($handle);

        if ($imported === 0 && $results['failed'] > 0) {
            throw new \Exception('Gagal mengimpor data. Ada '.$results['failed'].' baris yang error. Cek logs.');
        }

        if ($imported === 0 && $results['skipped'] > 0) {
            throw new \Exception('Semua baris ('.$results['skipped'].') dilewati karena data tidak lengkap (Name, Phone, atau Brand kosong).');
        }

        if ($imported === 0) {
            throw new \Exception('Tidak ada data valid yang ditemukan dalam file.');
        }

        return $results;
    }
}
