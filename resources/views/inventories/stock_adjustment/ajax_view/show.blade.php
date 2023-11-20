<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog col-80-modal">
      <div class="modal-content" >
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">@lang('menu.stock_adjustment_details') | (@lang('menu.voucher_no') : <strong>{{ $adjustment->voucher_no }}</strong>)</h5>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 text-left">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($adjustment->date)) }}</li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.voucher_no') : </strong> {{ $adjustment->voucher_no }}</li>
                    </ul>
                </div>

                <div class="col-md-6 text-left">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>@lang('menu.type') : </strong>
                            {!! $adjustment->type == 1 ? '<span class="badge bg-primary">Normal</span>' : '<span class="badge bg-danger">Abnormal</span>' !!}
                        </li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.created_by') : </strong>
                            {{  $adjustment?->createdBy?->prefix.' '.$adjustment?->createdBy?->name.' '.$adjustment?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div><br>

            <div class="row">
                <div class="table-responsive">
                    <table id="" class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-primary text-white text-start">
                                <th class="text-start" style="font-size:11px!important;">@lang('menu.sl')</th>
                                <th class="text-start" style="font-size:11px!important;">@lang('menu.item')</th>
                                <th class="text-start" style="font-size:11px!important;">@lang('menu.stock_location')</th>
                                <th class="text-start" style="font-size:11px!important;">@lang('menu.quantity')</th>
                                <th class="text-start" style="font-size:11px!important;">@lang('menu.unit_cost_inc_tax')</th>
                                <th class="text-start" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                            </tr>
                        </thead>
                        <tbody class="adjustment_product_list">
                            @foreach ($adjustment->adjustmentProducts as $adjustmentProduct)
                                <tr>
                                    <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                    @php
                                        $variant = $adjustmentProduct->variant ? ' ('.$adjustmentProduct->variant->variant_name.')' : '';
                                    @endphp
                                    <td class="text-start" style="font-size:11px!important;">{{ $adjustmentProduct?->product?->name.$variant }}</td>
                                    <td class="text-start" style="font-size:11px!important;">
                                        @if ($adjustmentProduct->warehouse)

                                            {{ $adjustmentProduct->warehouse->warehouse_name.'/'.$adjustmentProduct->warehouse->warehouse_code }}
                                        @else

                                            {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                        @endif
                                    </td>
                                    <td class="text-start" style="font-size:11px!important;">
                                        @php
                                            $baseUnitMultiplier = $adjustmentProduct?->stockAdjustmentUnit?->base_unit_multiplier ? $adjustmentProduct?->stockAdjustmentUnit?->base_unit_multiplier : 1;
                                            $adjustedQty = $adjustmentProduct->quantity / $baseUnitMultiplier;
                                        @endphp

                                        @if ($adjustmentProduct?->stockAdjustmentUnit?->baseUnit)
                                            @php
                                                $isMultiplierUnitExists = 1;
                                            @endphp

                                            (<strong>{{ App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity) }}/{{ $adjustmentProduct?->stockAdjustmentUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($adjustedQty) }}/{{ $adjustmentProduct?->stockAdjustmentUnit?->code_name }}
                                        @else

                                            {{ App\Utils\Converter::format_in_bdt($adjustedQty) }}/{{ $adjustmentProduct?->stockAdjustmentUnit?->code_name }}
                                        @endif
                                    </td>
                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($adjustmentProduct->unit_cost_inc_tax * $baseUnitMultiplier) }}
                                    </td>
                                    <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal) }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <p class="fw-bold" style="font-size:11px!important;">@lang('menu.recovered_amount_against_reference')</p>
                    <div class="payment_table">
                        <div class="table-responsive">
                            <table class="table modal-table table-striped table-sm">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th style="font-size:11px!important;">@lang('menu.date')</th>
                                        <th style="font-size:11px!important;">@lang('menu.voucher_no')</th>
                                        <th style="font-size:11px!important;">@lang('menu.type')</th>
                                        <th style="font-size:11px!important;">@lang('menu.account')</th>
                                        <th style="font-size:11px!important;">@lang('menu.amount')</th>
                                        <th class="action_hideable" style="font-size:11px!important;">@lang('menu.action')</th>
                                    </tr>
                                </thead>
                                <tbody id="p_details_payment_list">
                                    @php
                                        $totalReceivedAmount = 0;
                                    @endphp
                                    @if (count($adjustment->references) > 0)

                                       @foreach ($adjustment->references as $reference)
                                           <tr>
                                               <td style="font-size:11px!important;">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($reference->paymentDescription->payment->date)) }}</td>
                                               <td style="font-size:11px!important;">{{ $reference->paymentDescription->payment->voucher_no }}</td>
                                               <td style="font-size:11px!important;">{{ $reference?->paymentDescription?->paymentMethod->name }}</td>

                                                <td style="font-size:11px!important;">{{ $reference?->paymentDescription?->account?->name }}</td>

                                                <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($reference?->amount) }}</td>
                                                @php
                                                    $totalReceivedAmount += $reference?->amount ? $reference?->amount : 0;
                                                @endphp

                                                <td class="action_hideable" style="font-size:11px!important;">
                                                    @if ($reference->paymentDescription->payment->payment_type == 1)

                                                        <a href="{{ route('vouchers.receipts.show', [$reference->paymentDescription->payment->id]) }}" id="extra_details_btn" class="btn-sm">@lang('menu.details')</a>
                                                    @else
                                                        <a href="{{ route('vouchers.payments.show', [$reference->paymentDescription->payment->id]) }}" id="extra_details_btn" class="btn-sm">@lang('menu.details')</a>
                                                    @endif
                                               </td>
                                           </tr>
                                       @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center" style="font-size:11px!important;">@lang('menu.no_data_found')</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end" style="font-size:11px!important;">Total Received Against Reference:</th>
                                        <th style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($totalReceivedAmount) }}</th>
                                        <th style="font-size:11px!important;"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table modal-table table-sm">
                            <tr>
                                <th class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($adjustment->net_total_amount) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end" style="font-size:11px!important;">@lang('menu.recovered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($adjustment->recovered_amount) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div><br>

            <hr class="p-0 m-0">
            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>@lang('menu.reason') : </h6>
                        <p class="reason">{{ $adjustment->reason }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0 me-2">@lang('menu.close')</button>
            <button type="submit" class="btn btn-sm btn-success m-0" id="print_modal_details_btn">@lang('menu.print')</button>
        </div>
      </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Adjustment print templete-->
@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = json_decode($generalSettings->business, true)['date_format'];
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<style>
    div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px; margin-right: 10px;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
<div class="print_details d-none">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black;">
            <div class="col-4">
                @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                    <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                @else

                    <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                @endif
            </div>

            <div class="col-8">
                <div class="heading text-end">
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
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <div class="middle_header_text text-center">
                    <h6 class="text-uppercase fw-bold">@lang('menu.stock_adjustment_voucher')</h6>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($adjustment->date)) }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.voucher_no') : </strong>{{ $adjustment->voucher_no }}</li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled float-right">
                    <li style="font-size:11px!important;">
                        <strong>@lang('menu.type') : </strong>
                        {{ $adjustment->type == 1 ? 'Normal' : 'Abnormal' }}
                    </li>
                    <li style="font-size:11px!important;">
                        <strong>@lang('menu.created_by') : </strong>
                        {{ $adjustment?->createdBy?->prefix.' '.$adjustment?->createdBy?->name.' '.$adjustment?->createdBy?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <tr>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.sl')</th>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.item')</th>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.stock_location')</th>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.quantity')</th>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.unit_cost_inc_tax')</th>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="adjustment_print_product_list">
                    @foreach ($adjustment->adjustmentProducts as $adjustmentProduct)
                        <tr>
                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                            @php
                                $variant = $adjustmentProduct->variant ? ' ('.$adjustmentProduct->variant->variant_name.')' : '';
                            @endphp
                            <td class="text-start" style="font-size:11px!important;">{{ $adjustmentProduct->product->name.$variant }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                @if ($adjustmentProduct->warehouse)

                                    {{ $adjustmentProduct->warehouse->warehouse_name.'/'.$adjustmentProduct->warehouse->warehouse_code }}
                                @else

                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                @endif
                            </td>
                            <td class="text-start" style="font-size:11px!important;">
                                @php
                                    $baseUnitMultiplier = $adjustmentProduct?->stockAdjustmentUnit?->base_unit_multiplier ? $adjustmentProduct?->stockAdjustmentUnit?->base_unit_multiplier : 1;
                                    $adjustedQty = $adjustmentProduct->quantity / $baseUnitMultiplier;
                                @endphp

                                @if ($adjustmentProduct?->stockAdjustmentUnit?->baseUnit)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity) }}/{{ $adjustmentProduct?->stockAdjustmentUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($adjustedQty) }}/{{ $adjustmentProduct?->stockAdjustmentUnit?->code_name }}
                                @else

                                    {{ App\Utils\Converter::format_in_bdt($adjustedQty) }}/{{ $adjustmentProduct?->stockAdjustmentUnit?->code_name }}
                                @endif
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($adjustmentProduct->unit_cost_inc_tax * $baseUnitMultiplier) }} </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal) }} </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="border-bottom:1px solid white!important;">
                        <th colspan="5" class="text-end text-black" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                        <td class="text-start" style="font-size:11px!important;">
                            {{ App\Utils\Converter::format_in_bdt($adjustment->net_total_amount) }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end text-black" style="font-size:11px!important;">@lang('menu.recovered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                        <td class="text-start" style="font-size:11px!important;">
                            {{ App\Utils\Converter::format_in_bdt($adjustment->recovered_amount) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br><br>

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

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($adjustment->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p>{{ $adjustment->voucher_no }}</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small>@lang('menu.print_date') :
                        {{ date(json_decode($generalSettings->business, true)['date_format']) }}
                    </small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_sale'))
                        <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Adjustment print templete end-->
