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
                <input type="checkbox"
                    id="select-all"
                    class="rounded border-gray-300 h-6 w-6">
                Select All
            </label>

            {{-- DOWNLOAD SELECTED --}}
            <button
                onclick="downloadSelected()"
                class="bg-gray-800 text-white px-4 py-2 rounded text-center flex items-center justify-center gap-2">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                Download Selected
            </button>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="mb-6">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search barcodes..."
            class="w-full md:w-96 border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500">
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

                    <input type="checkbox"
                        class="barcode-checkbox hidden"
                        value="{{ $item->id }}">

                    {{-- CHECKBOX UI --}}
                    <div class="check-ui w-9 h-9 rounded border border-gray-300 bg-gray-50 flex items-center justify-center transition-all duration-200"> 
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
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
    @if($items->isEmpty())
        <div class="text-center text-gray-500 mt-12">
            No barcodes found.
        </div>
    @endif
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    // UI TOGGLE
    document.querySelectorAll('.barcode-checkbox').forEach(cb => {

        cb.addEventListener('change', function () {

            const ui = this.parentElement.querySelector('.check-ui');
            const icon = this.parentElement.querySelector('.check-icon');

            if (this.checked) {

                ui.classList.remove('border-gray-300', 'bg-white');

                ui.classList.add(
                    'bg-green-500',
                    'border-green-500',
                    'scale-110'
                );

                icon.classList.remove('text-gray-400');
                icon.classList.add('text-white');

            } else {

                ui.classList.remove(
                    'bg-green-500',
                    'border-green-500',
                    'scale-110'
                );

                ui.classList.add(
                    'border-gray-300',
                    'bg-white'
                );

                icon.classList.remove('text-white');
                icon.classList.add('text-gray-400');

            }

        });

    });

    // SELECT ALL
    document.getElementById('select-all')
        .addEventListener('change', function () {

            document.querySelectorAll('.barcode-checkbox')
                .forEach(cb => {

                    cb.checked = this.checked;
                    cb.dispatchEvent(new Event('change'));

                });

        });

});

// DOWNLOAD SELECTED
function downloadSelected() {

    let items = [];

    document.querySelectorAll('.barcode-checkbox').forEach(cb => {

        if (cb.checked) {

            const card = cb.closest('.bg-white');

            const qtyInput = card.querySelector('.barcode-qty');

            items.push({
                id: cb.value,
                qty: qtyInput.value || 1
            });

        }

    });

    if (items.length === 0) {

        alert('Please select at least one barcode');
        return;

    }

    const query = encodeURIComponent(JSON.stringify(items));

    window.open(
        '/barcodes/export?items=' + query,
        '_blank'
    );

    // RESET
    document.querySelectorAll('.barcode-checkbox').forEach(cb => {

        cb.checked = false;
        cb.dispatchEvent(new Event('change'));

    });

}
</script>

<script>
window.addEventListener('openBarcodePrint', event => {
    window.open(event.detail[0], '_blank');
});
</script>