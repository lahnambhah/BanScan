<div class="space-y-4 px-2 sm:px-0">
    
    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold">Scan</h1>
        <p class="text-gray-500">
            Scan barcodes to quickly process sales
        </p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        {{-- LEFT --}}
        <div class="bg-white rounded-xl shadow">
            <div class="p-4 border-b">
                <h2 class="font-semibold">Barcode Scanner</h2>
                <p class="text-xs text-gray-500">USB scanner ready for scanning</p>
            </div>

            <div class="p-4 space-y-4">
                {{-- SCANNER STATUS --}}
                <div class="border-2 border-dashed rounded-xl min-h-[220px] sm:min-h-[60px] flex flex-col items-center justify-center bg-gray-50 p-4">
                    {{-- DESKTOP --}}
                    <div class="hidden md:flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mb-3">
                            <x-heroicon-o-qr-code class="w-8 h-8 text-green-600" />
                        </div>

                        <p class="font-semibold text-md">USB Scanner Ready</p>
                        <p class="text-sm text-gray-500 mt-1">Scan using barcode scanner</p>
                    </div>

                    {{-- MOBILE CAMERA --}}
                    <div class="flex md:hidden flex-col items-center w-full">
                        <button
                            id="start-camera"
                            type="button"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded flex items-center gap-2">
                            <x-heroicon-o-camera class="w-5 h-5" />Open Camera Scanner
                        </button>
                        <p class="text-xs text-gray-500 mt-3 text-center">Scan products using your phone camera</p>

                        {{-- CAMERA --}}
                        <div
                            id="reader"
                            class="w-full mt-4 hidden overflow-hidden rounded-xl">
                        </div>
                    </div>
                </div>

                {{-- INPUT --}}
                <div class="flex flex-col sm:flex-row gap-2">
                    <input
                        type="text"
                        id="barcode-input"
                        wire:model.live="barcodeInput"
                        wire:keydown.enter.prevent="processScan"
                        placeholder="Scan barcode here..."
                        class="w-full border rounded px-4 py-3 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:outline-none"
                        autocomplete="off"
                        autofocus>
                    <button wire:click="lookup"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded text-md w-full sm:w-auto font-medium">
                        Add
                    </button>
                </div>
                @error('barcodeInput')
                        <p class="text-red-600 text-xs mt-1">
                            {{ $message }}
                        </p>        
                @enderror

                <p class="text-xs text-gray-400">Tip: Simply scan product barcode using your USB scanner</p>

                {{-- QUICK ADD --}}
                <div class="pt-2 space-y-5">
                    {{-- TITLE --}}
                    <div>
                        <h3 class="font-semibold text-gray-800">Quick Add</h3>
                        <p class="text-xs text-gray-500 mt-1">Search inventory or add custom items</p>
                    </div>

                    {{-- SEARCH EXISTING --}}
                    <div>
                        <input
                            type="text"
                            wire:model.live="searchItem"
                            placeholder="Search existing item..."
                            class="w-full border rounded px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        {{-- RESULTS --}}
                        @if($searchItem)
                            <div class="mt-2 border rounded-xl bg-white max-h-52 overflow-y-auto">
                                @forelse($searchResults as $item)
                                    <div class="px-4 py-3 hover:bg-gray-50 border-b flex items-center justify-between gap-4">
                                        {{-- LEFT --}}
                                        <div class="min-w-0">
                                            <p class="font-medium text-sm truncate">
                                                {{ $item->name }}
                                            </p>

                                            <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                                <span>
                                                    ₹{{ $item->price }}
                                                </span>
                                                <span>
                                                    Stock: {{ $item->stock }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- ADD BUTTON --}}
                                        <button
                                            wire:click="addManualItem({{ $item->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded whitespace-nowrap">
                                            Add
                                        </button>
                                    </div>
                                @empty

                                    <div class="p-4 text-sm text-gray-500 text-center">
                                        No items found
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>

                    {{-- DIVIDER --}}
                    <div class="flex items-center gap-3">
                        <div class="h-px bg-gray-200 flex-1"></div>
                            <span class="text-xs text-gray-400 uppercase">
                                Custom Item
                            </span>
                        <div class="h-px bg-gray-200 flex-1"></div>
                    </div>

                    {{-- CUSTOM ITEM --}}
                    <div class="grid grid-cols-12 gap-3">
                        {{-- NAME --}}
                        <div class="col-span-12 sm:col-span-6">
                            <input
                                type="text"
                                wire:model="manualName"
                                placeholder="Item Name"
                                class="w-full border rounded px-4 py-3">
                            @error('manualName')
                                <p class="text-red-600 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- PRICE --}}
                        <div class="col-span-5 sm:col-span-3">
                            <input
                                type="number"
                                wire:model="manualPrice"
                                placeholder="Price"
                                class="w-full border rounded px-4 py-3">
                            @error('manualPrice')
                                <p class="text-red-600 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- QTY --}}
                        <div class="col-span-3 sm:col-span-2">
                            <input
                                type="number"
                                wire:model="manualQty"
                                placeholder="Qty"
                                class="w-full border rounded px-3 py-3 text-center">
                            @error('manualQty')
                                <p class="text-red-600 text-xs mt-1">
                                    {{ $message }}  
                                </p>
                            @enderror
                        </div>

                        {{-- BUTTON --}}
                        <div class="col-span-4 sm:col-span-2">
                            <button
                                wire:click="addCustomItem"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded px-5 py-3 font-medium">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT (CART) --}}
        <div class="bg-white rounded-xl shadow">
            <div class="p-4 border-b flex items-center gap-2">
                <x-heroicon-o-shopping-cart class="w-5 h-5 text-green-600" />
                <h2 class="font-semibold text-green-600">Cart</h2>
            </div>

            <div class="p-4">
                @if(count($cart))
                    <div class="space-y-3">
                        @foreach($cart as $code => $item)
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 border-b pb-3">
                                <div>
                                    <p class="font-medium text-sm">
                                        {{ $item['name'] }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $item['barcode'] }}
                                    </p>

                                    @if(empty($item['manual']))
                                        <p class="text-xs text-gray-500 mt-1">
                                            In Stock: {{ \App\Models\Item::find($item['id'])?->stock ?? 0 }}
                                        </p>
                                    @endif
                                </div>

                                {{-- CENTER PRICE --}}
                                <div class="text-left sm:text-center">
                                    <p class="font-semibold">
                                        ₹ {{ $item['price'] }}
                                    </p>
                                    
                                    <div class="flex justify-center mt-2">
                                        <input
                                            type="number"
                                            min="1"
                                            max="{{ \App\Models\Item::find($item['id'])?->stock ?? 9999 }}"
                                            value="{{ $item['qty'] }}"
                                            wire:change="updateQty('{{ $code }}', $event.target.value)"
                                            class="w-20 border rounded text-center py-1 text-sm">
                                    </div>

                                    <button
                                        wire:click="remove('{{ $code }}')"
                                        class="mt-2 text-red-600 hover:text-red-800 text-sm underline">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        {{-- CUSTOMER --}}
                        <input type="text"
                            wire:model="customerPhone"
                            placeholder="Customer phone (Optional)"
                            pattern="[0-9]{10}"
                            class="w-full border rounded-lg px-4 py-3 text-sm mt-2">

                        @error('customerPhone')
                            <p class="text-red-600 text-xs mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- TOTAL --}}
                        <div class="flex justify-between font-bold pt-2">
                            <span>Total</span>
                            <span>
                                ₹ {{ $this->total }}
                            </span>
                        </div>

                        <button wire:click="completeSale"
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl mt-3 text-sm sm:text-base" >
                            Checkout
                        </button>

                        @if (session()->has('success'))
                            <div class="text-green-600 text-sm mt-2">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="h-72 flex flex-col items-center justify-center text-center">
                        <x-heroicon-o-shopping-cart
                            class="w-12 h-12 text-gray-300 mb-3" />

                        <p class="text-gray-400 text-sm">
                            No items scanned
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    @if($showSuccess)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
            {{-- RESPONSIVE MODAL --}}
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-xl w-full max-w-sm text-center animate-in fade-in zoom-in duration-200">

                {{-- ICON --}}
                <div class="flex justify-center items-center text-green-600 mb-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-green-100 flex items-center justify-center">
                        <x-heroicon-o-check class="w-10 h-10 sm:w-12 sm:h-12 animate-bounce" />
                    </div>
                </div>

                {{-- TITLE --}}
                <h2 class="text-xl sm:text-2xl font-bold mb-2">
                    Sale Completed
                </h2>

                {{-- SUBTITLE --}}
                <p class="text-gray-500 text-sm sm:text-base mb-5">
                    Transaction successful
                </p>

                {{-- BUTTONS --}}
                <div class="space-y-3">
                    {{-- PRINT --}}
                    <a href="{{ route('receipt.print', $lastSaleId) }}"
                        target="_blank"
                        class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl text-sm sm:text-base transition">
                        <x-heroicon-o-printer class="w-5 h-5" />
                        Print Receipt
                    </a>

                    {{-- NEW SALE --}}
                    <button
                        wire:click="$set('showSuccess', false)"
                        class="w-full flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-800 text-white py-3 rounded-xl text-sm sm:text-base transition">
                        <x-heroicon-o-plus class="w-5 h-5" />
                        New Sale
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- AUTO FOCUS --}}
<script>
window.addEventListener('focusScanner', () => {

    setTimeout(() => {

        const input =
            document.getElementById('scanner-input');

        if (input) {
            input.focus();
        }

    }, 100);

});

