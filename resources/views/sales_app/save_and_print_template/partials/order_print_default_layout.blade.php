@php $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first(); @endphp
    <div class="sale_print_template">
    <style>
        div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
        @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}

        h6 { font-size: 16px; }
        p {  font-size: 14px; }
        td {  color: black; }
    </style>
        <div class="details_area">
            @if ($defaultLayout->is_header_less == 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="header_text text-center">
                            <p>{{ $defaultLayout->header_text }}</p>
                            <p>{{ $defaultLayout->sub_heading_1 }}<p>
                            <p>{{ $defaultLayout->sub_heading_2 }}<p>
                            <p>{{ $defaultLayout->sub_heading_3 }}<p>
                        </div>
                    </div>
                </div>

                <div class="row" style="border-bottom: 1px solid black;">
                    <div class="col-4">
                        @if ($defaultLayout->show_shop_logo == 1)
                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                            @endif
                        @endif
                    </div>

                    <div class="col-8 text-end">
                        <h6 class="company_name" style="text-transform: uppercase;">
                            {{ json_decode($generalSettings->business, true)['shop_name'] }}
                        </h6>

                        <p class="company_address"><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>

                        <p>
                            @if ($defaultLayout->branch_email && json_decode($generalSettings->business, true)['email'])

                                <strong>@lang('menu.email') :</strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                            @endif

                            @if ($defaultLayout->branch_phone)

                                <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-12 text-center">
                        <h5 style="text-transform: uppercase;">@lang('menu.sales_order')</h5>
                    </div>
                </div>
            @endif

            @if ($defaultLayout->is_header_less == 1)

                @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                    <br/>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-1">
                <div class="row">
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>@lang('menu.customer') : </strong> {{ $order->customer ? $order->customer->name : 'Walk-In-Customer' }}
                            </li>

                            @if ($defaultLayout->customer_phone)
                                <li style="font-size:11px!important;">
                                    <strong>@lang('menu.phone') : </strong> {{ $order->customer ? $order->customer->phone : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_address)
                                <li style="font-size:11px!important;">
                                    <strong>@lang('menu.address') : </strong> {{ $order->customer ? $order->customer->address : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-4 text-center">
                        @if ($defaultLayout->is_header_less == 1)

                            <h5 style="text-transform: uppercase;">@lang('menu.sales_order')</h5>
                        @endif
                        <img style="width: 170px; height:30px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($order->order_id, $generator::TYPE_CODE_128)) }}">
                    </div>

                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong> @lang('menu.order_no') : </strong> {{ $order->order_id }}</li>
                            <li style="font-size:11px!important;"><strong> @lang('menu.date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($order->order_date)) }}</li>
                            <li style="font-size:11px!important;"><strong> @lang('menu.sr') : </strong> {{ $order->sr->prefix . ' ' . $order->sr->name . ' ' . $order->sr->last_name }} </li>
                            <li style="font-size:11px!important;"><strong> @lang('menu.created_by') : </strong> {{ $order->orderBy->prefix . ' ' . $order->orderBy->name . ' ' . $order->orderBy->last_name }} </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-1 pb-3">
                <table class="table report-table table-sm table-bordered print_table">
                    <thead>
                        <tr>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.sl')</th>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.description')</th>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.ordered_qty')</th>
                            <th class="text-end" style="font-size:11px!important;">@lang('short.delivered_qty')</th>
                            <th class="text-end" style="font-size:11px!important;">@lang('short.left_qty')</th>

                            @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                <th class="text-end" style="font-size:11px!important;">@lang('menu.warranty')</th>
                            @endif

                            <th class="text-end" style="font-size:11px!important;">@lang('menu.price_exc_tax')</th>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.rate_type')</th>

                            @if ($defaultLayout->product_discount)

                                <th class="text-end" style="font-size:11px!important;">@lang('menu.discount')</th>
                            @endif

                            @if ($defaultLayout->product_tax)

                                <th class="text-end" style="font-size:11px!important;">@lang('menu.tax')</th>
                            @endif

                            <th class="text-end" style="font-size:11px!important;">@lang('menu.price_inc_tax')</th>

                            <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @php
                            $totalPrAmount = 0;
                            $isMultiplierUnitExists = 0;
                            $totalOrderedQtyAsMultiplier = 0;
                            $totalDeliveredQtyAsMultiplier = 0;
                            $totalLeftQtyAsMultiplier = 0;
                            $totalOrderedQtyAsBaseUnit = 0;
                            $totalDeliveredQtyAsBaseUnit = 0;
                            $totalLeftQtyAsBaseUnit = 0;
                        @endphp
                        @foreach ($customerCopySaleProducts as $saleProduct)

                            <tr>
                                <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1}}</td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $saleProduct->p_name }}

                                    @if ($saleProduct->product_variant_id)

                                        -{{ $saleProduct->variant_name }}
                                    @endif

                                    {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $saleProduct->description . '</small>' : '' !!}
                                </td>

                                @php
                                    $baseUnitMultiplier = $saleProduct->base_unit_multiplier ? $saleProduct->base_unit_multiplier : 1;
                                    $orderedQty = $saleProduct->ordered_quantity / $baseUnitMultiplier;
                                    $doDeliveredQty = $saleProduct->do_delivered_qty / $baseUnitMultiplier;
                                    $doLeftQty = $saleProduct->do_left_qty / $baseUnitMultiplier;

                                    $totalOrderedQtyAsMultiplier += $orderedQty;
                                    $totalDeliveredQtyAsMultiplier += $doDeliveredQty;
                                    $totalLeftQtyAsMultiplier += $doLeftQty;

                                    $totalOrderedQtyAsBaseUnit += $saleProduct->ordered_quantity;
                                    $totalDeliveredQtyAsBaseUnit += $saleProduct->do_delivered_qty;
                                    $totalLeftQtyAsBaseUnit += $saleProduct->do_left_qty;
                                @endphp

                                <td class="text-end" style="font-size:11px!important;">
                                    @if ($saleProduct?->base_unit_code_name)
                                        @php
                                            $isMultiplierUnitExists = 1;
                                        @endphp

                                        ({{ App\Utils\Converter::format_in_bdt($saleProduct->ordered_quantity) }}/{{ $saleProduct?->base_unit_code_name }})={{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $saleProduct->unit_code_name }}
                                    @else

                                        {{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $saleProduct->unit_code_name }}
                                    @endif

                                </td>
                                <td class="text-end" style="font-size:11px!important;">

                                    {{ App\Utils\Converter::format_in_bdt($saleProduct->do_delivered_qty) }}/{{ $saleProduct?->base_unit_code_name ? $saleProduct?->base_unit_code_name : $saleProduct?->unit_code_name }}
                                </td>

                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($saleProduct->do_left_qty) }}/{{ $saleProduct?->base_unit_code_name ? $saleProduct?->base_unit_code_name : $saleProduct->unit_code_name }}
                                </td>

                                @if (
                                    $defaultLayout->product_w_type ||
                                    $defaultLayout->product_w_duration ||
                                    $defaultLayout->product_w_discription
                                )
                                    <td class="text-end" style="font-size:11px!important;">
                                        @if ($saleProduct->warranty_id)

                                            {{ $saleProduct->w_duration . ' ' .$saleProduct->w_duration_type }}
                                            {{ $saleProduct->type == 1 ? 'Warranty' : 'Guaranty' }}
                                            @if ($saleProduct->w_description)

                                                {!! '<br><small>'.$saleProduct->w_description.'</small>'  !!}
                                            @endif
                                        @else

                                            <b>@lang('menu.no')</b>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax * $baseUnitMultiplier) }} </td>
                                @php
                                    $showPrAmount = $saleProduct->price_type == 'PR' ? '('.$saleProduct->pr_amount.')' : '';
                                    $totalPrAmount += $saleProduct->ordered_quantity * $saleProduct->pr_amount
                                @endphp

                                <td class="text-end" style="font-size:11px!important;">{{ $saleProduct->price_type.$showPrAmount }} </td>

                                @if ($defaultLayout->product_discount)

                                    <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount * $baseUnitMultiplier) }}</td>
                                @endif

                                @if ($defaultLayout->product_tax)

                                    <td class="text-end" style="font-size:11px!important;">{{ $saleProduct->unit_tax_percent }}%</td>
                                @endif

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax * $baseUnitMultiplier) }} </td>

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (count($order->saleProducts) > 15)
                <br>
                <div class="row page_break">
                    <div class="col-md-12 text-end">
                        <h6><em>@lang('menu.dontinued_to_this_next_page')....</em></h6>
                    </div>
                </div>

                @if ($defaultLayout->is_header_less == 1)
                    @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                        <br/>
                    @endfor
                @endif
            @endif

            <div class="row" style="margin-top: -23px!important;">
                <div class="col-7">
                    @if ($defaultLayout->show_total_in_word)
                        <p style="text-transform: uppercase; font-size:11px!important;"><strong>@lang('menu.in_word') : </strong> {{ App\Utils\Converter::format_in_text($order->total_payable_amount) }} ({{ json_decode($generalSettings->business, true)['currency'] }}) @lang('menu.only').</p>
                    @endif

                    @if (
                        $defaultLayout->account_name ||
                        $defaultLayout->account_no ||
                        $defaultLayout->bank_name ||
                        $defaultLayout->bank_branch
                    )
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($defaultLayout->account_name)
                                <p style="font-size:11px!important;">@lang('menu.account_name') : {{ $defaultLayout->account_name }}</p>
                            @endif

                            @if ($defaultLayout->account_no)
                                <p style="font-size:11px!important;">@lang('menu.account_no') : {{ $defaultLayout->account_no }}</p>
                            @endif

                            @if ($defaultLayout->bank_name)
                                <p style="font-size:11px!important;">@lang('menu.bank') : {{ $defaultLayout->bank_name }}</p>
                            @endif

                            @if ($defaultLayout->bank_branch)
                                <p style="font-size:11px!important;">@lang('menu.branch') : {{ $defaultLayout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="price_adjustment_note mt-2">
                        <p style="font-size:11px!important;"><strong>@lang('menu.price_adjustment_note') :</strong> {{ $order->price_adjustment_note }}</p>
                    </div>

                    <div class="order_note mt-1">
                        <p style="font-size:11px!important;"><strong>@lang('menu.comment') :</strong> {{ $order->comment }}</p>
                    </div>

                    <div class="order_note mt-1">
                        <p style="font-size:11px!important;"><strong>@lang('menu.note') :</strong> {{ $order->sale_note }}</p>
                    </div>

                    <div class="shipping_address mt-1">
                        <p style="font-size:11px!important;"><strong>@lang('menu.payment_note') :</strong> {{ $order->payment_note }}</p>
                    </div>

                    <div class="shipping_address mt-1">
                        <p style="font-size:11px!important;"><strong>@lang('menu.shipping_address') :</strong> {{ $order->shipping_address }}</p>
                    </div>

                    <div class="receiver_phone mt-1">
                        <p style="font-size:11px!important;"><strong>@lang('menu.receiver_phone') : </strong> {{ $order->receiver_phone }}</p>
                    </div>
                </div>

                <div class="col-5">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.rate_type') : <strong></td>
                                <td class="text-end" style="font-size:11px!important;">{{ $order->all_price_type }}{{ $order->all_price_type == 'PR' ? '('.App\Utils\Converter::format_in_bdt($totalPrAmount).')' : '' }}</td>
                            </tr>

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.total_ordered_qty') : <strong></td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->total_ordered_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.total_delivered_qty') : </strong></td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->total_delivered_qty) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                                </td>
                            </tr>

                            @if ($order->order_discount > 0)
                                <tr>
                                    <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.order_discount') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                    <td class="text-end" style="font-size:11px!important;">
                                        @if ($order->order_discount_type == 1)
                                            {{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }} (Fixed)
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }} ( {{ $order->order_discount }}%)
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            @if ($order->order_tax_percent > 0)
                                <tr>
                                    <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.order_tax') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->order_tax_amount) }} ({{ $order->order_tax_percent }} %)
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.total_ordered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($order->total_payable_amount) }}</td>
                            </tr>

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.received_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($order->paid) }}</td>
                            </tr>

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.current_balance') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="text-end fw-bold" style="font-size:11px!important;">
                                    @php
                                        $accountUtil = new App\Utils\AccountUtil();
                                        $amounts = $accountUtil->accountClosingBalance($order->customer_account_id, $order->sr_user_id);
                                    @endphp
                                    {{ $amounts['closing_balance_string'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

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
                <div class="col-md-12">
                    <div class="invoice_notice mt-1">
                        <p>{!! $defaultLayout->invoice_notice ? '<b>Attention : </b>' . $defaultLayout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <p>{{ $defaultLayout->footer_text }}</p>
                    </div>
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
