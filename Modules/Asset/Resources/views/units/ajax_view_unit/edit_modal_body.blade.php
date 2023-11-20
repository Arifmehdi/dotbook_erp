<form id="update_unit_form" action="{{ route('assets.units.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $units->id }}">
    <div class="form-group">
        <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
        <input required type="text" name="name" class="form-control " id="e_name" placeholder="units name"
            value="{{ $units->name }}" />
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end" id="update_unit_btn">@lang('menu.save_change')</button>
                <button type="button" class="btn btn-sm btn-danger float-end me-2" id="close_unit_form">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>
