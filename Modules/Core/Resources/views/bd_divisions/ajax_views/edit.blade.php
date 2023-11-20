<!-- Edit Modal -->
<form id="update_form" action="{{ route('core.bd-divisions.update', $division->id) }}"  method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label><b> {{ __('Division Name') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm add_input"
            data-name="{{ __('Division Name') }}" id="name" placeholder="{{ __('Division Name') }}"
             value="{{$division->name}}"/>
        <span class="error error_name"></span>
    </div>

    <div class="form-group">
        <label><b> {{ __('Division Name (Bangla)') }}</b> <span class="text-danger">*</span></label>
        <input type="text" name="bn_name" class="form-control form-control-sm add_input"
            data-name="{{ __('Division Name (Bangla)') }}" id="bn_name" placeholder="{{ __('Division Name (Bangla)') }}"
             value="{{$division->bn_name}}"/>
        <span class="error error_bn_name"></span>
    </div>

    <div class="form-group">
        <label><b> {{ __('URL') }}</b> <span class="text-danger">*</span></label>
        <input type="url" name="url" class="form-control form-control-sm add_input"
            data-name="{{ __('url') }}" id="url" placeholder="{{ __('website Url') }}"
            value="{{ $division->url }}"/>
        <span class="error error_url"></span>
    </div>

    <input type="hidden" name="id"  value="{{$division->id}}">
    {{-- <input type="hidden" name="status"  value="{{$division->status}}">
    <input type="hidden" name="country_id" value="{{$division->country_id}}"> --}}

    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
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
                url: url
                , type: 'PATCH'
                , data: request
                , success: function(data) {
                    toastr.success(data);
                    $('#update_form')[0].reset();
                    $('.loading_button').hide();
                    $('.division-table').DataTable().draw(false);
                    $('#editModal').modal('hide');
                },
                error: function(error) {
                    $('.loading_button').hide();
                    toastr.error(error.responseJSON.message);
                }
            });
        });
</script>