document.addEventListener('DOMContentLoaded', () => {

    const input =
        document.getElementById('scanner-input');

    if (input) {
        input.focus();
    }
});
</script>

<script>
    document.addEventListener('livewire:navigated', () => {
        focusScanner();
    });

    document.addEventListener('livewire:initialized', () => {
        focusScanner();

        Livewire.hook('message.processed', () => {
            focusScanner();
        });
    });

    function focusScanner() {
        let input = document.getElementById('barcode-input');

        if (input) {
            input.focus();
        }
    }
</script>

<script>
    function focusScanner() {
        let input = document.getElementById('barcode-input');

        if (input) {
            input.value = '';
            input.focus();
        }
    }

    document.addEventListener('livewire:init', () => {

        focusScanner();

        Livewire.on('clearBarcodeInput', () => {
            setTimeout(() => {
                focusScanner();
            }, 50);
        });
    });
</script>

<script>

document.addEventListener('DOMContentLoaded', () => {

    // MOBILE ONLY
    if (window.innerWidth >= 768) {
        return;
    }

    let scannerStarted = false;

    const cameraBtn =
        document.getElementById('start-camera');

    const reader =
        document.getElementById('reader');

    let html5QrCode;

    if (!cameraBtn || !reader) {
        return;
    }

    cameraBtn.addEventListener('click', async () => {

        // PREVENT MULTIPLE STARTS
        if (scannerStarted) {
            return;
        }

        scannerStarted = true;

        reader.classList.remove('hidden');

        html5QrCode = new Html5Qrcode("reader");

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

                    // FILL INPUT
                    let input =
                        document.getElementById('barcode-input');

                    if (input) {

                        input.value = decodedText;

                        input.dispatchEvent(
                            new Event('input', {
                                bubbles: true
                            })
                        );
                    }

                    // LIVEWIRE
                    @this.set(
                        'barcodeInput',
                        decodedText
                    );

                    @this.call('processScan');

                    // SMALL DELAY
                    setTimeout(() => {

                    }, 500);
                }

            );

        } catch (e) {

            console.error(e);

            scannerStarted = false;
        }

    });

});
</script>

{{-- STOCK EXCEEDED --}}
<script>
document.addEventListener('livewire:init', () => {

    Livewire.on('stockExceeded', (event) => {

        Swal.fire({
            icon: 'warning',
            title: 'Stock Limit Reached',
            text: `${event[0].item} only has ${event[0].stock} item(s) available in stock.`,
            confirmButtonText: 'OK'
        });

    });

});
</script>