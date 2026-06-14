<!DOCTYPE html>
<html>
<head>
    <title>BanScan Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
        }
        .sub {
            font-size: 12px;
            color: #666;
        }
        .section {
            margin-top: 20px;
        }
        h3 {
            font-size: 15px;
            margin-bottom: 10px;
            padding-left: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            vertical-align: top;
        }
        th {
            background: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-table td {
            text-align: center;
        }
        .day-block {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        .day-header {
            background: #f9f9f9;
            padding: 8px 10px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }
        .day-header .date {
            float: left;
        }
        .day-header .total {
            float: right;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th, .items-table td {
            border: none;
            border-bottom: 1px solid #eee;
            padding: 6px 10px;
        }
        .items-table th {
            background: none;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="title">BanScan Inventory Report</div>
    <div class="sub">
        Period:
        {{ date('d/m/Y', strtotime($fromDate)) }}
        →
        {{ date('d/m/Y', strtotime($toDate)) }}
    </div>
</div>

@if(($mode ?? 'sales') === 'item')
    {{-- ITEM REPORT --}}
    <div class="section">
        <h3>Item Report: {{ $itemName ?? 'N/A' }}</h3>

        {{-- Summary cards --}}
        <table class="summary-table" style="width: 100%; margin-bottom: 15px;">
            <tr>
                <th>Available Stock</th>
                <th>Total Sold</th>
                <th>Out of Stock</th>
                <th>Total Items</th>
            </tr>
            <tr>
                <td>{{ $itemStock }}</td>
                <td>{{ $itemSold }}</td>
                <td>{{ $itemOut ? 'Yes' : 'No' }}</td>
                <td>{{ $itemStock + $itemSold }}</td>
            </tr>
        </table>

        {{-- Sales table with phone numbers --}}
        <h3>Sales Over Period</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Phone Number</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalQty = 0; $totalAmount = 0; @endphp
                @forelse($itemSalesData as $row)
                    @php
                        $totalQty += $row['quantity'];
                        $totalAmount += $row['amount'];
                    @endphp
                    <tr>
                        <td>{{ $row['date'] }}</td>
                        <td>{{ $row['phone'] }}</td>
                        <td class="text-right">{{ $row['quantity'] }}</td>
                        <td class="text-right">₹ {{ number_format($row['amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No sales recorded for this item in the period.</td>
                    </tr>
                @endforelse
            </tbody>
            @if(count($itemSalesData) > 0)
                <tfoot>
                    <tr style="background: #f5f5f5; font-weight: bold;">
                        <td colspan="2">Total</td>
                        <td class="text-right">{{ $totalQty }}</td>
                        <td class="text-right">₹ {{ number_format($totalAmount, 2) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

@elseif(($mode ?? 'sales') === 'sales')
    {{-- SALES REPORT --}}
    <div class="section">
        <h3>Sales Summary</h3>
        <table class="summary-table">
            <tr>
                <th>Total Revenue</th>
                <th>Returns</th>
                <th>Net Revenue</th>
            </tr>
            <tr>
                <td>₹ {{ number_format($totalSales, 2) }}</td>
                <td>₹ {{ number_format($totalReturns, 2) }}</td>
                <td>₹ {{ number_format($netRevenue, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Daily Sales Breakdown</h3>
        @forelse($dailySalesData as $day)
            <div class="day-block">
                <div class="day-header clearfix">
                    <span class="date">{{ $day['formatted_date'] }}</span>
                    <span class="total">
                        {{ $day['items_count'] }} items · {{ $day['units_count'] }} units
                        | ₹ {{ number_format($day['total_amount'], 2) }}
                    </span>
                </div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($day['items'] as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td class="text-right">{{ $item['quantity'] }}</td>
                                <td class="text-right">₹ {{ number_format($item['price'], 2) }}</td>
                                <td class="text-right">₹ {{ number_format($item['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-center">No sales found for this period.</p>
        @endforelse
    </div>
@endif

<div class="footer">
    Generated by BanScan • {{ now()->format('d M Y h:i A') }}
</div>

</body>
</html>