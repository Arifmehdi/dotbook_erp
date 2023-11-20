<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.designations.update', $designation->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $designation->id }}" />

    <div class="col-xl-12 col-md-12">
        <label><b> {{ __('Section') }}</b> <span class="text-danger">*</span></label>
        <select name="section_id" required class="form-control submit_able form-select" id="section_id" autofocus=""
            required>
            <option value="">Select</option>
            @foreach ($departments as $department)
                <option disabled style="color: #2688cd;">{{ $department->name }}</option>
                @foreach ($department->sections as $section)
                    <option value="{{ $section->id }}" @if ($section->id == $designation->section_id) selected @endif> &nbsp;&nbsp;
                        -- {{ $section->name }}</option>
                @endforeach
            @endforeach
        </select>
        <span class="error error_for_month"></span>
    </div>
    <div class="row">
        <div class="form-group col-xl-6 col-md-6">
            <label>{{ __('Designation Name') }} <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ $designation->name }}"
                class="form-control form-control-sm add_input" data-name="{{ __('Designation Name') }}" id="name"
                placeholder="{{ __('Designation Name') }}" required />
            <span class="error error_name"></span>
        </div>

        <div class="col-xl-6 col-md-6">
            <label> {{ __('Give Report To') }} </label>
            <select name="parent_designation_id" class="form-control submit_able form-select" id="parent_designation_id"
                autofocus="">
                <option value="" selected>Select</option>
                @foreach ($allDesignation as $singleDesignation)
                    <option value="{{ $singleDesignation->id }}" @if ($singleDesignation->id == $designation->parent_designation_id) selected @endif>
                        {{ $singleDesignation->name }}</option>
                @endforeach
            </select>
            <span class="error error_parent_designation_id"></span>
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
                $('.designation-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
