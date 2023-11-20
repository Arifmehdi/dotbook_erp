
{{-- @php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp --}}
<!-- Quotation print templete-->
    @php
        $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
    @endphp
    <div class="sale_print_template">
        <div class="details_area">
            @if ($defaultLayout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <h4>{{ $defaultLayout->header_text }}</h4>
                                <p>{{ $defaultLayout->sub_heading_1 }}</p>
                                <p>{{ $defaultLayout->sub_heading_2 }}</p>
                                <p>{{ $defaultLayout->sub_heading_3 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($defaultLayout->show_shop_logo == 1)
                                @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                    <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;font-weight: 600;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h1>{{ $defaultLayout->draft_heading }}</h1>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-right">
                                <h4 class="company_name">
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                </h4>

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
                            <li><strong>@lang('menu.customer'): </strong>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
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
                                <li><strong>@lang('menu.phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        @if ($defaultLayout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $defaultLayout->draft_heading }}</h5>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> Draft No : {{ $sale->invoice_id }}
                                </strong></li>
                            <li><strong> Date : {{ $sale->date }}</strong></li>
                            <li><strong> User : {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }}</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <tr>
                            <th class="text-startx">@lang('menu.description')</th>
                            <th class="text-startx">@lang('menu.quantity')</th>
                            <th class="text-startx">@lang('menu.unit')</th>
                            @if ($defaultLayout->product_discount)
                                <th class="text-startx">@lang('menu.discount')</th>
                            @endif
                            <th class="text-startx">@lang('menu.sub_total')</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($sale->saleProducts as $saleProduct)
                            <tr>
                                <td class="text-start">
                                    {{ $saleProduct->product->name }}
                                    @if ($saleProduct->variant)
                                        -{{ $saleProduct->variant->variant_name }}
                                    @endif
                                    @if ($saleProduct->variant)
                                        ({{ $saleProduct->variant->variant_code }})
                                    @else
                                        ({{ $saleProduct->product->product_code }})
                                    @endif
                                    {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $saleProduct->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $saleProduct->quantity }} ({{ $saleProduct->unit }}) </td>

                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $saleProduct->unit_price_inc_tax }} </td>

                                @if ($defaultLayout->product_discount)
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ $saleProduct->unit_discount_amount }}
                                    </td>
                                @endif

                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $saleProduct->subtotal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <table class="table report-table table-sm table-bordered print_table">
                        <tbody>
                            <tr>
                                <td class="text-start"><strong>@lang('menu.net_total_amount') :</strong></td>
                                <td class="net_total text-end">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale->net_total_amount }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('menu.order_discount') : </strong></td>
                                <td class="order_discount text-end">
                                <b>@if ($sale->order_discount_type == 1)
                                        {{ $sale->order_discount_amount }} (Fixed)
                                    @else
                                        {{ $sale->order_discount_amount }} ( {{ $sale->order_discount }}%)
                                    @endif</b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> @lang('menu.order_tax') : </strong></td>
                                <td class="order_tax text-end">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale->order_tax_amount }}
                                    ({{ $sale->order_tax_percent }} %)</b></td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong>@lang('menu.shipment_charge') : </strong></td>
                                <td class="shipment_charge text-end">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ number_format($sale->shipment_charge, 2) }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-start"><strong>@lang('menu.total_payable')  : </strong></td>
                                <td class="total_payable text-end">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ number_format($sale->total_payable_amount, 2) }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>@lang('menu.receiver_signature') </h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="details_area text-end">
                        <h6>@lang('menu.signature_of_authority') </h6>
                    </div>
                </div>
            </div><br><br>
            <div class="row">
                {{-- <div class="barcode text-center">
                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($sale->invoice_id, $generatorPNG::TYPE_CODE_128)) }}">
                </div> --}}
            </div><br><br>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <span>{{ $defaultLayout->footer_text }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Sale print templete end-->
