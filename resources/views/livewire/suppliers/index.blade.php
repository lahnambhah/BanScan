<div class="max-w-7xl mx-auto px-2 sm:px-0 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl md:text-3xl font-bold">
                Suppliers
            </h1>

            <p class="text-gray-500 text-sm md:text-base">
                Manage and organize your suppliers
            </p>
        </div>

        {{-- BUTTON --}}
        <a href="/suppliers/create"
            class="bg-gray-800 text-white px-4 py-2 rounded text-center flex items-center justify-center gap-2">
            <x-heroicon-o-plus class="w-4 h-4" />
            Add Supplier
        </a>
    </div>

    {{-- SEARCH --}}
    <div>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search suppliers..."
           class="w-full md:w-96 border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500">
    </div>

    {{-- SUCCESS --}}
    @if(session()->has('success'))

    <div
        x-data="{show:true}"
        x-init="setTimeout(()=>show=false,3000)"
        x-show="show"
        class="bg-green-100 text-green-700 px-4 py-3 rounded-xl shadow">
        {{ session('success') }}
    </div>

    @endif

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block bg-white rounded-2xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b text-gray-600">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">
                            Name
                        </th>

                        <th class="px-6 py-4 text-left font-semibold">
                            Email
                        </th>

                        <th class="px-6 py-4 text-left font-semibold">
                            Phone
                        </th>

                        <th class="px-6 py-4 text-left font-semibold">
                            Address
                        </th>

                        <th class="px-6 py-4 text-left font-semibold">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($suppliers as $supplier)

                    <tr class="hover:bg-gray-50">
                        {{-- NAME --}}
                        <td class="px-6 py-4 font-semibold">
                            {{ $supplier->name }}
                        </td>

                        {{-- EMAIL --}}
                        <td class="px-6 py-4 text-gray-600">
                            {{ $supplier->email ?: 'N/A' }}
                        </td>

                        {{-- PHONE --}}
                        <td class="px-6 py-4 text-gray-600">
                            {{ $supplier->phone ?: 'N/A' }}
                        </td>

                        {{-- ADDRESS --}}
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                            {{ $supplier->address ?: 'No address' }}
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <a href="/suppliers/{{ $supplier->id }}/edit"
                                    class="text-orange-500 hover:text-orange-700 transition">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>

                                <button
                                    onclick="confirm('Delete this supplier?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $supplier->id }})"
                                    class="text-gray-400 hover:text-red-500 transition">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="5"
                            class="px-6 py-10 text-center text-gray-400">
                            No suppliers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="md:hidden space-y-4">

        @forelse($suppliers as $supplier)

        <div class="bg-white rounded-2xl shadow border p-4">
            {{-- TOP --}}
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <h2 class="font-bold text-base break-words">
                        {{ $supplier->name }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-1 break-all">
                        {{ $supplier->email ?: 'No email' }}
                    </p>
                </div>
            </div>

            {{-- DETAILS --}}
            <div class="grid grid-cols-1 gap-3 mt-4 text-sm">
                {{-- PHONE --}}
                <div>
                    <p class="text-gray-400 text-xs mb-1">
                        Phone
                    </p>
                    <p class="font-medium">
                        {{ $supplier->phone ?: 'N/A' }}
                    </p>
                </div>

                {{-- ADDRESS --}}
                <div>
                    <p class="text-gray-400 text-xs mb-1">
                        Address
                    </p>
                    <p class="font-medium break-words">
                        {{ $supplier->address ?: 'No address available' }}
                    </p>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="flex items-center justify-end gap-5 mt-5 pt-4 border-t">
                <a href="/suppliers/{{ $supplier->id }}/edit"
                    class="text-orange-500 hover:text-orange-700">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                </a>

                <button
                    onclick="confirm('Delete this supplier?') || event.stopImmediatePropagation()"
                    wire:click="delete({{ $supplier->id }})"
                    class="text-gray-400 hover:text-red-500">
                    <x-heroicon-o-trash class="w-5 h-5" />
                </button>
            </div>
        </div>

        @empty

        <div class="bg-white rounded-2xl shadow p-8 text-center text-gray-400">
            No suppliers found.
        </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $suppliers->links() }}
    </div>
</div>