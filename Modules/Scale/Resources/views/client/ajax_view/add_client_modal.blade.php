<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Add Weight Client</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_weight_client_form" action="{{ route('scale.client.store') }}">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label><strong>@lang('menu.name') </strong><span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="client_name" placeholder="@lang('menu.name')" required/>
                        <span class="error error_name"></span>
                    </div>

                    <div class="col-md-6">
                        <label><strong>@lang('menu.phone') </strong></label>
                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone number"/>
                        <span class="error error_phone"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label><strong>Company Name </strong></label>
                        <input type="text" name="company_name" class="form-control" placeholder="Company Name"/>
                    </div>

                    <div class="col-md-6">
                        <label><strong>@lang('menu.tax_number') </strong>  </label>
                        <input type="text" name="tax_number" class="form-control" placeholder="@lang('menu.tax_number')"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label><strong>@lang('menu.email') </strong></label>
                        <input type="email" name="email" class="form-control" placeholder="email Address"/>
                    </div>

                    <div class="col-md-6">
                        <label><strong>@lang('menu.address')</strong></label>
                        <input type="text" name="address" class="form-control"  placeholder="Address">
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn loading_button wc_btn-sm wc_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Add weight client by ajax
    $('#add_weight_client_form').on('submit', function(e){
        e.preventDefault();
        $('.wc_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $('.submit_button').prop('type', 'button');

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){

                $('#addOrEditWeightClientModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                toastr.success('Weight Clint Added Successfully.');
                $('.wc_loading_button').hide();

                var client_id = $('#client_id').val();

                if (client_id != undefined) {

                    $('#client_id').append('<option value="'+data.id+'">'+ data.name +(data.phone? '/'+data.phone : '' )+'</option>');
                    $('#client_id').val(data.id);
                } else {

                    table.ajax.reload();
                }
            },error: function(err) {

                $('.submit_button').prop('type', 'sumbit');
                $('.wc_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                }else if (err.status == 500) {

                    toastr.error('Server error please contact to the support.');
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
