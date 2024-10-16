    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-full-display">
          <div class="modal-content" >
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                  @lang('menu.send_stock_details')
              </h5>
              <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.warehouse')(From) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->warehouse->warehouse_name.'/'.$sendStock->warehouse->warehouse_code }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $sendStock->warehouse->phone }}</li>
                            <li><strong>@lang('menu.address') : </strong> {{ $sendStock->warehouse->address }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.b_location') (To) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ json_decode($generalSettings->business, true)['shop_name'] }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</li>
                            <li><strong>@lang('menu.address') : </strong> {{ json_decode($generalSettings->business, true)['address'] }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled float-right">
                            <li><strong>@lang('menu.date') : </strong> {{ $sendStock->date }}</li>
                            <li><strong>@lang('menu.reference_id') : </strong>{{ $sendStock->invoice_id }}</li>
                            <li><strong>@lang('menu.status') : </strong>
                                @if ($sendStock->status == 1)
                                <span class="badge bg-danger">@lang('menu.pending')</</span>
                                @elseif($sendStock->status == 2)
                                    <span class="badge bg-primary">@lang('menu.partial')</span>
                                @elseif($sendStock->status == 3)
                                <span class="badge bg-success">@lang('menu.completed')</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="" class="table table-sm modal-table">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-startx">@lang('menu.sl')</th>
                                    <th class="text-startx">@lang('menu.product')</th>
                                    <th class="text-startx">@lang('menu.unit')</th>
                                    <th class="text-startx">@lang('menu.quantity')</th>
                                    <th class="text-startx">@lang('menu.unit')</th>
                                    <th class="text-startx">@lang('menu.pending_qty')</th>
                                    <th class="text-startx">@lang('menu.received_qty')</th>
                                    <th class="text-startx">@lang('menu.sub_total')</th>
                                </tr>
                            </thead>
                            <tbody class="transfer_print_product_list">
                                @foreach ($sendStock->transfer_products as $transfer_product)
                                    <tr>
                                        <td class="text-start">{{ $loop->index + 1 }}</td>
                                        @php
                                            $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                                        @endphp
                                        <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                                        <td class="text-start">{{ $transfer_product->unit_price}}</td>
                                        <td class="text-start">{{ $transfer_product->quantity }}</td>
                                        <td class="text-start">{{ $transfer_product->unit }}</td>
                                        @php
                                            $panding_qty = $transfer_product->quantity - $transfer_product->received_qty;
                                        @endphp
                                        <td class="text-start"><b>{{ $panding_qty.' ('.$transfer_product->unit.')' }}</b></td>
                                        <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                                        <td class="text-start">{{ $transfer_product->subtotal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

              <hr class="p-0 m-0">
              <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>@lang('menu.receiver') @lang('menu.note') : </h6>
                        <p class="receiver_note">{{ $sendStock->receiver_note }}</p>
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

    <!-- Transfer print templete-->
    <div class="transfer_print_template d-none">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="heading text-center">
                            <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                            <small class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</small><br>
                            <small class="company_address">@lang('menu.phone') : {{ json_decode($generalSettings->business, true)['phone'] }}</small>
                            <h6 class="bill_name">@lang('menu.send_stock_invoice')</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sale_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.warehouse')(Form) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->warehouse->warehouse_name.'/'.$sendStock->warehouse->warehouse_code }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $sendStock->warehouse->phone }}</li>
                            <li><strong>@lang('menu.address') : </strong> {{ $sendStock->warehouse->address }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.b_location') (To) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ json_decode($generalSettings->business, true)['shop_name'].'' }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</li>
                            <li>
                                <strong>@lang('menu.address') : </strong> {{ json_decode($generalSettings->business, true)['address'] }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled float-right">
                            <<li><strong>@lang('menu.date') : </strong> {{ $sendStock->date }}</li>
                            <li><strong>@lang('menu.reference_id') : </strong>{{ $sendStock->invoice_id }}</li>
                            <li><strong>@lang('menu.status') : </strong>
                                @if ($sendStock->status == 1)
                                @lang('menu.pending')
                                @elseif($sendStock->status == 2)
                                @lang('menu.partial')
                                @elseif($sendStock->status == 3)
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
                                <th scope="col">@lang('menu.sl')</th>
                                <th scope="col">@lang('menu.product')</th>
                                <th scope="col">@lang('menu.unit')</th>
                                <th scope="col">@lang('menu.quantity')</th>
                                <th scope="col">@lang('menu.unit')</th>
                                <th scope="col">@lang('menu.pending_qty')</th>
                                <th scope="col">@lang('menu.received_qty')</th>
                                <th scope="col">@lang('menu.sub_total')</th>
                            </tr>
                        </tr>
                    </thead>
                    <tbody class="transfer_print_product_list">
                        @foreach ($sendStock->transfer_products as $transfer_product)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                @php
                                    $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                                @endphp
                                <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                                <td class="text-start">{{ $transfer_product->unit_price}}</td>
                                <td class="text-start">{{ $transfer_product->quantity }}</td>
                                <td class="text-start">{{ $transfer_product->unit }}</td>
                                @php
                                    $panding_qty = $transfer_product->quantity - $transfer_product->received_qty;
                                @endphp
                                <td class="text-start"><b>{{ $panding_qty.' ('.$transfer_product->unit.')' }}</b></td>
                                <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                                <td class="text-start">{{ $transfer_product->subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br><br>
            <div class="note">
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>@lang('menu.receivers_signature')</strong></h6>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6><strong>@lang('menu.signature_of_authority')</strong></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Transfer print templete end-->
