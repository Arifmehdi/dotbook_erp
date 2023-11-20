<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_unit')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_unit_form" action="{{ route('products.units.store') }}">
                @csrf
                <div class="form-group">
                    <label><b>@lang('menu.name')</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="unit_name" data-next="unit_short_name" placeholder="@lang('menu.unit_name')" />
                    <span class="error error_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.short_name') </b><span class="text-danger">*</span></label>
                    <input required type="text" name="short_name" class="form-control" id="unit_short_name" data-next="unit_as_a_multiplier_of_other_unit" placeholder="@lang('menu.short_name')" />
                    <span class="error error_unit_short_name"></span>
                </div>

                @if ($isAllowedMultipleUnit == 1)
                    <div class="form-group mt-1">
                        <label><b>@lang('menu.as_a_multiplier_of_other_unit')</b></label>
                        <select name="as_a_multiplier_of_other_unit" class="form-control form-select" id="unit_as_a_multiplier_of_other_unit" data-next="unit_base_unit_multiplier">
                            <option value="0">@lang('menu.no')</option>
                            <option value="1">@lang('menu.yes')</option>
                        </select>
                    </div>

                    <div class="display-none" id="multiple_unit_fields">
                        <div class="form-group mt-2 row g-2">
                            <div class="col-md-3">
                                <p class="fw-bold">@lang('menu.1') <span id="base_unit_name">@lang('menu.unit')</span>
                                </p>
                            </div>

                            <div class="col-md-1">
                                <p class="fw-bold"> = </p>
                            </div>

                            <div class="col-md-4">
                                <input type="text" name="base_unit_multiplier" class="form-control fw-bold" id="unit_base_unit_multiplier" data-next="unit_base_unit_id" placeholder="@lang('menu.amount_of_base_unit')" />
                                <span class="error error_unit_base_unit_multiplier"></span>
                            </div>

                            <div class="col-md-4">
                                <select name="base_unit_id" class="form-control select2 form-select" id="unit_base_unit_id" data-next="unit_save">
                                    <option value="">@lang('menu.select_base_unit')</option>
                                    @foreach ($baseUnits as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->code_name }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_unit_base_unit_id"></span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group pt-2">
                    <div class="d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button unit_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="button" id="unit_save" class="btn btn-sm btn-success unit_submit_button float-end">@lang('menu.save')</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
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

            if ($('#' + nextId).val() == undefined) {

                $('#unit_save').focus().select();
                return;
            }

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

                $('#unit_save').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.unit_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_unit_form').on('submit', function(e) {
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

                    toastr.success('Unit is added successfully');
                    $('#unitAddOrEditModal').modal('hide');
                    var unit_id = $('#unit_id').val();
                    var product_unit_id = $('#product_unit_id').val();
                    if (unit_id != undefined) {

                        $('#unit_id').append('<option value="' + data.id + '">' + data.name + ' (' +
                            data.code_name + ')' + '</option>');
                        $('#unit_id').val(data.id);

                        var nextId = $('#unit_id').data('next');
                        $('#' + nextId).focus().select();
                    } else if (product_unit_id != undefined) {

                        $('#product_unit_id').append('<option value="' + data.id + '">' + data
                            .name + ' (' + data.code_name + ')' + '</option>');
                        $('#product_unit_id').val(data.id);

                        var nextId = $('#product_unit_id').data('next');
                        $('#' + nextId).focus().select();
                    } else {

                        $('.unitTable').DataTable().ajax.reload();
                    }
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
