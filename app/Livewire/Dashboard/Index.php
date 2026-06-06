<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Models\Item;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ReturnModel;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Index extends Component
{
    public $selectedItem=null;
    public function render()
    {
        // DATE
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // ITEMS 
        $totalItemsToday = Item::count();
        $totalItemsYesterday = Item::whereDate('created_at', '<=', $yesterday)
            ->count();

        // LOW STOCK 
        $lowStockToday = Item::where('stock', '<=', 5)->count();
        $lowStockYesterday = Item::where('stock', '<=', 5)
            ->whereDate('updated_at', '<=', $yesterday)
            ->count();

        // SALES
        $todaySales = Sale::whereDate('created_at', today())->sum('total');
        $yesterdaySales = Sale::whereDate('created_at', today()->subDay())->sum('total');

        // RETURNS
        $todayReturns = ReturnModel::whereDate('created_at', $today)->sum('total');
        $yesterdayReturns = ReturnModel::whereDate('created_at', $yesterday)->sum('total');

        // PROFIT 
        $todayProfit = $todaySales - $todayReturns;
        $yesterdayProfit = $yesterdaySales - $yesterdayReturns;

        // GROWTH FUNCTION 
        $growth = function ($today, $yesterday) {
            if ($yesterday == 0) {
                return $today > 0 ? 100 : 0;
            }
            return round((($today - $yesterday) / $yesterday) * 100);
        };

        // WEEKLY SALES
        $weekDays = collect(range(0, 6))->map(function ($i) {
            return Carbon::now()->startOfWeek()->addDays($i);
        });

        $weeklySales = $weekDays->map(function ($day) {
            return Sale::whereDate('created_at', $day)->sum('total');
        });

        // RECENT SALES
        $recentSales = SaleItem::with('item')
            ->latest()
            ->take(5)
            ->get();

        // TOP SELLING 
        $topSelling = SaleItem::where('is_custom', false)
            ->selectRaw('item_id, SUM(quantity) as total_qty')
            ->groupBy('item_id')
            ->with('item')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('livewire.dashboard.index', [

            // MAIN STATS 
            'totalItems' => $totalItemsToday,
            'lowStock' => $lowStockToday,
            'categories' => Category::count(),
            'profitToday' => $todayProfit,

            // GROWTH 
            'itemsPercent' => $growth($totalItemsToday, $totalItemsYesterday),
            'lowStockPercent' => $growth($lowStockToday, $lowStockYesterday),
            'profitPercent' => $growth($todayProfit, $yesterdayProfit),

            // CHART
            'weeklySales' => $weeklySales,
            'stockStats' => $this->getStockStats(),

            // TABLES 
            'recentSales' => $recentSales,
            'topSelling' => $topSelling,

            'salesToday' => $todaySales,
            'salesPercent' => $growth($todaySales, $yesterdaySales)
        ]);
    }

    public function getStockStats()
    {
        // ALL ITEMS DEFAULT VIEW
        if (!$this->selectedItem) {

            return [
                'all' => true,
                'total_items' => Item::count(),
                'total_stock' => Item::sum('stock'),
                'low_stock' => Item::where('stock', '<=', 5)->count(),
                'out_stock' => Item::where('stock', 0)->count(),
            ];
        }

        // SINGLE ITEM VIEW
        $item = Item::find($this->selectedItem);
        if (!$item) {
            return null;
        }

        $sold = SaleItem::where('item_id', $item->id)->sum('quantity');
        return [
            'all' => false,
            'stock' => $item->stock,
            'sold' => $sold,
        ];
    }
}