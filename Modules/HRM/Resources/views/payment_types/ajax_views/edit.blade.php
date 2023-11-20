<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.payment-types.update', $paymentType->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $paymentType->id }}" />

    <div class="form-group row mt-1">
        <div class="col-xl-7 col-md-6">
            <label><strong> {{ __('PaymentType Name') }}</strong> <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ $paymentType->name }}" class="form-control"
                data-name="{{ __('Leave Type Name') }}" id="name" placeholder="{{ __('Payment Type Name') }}" />
            <span class="error error_name"></span>
        </div>
        <div class="col-xl-5 col-md-6">
            <label><strong> {{ __('Status') }}</strong> <span class="text-danger">*</span></label>
            <select name="status" required class="form-control submit_able form-select" id="status" autofocus="">
                <option value="">Select</option>
                <option @if ($paymentType->status == 1) selected @endif value="1">Allowed</option>
                <option @if ($paymentType->status == 0) selected @endif value="0">Not-Allowed</option>
            </select>
            <span class="error error_is_active"></span>
        </div>


        <div class="col-12">
            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                class="fas fa-spinner"></i></button>
                        <button type="submit"
                            class="btn btn-sm btn-success float-end submit_button">{{ __('Update') }}</button>
                        <button type="reset" data-bs-dismiss="modal"
                            class="btn btn-sm btn-danger float-end me-2">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
</form>
<script>
    $('#update_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.error').html('');

        $.ajax({
            url: url,
            type: 'PATCH',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('#update_form')[0].reset();
                $('.loading_button').hide();
                $('.leave-type-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
