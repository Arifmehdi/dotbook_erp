<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_requester')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_requester_form" action="{{ route('requesters.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label><b>@lang('menu.name')</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="requester_name" data-next="request_phone_number" placeholder="Requester Name" />
                    <span class="error error_requester_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.phone')</b> </label>
                    <input type="text" name="phone" class="form-control" id="request_phone_number" data-next="request_address" placeholder="Phone Number" />
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.address')</b> </label>
                    <input type="text" name="address" class="form-control" id="request_address" data-next="save_requester" placeholder="@lang('menu.address')" />
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn requester_btn-sm loading_button requester_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="button" id="save_requester" class="btn btn-sm btn-success float-end requester_submit_button">@lang('menu.save')</button>
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

        $('.requester_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.requester_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }else{

            $(this).prop('type', 'button');
        }
    });

    // Add supplier by ajax
    $('#add_requester_form').on('submit', function(e){

        e.preventDefault();
        $('.requester_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('#requestAddOrEditModal').modal('hide');
                toastr.success('Requester Added Successfully.');
                $('.requester_loading_button').hide();

                var requester_id = $('#requester_id').val();
                if (requester_id != undefined) {

                    $('#requester_id').append('<option value="'+data.id+'">'+ data.name +(data.phone_number ? '/'+data.phone_number : '')+'</option>');
                    $('#requester_id').val(data.id);

                    var nextId = $('#requester_id').data('next');
                    $('#'+nextId).focus().select();
                }else{

                    requester_table.ajax.reload();
                }
            }, error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.requester_loading_button').prop('type', 'sumbit');
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support.');
                }

                toastr.error('Input Field Error. Please check all form fields.');
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    $(document).on('change keypress click', 'select', function(e){

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#'+nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e){

        var nextId = $(this).data('next');

        if (e.which == 13) {

            $('#'+nextId).focus().select();
        }
    });
</script>
