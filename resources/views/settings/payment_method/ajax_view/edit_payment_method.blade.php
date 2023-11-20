<form id="edit_payment_method_form" class="p-2" action="{{ route('settings.payment.method.update', $method->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('menu.method') @lang('menu.name') </b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="e_name" required
                placeholder="Payment Method Name" value="{{ $method->name }}"/>
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save_change')</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>
