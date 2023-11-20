<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_download')</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_download_from" action="{{ route('downloads.download.update', $downloads->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <div class="col-md-3">
                    <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                    <input type="text" required name="e_title" class="form-control add_input" data-name="e_title"
                        id="e_title" placeholder="@lang('menu.title')" value="{{ $downloads->title }}" />
                    <span class="error error_e_title"></span>
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.date') </strong></label>
                    <input type="text" name="e_date" class="form-control edit_input" data-name="e_date"
                        id="e_date" placeholder="Date" autocomplete="off" value="{{ $downloads->date }}" />
                    <span class="error error_e_date"></span>
                </div>

                <div class="col-md-3">
                    <label><strong>@lang('menu.file') </strong></label>
                    <input type="file" name="e_file" class="form-control" data-name="e_file" id="e_file"
                        placeholder="@lang('menu.file')" multiple />
                    <span class="error error_e_file"></span>
                </div>
            </div>

            <div clas1s="form-group row mt-1">
                <label><strong>@lang('menu.description')</strong></label>
                <div class="col-md-12">
                    <textarea name="e_description" rows="3" class="form-control ckEditor" id="e_description"
                        placeholder="Asset Description">{{ $downloads->description }}</textarea>
                </div>
            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                        <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save_changes')</button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#edit_download_from').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.loading_button').hide();

                toastr.success(data);

                $('#editModal').modal('hide');
                $('.downloadTable').DataTable().ajax.reload();
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });
</script>
