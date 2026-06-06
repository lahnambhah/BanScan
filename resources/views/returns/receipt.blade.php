<!DOCTYPE html>
<html>
<head>
    <title>Return Receipt</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background: white;
            padding: 0;
        }

        /* Receipt container – full width, no fixed height */
        .receipt {
            width: 100%;
            background: white;
            padding: 20px 24px 24px 24px;   /* top, right, bottom, left */
            border-bottom: 2px dashed #aaa;  /* <-- cut line always at the end */
        }

        .center {
            text-align: center;
        }

        .store-name {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .store-sub {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }

        .line {
            border-top: 1px dashed #999;
            margin: 14px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin: 8px 0;
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }

        .total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 18px;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 2px solid black;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 20px;
        }

        /* Print button hidden on screen (optional – we auto‑print anyway) */
        .print-btn {
            display: none;
        }

        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            .receipt {
                border-bottom: 2px dashed black;  /* visible cut line on paper */
                padding: 16px 20px;
            }
        }
    </style>
</head>
<body>
<div class="receipt">
    <div class="center">
        <div class="store-name">BanScan Store</div>
        <div class="store-sub">Return Receipt</div>
    </div>
    <div class="line"></div>

    <div class="info-row">
        <span>Date:</span>
        <span>{{ $return->created_at->format('d M Y h:i A') }}</span>
    </div>
    <div class="info-row">
        <span>Customer Phone:</span>
        <span>{{ $return->customer_phone }}</span>
    </div>

    <div class="line"></div>

    @foreach($return->items as $item)
        <div class="item-row">
            <span>
                {{
                    $item->saleItem->is_custom
                        ? $item->saleItem->custom_name
                        : ($item->item->name ?? 'Deleted Item')
                }}
                x {{ $item->quantity }}
            </span>

            <span>
                ₹ {{ number_format($item->price * $item->quantity, 2) }}
            </span>
        </div>
    @endforeach

    <div class="line"></div>

    <div class="total">
        <span>Total Refund</span>
        <span>₹ {{ number_format($return->total, 2) }}</span>
    </div>

    <div class="footer">
        Thank you · Keep this receipt
    </div>
    <!-- The dashed cut line appears right after this footer, thanks to border-bottom on .receipt -->
</div>

<script>
    // Automatically open print dialog when page loads
    window.onload = function() {
        window.print();
    };

    // After printing (or cancelling), redirect back to scan page
    window.onafterprint = function() {
        window.location.href = '/scan';
    };
</script>
</body>
</html>