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

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}

    .amounts_table thead tr th {
        line-height: 10px!important;
    }

    .amounts_table thead tr td {
        line-height: 10px!important;
    }

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
 <!-- Purchase print templete-->
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
                    <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                    <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h6 style="text-transform: uppercase;"><strong>@lang('menu.purchase_invoice')</strong></h6>
                @if ($purchase->purchase_by_scale_id)

                    <p style="text-transform: uppercase;"><strong>@lang('menu.purchase_by_weight_voucher')</strong></p>
                @elseif ($purchase->receive_stock_id)

                    <p style="text-transform: uppercase;"><strong>@lang('menu.purchase_by_rs_voucher')</strong></p>
                @elseif ($purchase->requisition_id)

                    <p style="text-transform: uppercase;"><strong>@lang('menu.purchase_by_requisition')</strong></p>
                @else

                    <p style="text-transform: uppercase;"><strong>@lang('menu.general_purchase')</strong></p>
                @endif
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.supplier') : </strong><b>{{ $purchase->supplier->name }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong><b>{{ $purchase->supplier->phone }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong><b>{{ $purchase->supplier->address }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.challan') : </strong><b>{{ $purchase->challan_no }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.challan_date') : </strong><b>{{ $purchase->challan_date }}</b></li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.date') </strong>
                        <b>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) }}</b>
                    </li>

                    <li style="font-size:11px!important;"><strong>@lang('menu.p_invoice_id') : </strong><b>{{ $purchase->invoice_id }}</b></li>

                    <li style="font-size:11px!important;"><strong>@lang('menu.requisitions') No : </strong>
                        <b>{{ $purchase?->requisition?->requisition_no }}
                        {{ $purchase?->receiveStock?->requisition?->requisition_no }}</b>
                    </li>

                    <li style="font-size:11px!important;"><strong>@lang('menu.department') : </strong>
                        <b>{{ $purchase?->requisition?->department?->name }}
                        {{ $purchase?->receiveStock?->requisition?->department?->name }}</b>
                    </li>

                    <li style="font-size:11px!important;"><strong>@lang('menu.rs_voucher') : </strong>
                        <b>{{ $purchase?->receiveStock?->voucher_no }}</b>
                    </li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.vehicle_no') : </strong><b>{{ $purchase->vehicle_no }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.net_weight') : </strong><b>{{ $purchase->net_weight}}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.weight_voucher') : </strong>
                        <b>{{ $purchase?->purchaseByScale?->voucher_no }}</b>
                    </li>
                    <li class="mt-2" style="font-size:11px!important;"><strong>@lang('menu.created_by') : </strong>
                        <b>{{ $purchase?->admin?->prefix.' '.$purchase?->admin?->name.' '.$purchase?->admin?->last_name }}</b>
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-3 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th style="font-size:11px!important;">@lang('menu.description')</th>
                        <th style="font-size:11px!important;">@lang('menu.quantity')</th>
                        <th style="font-size:11px!important;">@lang('menu.unit_cost')</th>
                        <th style="font-size:11px!important;">@lang('menu.unit_discount')</th>
                        <th style="font-size:11px!important;">@lang('menu.tax')(%)</th>
                        <th style="font-size:11px!important;">@lang('menu.net') @lang('menu.unit_cost')</th>
                        <th style="font-size:11px!important;">@lang('menu.lot_number')</th>
                        <th style="font-size:11px!important;">@lang('menu.sub_total')</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @php
                        $isMultiplierUnitExists = 0;
                    @endphp
                    @foreach ($purchase->purchaseProducts as $purchaseProduct)
                        <tr>
                            @php
                                $variant = $purchaseProduct->variant ? ' ('.$purchaseProduct->variant->variant_name.')' : '';
                            @endphp

                            <td style="font-size:11px!important;">
                                {{ $purchaseProduct->product->name.' '.$variant }}
                                <small>{!! $purchaseProduct->description ? '<br/>'.$purchaseProduct->description : '' !!}</small>
                            </td>
                            @php
                                $baseUnitMultiplier = $purchaseProduct?->purchaseUnit?->base_unit_multiplier ? $purchaseProduct?->purchaseUnit?->base_unit_multiplier : 1;
                                $purchasedQty = $purchaseProduct->quantity / $baseUnitMultiplier;
                            @endphp
                            <td style="font-size:11px!important;">
                                @if ($purchaseProduct?->purchaseUnit?->baseUnit)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($purchaseProduct->quantity) }}/{{ $purchaseProduct?->purchaseUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($purchasedQty) }}/{{ $purchaseProduct?->purchaseUnit?->code_name }}
                                @else

                                    {{ App\Utils\Converter::format_in_bdt($purchasedQty) }}/{{ $purchaseProduct?->purchaseUnit?->code_name }}
                                @endif
                            </td>

                            <td style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost * $baseUnitMultiplier) }}
                            </td>

                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_discount_amount * $baseUnitMultiplier) }}</td>

                            <td style="font-size:11px!important;">{{ '('.$purchaseProduct->unit_tax_percent.'%) '.App\Utils\Converter::format_in_bdt($purchaseProduct->unit_tax_amount * $baseUnitMultiplier) }}</td>
                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->net_unit_cost * $baseUnitMultiplier) }}</td>
                            <td style="font-size:11px!important;">{{ $purchaseProduct->lot_no }}</td>
                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-7">
                <table class="table report-table table-sm amounts_table">
                    <thead>
                        <tr>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.labour_cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->labour_cost) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.transport_cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->transport_cost) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.scale_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                                <b>{{ $purchase->scale_charge }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.others') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->others) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.total_additional_expense') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-start" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->total_additional_expense) }}</b>
                            </td>
                        </tr>

                        @if ($purchase->expense)
                            <tr>
                                <td class="text-start" style="font-size:11px!important;"><strong>@lang('menu.expense') @lang('menu.voucher_no') : </strong> <b>{{ $purchase->expense->voucher_no }} </b></td>
                                <td class="text-start" style="font-size:11px!important;"></td>
                            </tr>
                        @endif
                    </thead>
                </table>

                <p style="text-transform: uppercase;font-size:11px!important;" ><strong>@lang('menu.in_word') : </strong> <b>{{ App\Utils\Converter::format_in_text($purchase->total_purchase_amount) }} ({{ json_decode($generalSettings->business, true)['currency'] }}) @lang('menu.only').</b></p>
                <p style="font-size:11px!important;"><strong>@lang('menu.purchase_not') : </strong> <b>{{ $purchase->purchase_note }}</b></p>
            </div>

            <div class="col-5">
                <table class="table report-table table-sm amounts_table">
                    <thead>
                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.purchase_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                @if ($purchase->order_discount_type == 1)

                                    <b>(@lang('menu.fixed')) {{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}</b>
                                @else

                                    <b>({{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}%)
                                    {{ App\Utils\Converter::format_in_bdt($purchase->order_discount_amount) }}</b>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.purchase_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ '('.$purchase->purchase_tax_percent.'%)'. $purchase->purchase_tax_amount }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('short.a_l_t_eduction') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                @if ($purchase->ait_deduction_type == 1)

                                    <b>(@lang('menu.fixed')){{ App\Utils\Converter::format_in_bdt($purchase->ait_deduction) }}</b>
                                @else

                                    <b>({{ App\Utils\Converter::format_in_bdt($purchase->ait_deduction) }}%)
                                    {{ App\Utils\Converter::format_in_bdt($purchase->ait_deduction_amount) }}</b>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('short.total_add_expense_item') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->total_expense_with_item) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.total_invoice_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.paid') @lang('menu.on_invoice') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->paid) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.curr_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                @php
                                    $accountUtil = new App\Utils\AccountUtil();
                                    $amounts = $accountUtil->accountClosingBalance($purchase->supplier_account_id);
                                @endphp
                                <b>{{ $amounts['closing_balance_string'] }}</b>
                            </td>
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
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorized_by')</p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchase->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p><b>{{ $purchase->invoice_id }}</b></p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_sale'))
                        <small class="d-block">@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
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
