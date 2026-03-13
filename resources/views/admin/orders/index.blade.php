@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Order</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data order — Teritori 2.</p>
        </div>
        <div class="flex flex-wrap gap-2 justify-start md:justify-end">
            <button data-modal-target="addOrderModal" data-modal-toggle="addOrderModal"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Order
            </button>
            <a href="{{ route('admin.orders.export') }}"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </a>
            <a href="{{ route('admin.orders.template') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Template CSV
            </a>
            <button data-modal-target="importModal" data-modal-toggle="importModal"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Import CSV
            </button>
            <button data-modal-target="clearDataModal" data-modal-toggle="clearDataModal"
                class="inline-flex items-center px-4 py-2 border-2 border-red-600 text-red-600 hover:bg-red-600 hover:text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Clear Data
            </button>
        </div>
    </div>
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
        {{-- Total Order Card --}}
        @php $isTotalActive = !request('status'); @endphp
        <a href="{{ route('admin.orders.index') }}" 
           style="background-color: {{ $isTotalActive ? '#111827' : '#FFFFFF' }}; border-color: {{ $isTotalActive ? '#111827' : '#E5E7EB' }};"
           class="group relative overflow-hidden p-5 rounded-2xl shadow-sm border-2 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-20 h-20 bg-white/5 rounded-full blur-2xl"></div>
            <div class="relative flex items-center gap-4">
                <div class="p-4 bg-red-600 text-white rounded-2xl shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black {{ $isTotalActive ? 'text-gray-400' : 'text-gray-500' }} uppercase tracking-widest leading-none mb-1">TOTAL DATA</p>
                    <h4 class="text-2xl font-black {{ $isTotalActive ? 'text-white' : 'text-gray-900' }} leading-tight font-mono">{{ number_format($totalOrders) }}</h4>
                    <p class="text-[11px] font-bold {{ $isTotalActive ? 'text-gray-500' : 'text-gray-400' }}">Order Teritori 2</p>
                </div>
            </div>
        </a>

        @php
            $statusConfigs = [
                'PROVISION COMPLETED' => [
                    'bg' => '#ECFDF5', 
                    'border' => '#10B981', 
                    'icon_bg' => '#10B981', 
                    'text' => '#065F46', 
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 
                    'label' => 'COMPLETED'
                ],
                'PROVISION START' => [
                    'bg' => '#EFF6FF', 
                    'border' => '#3B82F6', 
                    'icon_bg' => '#3B82F6', 
                    'text' => '#1E40AF', 
                    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 
                    'label' => 'START'
                ],
                'FALLOUT' => [
                    'bg' => '#FFF7ED', 
                    'border' => '#F97316', 
                    'icon_bg' => '#F97316', 
                    'text' => '#9A3412', 
                    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 
                    'label' => 'FALLOUT'
                ],
                'CANCELED' => [
                    'bg' => '#FEF2F2', 
                    'border' => '#EF4444', 
                    'icon_bg' => '#EF4444', 
                    'text' => '#991B1B', 
                    'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 
                    'label' => 'CANCELED'
                ],
                'CANCELED INPUT' => [
                    'bg' => '#FFF1F2', 
                    'border' => '#F43F5E', 
                    'icon_bg' => '#F43F5E', 
                    'text' => '#9F1239', 
                    'icon' => 'M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z', 
                    'label' => 'CANCEL INPUT'
                ],
                'PROVISION ISSUED' => [
                    'bg' => '#EEF2FF', 
                    'border' => '#6366F1', 
                    'icon_bg' => '#6366F1', 
                    'text' => '#3730A3', 
                    'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 
                    'label' => 'ISSUED'
                ],
                'OSS TESTING SERVICE' => [
                    'bg' => '#ECFEFF', 
                    'border' => '#06B6D4', 
                    'icon_bg' => '#06B6D4', 
                    'text' => '#164E63', 
                    'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 
                    'label' => 'OSS TESTING'
                ],
                'WAITING MILESTONE' => [
                    'bg' => '#FFFBEB', 
                    'border' => '#F59E0B', 
                    'icon_bg' => '#F59E0B', 
                    'text' => '#92400E', 
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 
                    'label' => 'WAITING'
                ],
            ];
        @endphp

        @foreach($statusCounts as $status => $count)
            @php
                $config = $statusConfigs[$status] ?? [
                    'bg' => '#F9FAFB', 
                    'border' => '#9CA3AF', 
                    'icon_bg' => '#6B7280', 
                    'text' => '#4B5563', 
                    'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 
                    'label' => $status
                ];
                $isActive = request('status') === $status;
            @endphp
            <a href="{{ route('admin.orders.index', ['status' => $status]) }}" 
               style="background-color: {{ $isActive ? $config['bg'] : '#FFFFFF' }}; border-color: {{ $isActive ? $config['border'] : '#F3F4F6' }};"
               class="group relative overflow-hidden p-5 rounded-2xl shadow-sm border-2 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 {{ $isActive ? 'shadow-md ring-2 ring-offset-2' : '' }}"
               {{ $isActive ? 'style=--tw-ring-color:'.$config['border'] : '' }}>
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-20 h-20 bg-gray-500/5 rounded-full blur-2xl"></div>
                <div class="relative flex items-center gap-4">
                    <div class="p-4 rounded-xl shadow-lg group-hover:scale-110 transition-transform" style="background-color: {{ $config['icon_bg'] }}; color: white;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest leading-none mb-1" style="color: {{ $config['text'] }};">{{ $config['label'] }}</p>
                        <h4 class="text-2xl font-black text-gray-900 leading-tight font-mono">{{ number_format($count) }}</h4>
                        <p class="text-[11px] font-bold text-gray-400">Order Status</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Branch Status Summary Table --}}
    <div class="mb-10">
        <div class="flex items-center gap-2 mb-4">
            <div class="h-6 w-1 bg-red-600 rounded-full"></div>
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Summary Order per Branch</h2>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-4 font-black text-gray-900 dark:text-white text-left uppercase tracking-widest text-[10px] border border-gray-200 dark:border-gray-600">Branch</th>
                            @foreach($allStatuses as $status)
                                <th class="px-4 py-4 font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest text-[10px] min-w-[120px] border border-gray-200 dark:border-gray-700">{{ $status }}</th>
                            @endforeach
                            <th class="px-6 py-4 font-black text-red-600 dark:text-red-400 uppercase tracking-widest text-[10px] bg-red-50/50 dark:bg-red-900/10 border border-gray-200 dark:border-gray-700">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php $footerTotals = array_fill_keys($allStatuses, 0); $grandTotal = 0; @endphp
                        @foreach($summaryTable as $branch => $statuses)
                            @php $rowTotal = 0; @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-left bg-gray-50/30 dark:bg-gray-700/10 border border-gray-200 dark:border-gray-600 whitespace-nowrap">{{ $branch }}</td>
                                @foreach($allStatuses as $status)
                                    @php 
                                        $count = $statuses[$status] ?? 0; 
                                        $rowTotal += $count;
                                        $footerTotals[$status] += $count;
                                    @endphp
                                    <td class="px-4 py-4 border border-gray-200 dark:border-gray-700 {{ $count > 0 ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-300 dark:text-gray-600' }}">
                                        {{ $count > 0 ? number_format($count) : '-' }}
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 font-black text-gray-900 dark:text-white bg-gray-50/50 dark:bg-gray-700/20 border border-gray-200 dark:border-gray-700 font-mono">
                                    {{ number_format($rowTotal) }}
                                    @php $grandTotal += $rowTotal; @endphp
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900 dark:bg-gray-700 text-white font-black">
                        <tr>
                            <td class="px-6 py-4 text-left uppercase tracking-widest text-[10px] border border-gray-800 dark:border-gray-600">TOTAL NASIONAL</td>
                            @foreach($allStatuses as $status)
                                <td class="px-4 py-4 font-mono border border-gray-800 dark:border-gray-600">{{ number_format($footerTotals[$status]) }}</td>
                            @endforeach
                            <td class="px-6 py-4 text-red-400 font-mono border border-gray-800 dark:border-gray-600 bg-black/20">{{ number_format($grandTotal) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


    {{-- Search Bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M16.65 11A5.65 5.65 0 1111 5.35 5.65 5.65 0 0116.65 11z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-red-500 focus:border-red-500 sm:text-sm"
                    placeholder="Cari No Order, Layanan, OLO, STO, ND, Paket...">
            </div>
            <button type="submit"
                class="inline-flex items-center justify-center px-4 py-2 bg-gray-900 dark:bg-white dark:text-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors">
                Filter
            </button>
            @if (request()->has('search') || request()->has('status'))
                <a href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center text-gray-600 dark:text-gray-300">
                <thead
                    class="text-xs font-semibold text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:text-gray-300 border-b-2 border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center">No</th>
                        <th class="px-6 py-4 font-semibold text-center whitespace-nowrap">Area</th>
                        <th class="px-6 py-4 font-semibold text-center whitespace-nowrap">Regional</th>
                        <th class="px-6 py-4 font-semibold text-center whitespace-nowrap">Branch</th>
                        <th class="px-6 py-4 font-semibold text-center">STO</th>
                        <th class="px-6 py-4 font-semibold text-center">Layanan</th>
                        <th class="px-6 py-4 font-semibold text-center">Teritory</th>
                        <th class="px-6 py-4 font-semibold text-center">Paket</th>
                        <th class="px-6 py-4 font-semibold text-center">No Order</th>
                        <th class="px-6 py-4 font-semibold text-center">OLO</th>
                        <th class="px-6 py-4 font-semibold text-center">ND</th>
                        <th class="px-6 py-4 font-semibold text-center">Status Order</th>
                        <th class="px-6 py-4 font-semibold text-center">Keterangan</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($orders as $index => $order)
                        <tr
                            class="even:bg-gray-50/50 dark:even:bg-gray-800/50 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $orders->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold uppercase text-gray-500">{{ $order->areaInfo->area ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold uppercase text-gray-500">{{ $order->areaInfo->regional ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold uppercase text-gray-500">{{ $order->areaInfo->branch ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap uppercase font-bold text-red-600">{{ $order->sto }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->layanan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $order->teritory }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->paket }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                {{ $order->no_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->olo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->nd }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColor = match (strtolower($order->status_order ?? '')) {
                                        'completed', 'complete', 'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'in progress', 'proses' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'cancelled', 'cancel', 'batal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ $order->status_order }}
                                </span>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate" title="{{ $order->keterangan }}">{{ $order->keterangan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Edit Icon --}}
                                    <button data-modal-target="editOrderModal{{ $order->id }}"
                                        data-modal-toggle="editOrderModal{{ $order->id }}"
                                        class="p-1.5 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 transition-colors"
                                        title="Edit Order">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Delete Icon --}}
                                    <button data-modal-target="deleteModal{{ $order->id }}"
                                        data-modal-toggle="deleteModal{{ $order->id }}"
                                        class="p-1.5 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 transition-colors"
                                        title="Hapus Order">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        <div id="editOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-lg max-h-full">
                                <div class="relative bg-white rounded-xl shadow dark:bg-gray-800">
                                    <div
                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Order</h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-toggle="editOrderModal{{ $order->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST"
                                        class="p-4 md:p-5">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid gap-4 mb-4 grid-cols-2">
                                            <div class="col-span-2">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Layanan</label>
                                                <input type="text" name="layanan" value="{{ $order->layanan }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div class="col-span-2">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Paket</label>
                                                <input type="text" name="paket" value="{{ $order->paket }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div class="col-span-1">
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No
                                                    Order</label>
                                                <input type="text" name="no_order" value="{{ $order->no_order }}" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div class="col-span-1">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">OLO</label>
                                                <input type="text" name="olo" value="{{ $order->olo }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div class="col-span-1">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">STO</label>
                                                <input type="text" name="sto" value="{{ $order->sto }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white uppercase">
                                            </div>
                                            <div class="col-span-1">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ND</label>
                                                <input type="text" name="nd" value="{{ $order->nd }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div class="col-span-2">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status
                                                    Order</label>
                                                <input type="text" name="status_order" value="{{ $order->status_order }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div class="col-span-2">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                                                <textarea name="keterangan" rows="2"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ $order->keterangan }}</textarea>
                                            </div>
                                        </div>
                                        <button type="submit"
                                            class="text-white inline-flex items-center bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full justify-center">
                                            Update Order
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Delete Modal --}}
                        <div id="deleteModal{{ $order->id }}" tabindex="-1"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-xl shadow dark:bg-gray-700">
                                    <button type="button"
                                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-hide="deleteModal{{ $order->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                    </button>
                                    <div class="p-4 md:p-5 text-center">
                                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Yakin ingin
                                            menghapus Order <strong>{{ $order->no_order }}</strong>?</h3>
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                Ya, Hapus
                                            </button>
                                        </form>
                                        <button data-modal-hide="deleteModal{{ $order->id }}" type="button"
                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tidak,
                                            Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="14" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-lg font-medium">Data Order tidak ditemukan</p>
                                    <p class="text-sm">Silakan tambah data atau import dari CSV.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    {{-- Add Order Modal --}}
    <div id="addOrderModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-xl shadow dark:bg-gray-800">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Order Baru</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="addOrderModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.orders.store') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Layanan</label>
                            <input type="text" name="layanan" value="{{ old('layanan') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Contoh: INTERNET">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Paket</label>
                            <input type="text" name="paket" value="{{ old('paket') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Contoh: 50 Mbps">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No Order</label>
                            <input type="text" name="no_order" required value="{{ old('no_order') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Contoh: ORD-00001">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">OLO</label>
                            <input type="text" name="olo" value="{{ old('olo') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Contoh: OLO Name">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">STO</label>
                            <input type="text" name="sto" value="{{ old('sto') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white uppercase"
                                placeholder="Contoh: BTG">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ND</label>
                            <input type="text" name="nd" value="{{ old('nd') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Contoh: 123456789">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Order</label>
                            <input type="text" name="status_order" value="{{ old('status_order') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Contoh: IN PROGRESS">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                            <textarea name="keterangan" rows="2"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-600 focus:border-red-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                    <button type="submit"
                        class="text-white inline-flex items-center bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full justify-center">
                        Simpan Order
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div id="importModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="relative w-full max-w-md mx-4">
            <div class="bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Import Data Order</h3>
                    <button type="button" data-modal-hide="importModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.orders.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800">
                            <p class="text-xs text-blue-700 dark:text-blue-300 font-medium">
                                ℹ️ Hanya data <strong>Teritori 2</strong> yang akan diimport.
                            </p>
                        </div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih File CSV</label>
                        <input type="file" name="file" accept=".csv" required
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format file harus .csv sesuai template.</p>
                    </div>
                    <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Upload
                            & Import</button>
                        <button type="button" data-modal-hide="importModal"
                            class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Clear Data Modal --}}
    <div id="clearDataModal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-xl shadow dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="clearDataModal">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-red-400 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Hapus Semua Data Order?</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Semua data order akan dihapus secara permanen.
                        Anda bisa import ulang CSV terbaru setelah ini.</p>
                    <form action="{{ route('admin.orders.clear') }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Ya, Hapus Semua
                        </button>
                    </form>
                    <button data-modal-hide="clearDataModal" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Scripts removed as modal design was simplified to match Purchase Order --}}
@endpush