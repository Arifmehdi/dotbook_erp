<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit')</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_announcement_form" action="{{ route('announcements.update', $announcement->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <div class="col-md-12">
                    <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" value="{{ $announcement->title }}"
                        required>
                </div>
                <div class="col-md-12">
                    <label><strong>@lang('menu.description')</strong></label>
                    <textarea name="description" rows="20" class="form-control ckEditor ckEditor" contenteditable="true"
                        id="description" placeholder="Description">{{ trim(strip_tags($announcement->description)) }}</textarea>
                </div>
                <div class="col-md-12">
                    <label><strong>@lang('menu.add_file') </strong></label>
                    <input type="file" name="files" class="form-control">
                    @isset($announcement->files)
                        <div class="py-3">
                            <img src="{{ asset('uploads/announcement/' . $announcement->files) }}" />
                        </div>
                    @endisset
                </div>
                <div class="mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
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
    $('#edit_announcement_form').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
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
                $('#announcementTable').DataTable().ajax.reload();
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
