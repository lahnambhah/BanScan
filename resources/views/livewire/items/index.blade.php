@php
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
@endphp

<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold">Inventory Items</h1>
            <p class="text-gray-500 text-sm md:text-base">Manage all your inventory items</p>
        </div>
        
        <a href="/items/create" 
            class="bg-gray-800 text-white px-4 py-2 rounded text-center flex items-center justify-center gap-2">
            <x-heroicon-o-plus class="w-4 h-4" />Add Item</a>
    </div>

    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 mb-6">
        <input
        type="text"
        wire:model.live="search"
        placeholder="Search items..."
        class="w-full md:w-96 border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500">

        <select
            wire:model.live="status"
            class="w-full md:w-auto border rounded px-8 py-2">

            <option value="all">All Items</option>
            <option value="instock">In Stock</option>
            <option value="lowstock">Low Stock</option>
            <option value="outstock">Out of Stock</option>
        </select>
    </div>

    @if(session()->has('success'))
        <div
            x-data="{show:true}"
            x-init="setTimeout(()=>show=false,3000)"
            x-show="show"
            class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden border">
        <div class="overflow-x-auto">

            <table class="w-full text-sm sm:text-sm min-w-[760px]">

                <thead class="bg-gray-50 border-b text-gray-600">
                    <tr>
                        <th class="px-3 py-3 text-left font-semibold w-[220px]">Item</th>
                        <th class="px-3 py-3 text-left font-semibold">Category</th>
                        <th class="hidden lg:table-cell px-3 py-3 text-left font-semibold">Supplier</th>
                        <th class="px-3 py-3 text-left font-semibold">Qty</th>
                        <th class="px-3 py-3 text-left font-semibold">Price</th>
                        <th class="hidden lg:table-cell px-3 py-3 text-left font-semibold">Expiry</th>
                        <th class="hidden lg:table-cell px-3 py-3 text-center font-semibold">Status</th>
                        <th class="px-3 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach($items as $item)

                    <tr class="hover:bg-gray-50">

                        <td class="px-3 py-3 font-medium">
                            {{ $item->name }}
                        </td>

                        <td class="px-3 py-3 text-blue-600 font-medium">
                            {{ $item->category->name }}
                        </td>

                        <td class="hidden lg:table-cell px-3 py-3 text-green-600 font-medium">
                            {{ $item->supplier->name ?? 'N/A' }}
                        </td>

                        <td class="px-3 py-3">
                            {{ $item->stock }}
                        </td>

                        <td class="px-3 py-3 font-medium whitespace-nowrap">
                            ₹{{ number_format($item->price) }}
                        </td>

                        <td class="hidden md:table-cell px-3 py-3">
                            @if($item->expiry_date)

                                @php
                                    $expiry = \Carbon\Carbon::parse($item->expiry_date);
                                    $days = (int) now()->diffInDays($expiry, false);
                                @endphp

                                @if($days < 0)
                                    <span class="text-red-600 font-semibold">
                                        Expired {{ abs($days) }} days ago
                                    </span>
                                @elseif($days == 0)
                                    <span class="text-red-500 font-semibold">
                                        Expires today
                                    </span>
                                @elseif($days <= 5)
                                    <span class="text-orange-500 font-semibold">
                                        {{ $days }} days left
                                    </span>
                                @else
                                    <span class="text-green-600">
                                        {{ $days }} days left
                                    </span>
                                @endif

                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>

                        <td class="hidden lg:table-cell px-3 py-3 text-center">
                            @if($item->stock == 0)
                                <span class="inline-flex items-center justify-center whitespace-nowrap bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">
                                    Out of Stock
                                </span>

                            @elseif($item->stock <= 10)
                                <span class="inline-flex items-center justify-center whitespace-nowrap bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">
                                    Low Stock
                                </span>

                            @else
                                <span class="inline-flex items-center justify-center whitespace-nowrap bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                                    In Stock
                                </span>
                            @endif
                        </td>

                        <td class="px-3 py-3 text-center">
                            <div class="flex items-center justify-center gap-3">

                                <a href="/items/{{ $item->id }}/edit"
                                class="text-orange-500 hover:text-orange-700">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>

                                <button
                                onclick="confirm('Delete this item?') || event.stopImmediatePropagation()"
                                wire:click="delete({{ $item->id }})"
                                class="text-gray-400 hover:text-red-500">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr> 
                    @endforeach
                    @if($items->isEmpty())
                        <tr>
                            <td colspan="9"
                                class="text-center py-10 text-gray-400">
                                No items found
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE ITEM CARDS --}}
    <div class="md:hidden space-y-4">

        @foreach($items as $item)

        <div class="bg-white rounded-2xl p-4 shadow border">

            {{-- TOP --}}
            <div class="flex justify-between items-start gap-3">
                <div>
                    <h2 class="font-bold text-base leading-tight">
                        {{ $item->name }}
                    </h2>
                    <p class="text-sm text-blue-600 mt-1">
                        {{ $item->category->name }}
                    </p>
                </div>

                {{-- STATUS --}}
                <div>
                    @if($item->stock == 0)
                        <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">
                            Out
                        </span>
                    @elseif($item->stock <= 10)
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">
                            Low
                        </span>
                    @else
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                            Stock
                        </span>
                    @endif
                </div>
            </div>

            {{-- DETAILS --}}
            <div class="grid grid-cols-2 gap-3 mt-4 text-sm">
                <div>
                    <p class="text-gray-400">Quantity</p>
                    <p class="font-semibold">
                        {{ $item->stock }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-400">Price</p>
                    <p class="font-semibold">
                        ₹{{ number_format($item->price) }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-400">Supplier</p>
                    <p class="font-semibold">
                        {{ $item->supplier->name ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-400">Expiry</p>
                    <p class="font-semibold text-xs">
                        @if($item->expiry_date)
                            @php
                                $expiry = \Carbon\Carbon::parse($item->expiry_date);
                                $days = (int) now()->diffInDays($expiry, false);
                            @endphp
                            @if($days < 0)
                                <span class="text-red-500">
                                    Expired
                                </span>
                            @elseif($days <= 5)
                                <span class="text-orange-500">
                                    {{ $days }} days
                                </span>
                            @else
                                <span class="text-green-600">
                                    {{ $days }} days
                                </span>
                            @endif
                        @else
                            <span class="text-gray-400">
                                N/A
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-4 mt-5 pt-4 border-t">
                <a href="/items/{{ $item->id }}/edit"
                class="text-orange-500 hover:text-orange-700">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                </a>

                <button
                    onclick="confirm('Delete this item?') || event.stopImmediatePropagation()"
                    wire:click="delete({{ $item->id }})"
                    class="text-gray-400 hover:text-red-500">

                    <x-heroicon-o-trash class="w-5 h-5" />
                </button>
            </div>
        </div>
        @endforeach
    </div>

</div>
