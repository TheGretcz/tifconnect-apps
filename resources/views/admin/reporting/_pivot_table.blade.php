<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden mb-10">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-900">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
            <span class="w-2 h-6 bg-red-600 rounded-full mr-3"></span>
            {{ $title }}
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-white uppercase bg-gray-800 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-4 font-extrabold">{{ $rowLabel }}</th>
                    @foreach($statuses as $status)
                        <th class="px-6 py-4 text-center">{{ $status }}</th>
                    @endforeach
                    <th class="px-6 py-4 text-center bg-red-700 font-black">Grand Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @php 
                    $colTotals = array_fill_keys($statuses, 0); 
                    $grandTotal = 0;
                @endphp
                @foreach($data as $rowName => $rowValues)
                    @php $rowTotal = 0; @endphp
                    <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white bg-gray-50/50 dark:bg-gray-800/30">{{ $rowName }}</td>
                        @foreach($statuses as $status)
                            @php 
                                $count = $rowValues[$status] ?? 0;
                                $rowTotal += $count;
                                $colTotals[$status] += $count;
                            @endphp
                            <td class="px-6 py-4 text-center">
                                @if($count > 0)
                                    <a href="{{ route('admin.purchase-orders.index', [$drillDownKey => $rowName, 'status_order' => $status]) }}" 
                                       class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg font-bold hover:bg-blue-600 hover:text-white transition-all">
                                        {{ $count }}
                                    </a>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600 font-medium">0</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="px-6 py-4 text-center font-black bg-gray-100 dark:bg-gray-900/50 text-gray-900 dark:text-white border-l border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.purchase-orders.index', [$drillDownKey => $rowName]) }}" class="hover:text-red-600 transition-colors">
                                {{ $rowTotal }}
                            </a>
                        </td>
                    </tr>
                    @php $grandTotal += $rowTotal; @endphp
                @endforeach
            </tbody>
            <tfoot class="bg-gray-900 dark:bg-black font-black text-white">
                <tr>
                    <td class="px-6 py-4 text-red-500 uppercase tracking-wider">GRAND TOTAL</td>
                    @foreach($statuses as $status)
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.purchase-orders.index', ['status_order' => $status]) }}" class="hover:text-red-400 transition-colors">
                                {{ $colTotals[$status] }}
                            </a>
                        </td>
                    @endforeach
                    <td class="px-6 py-4 text-center bg-red-700 text-white text-lg">{{ $grandTotal }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
