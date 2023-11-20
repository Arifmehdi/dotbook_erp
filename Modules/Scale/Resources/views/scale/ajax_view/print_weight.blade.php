@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = json_decode($generalSettings->business, true)['date_format'];
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
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
</style>
 <!-- Purchase print templete-->
    <div class="purchase_print_template">
        <div class="details_area">

            {{-- <div class="heading_area">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="heading" style="border-bottom: 1px solid black; padding-botton: 3px;">

                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                                <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                            @else

                                <p style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</p>
                            @endif

                            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}:</strong></p>

                            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>

                            <p><strong>@lang('menu.phone')  :</strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="purchase_and_deal_info mt-2">
                <div class="row pb-1">
                    <div class="col-12 text-center">
                        <h4 style="padding-bottom:5px;border-bottom: 1px solid black; font-weight: 600; margin-bottom:10pt;"><strong> DIGITAL WEIGHT SCLAE </strong></h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        <h4><strong>@lang('menu.weight_details'):</strong></h4>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li style="font-size:12px!important;"><strong>@lang('menu.client') : </strong>{{ $weightScaleData?->weightClient?->name }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.address')  : </strong>{{ $weightScaleData?->weightClient?->address }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.phone') : </strong> {{ $weightScaleData?->weightClient?->phone }}</li>
                            {{-- <li><strong>Company :</strong> {{ $weightScaleData->weightClient?->company_name }}</li> --}}
                            <li style="font-size:12px!important;"><strong>@lang('menu.item_name') : </strong> {{ $weightScaleData?->product?->name }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.quantity') : </strong> {{ $weightScaleData->quantity }}</li>
                        </ul>
                    </div>

                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li style="font-size:12px!important;"><strong>@lang('menu.weight_id') : </strong> {{ $weightScaleData->weight_id }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($weightScaleData->date)) }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.challan_no') : </strong> {{ $weightScaleData->challan_no }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.challan_date') :</strong> {{ $weightScaleData->challan_date }}</li>

                        </ul>
                    </div>

                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li style="font-size:12px!important;"><strong>@lang('menu.vehicle_no') : </strong> {{ $weightScaleData->vehicle_number }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.driver_name') : </strong> {{ $weightScaleData->driver_name }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.driver_phone') : </strong> {{ $weightScaleData->driver_phone }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.serial_no') : </strong> {{ $weightScaleData->serial_no }}</li>
                            <li style="font-size:12px!important;"><strong>@lang('menu.scale_operator') : </strong> {{ $weightScaleData->createdBy ? $weightScaleData->createdBy->prefix.' '.$weightScaleData->createdBy->name.' '.$weightScaleData->createdBy->last_name : 'N/A' }}</li>
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

                        $netWeight = $weightScaleData->gross_weight - $weightScaleData->tare_weight;
                        $netWeightDateAndTime = '';
                    @endphp

                    @foreach ($weightScaleData->weightDetails as $details)
                        <tr>
                            <td> <strong> {{ $details->weight_type == 1? __('menu.gross_weight') : __('menu.tare_weight') }} :</strong> </td>
                            <td><strong>= {{ App\Utils\Converter::format_in_bdt($details->weight) }} :</strong> Kg </td>
                            <td><strong>@lang('menu.date_and_time') </strong> {{ date($dateFormat.' '.$timeFormat, strtotime($details->created_at)) }}</td>
                        </tr>
                        @php
                            $netWeightDateAndTime = $details->created_at;
                        @endphp
                    @endforeach

                    {{-- <tr>
                        <td> <strong> Weight (Tare Weight) :</strong> </td>
                        <td><strong>= {{ App\Utils\Converter::format_in_bdt($weightScaleData->tare_weight) }} :</strong> Kg </td>
                        <td><strong>Date & Time </strong> {{ date(' h:i:s A', strtotime($weightScaleData->created_at)) }}</td>
                    </tr>

                    <tr>
                        <td> <strong> Weight (Gross Weight) :</strong> </td>
                        <td><strong>= {{ App\Utils\Converter::format_in_bdt($weightScaleData->gross_weight) }}:</strong> Kg </td>
                        <td><strong>Date & Time </strong> {{ date(' h:i:s A', strtotime($weightScaleData->created_at)) }}</td>
                    </tr> --}}

                    <tr>
                        <td><strong>@lang('menu.net_weight') : </strong></td>
                        <td>
                            @if($weightScaleData->gross_weight > 0 && $weightScaleData->tare_weight > 0)

                                <strong>= {{ App\Utils\Converter::format_in_bdt($netWeight) }} : </strong> Kg
                            @else

                                <strong>= {{ App\Utils\Converter::format_in_bdt(0) }} : </strong> Kg
                            @endif
                        </td>

                        @if($weightScaleData->gross_weight > 0 && $weightScaleData->tare_weight > 0)
                            <td><strong>@lang('menu.date_and_time') </strong> {{ date($dateFormat.' '.$timeFormat, strtotime($netWeightDateAndTime)) }}</td>
                        @endif
                    </tr>
                </table>
            </div>

            <br><br>

            <div class="row">
                <div class="col-4 text-start">
                    <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">SECURITY'S SIGNATURE</p>
                </div>

                <div class="col-4 text-center">
                    <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">OPERATOR'S SIGNATURE</p>
                </div>

                <div class="col-4 text-end">
                    <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorized_signature')</p>
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
        </div>
    </div>
 <!-- Purchase print templete end-->
