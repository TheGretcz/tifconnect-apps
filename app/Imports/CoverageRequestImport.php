<?php

namespace App\Imports;

use App\Models\CoverageRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class CoverageRequestImport
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

        $requiredHeaders = ['area', 'layanan', 'paket', 'phone', 'customer_name', 'customer_address', 'longlat', 'bandwidth'];
        foreach ($requiredHeaders as $required) {
            if (! in_array($required, $headers)) {
                fclose($handle);
                throw new \Exception("Header '{$required}' tidak ditemukan dalam file CSV.");
            }
        }

        $user = Auth::user();
        $imported = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $row = array_pad($row, count($headers), '');
            $data = array_combine($headers, $row);

            // Re-generate kode_pra for each row using the same logic as manual creation
            $kode_pra = self::generateKodePra($user->isp_brand);

            CoverageRequest::create([
                'user_id' => $user->user_id,
                'brand' => $user->isp_brand ?? '',
                'isp_name' => $user->isp_name ?? '',
                'area' => $data['area'],
                'layanan' => $data['layanan'],
                'paket' => $data['paket'],
                'phone' => $data['phone'],
                'cust_name' => $data['customer_name'],
                'cust_add' => $data['customer_address'],
                'longlat' => $data['longlat'],
                'bandwidth' => $data['bandwidth'],
                'kode_pra' => $kode_pra,
                'status' => 'PROCESSING',
            ]);
            $imported++;
        }

        fclose($handle);

        if ($imported === 0) {
            throw new \Exception('Tidak ada data valid yang ditemukan dalam file.');
        }
    }

    private static function generateKodePra(string $brand): string
    {
        $brand = strtoupper(str_replace(' ', '', $brand ?? 'ISP'));

        $lastRequest = CoverageRequest::where('kode_pra', 'like', $brand.'%')
            ->orderByRaw('LENGTH(kode_pra) DESC, kode_pra DESC')
            ->first();

        $nextNumber = 1;
        if ($lastRequest && $lastRequest->kode_pra) {
            $numPart = preg_replace('/^'.preg_quote($brand, '/').'/', '', $lastRequest->kode_pra);
            if (is_numeric($numPart)) {
                $nextNumber = (int) $numPart + 1;
            }
        }

        return $brand.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
