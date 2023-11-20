<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_unit')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="edit_unit_form" action="{{ route('products.units.update', $unit->id) }}">
                @csrf
                <div class="form-group">
                    <label><b>@lang('menu.name')</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="unit_name" value="{{ $unit->name }}" data-next="unit_short_name" placeholder="@lang('menu.unit_name')" />
                    <span class="error error_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.short_name') </b><span class="text-danger">*</span></label>
                    <input required type="text" name="short_name" class="form-control" id="unit_short_name" value="{{ $unit->code_name }}" data-next="unit_as_a_multiplier_of_other_unit" placeholder="@lang('menu.short_name')" />
                    <span class="error error_unit_short_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.as_a_multiplier_of_other_unit')</b></label>
                    <select name="as_a_multiplier_of_other_unit" class="form-control form-select" id="unit_as_a_multiplier_of_other_unit" data-next="unit_base_unit_multiplier">
                        <option value="0">@lang('menu.no')</option>
                        <option {{ $unit->base_unit_id ? 'selected' : '' }} value="1">@lang('menu.yes')</option>
                    </select>
                </div>

                <div class="{{ $unit->base_unit_id ? '' : 'display-none' }}" id="multiple_unit_fields">
                    <div class="form-group mt-2 row g-2">
                        <div class="col-md-3">
                            <p class="fw-bold">@lang('menu.1') <span id="base_unit_name">{{ $unit->name }}</span>
                            </p>
                        </div>

                        <div class="col-md-1">
                            <p class="fw-bold"> = </p>
                        </div>

                        <div class="col-md-4">
                            <input type="text" name="base_unit_multiplier" class="form-control fw-bold" id="unit_base_unit_multiplier" value="{{ $unit->base_unit_multiplier }}" data-next="unit_base_unit_id" placeholder="@lang('menu.amount_of_base_unit')" />
                            <span class="error error_unit_base_unit_multiplier"></span>
                        </div>

                        <div class="col-md-4">
                            <select name="base_unit_id" class="form-control select2 form-select" id="unit_base_unit_id" data-next="unit_save_changes">
                                <option value="">@lang('menu.select_base_unit')</option>
                                @foreach ($baseUnits as $baseUnit)
                                    <option {{ $baseUnit->id == $unit->base_unit_id ? 'SELECTED' : '' }} value="{{ $baseUnit->id }}">{{ $baseUnit->name }} ({{ $baseUnit->code_name }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="error error_unit_base_unit_id"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group pt-2">
                    <div class="d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button unit_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="unit_save_changes" class="btn btn-sm btn-success unit_submit_button float-end">@lang('menu.save_changes')</button>
                            <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    $(document).on('input', '#unit_name', function(event) {

        var val = $(this).val();
        $('#base_unit_name').html(val);
    });

    $(document).on('change', '#unit_as_a_multiplier_of_other_unit', function(event) {

        if ($(this).val() == 1) {

            $('#multiple_unit_fields').show();
        } else {

            $('#multiple_unit_fields').hide();
        }
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.unit_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'unit_as_a_multiplier_of_other_unit' && $(
                    '#unit_as_a_multiplier_of_other_unit').val() == 0) {

                $('#unit_save_changes').focus();
                return;
            }
            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.unit_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_unit_form').on('submit', function(e) {
        e.preventDefault();

        $('.unit_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.unit_loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success(data);
                    $('#unitAddOrEditModal').modal('hide');
                    $('.unitTable').DataTable().ajax.reload();
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.unit_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_unit_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
