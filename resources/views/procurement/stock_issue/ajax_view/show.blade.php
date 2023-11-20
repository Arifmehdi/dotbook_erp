@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-full-display">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">
                    @lang('menu.stock_issue_details') (@lang('menu.voucher_no') : <strong>{{ $stockIssue->voucher_no }}</strong>)
                 </h5>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>@lang('menu.issue_date') :</strong> {{ $stockIssue->date }}</li>
                            <li style="font-size:11px!important;"><strong> @lang('menu.voucher_no') :</strong> {{ $stockIssue->voucher_no}}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.event')  :</strong> {{ $stockIssue->event ? $stockIssue->event->name : '' }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>@lang('menu.receiver_dep'). :</strong> {{ $stockIssue->department ? $stockIssue->department->name : '' }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.created_by')  :</strong>
                                {{ $stockIssue->createdBy ? $stockIssue->createdBy->prefix.' '.$stockIssue->createdBy->name.' '.$stockIssue->createdBy->last_name : '' }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.business')  :</strong>
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}
                            </li>

                            <li style="font-size:11px!important;"><strong>@lang('menu.address')  :</strong>
                                {{ json_decode($generalSettings->business, true)['address'] }}
                            </li>

                            <li style="font-size:11px!important;"><strong>@lang('menu.phone')  :</strong>
                                {{ json_decode($generalSettings->business, true)['phone'] }}
                            </li>
                        </ul>
                    </div>
                </div>
                 <br>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="" class="table modal-table table-sm table-striped">
                                 <thead>
                                    <tr>
                                        <th style="font-size:11px!important;">@lang('menu.sl')</th>
                                        <th style="font-size:11px!important;">@lang('menu.item')</th>
                                        <th style="font-size:11px!important;">@lang('menu.stock_location')</th>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.quantity')</th>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_cost_inc_tax')</th>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                                    </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                    @foreach ($stockIssue->issueProducts as $issueProduct)
                                        <tr>
                                            @php
                                                $variant = $issueProduct->variant ? ' ('.$issueProduct->variant->variant_name.')' : '';
                                            @endphp
                                            <td style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                            <td style="font-size:11px!important;">
                                                {{ $issueProduct->product->name.' '.$variant }}
                                                <small>{!! $issueProduct->description ? '<br/>'.$issueProduct->description : '' !!}</small>
                                            </td>

                                            <td style="font-size:11px!important;">
                                                @if ($issueProduct->warehouse)
                                                    {{ $issueProduct?->warehouse->warehouse_name.'/'.$issueProduct?->warehouse->warehouse_code }}
                                                @else
                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                @endif
                                            </td>

                                            @php
                                                $baseUnitMultiplier = $issueProduct?->issueUnit?->base_unit_multiplier ? $issueProduct?->issueUnit?->base_unit_multiplier : 1;
                                                $issuedQty = $issueProduct->quantity / $baseUnitMultiplier;
                                            @endphp

                                            <td class="text-end" style="font-size:11px!important;">
                                                @if ($issueProduct?->issueUnit?->baseUnit)
                                                    @php
                                                        $isMultiplierUnitExists = 1;
                                                    @endphp

                                                    (<strong>{{ App\Utils\Converter::format_in_bdt($issueProduct->quantity) }}/{{ $issueProduct?->issueUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($issuedQty) }}/{{ $issueProduct?->issueUnit?->code_name }}
                                                @else

                                                    {{ App\Utils\Converter::format_in_bdt($issuedQty) }}/{{ $issueProduct?->issueUnit?->code_name }}
                                                @endif
                                            </td>

                                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($issueProduct->unit_cost_inc_tax * $baseUnitMultiplier) }}</td>
                                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($issueProduct->subtotal) }} </td>
                                        </tr>
                                    @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>

                 <hr>

                 <div class="row">
                     <div class="col-md-12">
                         <div class="details_area">
                             <p style="font-size:11px!important;"><b>@lang('menu.stock_issue_note')</b> : </p>
                             <p style="font-size:11px!important;">{{ $stockIssue->note }}</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                @if (auth()->user()->can('stock_issue_update'))

                    <a href="{{ route('stock.issue.edit', [$stockIssue->id]) }}" class="btn btn-sm btn-secondary m-0 me-2">@lang('menu.edit')</a>
                @endif
                <button type="submit" class="btn btn-sm btn-success m-0 me-2" id="print_modal_details_btn">@lang('menu.print')</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0">@lang('menu.close')</button>
             </div>
         </div>
     </div>
 </div>
 <!-- Details Modal End-->

