@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Check Coverage</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola permintaan cek coverage ISP</p>
        </div>
        <div class="flex flex-wrap gap-2 sm:ml-auto">
            <a href="{{ route('admin.coverage.template') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                Download Template
            </a>
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all">
                Import CSV
            </button>
            <a href="{{ route('admin.coverage.export') }}"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">
                Export CSV
            </a>
            <button data-modal-target="addCoverageModal" data-modal-toggle="addCoverageModal"
                class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 shadow-sm transition-all">
                <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Tambah Request
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.coverage.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari customer, ISP, phone..."
                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full ps-10 p-2.5">
            </div>
            <select name="status"
                class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 p-2.5">
                <option value="">Semua Status</option>
                <option value="PROCESSING" {{ request('status') == 'PROCESSING' ? 'selected' : '' }}>Processing</option>
                <option value="COVERED" {{ request('status') == 'COVERED' ? 'selected' : '' }}>Covered</option>
                <option value="NOT COVERED" {{ request('status') == 'NOT COVERED' ? 'selected' : '' }}>Not Covered</option>
            </select>
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

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center text-gray-600 dark:text-gray-300">
                <thead class="text-xs font-semibold text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:text-gray-300 border-b-2 border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-3 py-3 text-center">No</th>
                        <th class="px-3 py-3 text-center">Kode PRA</th>
                        <th class="px-3 py-3 text-center">Brand</th>
                        <th class="px-3 py-3 text-center">ISP Name</th>
                        <th class="px-3 py-3 text-center">Customer</th>
                        <th class="px-3 py-3 text-center">Address</th>
                        <th class="px-3 py-3 text-center">Phone</th>
                        <th class="px-3 py-3 text-center">Longlat</th>
                        <th class="px-3 py-3 text-center">Layanan</th>
                        <th class="px-3 py-3 text-center">Paket</th>
                        <th class="px-3 py-3 text-center">Bandwidth</th>
                        <th class="px-3 py-3 text-center">Area</th>
                        <th class="px-3 py-3 text-center">ODP</th>
                        <th class="px-3 py-3 text-center">GPON</th>
                        <th class="px-3 py-3 text-center">Status</th>
                        <th class="px-3 py-3 text-center">Aksi</th>
                        <th class="px-3 py-3 text-center">Created at</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($requests as $index => $req)
                        <tr
                            class="even:bg-gray-50/50 dark:even:bg-gray-800/50 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200">
                            <td class="px-3 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $requests->firstItem() + $index }}
                            </td>
                            <td class="px-3 py-3 font-mono text-xs">{{ $req->kode_pra ?? '-' }}</td>
                            <td class="px-3 py-3">{{ $req->brand }}</td>
                            <td class="px-3 py-3">{{ $req->isp_name }}</td>
                            <td class="px-3 py-3 font-medium text-gray-900 dark:text-white">{{ $req->cust_name }}</td>
                            <td class="px-3 py-3 max-w-[150px] truncate" title="{{ $req->cust_add }}">{{ $req->cust_add }}</td>
                            <td class="px-3 py-3">{{ $req->phone }}</td>
                            <td class="px-3 py-3 font-mono text-xs">{{ $req->longlat ?? '-' }}</td>
                            <td class="px-3 py-3">{{ $req->layanan }}</td>
                            <td class="px-3 py-3">{{ $req->paket }}</td>
                            <td class="px-3 py-3">{{ $req->bandwidth }}</td>
                            <td class="px-3 py-3">{{ $req->area }}</td>
                            <td class="px-3 py-3 font-mono text-xs">{{ $req->odp ?? '-' }}</td>
                            <td class="px-3 py-3 font-mono text-xs">{{ $req->gpon ?? '-' }}</td>
                            <td class="px-3 py-3">
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
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Edit Icon --}}
                                    <button data-modal-target="editCovModal-{{ $req->req_id }}"
                                        data-modal-toggle="editCovModal-{{ $req->req_id }}"
                                        class="p-1.5 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 transition-colors"
                                        title="Edit Coverage">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Delete Icon --}}
                                    <form method="POST" action="{{ route('admin.coverage.destroy', $req->req_id) }}"
                                        onsubmit="return confirm('Hapus request ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 transition-colors"
                                            title="Hapus Coverage">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-400">
                                {{ $req->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        <div id="editCovModal-{{ $req->req_id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-3xl max-h-full">
                                <div
                                    class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Coverage Request
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600"
                                            data-modal-hide="editCovModal-{{ $req->req_id }}">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.coverage.update', $req->req_id) }}">
                                        @csrf @method('PUT')
                                        <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Brand
                                                    *</label><input type="text" name="brand" value="{{ $req->brand }}" readonly
                                                    class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 cursor-not-allowed">
                                            </div>
                                            <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP
                                                    Name *</label><input type="text" name="isp_name"
                                                    value="{{ $req->isp_name }}" readonly
                                                    class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 cursor-not-allowed">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Area
                                                    *</label>
                                                <select name="area" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="JABODETABEK" {{ $req->area == 'JABODETABEK' ? 'selected' : '' }}>JABODETABEK</option>
                                                    <option value="JABAR" {{ $req->area == 'JABAR' ? 'selected' : '' }}>JABAR
                                                    </option>
                                                </select>
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Layanan
                                                    *</label>
                                                <select name="layanan" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="Vula" {{ $req->layanan == 'Vula' ? 'selected' : '' }}>Vula
                                                    </option>
                                                    <option value="Bitstream" {{ $req->layanan == 'Bitstream' ? 'selected' : '' }}>Bitstream</option>
                                                    <option value="Metro Ethernet" {{ $req->layanan == 'Metro Ethernet' ? 'selected' : '' }}>Metro Ethernet</option>
                                                </select>
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Paket
                                                    *</label>
                                                <select name="paket" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="Standar" {{ $req->paket == 'Standar' ? 'selected' : '' }}>
                                                        Standar</option>
                                                    <option value="Lite" {{ $req->paket == 'Lite' ? 'selected' : '' }}>Lite
                                                    </option>
                                                </select>
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Kode
                                                    PRA</label><input type="text" name="kode_pra" value="{{ $req->kode_pra }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Phone
                                                    *</label><input type="text" name="phone" value="{{ $req->phone }}" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Customer
                                                    Name *</label><input type="text" name="cust_name"
                                                    value="{{ $req->cust_name }}" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Bandwidth
                                                    *</label>
                                                <select name="bandwidth" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="" disabled>Pilih Bandwidth</option>
                                                    <option value="20 Mbps" {{ $req->bandwidth == '20 Mbps' ? 'selected' : '' }}>
                                                        20 Mbps</option>
                                                    <option value="30 Mbps" {{ $req->bandwidth == '30 Mbps' ? 'selected' : '' }}>
                                                        30 Mbps</option>
                                                    <option value="40 Mbps" {{ $req->bandwidth == '40 Mbps' ? 'selected' : '' }}>
                                                        40 Mbps</option>
                                                    <option value="50 Mbps" {{ $req->bandwidth == '50 Mbps' ? 'selected' : '' }}>
                                                        50 Mbps</option>
                                                    <option value="100 Mbps" {{ $req->bandwidth == '100 Mbps' ? 'selected' : '' }}>100 Mbps</option>
                                                    <option value="200 Mbps" {{ $req->bandwidth == '200 Mbps' ? 'selected' : '' }}>200 Mbps</option>
                                                </select>
                                            </div>
                                            <div class="md:col-span-2"><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Customer
                                                    Address *</label><textarea name="cust_add" required rows="2"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ $req->cust_add }}</textarea>
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Long/Lat
                                                    *</label><input type="text" name="longlat" value="{{ $req->longlat }}"
                                                    required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>

                                            {{-- Admin only fields --}}
                                            <div
                                                class="p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                                                <label class="block mb-1 text-sm font-medium text-red-700 dark:text-red-400">ODP
                                                    (Admin)</label>
                                                <input type="text" name="odp" value="{{ $req->odp }}"
                                                    class="bg-white border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-red-600 dark:text-white">
                                            </div>
                                            <div
                                                class="p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                                                <label
                                                    class="block mb-1 text-sm font-medium text-red-700 dark:text-red-400">GPON
                                                    (Admin)</label>
                                                <input type="text" name="gpon" value="{{ $req->gpon }}"
                                                    class="bg-white border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-red-600 dark:text-white">
                                            </div>
                                            <div
                                                class="p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                                                <label
                                                    class="block mb-1 text-sm font-medium text-red-700 dark:text-red-400">Status
                                                    (Admin)</label>
                                                <select name="status" required
                                                    class="bg-white border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-red-600 dark:text-white">
                                                    <option value="PROCESSING" {{ $req->status == 'PROCESSING' ? 'selected' : '' }}>PROCESSING</option>
                                                    <option value="COVERED" {{ $req->status == 'COVERED' ? 'selected' : '' }}>
                                                        COVERED</option>
                                                    <option value="NOT COVERED" {{ $req->status == 'NOT COVERED' ? 'selected' : '' }}>NOT COVERED</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                                            <button type="submit"
                                                class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Simpan</button>
                                            <button data-modal-hide="editCovModal-{{ $req->req_id }}" type="button"
                                                class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="17" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data
                                coverage request.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $requests->links() }}
        </div>
    </div>

    {{-- Add Coverage Modal --}}
    <div id="addCoverageModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-3xl max-h-full">
            <div
                class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Coverage Request</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600"
                        data-modal-hide="addCoverageModal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.coverage.store') }}">
                    @csrf
                    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Brand
                                *</label><input type="text" name="brand" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP Name
                                *</label><input type="text" name="isp_name" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Area
                                *</label>
                            <select name="area" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="JABODETABEK">JABODETABEK</option>
                                <option value="JABAR">JABAR</option>
                            </select>
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Layanan *</label>
                            <select name="layanan" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="Vula">Vula</option>
                                <option value="Bitstream">Bitstream</option>
                                <option value="Metro Ethernet">Metro Ethernet</option>
                            </select>
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Paket *</label>
                            <select name="paket" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="Standar">Standar</option>
                                <option value="Lite">Lite</option>
                            </select>
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Kode
                                PRA</label><input type="text" name="kode_pra"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Phone
                                *</label><input type="text" name="phone" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Customer Name
                                *</label><input type="text" name="cust_name" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Bandwidth
                                *</label>
                            <select name="bandwidth" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="" disabled selected>Pilih Bandwidth</option>
                                <option value="20 Mbps">20 Mbps</option>
                                <option value="30 Mbps">30 Mbps</option>
                                <option value="40 Mbps">40 Mbps</option>
                                <option value="50 Mbps">50 Mbps</option>
                                <option value="100 Mbps">100 Mbps</option>
                                <option value="200 Mbps">200 Mbps</option>
                            </select>
                        </div>
                        <div class="md:col-span-2"><label
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Customer Address
                                *</label><textarea name="cust_add" required rows="2"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Long/Lat
                                *</label><input type="text" name="longlat" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ODP</label><input
                                type="text" name="odp"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">GPON</label><input
                                type="text" name="gpon"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Status *</label>
                            <select name="status" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="PROCESSING">PROCESSING</option>
                                <option value="COVERED">COVERED</option>
                                <option value="NOT COVERED">NOT COVERED</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                        <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Tambah
                            Request</button>
                        <button data-modal-hide="addCoverageModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div id="importModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="relative w-full max-w-md mx-4">
            <div class="bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Import Coverage Requests</h3>
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.coverage.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Pilih
                            File CSV</label>
                        <input name="file"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="file_input" type="file" accept=".csv" required>
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