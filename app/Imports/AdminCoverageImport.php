<?php

namespace App\Imports;

use App\Models\CoverageRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class AdminCoverageImport
{
    public static function import(UploadedFile $file): void
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

        $requiredHeaders = ['brand', 'isp_name', 'area', 'layanan', 'paket', 'phone', 'customer_name', 'customer_address', 'longlat', 'bandwidth'];
        foreach ($requiredHeaders as $required) {
            if (! in_array($required, $headers)) {
                fclose($handle);
                throw new \Exception("Header '{$required}' tidak ditemukan dalam file CSV.");
            }
        }

        $imported = 0;
        $userId = Auth::id();

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $row = array_pad($row, count($headers), '');
            $data = array_combine($headers, $row);

            // Logic: Create or update based on (kode_pra if exists) or just create new
            // For admin import, we usually allow full control.

            CoverageRequest::create([
                'user_id' => $userId,
                'brand' => $data['brand'] ?? '',
                'isp_name' => $data['isp_name'] ?? '',
                'area' => $data['area'],
                'layanan' => $data['layanan'],
                'paket' => $data['paket'],
                'phone' => $data['phone'],
                'cust_name' => $data['customer_name'],
                'cust_add' => $data['customer_address'],
                'longlat' => $data['longlat'],
                'bandwidth' => $data['bandwidth'],
                'kode_pra' => $data['kode_pra'] ?? null,
                'status' => $data['status'] ?? 'PROCESSING',
                'odp' => $data['odp'] ?? null,
                'gpon' => $data['gpon'] ?? null,
            ]);
            $imported++;
        }

        fclose($handle);

        if ($imported === 0) {
            throw new \Exception('Tidak ada data valid yang ditemukan dalam file.');
        }
    }
}
