<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>

    <style>

        @page{
            margin:0;
        }

        *{
            box-sizing:border-box;
            margin:0;
            padding:0;
        }

        body{
            font-family:'Courier New', monospace;
            background:#f5f5f5;
            padding:10px;
            color:#111;
        }

        .receipt-page{
            width:100%;
            max-width:800px;
            margin:auto;
            background:#fff;
            min-height:100vh;
            padding:28px 34px;
        }

        .center{
            text-align:center;
        }

        .store-name{
            font-size:28px;
            font-weight:700;
            letter-spacing:2px;
            text-transform:uppercase;
        }

        .store-sub{
            font-size:11px;
            color:#777;
            margin-top:2px;
        }

        .receipt-title{
            margin-top:8px;
            font-size:13px;
            font-weight:700;
            letter-spacing:1px;
        }

        .line{
            border-top:1px dashed #999;
            margin:12px 0;
        }

        .info-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:4px 25px;
            margin-top:10px;
            font-size:12px;
        }

        .info{
            display:flex;
            justify-content:space-between;
            border-bottom:1px solid #f2f2f2;
            padding:4px 0;
        }

        .info span:first-child{
            color:#666;
        }

        .info span:last-child{
            font-weight:600;
        }

        .table-head{
            display:grid;
            grid-template-columns:1fr 70px 100px 120px;
            padding:7px 0;
            border-bottom:1px solid #ddd;
            font-size:11px;
            font-weight:700;
            color:#777;
            text-transform:uppercase;
            letter-spacing:1px;
        }

        .table-head span:not(:first-child){
            text-align:right;
        }

        .item{
            display:grid;
            grid-template-columns:1fr 70px 100px 120px;
            gap:10px;
            align-items:center;
            padding:8px 0;
            border-bottom:1px solid #f3f3f3;
        }

        .item-name{
            font-size:13px;
            font-weight:600;
            line-height:1.3;
        }

        .qty,
        .price,
        .subtotal{
            text-align:right;
            font-size:12px;
        }

        .subtotal{
            font-weight:700;
        }

        .totals{
            margin-top:10px;
            margin-left:auto;
            width:280px;
        }

        .total-row{
            display:flex;
            justify-content:space-between;
            padding:4px 0;
            font-size:12px;
        }

        .grand-total{
            display:flex;
            justify-content:space-between;
            margin-top:6px;
            padding-top:8px;
            border-top:2px solid #111;
            font-size:18px;
            font-weight:700;
        }

        .footer{
            margin-top:18px;
            text-align:center;
        }

        .thanks{
            font-size:14px;
            font-weight:700;
        }

        .note{
            margin-top:4px;
            font-size:10px;
            color:#888;
        }

        .receipt-no{
            margin-top:8px;
            font-size:10px;
            letter-spacing:2px;
            color:#aaa;
        }

        .print-btn{
            position:fixed;
            bottom:20px;
            right:20px;
            background:#111;
            color:#fff;
            border:none;
            padding:10px 20px;
            border-radius:6px;
            cursor:pointer;
            font-size:13px;
        }

        .print-btn:hover{
            background:#333;
        }

        @media print{

            body{
                background:#fff;
                padding:0;
            }

            .receipt-page{
                max-width:100%;
                min-height:auto;
                padding:18px 22px;
            }

            .print-btn{
                display:none;
            }
        }

    </style>
</head>

<body onload="window.print(); setTimeout(() => window.location.href='/scan', 500);">

<div class="receipt-page">

    {{-- HEADER --}}
    <div class="center">

        <div class="store-name">
            BanScan Store
        </div>

        <div class="store-sub">
            Smart Inventory & POS System
        </div>

        <div class="receipt-title">
            SALES RECEIPT
        </div>

    </div>

    <div class="line"></div>

    {{-- INFO --}}
    <div class="info-grid">

        <div class="info">
            <span>Date</span>
            <span>{{ $sale->created_at->format('d M Y') }}</span>
        </div>

        <div class="info">
            <span>Time</span>
            <span>{{ $sale->created_at->format('h:i A') }}</span>
        </div>

        <div class="info">
            <span>Phone</span>
            <span>{{ $sale->customer_phone }}</span>
        </div>

        <div class="info">
            <span>Receipt No.</span>
            <span>{{ $sale->receipt_no }}</span>
        </div>

    </div>

    <div class="line"></div>

    {{-- TABLE HEAD --}}
    <div class="table-head">
        <span>Item</span>
        <span>Qty</span>
        <span>Price</span>
        <span>Total</span>
    </div>

    {{-- ITEMS --}}
    @foreach($sale->items as $item)

        <div class="item">

            <div class="item-name">
                @if($item->is_custom)
                    {{ $item->custom_name }}
                @else
                    {{ $item->item->name ?? 'Deleted Item' }}
                @endif
            </div>

            <div class="qty">
                {{ $item->quantity }}
            </div>

            <div class="price">
                ₹{{ number_format($item->price, 2) }}
            </div>

            <div class="subtotal">
                ₹{{ number_format($item->quantity * $item->price, 2) }}
            </div>

        </div>

    @endforeach

    {{-- TOTALS --}}
    <div class="totals">

        <div class="total-row">
            <span>Subtotal</span>
            <span>₹{{ number_format($sale->total, 2) }}</span>
        </div>

        <div class="grand-total">
            <span>TOTAL</span>
            <span>₹{{ number_format($sale->total, 2) }}</span>
        </div>

    </div>

    <div class="line"></div>

    {{-- FOOTER --}}
    <div class="footer">

        <div class="thanks">
            Thank You For Shopping!
        </div>

        <div class="note">
            Please keep this receipt for future reference
        </div>
    </div>

</div>

<button class="print-btn" onclick="window.print()">
    🖨 Print
</button>

</body>
</html>