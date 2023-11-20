<x-ckeditor-edit />
<form id="edit_memo_form" action="{{ route('memos.update') }}">
    @csrf
    <input type="hidden" id="id" name="id" value="{{ $memo->id }}">
    <div class="from-group">
        <label><b>@lang('menu.heading') <span class="text-danger">*</span></b></label>
        <input required type="text" class="form-control" name="heading" id="heading" placeholder="@lang('menu.heading')"
            value="{{ $memo->heading }}">
    </div>

    <div class="from-group mt-1">
        <label><b>@lang('menu.description') <span class="text-danger">*</span></b></label>
        <textarea required name="description" class="form-control ckEditor-edit" id="description" cols="10" rows="4"
            placeholder="Memo Description">{{ $memo->description }}</textarea>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save_change')</button>
                <button type="button" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>
