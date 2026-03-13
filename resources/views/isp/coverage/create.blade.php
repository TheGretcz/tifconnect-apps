@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Coverage Request</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi formulir untuk mengajukan cek coverage baru</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('isp.coverage.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Auto-filled fields --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
                    <input type="text" value="{{ $brand }}" readonly
                        class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ISP Name</label>
                    <input type="text" value="{{ $ispName }}" readonly
                        class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed">
                </div>

                <div>
                    <label for="area" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Area <span
                            class="text-red-500">*</span></label>
                    <select name="area" id="area" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Area</option>
                        <option value="JABODETABEK" {{ old('area', auth()->user()->area) == 'JABODETABEK' ? 'selected' : '' }}>JABODETABEK</option>
                        <option value="JABAR" {{ old('area', auth()->user()->area) == 'JABAR' ? 'selected' : '' }}>JABAR
                        </option>
                    </select>
                </div>

                <div>
                    <label for="layanan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Layanan <span
                            class="text-red-500">*</span></label>
                    <select name="layanan" id="layanan" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Layanan</option>
                        <option value="Vula" {{ old('layanan') == 'Vula' ? 'selected' : '' }}>Vula</option>
                        <option value="Bitstream" {{ old('layanan') == 'Bitstream' ? 'selected' : '' }}>Bitstream</option>
                        <option value="Metro Ethernet" {{ old('layanan') == 'Metro Ethernet' ? 'selected' : '' }}>Metro
                            Ethernet</option>
                    </select>
                </div>

                <div>
                    <label for="paket" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Paket <span
                            class="text-red-500">*</span></label>
                    <select name="paket" id="paket" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Paket</option>
                        <option value="Standar" {{ old('paket') == 'Standar' ? 'selected' : '' }}>Standar</option>
                        <option value="Lite" {{ old('paket') == 'Lite' ? 'selected' : '' }}>Lite</option>
                    </select>
                </div>

                <div>
                    <label for="kode_pra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
                        PRA</label>
                    <div class="flex items-center gap-2">
                        <input type="text" value="{{ $nextKodePra }}" disabled
                            class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed font-mono font-bold">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label for="cust_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer
                        Name <span class="text-red-500">*</span></label>
                    <input type="text" name="cust_name" id="cust_name" value="{{ old('cust_name') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Nama customer">
                </div>

                <div>
                    <label for="bandwidth" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bandwidth
                        <span class="text-red-500">*</span></label>
                    <select name="bandwidth" id="bandwidth" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Bandwidth</option>
                        <option value="20 Mbps" {{ old('bandwidth') == '20 Mbps' ? 'selected' : '' }}>20 Mbps</option>
                        <option value="30 Mbps" {{ old('bandwidth') == '30 Mbps' ? 'selected' : '' }}>30 Mbps</option>
                        <option value="40 Mbps" {{ old('bandwidth') == '40 Mbps' ? 'selected' : '' }}>40 Mbps</option>
                        <option value="50 Mbps" {{ old('bandwidth') == '50 Mbps' ? 'selected' : '' }}>50 Mbps</option>
                        <option value="100 Mbps" {{ old('bandwidth') == '100 Mbps' ? 'selected' : '' }}>100 Mbps</option>
                        <option value="200 Mbps" {{ old('bandwidth') == '200 Mbps' ? 'selected' : '' }}>200 Mbps</option>
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-2">
                    <label for="cust_add" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer
                        Address <span class="text-red-500">*</span></label>
                    <textarea name="cust_add" id="cust_add" required rows="3"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Alamat lengkap customer">{{ old('cust_add') }}</textarea>
                </div>

                <div>
                    <label for="longlat" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Long/Lat <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="longlat" id="longlat" value="{{ old('longlat') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Contoh: -6.200000, 106.816666">
                </div>
            </div>

            {{-- Status info --}}
            <div
                class="mt-6 p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">Status request akan otomatis diset ke
                        <strong>PROCESSING</strong> dan akan diupdate oleh Admin.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6">
                <button type="submit"
                    class="text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 font-medium rounded-lg text-sm px-6 py-3 shadow-lg shadow-red-600/25 transition-all">
                    Submit Request
                </button>
                <a href="{{ route('isp.dashboard') }}"
                    class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-6 py-3 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection