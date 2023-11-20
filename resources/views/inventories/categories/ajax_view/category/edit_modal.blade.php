<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_category')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_category_form" action="{{ route('product.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control " id="category_name" value="{{ $category->name }}" data-next="category_code" placeholder="Category Name" />
                    <span class="error error_category_name"></span>
                </div>

                <div class="form-group">
                    <label><b>@lang('menu.code') </b> <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" id="category_code" data-next="category_description" value="{{ $category->code }}" placeholder="Category Code" />
                    <span class="error error_category_code"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.description') </b> </label>
                    <input name="description" class="form-control" id="category_description" value="{{ $category->description }}" data-next="save_changes_btn" placeholder="Description">
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.photo') </b> <small class="text-danger"><b>@lang('menu.photo') size: 400px * 400px.</b> </small></label>
                    <input type="file" name="photo" class="form-control" id="category_phone" accept=".jpg, .jpeg, .png, .gif">
                    <span class="error error_category_photo"></span>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button category_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="save_changes_btn" class="btn btn-sm btn-success float-end category_submit_button">@lang('menu.save_change')</button>
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

        $('.category_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.category_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_category_form').on('submit', function(e) {
        e.preventDefault();

        $('.category_loading_button').show();
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
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success: function(data) {
                isAjaxIn = true;
                isAllowSubmit = true;
                $('.category_loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success(data);
                    $('#categoryAddOrEditModal').modal('hide');
                    category_table.ajax.reload(null, false);
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.category_loading_button').hide();
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

                    $('.error_category_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
