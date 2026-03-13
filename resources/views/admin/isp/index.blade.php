@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage ISP</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data ISP partner</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button data-modal-target="addIspModal" data-modal-toggle="addIspModal"
                class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 shadow-sm transition-all">
                <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Tambah ISP
            </button>
            <a href="{{ route('admin.isp.export') }}"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm transition-all">
                <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
                Export CSV
            </a>
            <a href="{{ route('admin.isp.template') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 shadow-sm transition-all">
                Template CSV
            </a>
            <button data-modal-target="importIspModal" data-modal-toggle="importIspModal"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-all">
                Import CSV
            </button>
            @if(auth()->user()->role === 'Super Admin')
                <button data-modal-target="clearIspModal" data-modal-toggle="clearIspModal"
                    class="px-4 py-2 text-sm font-medium text-red-600 bg-white border-2 border-red-600 rounded-lg hover:bg-red-600 hover:text-white shadow-sm transition-all">
                    Clear Data
                </button>
            @endif
        </div>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.isp.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari ISP Code, Brand, Name, Area..."
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

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center text-gray-600 dark:text-gray-300">
                <thead class="text-xs font-semibold text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:text-gray-300 border-b-2 border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-center">No</th>
                        <th class="px-4 py-3 text-center">ISP Code</th>
                        <th class="px-4 py-3 text-center">Brand</th>
                        <th class="px-4 py-3 text-center">ISP Name</th>
                        <th class="px-4 py-3 text-center">Area</th>
                        <th class="px-4 py-3 text-center">BA</th>
                        <th class="px-4 py-3 text-center">CA</th>
                        <th class="px-4 py-3 text-center">SID</th>
                        <th class="px-4 py-3 text-center">VLAN</th>
                        <th class="px-4 py-3 text-center">Layanan</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($isps as $index => $isp)
                        <tr
                            class="even:bg-gray-50/50 dark:even:bg-gray-800/50 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $isps->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $isp->isp_code }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $isp->isp_brand }}</td>
                            <td class="px-4 py-3">{{ $isp->isp_name }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">{{ $isp->area }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $isp->ba }}</td>
                            <td class="px-4 py-3">{{ $isp->ca }}</td>
                            <td class="px-4 py-3">{{ $isp->sid }}</td>
                            <td class="px-4 py-3">{{ $isp->vlan }}</td>
                            <td class="px-4 py-3">{{ $isp->layanan }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Edit Icon --}}
                                    <button data-modal-target="editIspModal-{{ $isp->isp_code }}"
                                        data-modal-toggle="editIspModal-{{ $isp->isp_code }}"
                                        class="p-1.5 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 transition-colors"
                                        title="Edit ISP">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Delete Icon --}}
                                    <form method="POST" action="{{ route('admin.isp.destroy', $isp->isp_code) }}"
                                        onsubmit="return confirm('Hapus ISP ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 transition-colors"
                                            title="Hapus ISP">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        <div id="editIspModal-{{ $isp->isp_code }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                <div
                                    class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit ISP</h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600"
                                            data-modal-hide="editIspModal-{{ $isp->isp_code }}">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.isp.update', $isp->isp_code) }}">
                                        @csrf @method('PUT')
                                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP
                                                    Brand *</label><input type="text" name="isp_brand"
                                                    value="{{ $isp->isp_brand }}" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP
                                                    Name *</label><input type="text" name="isp_name"
                                                    value="{{ $isp->isp_name }}" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Area
                                                    *</label><input type="text" name="area" value="{{ $isp->area }}" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">BA</label><input
                                                    type="text" name="ba" value="{{ $isp->ba }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">CA</label><input
                                                    type="text" name="ca" value="{{ $isp->ca }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">SID</label><input
                                                    type="text" name="sid" value="{{ $isp->sid }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">VLAN</label><input
                                                    type="text" name="vlan" value="{{ $isp->vlan }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Layanan</label><input
                                                    type="text" name="layanan" value="{{ $isp->layanan }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                        </div>
                                        <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                                            <button type="submit"
                                                class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Simpan</button>
                                            <button data-modal-hide="editIspModal-{{ $isp->isp_code }}" type="button"
                                                class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data ISP.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $isps->links() }}
        </div>
    </div>

    {{-- Add ISP Modal --}}
    <div id="addIspModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div
                class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah ISP Baru</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600"
                        data-modal-hide="addIspModal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.isp.store') }}">
                    @csrf
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <div
                                class="p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <p class="text-sm text-blue-800 dark:text-blue-300">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <strong>Info:</strong> Kode ISP akan dibuat secara otomatis oleh sistem.
                                </p>
                            </div>
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP Brand
                                *</label><input type="text" name="isp_brand" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP Name
                                *</label><input type="text" name="isp_name" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Area
                                *</label><input type="text" name="area" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Contoh: JABODETABEK"></div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">BA</label><input
                                type="text" name="ba"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">CA</label><input
                                type="text" name="ca"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">SID</label><input
                                type="text" name="sid"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">VLAN</label><input
                                type="text" name="vlan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Layanan</label><input
                                type="text" name="layanan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>
                    <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                        <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Tambah
                            ISP</button>
                        <button data-modal-hide="addIspModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div id="importIspModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div
                class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Import Data ISP</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600"
                        data-modal-hide="importIspModal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.isp.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Upload file CSV/Excel sesuai template. <a
                                href="{{ route('admin.isp.template') }}" class="text-red-600 hover:underline">Download
                                Template</a></p>
                        <input type="file" name="file" accept=".csv,.xlsx,.xls" required
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    </div>
                    <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Import</button>
                        <button data-modal-hide="importIspModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Clear Data Modal --}}
    @if(auth()->user()->role === 'Super Admin')
        <div id="clearIspModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div
                    class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus
                            SEMUA data ISP? Tindakan ini tidak dapat dibatalkan.</h3>
                        <form action="{{ route('admin.isp.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                Ya, Hapus Semua
                            </button>
                            <button data-modal-hide="clearIspModal" type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection