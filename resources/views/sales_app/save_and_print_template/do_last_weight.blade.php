@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = json_decode($generalSettings->business, true)['date_format'];
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
    /* div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;} */
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
<div class="print_details">
    <div class="header_area">
        {{-- <div class="row">
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

                <p class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                <p><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                <p><strong>@lang('menu.email') :</strong> {{ json_decode($generalSettings->business, true)['email'] }}</p>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-md-12 text-center">
                <div class="">
                    <h4 style="text-transform: uppercase; border-bottom: 1px solid black;padding-bottom:5px;">@lang('menu.weight_details')</h4>
                    <h6 style="text-transform: uppercase;" class="mt-1"><strong>@lang('menu.for_sales')</strong></h6>
                </div>
            </div>
        </div>

        <div class="weight_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:12px!important;">
                            <strong>@lang('menu.date') : </strong>
                            {{ date($dateFormat.' '.$timeFormat, strtotime($weight->created_at)) }}
                        </li>

                        <li style="font-size:12px!important;"><strong>@lang('menu.vehicle_no') : </strong>{{ $weight->do_car_number }}</li>
                        <li style="font-size:12px!important;"><strong>@lang('menu.driver_name') : </strong>{{ $weight->do_driver_name }}</li>
                        <li style="font-size:12px!important;"><strong>@lang('menu.driver_phone') : </strong>{{ $weight->do_driver_phone }}</li>
                    </ul>
                </div>

                <div class="col-lg-4 text-center">
                    @if ($weight->sale)
                        <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($weight->sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    @endif
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:12px!important;">
                            <strong> @lang('menu.do_id') : </strong> {{ $weight->do ? $weight->do->do_id : '' }}
                        </li>

                        <li style="font-size:12px!important;">
                            <strong>@lang('menu.invoice_id') : </strong> {{ $weight->reserve_invoice_id }}
                        </li>

                        <li style="font-size:12px!important;">
                            <strong> {{ __("1st W/t By") }} : </strong>
                            {{ $weight->firstWeightedBy ? $weight->firstWeightedBy->prefix . ' ' . $weight->firstWeightedBy->name . ' ' . $weight->firstWeightedBy->last_name : 'N/A' }}
                        </li>

                        <li style="font-size:12px!important;">
                            <strong> {{ __("2nd W/t By") }} : </strong>
                            {{ $weight->secondWeightedBy ? $weight->secondWeightedBy->prefix . ' ' . $weight->secondWeightedBy->name . ' ' . $weight->secondWeightedBy->last_name : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <div class="with_list_table pt-3 pb-3">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td><strong>{{ __("1st") }}</strong> {{ __("Weight") }}</td>
                                <td>{{ App\Utils\Converter::format_in_bdt($weight->first_weight) }}</td>
                                <td>{{ date($dateFormat.' '.$timeFormat, strtotime($weight->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __("2nd") }}</strong> {{ __("Weight") }}</td>
                                <td>{{ App\Utils\Converter::format_in_bdt($weight->second_weight)}}</td>
                                <td>{{ $weight->second_weight > 0 ? date($dateFormat.' '.$timeFormat, strtotime($weight->updated_at)) : '' }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>@lang('menu.net_weight')</th>
                                <th>{{  App\Utils\Converter::format_in_bdt($weight->second_weight - $weight->first_weight) }}</th>
                                <td>{{ $weight->second_weight > 0 ? date($dateFormat.' '.$timeFormat, strtotime($weight->updated_at)) : '' }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-2"></div>
        </div>

        <br><br>

        <div class="row">
            <div class="col-3 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600; font-size:12px!important">{{ __("Driver Singature") }}</p>
            </div>

            <div class="col-3 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600; font-size:12px!important;">{{ __("Security Singature") }}</p>
            </div>

            <div class="col-3 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600; font-size:12px!important;">{{ __("Operator Singature") }}</p>
            </div>

            <div class="col-3 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600; font-size:12px!important;">{{ __("Authorized Singature") }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4 text-start">
        <small>@lang('menu.print_date') : {{ date($dateFormat) }}</small>
    </div>

    <div class="col-4 text-center">
        @if (config('company.print_on_sale'))
            <small>@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
        @endif
    </div>

    <div class="col-4 text-end">
        <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
    </div>
</div>
