@php
    $dateFormat = json_decode($generalSettings->business, true)['date_format'];
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<!-- Details Modal -->
<div class="modal-dialog col-65-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">
                Weight Details For Sales | @lang('menu.do_id') : {{ $weight->do ? $weight->do->do_id : '' }} | @lang('menu.invoice_id') : <strong>{{ $weight->reserve_invoice_id }} </strong>
            </h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-6">
                    <ul class="list-unstyled">
                        <li>
                            <strong>@lang('menu.date') : </strong>
                            {{ date($dateFormat.' '.$timeFormat, strtotime($weight->created_at)) }}
                        </li>

                        <li><strong>@lang('menu.vehicle_no') : </strong>{{ $weight->do_car_number }}</li>
                        <li><strong>@lang('menu.driver_name') : </strong>{{ $weight->do_driver_name }}</li>
                        <li><strong>@lang('menu.driver_phone') : </strong>{{ $weight->do_driver_phone }}</li>
                    </ul>
                </div>

                <div class="col-lg-6">
                    <ul class="list-unstyled">
                        <li>
                            <strong> @lang('menu.do_id') : </strong> {{ $weight->do ? $weight->do->do_id : '' }}
                        </li>

                        <li>
                            <strong>@lang('menu.invoice_id') : </strong> {{ $weight->reserve_invoice_id }}
                        </li>

                        <li>
                            <strong> 1st W/t By : </strong>
                            {{ $weight->firstWeightedBy ? $weight->firstWeightedBy->prefix . ' ' . $weight->firstWeightedBy->name . ' ' . $weight->firstWeightedBy->last_name : 'N/A' }}
                        </li>

                        <li>
                            <strong> 2nd W/t By : </strong>
                            {{ $weight->secondWeightedBy ? $weight->secondWeightedBy->prefix . ' ' . $weight->secondWeightedBy->name . ' ' . $weight->secondWeightedBy->last_name : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="with_list_table pt-3 pb-3">
                        <table class="table modal-table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>1st</strong> Weight</td>
                                    <td><strong>= {{ App\Utils\Converter::format_in_bdt($weight->first_weight) }}</strong></td>
                                    <td>{{ date($dateFormat.' '.$timeFormat, strtotime($weight->created_at)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>2nd</strong> Weight</td>
                                    <td><strong>= {{ App\Utils\Converter::format_in_bdt($weight->second_weight)}}</strong></td>
                                    <td>{{ $weight->second_weight > 0 ? date($dateFormat.' '.$timeFormat, strtotime($weight->updated_at)) : '' }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>@lang('menu.net_weight')</th>
                                    <th>= {{  App\Utils\Converter::format_in_bdt($weight->second_weight - $weight->first_weight) }}</th>
                                    <th>{{ $weight->second_weight > 0 ? date($dateFormat.' '.$timeFormat, strtotime($weight->updated_at)) : '' }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0 me-2">@lang('menu.close')</button>
            <a href="{{ route('sales.delivery.print.weight', $weight->id) }}" id="printBtn" class="btn btn-sm btn-success m-0">@lang('menu.print_weight')</a>
        </div>
    </div>
</div>
<!-- Details Modal End-->