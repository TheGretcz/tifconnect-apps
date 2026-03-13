<?php

namespace App\Imports;

use App\Models\Isp;
use Illuminate\Http\UploadedFile;

class IspImport
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

        // isp_code is now automated
        $requiredHeaders = ['isp_brand', 'isp_name', 'area'];
        foreach ($requiredHeaders as $required) {
            if (! in_array($required, $headers)) {
                fclose($handle);
                throw new \Exception("Header '{$required}' tidak ditemukan dalam file CSV.");
            }
        }

        $imported = 0;
        $lineNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Handle cases where row has fewer columns than headers
            $row = array_pad($row, count($headers), '');
            $data = array_combine($headers, $row);

            if (empty($data['isp_brand']) || empty($data['isp_name']) || empty($data['area'])) {
                continue;
            }

            // Check if ISP brand already exists
            $isp = Isp::where('isp_brand', $data['isp_brand'])->first();

            if ($isp) {
                // Update existing
                $isp->update([
                    'isp_name' => $data['isp_name'],
                    'area' => $data['area'],
                    'ba' => $data['ba'] ?? $isp->ba,
                    'ca' => $data['ca'] ?? $isp->ca,
                    'sid' => $data['sid'] ?? $isp->sid,
                    'vlan' => $data['vlan'] ?? $isp->vlan,
                    'layanan' => $data['layanan'] ?? $isp->layanan,
                    'created_date' => ! empty(trim($data['created_date'] ?? '')) ? $data['created_date'] : $isp->created_date,
                ]);
            } else {
                // Create new with auto-code
                Isp::create([
                    'isp_code' => Isp::generateIspCode(),
                    'isp_brand' => $data['isp_brand'],
                    'isp_name' => $data['isp_name'],
                    'area' => $data['area'],
                    'ba' => $data['ba'] ?? null,
                    'ca' => $data['ca'] ?? null,
                    'sid' => $data['sid'] ?? null,
                    'vlan' => $data['vlan'] ?? null,
                    'layanan' => $data['layanan'] ?? null,
                    'created_date' => ! empty(trim($data['created_date'] ?? '')) ? $data['created_date'] : null,
                ]);
            }
            $imported++;
        }

        fclose($handle);

        if ($imported === 0) {
            throw new \Exception('Tidak ada data valid yang ditemukan dalam file.');
        }
    }
}
