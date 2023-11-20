@php
    $inWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<!-- D/O print templete-->
@include('sales_app.save_and_print_template.partials.do_default_print_layout')
<!-- D/O print templete end-->
