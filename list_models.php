<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = env('GEMINI_API_KEY');
$url = 'https://generativelanguage.googleapis.com/v1beta/models?key='.$apiKey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$output = '';
if (isset($data['models'])) {
    foreach ($data['models'] as $model) {
        $output .= $model['name']."\n";
    }
} else {
    $output = 'Error: '.$response;
}
file_put_contents('models_list.txt', $output);
echo "Done. Check models_list.txt\n";
