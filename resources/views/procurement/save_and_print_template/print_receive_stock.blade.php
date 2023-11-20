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
                        <strong>@lang('menu.email') : </strong><b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                        <strong>@lang('menu.phone') : </strong><b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;"><strong>@lang('menu.receive_stock_voucher')</strong></h6>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>@lang('menu.supplier') : </strong><b>{{ $receiveStock?->supplier?->name }}</b></li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong><b>{{ $receiveStock?->supplier?->phone }}</b></li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong><b>{{ $receiveStock?->supplier?->address }}</b></li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.challan') : </strong><b>{{ $receiveStock->challan_no }}</b></li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.challan_date') : </strong><b>{{ $receiveStock->challan_date }}</b></li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong>
                            <b>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($receiveStock->date)) }}</b>
                        </li>

                        <li style="font-size:11px!important;"><strong>@lang('menu.voucher_no') : </strong>
                            <b>{{ $receiveStock->voucher_no }}</b>
                        </li>

                        <li style="font-size:11px!important;"><strong>@lang('menu.created_by') : </strong>
                            <b>{{ $receiveStock?->createdBy?->prefix.' '.$receiveStock?->createdBy?->name.' '.$receiveStock?->createdBy?->last_name }}</b>
                        </li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>@lang('menu.requisitions') No : </strong>
                            <b>{{ $receiveStock?->requisition?->requisition_no  }}</b>
                        </li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.po_id') : </strong>
                            <b>{{ $receiveStock?->purchaseOrder?->invoice_id  }}</b>
                        </li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.department') : </strong>
                            @if ($receiveStock->requisition)

                                @if ($receiveStock->requisition->department)

                                    <b>{{ $receiveStock->requisition->department->name }}</b>
                                @endif
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table pt-3 pb-3">
                <table class="table report-table table-sm table-bordered print_table">
                    <thead>
                        <tr>
                            <th style="font-size:11px!important;">@lang('menu.sl')</th>
                            <th style="font-size:11px!important;">@lang('menu.item_name')</th>
                            <th style="font-size:11px!important;">@lang('menu.quantity')</th>
                            <th style="font-size:11px!important;">@lang('menu.unit')</th>
                            <th style="font-size:11px!important;">@lang('menu.lot_number')</th>
                            <th style="font-size:11px!important;">@lang('menu.description')</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @php
                            $isMultiplierUnitExists = 1;
                            $totalQty = 0;
                        @endphp
                        @foreach ($receiveStock->receiveStockProducts as $rsProduct)
                            <tr>
                                @php
                                    $variant = $rsProduct->variant ? ' ('.$rsProduct->variant->variant_name.')' : '';
                                @endphp
                                <td style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                <td style="font-size:11px!important;">{{ $rsProduct->product->name.' '.$variant }}</td>
                                @php
                                    $baseUnitMultiplier = $rsProduct?->receiveUnit?->base_unit_multiplier ? $rsProduct?->receiveUnit?->base_unit_multiplier : 1;
                                    $receivedQty = $rsProduct->quantity / $baseUnitMultiplier;
                                    $totalQty += $receivedQty;
                                @endphp
                                <td style="font-size:11px!important;">
                                    @if ($rsProduct?->receiveUnit?->baseUnit)
                                        @php
                                            $isMultiplierUnitExists = 1;
                                        @endphp

                                        (<strong>{{ App\Utils\Converter::format_in_bdt($rsProduct->quantity) }}/{{ $rsProduct?->receiveUnit?->baseUnit->code_name }}</strong>)=<b>{{ App\Utils\Converter::format_in_bdt($receivedQty) }}/{{ $rsProduct?->receiveUnit?->code_name }}</b>
                                    @else

                                        <b>{{ App\Utils\Converter::format_in_bdt($receivedQty) }}/{{ $rsProduct?->receiveUnit?->code_name }}</b>
                                    @endif
                                </td>
                                <td style="font-size:11px!important;">{{ $rsProduct?->receiveUnit?->code_name }}</td>
                                <td style="font-size:11px!important;">{{ $rsProduct->lot_number }}</td>
                                <td style="font-size:11px!important;">{{ $rsProduct->short_description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-12 text-start">
                    <p style="font-size:11px!important;"><strong>@lang('menu.receive_notes') : </strong> {{ $receiveStock->note }} </p>
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
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($receiveStock->voucher_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $receiveStock->voucher_no }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small>@lang('menu.print_date') {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_sale'))
                            <small class="d-block">@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
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
