<?php

namespace App\Imports;

use App\Models\Order;
use Illuminate\Http\UploadedFile;

class OrderImport
{
    /**
     * CSV column indices (0-based) to extract:
     * B=1, C=2, D=3, E=4, I=8, P=15, Y=24, AM=38, AP=41
     */
    private static array $columnIndices = [
        'layanan' => 1,  // B
        'teritory' => 2,  // C
        'paket' => 3,  // D
        'no_order' => 4,  // E
        'olo' => 8,  // I
        'sto' => 15, // P
        'nd' => 24, // Y
        'status_order' => 38, // AM
        'keterangan' => 41, // AP
    ];

    /**
     * Import CSV — only imports rows where TERITORY column contains "2".
     */
    public static function import(UploadedFile $file): int
    {
        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            throw new \Exception('Tidak bisa membuka file.');
        }

        // Skip header row
        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            throw new \Exception('File CSV kosong.');
        }

        $imported = 0;
        $skipped = 0;
        $sampleValues = []; // Collect sample teritory values for debugging
        $teritoryIndex = self::$columnIndices['teritory']; // Column C (index 2)

        while (($row = fgetcsv($handle)) !== false) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Check if row has enough columns
            $maxIndex = max(self::$columnIndices);
            if (count($row) <= $maxIndex) {
                continue;
            }

            // Filter: only teritory 2
            // Match flexibly: "2", "TERITORI 2", "Teritory 2", "teritori-2", etc.
            $rowTeritory = trim($row[$teritoryIndex] ?? '');

            // Collect sample values for debug (max 5 unique)
            if (count($sampleValues) < 5 && ! in_array($rowTeritory, $sampleValues)) {
                $sampleValues[] = $rowTeritory;
            }

            if (! self::isTeritory2($rowTeritory)) {
                $skipped++;

                continue;
            }

            // Extract only the selected columns
            $data = [];
            foreach (self::$columnIndices as $field => $index) {
                $data[$field] = trim($row[$index] ?? '');
            }

            // Skip if no_order is empty (primary identifier)
            if (empty($data['no_order'])) {
                continue;
            }

            Order::updateOrCreate(
                ['no_order' => $data['no_order']],
                $data
            );
            $imported++;
        }

        fclose($handle);

        if ($imported === 0) {
            $samplesStr = implode(', ', array_map(fn ($v) => "'{$v}'", $sampleValues));
            throw new \Exception("Tidak ada data teritori 2 yang ditemukan. {$skipped} baris dilewati. Contoh nilai kolom C (teritory): {$samplesStr}");
        }

        return $imported;
    }

    /**
     * Check if the teritory value is "TIF2".
     */
    private static function isTeritory2(string $value): bool
    {
        $value = strtoupper(trim($value));

        return $value === 'TIF2';
    }
}
