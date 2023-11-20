<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_requester')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_requesters_form" action="{{ route('requesters.update', $requesters->id) }}">
                <div class="form-group">
                    <label><b>@lang('menu.name')</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="requester_name" value="{{ $requesters->name }}" data-next="requester_phone" placeholder="@lang('menu.name')" />
                    <span class="error error_e_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.phone') </b></label>
                    <input type="text" name="phone" class="form-control" id="requester_phone" value="{{ $requesters->phone_number }}" data-next="requester_address" placeholder="Phone Number" />
                </div>

                <div class="form-group mt-1">
                    <label><b>@lang('menu.address')</b></label>
                    <input name="address" class="form-control" id="requester_address" value="{{ $requesters->area }}" data-next="save_changes_requester" placeholder="@lang('menu.address')">
                </div>

                <div class="form-group mt-3">
                    <div class="d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn requester_btn-sm loading_button requester_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="button" id="save_changes_requester" class="btn btn-sm btn-success requester_submit_button float-end">@lang('menu.save_change')</button>
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

    // edit bank by ajax
    $('#edit_requesters_form').on('submit', function(e) {
        e.preventDefault();
        $('.requester_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url
            , type: 'post'
            , data: request
            , success: function(data) {

                requester_table.ajax.reload();
                toastr.success(data);
                $('.requester_loading_button').hide();
                $('#requestAddOrEditModal').modal('hide');
            },error: function(err) {

                $('.error').html('');
                $('.requester_loading_button').hide();

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if(err.status == 500){

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                toastr.error('Input Field Error. Please check all form fields.');
            }
        });
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
