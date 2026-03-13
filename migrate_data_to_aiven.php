<?php

// Konfigurasi Database Lokal
$localHost = '127.0.0.1';
$localDb   = 'tif_new';
$localUser = 'root';
$localPass = '';

// Konfigurasi Database Aiven (dari .env Anda sekarang)
$aivenHost = 'mysql-16b3f798-ahmedseptiyanto97-6573.f.aivencloud.com';
$aivenPort = 28472;
$aivenDb   = 'defaultdb';
$aivenUser = 'avnadmin';
$aivenPass = getenv('DB_PASSWORD') ?: 'YOUR_AIVEN_PASSWORD_HERE';

try {
    echo "Connecting to Local Database...\n";
    $localPdo = new PDO("mysql:host=$localHost;dbname=$localDb;charset=utf8mb4", $localUser, $localPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "Connecting to Aiven Database...\n";
    $aivenPdo = new PDO("mysql:host=$aivenHost;port=$aivenPort;dbname=$aivenDb;charset=utf8mb4", $aivenUser, $aivenPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Matikan foreign key checks sementara di Aiven
    $aivenPdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // Ambil daftar semua tabel dari lokal
    $stmt = $localPdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        echo "Processing table: $table ... ";
        
        // Ambil data dari lokal
        $dataStmt = $localPdo->query("SELECT * FROM `$table`");
        $rows = $dataStmt->fetchAll();

        if (count($rows) > 0) {
            // Kosongkan tabel di Aiven sebelum insert (opsional, untuk mencegah duplikat saat retry)
            $aivenPdo->exec("TRUNCATE TABLE `$table`");

            // Ambil nama kolom
            $columns = array_keys($rows[0]);
            $colNames = implode("`, `", $columns);
            $placeholders = implode(", ", array_fill(0, count($columns), "?"));

            $insertStmt = $aivenPdo->prepare("INSERT INTO `$table` (`$colNames`) VALUES ($placeholders)");

            $count = 0;
            $aivenPdo->beginTransaction();
            foreach ($rows as $row) {
                $insertStmt->execute(array_values($row));
                $count++;
            }
            $aivenPdo->commit();
            
            echo "Inserted $count rows.\n";
        } else {
            echo "No data. Skipped.\n";
        }
    }

    $aivenPdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "\n\n=== MIGRATION TO AIVEN COMPLETE! ===\n";

} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    if (isset($aivenPdo) && $aivenPdo->inTransaction()) {
        $aivenPdo->rollBack();
    }
}
