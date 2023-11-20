<!-- Edit Modal -->
<form id="update_form" action="{{ route('core.bd-upazila.update', $bd_upazila->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $bd_upazila->id }}" />
    @csrf
    <div class="form-group">
        <label><b> {{ __('Thana Name') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm add_input"
            data-name="{{ __('Thana Name') }}" value="{{ $bd_upazila->name }}" id="name"
            placeholder="{{ __('Thana Name') }}" required />
        <span class="error error_name"></span>
    </div>

    <div class="form-group">
        <label><b> {{ __('Thana Name (Bangla)') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="bn_name" class="form-control form-control-sm add_input"
            data-name="{{ __('Thana Name (Bangla)') }}" id="bn_name" placeholder="{{ __('Thana Name (Bangla)') }}"
            value="{{ $bd_upazila->bn_name }}" required />
        <span class="error error_bn_name"></span>
    </div>
    <div class="form-group">
        <label><b> {{ __('Web Address') }}</b> <span class="text-danger">*</span></label>
        <input type="url" name="url" class="form-control form-control-sm add_input"
            data-name="{{ __('Web Address') }}" id="bn_name" placeholder="{{ __('Web Address') }}"
            value="{{ $bd_upazila->url }}" required />
        <span class="error error_bn_name"></span>
    </div>
    <input type="hidden" name="status" value="1">
    <div class="col-xl-12 col-md-12">
        <label> {{ __('District') }} <span class="text-danger">*</span></label>
        <select name="district_id" class="form-control submit_able form-select" id="district_id" autofocus=""
            required>
            <option value="" selected>Select</option>
            @foreach ($bd_districts as $district)
                <option value="{{ $district->id }}" @if ($district->id == $bd_upazila->district_id) selected @endif>
                    {{ $district->name }}</option>
            @endforeach
        </select>
        <span class="error error_for_month"></span>
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
                $('.thana-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
