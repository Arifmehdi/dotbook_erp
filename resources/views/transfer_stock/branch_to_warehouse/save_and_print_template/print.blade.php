@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<div class="transfer_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                        <h6 class="bill_name">@lang('menu.transfer_stock_details') (To Branch)</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.b_location') (From) : </strong></li>
                        <li><strong>@lang('menu.name') :</strong> {{ json_decode($generalSettings->business, true)['shop_name'].'' }}</li>
                        <li><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</li>
                        <li><strong>@lang('menu.address') : </strong>
                            {{ json_decode($generalSettings->business, true)['address'] }}
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.warehouse') (To) : </strong></li>
                        <li><strong>@lang('menu.name') :</strong> {{ $transfer->warehouse->warehouse_name.'/'.$transfer->warehouse->warehouse_code }}</li>
                        <li><strong>@lang('menu.phone') : </strong>{{ $transfer->warehouse->phone }}</li>
                        <li><strong>@lang('menu.address') : </strong> {{ $transfer->warehouse->address }}</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled float-end">
                        <li><strong>@lang('menu.date') : </strong> {{ $transfer->date }}</li>
                        <li><strong>@lang('menu.reference_id') : </strong> {{ $transfer->invoice_id }}</li>
                        <li><strong>@lang('menu.status') : </strong>
                            @if ($transfer->status == 1)
                            @lang('menu.pending')
                            @elseif($transfer->status == 2)
                            @lang('menu.partial')
                            @elseif($transfer->status == 3)
                            @lang('menu.completed')
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th class="text-startx">@lang('menu.sl')</th>
                            <th class="text-startx">@lang('menu.product')</th>
                            <th class="text-startx">@lang('menu.unit')</th>
                            <th class="text-startx">@lang('menu.quantity')</th>
                            <th class="text-startx">@lang('menu.unit')</th>
                            <th class="text-startx">@lang('menu.receive_qty')</th>
                            <th class="text-startx">@lang('menu.sub_total')</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="transfer_print_product_list">
                    @foreach ($transfer->transfer_products as $transfer_product)
                        <tr>
                            <td class="text-start">{{ $loop->index + 1 }}</td>
                            @php
                                $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                            @endphp
                            <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                            <td class="text-start">{{ $transfer_product->unit_price}}</td>
                            <td class="text-start">{{ $transfer_product->quantity }}</td>
                            <td class="text-start">{{ $transfer_product->unit }}</td>
                            <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                            <td class="text-start">{{ $transfer_product->subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-start" colspan="6"><strong>@lang('menu.net_total_amount') :</strong></td>
                        <td class="text-start" colspan="2">{{ $transfer->net_total_amount }}</td>
                    </tr>

                    <tr>
                        <th class="text-startx" colspan="6">@lang('menu.shipping_charge')</th>
                        <td class="text-start" colspan="2">{{ $transfer->shipping_charge }}</td>
                    </tr>

                    <tr>
                        <th class="text-startx" colspan="6">@lang('menu.grand_total')</th>
                        @php
                            $grandTotal = $transfer->net_total_amount  + $transfer->shipping_charge;
                        @endphp
                        <td class="text-start" colspan="2">{{ bcadd($grandTotal, 0, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br>

        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>@lang('menu.receivers_signature')</strong></p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>@lang('menu.signature_of_authority')</strong></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4 text-center">
                <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transfer->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p class="p-0 m-0">{{ $transfer->invoice_id }}</b></small>
                @if (config('company.print_on_others'))
                    <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
                @endif
            </div>
        </div>
    </div>
</div>
