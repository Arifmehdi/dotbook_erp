<style>
    .close-button {
        margin-left: 10px;
    }
</style>
<form id="edit_visit_form" action="{{ route('hrm.visit.update', $visit->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $visit->id }}" />
    <div class="row">
        <div class="form-group col-xl-12 col-md-12">
            <label><b> {{ __('Visit Title') }}</b> <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-sm add_input"
                value="{{ $visit->title }}" data-name="{{ __('Visit Title') }}" id="title"
                placeholder="{{ __('Visit Title') }}" />
            <span class="error error_title"></span>
        </div>
        <div class="form-group col-xl-12 col-md-12">
            <label><b> {{ __('Category') }}</b></label><br>
            <select name="category" id="category" class="form-control select2 form-select">
                <option value="" selected disabled>--{{ __('Choose Category') }}--</option>
                <option value="Official" @if ($visit->category == 'Official') {{ 'selected' }} @endif>
                    {{ __('Official') }}</option>
                <option value="Unofficial" @if ($visit->category == 'Unofficial') {{ 'selected' }} @endif>
                    {{ __('Unofficial') }}</option>
            </select>
            <span class="error error_category"></span>
        </div>
        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('From Date') }}</b> <span class="text-danger">*</span></label>
            <input type="date" name="from_date" value="{{ $visit->from_date }}"
                class="form-control form-control-sm add_input" data-name="{{ __('From Date') }}" id="from_date"
                placeholder="{{ __('From Date') }}" />
            <span class="error error_from-date"></span>
        </div>
        <div class="form-group col-xl-6 col-md-6">
            <label><b> {{ __('To Date') }}</b></label>
            <input type="date" name="to_date" class="form-control form-control-sm add_input"
                data-name="{{ __('To Date') }}" value="{{ $visit->to_date }}" id="to_date"
                placeholder="{{ __('To Date') }}" />
            <span class="error error_to-date"></span>
        </div>
    </div>

    <div class="col-xl-12 col-md-12">
        <label> {{ __('Description') }} <span class="text-danger"></span></label>
        <textarea name="description" rows="10" class="form-control ckEditor ckEditor" contenteditable="true"
            id="description" placeholder="Description">{!! $visit->description !!}</textarea>
        <span class="error error_description"></span>
    </div>
    <div class="row">
        <div class="form-group col-xl-12 col-md-12">
            <label><b> {{ __('Attachment') }}</b></label>
            <input type="file" name="attachments" value="{{ $visit->attachments }}"
                class="form-control attachment_edit" id="attachment_edit">
            <span class="error error_attachment"></span>
        </div>
        <input type="hidden" name="old_photo" value="{{ $visit->attachments }}">
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            @php
                $attachments = $visit->attachments;
                $extension = pathinfo($attachments, PATHINFO_EXTENSION);
            @endphp
            @if ($extension == 'pdf')
                @if (isset($attachments))
                    <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1 ms-auto d-block"
                        data-url="{{ route('hrm.visit_file_delete', $visit->id) }}">X</button>
                @endif
                <iframe src="{{ asset('/uploads/visits/' . $visit->attachments) }}"
                    style="height:90%; width:100%; margin-top: 13px;"
                    class="@if ($visit->attachments) {{ 'd-block' }}@else{{ 'd-none' }} @endif edit_p_avatar">
                </iframe>
            @else
                @if (isset($attachments))
                    <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1 ms-auto d-block"
                        data-url="{{ route('hrm.visit_file_delete', $visit->id) }}">X</button>
                @endif
                <img src="{{ asset('/uploads/visits/' . $visit->attachments) }}"
                    style="height:90%; width:100%; margin-top: 13px;" alt="No image"
                    class="@if ($visit->attachments) {{ 'd-block' }}@else{{ 'd-none' }} @endif edit_p_avatar">
            @endif
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
    ClassicEditor
        .create(document.querySelector('.ckEditor'))
        .then(editor => {})
        .catch(error => {});

    //show image on edit from with jquery
    $("#attachment_edit").change(function() {
        var file = $("#attachment_edit").get(0).files[0];
        if (file) {
            var extension = file.name.split(".").pop();
            var imageExtensions = ['jpg', 'png', 'gif', 'bmp', 'jpeg'];
            if (imageExtensions.includes(extension)) {
                var reader = new FileReader();
                reader.onload = function() {
                    $(".edit_p_avatar").attr("src", reader.result);
                    $(".edit_p_avatar").attr("alt", extension);
                    $(".edit_p_avatar").removeClass("d-none");
                    $(".edit_p_avatar").addClass("d-block");
                }
                reader.readAsDataURL(file);
            }
        }
    });

    $('.btn_remove').on('click', function(e) {
        e.preventDefault();
        var fileUrl = $(this).data('url');
        $.confirm({
            'title': 'Delete Confirmation',
            'content': 'Are you sure, you want to delete?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-primary',
                    'action': function() {
                        $.ajax({
                            url: fileUrl,
                            type: 'DELETE',
                            dataType: 'json',
                            success: function(response) {
                                toastr.success(response);
                                $(".edit_p_avatar").removeClass('d-block');
                                $(".edit_p_avatar").addClass('d-none');
                                $(".btn_remove").removeClass('d-block');
                                $(".btn_remove").addClass('d-none');
                            },
                            error: function(response) {
                                toastr.error('Delete failed. Try again!');
                            }
                        });
                    }
                },
                'No': {
                    'class': 'no btn-danger',
                    'action': function() {

                    }
                }
            }
        });
        // $(this).closest('div').remove();
    });
</script>
