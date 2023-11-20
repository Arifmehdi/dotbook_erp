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

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 15px;margin-right: 15px;}
    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
 <!-- Requisition print templete-->
<div class="purchase_print_template">
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
                    <strong>@lang('menu.email') : </strong><b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                    <strong>@lang('menu.phone') : </strong><b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h6 style="text-transform: uppercase;"><strong>@lang('menu.purchase_requisition')</strong></h6>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong> <b>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($requisition->date)) }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.requisition_no') : </strong> <b>{{ $requisition->requisition_no }}</b></li>

                    <li style="font-size:11px!important;">
                        <strong>@lang('menu.department') : </strong>
                        <b>{{ $requisition?->department?->name }}</b>
                    </li>

                    <li style="font-size:11px!important;">
                        <strong>@lang('menu.requested_by') : </strong>
                        <b>{{ $requisition?->requester?->name }}</b>
                    </li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;">
                        <strong>@lang('menu.approval_status') : </strong>
                        @if ($requisition->is_approved == 1)

                            <b>@lang('menu.approved')</b>
                        @elseif($requisition->is_approved == 0)

                            <b>@lang('menu.pending')</b>
                        @endif
                        </li>

                    <li style="font-size:11px!important;">
                        <strong>@lang('menu.created_by') : </strong>
                        <b>{{ $requisition?->createdBy?->prefix.' '.$requisition?->createdBy?->name.' '.$requisition?->createdBy?->last_name }}</b>
                    </li>

                    <li style="font-size:11px!important;">
                        <strong>@lang('menu.approved_by') : </strong>
                        <b>{{ $requisition?->approvedBy?->prefix.' '.$requisition?->approvedBy?->name.' '.$requisition?->approvedBy?->last_name }}</b>
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-3 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th style="font-size:11px!important;">@lang('menu.serial')</th>
                        <th style="font-size:11px!important;">@lang('menu.item')</th>
                        <th style="font-size:11px!important;">@lang('menu.quantity')</th>
                        <th style="font-size:11px!important;">@lang('menu.last_purchase_price')</th>
                        <th style="font-size:11px!important;">@lang('menu.current_stock')</th>
                        <th style="font-size:11px!important;">@lang('menu.purpose')</th>
                        <th style="font-size:11px!important;">@lang('menu.pr_type')</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @php
                        $isMultiplierUnitExists = 1;
                        $totalQty = 0;
                    @endphp
                    @foreach ($requisition->requisitionProducts as $reqProduct)
                        <tr>
                            <td style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                            @php
                                $variant = $reqProduct->variant ? ' ('.$reqProduct->variant->variant_name.')' : '';
                            @endphp

                            <td style="font-size:11px!important;"><b>{{ $reqProduct->product->name .' '. $variant }}</b></td>
                            @php
                                $baseUnitMultiplier = $reqProduct?->requisitionUnit?->base_unit_multiplier ? $reqProduct?->requisitionUnit?->base_unit_multiplier : 1;
                                $requestedQty = $reqProduct->quantity / $baseUnitMultiplier;
                                $totalQty += $requestedQty;
                            @endphp
                            <td style="font-size:11px!important;">
                                @if ($reqProduct?->requisitionUnit?->baseUnit)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($reqProduct->quantity) }}/{{ $reqProduct?->requisitionUnit?->baseUnit->code_name }}</strong>)=<b>{{ App\Utils\Converter::format_in_bdt($requestedQty) }}/{{ $reqProduct?->requisitionUnit?->code_name }}</b>
                                @else

                                    <b>{{ App\Utils\Converter::format_in_bdt($requestedQty) }}/{{ $reqProduct?->requisitionUnit?->code_name }}</b>
                                @endif
                            </td>
                            <td style="font-size:11px!important;"><b>{{ App\Utils\Converter::format_in_bdt($reqProduct->last_purchase_price) }} @lang('menu.per'): {{ $reqProduct?->product?->unit?->code_name }}</b></td>
                            <td style="font-size:11px!important;"><b>{{ App\Utils\Converter::format_in_bdt($reqProduct->current_stock). '/' . $reqProduct?->product?->unit?->code_name }}</b></td>
                            <td style="font-size:11px!important;"><b>{{ $reqProduct->purpose }}</b></td>
                            <td style="font-size:11px!important;"><b>{{ $reqProduct->pr_type == 1 ? __('menu.normal') : __('menu.emergency') }}</b></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-6">
                <p style="font-size:11px!important;"><strong>@lang('menu.requisition_note') :</strong> </p>
                <p style="font-size:11px!important;"><b>{{ $requisition->note }}</b></p><br>
            </div>

            <div class="col-6">
                <table class="table report-table table-sm table-bordered print_table">
                    <thead>
                        <tr>
                            <th colspan="11" class="text-end" style="font-size:11px!important;">@lang('menu.total_item') </th>
                            <td colspan="2" class="text-end" style="font-size:11px!important;"><b>{{ $requisition->total_item }}</b></td>
                        </tr>

                        <tr>
                            <th colspan="11" class="text-end" style="font-size:11px!important;">@lang('menu.total_quantity') :</th>
                            <td colspan="2" class="text-end" style="font-size:11px!important;"><b>{{ App\Utils\Converter::format_in_bdt($totalQty) }}</b></td>
                        </tr>
                    </thead>
                </table>
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
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($requisition->requisition_no, $generator::TYPE_CODE_128)) }}">
                <p>{{ $requisition->requisition_no }}</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small>@lang('menu.print_date')  {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_sale'))
                        <small class="d-block">@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd') .</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small>@lang('menu.print_time') {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Purchase print templete end-->
