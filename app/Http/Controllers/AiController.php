<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\ChatMessage;
use App\Models\CoverageRequest;
use App\Models\Isp;
use App\Models\Order;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    public function extractPo(Request $request)
    {
        $request->validate([
            'po_document' => 'required|file|mimes:pdf|max:10240',
        ]);

        $apiKey = env('AI_API_KEY');
        $baseUrl = env('AI_BASE_URL');
        $model = env('AI_MODEL', 'gemini-1.5-flash');

        if (! $apiKey || ! $baseUrl) {
            return response()->json(['error' => 'AI Configuration is missing.'], 500);
        }

        try {
            $file = $request->file('po_document');
            $fileContent = base64_encode(file_get_contents($file->path()));

            $prompt = 'Extract ONLY the Purchase Order (PO) number from this document. '.
                'Return ONLY the plain text of the PO number, nothing else. '.
                "If not found, return 'NOT_FOUND'.";

            $response = Http::timeout(60)->withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$baseUrl}/chat/completions", [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => $prompt],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:application/pdf;base64,{$fileContent}",
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::error('LiteLLM API Error: '.$response->body());

                return response()->json(['error' => 'Gagal menghubungi AI'], 500);
            }

            $poNumber = trim($response->json('choices.0.message.content'));

            return response()->json(['po_number' => ($poNumber === 'NOT_FOUND' ? null : $poNumber)]);

        } catch (\Exception $e) {
            Log::error('AI Extraction Exception: '.$e->getMessage());

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $user = auth()->user();
        $userMessage = $request->message;

        ChatMessage::create([
            'user_id' => $user->user_id,
            'message' => $userMessage,
            'is_ai' => false,
        ]);

        $apiKey = config('services.ai.api_key', env('AI_API_KEY'));
        $baseUrl = config('services.ai.base_url', env('AI_BASE_URL'));
        $model = config('services.ai.model', env('AI_MODEL', 'gemini-1.5-flash'));

        if (! $apiKey || ! $baseUrl) {
            return response()->json(['error' => 'AI is temporarily unavailable.'], 500);
        }

        try {
            $context = $this->buildAiContext($user, $userMessage);

            // 6. Chat history — hanya 4 pesan terakhir (hemat token)
            $history = ChatMessage::where('user_id', $user->user_id)->orderBy('created_at', 'desc')->limit(4)->get()->reverse();
            $messages = [['role' => 'system', 'content' => $context]];
            foreach ($history as $msg) {
                $messages[] = ['role' => $msg->is_ai ? 'assistant' : 'user', 'content' => $msg->message];
            }

            // 7. Check if streaming requested, otherwise return optimized JSON
            if ($request->boolean('stream', false)) {
                return $this->streamAiResponse($baseUrl, $apiKey, $model, $messages, $user);
            }

            return $this->jsonAiResponse($baseUrl, $apiKey, $model, $messages, $user);

        } catch (\Exception $e) {
            Log::error('Chat AI Exception: '.$e->getMessage());

            return response()->json(['error' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    private function buildAiContext($user, $userMessage)
    {
        // 1. Keyword Extraction — broad coverage (includes STOs, Brands, Branch codes, and names)
        $searchTerms = [];
        if (preg_match_all('/\b[a-zA-Z0-9_]{3,}\b/', strtolower($userMessage), $matches)) {
            $stopWords = ['apa', 'siapa', 'kapan', 'dimana', 'bagaimana', 'kenapa', 'mengapa', 'berapa', 'jumlah', 'total', 'yang', 'dan', 'atau', 'dari', 'ke', 'di', 'pada', 'untuk', 'dengan', 'adalah', 'ini', 'itu', 'saya', 'kamu', 'tolong', 'bantu', 'carikan', 'tampilkan', 'data', 'order', 'status', 'semua', 'ada', 'tidak', 'belum', 'sudah', 'buatkan'];
            $words = array_diff($matches[0], $stopWords);
            $searchTerms = array_unique($words);
        }

        $coverageData = collect();
        $poData = collect();
        $orderData = collect();
        $areaData = collect();
        $stats = [];

        // Define Role-based Filters
        $ispFilter = $user->isAdmin() ? [] : [['isp_name', 'LIKE', "%{$user->isp_name}%"]];
        $oloFilter = $user->isAdmin() ? [] : [['olo', 'LIKE', "%{$user->isp_name}%"]];

        // 2. Verified Search across 5 Core Menus
        foreach ($searchTerms as $term) {
            $term = trim($term);
            if (strlen($term) < 3) continue;

            // [MENU_CHECK_COVERAGE] - CoverageRequest Model
            $coverageData = $coverageData->merge(
                CoverageRequest::where(function($q) use ($term) {
                    $q->where('cust_name', 'LIKE', "%$term%")
                      ->orWhere('phone', 'LIKE', "%$term%")
                      ->orWhere('kode_pra', 'LIKE', "%$term%");
                })->where($ispFilter)->limit(15)->get(['req_id', 'cust_name', 'status', 'phone', 'paket', 'cust_add', 'bandwidth'])
            );

            // [MENU_PURCHASE_ORDER] - PurchaseOrder Model
            $poData = $poData->merge(
                PurchaseOrder::where(function($q) use ($term) {
                    $q->where('no_order', 'LIKE', "%$term%")
                      ->orWhere('po_number', 'LIKE', "%$term%")
                      ->orWhere('cust_name', 'LIKE', "%$term%")
                      ->orWhere('phone', 'LIKE', "%$term%");
                })->where($ispFilter)->limit(15)->get(['no_order', 'po_number', 'cust_name', 'phone', 'paket', 'sto', 'admin_no_order', 'cust_add', 'bandwidth', 'area', 'odp'])
            );

            // [MENU_DATA_ORDER] - Order Model
            $orderData = $orderData->merge(
                Order::with('areaInfo')->where(function($q) use ($term) {
                    $q->where('no_order', 'LIKE', "%$term%")
                      ->orWhere('nd', 'LIKE', "%$term%");
                })->where($oloFilter)->limit(15)->get()->map(function($o) {
                    return [
                        'no_order' => $o->no_order,
                        'status_order' => $o->status_order,
                        'nd' => $o->nd,
                        'paket' => $o->paket,
                        'layanan' => $o->layanan,
                        'branch' => $o->areaInfo->branch ?? '-',
                        'regional' => $o->areaInfo->regional ?? '-',
                        'keterangan' => $o->keterangan
                    ];
                })
            );

            // [MENU_DATA_AREA] - Area Model (Global Visibility)
            $areaData = $areaData->merge(
                Area::where('nama_sto', 'LIKE', "%$term%")
                    ->orWhere('sto', 'LIKE', "%$term%")
                    ->orWhere('area', 'LIKE', "%$term%")
                    ->orWhere('regional', 'LIKE', "%$term%")
                    ->limit(10)->get(['sto', 'nama_sto', 'area', 'regional', 'branch'])
            );
        }

        // 3. Fallback Context — Always provide some context
        if ($coverageData->isEmpty()) $coverageData = CoverageRequest::where($ispFilter)->latest()->limit(2)->get();
        if ($poData->isEmpty()) $poData = PurchaseOrder::where($ispFilter)->latest()->limit(2)->get();
        if ($areaData->isEmpty()) $areaData = Area::latest()->limit(2)->get();

        // 4. Menu-Specific Global Stats Integration
        $stats = [
            'CHECK_COVERAGE' => [
                'total' => CoverageRequest::where($ispFilter)->count(),
                'by_status' => CoverageRequest::where($ispFilter)->groupBy('status')->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))->pluck('total', 'status')->toArray()
            ],
            'PURCHASE_ORDER' => [
                'total' => PurchaseOrder::where($ispFilter)->count(),
                'by_area' => PurchaseOrder::where($ispFilter)->groupBy('area')->select('area', \Illuminate\Support\Facades\DB::raw('count(*) as total'))->pluck('total', 'area')->toArray()
            ],
            'DATA_ORDER' => [
                'total' => Order::where($oloFilter)->count(),
                'by_status' => Order::where($oloFilter)->groupBy('status_order')->select('status_order', \Illuminate\Support\Facades\DB::raw('count(*) as total'))->pluck('total', 'status_order')->toArray()
            ],
            'DATA_AREA' => [
                'total_sto' => Area::count(),
                'regional' => Area::distinct()->pluck('regional')->toArray()
            ]
        ];

        // 4b. Reporting Dashboard Details (Joined logical stats)
        if (preg_match('/total|berapa|jumlah|count|statistik|laporan|report|pivot|brand|branch|regional|dashboard|re|po/i', $userMessage)) {
            $reStatsQuery = \Illuminate\Support\Facades\DB::table('purchase_orders')
                ->leftJoin('orders', \Illuminate\Support\Facades\DB::raw("COALESCE(NULLIF(purchase_orders.admin_no_order_input, ''), purchase_orders.admin_no_order)"), '=', 'orders.no_order')
                ->whereNotNull('orders.status_order')
                ->whereNotIn('orders.status_order', ['CANCELED INPUT', 'CANCELED', '-', '']);
            
            if (!$user->isAdmin()) $reStatsQuery->where('purchase_orders.isp_name', 'LIKE', "%{$user->isp_name}%");

            $stats['REPORTING_DASHBOARD'] = [
                'RE_BY_AREA' => (clone $reStatsQuery)->select('purchase_orders.area', \Illuminate\Support\Facades\DB::raw('count(*) as count'))->groupBy('purchase_orders.area')->pluck('count', 'area')->toArray(),
                'TOTAL_RE' => (clone $reStatsQuery)->count(),
                'TOTAL_CANCEL' => PurchaseOrder::where($ispFilter)->where(function ($q) {
                    $q->where('admin_no_order', 'like', '%Cancel PO%')
                        ->orWhere('admin_no_order_input', 'like', '%Cancel PO%');
                })->count()
            ];
        }

        // 5. High-Context System Prompt
        return "Anda adalah Asisten AI TIF Connect Professional untuk 5 Menu Utama.
User: {$user->username}, Role: {$user->role}.

SUMBER DATA (5 MENU):
1. [MENU_CHECK_COVERAGE]: Data pengajuan coverage (" . json_encode($stats['CHECK_COVERAGE']) . ")
2. [MENU_PURCHASE_ORDER]: Data PO & Detail Paket (" . json_encode($stats['PURCHASE_ORDER']) . ")
3. [MENU_DATA_AREA]: Lokasi STO & Regional (" . json_encode($stats['DATA_AREA']) . ")
4. [MENU_DATA_ORDER]: Status teknis provisioning (" . json_encode($stats['DATA_ORDER']) . ")
5. [MENU_REPORTING]: Statistik integrasi & RE (" . (isset($stats['REPORTING_DASHBOARD']) ? json_encode($stats['REPORTING_DASHBOARD']) : "Tersedia jika ditanyakan") . ")

INSTRUKSI:
- IDENTITAS: Jika ditanya siapa kamu, jawab PERSIS seperti ini: 'Saya adalah Asisten AI TIF Connect, ada yang bisa saya bantu?'
- JANGAN gunakan salam kaku selain identitas di atas. Jawab LANGSUNG ke inti.
- Gunakan data dari label [MENU_...] di atas sesuai pertanyaan user.
- Jika user tanya 'Total pengajuan', cek [MENU_CHECK_COVERAGE].
- Jika tanya 'Statistik RE', cek [MENU_REPORTING].
- Mapping: Bandwidth=Paket, Address=Alamat, Customer=Nama Pelanggan.

Detail Search Results:
Coverage: ".$coverageData->take(4)->toJson()."
PurchaseOrder: ".$poData->take(4)->toJson()."
DataOrder: ".$orderData->take(4)->toJson()."
DataArea: ".$areaData->take(4)->toJson()."
";
    }

    private function streamAiResponse($baseUrl, $apiKey, $model, $messages, $user)
    {
        return response()->stream(function () use ($baseUrl, $apiKey, $model, $messages, $user) {
            $fullResponse = '';

            try {
                $ch = curl_init("{$baseUrl}/chat/completions");
                curl_setopt_array($ch, [
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => [
                        "Authorization: Bearer {$apiKey}",
                        'Content-Type: application/json',
                        'Accept: text/event-stream',
                    ],
                    CURLOPT_POSTFIELDS => json_encode([
                        'model' => $model,
                        'messages' => $messages,
                        'stream' => true,
                        'max_tokens' => 500,
                        'temperature' => 0.3,
                    ]),
                    CURLOPT_RETURNTRANSFER => false,
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_WRITEFUNCTION => function ($ch, $data) use (&$fullResponse) {
                        $lines = explode("\n", $data);
                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (empty($line) || ! str_starts_with($line, 'data: ')) {
                                continue;
                            }

                            $jsonStr = substr($line, 6);
                            if ($jsonStr === '[DONE]') {
                                echo "data: [DONE]\n\n";
                                if (ob_get_level()) {
                                    ob_flush();
                                }
                                flush();

                                continue;
                            }

                            $json = json_decode($jsonStr, true);
                            $content = $json['choices'][0]['delta']['content'] ?? '';
                            if ($content !== '') {
                                $fullResponse .= $content;
                                echo 'data: '.json_encode(['content' => $content])."\n\n";
                                if (ob_get_level()) {
                                    ob_flush();
                                }
                                flush();
                            }
                        }

                        return strlen($data);
                    },
                ]);

                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                // Fallback jika streaming gagal
                if (empty($fullResponse) || $httpCode >= 400) {
                    $fallbackResponse = Http::timeout(60)->withHeaders([
                        'Authorization' => "Bearer {$apiKey}",
                        'Content-Type' => 'application/json',
                    ])->post("{$baseUrl}/chat/completions", [
                        'model' => $model,
                        'messages' => $messages,
                        'max_tokens' => 500,
                        'temperature' => 0.3,
                    ]);

                    $fullResponse = $fallbackResponse->json('choices.0.message.content') ?? 'Maaf, saya sedang tidak bisa menjawab.';
                    echo 'data: '.json_encode(['content' => $fullResponse])."\n\n";
                    echo "data: [DONE]\n\n";
                    if (ob_get_level()) {
                        ob_flush();
                    }
                    flush();
                }

                if (! empty($fullResponse)) {
                    ChatMessage::create(['user_id' => $user->user_id, 'message' => $fullResponse, 'is_ai' => true]);
                }
            } catch (\Exception $e) {
                Log::error('Streaming AI Exception: '.$e->getMessage());
                echo 'data: '.json_encode(['content' => 'Maaf, terjadi kesalahan.'])."\n\n";
                echo "data: [DONE]\n\n";
                if (ob_get_level()) {
                    ob_flush();
                }
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function jsonAiResponse($baseUrl, $apiKey, $model, $messages, $user)
    {
        $response = Http::timeout(60)->withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ])->post("{$baseUrl}/chat/completions", [
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => 500,
            'temperature' => 0.3,
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mendapatkan respon AI'], 500);
        }

        $aiText = $response->json('choices.0.message.content') ?? 'Maaf, saya sedang tidak bisa menjawab.';
        ChatMessage::create(['user_id' => $user->user_id, 'message' => $aiText, 'is_ai' => true]);

        return response()->json(['message' => $aiText]);
    }

    public function getChatHistory()
    {
        return response()->json(
            ChatMessage::where('user_id', auth()->id())
                ->orderBy('created_at', 'asc')
                ->limit(50)
                ->get()
        );
    }

    public function clearChatHistory()
    {
        ChatMessage::where('user_id', auth()->id())->delete();
        return response()->json(['success' => true]);
    }
}
