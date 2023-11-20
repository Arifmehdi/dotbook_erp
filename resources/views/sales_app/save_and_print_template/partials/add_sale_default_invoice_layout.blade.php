@php
    $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
@endphp
@if ($defaultLayout->layout_design == 1)
    <div class="sale_print_template">
        <style>
            div#footer {position:fixed;bottom:27px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
            @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px;  margin-left: 10px;margin-right: 10px;}

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

                        <p class="company_address">
                            <b>{{ json_decode($generalSettings->business, true)['address'] }}</b>
                        </p>

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
                        <h5>{{ $defaultLayout->invoice_heading }}</h5>
                    </div>
                </div>
            @endif

            @if ($defaultLayout->is_header_less == 1)
                @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                    <br/>
                @endfor
            @endif

            <div class="purchase_and_deal_info mt-1">
                <div class="row">
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;" style="font-size:11px!important;"><strong>@lang('menu.customer') : </strong>
                                {{ $sale?->customer?->name }}
                            </li>

                            @if ($defaultLayout->customer_phone)
                                <li style="font-size:11px!important;" style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong> {{ $sale?->customer?->phone }}</li>
                            @endif

                            @if ($defaultLayout->customer_address)
                                <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong>{{ $sale?->customer?->address }}</li>
                            @endif

                            <li class="mt-1" style="font-size:11px!important;"><strong>@lang('menu.challan_no') : </strong> {{ $sale->do_to_inv_challan_no }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.challan_date') : </strong> {{ $sale->do_to_inv_challan_date }}</li>
                        </ul>
                    </div>

                    <div class="col-4 text-center">
                        @if ($defaultLayout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5 style="text-transform: uppercase;">
                                    {{ $sale->status == 1 ? $defaultLayout->invoice_heading : 'SALE ORDER' }}
                                </h5>
                            </div>
                        @endif

                        <img style="width: 170px; height:30px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    </div>

                    <div class="col-4">
                        <ul class="list-unstyled">
                            @if ($sale?->do)

                                <li style="font-size:11px!important;"><strong> @lang('menu.do_id') : </strong> {{ $sale?->do?->do_id }}</li>
                                <li style="font-size:11px!important;">
                                    <strong> @lang('short.delivery_date') : </strong> {{ $sale->do ? date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale?->do?->do_date)) : '' }}
                                </li>
                            @endif

                            <li style="font-size:11px!important;"><strong>@lang('menu.invoice_id') : </strong> {{ $sale->invoice_id }}</li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.invoice_date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date))  }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong> @lang('menu.sr') : </strong> {{ $sale?->sr?->prefix . ' ' . $sale?->sr?->name . ' ' . $sale?->sr?->last_name }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong> @lang('menu.created_by') : </strong> {{ $sale?->saleBy?->prefix . ' ' . $sale?->saleBy?->name . ' ' . $sale?->saleBy?->last_name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table mt-1">
                <table class="table report-table table-sm table-bordered print_table">
                    <thead>
                        <tr>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.sl')</th>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.description')</th>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.sold_quantity')</th>
                            @if (
                                $defaultLayout->product_w_type ||
                                $defaultLayout->product_w_duration ||
                                $defaultLayout->product_w_discription
                            )
                                <th class="text-start" style="font-size:11px!important;">@lang('menu.warranty')</th>
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
                        @endphp
                        @foreach ($customerCopySaleProducts as $saleProduct)
                            <tr>
                                <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $saleProduct->p_name }}

                                    @if ($saleProduct->product_variant_id)

                                        -{{ $saleProduct->variant_name }}
                                    @endif
                                    {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $saleProduct->description . '</small>' : '' !!}
                                </td>

                                @php
                                    $baseUnitMultiplier = $saleProduct?->base_unit_multiplier ? $saleProduct?->base_unit_multiplier : 1;
                                    $soldQty = $saleProduct->quantity / $baseUnitMultiplier;
                                @endphp

                                <td class="text-end" style="font-size:11px!important;">
                                    @if ($saleProduct?->base_unit_code_name)
                                        @php
                                            $isMultiplierUnitExists = 1;
                                        @endphp

                                        (<strong>{{ App\Utils\Converter::format_in_bdt($saleProduct->quantity) }}/{{ $saleProduct?->base_unit_code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($soldQty) }}/{{ $saleProduct?->unit_code_name }}
                                    @else

                                        {{ App\Utils\Converter::format_in_bdt($soldQty) }}/{{ $saleProduct?->unit_code_name }}
                                    @endif
                                </td>

                                @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                    <td class="text-start">
                                        @if ($saleProduct->warranty_id)
                                            {{ $saleProduct->w_duration . ' ' . $saleProduct->w_duration_type }}
                                            {{ $saleProduct->type == 1 ? 'Warranty' : 'Guaranty' }}
                                            {!! $defaultLayout->product_w_discription ? '<br><small class="text-muted">' . $saleProduct->w_description . '</small>' : '' !!}
                                        @else
                                            <strong>No</strong>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax * $baseUnitMultiplier) }} </td>

                                @php
                                    $showPrAmount = $saleProduct->price_type == 'PR' ? '('.$saleProduct->pr_amount.')' : '';
                                    $totalPrAmount += $soldQty * $saleProduct->pr_amount;
                                @endphp

                                <td class="text-end" style="font-size:11px!important;">{{ $saleProduct->price_type.$showPrAmount }} </td>

                                @if ($defaultLayout->product_discount)

                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount * $baseUnitMultiplier) }}
                                    </td>
                                @endif

                                @if ($defaultLayout->product_tax)

                                    <td class="text-end" style="font-size:11px!important;">{{ $saleProduct->unit_tax_percent }}%</td>
                                @endif

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax * $baseUnitMultiplier) }}</td>

                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (count($sale->saleProducts) > 15)
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
                        <p style="text-transform: uppercase; font-size:11px!important;"><strong>@lang('menu.in_word') : </strong> {{ App\Utils\Converter::format_in_text($sale->total_payable_amount) }} ({{ json_decode($generalSettings->business, true)['currency'] }}) @lang('menu.only').</p>
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

                    <div class="vehicle_details mt-2">
                        <table class="modal-table">
                            <tr>
                                <td style="font-size:11px!important;">@lang('menu.vehicle_no')</td>
                                <td style="font-size:11px!important;"> : {{ $sale?->weight?->do_car_number }}</td>
                            </tr>

                            <tr>
                                <td style="font-size:11px!important;">@lang('menu.driver_name')</td>
                                <td style="font-size:11px!important;"> : {{ $sale?->weight?->do_driver_name }}</td>
                            </tr>

                            <tr>
                                <td style="font-size:11px!important;">@lang('menu.driver_phone') </td>
                                <td style="font-size:11px!important;"> : {{ $sale?->weight?->do_driver_phone }}</td>
                            </tr>
                        </table>
                    </div>

                    <p class="p-0 m-0 mt-1" style="font-size:11px!important;"><strong>@lang('menu.sale_note') :</strong> {{ $sale->sale_note }}</p>
                    <p class="p-0 m-0 mt-1" style="font-size:11px!important;"><strong>@lang('menu.payment_note') :</strong> {{ $sale->payment_note }}</p>
                    <p class="p-0 m-0 mt-1" style="font-size:11px!important;"><strong>@lang('menu.shipping_address') :</strong> {{ $sale->shipping_address }}</p>
                    <p class="p-0 m-0 mt-1" style="font-size:11px!important;"><strong>{{ __('Receiver Phone') }} :</strong> {{ $sale->receiver_phone }}</p>
                </div>
                <div class="col-md-6">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.rate_type') : </strong></td>
                                <td class="text-end" style="font-size:11px!important;">{{ $sale->all_price_type }}{{ $sale->all_price_type == 'PR' ? '('.App\Utils\Converter::format_in_bdt($totalPrAmount).')' : '' }}</td>
                            </tr>

                            @if ($sale->delivery_order_id)

                                <tr>
                                    <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.total') @lang('menu.qty_weight') :</strong></td>
                                    <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($sale->total_sold_qty) }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.net_total_amount') :{{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}</td>
                            </tr>

                            @if (!$sale->delivery_order_id)

                                <tr>
                                    <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.sale_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                    <td class="order_discount text-end" style="font-size:11px!important;">
                                        @if ($sale->order_discount_type == 1)
                                            (@lang('menu.fixed')) {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                        @else
                                            ({{ $sale->order_discount }}%) {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.sale_tax'): {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                    <td class="order_tax text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                        ({{ $sale->order_tax_percent }}%)
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                    <td class="shipment_charge text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.total_invoice_amount') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.received_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.due') @lang('menu.on_invoice') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                    <td class="text-end" style="font-size:11px!important;">
                                        @if ($sale->due < 0)

                                            ({{ App\Utils\Converter::format_in_bdt(abs($sale->due)) }})
                                        @else

                                            {{ App\Utils\Converter::format_in_bdt(abs($sale->due)) }}
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-end">@lang('menu.curr_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-end">
                                    @php
                                        $accountUtil = new App\Utils\AccountUtil();
                                        $amounts = $accountUtil->accountClosingBalance($sale->customer_account_id, $sale->sr_user_id);
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
                <div class="row">
                    <div class="col-4 text-start">
                        <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_sale'))
                            <small>@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <style>@page{margin: 8px;}</style>
    <!-- Packing slip print templete-->
    <div class="sale_print_template">
        <div class="pos_print_template">
            <div class="row">
                <div class="company_info">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    @if ($defaultLayout->show_shop_logo == 1)
                                        @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                                        @else
                                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:black;font-weight: 600;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                                        @endif
                                    @endif
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span>{{ json_decode($generalSettings->business, true)['address'] }} </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span><b>@lang('menu.phone') :</b> {{ json_decode($generalSettings->business, true)['phone'] }} </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span><b>@lang('menu.email') :</b> {{ json_decode($generalSettings->business, true)['email'] }} </span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="customer_info mt-2">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <strong>@lang('menu.date'):</strong> <span>{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <strong>@lang('menu.inv_no'): </strong> <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <strong>@lang('menu.customer'):</strong> <span>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="description_area pt-2 pb-1">
                    <table class="w-100">
                        <thead class="t-head">
                            <tr>
                                <th class="text-start"> @lang('menu.description')</th>
                                <th class="text-center">@lang('menu.qty')</th>
                                <th class="text-center">@lang('menu.price')</th>
                                <th class="text-end">@lang('menu.total')</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($customerCopySaleProducts as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->product_variant_id ? ' '.$saleProduct->variant_name : '';
                                    @endphp
                                    <th class="text-start">{{ $loop->index + 1 }}. {{ $saleProduct->p_name.$variant }}</th>
                                    <th class="text-center">{{ (float)$saleProduct->quantity }}</th>
                                    <th class="text-center">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</th>
                                    <th class="text-end">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</th>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>

                <div class="amount_area">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-end">@lang('menu.net_total') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('menu.discount') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('menu.order_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            @if ($previous_due != 0)
                                <tr>
                                    <th class="text-end">@lang('menu.previous_due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">
                                        <span>
                                            {{ App\Utils\Converter::format_in_bdt($previous_due) }}
                                        </span>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-end"> Payable : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($total_payable_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> @lang('menu.paid') :  {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($paying_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('menu.change_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="total_paid text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($change_amount > 0 ? $change_amount : 0) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> Due : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($total_due) }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="footer_text_area mt-2">
                    <table class="w-100 ">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <span>
                                        {{ $defaultLayout->invoice_notice ?  $defaultLayout->invoice_notice : '' }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <br>
                                    <span>
                                        {{ $defaultLayout->footer_text ?  $defaultLayout->footer_text : '' }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="footer_area mt-1">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            @if (config('company.print_on_sale'))
                                <tr>
                                    <th class="text-center">
                                        <span>@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd') .</strong> </span>
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
