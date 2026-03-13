<?php

$host = '127.0.0.1';
$db   = 'tif_new';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$stmt = $pdo->query("
    SELECT t.table_name
    FROM information_schema.tables t
    LEFT JOIN information_schema.table_constraints tc 
        ON t.table_schema = tc.table_schema 
        AND t.table_name = tc.table_name 
        AND tc.constraint_type = 'PRIMARY KEY'
    WHERE t.table_schema = 'tif_new' 
        AND tc.constraint_name IS NULL
        AND t.table_type = 'BASE TABLE'
");

$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (empty($tables)) {
    echo "GOOD: All tables have a primary key.\n";
} else {
    echo "BAD: The following tables DO NOT have a primary key:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
}
