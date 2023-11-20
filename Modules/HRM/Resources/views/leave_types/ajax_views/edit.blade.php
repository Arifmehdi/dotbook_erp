<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.leave-types.update', $leaveType->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $leaveType->id }}" />

    <div class="form-group row mt-1">
        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('Leave Type Name') }}</strong> <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ $leaveType->name }}" class="form-control"
                data-name="{{ __('Leave Type Name') }}" id="name" placeholder="{{ __('Leave Type Name') }}"
                required />
            <span class="error error_name"></span>
        </div>
        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('For Month') }}</strong> <span class="text-danger">*</span></label>
            <select name="for_months" required class="form-control submit_able form-select" id="units_id"
                autofocus="">
                <option @if ($leaveType->for_months == 1) selected @endif value="1">Monthly</option>
                <option @if ($leaveType->for_months == 12) selected @endif value="12">Yearly</option>
                <option @if ($leaveType->for_months == 6) selected @endif value="6">Bi-Yearly</option>
            </select>
            <span class="error error_for_month"></span>
        </div>
        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('Days') }}</strong> <span class="text-danger">*</span></label>
            <input type="number" name="days" value="{{ $leaveType->days }}" class="form-control"
                data-name="{{ __('Days') }}" id="name" placeholder="{{ __('Days') }}" required />
            <span class="error error_days"></span>
        </div>
    </div>
    <div class="form-group row mt-1">
        <div class="col-xl-4 col-md-6">
            <label><strong> {{ __('Status') }}</strong> <span class="text-danger">*</span></label>
            <select name="is_active" required class="form-control submit_able form-select" id="status"
                autofocus="">
                <option @if ($leaveType->is_active == 1) selected @endif value="1">Allowed</option>
                <option @if ($leaveType->is_active == 0) selected @endif value="0">Not-allowed</option>
            </select>
            <span class="error error_is_active"></span>
        </div>
    </div>

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
