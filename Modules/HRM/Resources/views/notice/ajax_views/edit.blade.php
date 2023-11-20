<form id="edit_notice_form" action="{{ route('hrm.notices.update', $notice->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="title" value="{{ $notice->title }}" required>
        </div>
        <div class="col-md-6">
            <label><strong>{{ __('Notice By') }} </strong></label>
            <input type="text" name="notice_by" value="{{ $notice->notice_by }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label><strong>@lang('menu.add_file') </strong></label>
            <input type="file" name="attachment" class="form-control attachment_edit" id="attachment_edit">
            <span class="error error_attachment" data-name="attachment" value="{{ $notice->attachment }}"></span>
        </div>

        <input type="hidden" name="old_photo" value="{{ $notice->attachment }}">

        <div class="col-md-2">
            <img src="{{ asset('/uploads/notice/' . $notice->attachment) }}"
                style="height:70px; width:70px; margin-top: 13px;" alt="No image" id="edit_p_avatar">
        </div>
        <div class="col-md-12">
            <label><strong>@lang('menu.description')</strong></label>
            <textarea name="description" rows="10" class="form-control ckEditor ckEditor" placeholder="Description">{!! $notice->description !!}</textarea>
        </div>
        <div class="mt-3">
            <div class="col-md-12 d-flex justify-content-end">
                <div class="loading-btn-box">
                    <button type="button" class="btn btn-sm loading_button display-none float-end"><i
                            class="fas fa-spinner"></i></button>
                    <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                    <button type="reset" data-bs-dismiss="modal"
                        class="btn btn-sm btn-danger float-start float-end me-2">@lang('menu.close')</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    ClassicEditor
        .create(document.querySelector('.ckEditor'))
        .then(editor => {})
        .catch(error => {});

    //show image on edit from with jquery
    $("#attachment_edit").on("change", function() {
        var file = $("#attachment_edit").get(0).files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function() {
                var extension = file.name.split(".").pop();
                $("#edit_p_avatar").attr("src", reader.result);
                $("#edit_p_avatar").attr("alt", extension);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
