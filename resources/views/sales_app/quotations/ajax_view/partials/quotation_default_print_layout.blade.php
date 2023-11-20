@php
    $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
@endphp

<style>
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>

<div class="print_details d-none">
    <div class="details_area">
        @if ($defaultLayout->is_header_less == 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="header_text text-center">
                        <h5>{{ $defaultLayout->header_text }}</h5>
                        <p>{{ $defaultLayout->sub_heading_1 }}</p>
                        <p>{{ $defaultLayout->sub_heading_2 }}</p>
                        <p>{{ $defaultLayout->sub_heading_3 }}</p>
                    </div>
                </div>
            </div>

            <div class="row" style="border-bottom: 1px solid #000;">
                <div class="col-4">
                    @if ($defaultLayout->show_shop_logo == 1)

                        @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;font-weight: 600;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <h6 class="company_name" style="text-transform: uppercase;">
                        {{ json_decode($generalSettings->business, true)['shop_name'] }}
                    </h6>

                    <p class="company_address">
                        <b>{{ json_decode($generalSettings->business, true)['address'] }}</b>
                    </p>

                    <p>
                        @if ($defaultLayout->branch_email)
                            <strong>@lang('menu.email') : </strong><b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                        @endif

                        @if ($defaultLayout->branch_phone)
                            <strong>@lang('menu.phone') : </strong><b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-12 text-center">
                    <h6 style="text-transform: uppercase;">{{ $defaultLayout->quotation_heading }}</h6>
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
                        <li style="font-size:11px!important;">
                            <strong>@lang('menu.customer') : </strong> {{ $quotation->customer ? $quotation->customer->name : 'Walk-In-Customer' }}
                        </li>

                        @if ($defaultLayout->customer_address)
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.address') : </strong>{{ $quotation->customer ? $quotation->customer->address : '' }}
                            </li>
                        @endif

                        @if ($defaultLayout->customer_tax_no)
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.tax_number') : </strong> {{ $quotation->customer ? $quotation->customer->tax_number : '' }}
                            </li>
                        @endif

                        @if ($defaultLayout->customer_phone)
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.phone') : </strong> {{ $quotation->customer ? $quotation->customer->phone : '' }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-4 text-center">
                    @if ($defaultLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h5>{{ $defaultLayout->quotation_heading }}</h5>
                        </div>
                    @endif

                    <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($quotation->quotation_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong> @lang('menu.quotation_id') :</strong> {{ $quotation->quotation_id }}</li>
                        <li style="font-size:11px!important;"><strong> @lang('menu.date') : </strong> {{ $quotation->quotation_date }}</li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.sr') :</strong>
                            {{ $quotation?->sr?->prefix . ' ' . $quotation?->sr?->name . ' ' . $quotation->sr?->last_name }}
                        </li>

                        <li style="font-size:11px!important;"> <strong>@lang('menu.created_by') :</strong>
                            {{ $quotation?->quotationBy?->prefix . ' ' . $quotation?->quotationBy?->name . ' ' . $quotation?->quotationBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-start" style="font-size:11px!important;">@lang('menu.sl')</th>
                        <th class="text-start" style="font-size:11px!important;">@lang('menu.description')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.quantity')</th>
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
                    @php $totalPrAmount = 0; @endphp
                    @foreach ($customerCopySaleProducts as $quotationProduct)
                        <tr>
                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                            <td class="text-start" style="font-size:11px!important;">
                                {{ $quotationProduct->p_name }}

                                @if ($quotationProduct->product_variant_id)

                                    -{{ $quotationProduct->variant_name }}
                                @endif
                            </td>

                            @php
                                $baseUnitMultiplier = $quotationProduct?->base_unit_multiplier ? $quotationProduct?->base_unit_multiplier : 1;
                                $quotedQty = $quotationProduct->ordered_quantity / $baseUnitMultiplier;
                            @endphp

                            <td class="text-end" style="font-size:11px!important;">
                                @if ($quotationProduct?->base_unit_code_name)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($quotationProduct->ordered_quantity) }}/{{ $quotationProduct?->base_unit_code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($quotedQty) }}/{{ $quotationProduct?->unit_code_name }}
                                @else

                                    {{ App\Utils\Converter::format_in_bdt($quotedQty) }}/{{ $quotationProduct?->unit_code_name }}
                                @endif
                            </td>

                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotationProduct->unit_price_inc_tax * $baseUnitMultiplier) }}</td>

                            @php
                                $showPrAmount = $quotationProduct->price_type == 'PR' ? '('.App\Utils\Converter::format_in_bdt($quotationProduct->pr_amount).')' : '';
                                $totalPrAmount += $quotedQty * $quotationProduct->pr_amount
                            @endphp

                            <td class="text-end" style="font-size:11px!important;">{{ $quotationProduct->price_type.$showPrAmount }} </td>

                            @if ($defaultLayout->product_discount)
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($quotationProduct->unit_discount_amount * $baseUnitMultiplier) }}
                                </td>
                            @endif

                            @if ($defaultLayout->product_tax)
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($quotationProduct->unit_tax_percent) }}%
                                </td>
                            @endif

                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($quotationProduct->unit_price_inc_tax * $baseUnitMultiplier) }}
                            </td>

                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($quotationProduct->subtotal) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if (count($quotation->saleProducts) > 9)
            <br>
            <div class="row page_break">
                <div class="col-md-12 text-right">
                    <h6><em>@lang('menu.dontinued_to_this_next_page')....</em></h6>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-6">
                @if ($defaultLayout->show_total_in_word)
                    <p style="text-transform: uppercase; font-size:11px!important;"><strong>@lang('menu.in_word') : </strong> {{ App\Utils\Converter::format_in_text($quotation->total_payable_amount) }} ({{ json_decode($generalSettings->business, true)['currency'] }}) @lang('menu.only').</p>
                @endif
            </div>
            <div class="col-6">
                <table class="table modal-table table-sm table-sm">
                    <tbody>
                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.rate_type') : </th>
                            <td class="text-end" style="font-size:11px!important;">{{ $quotation->all_price_type }}{{ $quotation->all_price_type == 'PR' ? '('.App\Utils\Converter::format_in_bdt($totalPrAmount).')' : '' }}</td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotation->net_total_amount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.order_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>
                                    @if ($quotation->order_discount_type == 1)

                                        (Fixed) {{ App\Utils\Converter::format_in_bdt($quotation->order_discount_amount) }}
                                    @else

                                        ({{ $quotation->order_discount }}%) {{ App\Utils\Converter::format_in_bdt($quotation->order_discount_amount) }}
                                    @endif
                                </b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.order_tax'): {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                ({{ $quotation->order_tax_percent }} %) {{ App\Utils\Converter::format_in_bdt($quotation->order_tax_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($quotation->shipment_charge) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotation->total_payable_amount) }}</td>
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
