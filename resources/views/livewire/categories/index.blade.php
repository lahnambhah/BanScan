<div class="max-w-7xl mx-auto px-2 sm:px-0 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold">Categories</h1>  
            <p class="text-gray-500 text-sm md:text-base">Organize and manage your inventory categories</p>
        </div>

        {{-- BUTTON --}}
        <a href="/categories/create"
           class="bg-gray-800 text-white px-4 py-2 rounded text-center flex items-center justify-center gap-2">
            <x-heroicon-o-plus class="w-4 h-4" />
            Add Category
        </a>
    </div>

    {{-- SEARCH --}}
    <div>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search categories..."
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
                            Category
                        </th>

                        <th class="px-6 py-4 text-left font-semibold">
                            Description
                        </th>

                        <th class="px-6 py-4 text-left font-semibold">
                            Items
                        </th>

                        <th class="px-6 py-4 text-left font-semibold">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50">

                        {{-- NAME --}}
                        <td class="px-6 py-4 font-semibold">
                            {{ $category->name }}
                        </td>

                        {{-- DESCRIPTION --}}
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                            {{ $category->description ?: 'No description' }}
                        </td>

                        {{-- ITEMS --}}
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $category->items_count ?? 0 }} Items
                            </span>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <a href="/categories/{{ $category->id }}/edit"
                                    class="text-orange-500 hover:text-orange-700 transition">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>

                                <button
                                    onclick="confirm('Delete this category?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $category->id }})"
                                    class="text-gray-400 hover:text-red-500 transition">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="4"
                            class="text-center py-10 text-gray-400">
                            No categories found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="md:hidden space-y-4">

        @forelse($categories as $category)

        <div class="bg-white rounded-2xl shadow border p-4">
            {{-- TOP --}}
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h2 class="font-bold text-base break-words">
                        {{ $category->name }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-2 break-words">
                        {{ $category->description ?: 'No description available' }}
                    </p>
                </div>

                {{-- COUNT --}}
                <div class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                    {{ $category->items_count ?? 0 }}
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="flex items-center justify-end gap-5 mt-5 pt-4 border-t">
                <a href="/categories/{{ $category->id }}/edit"
                    class="text-orange-500 hover:text-orange-700">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                </a>

                <button
                    onclick="confirm('Delete this category?') || event.stopImmediatePropagation()"
                    wire:click="delete({{ $category->id }})"
                    class="text-gray-400 hover:text-red-500">
                    <x-heroicon-o-trash class="w-5 h-5" />
                </button>
            </div>
        </div>

        @empty

        <div class="bg-white rounded-2xl shadow p-8 text-center text-gray-400">
            No categories found
        </div>

        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>