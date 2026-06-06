<div class="max-w-3xl mx-auto px-4 sm:px-0">
    <h1 class="text-2xl sm:text-3xl font-bold mb-2">
        Edit Supplier
    </h1>

    <p class="text-gray-500 mb-6">
        Update supplier details
    </p>


    <div class="bg-white p-5 sm:p-8 rounded-xl shadow space-y-6">
        <div>
            <label class="block mb-2 font-medium">Name</label>
            <input wire:model="name" class="w-full border rounded-lg px-4 py-2">
        </div>

        <div>
            <label class="block mb-2 font-medium">Email</label>
            <input wire:model="email" class="w-full border rounded-lg px-4 py-2">
        </div>

        <div>
            <label class="block mb-2 font-medium">Phone</label>
            <input wire:model="phone" class="w-full border rounded-lg px-4 py-2">
        </div>

        <div>
            <label class="block mb-2 font-medium">Address</label>
            <textarea wire:model="address" class="w-full border rounded-lg px-4 py-2"></textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:justify-between sm:items-center">
            <a href="/suppliers" class="text-gray-500">← Back</a>

            <button
                wire:click="update"
                class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-900 transition w-full sm:w-auto">
                Update Supplier
            </button>
        </div>
    </div>
</div>