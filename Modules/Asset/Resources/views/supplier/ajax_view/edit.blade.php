<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_supplier')</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_supplier_form" action="{{ route('assets.supplier.update', $asset_supplier->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>Supplier Code </strong></label>
                    <input type="text" required name="supplier_code" class="form-control add_input"
                        data-name="Supplier Code" id="supplier_code" placeholder="@lang('menu.name')" value="{{ $asset_supplier->supplier_code }}" disabled/>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
                    <input type="text" required name="name" class="form-control add_input"
                        data-name="Name" id="name" placeholder="@lang('menu.name')" value="{{ $asset_supplier->name }}"/>
                    <span class="error error_e_name"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.phone')  </strong> <span class="text-danger">*</span></label>
                    <input type="text" required name="phone" class="form-control add_input"
                        data-name="Phone" id="phone" placeholder="Phone" value="{{ $asset_supplier->phone }}"/>
                    <span class="error error_e_phone"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Alternative Phone </strong> </label>
                    <input type="text" name="alternative_phone" class="form-control add_input"
                        data-name="Alternative Phone" id="alternative_phone" placeholder="Alternative Phone" value="{{ $asset_supplier->alternative_phone }}"/>

                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.email') </strong> <span class="text-danger">*</span></label>
                    <input type="text" required name="email" class="form-control add_input"
                        data-name="Email" id="email" placeholder="Email" value="{{ $asset_supplier->email }}"/>
                    <span class="error error_e_email"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.address') </strong> <span class="text-danger">*</span></label>
                    <input type="text" name="address" class="form-control add_input"
                        data-name="Address" id="address" placeholder="Address" value="{{ $asset_supplier->address }}"/>
                    <span class="error error_e_address"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="row mt-4">
                        <p class="checkbox_input_wrap">
                            <input type="checkbox" name="status" id="status" @if ($asset_supplier->status == 1) checked @endif> &nbsp;
                            <b>@lang('menu.status')</b>
                        </p>
                    </div>
                </div>


            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                        <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save_change')</button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#edit_supplier_form').on('submit', function(e) {

        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        $.ajax({

            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                $('.loading_button').hide();



                toastr.success(data);
                $('#editModal').modal('hide');
                $('.supplierTable').DataTable().ajax.reload();
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });

</script>
