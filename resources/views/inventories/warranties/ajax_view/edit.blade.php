<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_warranty')/@lang('menu.guaranty')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_warranty_form" action="{{ route('product.warranties.update', $warranty->id) }}">
                @csrf
                <div class="form-group">
                    <label><b>@lang('menu.name')</b></label> <span class="text-danger">*</span>
                    <input required type="text" name="name" class="form-control" data-next="warranty_type" id="warranty_name" placeholder="@lang('menu.name')" value="{{ $warranty->name }}" />
                    <span class="error error_warranty_name"></span>
                </div>

                <div class="row mt-1">
                    <div class="col-md-4">
                        <label><b>@lang('menu.type')</b></label>
                        <select required name="type" class="form-control form-select" id="warranty_type" data-next="warranty_duration">
                            <option {{ $warranty->type == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.warranty')
                            </option>
                            <option {{ $warranty->type == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.guaranty')
                            </option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label><b>@lang('menu.duration')</b></label> <span class="text-danger">*</span>
                        <div class="input-group">
                            <input required type="number" name="duration" class="form-control" data-next="warranty_duration_type" id="warranty_duration" value="{{ $warranty->duration }}">

                            <select required name="duration_type" class="form-control form-select" id="warranty_duration_type" data-next="warranty_description">
                                <option {{ $warranty->duration_type == 'Months' ? 'SELECTED' : '' }} value="Months">
                                    @lang('menu.months')</option>
                                <option {{ $warranty->duration_type == 'Days' ? 'SELECTED' : '' }} value="Days">
                                    @lang('menu.days')</option>
                                <option {{ $warranty->duration_type == 'Years' ? 'SELECTED' : '' }} value="Years">
                                    @lang('menu.years')</option>
                            </select>
                        </div>

                        <span class="error error_warranty_duration"></span>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label><b>@lang('menu.description')</b></label>
                    <input name="description" id="warranty_description" class="form-control" value="{{ $warranty->description }}" data-next="warranty_save_changes" placeholder="@lang('menu.description')">
                </div>

                <div class="form-group pt-2">
                    <div class="d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button warranty_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="button" id="warranty_save_changes" class="btn btn-sm btn-success float-end warranty_submit_button">@lang('menu.save_changes')</button>
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
        }
    });

    $('#edit_warranty_form').on('submit', function(e) {
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

                    toastr.success(data);
                    $('#warrantyAddOrEditModal').modal('hide');
                    warranties_table.ajax.reload();
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

                    $('.error_warranty_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
