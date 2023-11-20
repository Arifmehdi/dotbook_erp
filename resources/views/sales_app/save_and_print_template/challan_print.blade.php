@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<!-- Challan print templete-->
@include('sales_app.save_and_print_template.partials.add_sale_default_challan_layout')
<!-- Challan print templete end-->
