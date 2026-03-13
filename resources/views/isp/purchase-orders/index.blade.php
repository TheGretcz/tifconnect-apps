@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Purchase Order</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola Purchase Order Anda</p>
        </div>
        <div class="flex flex-wrap gap-2 sm:ml-auto">
            <a href="{{ route('isp.purchase-orders.export') }}"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">
                Export CSV
            </a>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="mb-4">
        <form id="searchForm" method="GET" action="{{ route('isp.purchase-orders.index') }}"
            class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                    placeholder="Cari PO number, customer, no order..."
                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full ps-10 p-2.5">
            </div>
            @if(request('search'))
                <a href="{{ route('isp.purchase-orders.index') }}"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Reset</a>
            @endif
        </form>
    </div>

    {{-- PO Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center text-gray-600 dark:text-gray-300">
                <thead
                    class="text-xs font-semibold text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:text-gray-300 border-b-2 border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-3 py-3 text-center">No Order</th>
                        <th class="px-3 py-3 text-center">PO ID</th>
                        <th class="px-3 py-3 text-center">PO Number</th>
                        <th class="px-3 py-3 text-center">Kode PRA</th>
                        <th class="px-3 py-3 text-center">Brand</th>
                        <th class="px-3 py-3 text-center">ISP Name</th>
                        <th class="px-3 py-3 text-center">Customer</th>
                        <th class="px-3 py-3 text-center">Address</th>
                        <th class="px-3 py-3 text-center">Phone</th>
                        <th class="px-3 py-3 text-center">Longlat</th>
                        <th class="px-3 py-3 text-center">Area</th>
                        <th class="px-3 py-3 text-center">Layanan</th>
                        <th class="px-3 py-3 text-center">Bandwidth</th>
                        <th class="px-3 py-3 text-center">ODP</th>
                        <th class="px-3 py-3 text-center">GPON</th>
                        <th class="px-3 py-3 text-center">STO</th>
                        <th class="px-3 py-3 text-center">Branch</th>
                        <th class="px-3 py-3 text-center">Regional</th>
                        <th class="px-3 py-3 text-center">No Order</th>
                        <th class="px-3 py-3 text-center">No Order Inpul</th>
                        <th class="px-3 py-3 text-center">Reason Cancel</th>
                        <th class="px-3 py-3 text-center">Category Cancel</th>
                        <th class="px-3 py-3 text-center">Status Order</th>
                        <th class="px-3 py-3 text-center">Keterangan</th>
                        <th class="px-3 py-3 text-center">Tanggal</th>
                        <th class="px-3 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($purchaseOrders as $index => $po)
                        <tr
                            class="even:bg-gray-50/50 dark:even:bg-gray-800/50 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200">
                            <td class="px-3 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $purchaseOrders->firstItem() + $index }}
                            </td>
                            <td class="px-3 py-3 font-mono font-bold text-gray-900 dark:text-white">
                                {{ $po->no_order }}
                            </td>
                            <td class="px-3 py-3 font-medium text-blue-600 dark:text-blue-400">
                                {{ $po->po_number ?? '-' }}
                            </td>
                            <td class="px-3 py-3 font-mono text-xs">{{ $po->kode_pra ?? '-' }}</td>
                            <td class="px-3 py-3 text-xs">{{ $po->brand }}</td>
                            <td class="px-3 py-3 text-xs">{{ $po->joined_isp_name ?? $po->isp_name }}</td>
                            <td class="px-3 py-3 font-medium text-gray-900 dark:text-white">{{ $po->cust_name }}</td>
                            <td class="px-3 py-3 max-w-[150px] truncate" title="{{ $po->cust_add }}">{{ $po->cust_add }}</td>
                            <td class="px-3 py-3">{{ $po->phone }}</td>
                            <td class="px-3 py-3 text-xs font-mono">{{ $po->longlat ?? '-' }}</td>
                            <td class="px-3 py-3">{{ $po->area }}</td>
                            <td class="px-3 py-3">{{ $po->layanan }}</td>
                            <td class="px-3 py-3">{{ $po->bandwidth ?? '-' }}</td>
                            <td class="px-3 py-3 font-mono text-xs">{{ $po->odp ?? '-' }}</td>
                            <td class="px-3 py-3 font-mono text-xs">{{ $po->gpon ?? '-' }}</td>
                            <td class="px-3 py-3 font-mono text-xs font-bold text-red-600">{{ $po->sto ?? '-' }}</td>
                            <td
                                class="px-3 py-3 text-xs font-semibold text-blue-700 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $po->joined_branch ?? ($po->branch ?? '-') }}
                            </td>
                            <td
                                class="px-3 py-3 text-xs font-semibold text-green-700 bg-green-50 dark:bg-green-900/30 dark:text-green-300">
                                {{ $po->joined_regional ?? ($po->regional ?? '-') }}
                            </td>
                            <td class="px-3 py-3 text-xs font-mono">{{ $po->admin_no_order ?? '-' }}</td>
                            <td class="px-3 py-3 text-xs font-mono uppercase">{{ $po->admin_no_order_input ?? '-' }}</td>
                            <td class="px-3 py-3 text-xs font-medium text-orange-600 dark:text-orange-400">
                                {{ $po->reason_cancel ?? '-' }}
                            </td>
                            <td class="px-3 py-3 text-xs font-medium text-purple-600 dark:text-purple-400">
                                {{ $po->category_cancel ?? '-' }}
                            </td>
                            <td class="px-3 py-3">
                                @php
                                    $statusColor = match (strtolower($po->order_status_val ?? '')) {
                                        'completed', 'complete', 'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'in progress', 'proses' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'cancelled', 'cancel', 'batal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    };
                                @endphp
                                @if($po->order_status_val)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColor }}">
                                        {{ $po->order_status_val }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-xs max-w-[150px] truncate" title="{{ $po->order_keterangan_val }}">
                                {{ $po->order_keterangan_val ?? '-' }}
                            </td>
                            <td class="px-3 py-3">{{ $po->created_at->format('d/m/Y') }}</td>
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-center">
                                    {{-- View Icon --}}
                                    @if($po->po_document)
                                        <button type="button"
                                            onclick="openPdfModal('{{ route('isp.purchase-orders.pdf', $po->po_id) }}')"
                                            class="p-1.5 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 transition-colors"
                                            title="View PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="26" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada Purchase Order. Buat PO dari halaman
                                <a href="{{ route('isp.dashboard') }}" class="text-red-600 hover:underline">Dashboard</a>
                                pada coverage yang berstatus Covered.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $purchaseOrders->links() }}
        </div>
    </div>


    {{-- PDF View Modal --}}
    <div id="pdfModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/90 backdrop-blur-sm">
        <div class="relative w-full h-full flex flex-col p-4">
            <div class="flex justify-end p-2">
                <button type="button" onclick="closePdfModal()"
                    class="text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-2">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="bg-white rounded-lg overflow-hidden flex-1 shadow-2xl">
                <iframe id="pdfFrame" class="w-full h-full" src=""></iframe>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Search auto-submit
        document.addEventListener('DOMContentLoaded', function () {
            let timer;
            const input = document.getElementById('searchInput');
            const form = document.getElementById('searchForm');
            if (input && form) {
                input.addEventListener('input', function () {
                    clearTimeout(timer);
                    timer = setTimeout(function () { form.submit(); }, 400);
                });
            }
        });

        function openPdfModal(url) {
            document.getElementById('pdfFrame').src = url;
            document.getElementById('pdfModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePdfModal() {
            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfFrame').src = '';
            document.body.style.overflow = '';
        }

        window.onclick = function (event) {
            const pdfModal = document.getElementById('pdfModal');
            if (event.target === pdfModal) closePdfModal();
        }
    </script>
@endpush