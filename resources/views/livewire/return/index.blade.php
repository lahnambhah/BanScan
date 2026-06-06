<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Return Items</h1>

            <p class="text-gray-500 text-sm sm:text-base">
                Process returned products using receipt numbers
            </p>
        </div>
    </div>

    {{-- SEARCH RECEIPT --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-5">

        <div class="flex flex-col sm:flex-row gap-3">

            <input type="text"
                wire:model="filterReceipt"
                placeholder="Enter Receipt No..."
                class="w-full border rounded px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('filterReceipt')
                <p class="text-red-500 text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror

            <button
                wire:click="loadSales"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded whitespace-nowrap font-medium">
                Load Sales
            </button>
        </div>
    </div>

    {{-- ERROR --}}
    @if(session()->has('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    {{-- SUCCESS --}}
    @if(session()->has('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- SALES + RETURN CART --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- PURCHASED ITEMS --}}
        <div class="bg-white rounded-2xl shadow p-4 sm:p-5">

            <div class="flex items-center justify-between mb-5">

                <div>
                    <h2 class="text-lg font-bold">
                        Purchased Items
                    </h2>

                    <p class="text-sm text-gray-500">
                        Select items to return
                    </p>
                </div>

                <div class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                    {{ count($salesItems) }} Items
                </div>
            </div>

            <div class="space-y-4 max-h-[650px] overflow-y-auto pr-1">

                @forelse($salesItems as $item)

                <div class="border rounded-2xl p-4 hover:border-blue-400 transition">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        {{-- LEFT --}}
                        <div class="min-w-0 flex-1">

                            <h3 class="font-semibold text-base break-words">
                                {{ $item['name'] }}
                            </h3>

                            <p class="text-xs text-gray-400 break-all mt-1">
                                {{ $item['barcode'] }}
                            </p>

                            {{-- STATS --}}
                            <div class="flex flex-wrap gap-2 mt-3 text-xs">

                                <span class="bg-gray-100 px-2 py-1 rounded-full">
                                    Sold: {{ $item['sold_qty'] }}
                                </span>

                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full">
                                    Returned: {{ $item['returned_qty'] }}
                                </span>

                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                    Remaining: {{ $item['remaining_qty'] }}
                                </span>
                            </div>
                        </div>

                        {{-- ACTION --}}
                        <button
                            wire:click="addFromSales({{ $item['sale_item_id'] }})"
                            class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded text-sm whitespace-nowrap
                            {{ $item['remaining_qty'] <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $item['remaining_qty'] <= 0 ? 'disabled' : '' }}>

                            + Add Return
                        </button>
                    </div>
                </div>

                @empty

                <div class="text-center py-16 text-gray-400">
                    <x-heroicon-o-arrow-uturn-left class="w-14 h-14 mx-auto mb-3 opacity-30" />
                    <p>No sales loaded</p>
                </div>

                @endforelse
            </div>
        </div>

        {{-- RETURN CART --}}
        <div class="bg-white rounded-2xl shadow p-4 sm:p-5">

            <div class="flex items-center justify-between mb-5">

                <div>
                    <h2 class="text-lg font-bold">
                        Return Cart
                    </h2>

                    <p class="text-sm text-gray-500">
                        Selected return items
                    </p>
                </div>

                <div class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-medium">
                    {{ count($cart) }} Items
                </div>
            </div>

            <div class="space-y-4 max-h-[650px] overflow-y-auto pr-1">

                @forelse($cart as $item)

                <div class="border rounded-2xl p-4">
                    <div class="space-y-4">
                        {{-- INFO --}}
                        <div>
                            <h3 class="font-semibold text-sm break-words">
                                {{ $item['name'] }}
                            </h3>

                            <p class="text-xs text-gray-400 break-all mt-1">
                                {{ $item['barcode'] }}
                            </p>
                        </div>

                        {{-- CONTROLS --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                            {{-- PRICE --}}
                            <div>
                                <p class="text-xs text-gray-400">
                                    Refund
                                </p>

                                <p class="font-bold text-lg">
                                    ₹{{ number_format($item['price']) }}
                                </p>
                            </div>

                            {{-- QTY --}}
                            <div class="flex items-center gap-3">

                                <button
                                    wire:click="decrease('{{ $item['sale_item_id'] }}')"
                                    class="w-10 h-10 rounded bg-gray-100 hover:bg-gray-200 text-lg">
                                    -
                                </button>

                                <span class="font-semibold text-lg min-w-[20px] text-center">
                                    {{ $item['qty'] }}
                                </span>

                                <button
                                    wire:click="increase('{{ $item['sale_item_id'] }}')"
                                    class="w-10 h-10 rounded bg-gray-100 hover:bg-gray-200 text-lg">
                                    +
                                </button>
                            </div>

                            {{-- REMOVE --}}
                            <button
                                wire:click="remove({{ $item['sale_item_id'] }})"
                                class="text-red-500 hover:text-red-700 text-sm">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>

                @empty

                <div class="text-center py-16 text-gray-400">
                    <x-heroicon-o-shopping-cart class="w-14 h-14 mx-auto mb-3 opacity-30" />
                    <p>No items added</p>
                </div>

                @endforelse
            </div>

            {{-- TOTAL --}}
            <div class="border-t mt-6 pt-5">
                <div class="flex justify-between items-center mb-5">
                    <span class="text-lg font-semibold">
                        Total Refund
                    </span>

                    <span class="text-2xl font-bold text-green-600">
                        ₹{{ number_format($this->total) }}
                    </span>
                </div>

                {{-- COMPLETE --}}
                <button
                    wire:click="completeReturn"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded font-medium transition">
                    Complete Return
                </button>
            </div>
        </div>
    </div>

    {{-- RETURN HISTORY --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-5">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">

            <div>
                <h2 class="text-lg font-bold">
                    Return History
                </h2>

                <p class="text-sm text-gray-500">
                    Recently processed returns
                </p>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-5">

            <input type="text"
                wire:model.lazy="filterReceipt"
                placeholder="Search Receipt No..."
                class="w-full border rounded px-4 py-3 text-sm">

            <input type="date"
                wire:model.lazy="filterDate"
                class="w-full border rounded px-4 py-3 text-sm">

            <button
                wire:click="loadReturns"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded whitespace-nowrap font-medium">
                Apply
            </button>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="py-4">Date</th>
                        <th>Phone</th>
                        <th>Total</th>
                        <th>Items</th>
                        <th>Receipt</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
            
                    @forelse($returnHistory as $return)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-4">
                            {{ $return->created_at->format('d M Y') }}
                        </td>

                        <td>
                            {{ $return->customer_phone }}
                        </td>

                        <td class="font-semibold">
                            ₹{{ number_format($return->total) }}
                        </td>

                        <td>
                            {{ $return->items->count() }} items
                        </td>

                        <td class="font-medium">
                            {{ optional(optional(optional($return->items->first())->saleItem)->sale)->receipt_no }}
                        </td>

                        <td>
                            <a href="{{ route('return.receipt', $return->id) }}"
                                class="bg-gray-800 text-white px-4 py-2 rounded text-sm inline-flex items-center justify-center">
                                View Receipt
                            </a>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="6"
                            class="text-center py-10 text-gray-400">
                            No return history
                        </td>
                    </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARDS --}}
        <div class="md:hidden space-y-4">

            @forelse($returnHistory as $return)

            <div class="border rounded-2xl p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold">
                            ₹{{ number_format($return->total) }}
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $return->created_at->format('d M Y') }}
                        </p>
                    </div>

                    <div class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">
                        {{ $return->items->count() }} items
                    </div>
                </div>

                <div class="mt-4 space-y-2 text-sm">
                    <div>
                        <span class="text-gray-400">Phone:</span>
                        {{ $return->customer_phone }}
                    </div>

                    <div>
                        <span class="text-gray-400">Receipt:</span>

                        {{ optional(optional(optional($return->items->first())->saleItem)->sale)->receipt_no }}
                    </div>
                </div>

                <a href="{{ route('return.receipt', $return->id) }}"
                    class="mt-4 w-full bg-gray-800 text-white py-3 rounded flex items-center justify-center text-sm">
                    View Receipt
                </a>
            </div>

            @empty

            <div class="text-center py-10 text-gray-400">
                No return history
            </div>

            @endforelse

        </div>
        <div class="mt-4">
            {{ $returnHistory->links() }}
        </div>
    </div>
</div>
