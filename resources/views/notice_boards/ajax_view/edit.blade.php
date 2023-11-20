@push('css')
@endpush

<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_notice')</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_notice_form" action="{{ route('notice_boards.update', $notice->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">

                <div class="col-md-12">
                    <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" value="{{ $notice->title }}" required>
                </div>
                <div class="col-md-12">
                    <label><strong>@lang('menu.description')</strong></label>
                    <textarea name="description" class="form-control ckEditor ckEditor" rows="10" id="description"
                        placeholder="Description">
                        {{ trim(strip_tags($notice->description)) }}
                    </textarea>
                </div>
                <div class="col-md-12">
                    <label><strong>@lang('menu.add_file') </strong></label>
                    <input type="file" name="files" class="form-control">
                    @if (isset($notice->files))
                        <img src="{{ asset('uploads/notice/' . $notice->files) }}" />
                    @endif

                </div>
                <div class="mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none float-end"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit" class="btn btn-sm btn-success float-start submit_button float-end"
                                id="update_btn">@lang('menu.update')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="btn btn-sm btn-danger float-start float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#edit_notice_form').on('submit', function(e) { // clisk Edit Button
        e.preventDefault(); // what
        var url = $(this).attr('action'); // get the url with id. Just alert the url and you will know.
        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                toastr.success(data);
                $('.loading_button').hide();
                $('#editModal').modal('hide');
                $('#noticeTable').DataTable().ajax.reload();
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
