<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_sub_category')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_sub_category_form" action="{{ route('product.subcategories.update', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mt-1">
                    <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control " value="{{ $subcategory->name }}" id="subcategory_name" data-next="subcategory_parent_category_id" placeholder="Sub category name" />
                    <span class="error error_subcategory_name"></span>
                </div>

                <div class="form-group">
                    <label><b>@lang('menu.parent_category') </b> <span class="text-danger">*</span></label>
                    <select name="parent_category_id" class="form-control form-select" id="subcategory_parent_category_id" data-next="subcategory_description">
                        @foreach ($categories as $row)
                            <option value="{{ $row->id }}" @if ($subcategory->parent_category_id == $row->id) selected @endif>
                                {{ $row->name }}</option>
                        @endforeach
                    </select>
                    <span class="error error_sub_e_parent_category_id"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.description') </b> </label>
                    <input name="description" class="form-control" id="subcategory_description" data-next="subcategory_save_changes_btn" value="{{ $subcategory->description }}" placeholder="Description">
                </div>

                <div class="form-group editable_cate_img_field mt-1">
                    <label><b>Sub Category photo </b></label>
                    <input type="file" name="photo" class="form-control" id="e_photo" accept=".jpg, .jpeg, .png, .gif">
                    <span class="error error_sub_e_photo"></span>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button subcategory_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="subcategory_save_changes_btn" class="btn btn-sm btn-success float-end subcategory_submit_button">@lang('menu.save_changes')</button>
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

        $('.subcategory_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('click change keypress', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.subcategory_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_sub_category_form').on('submit', function(e) {
        e.preventDefault();

        $('.subcategory_loading_button').show();
        var url = $(this).attr('action');

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
                $('.subcategory_loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success(data);
                    $('#subcategoryAddOrEditModal').modal('hide');
                    subCatetable.ajax.reload(null, false);
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.subcategory_loading_button').hide();
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

                    $('.error_subcategory_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
