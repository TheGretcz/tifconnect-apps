<?php

namespace App\Imports;

use App\Models\Area;
use Illuminate\Http\UploadedFile;

class AreaImport
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

        $requiredHeaders = ['sto', 'nama sto', 'area', 'regional', 'branch'];
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

            $data = array_combine($headers, $row);

            if (empty($data['sto']) || empty($data['nama sto']) || empty($data['area']) || empty($data['regional']) || empty($data['branch'])) {
                continue;
            }

            Area::updateOrCreate(
                ['sto' => strtoupper($data['sto'])],
                [
                    'nama_sto' => $data['nama sto'],
                    'area' => $data['area'],
                    'regional' => $data['regional'],
                    'branch' => $data['branch'],
                ]
            );
            $imported++;
        }

        fclose($handle);

        if ($imported === 0) {
            throw new \Exception('Tidak ada data valid yang ditemukan dalam file.');
        }
    }
}
