<div class="max-w-4xl mx-auto px-4 sm:px-0">
    <h1 class="text-2xl sm:text-3xl font-bold mb-2">Edit Item</h1>
    <p class="text-gray-500 mb-6">Update item information</p>

    <div class="bg-white p-5 sm:p-8 rounded-2xl shadow space-y-6">

        {{-- Item Name --}}
        <div>
            <label class="block mb-1 font-medium">Item Name</label>
            <input type="text" wire:model="name"
            class="w-full border rounded-lg px-4 py-2">

            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Category + Supplier --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="block mb-1 font-medium">Category</label>
                    <select wire:model="category_id"
                        class="w-full border rounded-lg px-4 py-2">

                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">Supplier</label>
                    <select wire:model="supplier_id"
                        class="w-full border rounded-lg px-4 py-2">

                        <option value="">None</option>

                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                @error('supplier_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Manufactured + Expiry Date --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 font-medium">Manufactured Date</label>
                <input type="date"
                    wire:model="manufactured_at"
                    class="w-full border rounded-lg px-4 py-2">
            </div>
            @error('manufactured_at') 
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <div>
                <label class="block mb-2 font-medium">Expiry Date</label>
                <input type="date"
                    wire:model="expiry_date"
                    class="w-full border rounded-lg px-4 py-2">
            </div>
            @error('expiry_date') 
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Quantity --}}
        <div>
            <label class="block mb-1 font-medium">Quantity</label>
            <input type="number" wire:model="stock"
            class="w-full border rounded-lg px-4 py-2">

            @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Barcode (READ ONLY) --}}
        <div>
            <label class="block mb-1 font-medium">Barcode</label>

            <div class="border rounded-lg px-4 py-3 bg-gray-50">
                <span class="text-gray-700 text-sm">
                {{ $barcode ?? 'N/A' }}
                </span>
            </div>

            <p class="text-xs text-gray-400 mt-1">
                Barcode cannot be changed after creation
            </p>
        </div>

        {{-- Low Stock Alert --}}
        <div>
            <label class="block mb-1 font-medium">Low Stock Alert</label>

            <input
                type="number"
                wire:model="low_stock_limit"
                class="w-full border rounded-lg px-4 py-2">

            @error('low_stock_limit')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        {{-- Price --}}
        <div>
            <label class="block mb-1 font-medium">Price</label>
            <input type="number" wire:model="price"
            class="w-full border rounded-lg px-4 py-2">

            @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-between sm:items-center pt-4">
            <a href="/items" class="text-gray-500 hover:underline">
            ← Back
            </a>

            <button wire:click="update" class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-700 w-full sm:w-auto">  
                <span wire:loading.remove wire:target="update">
                    Update Item
                </span>
                <span wire:loading wire:target="update">
                    Saving...
                </span>
            </button>
        </div>
    </div>
</div>