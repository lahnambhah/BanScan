@php
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
@endphp

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col xl:flex-row xl:justify-between xl:items-center gap-4 mb-6">

        <div>
            <h1 class="text-3xl font-bold">Barcodes</h1>
            <p class="text-gray-500">Generate and manage barcodes for your items</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-3">

            {{-- SELECT ALL --}}
            <label class="flex items-center gap-2 text-sm text-gray-600">
               <input
                    type="checkbox"
                    wire:click="toggleSelectAll"
                    class="rounded border-gray-300 h-6 w-6">
                Select All
            </label>

            {{-- DOWNLOAD SELECTED --}}
            <button
                wire:click="downloadSelected"
                class="bg-gray-800 text-white px-4 py-2 rounded text-center flex items-center justify-center gap-2">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                Download Selected
            </button>
        </div>
    </div>

    {{-- SEARCH + FILTER --}}
    <div class="mb-6 flex items-center gap-3">
        {{-- SEARCH --}}
        <div class="relative w-full md:w-96">
            <input
                wire:key="search-{{ md5($search) }}"
                type="text"
                wire:model.live.debounce.1000ms="search"
                placeholder="Search barcodes..."
                class="w-full border rounded px-4 py-2 pr-10 focus:ring-2 focus:ring-blue-500">

            @if($search)
                <button
                    type="button"
                    wire:click="clearSearch"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            @endif
        </div>

        {{-- CATEGORY FILTER --}}
        <select
            wire:model.live="category"
            class="border rounded px-4 py-2 w-56">

            <option value="">
                All Categories
            </option>

            @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        @foreach($items as $item)

        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition relative border">

            {{-- BARCODE BOX --}}
            <div class="bg-gray-50 border border-dashed rounded-xl p-4 flex flex-col items-center">

                {{-- BARCODE --}}
                <div class="overflow-hidden flex justify-center" style="transform: scale(.70); transform-origin:center;">
                    {!! DNS1D::getBarcodeHTML($item->barcode, 'C128') !!}
                </div>

                {{-- NUMBER --}}
                <p class="text-xs text-gray-500 tracking-widest mt-2">
                    {{ $item->barcode }}
                </p>
            </div>

            {{-- ITEM NAME --}}
            <h3 class="mt-4 text-center font-semibold text-sm text-gray-800">
                {{ Str::limit($item->name, 28) }}
            </h3>

            {{-- CATEGORY --}}
            <p class="text-center text-xs text-gray-400 mt-1">
                {{ $item->category->name ?? '-' }}
            </p>

            {{-- SINGLE DOWNLOAD --}}
            <div class="mt-5 flex items-center justify-between gap-3">

                {{-- SELECT --}}
                <label class="cursor-pointer flex items-center gap-2 group">

                    <input
                        type="checkbox"
                        wire:model.live="selected"
                        value="{{ $item->id }}"
                        class="hidden">

                    {{-- CHECKBOX UI --}}
                    <div class="w-9 h-9 rounded flex items-center justify-center transition-all duration-200
                        {{ in_array($item->id, $selected)
                            ? 'bg-green-500 border-green-500 scale-110'
                            : 'border border-gray-300 bg-gray-50' }}">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ in_array($item->id, $selected) ? 'text-white' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /> 
                        </svg> 
                    </div>

                    <span class="text-sm font-semibold text-gray-700">
                        Select
                    </span>

                </label>

                {{-- COPIES --}}
                <div class="flex items-center gap-2 border rounded px-3 py-2 bg-gray-50 shrink-0">

                    <span class="text-xs text-gray-500">
                        Copies
                    </span>

                    <input
                        type="number"
                        min="1"
                        value="1"
                        data-id="{{ $item->id }}"
                        class="barcode-qty w-12 bg-transparent border-0 focus:ring-0 text-center text-sm font-semibold p-0">
                </div>
            </div>
        </div>
        @endforeach
    </div>    
    <div class="mt-6">
    {{ $items->links() }}
</div>
    @if($items->isEmpty())
        <div class="text-center text-gray-500 mt-12">
            No barcodes found.
        </div>
    @endif
</div>

{{-- BARCODE ERROR --}}
<script>
window.addEventListener('barcodeError', event => {

    Swal.fire({
        icon: 'warning',
        title: 'No Barcode Selected',
        text: event.detail.message
    });

});
</script>

{{-- OPEN PRINT PAGE --}}
<script>
window.addEventListener('openBarcodePrint', event => {

    const url = event.detail[0];

    window.open(url, '_blank');

});
</script>

