<div class="max-w-3xl mx-auto px-4 sm:px-0">
    <h1 class="text-2xl sm:text-3xl font-bold mb-2">
        Add Category
    </h1>

    <p class="text-gray-500 mb-6">
        Create a new inventory category
    </p>

    <div class="bg-white p-5 sm:p-8 rounded-xl shadow space-y-6">
        <div>
            <label class="block mb-2 font-medium">
                Category Name *
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

        <div>
            <label class="block mb-2 font-medium">
                Description
            </label>
            <textarea
                wire:model="description"
                class="w-full border rounded-lg px-4 py-2">
            </textarea>
        </div>


        <div class="flex flex-col sm:flex-row gap-3 sm:justify-between sm:items-center">
            <a href="/categories" class="text-gray-500">
                ← Back
            </a>
            <button
                wire:click="save"
                class="bg-gray-800 text-white px-6 py-2 rounded cursor-pointer w-full sm:w-auto">
                Save Category
            </button>
        </div>
    </div>
</div>