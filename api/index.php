<?php

// Pastikan Vercel menggunakan /tmp untuk path yang sifatnya read-write
$compiled = '/tmp/storage/framework/views';
$cache = '/tmp/storage/framework/cache/data';
$sessions = '/tmp/storage/framework/sessions';
$logs = '/tmp/storage/logs';

$paths = [$compiled, $cache, $sessions, $logs];

foreach ($paths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

$_ENV['VIEW_COMPILED_PATH'] = $compiled;
putenv("VIEW_COMPILED_PATH={$compiled}");

$_ENV['APP_STORAGE'] = '/tmp/storage';
putenv("APP_STORAGE=/tmp/storage");

require __DIR__ . '/../public/index.php';
