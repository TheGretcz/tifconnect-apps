<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UserImport
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

        $requiredHeaders = ['role', 'password'];
        foreach ($requiredHeaders as $required) {
            if (! in_array($required, $headers)) {
                fclose($handle);
                throw new \Exception("Header '{$required}' tidak ditemukan dalam file CSV.");
            }
        }

        $imported = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $row = array_pad($row, count($headers), '');
            $data = array_combine($headers, $row);

            // Generate Username
            $username = self::generateUsername();

            User::create([
                'username' => $username,
                'password' => Hash::make($data['password'] ?? 'TIFConnect2026'),
                'role' => $data['role'],
                'pic_isp' => $data['pic_isp'] ?? null,
                'isp_brand' => $data['isp_brand'] ?? null,
                'isp_name' => $data['isp_name'] ?? null,
                'area' => $data['area'] ?? null,
            ]);
            $imported++;
        }

        fclose($handle);

        if ($imported === 0) {
            throw new \Exception('Tidak ada data valid yang ditemukan dalam file.');
        }
    }

    private static function generateUsername(): string
    {
        do {
            $username = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (User::where('username', $username)->exists());

        return $username;
    }
}
