 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                   Random Weight Scale Details Weight ID  <strong>{{ $weightScale->weight_id }}</strong>
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>Client :</strong> {{ $weightScale->weightClient?->name }}</li>
                            <li><strong>@lang('menu.address')  :</strong> {{ $weightScale->weightClient?->address }}</li>
                            <li><strong>@lang('menu.phone')  :</strong> {{ $weightScale->weightClient?->phone }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.weight') Weight ID  :</strong> {{ $weightScale->weight_id }}</li>
                            <li><strong>@lang('menu.date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($weightScale->date)) }}</li>
                            <li><strong>Weight ID : </strong> {{ $weightScale->weight_id }}</li>
                            <li><strong>@lang('menu.challan_date') : </strong> {{ $weightScale->challan_date }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.vehicle_no')  :</strong> {{ $weightScale->vehicle_number }}</li>
                            <li><strong>@lang('menu.driver_name')  :</strong> {{ $weightScale->driver_name }}</li>
                            <li><strong>@lang('menu.driver_phone')  :</strong> {{ $weightScale->driver_phone }}</li>
                            <li><strong>Entered By :</strong> {{ $weightScale->createdBy ? $weightScale->createdBy->prefix.' '.$weightScale->createdBy->name.' '.$weightScale->createdBy->last_name : 'N/A' }}</li>
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
                                        <th scope="col">Client</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Gross Weight</th>
                                        <th scope="col">Tare Weight</th>
                                        <th scope="col">Net Weight</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_print_product_list">
                                    <tr>
                                        <td>{{ $weightScale->weightClient?->name }}</td>
                                        <td>{{ $weightScale->weightClient?->phone }}</td>
                                        <td>{{ $weightScale->product?->name }}</td>
                                        <td>{{ App\Utils\Converter::format_in_bdt($weightScale->gross_weight) }}</td>
                                        <td>{{ App\Utils\Converter::format_in_bdt($weightScale->tare_weight) }}</td>
                                        <td>{{ App\Utils\Converter::format_in_bdt($weightScale->net_weight) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-end">@lang('menu.total') :</th>
                                        <th>{{ App\Utils\Converter::format_in_bdt($weightScale->gross_weight) }}</th>
                                        <th>{{ App\Utils\Converter::format_in_bdt($weightScale->tare_weight) }}</th>
                                        <th>{{ App\Utils\Converter::format_in_bdt($weightScale->net_weight) }}</th>
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
                                   <th class="text-end">Net Weight By Scale (Gross Weight - Tare Weight)</th>
                                   <td class="text-end">
                                       = {{ App\Utils\Converter::format_in_bdt($weightScale->net_weight) }} Kg
                                  </td>
                               </tr>

                               <tr>
                                   <th class="text-end">Net Weight Without Wastage</th>
                                   <td class="text-end">
                                       = {{ App\Utils\Converter::format_in_bdt($weightScale->net_weight) }} Kg
                                  </td>
                               </tr>
                           </table>
                       </div>
                   </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="{{ route('random.scale.print.weight', [$weightScale->id]) }}" id="printWeightBtn" class="btn btn-sm btn-success m-0 me-2">@lang('menu.print_weight')</a>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->
