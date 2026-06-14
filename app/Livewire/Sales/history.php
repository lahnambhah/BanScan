<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Sale;

#[Layout('layouts.app')]
class History extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate = '';
    public $endDate = '';

    protected $rules = [
        'startDate' => 'nullable|date|before_or_equal:today',
        'endDate' => 'nullable|date|before_or_equal:today|after_or_equal:startDate',
    ];

    protected $messages = [

        // START DATE
        'startDate.date' => 'Start date is invalid.',
        'startDate.before_or_equal' => 'Start date cannot be in the future.',

        // END DATE
        'endDate.date' => 'End date is invalid.',
        'endDate.before_or_equal' => 'End date cannot be in the future.',
        'endDate.after_or_equal' => 'End date must be after or equal to the start date.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Check date range
        if ($this->startDate && $this->endDate) {

            if ($this->startDate > $this->endDate) {

                session()->flash(
                    'error',
                    'Start date cannot be greater than end date.'
                );

                return view('livewire.sales.history', [
                    'sales' => Sale::latest()->paginate(10)
                ]);
            }
        }

        $sales = Sale::latest()

            ->when($this->search, function ($query) {

                $query->where('customer_phone', 'like', '%' . $this->search . '%')
                    ->orWhere('receipt_no', 'like', '%' . $this->search . '%');

            })

            ->when($this->startDate, function ($query) {

                $query->whereDate(
                    'created_at',
                    '>=',
                    $this->startDate
                );

            })

            ->when($this->endDate, function ($query) {

                $query->whereDate(
                    'created_at',
                    '<=',
                    $this->endDate
                );

            })

            ->paginate(10);

        return view('livewire.sales.history', [
            'sales' => $sales
        ]);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }
}