@php
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
@endphp

<div>
<div class="max-w-4xl mx-auto px-4 sm:px-0">

<h1 class="text-2xl sm:text-3xl font-bold mb-2">Add Item</h1>
<p class="text-gray-500 mb-6">Add a new item to your inventory</p>

<div class="bg-white p-5 sm:p-8 rounded-2xl shadow space-y-6">

    {{-- NAME --}}
    <div>
        <label class="block mb-1 font-medium">Item Name *</label>
        <input type="text"
            wire:model="name"
            class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- CATEGORY + SUPPLIER --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block mb-1 font-medium">Category *</label>

            <select wire:model="category_id"
                class="w-full border rounded-xl px-4 py-2">

                <option value="">Select</option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach

            </select>
            @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror    
        </div>

        <div>
            <label class="block mb-1 font-medium">Supplier *</label>

            <select wire:model="supplier_id"
                class="w-full border rounded-xl px-4 py-2">

                <option value="">Select</option>

                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">
                        {{ $supplier->name }}
                    </option>
                @endforeach

            </select>
            @error('supplier_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror        
        </div>
    </div>

    {{-- BARCODE SECTION --}}
    <div>

        <label class="block mb-2 font-semibold text-gray-700">
            Barcode *
        </label>

        <div class="flex flex-col gap-3">
            {{-- TOP --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">

                {{-- GENERATE --}}
                <button
                    type="button"
                    wire:click="generateBarcode"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow flex items-center justify-center gap-2 w-full sm:w-auto">

                    <x-heroicon-o-sparkles class="w-5 h-5" />

                    Generate

                </button>

                {{-- MOBILE CAMERA --}}
                <button
                    type="button"
                    id="start-item-camera"
                    class="md:hidden bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow flex items-center justify-center gap-2 w-full">

                    <x-heroicon-o-camera class="w-5 h-5" />

                    Scan Using Camera

                </button>

                {{-- INPUT --}}
                <div class="flex-1">

                    <input
                        type="text"
                        id="barcode-scanner-input"
                        wire:model.live="barcode"
                        placeholder="Scan barcode using scanner..."
                        autocomplete="off"
                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">

                </div>

            </div>

            {{-- CAMERA READER --}}
            <div
                id="item-reader"
                class="hidden overflow-hidden rounded-xl border bg-black">
            </div>

            @error('barcode')
                <span class="text-red-500 text-sm">
                    {{ $message }}
                </span>
            @enderror

        </div>

        {{-- BARCODE PREVIEW --}}
        <div class="mt-4 border rounded-xl px-4 py-4 bg-gray-50 min-h-[90px]">

            @if($barcode)

                <div class="flex flex-col sm:flex-row sm:items-center gap-4 overflow-hidden">

                    <div class="scale-90 origin-left">
                        {!! DNS1D::getBarcodeHTML($barcode, 'C128') !!}
                    </div>

                    <span class="text-xs sm:text-sm tracking-widest text-gray-600 break-all">
                        {{ $barcode }}
                    </span>

                </div>

            @else

                <div class="text-gray-400">
                    No barcode scanned
                </div>

            @endif

        </div>

        <p class="text-xs text-gray-500 mt-2">
            Tip: Simply scan product barcode using your USB barcode scanner
        </p>

    </div>

    {{-- DATES --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block mb-2 font-medium">
                Manufactured Date
            </label>

            <input type="date"
                wire:model="manufactured_at"
                class="w-full border rounded-lg px-4 py-2">
            @error('manufactured_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Expiry Date
            </label>

            <input type="date"
                wire:model="expiry_date"
                class="w-full border rounded-lg px-4 py-2">
            @error('expiry_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        
    </div>

    {{-- LOW STOCK + QUANTITY --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- QUANTITY --}}
        <div>
            <label class="block mb-1 font-medium">
                Quantity *
            </label>

            <input type="number"
                wire:model="stock"
                class="w-full border rounded-lg px-4 py-2">

            @error('stock')
                <span class="text-red-500 text-sm">
                    {{ $message }}
                </span>
            @enderror
        </div>

        {{-- LOW STOCK ALERT --}}
        <div>
            <label class="block mb-1 font-medium">
                Low Stock Alert Level
            </label>

            <input type="number"
                wire:model="low_stock_limit"
                class="w-full border rounded-lg px-4 py-2">

            @error('low_stock_limit')
                <span class="text-red-500 text-sm">
                    {{ $message }}
                </span>
            @enderror
            
            <p class="text-xs text-gray-500 mt-1">
                Notification will trigger when stock reaches this value.
            </p>
        </div>
    </div>

    {{-- PRICE --}}
    <div>
        <label class="block mb-1 font-medium">Price *</label>

        <input type="number"
            wire:model="price"
            class="w-full border rounded-xl px-4 py-2">
        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- BUTTONS --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:justify-between sm:items-center pt-4">

        <a href="/items"
            class="text-gray-500 hover:underline">

            ← Back
        </a>

        <button
            wire:click="save"
            class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-700 w-full sm:w-auto">

            Save Item
        </button>

    </div>

</div>
</div>

{{-- AUTO FOCUS SCANNER --}}
<script>

    function focusBarcodeScanner() {

        let input = document.getElementById('barcode-scanner-input');

        if (input) {
            input.focus();
        }
    }

    document.addEventListener('livewire:init', () => {

        focusBarcodeScanner();

        Livewire.hook('message.processed', () => {
            focusBarcodeScanner();
        });

    });

</script>

<script>

document.addEventListener('DOMContentLoaded', () => {

    // ONLY MOBILE
    if (window.innerWidth >= 768) {
        return;
    }

    let scannerStarted = false;

    const cameraBtn =
        document.getElementById('start-item-camera');

    const reader =
        document.getElementById('item-reader');

    let html5QrCode;

    if (!cameraBtn || !reader) {
        return;
    }

    cameraBtn.addEventListener('click', async () => {

        if (scannerStarted) {
            return;
        }

        scannerStarted = true;

        reader.classList.remove('hidden');

        html5QrCode = new Html5Qrcode("item-reader");

        try {

            await html5QrCode.start(

                {
                    facingMode: "environment"
                },

                {
                    fps: 10,
                    qrbox: 250
                },

                async (decodedText) => {

                    let input =
                        document.getElementById(
                            'barcode-scanner-input'
                        );

                    if (input) {

                        input.value = decodedText;

                        input.dispatchEvent(
                            new Event('input', {
                                bubbles: true
                            })
                        );
                    }

                    @this.set(
                        'barcode',
                        decodedText
                    );

                }

            );

        } catch (e) {

            console.error(e);

            scannerStarted = false;
        }

    });

});
</script>

</div>