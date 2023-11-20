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

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
 <!-- Purchase print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="heading" style="border-bottom: 1px solid black; padding-botton: 3px;">
                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                                <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                            @else

                                <p style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</p>
                            @endif

                            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
                            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                            <p><strong>@lang('menu.phone')  </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="purchase_and_deal_info mt-3">
                <div class="row">
                    <div class="col-12 text-center">
                        <h4 style="text-transform:uppercase;"><strong>@lang('menu.challan_by_weight')</strong></h4>
                        <h6><strong>{{ $purchaseByScale->status == 1 ? 'COMPLETED' : 'RUNNING' }}</strong></h6>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.supplier') : </strong>{{ $purchaseByScale?->supplier?->name }}</li>
                            <li><strong>@lang('menu.address') : </strong>{{ $purchaseByScale?->supplier?->address }}</li>
                            <li><strong>@lang('menu.phone') : </strong>{{ $purchaseByScale?->supplier?->phone }}</li>
                        </ul>
                    </div>

                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.weight') @lang('menu.voucher_no'). : </strong> {{ $purchaseByScale->voucher_no }}</li>
                            <li><strong>@lang('menu.date') </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchaseByScale->date)) }}</li>
                            <li><strong>@lang('menu.challan_no'). : </strong> {{ $purchaseByScale->challan_no }}</li>
                            <li><strong>@lang('menu.challan_date') : </strong> {{ $purchaseByScale->challan_date }}</li>
                        </ul>
                    </div>

                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.vehicle_no') : </strong> {{ $purchaseByScale->vehicle_number }}</li>
                            <li><strong>@lang('menu.driver_name') : </strong> {{ $purchaseByScale->driver_name }}</li>
                            <li><strong>@lang('menu.driver_phone') : </strong> {{ $purchaseByScale->driver_phone }}</li>
                            <li><strong>@lang('menu.created_by') : </strong> {{  $purchaseByScale?->createdBy?->prefix.' '.$purchaseByScale?->createdBy?->name.' '.$purchaseByScale?->createdBy?->last_name }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="purchase_product_table mt-2">
                <table class="table report-table table-sm table-bordered print_table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('menu.item_name')</th>
                            <th scope="col">@lang('menu.item_weight_by_scale')</th>
                            <th scope="col">@lang('menu.wastage')</th>
                            <th scope="col">@lang('menu.item_net_weight')</th>
                            <th scope="col">@lang('menu.remark')</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @php
                            $totalItemWeightByScala = 0;
                            $totalIWastage = 0;
                            $totalNetWeight = 0;
                        @endphp
                        @foreach ($purchaseByScale->weightsByProduct as $weight)
                            <tr>
                                <td>{{ $weight?->product?->name .($weight->variant ? ' - ' . $weight->variant->variant_name : '' ) }}</td>

                                <td>{{ App\Utils\Converter::format_in_bdt($weight->differ_weight) }}/{{$weight?->product?->unit?->name}}</td>
                                @php $totalItemWeightByScala += $weight->differ_weight; @endphp
                                <td>{{ App\Utils\Converter::format_in_bdt($weight->wast) }}/{{$weight?->product?->unit?->name}}</td>
                                @php $totalIWastage += $weight->wast; @endphp
                                @php
                                    $net_weight = $weight->differ_weight - $weight->wast;
                                @endphp
                                <td>{{ App\Utils\Converter::format_in_bdt($net_weight) }}/{{$weight?->product?->unit?->name}}</td>
                                @php $totalNetWeight += $net_weight; @endphp
                                <td>{{ $weight->remarks }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-end">@lang('menu.total') :</th>
                            <th>{{ App\Utils\Converter::format_in_bdt($totalItemWeightByScala) }}</th>
                            <th>{{ App\Utils\Converter::format_in_bdt($totalIWastage) }}</th>
                            <th>{{ App\Utils\Converter::format_in_bdt($totalNetWeight) }}</th>
                            <th>---</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-12">
                    <p><strong>@lang('menu.net_weight_by_scale') (@lang('menu.first_weight') - @lang('menu.last_weight')) = </strong> {{ App\Utils\Converter::format_in_bdt($purchaseByScale->net_weight) }} <strong>Kg</strong></p>
                    <p><strong>@lang('menu.net_weight_without_wastage') = </strong> {{ App\Utils\Converter::format_in_bdt($totalNetWeight) }} <strong>Kg</strong></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchaseByScale->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $purchaseByScale->voucher_no }}</p>
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
