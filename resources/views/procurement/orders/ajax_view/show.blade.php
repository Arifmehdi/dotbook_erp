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
                     @lang('menu.po_details') (@lang('menu.po_id') : <strong>{{ $order->invoice_id }}</strong>)
                 </h5>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-4">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>@lang('menu.name') : </strong> {{ $order->supplier->name }}</li>
                             <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong> {{ $order->supplier->phone }}</li>
                             <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong> {{ $order->supplier->address }}</li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($order->date)) . ' ' . date($timeFormat, strtotime($order->time)) }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.po_id') : </strong> {{ $order->invoice_id }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.delivery_date') :</strong> {{$order->delivery_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($order->delivery_date)) : '' }}</li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>@lang('menu.requisition_no') : </strong> {{ $order?->requisition?->requisition_no }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.purchase_ledger_ac') : </strong> {{ $order?->purchaseAccount?->name }}</li>

                            <li style="font-size:11px!important;"><strong>@lang('menu.receiving_status') : </strong>
                                @if ($order->po_receiving_status == 'Pending')

                                    <span class="badge bg-danger">@lang('menu.pending')</span>
                                @elseif ($order->po_receiving_status == 'Completed')

                                    <span class="badge bg-success">@lang('menu.completed')</span>
                                @else

                                    <span class="badge bg-primary">@lang('menu.partial')</span>
                                @endif
                            </li>

                             <li style="font-size:11px!important;">
                                 <strong>@lang('menu.created_by') : </strong>
                                {{ $order?->admin?->prefix.' '.$order?->admin?->name.' '.$order?->admin?->last_name }}
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
                                         <th style="font-size:11px!important;">@lang('menu.item')</th>
                                         <th style="font-size:11px!important;">@lang('menu.ordered_quantity')</th>
                                         <th class="text-start" style="font-size:11px!important;">@lang('menu.unit_cost')(@lang('menu.before_discount'))</th>
                                         <th style="font-size:11px!important;">@lang('menu.unit_discount')</th>
                                         <th style="font-size:11px!important;">@lang('menu.unit_cost')(@lang('menu.before_tax'))</th>
                                         <th style="font-size:11px!important;">@lang('menu.sub_total') (@lang('menu.before_tax'))</th>
                                         <th style="font-size:11px!important;">@lang('menu.tax')(%)</th>
                                         <th style="font-size:11px!important;">@lang('menu.unit_cost')(@lang('menu.after_tax'))</th>
                                         <th style="font-size:11px!important;">@lang('menu.sub_total')</th>
                                         <th style="font-size:11px!important;">@lang('menu.pending_qty')</th>
                                         <th style="font-size:11px!important;">@lang('menu.received_qty')</th>
                                     </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                     @foreach ($order->orderedProducts as $orderedProduct)
                                        <tr>
                                            @php
                                                $variant = $orderedProduct->variant ? '('.$orderedProduct->variant->variant_name.')' : '';

                                            @endphp

                                            <td style="font-size:11px!important;">{{ $orderedProduct?->product?->name.' '.$variant }}</td>

                                            @php
                                                $baseUnitMultiplier = $orderedProduct?->orderUnit?->base_unit_multiplier ? $orderedProduct?->orderUnit?->base_unit_multiplier : 1;
                                                $orderedQty = $orderedProduct->order_quantity / $baseUnitMultiplier;
                                                $pendingQty = $orderedProduct->pending_quantity / $baseUnitMultiplier;
                                                $receivedQty = $orderedProduct->received_quantity / $baseUnitMultiplier;
                                            @endphp

                                            <td style="font-size:11px!important;">
                                                @if ($orderedProduct?->orderUnit?->baseUnit)
                                                    @php
                                                        $isMultiplierUnitExists = 1;
                                                    @endphp

                                                    (<strong>{{ App\Utils\Converter::format_in_bdt($orderedProduct->order_quantity) }}/{{ $orderedProduct?->orderUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}
                                                @else

                                                    {{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}
                                                @endif
                                            </td>

                                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->unit_cost * $baseUnitMultiplier) }}</td>

                                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->unit_discount_amount * $baseUnitMultiplier) }} </td>

                                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->unit_cost_with_discount * $baseUnitMultiplier) }}</td>
                                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->subtotal) }}</td>
                                            <td style="font-size:11px!important;">{{ '('.$orderedProduct->unit_tax_percent.'%)='.($orderedProduct->unit_tax_amount * $baseUnitMultiplier) }}</td>
                                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->net_unit_cost * $baseUnitMultiplier) }} </td>
                                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->line_total) }}</td>
                                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($pendingQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}</td>
                                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($receivedQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}</td>

                                            @if (count($orderedProduct->receivedProducts) > 0)
                                                <tr>
                                                    <td colspan="5" class="text-center"><strong>@lang('menu.receive_details') ➡</strong></td>

                                                    <td colspan="6">
                                                        <table class="table report-table table-sm table-bordered print_table">
                                                            <thead>
                                                                <tr class="bg-info">
                                                                    <th style="font-size:11px!important;">@lang('menu.received') @lang('menu.date')</th>
                                                                    <th style="font-size:11px!important;">@lang('menu.rs_voucher')</th>
                                                                    <th style="font-size:11px!important;">@lang('menu.challan_no')</th>
                                                                    <th style="font-size:11px!important;">@lang('menu.challan_date')</th>
                                                                    <th style="font-size:11px!important;">@lang('menu.lot_number')</th>
                                                                    <th style="font-size:11px!important;">@lang('menu.received') @lang('menu.quantity')</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($orderedProduct->receivedProducts as $receivedProduct)
                                                                    <tr class="text-end">
                                                                        <td style="font-size:11px!important;">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($receivedProduct?->receiveStock?->date)) }}</td>
                                                                        <td style="font-size:11px!important;">{{ $receivedProduct?->receiveStock?->voucher_no }}</td>
                                                                        <td style="font-size:11px!important;">{{ $receivedProduct?->receiveStock?->challan_no }}</td>
                                                                        <td style="font-size:11px!important;">{{ $receivedProduct?->receiveStock?->challan_date }}</td>
                                                                        <td style="font-size:11px!important;">{{ $receivedProduct->lot_number }}</td>
                                                                        <td class="fw-bold text-success" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($receivedProduct->quantity / $baseUnitMultiplier) }}/{{ $orderedProduct?->orderUnit?->code_name }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                    <p class="fw-bold">@lang('menu.payments_against_reference')</p>
                     <div class="col-md-8">
                        @if (auth()->user()->can('payments_index'))
                            @include('procurement.orders.ajax_view.partials.modal_payment_list')
                        @endif
                     </div>

                     <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.purchase_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        @if ($order->order_discount_type == 1)

                                            (Fixed) {{ App\Utils\Converter::format_in_bdt($order->order_discount) }}
                                        @else

                                            ({{ App\Utils\Converter::format_in_bdt($order->order_discount) }}%)
                                            {{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.purchase_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ '('.$order->purchase_tax_percent.'%) ' . $order->purchase_tax_amount }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('short.a_l_t_eduction') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        @if ($order->ait_deduction_type == 1)

                                            (Fixed) {{ App\Utils\Converter::format_in_bdt($order->ait_deduction) }}
                                        @else

                                            ({{ App\Utils\Converter::format_in_bdt($order->ait_deduction) }}%)
                                            {{ App\Utils\Converter::format_in_bdt($order->ait_deduction_amount) }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('short.total_add_expense_item') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->total_expense_with_item) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.total_ordered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->total_purchase_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.curr_balance') {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end fw-bold" style="font-size:11px!important;">
                                        @php
                                            $accountUtil = new App\Utils\AccountUtil();
                                            $amounts = $accountUtil->accountClosingBalance($order->supplier_account_id);
                                        @endphp
                                        {{ $amounts['closing_balance_string'] }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                 </div>
                 <hr>
                 <div class="row">
                     <div class="col-md-6">
                         <div class="details_area">
                             <p style="font-size:11px!important;"><b>@lang('menu.shipping_details')</b> : </p>
                             <p class="shipping_details">{{ $order->shipment_details }}</p>
                         </div>
                     </div>

                     <div class="col-md-6">
                         <div class="details_area">
                             <p style="font-size:11px!important;"><b>@lang('menu.order_note')</b> : </p>
                             <p class="purchase_note">{{ $order->purchase_note }}</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                @if (auth()->user()->can('edit_po'))
                    <a href="{{ route('purchases.order.edit', [$order->id, 'ordered']) }}" class="btn btn-sm btn-secondary m-0 me-2">@lang('menu.edit')</a>
                @endif

                <a href="{{ route('purchases.order.supplier.copy.print', $order->id) }}" id="print_supplier_copy" class="btn btn-sm btn-info m-0 me-2"> <i class="fas fa-print"></i> Print Supplier Copy</a>
                <button type="submit" class="btn btn-sm btn-success m-0 me-2" id="print_modal_details_btn">@lang('menu.print')</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0">@lang('menu.close')</button>
             </div>
         </div>
     </div>
 </div>
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

    div#footer {position:fixed;bottom:27px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:A4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}

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
 <!-- Purchase Order print templete-->
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

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h5 style="text-transform:uppercase;"><strong>@lang('menu.purchase_order')</strong></h5>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.supplier') : </strong><b>{{ $order?->supplier?->name }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong><b>{{ $order?->supplier?->phone }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong><b>{{ $order?->supplier?->address }}</b></li>
                </ul>
            </div>
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong><b>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($order->date)) }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.po_id') : </strong> <b>{{ $order->invoice_id }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.delivery_date') : </strong><b>{{ $order->delivery_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($order->delivery_date)) : '' }}</b></li>
                </ul>
            </div>
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.requisition_no') : </strong> {{ $order?->requisition?->requisition_no }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.receiving_status') : </strong>{{ $order->po_receiving_status }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.created_by') : </strong>
                        {{ $order?->admin?->prefix.' '.$order?->admin?->name.' '.$order?->admin?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-3 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th style="font-size:11px!important;">@lang('menu.item')</th>
                        <th style="font-size:11px!important;">@lang('menu.ordered_quantity')</th>
                        <th style="font-size:11px!important;">@lang('menu.unit_cost')</th>
                        <th style="font-size:11px!important;">@lang('menu.unit_discount')</th>
                        <th style="font-size:11px!important;">@lang('menu.tax')(%)</th>
                        <th style="font-size:11px!important;">@lang('menu.sub_total')</th>
                        <th style="font-size:11px!important;">@lang('menu.pending_qty')</th>
                        <th style="font-size:11px!important;">@lang('menu.received_qty')</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @foreach ($order->orderedProducts as $orderedProduct)
                        <tr>
                            @php
                                $variant = $orderedProduct->variant ? ' ('.$orderedProduct->variant->variant_name.')' : '';
                            @endphp

                            <td style="font-size:11px!important;">
                                {{ $orderedProduct?->product?->name.' '.$variant }}
                                <small>{!! $orderedProduct->description ? '<br/>'.$orderedProduct->description : '' !!}</small>
                            </td>

                            @php
                                $baseUnitMultiplier = $orderedProduct?->orderUnit?->base_unit_multiplier ? $orderedProduct?->orderUnit?->base_unit_multiplier : 1;
                                $orderedQty = $orderedProduct->order_quantity / $baseUnitMultiplier;
                                $pendingQty = $orderedProduct->pending_quantity / $baseUnitMultiplier;
                                $receivedQty = $orderedProduct->received_quantity / $baseUnitMultiplier;
                            @endphp
                            <td style="font-size:11px!important;">
                                @if ($orderedProduct?->orderUnit?->baseUnit)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($orderedProduct->order_quantity) }}/{{ $orderedProduct?->orderUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}
                                @else

                                    {{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}
                                @endif
                            </td>

                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->unit_cost * $baseUnitMultiplier) }}</td>
                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->unit_discount_amount * $baseUnitMultiplier) }} </td>
                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->unit_tax_percent).'%' }}</td>
                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderedProduct->line_total) }}</td>
                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($pendingQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}</td>
                            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($receivedQty) }}/{{ $orderedProduct?->orderUnit?->code_name }}</td>

                            @if (count($orderedProduct->receivedProducts) > 0)
                                <tr>
                                    <td colspan="1" class="text-center"><strong>@lang('menu.receive_details') ➡</strong></td>

                                    <td colspan="7">
                                        <table class="table report-table table-sm table-bordered print_table">
                                            <thead>
                                                <tr class="bg-info">
                                                    <th style="font-size:11px!important;">@lang('menu.received') @lang('menu.date')</th>
                                                    <th style="font-size:11px!important;">@lang('menu.rs_voucher')</th>
                                                    <th style="font-size:11px!important;">@lang('menu.challan_no')</th>
                                                    <th style="font-size:11px!important;">@lang('menu.challan_date')</th>
                                                    <th style="font-size:11px!important;">@lang('menu.lot_number')</th>
                                                    <th style="font-size:11px!important;">@lang('menu.received') @lang('menu.quantity')</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($orderedProduct->receivedProducts as $receivedProduct)
                                                    <tr class="text-end">
                                                        <td style="font-size:11px!important;">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($receivedProduct?->receiveStock?->date)) }}</td>
                                                        <td style="font-size:11px!important;">{{ $receivedProduct?->receiveStock?->voucher_no }}</td>
                                                        <td style="font-size:11px!important;">{{ $receivedProduct?->receiveStock?->challan_no }}</td>
                                                        <td style="font-size:11px!important;">{{ $receivedProduct?->receiveStock?->challan_date }}</td>
                                                        <td style="font-size:11px!important;">{{ $receivedProduct->lot_number }}</td>
                                                        <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($receivedProduct->quantity / $baseUnitMultiplier) }}/{{ $orderedProduct?->orderUnit?->code_name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-6">
                <p style="text-transform: uppercase;font-size:10px!important;"><strong>@lang('menu.in_word') : </strong> <b>{{ App\Utils\Converter::format_in_text($order->total_purchase_amount) }} ({{ json_decode($generalSettings->business, true)['currency'] }}) @lang('menu.only').</b></p>
                <p style="font-size:11px!important;"><strong>@lang('menu.order_note') </strong> :</p>
                <p style="font-size:11px!important;"><b>{{ $order->purchase_note }}</b></p><br>
                <p style="font-size:11px!important;"><strong>@lang('menu.shipment_details') </strong> :</p>
                <p style="font-size:11px!important;"><b>{{ $order->shipment_details }}</b></p>
            </div>

            <div class="col-6">
                <table class="table report-table table-sm amounts_table">
                    <thead>
                        <tr>
                            <th colspan="11" class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td colspan="2" class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="11" class="text-end" style="font-size:11px!important;">@lang('menu.order_discount') :
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                            </th>
                            <td colspan="2" class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->order_discount) }} {{$order->order_discount_type == 1 ? '(Fixed)' : '%' }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="11" class="text-end" style="font-size:11px!important;">@lang('menu.order_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td colspan="2" class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->purchase_tax_amount).' ('.$order->purchase_tax_percent.'%)' }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="11" class="text-end" style="font-size:11px!important;">@lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td colspan="2" class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="11" class="text-end" style="font-size:11px!important;">@lang('menu.total_ordered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td colspan="2" class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->total_purchase_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="11" class="text-end" style="font-size:11px!important;">@lang('menu.curr_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td colspan="2" class="text-end" style="font-size:11px!important;">
                                @php
                                    $accountUtil = new App\Utils\AccountUtil();
                                    $amounts = $accountUtil->accountClosingBalance($order->supplier_account_id);
                                @endphp
                                {{ $amounts['closing_balance_string'] }}
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
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorize_by')</p>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($order->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{ $order->invoice_id }}</p>
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
                    <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Purchase print templete end-->
