<div wire:poll.10s class="space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Dashboard</h1>
            <p class="text-gray-500 text-sm">
                Welcome back! Here's what's happening with your inventory.
            </p>
        </div>

        {{-- NOTIFICATION ICON --}}
        <div class="relative" x-data="{ open: false }" wire:poll.5s>

            <button @click="open = !open" class="relative inline-flex items-center justify-center">
                <x-heroicon-o-bell class="w-8 h-8 block"/>
                @if(auth()->user()->unreadNotifications->count())
                    <span class="absolute top-0 right-0 translate-x-1/3 -translate-y-1/3
                                bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            {{-- DROPDOWN --}}
            <div x-show="open" @click.outside="open = false"
                class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg z-50">

                {{-- HEADER --}}
                <div class="flex justify-between items-center p-3 border-b font-semibold">
                    <span>Notifications</span>

                    <form method="POST" action="{{ route('notifications.readAll') }}"wire:submit.prevent="$refresh">
                        @csrf
                        <button class="text-xs text-blue-600 hover:underline">
                            Mark all
                        </button>
                    </form>
                </div>

                {{-- LIST --}}
                <div class="max-h-80 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications as $notification)

                        @php
                            $data = $notification->data ?? [];
                            $type = $data['type'] ?? 'old';
                        @endphp

                        <div class="p-3 border-b flex justify-between gap-2">
                            {{-- LEFT CONTENT --}}
                            <div class="text-sm">

                                {{-- SALE --}}
                                @if($type === 'sale')
                                    <p class="font-semibold">New Sale</p>
                                    <p class="text-gray-500">
                                        ₹{{ $data['total'] ?? 0 }}
                                    </p>

                                    @if(isset($data['sale_id']))
                                        <a href="{{ route('receipt.print', $data['sale_id']) }}"
                                        class="text-xs text-blue-600">
                                            View Receipt
                                        </a>
                                    @endif

                                {{-- LOW STOCK --}}
                                @elseif($type === 'low_stock')
                                    <p class="font-semibold">Low Stock</p>
                                    <p class="text-gray-500">
                                        {{ $data['item_name'] ?? 'Item' }}
                                        ({{ $data['stock'] ?? 0 }} left)
                                    </p>

                                {{-- NEW ITEM --}}
                                @elseif($type === 'new_item')
                                    <p class="font-semibold">New Item</p>
                                    <p class="text-gray-500">
                                        {{ $data['item_name'] ?? 'Item' }}
                                    </p>

                                {{-- OLD DATA (fallback) --}}
                                @else
                                    <p class="font-semibold">
                                        {{ $data['title'] ?? 'Notification' }}
                                    </p>

                                    <p class="text-gray-500">
                                        {{ $data['message'] ?? '' }}
                                    </p>
                                @endif

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $data['time'] ?? '' }}
                                </p>
                            </div>

                            {{-- RIGHT ACTION --}}
                            @if(is_null($notification->read_at))
                                <form method="POST" action="{{ route('notification.read', $notification->id) }}">
                                    @csrf
                                    <button class="text-xs text-blue-600 hover:underline">
                                        Mark as read
                                    </button>
                                </form>
                            @endif

                        </div>

                    @empty
                        <div class="p-3 text-gray-500 text-sm">
                            No notifications
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

        {{-- ITEMS --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow">
            <div class="flex justify-between">
               <div class="p-3 bg-slate-700 text-white rounded-lg">
                    <x-heroicon-o-cube class="w-6 h-6" /> 
                </div>
                {{--<div class="text-sm font-semibold
                    {{ $itemsPercent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $itemsPercent >= 0 ? '↑' : '↓' }}
                    {{ abs($itemsPercent) }}%
                </div>--}}
            </div>

            <p class="text-gray-500 mt-4">Total Items</p>
            <p class="text-3xl font-bold">{{ $totalItems }}</p>
        </div>

        {{-- LOW STOCK --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow">
            <div class="flex justify-between">
                <div class="p-3 bg-amber-600 text-white rounded-lg">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
                </div>
                {{-- <div class="text-sm font-semibold
                    {{ $lowStockPercent <= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $lowStockPercent <= 0 ? '↓' : '↑' }}
                    {{ abs($lowStockPercent) }}%
                </div> --}}
            </div>

            <p class="text-gray-500 mt-4">Low Stock</p>
            <p class="text-3xl font-bold">{{ $lowStock }}</p>
        </div>

        {{-- SALES --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow">
            <div class="flex justify-between items-start">

                <div class="p-3 bg-blue-600 text-white rounded-lg">
                    <x-heroicon-o-currency-rupee class="w-6 h-6" />
                </div>

                {{-- <div class="text-sm font-semibold
                    {{ $salesPercent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $salesPercent >= 0 ? '↑' : '↓' }}
                    {{ abs($salesPercent) }}%
                </div> --}}
            </div>

            <p class="text-gray-500 mt-4">Sales Today</p>
            <p class="text-3xl font-bold mt-1">
                ₹{{ number_format($salesToday, 0) }}
            </p>
        </div>

        {{-- CATEGORY --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow">
            <div class="flex justify-between">
                <div class="p-3 bg-gray-700 text-white rounded-lg">
                    <x-heroicon-o-folder class="w-6 h-6" />
                </div>
                {{-- <div class="text-green-600 text-sm font-semibold">↑ 0%</div> --}}
            </div>

            <p class="text-gray-500 mt-4">Categories</p>
            <p class="text-3xl font-bold">{{ $categories }}</p>
        </div>

    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- WEEKLY SALES --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow min-h-[300px]" wire:ignore>
            <h3 class="font-semibold mb-3">Weekly Sales</h3>
            <div class="h-[220px]">
                <canvas id="weeklySalesChart"></canvas>
            </div>
        </div>

        {{-- STOCK --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow min-h-[300px]">
            <!-- HEADER -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h3 class="font-semibold">Stock Overview</h3>

                <select wire:model.live="selectedItem"
                    class="border rounded px-3 py-2 text-sm w-full sm:w-48">
                    <option value="">All Items</option>

                    @foreach(\App\Models\Item::all() as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- CONTENT -->
            @if(!empty($stockStats))

                {{-- ALL ITEMS VIEW --}}
                @if($stockStats['all'])

                <div class="flex flex-col items-center justify-center h-[200px] space-y-3">
                    <p class="text-gray-500 text-sm">All Items Overview</p>
                    <p class="text-4xl font-bold text-blue-600">
                        {{ $stockStats['total_stock'] }}
                    </p>
                    <p class="text-xs text-gray-500">Total Stock</p>

                    <div class="grid grid-cols-3 gap-4 text-sm mt-2 w-full">
                        <div class="text-center">
                            <p class="font-semibold text-green-600">
                                {{ $stockStats['total_items'] }}
                            </p>
                            <p class="text-gray-500 text-xs">Items</p>
                        </div>

                        <div class="text-center">
                            <p class="font-semibold text-orange-500">
                                {{ $stockStats['low_stock'] }}
                            </p>
                            <p class="text-gray-500 text-xs">Low</p>
                        </div>

                        <div class="text-center">
                            <p class="font-semibold text-red-500">
                                {{ $stockStats['out_stock'] }}
                            </p>
                            <p class="text-gray-500 text-xs">Out</p>
                        </div>
                    </div>
                </div>

                {{-- SINGLE ITEM VIEW --}}
                @else
                    <div class="flex flex-col items-center justify-center h-[200px] space-y-3">
                        <p class="text-gray-500 text-sm">Item Overview</p>
                        <p class="text-4xl font-bold text-blue-600">
                            {{ $stockStats['stock'] }}
                        </p>
                        <p class="text-xs text-gray-500">In Stock</p>

                        <div class="grid grid-cols-2 gap-4 text-sm mt-2 w-full">
                            <div class="text-center">
                                <p class="font-semibold text-gray-800">
                                    {{ $stockStats['sold'] }}
                                </p>
                                <p class="text-gray-500 text-xs">Sold</p>
                            </div>

                            <div class="text-center">
                                <p class="font-semibold text-gray-800">
                                    {{ $stockStats['stock'] + $stockStats['sold'] }}
                                </p>
                                <p class="text-gray-500 text-xs">Total</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- RECENT + TOP SELLING --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- RECENT SALES --}}
        <div class="xl:col-span-2 bg-white p-4 sm:p-6 rounded-xl shadow">
            <h3 class="font-semibold mb-4">Recently Sold Items</h3>

            @if($recentSales->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm sm:text-sm">
                        <thead class="text-left text-gray-500 border-b text-sm">
                            <tr>
                                <th class="pb-2">Item</th>
                                <th class="pb-2">Qty</th>
                                <th class="pb-2">Price</th>
                                <th class="pb-2">Date</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach($recentSales as $sale)
                                <tr>
                                    <td class="py-2 font-medium">
                                        {{ $sale->is_custom
                                            ? $sale->custom_name
                                            : ($sale->item->name ?? 'Deleted Item')
                                        }}
                                    </td>

                                    <td class="py-2">
                                        {{ $sale->quantity }}
                                    </td>

                                    <td class="py-2">
                                        ₹{{ number_format($sale->price * $sale->quantity, 0) }}
                                    </td>

                                    <td class="py-2 text-gray-500">
                                        {{ $sale->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-400">No recent sales yet.</p>
            @endif
        </div>

        {{-- TOP SELLING --}}
        <div class="bg-white p-6 sm:p-6 rounded-xl shadow">
            <h3 class="font-semibold mb-4">Most Selling Items</h3>

            @if($topSelling->count())
                <div class="space-y-4">
                    @foreach($topSelling as $item)
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <p class="font-medium">
                                    {{ $item->is_custom
                                        ? $item->custom_name
                                        : ($item->item->name ?? 'Deleted Item') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $item->total_qty }} sold
                                </p>
                            </div>

                            <div class="text-blue-600 font-semibold">
                                #{{ $loop->iteration }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400">No sales data yet.</p>
            @endif
        </div>

    </div>

</div>

{{-- CHART SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let weeklyChart = null;

function loadCharts() {

    const weeklyCtx = document.getElementById('weeklySalesChart');

    if (weeklyCtx) {

        if (weeklyChart) weeklyChart.destroy();

        weeklyChart = new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
                datasets: [{
                    label: 'Sales',
                    data: @json($weeklySales),
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 50000,
                        ticks: {
                            stepSize: 1000,
                            callback: function(value) {
                                return '₹' + value;
                            }
                        }
                    }
                }
            }
        });
    }
}

document.addEventListener("DOMContentLoaded", loadCharts);
document.addEventListener("livewire:update", () => {
    setTimeout(loadCharts, 100);
});
</script>