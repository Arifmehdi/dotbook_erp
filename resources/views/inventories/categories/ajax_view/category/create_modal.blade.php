<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_category')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_category_form" action="{{ route('product.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label><b>@lang('menu.name')</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="category_name" data-next="category_code" placeholder="Category name" autofocus />
                    <span class="error error_category_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.code')</b></label>
                    <input type="text" name="code" class="form-control" id="category_code" data-next="category_description" placeholder="Category Code" autocomplete="off" />
                    <span class="error error_category_code"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.description')</b></label>
                    <input name="description" class="form-control" id="category_description" data-next="category_save_btn" placeholder="Description" autocomplete="off">
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.photo')</b><small class="text-danger"><b>@lang('menu.photo') size : 400px * 400px.</b></small></label>
                    <input type="file" name="photo" class="form-control" id="category_photo">
                    <span class="error error_photo"></span>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button category_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="category_save_btn" class="btn btn-sm btn-success float-end category_submit_button">@lang('menu.save')</button>
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
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_category_form').on('submit', function(e) {
        e.preventDefault();

        $('.category_loading_button').show();
        var url = $(this).attr('action');
        // var request = $(this).serialize();

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

                    toastr.success('Category created successfully');
                    $('#categoryAddOrEditModal').modal('hide');
                    var category_id = $('#brand_id').val();
                    var product_category_id = $('#product_brand_id').val();

                    if (category_id != undefined) {

                        $('#category_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                        $('#category_id').val(data.id);

                        var nextId = $('#category_id').data('next');
                        $('#' + nextId).focus().select();
                    } else if (product_category_id != undefined) {

                        $('#product_category_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                        $('#product_category_id').val(data.id);

                        var nextId = $('#product_category_id').data('next');
                        $('#' + nextId).focus().select();
                    } else {

                        category_table.ajax.reload();
                    }
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
