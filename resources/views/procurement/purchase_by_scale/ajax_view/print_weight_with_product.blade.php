@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
 <!-- Details Modal End-->
 <style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 8px;margin-right: 8px;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
 <!-- Purchase print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="heading_area">
                {{-- <div class="row">
                    <div class="col-12 text-center">
                        <div class="heading" style="border-bottom: 1px solid black; padding-botton: 3px;">
                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                                <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                            @else

                                <p style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</p>
                            @endif

                            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>

                            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>

                            <p><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="purchase_and_deal_info mt-1">
                <div class="row">
                    <div class="col-12 text-center">
                        <h4 style="text-transform: uppercase; border-bottom: 2px solid black;"><strong>@lang('menu.weight_details')</strong></h4>
                        <h6 style="text-transform: uppercase;" class="mt-2"><strong>@lang('menu.for_purchase_by_scale')</strong></h6>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li style="font-size:12px!important;"><strong>@lang('menu.supplier') : </strong>{{ $purchaseByScale?->supplier?->name }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.address') : </strong>{{ $purchaseByScale?->supplier?->address }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.phone') : </strong> {{ $purchaseByScale?->supplier?->phone }}</li>
                        </ul>
                    </div>

                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li style="font-size:12px!important;"><strong>@lang('menu.weight') @lang('menu.voucher_no'). : </strong> {{ $purchaseByScale->voucher_no }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchaseByScale->date)) }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.challan_no'). : </strong> {{ $purchaseByScale->challan_no }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.challan_date') : </strong> {{ $purchaseByScale->challan_date }}</li>
                        </ul>
                    </div>

                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li style="font-size:12px!important;"><strong>@lang('menu.vehicle_no') : </strong> {{ $purchaseByScale->vehicle_number }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.driver_name') : </strong> {{ $purchaseByScale->driver_name }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.driver_phone') : </strong> {{ $purchaseByScale->driver_phone }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="purchase_product_table mt-2">
                <table class="table report-table table-sm table-bordered print_table">
                    @php
                        $serialArr = [
                            '1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th', '13th', '14th', '15th', '16th', '17th', '18th', '19th', '20th', '21th', '22th', '23th', '24th', '25th', '26th', '27th', '28th', '29th', '30th',
                        ];
                    @endphp

                    @php
                        $previousWeight = 0;
                        $previousIndex = 0;
                        $lastWeight = count($purchaseByScale->weights) - 1;
                        $totalIWastage = 0;
                    @endphp

                    @foreach ($purchaseByScale->weights as $weight)
                        @if ($loop->index == 0)

                            <tr>
                                <td>
                                    <strong>{{ $serialArr[$loop->index] }} @lang('menu.weight') (@lang('menu.gross_weight')) </strong>
                                </td>
                                <td colspan="4">= <strong>{{ App\Utils\Converter::format_in_bdt($weight->scale_weight) }}</strong> Kg </td>
                            </tr>

                            @php
                                $previousWeight = $weight->scale_weight;
                                $previousIndex = $loop->index;
                            @endphp
                        @else

                            <tr>
                                <td><strong>{{ $serialArr[$loop->index] }} Weight {{ ($loop->index) == $lastWeight ? '('. __('menu.last_weight') .')' : ''  }} </strong></td>
                                <td colspan="4"><strong>= {{ App\Utils\Converter::format_in_bdt($weight->scale_weight) }} </strong> Kg </td>

                                <tr>
                                    <td>
                                        @php
                                            $differWeight = $previousWeight - $weight->scale_weight;
                                        @endphp
                                        <p>Differ From <strong>{{ $serialArr[$previousIndex] }}</strong> Weight : {{ App\Utils\Converter::format_in_bdt($differWeight) }}/Kg</p>
                                    </td>

                                    <td><p><strong>@lang('menu.item') :</strong> {{ $weight->product ? $weight->product->name .($weight->variant ? '-'. $weight->variant->variant_name : '') : 'N/A' }} </p></td>

                                    <td>
                                        <p><strong>@lang('menu.wastage') : </strong> {{ $weight->wast ? $weight->wast : 0 }}/Kg</p>
                                        @php $totalIWastage += $weight->wast; @endphp
                                    </td>

                                    <td>
                                        @php
                                            $netWeight = $differWeight -  $weight->wast;
                                        @endphp
                                        <p><strong>@lang('menu.net_weight') : </strong> {{ App\Utils\Converter::format_in_bdt($netWeight) }}/Kg</p>
                                    </td>

                                    <td>
                                        <p><strong>@lang('menu.remark') :</strong> {{ $weight->remarks }} </p>
                                    </td>
                                </tr>
                            </tr>

                            @php
                                $previousWeight = $weight->scale_weight;
                                $previousIndex = $loop->index;
                            @endphp
                        @endif
                    @endforeach
                </table>
            </div>

            <div class="row">
                <div class="col-12">
                    <p style="font-size:12px!important;"><strong>@lang('menu.net_weight_by_scale') (@lang('menu.first_weight') - @lang('menu.last_weight')) = </strong> {{ App\Utils\Converter::format_in_bdt($purchaseByScale->net_weight) }} <strong>Kg</strong></p>
                    @php
                        $netWeightWithWastage = $purchaseByScale->net_weight - $totalIWastage;
                    @endphp
                    <p style="font-size:12px!important;"><strong>@lang('menu.net_weight_without_wastage') = </strong> {{ App\Utils\Converter::format_in_bdt($netWeightWithWastage) }} <strong>Kg</strong></p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_sale'))
                            <small class="d-block">@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution')</strong></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <!-- Purchase print templete end-->
