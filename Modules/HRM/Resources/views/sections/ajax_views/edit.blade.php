<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.sections.update', $section->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $section->id }}" />

    <div class="col-xl-12 col-md-12">
        <label> {{ __('Department') }}<span class="text-danger">*</span></label>
        <select name="hrm_department_id" required class="form-control submit_able form-select" id="hrm_department_id"
            autofocus="">
            <option value="">{{ __('Select') }}</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @if ($department->id === $section->hrmDepartment->id) selected @endif>
                    {{ $department->name }}</option>
            @endforeach
        </select>
        <span class="error error_for_month"></span>
    </div>
    <div class="form-group">
        <label><b> {{ __('Section Name') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm add_input"
            data-name="{{ __('Section Name') }}" id="name" value="{{ $section->name }}"
            placeholder="{{ __('Section Name') }}" required />
        <span class="error error_name"></span>
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
                $('.section-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
