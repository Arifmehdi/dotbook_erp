<x-ckeditor-edit />
<!-- Edit Modal -->
<form id="update_form" action="{{ route('hrm.interview_question_update', ['id' => $interview->id]) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $interview->id }}" />

    <div class="form-group row mt-1">
        <div class="col-xl-12 col-md-12">
            <label><strong> {{ __('Title') }}</strong> <span class="text-danger">*</span></label>
            <input type="text" name="title" value="{{ $interview->title }}" class="form-control"
                data-name="{{ __('Interview Question Title') }}" id="title"
                placeholder="{{ __('Interview Question Title') }}" required />
            <span class="error error_title"></span>
        </div>
        <div class="col-xl-12 col-md-12">
            <label><strong> {{ __('Description') }}</strong> <span class="text-danger">*</span></label>
            <textarea class="form-control ckEditor-edit" name="description" id="description" rows="7">
                    {{ $interview->description }}
                </textarea>
            <span class="error error_description"></span>
        </div>
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
            type: 'POST',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('#update_form')[0].reset();
                $('.loading_button').hide();
                $('.leave-type-table').DataTable().draw(false);
                $('#editModal').modal('hide');
            },
            error: function(error) {
                $('.loading_button').hide();
                toastr.error(error.responseJSON.message);
            }
        });
    });
</script>
