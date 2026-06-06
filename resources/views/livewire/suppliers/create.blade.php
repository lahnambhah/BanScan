<div class="max-w-3xl mx-auto px-4 sm:px-0">

    <h1 class="text-2xl sm:text-3xl font-bold mb-2">
        Add Supplier
    </h1>

    <p class="text-gray-500 mb-6">
        Add a new supplier for inventory sourcing
    </p>

    <div class="bg-white p-5 sm:p-8 rounded-xl shadow space-y-6">

        {{-- NAME --}}
        <div>
            <label class="block mb-2 font-medium">
                Supplier Name *
            </label>
            <input
                type="text"
                wire:model="name"
                class="w-full border rounded-lg px-4 py-2">

            @error('name')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="block mb-2 font-medium">
                Email Address *
            </label>
            <input
                type="email"
                wire:model="email"
                class="w-full border rounded-lg px-4 py-2">
            
                @error('email')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- PHONE --}}
        <div>
            <label class="block mb-2 font-medium">
                Phone Number *
            </label>
            <input
                type="text"
                wire:model="phone"
                class="w-full border rounded-lg px-4 py-2">
            
            @error('phone')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- ADDRESS --}}
        <div>
            <label class="block mb-2 font-medium">
                Address
            </label>
            <textarea
                wire:model="address"
                class="w-full border rounded-lg px-4 py-2">
            </textarea>
        </div>

        {{-- ACTIONS --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-between sm:items-center">
            <a href="/suppliers" class="text-gray-500">
                ← Back
            </a>

            <button
                wire:click="save"
                class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-900 transition w-full sm:w-auto">
                Save Supplier
            </button>
        </div>

    </div>
</div>