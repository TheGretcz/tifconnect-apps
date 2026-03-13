<?php

namespace App\Http\Controllers\Isp;

use App\Http\Controllers\Controller;
use App\Models\CoverageRequest;
use Illuminate\Http\Request;

class CoverageController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $brand = $user->isp_brand ?? 'ISP';
        $ispName = $user->isp_name ?? '';
        $nextKodePra = $this->generateKodePra($brand);

        return view('isp.coverage.create', compact('nextKodePra', 'brand', 'ispName'));
    }

    /**
     * Generate Kode PRA: ISP_BRAND + sequential 4-digit number
     * e.g. MAXINDO0001, MAXINDO0002, ...
     */
    private function generateKodePra(string $brand): string
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

    public function store(Request $request)
    {
        $user = auth()->user();
        $brand = $user->isp_brand ?? 'ISP';
        $ispName = $user->isp_name ?? '';

        $validated = $request->validate([
            'area' => 'required|string|max:255',
            'layanan' => 'required|in:Vula,Bitstream,Metro Ethernet',
            'paket' => 'required|in:Standar,Lite',
            'phone' => 'required|string|max:255',
            'cust_name' => 'required|string|max:255',
            'cust_add' => 'required|string',
            'longlat' => 'required|string|max:255',
            'bandwidth' => 'required|in:20 Mbps,30 Mbps,40 Mbps,50 Mbps,100 Mbps,200 Mbps',
        ]);

        $validated['user_id'] = $user->user_id;
        $validated['brand'] = $brand;
        $validated['isp_name'] = $ispName;
        $validated['kode_pra'] = $this->generateKodePra($brand);
        $validated['status'] = 'PROCESSING';

        CoverageRequest::create($validated);

        return redirect()->route('isp.dashboard')->with('success', 'Coverage request berhasil ditambahkan.');
    }
}
