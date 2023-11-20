<style>
    .input-group-text {
        padding: 0px 8px !important;
    }

    .input-group-prepend {
        background: white !important;
    }
</style>
<div class="modal-dialog five-col-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel"> @lang('menu.add') @lang('menu.opening_stock')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <form id="update_opening_stock_form" action="{{ route('products.opening.stock.save.add.or.update') }}" method="POST">
            @csrf
            <div class="modal-body" id="opening_stock_view">
                <div class="card mt-3">
                    <div class="card-header">
                        <p class="m-0"><strong>@lang('menu.item') : </strong>{{ $product->name }}</p>
                    </div>
                    <div class="card_body">
                        <div class="product_stock_table_area">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-white">@lang('menu.stock_location')</th>
                                            <th class="text-white">@lang('menu.variant')</th>
                                            <th class="text-white">@lang('menu.quantity_remaining')</th>
                                            <th class="text-white">@lang('menu.unit_cost_exc_tax')</th>
                                            <th class="text-white">@lang('menu.sub_total')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($products as $pro)
                                            @php
                                                $__v_id = $pro->v_id ? $pro->v_id : null;
                                                $os = DB::table('product_opening_stocks')
                                                    ->where('product_id', $pro->p_id)
                                                    ->where('product_variant_id', $__v_id)
                                                    ->first();
                                            @endphp

                                            @if ($os)
                                                <tr>
                                                    @if (count($warehouses) > 0)
                                                        <td>
                                                            <input name="warehouse_count" value="YES" type="hidden" />
                                                            <select required class="form-control form-select" name="warehouse_ids[]">
                                                                <option value="">@lang('menu.select_warehouse')</option>
                                                                @foreach ($warehouses as $w)
                                                                    <option {{ $w->id == $os->warehouse_id ? 'SELECTED' : '' }} value="{{ $w->id }}">
                                                                        {{ $w->warehouse_name . '/' . $w->warehouse_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_warehouse_id"></span>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <p>
                                                                {!! json_decode($generalSettings->business, true)['shop_name'] !!}
                                                            </p>
                                                        </td>
                                                    @endif

                                                    <td class="text">{{ $pro->v_name ? $pro->v_name : 'N/A' }}</td>

                                                    <td>
                                                        <input type="hidden" name="product_ids[]" value="{{ $pro->p_id }}">
                                                        <input type="hidden" name="variant_ids[]" value="{{ $pro->v_id ? $pro->v_id : 'noid' }}">

                                                        <div class="input-group width-25 ml-2">
                                                            <input required type="number" step="any" name="quantities[]" class="form-control fw-bold" id="quantity" value="{{ $os->quantity }}" placeholder="@lang('menu.quantity')" autocomplete="off">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text input_group_text_custom text-dark">{{ $pro->u_code }}</span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <input required type="number" step="any" name="unit_costs_inc_tax[]" class="form-control fw-bold" id="unit_cost_inc_tax" value="{{ $os->unit_cost_inc_tax > 0 ? $os->unit_cost_inc_tax : '' }}" placeholder="Unit Cost Inc. Tax" autocomplete="off">
                                                    </td>

                                                    <td class="text">
                                                        <span id="span_subtotal" class="fw-bold">{{ $os->subtotal }}</span>
                                                        <input type="hidden" id="subtotal" name="subtotals[]" value="{{ $os->subtotal }}">
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    @if (count($warehouses) > 0)
                                                        <td>
                                                            <input name="warehouse_count" value="YES" type="hidden" />
                                                            <select required class="form-control form-select" name="warehouse_ids[]" autofocus>
                                                                <option value="">@lang('menu.select_warehouse')</option>
                                                                @foreach ($warehouses as $w)
                                                                    <option value="{{ $w->id }}">
                                                                        {{ $w->warehouse_name . '/' . $w->warehouse_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_warehouse_id"></span>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <p>
                                                                {!! json_decode($generalSettings->business, true)['shop_name'] !!}
                                                            </p>
                                                        </td>
                                                    @endif

                                                    <td class="text">{{ $pro->v_name ? $pro->v_name : 'N/A' }}</td>

                                                    <td>
                                                        <input type="hidden" name="product_ids[]" value="{{ $pro->p_id }}">
                                                        <input type="hidden" name="variant_ids[]" value="{{ $pro->v_id ? $pro->v_id : 'noid' }}">

                                                        <div class="input-group width-25 ml-2">
                                                            <input required type="number" step="any" name="quantities[]" class="form-control fw-bold" id="quantity" placeholder="@lang('menu.quantity')" autocomplete="off">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text input_group_text_custom text-dark">
                                                                    {{ $pro->u_code }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <input required type="number" step="any" name="unit_costs_inc_tax[]" class="form-control fw-bold" id="unit_cost_inc_tax" value="{{ $pro->v_cost_inc_tax ? ($pro->v_cost_inc_tax > 0 ? $pro->v_cost_inc_tax : '') : ($pro->p_cost_inc_tax > 0 ? $pro->p_cost_inc_tax : '') }}" placeholder="Unit Cost Inc. Tax" autocomplete="off">
                                                    </td>

                                                    <td class="text">
                                                        <span id="span_subtotal" class="fw-bold">0.00</span>
                                                        <input type="hidden" id="subtotal" name="subtotals[]" value="0.00">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button opening_stock_loading_button display-none"><i class="fas fa-spinner"></i></button>
                        <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).on('input', '#quantity', function() {

        var qty = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val() ? tr.find('#unit_cost_inc_tax').val() : 0;
        var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_inc_tax);
        tr.find('#span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
    });

    $(document).on('input', '#unit_cost_inc_tax', function() {

        var unit_cost_inc_tax = $(this).val() ? $(this).val() : 0;
        var tr = $(this).closest('tr');
        var qty = tr.find('#quantity').val() ? tr.find('#quantity').val() : 0;
        var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_inc_tax);
        tr.find('#span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
    });

    $('#update_opening_stock_form').on('submit', function(e) {
        e.preventDefault();

        $('.opening_stock_loading_button').show();
        var request = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                toastr.success(data);
                product_table.ajax.reload();
                refresh();
                $('.opening_stock_loading_button').hide();
                $('#openingStockModal').modal('hide');
            },
            error: function(err) {

                $('.opening_stock_loading_button').hide();

                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
