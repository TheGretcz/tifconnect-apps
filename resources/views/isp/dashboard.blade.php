@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard ISP</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang,
            {{ auth()->user()->pic_isp ?? auth()->user()->username }}! ({{ auth()->user()->isp_name }})
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-8">
        <a href="{{ route('isp.dashboard') }}"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Request</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalRequests }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('isp.purchase-orders.index') }}"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-600/20">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total PO</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalPO }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('isp.dashboard', ['status' => 'COVERED']) }}"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Covered</p>
                    <p class="text-3xl font-bold text-green-600">{{ $totalCovered }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('isp.dashboard', ['status' => 'PROCESSING']) }}"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center shadow-lg shadow-yellow-400/30">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Processing</p>
                    <p class="text-3xl font-bold text-yellow-500">{{ $totalProcessing }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route('isp.dashboard', ['status' => 'NOT COVERED']) }}"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Not Covered</p>
                    <p class="text-3xl font-bold text-red-600">{{ $totalNotCovered }}</p>
                </div>
            </div>
        </a>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="mb-4">
        <form id="searchForm" method="GET" action="{{ route('isp.dashboard') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                    placeholder="Cari customer, phone, alamat, area, layanan..."
                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full ps-10 p-2.5">
            </div>
            <select name="status" onchange="document.getElementById('searchForm').submit()"
                class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 p-2.5">
                <option value="">Status</option>
                <option value="PROCESSING" {{ request('status') == 'PROCESSING' ? 'selected' : '' }}>Processing</option>
                <option value="COVERED" {{ request('status') == 'COVERED' ? 'selected' : '' }}>Covered</option>
                <option value="NOT COVERED" {{ request('status') == 'NOT COVERED' ? 'selected' : '' }}>Not Covered</option>
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('isp.dashboard') }}"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Reset</a>
            @endif
        </form>
    </div>

    {{-- Requests Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div
            class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Coverage Requests Anda</h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('isp.coverage.template') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                    Download Template
                </a>
                <button type="button" onclick="document.getElementById('importCoverageModal').classList.remove('hidden')"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all">
                    Import CSV
                </button>
                <a href="{{ route('isp.coverage.export') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm transition-all">
                    <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('isp.coverage.create') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 shadow-sm transition-all">
                    + Tambah Request
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Kode PRA</th>
                        <th class="px-4 py-3">Brand</th>
                        <th class="px-4 py-3">ISP Name</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Address</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Longlat</th>
                        <th class="px-4 py-3">Layanan</th>
                        <th class="px-4 py-3">Paket</th>
                        <th class="px-4 py-3">Bandwidth</th>
                        <th class="px-4 py-3">Area</th>
                        <th class="px-4 py-3">ODP</th>
                        <th class="px-4 py-3">GPON</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                        <th class="px-4 py-3">Created at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $index => $req)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $requests->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $req->kode_pra }}</td>
                            <td class="px-4 py-3">{{ $req->brand }}</td>
                            <td class="px-4 py-3">{{ $req->isp_name }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $req->cust_name }}</td>
                            <td class="px-4 py-3 max-w-[200px] truncate" title="{{ $req->cust_add }}">{{ $req->cust_add }}</td>
                            <td class="px-4 py-3">{{ $req->phone }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $req->longlat ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $req->layanan }}</td>
                            <td class="px-4 py-3">{{ $req->paket }}</td>
                            <td class="px-4 py-3">{{ $req->bandwidth }}</td>
                            <td class="px-4 py-3">{{ $req->area }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $req->odp ?? '-' }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $req->gpon ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($req->status === 'PROCESSING')
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Processing</span>
                                @elseif($req->status === 'COVERED')
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Covered</span>
                                @else
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Not
                                        Covered</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($req->status === 'COVERED')
                                    @if($req->purchaseOrder)
                                        <span
                                            class="px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 dark:bg-gray-700 dark:text-gray-500 rounded-lg cursor-not-allowed inline-flex items-center whitespace-nowrap">
                                            <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            PO Terkirim
                                        </span>
                                    @else
                                        <button type="button" onclick="openPoModal({{ json_encode($req) }})"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-sm transition-all whitespace-nowrap">
                                            <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Request PO
                                        </button>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-400">
                                {{ $req->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                @if(request('search') || request('status'))
                                    Tidak ada data yang cocok dengan pencarian. <a href="{{ route('isp.dashboard') }}"
                                        class="text-red-600 hover:underline">Reset filter</a>
                                @else
                                    Belum ada coverage request. <a href="{{ route('isp.coverage.create') }}"
                                        class="text-red-600 hover:underline">Tambah sekarang</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $requests->links() }}
        </div>
    </div>

    {{-- PO Request Modal --}}
    <div id="poRequestModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="relative w-full max-w-3xl max-h-[90vh] mx-4 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Request Purchase Order</h3>
                    <button type="button" onclick="closePoModal()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('isp.purchase-orders.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="coverage_request_id" id="po_coverage_request_id">

                    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Coverage Data (Read Only) --}}
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
                            <input type="text" id="po_brand" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP Name</label>
                            <input type="text" id="po_isp_name" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Area</label>
                            <input type="text" id="po_area" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Layanan</label>
                            <input type="text" id="po_layanan" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Paket</label>
                            <input type="text" id="po_paket" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Kode PRA</label>
                            <input type="text" id="po_kode_pra" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed font-mono">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                            <input type="text" id="po_cust_name" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                            <input type="text" id="po_phone" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Bandwidth</label>
                            <input type="text" id="po_bandwidth" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                            <textarea id="po_cust_add" disabled rows="2"
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed"></textarea>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ODP</label>
                            <input type="text" id="po_odp" disabled
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed font-mono">
                        </div>

                        {{-- PO Fields --}}
                        <div class="md:col-span-3 border-t dark:border-gray-700 pt-4 mt-2">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Data Purchase Order
                            </h4>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Upload Dokumen PO
                                (PDF) <span class="text-red-500">*</span></label>
                            <input type="file" name="po_document" id="po_document" accept=".pdf" required
                                onchange="extractPoNumber(this)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 10MB. PO Number akan di-extract
                                otomatis dari PDF.</p>
                        </div>

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">PO Number</label>
                            <div class="relative">
                                <input type="text" name="po_number" id="po_number" placeholder="Otomatis dari PDF..."
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <div id="po_number_loading" class="hidden absolute right-2.5 top-2.5">
                                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p id="po_ocr_status" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                        <button type="submit" id="poSubmitBtn"
                            class="text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 shadow-lg shadow-blue-600/25 transition-all">
                            Submit Purchase Order
                        </button>
                        <button type="button" onclick="closePoModal()"
                            class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

