@php
    $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
@endphp
<div class="challan_print_template d-none">
    <style>
        @page {size:a4;margin-top: 0.8cm; /*margin-bottom: 35px;*/ margin-left: 4%;margin-right: 4%;}
    </style>
    <div class="details_area">
        @if ($defaultLayout->is_header_less == 0)
            <div class="heading_area">
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
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        @if ($defaultLayout->show_shop_logo == 1)
                            <img style="height: auto; width:200px;" src="{{asset('uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="middle_header_text text-center">
                            <h1>{{ $defaultLayout->challan_heading }}</h1>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="heading text-end">
                            <h3 class="company_name">
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}</h3>
                            <h6 class="company_address">
                                {{ json_decode($generalSettings->business, true)['address'] }}
                            </h6>

                            @if ($defaultLayout->branch_phone)
                                <h6>@lang('menu.phone') : {{ json_decode($generalSettings->business, true)['phone'] }}</h6>
                            @endif

                            @if ($defaultLayout->branch_email)
                                <h6>@lang('menu.email') : {{ json_decode($generalSettings->business, true)['email'] }}</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($defaultLayout->is_header_less == 1)
            @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                </br>
            @endfor
        @endif

        <div class="purchase_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.customer'): </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                        </li>
                        @if ($defaultLayout->customer_address)
                            <li><strong>@lang('menu.address') : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                            </li>
                        @endif

                        @if ($defaultLayout->customer_tax_no)
                            <li><strong>@lang('menu.tax_number') : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                            </li>
                        @endif

                        @if ($defaultLayout->customer_phone)
                            <li><strong>@lang('menu.phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}</li>
                        @endif
                    </ul>
                </div>
                <div class="col-lg-4">
                    @if ($defaultLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h5>{{ $defaultLayout->challan_heading }}</h5>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.challan_no') : </strong> {{ $sale->invoice_id }}
                            </li>
                        <li><strong> Date : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }} </li>
                        <li><strong> @lang('menu.user') : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
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
                    @foreach ($sale->saleProducts as $saleProduct)
                        <tr>
                            <td class="text-start">{{ $loop->index + 1 }}</td>
                            <td class="text-start">
                                {{ $saleProduct->product->name }}
                                @if ($saleProduct->variant)
                                    -{{ $saleProduct->variant->variant_name }}
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
            <div class="col-md-6">
                <div class="details_area">
                    <h6>@lang('menu.receiver_signature') </h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="details_area text-end">
                    <h6> @lang('menu.signature_of_authority') </h6>
                </div>
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
                    <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                </div>

                @if (config('company.print_on_sale'))
                    <div class="col-4 text-center">
                        <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                        <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
                    </div>
                @endif

                <div class="col-4 text-center">
                    <small>@lang('menu.print_time') :{{ date('h:i:s') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
