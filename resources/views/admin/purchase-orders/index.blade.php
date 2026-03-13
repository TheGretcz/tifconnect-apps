@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Purchase Order</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua Purchase Order dari seluruh ISP</p>
        </div>
        <div class="flex flex-wrap gap-2 sm:ml-auto">
            <a href="{{ route('admin.purchase-orders.template') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                Download Template
            </a>
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all">
                Import CSV
            </button>
            <a href="{{ route('admin.purchase-orders.export') }}"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">
                Export CSV
            </a>
            @if(auth()->user()->role === 'Super Admin')
                <button type="button" data-modal-target="clearPoModal" data-modal-toggle="clearPoModal"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all">
                    Clear Data
                </button>
            @endif
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.purchase-orders.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari PO number, customer, ISP..."
                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full ps-10 p-2.5">
            </div>
            <select name="area"
                class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 p-2.5">
                <option value="">Semua Area</option>
                <option value="JABODETABEK" {{ request('area') == 'JABODETABEK' ? 'selected' : '' }}>JABODETABEK</option>
                <option value="JABAR" {{ request('area') == 'JABAR' ? 'selected' : '' }}>JABAR</option>
            </select>
            <button type="submit"
                class="px-4 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900 transition-all">Filter</button>
        </form>
    </div>

    {{-- PO Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center text-gray-600 dark:text-gray-300">
                <thead
                    class="text-xs font-semibold text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:text-gray-300 border-b-2 border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-3 py-3 text-center">No</th>
                        <th class="px-3 py-3 text-center">No Order ISP</th>
                        <th class="px-3 py-3 text-center">PO Number</th>
                        <th class="px-3 py-3 text-center">Kode PRA</th>
                        <th class="px-3 py-3 text-center">Brand</th>
                        <th class="px-3 py-3 text-center">ISP Name</th>
                        <th class="px-3 py-3 text-center">Nama Pelanggan</th>
                        <th class="px-3 py-3 text-center">Alamat</th>
                        <th class="px-3 py-3 text-center">Phone</th>
                        <th class="px-3 py-3 text-center">Longlat</th>
                        <th class="px-3 py-3 text-center">Area</th>
                        <th class="px-3 py-3 text-center">Layanan</th>
                        <th class="px-3 py-3 text-center">Paket</th>
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
                                {{ $po->areaInfo->branch ?? ($po->branch ?? '-') }}
                            </td>
                            <td
                                class="px-3 py-3 text-xs font-semibold text-green-700 bg-green-50 dark:bg-green-900/30 dark:text-green-300">
                                {{ $po->areaInfo->regional ?? ($po->regional ?? '-') }}
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
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Edit Icon --}}
                                    <button type="button" onclick="openEditModal({{ json_encode($po) }})"
                                        class="p-1.5 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 transition-colors"
                                        title="Edit PO">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- View Icon --}}
                                    @if($po->po_document)
                                        <button type="button"
                                            onclick="openPdfModal('{{ route('admin.purchase-orders.pdf', $po->po_id) }}')"
                                            class="p-1.5 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 transition-colors"
                                            title="View PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Delete Icon --}}
                                    <form method="POST" action="{{ route('admin.purchase-orders.destroy', $po->po_id) }}"
                                        onsubmit="return confirm('Hapus Purchase Order ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 transition-colors"
                                            title="Hapus PO">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="19" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada Purchase Order.
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

    {{-- Edit PO Modal --}}
    <div id="editPoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="relative w-full max-w-2xl max-h-[90vh] mx-4 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Purchase Order</h3>
                    <button type="button" onclick="closeEditModal()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form id="editPoForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
                            <input type="text" name="brand" id="edit_brand" readonly
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP Name</label>
                            <input type="text" name="isp_name" id="edit_isp_name" readonly
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Area</label>
                            <select name="area" id="edit_area" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="JABODETABEK">JABODETABEK</option>
                                <option value="JABAR">JABAR</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">PO ID
                                (Random)</label>
                            <input type="text" name="no_order" id="edit_no_order" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">PO Number</label>
                            <div class="relative">
                                <input type="text" name="po_number" id="edit_po_number" placeholder="PO Number..."
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
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Longlat</label>
                            <input type="text" name="longlat" id="edit_longlat" readonly
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-red-600 dark:text-red-400 font-bold">No
                                Order</label>
                            <input type="text" name="admin_no_order" id="edit_admin_no_order"
                                class="bg-white border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-red-600 dark:text-white shadow-sm">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-red-600 dark:text-red-400 font-bold">No Order
                                Input</label>
                            <input type="text" name="admin_no_order_input" id="edit_admin_no_order_input"
                                class="bg-white border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-red-600 dark:text-white shadow-sm">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Nama Pelanggan
                                *</label>
                            <input type="text" name="cust_name" id="edit_cust_name" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Phone *</label>
                            <input type="text" name="phone" id="edit_phone" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Paket</label>
                            <input type="text" name="bandwidth" id="edit_bandwidth"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ODP</label>
                            <input type="text" name="odp" id="edit_odp" oninput="extractSto(this.value)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-red-600 dark:text-red-400 font-bold">STO
                                (Auto)</label>
                            <input type="text" name="sto" id="edit_sto"
                                class="bg-white border border-red-300 text-red-600 font-bold text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-red-600 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">GPON Address</label>
                            <input type="text" name="gpon" id="edit_gpon"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Branch
                                (Manual)</label>
                            <input type="text" name="branch" id="edit_branch_manual"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Regional
                                (Manual)</label>
                            <input type="text" name="regional" id="edit_regional_manual"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Alamat *</label>
                            <textarea name="cust_add" id="edit_cust_add" required rows="2"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-red-600 dark:text-red-400 font-bold">Reason
                                Cancel</label>
                            <select name="reason_cancel" id="edit_reason_cancel"
                                class="bg-white border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-red-600 dark:text-white shadow-sm">
                                <option value="">-- Pilih Alasan Cancel --</option>
                                @foreach(['Tidak ada Jaringan', 'ODP Full', 'Tarikan > 250m', 'ODP Rusak', 'ODP Unspec/Los', 'Pelanggan Hold/Batal', 'Double Input', 'Salah Data Pelanggan', 'Kendala rute', 'Tanam Tiang', 'Izin Kawasan', 'Izin Tanam Tiang', 'Ada layanan Telkom', 'Pelanggan RNA', 'Crossing Jalan/Rute tidak aman', 'Kendala SPBT', 'Order Metro'] as $reason)
                                    <option value="{{ $reason }}">{{ $reason }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-red-600 dark:text-red-400 font-bold">Category
                                Cancel</label>
                            <select name="category_cancel" id="edit_category_cancel"
                                class="bg-white border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-red-600 dark:text-white shadow-sm">
                                <option value="">-- Pilih Kategori Cancel --</option>
                                <option value="Kendala Jaringan">Kendala Jaringan</option>
                                <option value="Kendala Pelanggan">Kendala Pelanggan</option>
                                <option value="Kendala Inputer">Kendala Inputer</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Ganti Dokumen PO
                                (PDF)</label>
                            <input type="file" name="po_document" id="edit_po_document" accept=".pdf"
                                onchange="extractPoNumber(this)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti file PDF.</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                        <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Simpan</button>
                        <button type="button" onclick="closeEditModal()"
                            class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                    </div>
                </form>
            </div>
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
    {{-- Import Modal --}}
    <div id="importModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="relative w-full max-w-md mx-4">
            <div class="bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Import Purchase Order</h3>
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.purchase-orders.import') }}" method="POST" enctype="multipart/form-data">
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
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        function openEditModal(po) {
            document.getElementById('editPoForm').action = '/admin/purchase-orders/' + po.po_id;
            document.getElementById('edit_brand').value = po.brand || '';
            document.getElementById('edit_isp_name').value = po.isp_name || '';
            document.getElementById('edit_area').value = po.area || '';
            document.getElementById('edit_no_order').value = po.no_order;
            document.getElementById('edit_po_number').value = po.po_number || '';
            document.getElementById('edit_cust_name').value = po.cust_name;
            document.getElementById('edit_phone').value = po.phone;
            document.getElementById('edit_bandwidth').value = po.bandwidth || '';
            document.getElementById('edit_odp').value = po.odp || '';
            document.getElementById('edit_sto').value = po.sto || '';
            document.getElementById('edit_gpon').value = po.gpon || '';
            document.getElementById('edit_admin_no_order').value = po.admin_no_order || '';
            document.getElementById('edit_admin_no_order_input').value = po.admin_no_order_input || '';
            document.getElementById('edit_reason_cancel').value = po.reason_cancel || '';
            document.getElementById('edit_category_cancel').value = po.category_cancel || '';
            document.getElementById('edit_longlat').value = po.longlat || '';
            document.getElementById('edit_branch_manual').value = po.branch || '';
            document.getElementById('edit_regional_manual').value = po.regional || '';
            document.getElementById('edit_cust_add').value = po.cust_add;
            document.getElementById('po_ocr_status').textContent = '';
            document.getElementById('edit_po_document').value = '';
            document.getElementById('editPoModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function extractSto(odpValue) {
            if (!odpValue) return;
            // Pattern: ODP-{STO}-{DATA}
            // Example: ODP-JTN-FAC/02 -> JTN
            const parts = odpValue.split('-');
            if (parts.length >= 2) {
                const sto = parts[1];
                document.getElementById('edit_sto').value = sto.toUpperCase();
            }
        }

        // === HYBRID PO EXTRACTION ===
        async function extractPoNumber(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            const loading = document.getElementById('po_number_loading');
            const status = document.getElementById('po_ocr_status');
            const poNumberInput = document.getElementById('edit_po_number');

            loading.classList.remove('hidden');
            status.textContent = '🤖 AI sedang menganalisa...';
            status.className = 'mt-1 text-xs text-blue-600 dark:text-blue-400 font-medium';

            // Step 1: Try AI
            try {
                const formData = new FormData();
                formData.append('po_document', file);
                formData.append('_token', '{{ csrf_token() }}');
                const response = await fetch('/ai/extract-po', { method: 'POST', body: formData, headers: { 'Accept': 'application/json' } });
                const data = await response.json();
                if (!data.error && data.po_number) {
                    poNumberInput.value = data.po_number;
                    status.textContent = '✅ PO Number ditemukan oleh AI!';
                    status.className = 'mt-1 text-xs text-green-600 dark:text-green-400 font-bold';
                    loading.classList.add('hidden');
                    return;
                }
            } catch (e) { console.warn('AI fallback:', e.message); }

            // Step 2: Local pdf.js fallback
            status.textContent = '📄 Membaca PDF lokal...';
            try {
                const arrayBuffer = await file.arrayBuffer();
                const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                let allText = '';
                for (let i = 1; i <= Math.min(pdf.numPages, 3); i++) {
                    const page = await pdf.getPage(i);
                    const tc = await page.getTextContent();
                    allText += tc.items.map(item => item.str).join(' ') + '\n';
                }
                console.log('LOCAL PDF TEXT:', allText);
                const poNumber = findPoNumber(allText);
                if (poNumber) {
                    poNumberInput.value = poNumber;
                    status.textContent = '✅ PO Number berhasil ditemukan!';
                    status.className = 'mt-1 text-xs text-green-600 dark:text-green-400 font-bold';
                } else {
                    status.textContent = '⚠️ PO Number tidak ditemukan. Input manual.';
                    status.className = 'mt-1 text-xs text-orange-600 dark:text-orange-400 font-medium';
                    poNumberInput.placeholder = 'Input manual di sini...';
                }
            } catch (error) {
                status.textContent = '❌ Gagal membaca PDF.';
                status.className = 'mt-1 text-xs text-red-600 dark:text-red-400 font-medium';
            } finally { loading.classList.add('hidden'); }
        }

        function findPoNumber(text) {
            let t = text.replace(/\s+/g, ' ').trim();

            // Step 1: Find PO label and extract value using token-based approach
            const labelRegex = /(?:No\s*\.?\s*PO|Nomor\s+PO|PO\s*Number|Purchase\s*Order\s*No\s*\.?)\s*[:\-]\s*/gi;
            const labelMatch = labelRegex.exec(t);

            if (labelMatch) {
                const startPos = labelMatch.index + labelMatch[0].length;
                const afterLabel = t.substring(startPos, startPos + 80);
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
                    } else { break; }
                }

                if (poParts.length > 0) {
                    let result = poParts.join('');
                    result = result.replace(/\s*([\/\-\.])\s*/g, '$1');
                    result = result.replace(/[\/\-\.]$/, '');
                    if (result.length >= 3 && /\d/.test(result)) return result;
                }
            }

            // Step 2: Structural patterns
            const structPatterns = [
                /\b(\d{1,4}\s*[\-\.]\s*[A-Z]{2,}(?:\s*[\/\-\.]\s*[A-Z0-9]{1,}){2,})\b/i,
                /\b(\d{1,4}\s*\/\s*[A-Z]{2,}(?:\s*\/\s*[A-Z0-9]{1,}){2,})\b/i,
                /\b([A-Z0-9]{2,}(?:\s*[\/\-\.]\s*[A-Z0-9]{1,}){2,})\b/i,
                /\b(PO\s*[\-\/]?\s*\d{3,}[A-Z0-9\-\/\.]*)\b/i,
            ];
            for (const p of structPatterns) {
                const m = t.match(p);
                if (m && m[1]) { let r = m[1].trim().replace(/\s*([\/\-\.])\s*/g, '$1'); if (r.length >= 4 && (/\d/.test(r) || /[\/\-]/.test(r))) return r; }
            }
            return null;
        }

        function closeEditModal() {
            document.getElementById('editPoModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

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
            const editModal = document.getElementById('editPoModal');
            const pdfModal = document.getElementById('pdfModal');
            if (event.target === editModal) closeEditModal();
            if (event.target === pdfModal) closePdfModal();
        }
    </script>
    @if(auth()->user()->role === 'Super Admin')
        <!-- Clear Data Modal -->
        <div id="clearPoModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="clearPoModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus
                            SEMUA data Purchase Order?</h3>
                        <div class="flex justify-center gap-3">
                            <form action="{{ route('admin.purchase-orders.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                    Ya, Hapus Semua
                                </button>
                            </form>
                            <button data-modal-hide="clearPoModal" type="button"
                                class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tidak,
                                Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endpush