<div class="space-y-6">

    {{-- RESPONSIVE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">
                Sales History
            </h1>

            <p class="text-gray-500 text-sm sm:text-base">
                Latest completed sales
            </p>
        </div>

        {{-- RESPONSIVE SEARCH --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text"
                wire:model.live="search"
                placeholder="Enter Receipt No..."
                class="border rounded px-4 py-3 w-full sm:w-72">

            {{-- DATE FILTERS --}}
            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <input
                        type="date"
                        wire:model.live="startDate"
                        max="{{ date('Y-m-d') }}"
                        class="border rounded px-3 py-3">

                    <input
                        type="date"
                        wire:model.live="endDate"
                        max="{{ date('Y-m-d') }}"
                        class="border rounded px-3 py-3">
                </div>

                @if(session('error'))
                    <div class="bg-red-100 text-red-700 px-3 py-2 rounded text-sm">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>     
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left p-4 font-semibold">
                            Receipt
                        </th>

                        <th class="text-left p-4 font-semibold">
                            Phone
                        </th>

                        <th class="text-left p-4 font-semibold">
                            Total
                        </th>

                        <th class="text-left p-4 font-semibold">
                            Date
                        </th>

                        <th class="text-left p-4 font-semibold">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($sales as $sale)

                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-semibold">
                            {{ $sale->receipt_no }}
                        </td>

                        <td class="p-4">
                            {{ $sale->customer_phone }}
                        </td>

                        <td class="p-4 font-semibold">
                            ₹{{ number_format($sale->total) }}
                        </td>

                        <td class="p-4 text-sm text-gray-500">
                            {{ $sale->created_at->format('d M Y h:i A') }}
                        </td>

                        <td class="p-4">
                            <a href="{{ route('receipt.print', $sale->id) }}"
                                target="_blank"
                                class="bg-gray-800 text-white px-4 py-2 rounded text-sm inline-flex items-center justify-center">
                                Print Receipt
                            </a>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="5"
                            class="p-6 text-center text-gray-500">
                            No sales found
                        </td>
                    </tr>

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="md:hidden space-y-4">

        @forelse($sales as $sale)

        <div class="bg-white rounded-2xl shadow p-4 border">

            {{-- TOP --}}
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h2 class="font-bold text-sm break-all">
                        {{ $sale->receipt_no }}
                    </h2>

                    <p class="text-gray-500 text-xs mt-1">
                        {{ $sale->created_at->format('d M Y h:i A') }}
                    </p>
                </div>

                <div class="text-right">
                    <p class="font-bold text-base">
                        ₹{{ number_format($sale->total) }}
                    </p>
                </div>
            </div>

            {{-- DETAILS --}}
            <div class="mt-4 grid grid-cols-1 gap-3 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">
                        Customer Phone
                    </p>

                    <p class="font-medium">
                        {{ $sale->customer_phone }}
                    </p>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="mt-5 pt-4 border-t">

                <a href="{{ route('receipt.print', $sale->id) }}"
                    target="_blank"
                    class="w-full bg-gray-800 text-white py-3 rounded text-sm flex items-center justify-center">
                    Print Receipt
                </a>
            </div>
        </div>

        @empty

        <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
            No sales found
        </div>

        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>