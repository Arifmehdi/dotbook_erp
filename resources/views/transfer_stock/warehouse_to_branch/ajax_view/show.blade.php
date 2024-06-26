@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-full-display">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            @lang('menu.transfer_details') (@lang('menu.invoice_id') : <strong>{{ $transfer->invoice_id }}</strong>)
          </h5>
          <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
            class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.warehouse') (From) : </strong></li>
                        <li><strong>@lang('menu.name') :</strong>{{ $transfer->warehouse->warehouse_name.'/'.$transfer->warehouse->warehouse_code }}</li>
                        <li><strong>@lang('menu.phone') : </strong>{{ $transfer->warehouse->phone }}</li>
                        <li><strong>@lang('menu.address') : </strong>{{ $transfer->warehouse->address }}</li>
                    </ul>
                </div>

                <div class="col-md-4 text-left">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.b_location') (To) : </strong></li>
                        <li><strong>@lang('menu.name') :</strong> {{ json_decode($generalSettings->business, true)['shop_name'].'' }}</li>
                        <li><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</li>
                        {{ json_decode($generalSettings->business, true)['address'] }}
                    </ul>
                </div>

                <div class="col-md-4 text-left">
                    <ul class="list-unstyled float-right">
                        <li><strong>@lang('menu.date') : </strong> {{ $transfer->date }}</li>
                        <li><strong>@lang('menu.reference_id') : </strong> {{ $transfer->invoice_id }}</li>
                        <li><strong>@lang('menu.status') : </strong>
                            @if ($transfer->status == 1)
                                <span class="badge bg-danger">@lang('menu.pending')</span>
                            @elseif($transfer->status == 2)
                                <span class="badge bg-primary">@lang('menu.partial')</span>
                            @elseif($transfer->status == 3)
                               <span class="badge bg-success">@lang('menu.completed')</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div><br>

            <div class="row">
                <div class="table-responsive">
                    <table id="" class="table modal-table table-striped table-sm">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="text-startx">@lang('menu.sl')</th>
                                <th class="text-startx">@lang('menu.item')</th>
                                <th class="text-startx">@lang('menu.unit')</th>
                                <th class="text-startx">@lang('menu.quantity')</th>
                                <th class="text-startx">@lang('menu.unit')</th>
                                <th class="text-startx">@lang('menu.pending_qty')</th>
                                <th class="text-startx">@lang('menu.received_qty')</th>
                                <th class="text-startx">@lang('menu.sub_total')</th>
                            </tr>
                        </thead>
                        <tbody class="transfer_print_product_list">
                            @foreach ($transfer->transfer_products as $transfer_product)
                                <tr>
                                    <td class="text-start">{{ $loop->index + 1 }}</td>
                                    @php
                                        $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                                        $sku = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_code.')' : $transfer_product->product->product_code;
                                    @endphp
                                    <td class="text-start">{{ $transfer_product->product->name.$variant.' ('.$sku.')' }}</td>
                                    <td class="text-start">{{ $transfer_product->unit_price}}</td>
                                    <td class="text-start">{{ $transfer_product->quantity }}</td>
                                    <td class="text-start">{{ $transfer_product->unit }}</td>
                                    @php
                                        $panding_qty = $transfer_product->quantity - $transfer_product->received_qty;
                                    @endphp
                                    <td class="text-start text-danger"><b>{{ $panding_qty.' ('.$transfer_product->unit.')' }}</b></td>
                                    <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                                    <td class="text-start">{{ $transfer_product->subtotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-6 offset-6">
                    <div class="table-responsive">
                        <table class="table modal-table table-sm">
                            <tr>
                                <th class="text-startx" colspan="6">@lang('menu.net_total_amount') :</th>
                                <td class="text-start" colspan="2">
                                    {{json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $transfer->net_total_amount }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-startx" colspan="6">@lang('menu.shipping_charge')</th>
                                <td class="text-start" colspan="2">
                                    {{json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $transfer->shipping_charge }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-startx" colspan="6">@lang('menu.grand_total')</th>
                                @php
                                    $grandTotal = $transfer->net_total_amount  + $transfer->shipping_charge;
                                @endphp
                                <td class="text-start" colspan="2">
                                    {{json_decode($generalSettings->business, true)['currency'] }}
                                    {{ bcadd($grandTotal, 0, 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div> <br>
          <hr class="p-0 m-0">
          <div class="row">
            <div class="col-md-6">
                <div class="details_area">
                    <h6>Additional Note : </h6>
                    <p>{{ $transfer->additional_note }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="details_area">
                    <h6>Receiver Note : </h6>
                    <p>{{ $transfer->receiver_note }}</p>
                </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            <button type="submit" class="btn btn-sm btn-success print_btn float-end">@lang('menu.print')</button>
        </div>
      </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Print Template-->
<div class="transfer_print_template d-none">
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
                        <li><strong>@lang('menu.warehouse') (Form) : </strong></li>
                        <li><strong>@lang('menu.name') :</strong> {{ $transfer->warehouse->warehouse_name.'/'.$transfer->warehouse->warehouse_code }}</li>
                        <li><strong>@lang('menu.phone') : </strong>{{ $transfer->warehouse->phone }}</li>
                        <li><strong>@lang('menu.address') : </strong> {{ $transfer->warehouse->address }}</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.b_location') (To) : </strong></li>
                        <li><strong>@lang('menu.name') :</strong> {{ json_decode($generalSettings->business, true)['shop_name'] }}</li>
                        <li><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</li>
                        {{ json_decode($generalSettings->business, true)['address'] }}
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled float-end">
                        <li><strong>@lang('menu.date') : </strong> {{ $transfer->date }}</li>
                        <li><strong>@lang('menu.reference_id') : </strong> {{ $transfer->invoice_id }}</li>
                        <li><strong>@lang('menu.status') : </strong>
                            @if ($transfer->status == 1)
                                <span class="badge bg-danger">@lang('menu.pending')</</span>
                            @elseif($transfer->status == 2)
                                <span class="badge bg-primary">@lang('menu.partial')</span>
                            @elseif($transfer->status == 3)
                               <span class="badge bg-success">@lang('menu.completed')</span>
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
                            <th class="text-startx">@lang('menu.item')</th>
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
        </div><br>


        <div class="row">
            <div class="col-md-6">
                <p><strong>@lang('menu.receivers_signature')</strong></p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>@lang('menu.signature_of_authority')</strong></p>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transfer->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p class="p-0 m-0"><b>{{ $transfer->invoice_id }}</b></p>
                @if (config('company.print_on_others'))
                    <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Print Template End-->
