<!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                   @lang('menu.purchase_by_scale_details')  (@lang('menu.voucher_no') : <strong>{{ $purchaseByScale->voucher_no }} </strong>)
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.supplier') : </strong>{{ $purchaseByScale?->supplier?->name }}</li>
                            <li><strong>@lang('menu.address') : </strong>{{ $purchaseByScale?->supplier?->address }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $purchaseByScale?->supplier?->phone }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.weight') @lang('menu.voucher_no'). : </strong> {{ $purchaseByScale->voucher_no }}</li>
                            <li><strong>@lang('menu.date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchaseByScale->date)) }}</li>
                            <li><strong>@lang('menu.challan_no'). : </strong> {{ $purchaseByScale->challan_no }}</li>
                            <li><strong>@lang('menu.challan_date')  : </strong> {{ $purchaseByScale->challan_date }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.vehicle_no') : </strong> {{ $purchaseByScale->vehicle_number }}</li>
                            <li><strong>@lang('menu.driver_name') : </strong> {{ $purchaseByScale->driver_name }}</li>
                            <li><strong>@lang('menu.driver_phone') : </strong> {{ $purchaseByScale->driver_phone }}</li>
                            <li><strong>@lang('menu.created_by') : </strong> {{ $purchaseByScale?->createdBy?->prefix.' '.$purchaseByScale?->createdBy?->name.' '.$purchaseByScale?->createdBy?->last_name }}</li>
                        </ul>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">@lang('menu.item_name') :</th>
                                        <th scope="col">@lang('menu.item_weight_by_scale') :</th>
                                        <th scope="col">@lang('menu.wastage') :</th>
                                        <th scope="col">@lang('menu.item_net_weight') :</th>
                                        <th scope="col">@lang('menu.remark') :</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_print_product_list">
                                    @php
                                        $totalItemWeightByScala = 0;
                                        $totalIWastage = 0;
                                        $totalNetWeight = 0;
                                    @endphp
                                    @foreach ($purchaseByScale->weightsByProduct as $weight)
                                        <tr>
                                            <td>{{ $weight->product ? $weight->product->name.($weight->variant ? ' - ' . $weight->variant->variant_name : '') : 'Not-Available' }}</td>

                                            <td>{{ App\Utils\Converter::format_in_bdt($weight->differ_weight) }}/{{$weight?->product?->unit?->name}}</td>
                                            @php $totalItemWeightByScala += $weight->differ_weight; @endphp
                                            <td>{{ App\Utils\Converter::format_in_bdt($weight->wast) }}/{{$weight?->product?->unit?->name}}</td>
                                            @php $totalIWastage += $weight->wast; @endphp
                                            @php $net_weight = $weight->differ_weight - $weight->wast; @endphp
                                            <td>{{ App\Utils\Converter::format_in_bdt($net_weight) }}/{{$weight?->product?->unit?->name}}</td>
                                            @php $totalNetWeight += $net_weight; @endphp
                                            <td>{{ $weight->remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-end">@lang('menu.total') :</th>
                                        <th>{{ App\Utils\Converter::format_in_bdt($totalItemWeightByScala) }}</th>
                                        <th>{{ App\Utils\Converter::format_in_bdt($totalIWastage) }}</th>
                                        <th>{{ App\Utils\Converter::format_in_bdt($totalNetWeight) }}</th>
                                        <th>---</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                           <table class="table modal-table table-sm">
                               <tr>
                                   <th class="text-end">@lang('menu.net_weight_by_scale') (@lang('menu.first_weight') - @lang('menu.last_weight'))</th>
                                   <td class="text-end">
                                       = {{ App\Utils\Converter::format_in_bdt($purchaseByScale->net_weight) }} Kg
                                  </td>
                               </tr>

                               <tr>
                                   <th class="text-end">@lang('menu.net_weight_without_wastage')</th>
                                   <td class="text-end">
                                       = {{ App\Utils\Converter::format_in_bdt($totalNetWeight) }} Kg
                                  </td>
                               </tr>
                           </table>
                       </div>
                   </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="{{ route('purchase.by.scale.print.weight.challan', $purchaseByScale->id) }}" id="printBtn" class="btn btn-sm btn-success m-0 me-2">@lang('menu.print_challan')</a>
                <a href="{{ route('purchase.by.scale.print.weight', ['without_product', $purchaseByScale->id]) }}" id="printBtn" class="btn btn-sm btn-success m-0 me-2">@lang('menu.print_weight')</a>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->
