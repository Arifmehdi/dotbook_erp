@php
    $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
@endphp

<div class="sale_print_template">
    <style>
        @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
        div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

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
                        <p>{{ $defaultLayout->sub_heading_1 }}</p>
                        <p>{{ $defaultLayout->sub_heading_2 }}</p>
                        <p>{{ $defaultLayout->sub_heading_3 }}</p>
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
                    <p class="company_name" style="text-transform: uppercase;">
                        <strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong>
                    </p>

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
                    <h5 style="text-transform: uppercase;">@lang('menu.delivery_order')</h5>

                    <h6>
                        @if ($do->delivery_qty_status == 0)

                            @lang('menu.pending')
                        @elseif ($do->delivery_qty_status == 1)

                            @lang('menu.partial')
                        @elseif ($do->delivery_qty_status == 2)

                            @lang('menu.completed')
                        @endif
                    </h6>
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
                        <li style="font-size:11px!important;"><strong>@lang('menu.customer') : </strong>{{ $do?->customer?->name }}</li>

                        @if ($defaultLayout->customer_phone)

                            <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong> {{ $do?->customer?->phone }}</li>
                        @endif

                        @if ($defaultLayout->customer_address)

                            <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong>{{ $do?->customer?->address }}</li>
                        @endif
                    </ul>
                </div>
                <div class="col-4 text-center">
                    @if ($defaultLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h5 style="text-transform: uppercase;">@lang('menu.delivery_order')</h5>

                            <h6>
                                @if ($do->delivery_qty_status == 0)
                                    @lang('menu.pending')
                                @elseif ($do->delivery_qty_status == 1)
                                    @lang('menu.partial')
                                @elseif ($do->delivery_qty_status == 2)
                                    @lang('menu.completed')
                                @endif
                            </h6>
                        </div>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($do->do_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong> @lang('menu.do_id') : </strong> {{ $do->do_id }}</li>

                        <li style="font-size:11px!important;"><strong>@lang('menu.do_date') : </strong>
                            {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($do->do_date)) }}
                        </li>

                        <li style="font-size:11px!important;"><strong> @lang('menu.sr') : </strong> {{ $do?->sr?->prefix . ' ' . $do?->sr?->name . ' ' . $do?->sr?->last_name }} </li>
                        <li style="font-size:11px!important;"><strong> @lang('menu.created_by') : </strong> {{ $do?->doBy?->prefix . ' ' . $do?->doBy?->name . ' ' . $do?->doBy?->last_name }}</li>
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
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.do_qty')</th>

                        <th class="text-end" style="font-size:11px!important;">@lang('short.delivered_qty')</th>

                        <th class="text-end" style="font-size:11px!important;">@lang('short.left_qty')</th>

                        <th class="text-end" style="font-size:11px!important;">@lang('menu.price_exc_tax')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.rate_type')</th>

                        @if ($defaultLayout->product_discount)
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.discount')</th>
                        @endif

                        @if ($defaultLayout->product_tax)
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.tax')</th>
                        @endif

                        <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                    </tr>
                </thead>

                <tbody class="sale_print_product_list">
                    @php
                        $isMultiplierUnitExists = 0;
                        $totalPrAmount = 0;
                        $totalDoQtyAsMultiplier = 0;
                        $totalDeliveredQtyAsMultiplier = 0;
                        $totalLeftQtyAsMultiplier = 0;
                        $totalDoQtyAsBaseUnit = 0;
                        $totalDeliveredQtyAsBaseUnit = 0;
                        $totalLeftQtyAsBaseUnit = 0;
                    @endphp
                    @foreach ($customerCopySaleProducts as $doProduct)
                        <tr>
                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $doProduct->p_name }}

                                @if ($doProduct->product_variant_id)
                                    -{{ $doProduct->variant_name }}
                                @endif
                                {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $doProduct->description . '</small>' : '' !!}
                            </td>

                            @php
                                $baseUnitMultiplier = $doProduct?->base_unit_multiplier ? $doProduct?->base_unit_multiplier : 1;
                                $doQty = $doProduct->do_qty / $baseUnitMultiplier;
                                $doDeliveredQty = $doProduct->do_delivered_qty / $baseUnitMultiplier;
                                $doLeftQty = $doProduct->do_left_qty / $baseUnitMultiplier;

                                $totalDoQtyAsMultiplier += $doQty;
                                $totalDeliveredQtyAsMultiplier += $doDeliveredQty;
                                $totalLeftQtyAsMultiplier += $doLeftQty;

                                $totalDoQtyAsBaseUnit += $doProduct->ordered_quantity;
                                $totalDeliveredQtyAsBaseUnit += $doProduct->do_delivered_qty;
                                $totalLeftQtyAsBaseUnit += $doProduct->do_left_qty;
                            @endphp

                            <td class="text-end" style="font-size:11px!important;">
                                @if ($doProduct?->base_unit_code_name)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($doProduct->do_qty) }}/{{ $doProduct?->base_unit_code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($doQty) }}/{{ $doProduct?->unit_code_name }}
                                @else

                                    {{ App\Utils\Converter::format_in_bdt($doQty) }}/{{ $doProduct?->unit_code_name }}
                                @endif
                            </td>

                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($doProduct->do_delivered_qty) }}/{{ $doProduct?->base_unit_code_name ? $doProduct?->base_unit_code_name : $doProduct?->unit_code_name }}
                            </td>

                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($doProduct->do_left_qty) }}/{{ $doProduct?->base_unit_code_name ? $doProduct?->base_unit_code_name : $doProduct?->unit_code_name }}
                            </td>

                            @if (
                                $defaultLayout->product_w_type ||
                                $defaultLayout->product_w_duration ||
                                $defaultLayout->product_w_discription
                            )
                                <td class="text-end" style="font-size:11px!important;">
                                    @if ($doProduct->warranty_id)
                                        {{ $doProduct->w_duration . ' ' . $doProduct->w_duration_type }}
                                        {{ $doProduct->type == 1 ? 'Warranty' : 'Guaranty' }}
                                        {!! $defaultLayout->product_w_discription
                                            ? '<br><small class="text-muted">' . $doProduct->w_description . '</small>'
                                            : '' !!}
                                    @else
                                        <strong>@lang('short.no')</strong>
                                    @endif
                                </td>
                            @endif

                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($doProduct->unit_price_inc_tax) }} </td>

                            @php
                                $showPrAmount = $doProduct->price_type == 'PR' ? '('.App\Utils\Converter::format_in_bdt($doProduct->pr_amount).')' : '';
                                $totalPrAmount += $doQty * $doProduct->pr_amount
                            @endphp

                            <td class="text-end" style="font-size:11px!important;">{{ $doProduct->price_type.$showPrAmount }} </td>

                            @if ($defaultLayout->product_discount)
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($doProduct->unit_discount_amount * $baseUnitMultiplier) }}
                                </td>
                            @endif

                            @if ($defaultLayout->product_tax)
                                <td class="text-end" style="font-size:11px!important;">{{ $doProduct->unit_tax_percent }}%</td>
                            @endif

                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($doProduct->subtotal) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end text-black" style="font-size:11px!important;">@lang('menu.total') : </th>
                        <th class="text-end text-black" style="font-size:11px!important;">
                            @if ($isMultiplierUnitExists == 1)
                                ({{ App\Utils\Converter::format_in_bdt($totalDoQtyAsBaseUnit) }}) =
                            @endif

                            {{ App\Utils\Converter::format_in_bdt($totalDoQtyAsMultiplier) }}
                        </th>

                        <th class="text-end text-black" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($do->total_delivered_qty) }}
                        </th>
                        <th class="text-end text-black" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($do->do_total_left_qty) }}
                        </th>
                        <th class="text-end text-black" style="font-size:11px!important;">---</th>

                        @if ($defaultLayout->product_discount)
                            <th class="text-end text-black" style="font-size:11px!important;">---</th>
                        @endif

                        @if ($defaultLayout->product_tax)
                            <th class="text-end text-black" style="font-size:11px!important;">---</th>
                        @endif
                        <th class="text-end text-black" style="font-size:11px!important;">---</th>

                        <th class="text-end text-black" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($do->net_total_amount) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if (count($do->saleProducts) > 15)
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

        <div class="row">
            <div class="col-md-6">
                @if ($defaultLayout->show_total_in_word == 1)
                    <p style="text-transform: uppercase; font-size:11px!important;"><strong>@lang('menu.in_word') : </strong> {{ App\Utils\Converter::format_in_text($do->total_payable_amount) }} ({{ json_decode($generalSettings->business, true)['currency'] }}) @lang('menu.only').</p>
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
                            <p style="font-size:11px!important;">@lang('short.bank') : {{ $defaultLayout->bank_name }}</p>
                        @endif

                        @if ($defaultLayout->bank_branch)
                            <p style="font-size:11px!important;">@lang('short.branch') : {{ $defaultLayout->bank_branch }}</p>
                        @endif
                    </div>
                @endif

                <div class="price_adjustment_note mt-2">
                    <p style="font-size:11px!important;"><strong>@lang('menu.price_adjustment_note') :</strong> {{ $do->price_adjustment_note }}</p>
                </div>

                <div class="order_note mt-1">
                    <p style="font-size:11px!important;"><strong>@lang('menu.comment') :</strong> {{ $do->comment }}</p>
                </div>

                <div class="order_note mt-1">
                    <p style="font-size:11px!important;"><strong>@lang('menu.note') :</strong> {{ $do->sale_note }}</p>
                </div>

                <div class="shipping_address mt-1">
                    <p style="font-size:11px!important;"><strong>@lang('menu.payment_note') :</strong> {{ $do->payment_note }}</p>
                </div>

                <div class="shipping_address mt-1">
                    <p style="font-size:11px!important;"><strong>@lang('menu.shipping_address') :</strong> {{ $do->shipping_address }}</p>
                </div>

                <div class="receiver_phone mt-1">
                    <p style="font-size:11px!important;"><strong>@lang('menu.receiver_phone') : </strong> {{ $do->receiver_phone }}</p>
                </div>
            </div>

            <div class="col-6">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.rate_type') : </strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ $do->all_price_type }}{{ $do->all_price_type == 'PR' ? '(' . App\Utils\Converter::format_in_bdt($totalPrAmount) . ')' : '' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('short.total_do_qty') :</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($do->total_do_qty) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.total_delivered_qty') :</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($do->total_delivered_qty) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.total_left_qty') :</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($do->do_total_left_qty) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($do->net_total_amount) }}</td>
                        </tr>

                        @if ($do->order_discount > 0)
                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.order_discount') :
                                    {{ json_decode($generalSettings->business, true)['currency'] }}</strong>
                                </td>
                                <td class="order_discount text-end" style="font-size:11px!important;">
                                    @if ($do->order_discount_type == 1)
                                        {{ App\Utils\Converter::format_in_bdt($do->order_discount_amount) }}(Fixed)
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($do->order_discount_amount) }} ({{ $do->order_discount }}%)
                                    @endif
                                </td>
                            </tr>
                        @endif

                        @if ($do->order_tax_percent > 0)
                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.order_tax') :
                                    {{ json_decode($generalSettings->business, true)['currency'] }}</strong>
                                </td>

                                <td class="order_tax text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($do->order_tax_amount) }}
                                    ({{ $do->order_tax_percent }} %)
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.shipment_cost') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                            <td class="shipment_charge text-end">{{ App\Utils\Converter::format_in_bdt($do->shipment_charge) }}</td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.total_ordered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                            <td class="total_payable text-end">{{ App\Utils\Converter::format_in_bdt($do->total_payable_amount) }}</td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.received_amount') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                            <td class="total_payable text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($do->paid) }}</td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.current_balance') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                            <td class="text-end fw-bold" style="font-size:11px!important;">
                                @php
                                    $accountUtil = new App\Utils\AccountUtil();
                                    $amounts = $accountUtil->accountClosingBalance($do->customer_account_id, $do->sr_user_id);
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
                <p style="display: inline; border-top: 1px solid black; padding:0px 11px; font-weight: 600;">@lang('menu.prepared_by')</p>
            </div>

            <div class="col-4 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 11px; font-weight: 600;">@lang('menu.checked_by')</p>
            </div>

            <div class="col-4 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 11px; font-weight: 600;">@lang('menu.authorize_by')</p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="invoice_notice">
                    <p>{!! $defaultLayout->invoice_notice ? '<strong>Attention : </strong>' . $defaultLayout->invoice_notice : '' !!}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="footer_text text-center">
                    <p>{{ $defaultLayout->footer_text }}</p>
                </div>
            </div>
        </div><br>

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

