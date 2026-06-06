<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\ReturnModel;
use App\Models\SaleItem;
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $fromDate;
    public $toDate;
    public $mode = 'sales';

    // Sales report
    public $totalSales = 0;
    public $totalReturns = 0;
    public $netRevenue = 0;
    public $dailySalesData = [];

    // Item report
    public $selectedItem = null;
    public $itemStock = 0;
    public $itemSold = 0;
    public $itemOut = false;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->fromDate = now()->startOfMonth()->toDateString();
        $this->toDate = now()->endOfMonth()->toDateString();
        $this->loadReport();
    }

    public function loadReport()
    {
        $this->validateReport();
        $from = Carbon::parse($this->fromDate)->startOfDay();
        $to   = Carbon::parse($this->toDate)->endOfDay();

        // SALES REPORT
        $this->totalSales = Sale::whereBetween('created_at', [$from, $to])->sum('total');
        $this->totalReturns = ReturnModel::whereBetween('created_at', [$from, $to])->sum('total');
        $this->netRevenue = $this->totalSales - $this->totalReturns;

        $sales = Sale::with(['items.item'])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at')
            ->get();

        $grouped = [];
        foreach ($sales as $sale) {
            $date = $sale->created_at->format('Y-m-d');
            if (!isset($grouped[$date])) {
                $grouped[$date] = [
                    'date' => $date,
                    'formatted_date' => $sale->created_at->format('l, d-m-Y'),
                    'total_amount' => 0,
                    'items_count' => 0,
                    'units_count' => 0,
                    'items' => []
                ];
            }

            $grouped[$date]['total_amount'] += $sale->total;
            $grouped[$date]['items_count'] += $sale->items->count();

            foreach ($sale->items as $saleItem) {
                $grouped[$date]['units_count'] += $saleItem->quantity;
                $grouped[$date]['items'][] = [
                    'name' => $saleItem->item->name ?? 'Deleted Item',
                    'quantity' => $saleItem->quantity,
                    'price' => $saleItem->price,
                    'amount' => $saleItem->quantity * $saleItem->price,
                ];
            }
        }
        $this->dailySalesData = collect($grouped)->sortKeys()->values()->toArray();

        // ITEM REPORT
        if ($this->selectedItem) {
            $item = Item::find($this->selectedItem);
            if ($item) {
                $this->itemStock = $item->stock;
                $this->itemSold = SaleItem::where('item_id', $item->id)
                    ->whereBetween('created_at', [$from, $to])
                    ->sum('quantity');
                $this->itemOut = ($item->stock == 0);
            }
        } else {
            $this->itemStock = 0;
            $this->itemSold = 0;
            $this->itemOut = false;
        }
    }

    public function getItemSalesPaginatedProperty()
    {
        if (!$this->selectedItem) {
            return null;
        }

        $from = Carbon::parse($this->fromDate)->startOfDay();
        $to   = Carbon::parse($this->toDate)->endOfDay();

        $paginated = SaleItem::where('item_id', $this->selectedItem)
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at')
            ->paginate(10);

        $saleIds = $paginated->getCollection()->pluck('sale_id')->unique()->filter();
        $salesMap = Sale::whereIn('id', $saleIds)->get()->keyBy('id');

        $transformed = [];
        foreach ($paginated->getCollection() as $saleItem) {
            $sale = $salesMap->get($saleItem->sale_id);
            $phone = $sale->customer_phone ?? '—';
            $transformed[] = [
                'date' => $saleItem->created_at->format('d-m-Y'),
                'phone' => $phone,
                'quantity' => $saleItem->quantity,
                'amount' => $saleItem->quantity * $saleItem->price,
            ];
        }

        $paginated->setCollection(collect($transformed));
        return $paginated;
    }

    public function updatedSelectedItem()
    {
        $this->resetPage();
        $this->loadReport();
    }

    public function updatedFromDate()
    {
        $this->resetPage();
        $this->loadReport();
    }

    public function updatedToDate()
    {
        $this->resetPage();
        $this->loadReport();
    }

    public function downloadReport()
    {
        $this->validateReport();
        $this->loadReport();

        $from = Carbon::parse($this->fromDate)->startOfDay();
        $to   = Carbon::parse($this->toDate)->endOfDay();

        if ($this->mode === 'item') {
            // Item Report PDF
            $item = Item::find($this->selectedItem);
            if (!$item) {
                // Handle case where no item selected or item not found
                return redirect()->back()->with('error', 'No item selected');
            }

            $saleItems = SaleItem::where('item_id', $this->selectedItem)
                ->whereBetween('created_at', [$from, $to])
                ->orderBy('created_at')
                ->get();
            $saleIds = $saleItems->pluck('sale_id')->unique();
            $salesMap = Sale::whereIn('id', $saleIds)->get()->keyBy('id');
            $itemSalesDataFull = [];
            foreach ($saleItems as $saleItem) {
                $sale = $salesMap->get($saleItem->sale_id);
                $phone = $sale->customer_phone ?? '—';
                $itemSalesDataFull[] = [
                    'date' => $saleItem->created_at->format('d-m-Y'),
                    'phone' => $phone,
                    'quantity' => $saleItem->quantity,
                    'amount' => $saleItem->quantity * $saleItem->price,
                ];
            }

            $pdf = Pdf::loadView('livewire.reports.pdf', [
                'mode' => 'item',
                'itemName' => $item->name ?? 'N/A',
                'itemStock' => $this->itemStock,
                'itemSold' => $this->itemSold,
                'itemOut' => $this->itemOut,
                'itemSalesData' => $itemSalesDataFull,
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
            ]);
        } else {
            // Sales Report PDF
            $pdf = Pdf::loadView('livewire.reports.pdf', [
                'mode' => 'sales',
                'totalSales' => $this->totalSales,
                'totalReturns' => $this->totalReturns,
                'netRevenue' => $this->netRevenue,
                'dailySalesData' => $this->dailySalesData,
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
            ]);
        }

        return response()->streamDownload(fn() => print($pdf->output()), 'report.pdf');
    }

    public function render()
    {
        return view('livewire.reports.index', [
            'itemSalesPaginated' => $this->getItemSalesPaginatedProperty(),
        ]);
    }

    protected function validateReport()
    {
        $rules = [

            'fromDate' => 'required|date|before_or_equal:today',
            'toDate' => 'required|date|after_or_equal:fromDate',

        ];

        // ITEM REPORT VALIDATION
        if ($this->mode === 'item') {

            $rules['selectedItem'] = 'required|exists:items,id';

        }

        $this->validate($rules, [

            // FROM DATE
            'fromDate.required' => 'Start date is required.',
            'fromDate.date' => 'Invalid start date.',

            // TO DATE
            'toDate.required' => 'End date is required.',
            'toDate.date' => 'Invalid end date.',
            'toDate.after_or_equal' => 'End date must be after start date.',

            // ITEM
            'selectedItem.required' => 'Please select an item.',
            'selectedItem.exists' => 'Selected item does not exist.',

        ]);
    }
}