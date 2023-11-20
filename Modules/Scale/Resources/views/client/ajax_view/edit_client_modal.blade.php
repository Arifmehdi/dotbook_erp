<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_weight_client')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_weight_client_form" action="{{ route('scale.client.update', $client->id) }}">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label><strong>@lang('menu.name') </strong><span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="e_client_name" value="{{ $client->name }}" placeholder="@lang('menu.name')"/>
                        <span class="error error_e_name"></span>
                    </div>

                    <div class="col-md-6">
                        <label><strong>@lang('menu.phone') </strong></label>
                        <input type="text" name="phone" class="form-control" id="phone" value="{{ $client->phone }}" placeholder="@lang('menu.phone_number')"/>
                        <span class="error error_phone"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label><strong>@lang('menu.company_name') </strong></label>
                        <input type="text" name="company_name" class="form-control" value="{{ $client->company_name }}" placeholder="Company Name"/>
                    </div>

                    <div class="col-md-6">
                        <label><strong>@lang('menu.tax_number') </strong>  </label>
                        <input type="text" name="tax_number" class="form-control" value="{{ $client->tax_no }}" placeholder="@lang('menu.tax_number')"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-6">
                        <label><strong>@lang('menu.email') </strong></label>
                        <input type="email" name="email" class="form-control" value="{{ $client->email }}" placeholder="@lang('menu.email')"/>
                    </div>

                    <div class="col-md-6">
                        <label><strong>@lang('menu.address')</strong></label>
                        <input type="text" name="address" class="form-control" value="{{ $client->email }}" placeholder="@lang('menu.address')">
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
    // Edit weight client by ajax
    $('#edit_weight_client_form').on('submit', function(e){
        e.preventDefault();
        $('.wc_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){

                $('.error').html('');
                $('#addOrEditWeightClientModal').modal('hide');
                toastr.success(data);
                $('.wc_loading_button').hide();
                table.ajax.reload();
            },error: function(err) {

                $('.wc_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error.');
                }else if (err.status == 500) {

                    toastr.error('Server error please contact to the support.');
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
