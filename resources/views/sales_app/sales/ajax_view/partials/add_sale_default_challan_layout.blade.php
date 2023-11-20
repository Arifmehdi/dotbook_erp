@php
    $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
@endphp
<div class="challan_print_template d-none">
    <style>
        @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}

        h6 { font-size: 16px; }
        p {  font-size: 14px; }
        td {  color: black; }
    </style>
    <div class="details_area">
        @if ($defaultLayout->is_header_less == 0)
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

                    <p><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>

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
                    <h5 style="text-transform: uppercase;">{{ $defaultLayout->challan_heading }}</h5>
                </div>
            </div>
        @endif

        @if ($defaultLayout->is_header_less == 1)
            @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                <br/>
            @endfor
        @endif

        <div class="purchase_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>@lang('menu.customer'): </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                        </li>

                        @if ($defaultLayout->customer_phone)
                            <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}</li>
                        @endif

                        @if ($defaultLayout->customer_address)
                            <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4 text-center">
                    @if ($defaultLayout->is_header_less == 1)
                        <h5>{{ $defaultLayout->challan_heading }}</h5>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $sale->invoice_id }}</p>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        @if ($sale?->do)

                            <li style="font-size:11px!important;"><strong>@lang('menu.do_id') : </strong> {{ $sale?->do?->do_id }}</li>
                            <li style="font-size:11px!important;"><strong> @lang('short.delivery_date') : </strong> {{ $sale->do ? date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->do->do_date)) : '' }}</li>
                        @endif
                        <li style="font-size:11px!important;"><strong>@lang('menu.challan_no') : </strong> {{ $sale->invoice_id }}</li>
                        <li style="font-size:11px!important;"><strong> @lang('menu.date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }} </li>
                        <li style="font-size:11px!important;"><strong> @lang('menu.sr') : </strong> {{ __('menu.sr').' '. $sale?->sr?->prefix . ' ' . $sale?->sr?->name . ' ' . $sale?->sr?->last_name }}</li>
                        <li style="font-size:11px!important;"><strong> @lang('menu.created_by') : </strong> {{ $sale?->saleBy?->prefix . ' ' . $sale?->saleBy?->name . ' ' . $sale?->saleBy?->last_name }} </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-start" style="font-size:11px!important;">@lang('menu.serial')</th>
                        <th class="text-start" style="font-size:11px!important;">@lang('menu.description')</th>
                        <th class="text-start" style="font-size:11px!important;">@lang('menu.unit')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.quantity')</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($customerCopySaleProducts as $saleProduct)
                        <tr>
                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}.</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $saleProduct->p_name }}

                                @if ($saleProduct->product_variant_id)
                                    -{{ $saleProduct->variant_name }}
                                @endif
                                {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $saleProduct->description . '</small>' : '' !!}
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ $saleProduct->unit_code_name }}</td>
                            @php
                                $baseUnitMultiplier = $saleProduct?->base_unit_multiplier ? $saleProduct?->base_unit_multiplier : 1;
                                $soldQty = $saleProduct->quantity / $baseUnitMultiplier;
                            @endphp
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($soldQty) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div><br><br>

        @if (count($sale->saleProducts) > 11)
            <div class="row page_break">
                <div class="col-md-12 text-end">
                    <h6><em>@lang('menu.dontinued_to_this_next_page')</em></h6>
                </div>
            </div>
        @endif

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
        </div><br>

        <div class="row">
            <div class="col-md-12">
                <div class="footer_text text-center">
                    <span>{{ $defaultLayout->footer_text }}</span>
                </div>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-center">
                    <small>@lang('menu.print_date') :
                        {{ date(json_decode($generalSettings->business, true)['date_format']) }}
                    </small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_sale'))
                        <small class="d-block">@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd') .</strong></small>
                    @endif
                </div>

                <div class="col-4 text-center">
                    <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
