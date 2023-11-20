@php
    $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
@endphp
<div class="challan_print_template">
    <style>
        @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}

        h6 { font-size: 16px; }
        p { font-size: 14px; }
        td { color: black; }
    </style>
    <div class="details_area">
        @if ($defaultLayout->is_header_less == 0)

            <div class="row">
                <div class="col-md-12">
                    <div class="header_text text-center">
                        <h4>{{ $defaultLayout->header_text }}</h4>
                        <p>{{ $defaultLayout->sub_heading_1 }}<p/>
                        <p>{{ $defaultLayout->sub_heading_2 }}<p/>
                        <p>{{ $defaultLayout->sub_heading_3 }}<p/>
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

                    <p style="text-transform: uppercase;">
                        <strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong>
                    </p>
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

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h4 class="text-uppercase">{{ $defaultLayout->challan_heading }}</h4>
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
                        <li><strong>@lang('menu.customer'): </strong> {{ $sale?->customer?->name }}
                        </li>
                        @if ($defaultLayout->customer_address)
                            <li><strong>@lang('menu.address') : </strong> {{ $sale?->customer?->address  }}
                            </li>
                        @endif

                        @if ($defaultLayout->customer_phone)
                            <li><strong>@lang('menu.phone') : </strong> {{ $sale?->customer?->phone }}</li>
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
                        <li><strong>@lang('menu.challan_no') : </strong> {{ $sale->invoice_id }}</li>
                        <li><strong> {{ __('Date') }} : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }} </li>
                        <li><strong> @lang('menu.sr') : </strong> {{ $sale?->sr?->prefix . ' ' . $sale?->sr?->name . ' ' . $sale?->sr?->last_name }} </li>
                        <li><strong> @lang('menu.created_by') : </strong> {{ $sale?->saleBy?->prefix . ' ' . $sale?->saleBy?->name . ' ' . $sale?->saleBy?->last_name }} </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                    <tr>
                        <th class="text-startx">@lang('menu.serial')</th>
                        <th class="text-startx">@lang('menu.description')</th>
                        <th class="text-startx">@lang('menu.unit')</th>
                        <th class="text-startx">@lang('menu.quantity')</th>
                    </tr>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($customerCopySaleProducts as $saleProduct)
                        <tr>
                            <td class="text-start">{{ $loop->index + 1 }}</td>
                            <td class="text-start">
                                {{ $saleProduct->p_name }}

                                @if ($saleProduct->product_variant_id)
                                    -{{ $saleProduct->variant_name }}
                                @endif
                                {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $saleProduct->description . '</small>' : '' !!}
                            </td>
                            <td class="text-start">{{ $saleProduct->unit }}</td>
                            <td class="text-start">{{ $saleProduct->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div><br><br>

        @if (count($sale->saleProducts) > 11)
            <div class="row page_break">
                <div class="col-md-12 text-end">
                    <h6><em>@lang('menu.dontinued_to_this_next_page')....</em></h6>
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
                        <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
                    @endif
                </div>

                <div class="col-4 text-center">
                    <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
