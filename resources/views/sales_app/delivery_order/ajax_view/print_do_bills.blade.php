<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $totalQty = 0;
    $totalUnitPrice = 0;
    $totalSubTotal = 0;
@endphp
    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-4">
            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            @else
                <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
            @endif
        </div>

        <div class="col-8 text-end">
            <p class="company_name" style="text-transform: uppercase;">
                <strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong>
            </p>

            <p class="company_address"><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>

            <p>
                <strong>@lang('menu.email') :</strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
            </p>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            <h6 style="text-transform:uppercase;"><strong>@lang('menu.bill_against_do') </strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-6">
            <ul class="list-unstyled">
                <li style="font-size:11px!important;"><strong>@lang('menu.customer') : </strong> {{ $do->customer ? $do->customer->name : ''  }}</li>
                <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong> {{ $do->customer ? $do->customer->phone : '' }}</li>
                <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong> {{ $do->customer ? $do->customer->address : ''  }}</li>
            </ul>
        </div>

        <div class="col-6">
            <ul class="list-unstyled">
                <li style="font-size:11px!important;"><strong>@lang('menu.do_id') : </strong>{{ $do->do_id }}</li>
                <li style="font-size:11px!important;"><strong>@lang('menu.do_date') : </strong>{{ date($__date_format, strtotime($do->do_date)) }}</li>
                <li style="font-size:11px!important;"><strong>@lang('menu.print_date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format']).' '.date($timeFormat) }}</li>
            </ul>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th style="font-size:10px!important;">@lang('menu.vehicle_no').</th>
                        <th style="font-size:10px!important;">@lang('menu.item_name')</th>
                        <th style="font-size:10px!important;">@lang('menu.invoice_id')</th>
                        <th style="font-size:10px!important;">@lang('menu.qty')</th>
                        <th class="text-end" style="font-size:10px!important;">@lang('menu.price')</th>
                        <th class="text-end" style="font-size:10px!important;">@lang('menu.sub_total')</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @php
                        $previousDate = '';
                    @endphp
                    @foreach ($saleProducts as $sProduct)
                        @php
                            $date = date($__date_format, strtotime($sProduct->report_date));
                        @endphp
                        @if ($previousDate != $date)

                            @php
                                $previousDate = $date;
                            @endphp

                            <thead>
                                <tr>
                                    <th colspan="8">{{ $date }}</th>
                                </tr>
                            </thead>
                        @endif

                        <tr>
                            <td style="font-size:10px!important;">{{ $sProduct->do_car_number }}</td>
                            <td style="font-size:10px!important;">
                                @php
                                    $variant = $sProduct->variant_name ? ' - ' . $sProduct->variant_name : '';
                                    $totalQty += $sProduct->quantity;
                                    $totalUnitPrice += $sProduct->unit_price_inc_tax;
                                    $totalSubTotal += $sProduct->subtotal;
                                @endphp
                                {{ $sProduct->name . $variant }}
                            </td>

                            <td style="font-size:10px!important;">{{ $sProduct->invoice_id }}</td>
                            <td style="font-size:10px!important;" class="fw-bold">{!! App\Utils\Converter::format_in_bdt($sProduct->quantity) . '/' . $sProduct->unit_code !!}</td>
                            <td class="text-end fw-bold" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($sProduct->unit_price_inc_tax) }}</td>
                            <td class="text-end fw-bold" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($sProduct->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-6"></div>
        <div class="col-6">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-end" style="font-size:10px!important;">@lang('menu.total_quantity') :</th>
                        <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($totalQty) }}</td>
                    </tr>

                    <tr>
                        <th class="text-end" style="font-size:10px!important;">@lang('menu.net_total_amount') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                        <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($totalSubTotal) }}</td>
                    </tr>

                </thead>
            </table>
        </div>
    </div>

    <div id="footer">
        <div class="row">
            <div class="col-4 text-start">
                <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
            </div>

            <div class="col-4 text-center">
                @if (config('company.print_on_sale'))
                    <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
