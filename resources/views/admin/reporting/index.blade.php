@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reporting Dashboard</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Analisis data Purchase Order dalam bentuk tabel pivot interaktif.</p>
</div>

@php
    $metricCards = [
        [
            'title' => 'Total RE',
            'stats' => $reStats,
            'color' => 'indigo',
            'gradient' => 'from-indigo-600 to-violet-700',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />',
            'params' => ['filter' => 're']
        ],
        [
            'title' => 'Total PO',
            'stats' => $poStats,
            'color' => 'blue',
            'gradient' => 'from-blue-600 to-cyan-600',
            'icon' => '<path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />',
            'params' => ['filter' => 'has_po']
        ],
        [
            'title' => 'Total PS',
            'stats' => $psStats,
            'color' => 'green',
            'gradient' => 'from-green-500 to-emerald-600',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
            'params' => ['status_order' => 'PROVISION COMPLETED']
        ],
        [
            'title' => 'Total Cancel PO',
            'stats' => $cancelStats,
            'color' => 'red',
            'gradient' => 'from-red-600 to-rose-700',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
            'params' => ['search' => 'Cancel PO']
        ]
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach($metricCards as $card)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="p-4 border-b border-gray-50 dark:border-gray-700/50 flex items-center bg-gray-50/30 dark:bg-gray-800/50">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $card['gradient'] }} flex items-center justify-center shadow-lg shadow-{{ $card['color'] }}-500/20">
                <svg class="w-5 h-5 text-white" fill="{{ $card['title'] === 'Total PO' ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $card['icon'] !!}
                </svg>
            </div>
            <h3 class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-tight">{{ $card['title'] }}</h3>
        </div>
        
        <!-- Breakdown -->
        <div class="flex divide-x divide-gray-100 dark:divide-gray-700 h-full">
            {{-- JABODETABEK --}}
            <a href="{{ route('admin.purchase-orders.index', array_merge(['area' => 'JABODETABEK'], $card['params'])) }}" 
               class="flex-1 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tighter">JABODETABEK</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-{{ $card['color'] }}-600 transition-colors">
                    {{ $card['stats']['JABODETABEK'] ?? 0 }}
                </p>
            </a>
            
            {{-- JABAR --}}
            <a href="{{ route('admin.purchase-orders.index', array_merge(['area' => 'JABAR'], $card['params'])) }}" 
               class="flex-1 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group border-l dark:border-gray-700">
                <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tighter">JABAR</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-{{ $card['color'] }}-600 transition-colors">
                    {{ $card['stats']['JABAR'] ?? 0 }}
                </p>
            </a>
        </div>
        
        <!-- Footer Link (Total) -->
        <a href="{{ route('admin.purchase-orders.index', $card['params']) }}" 
           class="px-4 py-2 bg-gray-50 dark:bg-gray-800/80 text-[10px] font-bold text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400 border-t border-gray-100 dark:border-gray-700 hover:bg-{{ $card['color'] }}-50 dark:hover:bg-{{ $card['color'] }}-900/10 transition-colors flex justify-between items-center">
            <span>TOTAL: {{ ($card['stats']['JABAR'] ?? 0) + ($card['stats']['JABODETABEK'] ?? 0) }}</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </a>
    </div>
    @endforeach
</div>

<div class="space-y-8">
    {{-- Table 1: Brand --}}
    @include('admin.reporting._pivot_table', [
        'title' => 'Summary by Brand',
        'data' => $brandData,
        'statuses' => $statuses,
        'rowLabel' => 'Brand',
        'drillDownKey' => 'brand'
    ])

    {{-- Table 2: Branch --}}
    @include('admin.reporting._pivot_table', [
        'title' => 'Summary by Branch',
        'data' => $branchData,
        'statuses' => $statuses,
        'rowLabel' => 'Branch',
        'drillDownKey' => 'branch'
    ])

    {{-- Table 3: Regional --}}
    @include('admin.reporting._pivot_table', [
        'title' => 'Summary by Regional',
        'data' => $regionalData,
        'statuses' => $statuses,
        'rowLabel' => 'Regional',
        'drillDownKey' => 'regional'
    ])
</div>
@endsection
