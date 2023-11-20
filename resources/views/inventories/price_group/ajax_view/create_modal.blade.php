<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_price_group')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_price_group_form" action="{{ route('product.selling.price.groups.store') }}" method="POST">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="price_group_name" data-next="price_group_description"   placeholder="@lang('menu.name')"/>
                        <span class="error error_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label><b>@lang('menu.description')</b></label>
                        <input name="description" class="form-control" id="price_group_description" data-next="price_group_save_btn" placeholder="@lang('menu.description')">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button price_group_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="price_group_save_btn" class="btn btn-sm btn-success float-end price_group_submit_button">@lang('menu.save')</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.price_group_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#'+nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.price_group_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_price_group_form').on('submit',function(e) {
        e.preventDefault();

        $('.price_group_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function(){
                isAjaxIn = true;
            },
            url : url,
            type : 'post',
            data : request,
            success:function(data){

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.price_group_loading_button').hide();
                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                }else{

                    toastr.success(data);
                    $('#priceGroupAddOrEditModal').modal('hide');
                    price_group_table.ajax.reload();
                    refresh();
                }
            }, error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.price_group_loading_button').hide();
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

                    $('.error_price_group_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
