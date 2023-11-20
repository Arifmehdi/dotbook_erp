<!-- Edit Modal -->
<form id="update_form" action="{{ route('core.bd-districts.update', $bd_district->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $bd_district->id }}" />
    @csrf

    <div class="form-group">
        <label><b> {{ __('District Name') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm add_input"
            data-name="{{ __('District Name') }}" value="{{ $bd_district->name }}" id="name"
            placeholder="{{ __('District Name') }}" />
        <span class="error error_name"></span>
    </div>

    <div class="form-group">
        <label><b> {{ __('District Name (Bangla)') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="bn_name" class="form-control form-control-sm add_input"
            data-bn_name="{{ __('District Name (Bangla)') }}" value="{{ $bd_district->bn_name }}" id="bn_name"
            placeholder="{{ __('District Name (Bangla)') }}" />
        <span class="error error_bn_name"></span>
    </div>
    <div class="form-group">
        <label><b> {{ __('Web Address') }}</b> <span class="text-danger">*</span></label>
        <input type="url" name="url" class="form-control form-control-sm add_input"
            data-url="{{ __('Web Address') }}" value="{{ $bd_district->url }}" id="url"
            placeholder="{{ __('Web Address') }}" />
        <span class="error error_url"></span>
    </div>
    <input type="hidden" name="status" value="1">
    <div class="col-xl-12 col-md-12">
        <label> {{ __('Division') }} <span class="text-danger">*</span></label>
        <select name="division_id" class="form-control submit_able form-select" id="division_id" autofocus="">
            <option value="" selected>Select</option>
            @foreach ($bd_divisions as $division)
                <option value="{{ $division->id }}" @if ($division->id == $bd_district->division_id) selected @endif>
                    {{ $division->name }}</option>
            @endforeach
        </select>
        <span class="error error_division_id"></span>
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
                $('.district-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
