<!-- Edit Modal -->
<form id="update_form" action="{{ route('core.bd-unions.update', $bd_union->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $bd_union->id }}" />

    @csrf
    <div class="form-group">
        <label><b> {{ __('Unions Name') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm add_input"
            data-name="{{ __('Unions Name') }}" id="name" placeholder="{{ __('Unions Name') }}"
            value="{{ $bd_union->name }}" required />
        <span class="error error_name"></span>
    </div>

    <div class="form-group">
        <label><b> {{ __('Unions Name (Bangla)') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="bn_name" class="form-control form-control-sm add_input"
            data-name="{{ __('Unions Name (Bangla)') }}" id="bn_name" placeholder="{{ __('Unions Name (Bangla)') }}"
            value="{{ $bd_union->bn_name }}" required />
        <span class="error error_bn_name"></span>
    </div>
    <div class="form-group">
        <label><b> {{ __('Web Address') }}</b> <span class="text-danger">*</span></label>
        <input type="url" name="url" class="form-control form-control-sm add_input"
            data-name="{{ __('Web Address') }}" id="bn_name" placeholder="{{ __('Web Address') }}"
            value="{{ $bd_union->url }}" required />
        <span class="error error_bn_name"></span>
    </div>
    <input type="hidden" name="status" value="1">
    <div class="col-xl-12 col-md-12">
        <label> {{ __('Upazila') }} <span class="text-danger">*</span></label>
        <select name="upazilla_id" class="form-control submit_able form-select" id="upazilla_id" autofocus="">
            <option value="" selected>Select</option>
            @foreach ($bd_upazilas as $upazila)
                <option value="{{ $upazila->id }}" @if ($upazila->id == $bd_union->upazilla_id) selected @endif>
                    {{ $upazila->name }}</option>
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
                $('.union-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
