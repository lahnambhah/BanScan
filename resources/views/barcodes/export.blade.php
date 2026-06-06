@php
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
@endphp

<html>
<head>
    <title>All Barcodes</title>
</head>
<body style="font-family: Arial;">

@foreach($items as $item)

<div style="margin-bottom:30px; text-align:center;">

    {{-- SVG BARCODE --}}
    <div>
        {!! DNS1D::getBarcodeSVG($item->barcode, 'C128') !!}
    </div>

    <p style="margin-top:5px;">
        {{ $item->name }}
    </p>

</div>

@endforeach

<script>
window.print();
</script>

</body>
</html>