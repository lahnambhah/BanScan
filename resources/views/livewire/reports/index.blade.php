<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">
                Reports
            </h1>

            <p class="text-gray-500 text-sm sm:text-base">
                View business insights and analytics
            </p>
        </div>
        <button wire:click="downloadReport"
            class="bg-gray-800 text-white px-4 py-2 rounded text-sm sm:text-base text-center flex items-center justify-center gap-2">

            <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
            Download Report
        </button>
    </div>

    {{-- TABS + FILTERS --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        {{-- TABS --}}
        <div class="grid grid-cols-2 gap-3 lg:w-auto">
            <button
                wire:click="$set('mode','sales')"
                class="px-5 py-2 rounded text-md font-medium transition
                {{ ($mode ?? 'sales') === 'sales'
                    ? 'bg-gray-800 text-white'
                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Sales Report
            </button>

            <button
                wire:click="$set('mode','item')"
                class="px-5 py-2 rounded text-md font-medium transition
                {{ ($mode ?? 'sales') === 'item'
                    ? 'bg-gray-800 text-white'
                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Item Report
            </button>
        </div>

        {{-- FILTERS --}}
        <div class="flex flex-col sm:flex-row lg:flex-nowrap gap-3 lg:items-center w-full lg:w-auto">
            @if(($mode ?? 'sales') === 'item')
                <select
                    wire:model.live="selectedItem"
                    class="border px-4 py-2 rounded text-md min-w-[220px]">

                    <option value="">
                        Select Item
                    </option>

                    @foreach(\App\Models\Item::all() as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            @endif

            <input
                type="date"
                wire:model="fromDate"
                class="border px-4 py-2 rounded text-md">

            <input
                type="date"
                wire:model="toDate"
                class="border px-4 py-2 rounded text-md">

            <button
                wire:click="loadReport"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded text-md font-medium whitespace-nowrap">
                Apply
            </button>
        </div>
    </div>

    {{-- ERRORS --}}
    <div class="space-y-1">
        @error('fromDate')
            <p class="text-red-500 text-sm">
                {{ $message }}
            </p>
        @enderror

        @error('toDate')
            <p class="text-red-500 text-sm">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- SALES REPORT --}}
    @if(($mode ?? 'sales') === 'sales')

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Total Revenue</p>
                <h2 class="text-2xl font-bold">₹ {{ number_format($totalSales, 2) }}</h2>
            </div>
            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Returns</p>
                <h2 class="text-2xl font-bold text-red-500">₹ {{ number_format($totalReturns, 2) }}</h2>
            </div>
            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Net Revenue</p>
                <h2 class="text-2xl font-bold text-green-600">₹ {{ number_format($netRevenue, 2) }}</h2>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="font-semibold mb-4 text-medium">Daily Sales</h2>

            @forelse($dailySalesData as $day)
                <div class="border rounded-lg mb-6 overflow-hidden">
                    <div class="bg-gray-100 px-4 py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <span class="text-medium font-semibold">{{ $day['formatted_date'] }}</span>
                        <span class="font-bold text-gray-700 text-sm sm:text-base">
                            {{ $day['items_count'] }} items · {{ $day['units_count'] }} units
                            <span class="ml-3 text-blue-600">₹ {{ number_format($day['total_amount'], 2) }}</span>
                        </span>
                    </div>
                    <div class="p-3">
                        <div class="hidden sm:grid grid-cols-4 text-gray-500 text-sm font-medium mb-2 px-2">
                            <div>ITEM</div>
                            <div class="text-right">QTY</div>
                            <div class="text-right">PRICE</div>
                            <div class="text-right">AMOUNT</div>
                        </div>
                        @foreach($day['items'] as $item)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 py-3 border-b text-sm px-2">
                                <div>{{ $item['name'] }}</div>
                                <div class="text-right">×{{ $item['quantity'] }}</div>
                                <div class="text-right">₹ {{ number_format($item['price'], 2) }}</div>
                                <div class="text-right">₹ {{ number_format($item['amount'], 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 py-8">No sales found for this period.</div>
            @endforelse
        </div>

    @endif

    {{-- ITEM REPORT --}}
    @if(($mode ?? 'sales') === 'item')

        @if(!$selectedItem)
            <div class="bg-white p-6 rounded-xl shadow text-center text-gray-400">
                Select an item to view report
            </div>
            @error('selectedItem') <p class="text-red-500 text-sm text-center mt-2"> {{ $message }} </p> @enderror
        @else
            {{-- Stats cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Available Stock</p>
                    <h2 class="text-2xl font-bold text-blue-600">{{ $itemStock }}</h2>
                </div>
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total Sold</p>
                    <h2 class="text-2xl font-bold">{{ $itemSold }}</h2>
                </div>
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Out of Stock</p>
                    <h2 class="text-2xl font-bold text-red-500">{{ $itemOut ? 'Yes' : 'No' }}</h2>
                </div>
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total Items</p>
                    <h2 class="text-2xl font-bold">{{ $itemStock + $itemSold }}</h2>
                </div>
            </div>

            {{-- Sales table with pagination --}}
            <div class="bg-white p-5 rounded-xl shadow mt-6">
                <h2 class="font-semibold mb-4 text-medium">
                    Sales Over Period · {{ \App\Models\Item::find($selectedItem)->name ?? '' }}
                </h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b text-gray-600 text-sm">
                                <th class="text-left py-2">DATE</th>
                                <th class="text-left py-2">PHONE NUMBER</th>
                                <th class="text-right py-2">QTY</th>
                                <th class="text-right py-2">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalQty = 0; $totalAmount = 0; @endphp
                            @forelse($itemSalesPaginated as $row)
                                @php
                                    $totalQty += $row['quantity'];
                                    $totalAmount += $row['amount'];
                                @endphp
                                <tr class="border-b">
                                    <td class="py-2 text-sm">{{ $row['date'] }}</td>
                                    <td class="py-2 text-sm">{{ $row['phone'] }}</td>
                                    <td class="text-right py-2 text-sm">{{ $row['quantity'] }}</td>
                                    <td class="text-right py-2 text-sm">₹ {{ number_format($row['amount'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-gray-400 py-6">
                                        No sales for this item in the selected period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($itemSalesPaginated && count($itemSalesPaginated) > 0)
                            <tfoot class="font-semibold">
                                <tr>
                                    <td colspan="2" class="py-2">Total</td>
                                    <td class="text-right py-2">{{ $totalQty }}</td>
                                    <td class="text-right py-2">₹ {{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                {{-- Pagination links --}}
                @if($itemSalesPaginated && method_exists($itemSalesPaginated, 'links'))
                    <div class="mt-4">
                        {{ $itemSalesPaginated->links() }}
                    </div>
                @endif
            </div>
        @endif
    @endif

</div>