{{-- Import Coverage Modal --}}
<div id="importCoverageModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="relative w-full max-w-md mx-4">
        <div class="bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Import Coverage Request</h3>
                <button type="button" onclick="document.getElementById('importCoverageModal').classList.add('hidden')"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('isp.coverage.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih File CSV</label>
                    <input type="file" name="file" accept=".csv" required
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format file harus .csv sesuai template.</p>
                </div>
                <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Upload
                        & Import</button>
                    <button type="button"
                        onclick="document.getElementById('importCoverageModal').classList.add('hidden')"
                        class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        // Search bar auto-submit
        document.addEventListener('DOMContentLoaded', function () {
            let timer;
            const input = document.getElementById('searchInput');
            const form = document.getElementById('searchForm');
            if (input && form) {
                input.addEventListener('input', function () {
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        form.submit();
                    }, 400);
                });
            }

            // Prevent double-submit on PO form
            const poForm = document.querySelector('#poRequestModal form');
            if (poForm) {
                poForm.addEventListener('submit', function (e) {
                    const btn = document.getElementById('poSubmitBtn');
                    if (btn.disabled) {
                        e.preventDefault();
                        return false;
                    }
                    btn.disabled = true;
                    btn.innerHTML = `
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
                                                Mengirim...
                                            `;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                });
            }
        });

        // PO Modal Functions
        function openPoModal(req) {
            document.getElementById('po_coverage_request_id').value = req.req_id;
            document.getElementById('po_brand').value = req.brand;
            document.getElementById('po_isp_name').value = req.isp_name;
            document.getElementById('po_area').value = req.area;
            document.getElementById('po_layanan').value = req.layanan;
            document.getElementById('po_paket').value = req.paket;
            document.getElementById('po_kode_pra').value = req.kode_pra || '-';
            document.getElementById('po_cust_name').value = req.cust_name;
            document.getElementById('po_phone').value = req.phone;
            document.getElementById('po_bandwidth').value = req.bandwidth || '-';
            document.getElementById('po_cust_add').value = req.cust_add;
            document.getElementById('po_odp').value = req.odp || '-';
            document.getElementById('po_number').value = '';
            document.getElementById('po_document').value = '';
            document.getElementById('po_ocr_status').textContent = '';
            document.getElementById('poRequestModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePoModal() {
            document.getElementById('poRequestModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.getElementById('poRequestModal').addEventListener('click', function (e) {
            if (e.target === this) closePoModal();
        });

        // === HYBRID PO EXTRACTION: AI first, then local pdf.js fallback ===
        async function extractPoNumber(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            const loading = document.getElementById('po_number_loading');
            const status = document.getElementById('po_ocr_status');
            const poNumberInput = document.getElementById('po_number');

            loading.classList.remove('hidden');
            status.textContent = '🤖 AI sedang menganalisa dokumen...';
            status.className = 'mt-1 text-xs text-blue-600 dark:text-blue-400 font-medium';
            poNumberInput.placeholder = 'Mencari nomor...';

            // Step 1: Try AI extraction
            try {
                const formData = new FormData();
                formData.append('po_document', file);
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('/ai/extract-po', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (!data.error && data.po_number) {
                    poNumberInput.value = data.po_number;
                    status.textContent = '✅ PO Number ditemukan oleh AI!';
                    status.className = 'mt-1 text-xs text-green-600 dark:text-green-400 font-bold';
                    loading.classList.add('hidden');
                    return;
                }
                console.log('AI result (fallback to local):', data);
            } catch (e) {
                console.warn('AI unavailable, using local extraction:', e.message);
            }

            // Step 2: Fallback to local pdf.js extraction
            status.textContent = '📄 Membaca PDF secara lokal...';

            try {
                const arrayBuffer = await file.arrayBuffer();
                const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;

                let allText = '';
                const maxPages = Math.min(pdf.numPages, 3);

                for (let i = 1; i <= maxPages; i++) {
                    const page = await pdf.getPage(i);
                    const textContent = await page.getTextContent();
                    const pageText = textContent.items.map(item => item.str).join(' ');
                    allText += pageText + '\n';
                }

                console.log('--- LOCAL PDF TEXT ---');
                console.log(allText);
                console.log('--- END ---');

                const poNumber = findPoNumber(allText);

                if (poNumber) {
                    poNumberInput.value = poNumber;
                    status.textContent = '✅ PO Number berhasil ditemukan!';
                    status.className = 'mt-1 text-xs text-green-600 dark:text-green-400 font-bold';
                } else {
                    if (allText.trim().length < 20) {
                        status.textContent = '⚠️ PDF seperti hasil scan (tanpa teks). Input manual.';
                    } else {
                        status.textContent = '⚠️ PO Number tidak ditemukan. Input manual.';
                    }
                    status.className = 'mt-1 text-xs text-orange-600 dark:text-orange-400 font-medium';
                    poNumberInput.placeholder = 'Input manual di sini...';
                }
            } catch (error) {
                console.error('Local PDF Error:', error);
                status.textContent = '❌ Gagal membaca PDF. Silakan input manual.';
                status.className = 'mt-1 text-xs text-red-600 dark:text-red-400 font-medium';
                poNumberInput.placeholder = 'Input manual...';
            } finally {
                loading.classList.add('hidden');
            }
        }

        function findPoNumber(text) {
            let t = text.replace(/\s+/g, ' ').trim();
            console.log('Normalized text:', t);

            // Step 1: Find PO label and extract value using token-based approach
            const labelRegex = /(?:No\s*\.?\s*PO|Nomor\s+PO|PO\s*Number|Purchase\s*Order\s*No\s*\.?)\s*[:\-]\s*/gi;
            const labelMatch = labelRegex.exec(t);

            if (labelMatch) {
                const startPos = labelMatch.index + labelMatch[0].length;
                const afterLabel = t.substring(startPos, startPos + 80);
                console.log('After label:', afterLabel);

                // Split into tokens and collect PO-like parts
                const tokens = afterLabel.split(/\s+/);
                let poParts = [];
                const stopWords = /^(date|tanggal|kepada|hal|page|rev|telp|phone|fax|email|dear|attn|for|dari|yang|ini|itu|pada|rukan|pt|jl|jalan|we|to|re|cc|subject|alamat|address|dengan|bahwa|sebagai|atas|nama|perhatian|periode|total|qty|quantity|unit|price|harga|description|deskripsi|keterangan|item|barang|jumlah|biaya|discount|subtotal|ppn|pph|grand|nett|gross)$/i;

                for (const token of tokens) {
                    if (!token) continue;
                    if (stopWords.test(token)) break;

                    const hasDigitOrConnector = /[0-9\/\-\.]/.test(token);
                    const isShortCode = token.length <= 4 && /^[A-Za-z]+$/.test(token);

                    if (hasDigitOrConnector || isShortCode) {
                        poParts.push(token);
                    } else {
                        break;
                    }
                }

                if (poParts.length > 0) {
                    let result = poParts.join('');
                    result = result.replace(/\s*([\/\-\.])\s*/g, '$1');
                    result = result.replace(/[\/\-\.]$/, '');
                    if (result.length >= 3 && /\d/.test(result)) {
                        console.log('✓ Token-based match:', result);
                        return result;
                    }
                }
            }

            // Step 2: Structural patterns (no label found, look for PO-like codes)
            const structPatterns = [
                /\b(\d{1,4}\s*[\-\.]\s*[A-Z]{2,}(?:\s*[\/\-\.]\s*[A-Z0-9]{1,}){2,})\b/i,
                /\b(\d{1,4}\s*\/\s*[A-Z]{2,}(?:\s*\/\s*[A-Z0-9]{1,}){2,})\b/i,
                /\b([A-Z0-9]{2,}(?:\s*[\/\-\.]\s*[A-Z0-9]{1,}){2,})\b/i,
                /\b(PO\s*[\-\/]?\s*\d{3,}[A-Z0-9\-\/\.]*)\b/i,
            ];

            for (const pattern of structPatterns) {
                const match = t.match(pattern);
                if (match && match[1]) {
                    let result = match[1].trim().replace(/\s*([\/\-\.])\s*/g, '$1');
                    if (result.length >= 4 && (/\d/.test(result) || /[\/\-]/.test(result))) {
                        console.log('✓ Structure match:', result);
                        return result;
                    }
                }
            }

            console.log('✕ No PO match found.');
            return null;
        }
    </script>
@endpush