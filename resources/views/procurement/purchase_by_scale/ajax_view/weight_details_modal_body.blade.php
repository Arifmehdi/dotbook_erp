@php
    $serialArr = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th', '13th', '14th', '15th', '16th', '17th', '18th', '19th', '20th', '21th', '22th', '23th', '24th', '25th', '26th', '27th', '28th', '29th', '30th'];
@endphp
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content weight-details-modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.weight_details')</h6>
            @if ($isExistsAllDifferWeight == 1)
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            @endif
        </div>
        <div class="modal-body" id="weightDetailsModalBody">
            <form id="save_weight_details" action="{{ route('purchases.by.scale.save.weight.details', $purchaseByScaleId) }}" method="POST">
                @csrf
                <div class="hearder">
                    <ul class="list-unstyled list-group list-group-sm">
                        <li><strong>@lang('menu.supplier') :
                            </strong>{{ $purchaseByScale->sup_name }}/{{ $purchaseByScale->sup_phone }}</li>
                        <li><strong>@lang('menu.challan_no') : </strong>{{ $purchaseByScale->challan_no }}</li>
                        <li><strong>@lang('menu.vehicle_no') : </strong>{{ $purchaseByScale->vehicle_number }}</li>
                        <li><strong>@lang('menu.voucher_no') : </strong>{{ $purchaseByScale->voucher_no }}</li>
                    </ul>
                </div>
                <div class="form-group mt-2">
                    <table class="table report-table table-sm table-bordered print_table">
                        @php
                            $previousWeight = 0;
                            $previousIndex = 0;
                            $lastWeighIndex = '';
                            $lastWeight = count($weights) - 1;
                        @endphp
                        @foreach ($weights as $weight)
                            @php
                                $lastWeighIndex = $serialArr[$loop->index];
                            @endphp
                            @if ($loop->index == 0)
                                <tr>
                                    <td>
                                        <input type="hidden" name="weight_ids[]" id="weight_id" class="form-control" value="{{ $weight->id }}">
                                        <input type="hidden" name="weight_product_ids[]" id="weight_product_id" class="form-control">
                                        <input type="hidden" name="weight_variant_ids[]" id="weight_variant_id">
                                        <input type="hidden" name="differ_weights[]" id="differ_weights" class="form-control" value="0">
                                        <input type="hidden" name="wastes[]" class="form-control" value="0">
                                        <input type="hidden" name="remarks[]" name="remarks" class="form-control" value="">
                                        <strong>{{ $serialArr[$loop->index] }} @lang('menu.weight') (@lang('menu.gross_weight'))
                                        </strong>
                                    </td>
                                    <td colspan="4"><strong>=
                                            {{ App\Utils\Converter::format_in_bdt($weight->scale_weight) }}</strong> Kg
                                    </td>
                                </tr>

                                @php
                                    $previousWeight = $weight->scale_weight;
                                    $previousIndex = $loop->index;
                                @endphp
                            @else
                                <tr>
                                    <td><strong>{{ $serialArr[$loop->index] }} @lang('menu.weight')
                                            {{ $loop->index == $lastWeight ? '(' . __('menu.last_weight') . ')' : '' }}
                                        </strong></td>
                                    <td colspan="4"><strong>= {{ App\Utils\Converter::format_in_bdt($weight->scale_weight) }} </strong> Kg </td>
                                <tr>
                                    <td>
                                        <input type="hidden" name="weight_ids[]" id="weight_id" class="form-control" value="{{ $weight->id }}">
                                        <label><b>@lang('menu.difference_from') </b>
                                            <strong>{{ $serialArr[$previousIndex] }}</strong> <b>@lang('menu.weight')
                                            </b></label>
                                        <input readonly type="number" name="differ_weights[]" id="differ_weights" class="form-control fw-bold" value="{{ $previousWeight - $weight->scale_weight }}">
                                    </td>

                                    <td>
                                        <label><b>@lang('menu.remarkable_item')</b></label>
                                        <select name="weight_product_ids[]" id="weight_product_id" class="form-control form-select">
                                            <option data-variant_id="" value="">@lang('menu.select_item')</option>
                                            @foreach ($products as $product)
                                                <option {{ $product->product_id == $weight->product_id && $product->variant_id == $weight->variant_id ? 'SELECTED' : '' }} data-variant_id="{{ $product->variant_id }}" value="{{ $product->product_id }}">
                                                    {{ $product->product_name . ($product->variant_name ? ' - ' . $product->variant_name : '') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="weight_variant_ids[]" id="weight_variant_id" value="{{ $weight->variant_id }}">
                                    </td>

                                    <td>
                                        <label><b>@lang('menu.wastage')</b></label>
                                        <input type="number" name="wastes[]" class="form-control fw-bold" value="{{ $weight->wast ? $weight->wast : 0 }}">
                                    </td>

                                    <td>
                                        <label><b>@lang('menu.remark')</b></label>
                                        <input type="text" name="remarks[]" class="form-control" id="remarks" value="{{ $weight->remarks }}" placeholder="@lang('menu.remark')">
                                    </td>
                                </tr>
                                </tr>

                                @php
                                    $previousWeight = $weight->scale_weight;
                                    $previousIndex = $loop->index;
                                @endphp
                            @endif
                        @endforeach
                    </table>
                </div>

                <div class="footer">
                    <ul class="list-unstyled list-group list-group-sm">
                        <li><strong>@lang('menu.net_weight') (@lang('menu.first_weight') - @lang('menu.last_weight')) = </strong>
                            {{ App\Utils\Converter::format_in_bdt($purchaseByScale->net_weight) }} Kg</li>
                    </ul>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                            @if ($isExistsAllDifferWeight == 1)
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $('#save_weight_details').on('submit', function(e) {
        e.preventDefault();

        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.success(data.successMsg);
                var url = "{{ route('purchase.by.scale.weights.by.items', $purchaseByScaleId) }}";
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('.invoice_search_result').hide();
                        $('#item_list').empty();

                        $('#item_list').html(data);
                        calculateTotalAmount();
                        $('#weightDetailsModal').modal('hide');
                    },
                    error: function(err) {

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        } else if (err.status == 500) {

                            toastr.error(
                                'Server Error. Please contact to the support team.');
                            return;
                        }
                    }
                });
            },
            error: function(err) {

                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }
            }
        });
    });

    $(document).on('change', '#weight_product_id', function() {

        var variantId = $(this).find('option:selected').data('variant_id');
        $(this).closest('tr').find('#weight_variant_id').val(variantId);
    });
</script>
