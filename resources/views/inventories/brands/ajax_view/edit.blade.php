<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_brand')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="edit_brand_form" action="{{ route('product.brands.update', $brand->id) }}">
                @csrf
                <div class="form-group">
                    <label><b>@lang('brand.name')</b>  <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" value="{{ $brand->name}}" id="brand_name" data-next="brand_save_changes" placeholder="@lang('brand.name')"/>
                    <span class="error error_brand_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('brand.brand_photo') </b> </label>
                    <input type="file" name="photo" class="form-control" data-max-file-size="2M" id="brand_photo" accept=".jpg, .jpeg, .png, .gif">
                    <span class="error error_brand_photo"></span>
                </div>

                <div class="form-group pt-2">
                    <div class="d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="brand_save_changes" class="btn btn-sm btn-success brand_submit_button float-end">@lang('menu.save_changes')</button>
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

        $('.brand_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#'+nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.brand_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_brand_form').on('submit',function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function(){
                isAjaxIn = true;
            },
            url : url,
            type : 'post',
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success:function(data){

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                }else{

                    toastr.success(data);
                    $('#brandAddOrEditModal').modal('hide');
                    brand_table.ajax.reload(null, false);
                }
            }, error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if(err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                } else if(err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_brand_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
