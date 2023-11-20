@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    div#footer {position:fixed;bottom:27px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px;  margin-left: 15px;margin-right: 15px;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
 <!-- po Order print templete-->
<div class="po_print_template">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                    <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                @else

                    <p style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</p>
                @endif
            </div>

            <div class="col-8 text-end">
                <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
                <p><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
                <p>
                    <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                    <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h5 style="text-transform:uppercase;"><strong>@lang('menu.purchase_order')</strong></h5>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.supplier') : </strong><b>{{ $order?->supplier?->name }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong><b>{{ $order?->supplier?->phone }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong><b>{{ $order?->supplier?->address }}</b></li>
                </ul>
            </div>
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong><b>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($order->date)) }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.po_id') : </strong> <b>{{ $order->invoice_id }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.delivery_date') : </strong><b>{{ $order->delivery_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($order->delivery_date)) : '' }}</b></li>
                </ul>
            </div>
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.receiving_status') : </strong>{{ $order->po_receiving_status }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.created_by') : </strong>
                        {{ $order?->admin?->prefix.' '.$order?->admin?->name.' '.$order?->admin?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="po_product_table mt-2 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th style="font-size:11px!important;">@lang('menu.sl')</th>
                        <th style="font-size:11px!important;">@lang('menu.description')</th>
                        <th style="font-size:11px!important;">@lang('menu.ordered_quantity')</th>
                    </tr>
                </thead>
                <tbody class="po_print_product_list">
                    @php
                        $isMultiplierUnitExists = 1;
                    @endphp
                    @foreach ($order->orderedProducts as $orderedProduct)
                        <tr>
                            @php
                                $variant = $orderedProduct->variant ? ' ('.$orderedProduct->variant->variant_name.')' : '';
                            @endphp

                            <td style="font-size:11px!important;">
                                {{ $loop->index + 1 }}
                            </td>

                            <td style="font-size:11px!important;">
                                {{ $orderedProduct?->product?->name.' '.$variant }}
                                <small>{!! $orderedProduct->description ? '<br/>'.$orderedProduct->description : '' !!}</small>
                            </td>

                            @php
                                $baseUnitMultiplier = $orderedProduct?->orderUnit?->base_unit_multiplier ? $orderedProduct?->orderUnit?->base_unit_multiplier : 1;
                                $orderedQty = $orderedProduct->order_quantity / $baseUnitMultiplier;
                            @endphp
                            <td style="font-size:11px!important;">
                                @if ($orderedProduct?->orderUnit?->baseUnit)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($orderedProduct->order_quantity) }}/{{ $orderedProduct?->orderUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}
                                @else

                                    {{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br/><br/>
        <div class="row">
            <div class="col-4 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.prepared_by')</p>
            </div>

            <div class="col-4 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.checked_by')</p>
            </div>

            <div class="col-4 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorize_by')</p>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($order->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{ $order->invoice_id }}</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_sale'))
                        <small class="d-block">@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd') .</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- po print templete end-->
