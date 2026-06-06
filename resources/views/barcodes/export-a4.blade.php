@php
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Print Barcodes</title>

    <style>

        @page {
            size: A4;
            margin: 12mm;
        }

        body{
            font-family: Arial, sans-serif;
            margin:0;
            padding:0;
            background:white;
        }

        .sheet{
            display:grid;
            grid-template-columns: repeat(3, 1fr);
            gap:0;
        }

        .label{
            border:2px dashed #999;
            height:120px;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            padding:10px;
            box-sizing:border-box;
            margin:-1px;
            text-align:center;
        }

        .barcode{
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
        }

        .barcode svg{
            width:170px;
            height:45px;
        }

        .code{
            margin-top:4px;
            font-size:12px;
            letter-spacing:2px;
            font-weight:500;
        }

        .name{
            margin-top:6px;
            font-size:14px;
            font-weight:700;
            text-align:center;
        }

        .price{
            margin-top:4px;
            font-size:13px;
            font-weight:600;
            color:#444;
        }

    </style>
</head>

<body>

<div class="sheet">

@foreach($items as $item)

<div class="label">

    <div class="barcode">

        {!! DNS1D::getBarcodeSVG($item->barcode, 'C128', 1.4, 40, 'black', false) !!}

        <div class="code">
            {{ $item->barcode }}
        </div>

    </div>

    <div class="name">
        {{ $item->name }}
    </div>

    <div class="price">
        ₹{{ number_format($item->price) }}
    </div>

</div>

@endforeach

</div>

<script>
    // OPEN PRINT DIRECTLY
    window.onload = function () {
        window.print();
    };

    // RETURN TO BARCODES PAGE
    window.onafterprint = function () {
        window.location.href = "/barcodes";
    };
</script>

</body>
</html>