<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_warranty')/@lang('menu.guaranty')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="add_warranty_form" action="{{ route('product.warranties.store') }}">
                @csrf
                <div class="form-group row">
                    <div class="col-12">
                        <label><b>@lang('menu.name')</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control add_input"
                            data-next="warranty_type" id="warranty_name" placeholder="Enter Warranty/Guaranty Name" />
                        <span class="error error_warranty_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-4">
                        <label><b>@lang('menu.type')</b></label>
                        <select name="type" class="form-control form-select" id="warranty_type"
                            data-next="warranty_duration">
                            <option value="1">@lang('menu.warranty')</option>
                            <option value="2">@lang('menu.guaranty')</option>
                        </select>
                    </div>

                    <div class="col-lg-8">
                        <label><b>@lang('menu.duration')</b> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input required type="number" name="duration" class="form-control"
                                data-name="Warranty duration" id="warranty_duration" data-next="warranty_duration_type"
                                placeholder="@lang('menu.duration')">
                            <select name="duration_type" class="form-control form-select" id="warranty_duration_type"
                                data-next="warranty_description">
                                <option value="Months">@lang('menu.months')</option>
                                <option value="Days"> @lang('menu.days')</option>
                                <option value="Years">@lang('menu.years')</option>
                            </select>
                        </div>
                        <span class="error error_warranty_duration"></span>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.description')</b></label>
                    <input name="description" id="warranty_description" class="form-control" data-next="warranty_save" placeholder="@lang('menu.description')">
                </div>

                <div class="form-group pt-2">
                    <div class="d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button warranty_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="warranty_save" class="btn btn-sm btn-success float-end warranty_submit_button">@lang('menu.save')</button>
                            <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.warranty_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.warranty_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_warranty_form').on('submit', function(e) {
        e.preventDefault();

        $('.warranty_loading_button').show();
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
                $('.warranty_loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success('Warranty is added successfully');
                    $('#warrantyAddOrEditModal').modal('hide');
                    var warranty_id = $('#warranty_id').val();
                    var product_warranty_id = $('#product_warranty_id').val();

                    if (warranty_id != undefined) {

                        $('#warranty_id').append('<option value="' + data.id + '">' + data.name +
                            '</option>');
                        $('#warranty_id').val(data.id);

                        var nextId = $('#warranty_id').data('next');
                        $('#' + nextId).focus().select();
                    } else if (product_warranty_id != undefined) {

                        $('#product_warranty_id').append('<option value="' + data.id + '">' + data
                            .name + '</option>');
                        $('#product_warranty_id').val(data.id);

                        var nextId = $('#product_warranty_id').data('next');
                        $('#' + nextId).focus().select();
                    } else {

                        warranties_table.ajax.reload();
                    }
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.warranty_loading_button').hide();
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

                    $('.error_warranty_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