<style>
    div#footer {position:fixed;bottom:30px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px; margin-right: 10px;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>

 <!-- Purchase print templete-->
<div class="print_details d-none">
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

        <div class="row mt-3">
            <div class="col-12 text-center">
                <h6 style="text-transform: uppercase;"><strong>@lang('menu.stock_issue')</strong></h6>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.issue_date') : </strong><b>{{ $stockIssue->date }}</b></li>
                    <li style="font-size:11px!important;"><strong> @lang('menu.voucher_no') : </strong><b>{{ $stockIssue->voucher_no }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.event') : </strong><b>{{ $stockIssue?->event?->name }}</b></li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.receiver_dept') : </strong> <b>{{ $stockIssue->department ? $stockIssue->department->name : '' }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.created_by') : </strong>
                        <b>{{ $stockIssue->createdBy ? $stockIssue->createdBy->prefix.' '.$stockIssue->createdBy->name.' '.$stockIssue->createdBy->last_name : '' }}</b>
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table mt-2 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th style="font-size:11px!important;">@lang('menu.sl')</th>
                        <th style="font-size:11px!important;">@lang('menu.item')</th>
                        <th style="font-size:11px!important;">@lang('menu.stock_location')</th>
                        <th style="font-size:11px!important;">@lang('menu.quantity')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_cost_inc_tax')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @foreach ($stockIssue->issueProducts as $issueProduct)
                        <tr>
                            @php
                                $variant = $issueProduct->variant ? ' ('.$issueProduct->variant->variant_name.')' : '';
                            @endphp

                            <td style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                            <td style="font-size:11px!important;">
                                {{ $issueProduct->product->name.' '.$variant }}
                                <small>{!! $issueProduct->description ? '<br/>'.$issueProduct->description : '' !!}</small>
                            </td>

                            <td style="font-size:11px!important;">
                                @if ($issueProduct->warehouse)
                                    {{ $issueProduct?->warehouse->warehouse_name.'/'.$issueProduct?->warehouse->warehouse_code }}
                                @else
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                @endif
                            </td>

                            @php
                                $baseUnitMultiplier = $issueProduct?->issueUnit?->base_unit_multiplier ? $issueProduct?->issueUnit?->base_unit_multiplier : 1;
                                $issuedQty = $issueProduct->quantity / $baseUnitMultiplier;
                            @endphp

                            <td style="font-size:11px!important;">
                                @if ($issueProduct?->issueUnit?->baseUnit)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($issueProduct->quantity) }}/{{ $issueProduct?->issueUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($issuedQty) }}/{{ $issueProduct?->issueUnit?->code_name }}
                                @else

                                    {{ App\Utils\Converter::format_in_bdt($issuedQty) }}/{{ $issueProduct?->issueUnit?->code_name }}
                                @endif
                            </td>

                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($issueProduct->unit_cost_inc_tax * $baseUnitMultiplier) }}</td>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($issueProduct->subtotal) }} </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end text-black" style="font-size:11px!important;">@lang('menu.net_total_value') {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                        <td class="text-end" style="font-size:11px!important;">
                            {{ App\Utils\Converter::format_in_bdt($stockIssue->net_total_value) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row">
            <div class="col-12">
                <p style="font-size:11px!important;"><strong>@lang('menu.stock_issue_note') :</strong> {{ $stockIssue->note }}</p>
            </div>
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
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($stockIssue->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p>{{ $stockIssue->voucher_no }}</p>
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
                    <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Purchase print templete end-->
