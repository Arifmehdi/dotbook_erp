@php
    $inWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<!-- Sale print templete-->
@include('sales_app.save_and_print_template.partials.add_sale_default_invoice_layout')
<!-- Sale print templete end-->
