<form id="add_form" action="{{ route('hrm.designations.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="col-xl-12 col-md-12">
        <label> {{ __('Section') }} <span class="text-danger">*</span></label>
        <select name="section_id" class="form-control submit_able form-select" id="section_id" autofocus="" required>
            <option value="" selected>Select</option>
            @foreach ($departments as $department)
                <option disabled style="color: #2688cd;">{{ $department->name }}</option>
                @foreach ($department->sections as $section)
                    <option value="{{ $section->id }}"> &nbsp;&nbsp; -- {{ $section->name }}</option>
                @endforeach
            @endforeach
        </select>
        <span class="error error_for_month"></span>
    </div>
    <div class="row">
        <div class="form-group col-xl-6 col-md-6">
            <label>{{ __('Designation Name') }} <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form-control-sm add_input"
                data-name="{{ __('Designation Name') }}" id="name" placeholder="{{ __('Designation Name') }}"
                required />
            <span class="error error_name"></span>
        </div>

        <div class="col-xl-6 col-md-6">
            <label> {{ __('Give Report To') }} </label>
            <select name="parent_designation_id" class="form-control submit_able form-select" id="parent_designation_id"
                autofocus="">
                <option value="" selected>Select</option>
                @foreach ($designations as $designation)
                    <option value="{{ $designation->id }}">{{ $designation->name }}</option>
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
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>
<script>
    //Add new data
    $('#add_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.error').html('');

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('#add_form')[0].reset();
                $('.loading_button').hide();
                $('.designation-table').DataTable().draw(false);
                $('#addModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
