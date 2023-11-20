<style>
    video {
        border: 1px solid white;
    }

    .videoWrapper {
        position: relative;
        padding-bottom: 56.25%;
        /* 16:9 */
        height: 0;
    }

    .videoWrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    video {
        height: 100vh;
        border: 6px solid red;
        width: 100vw;
    }

</style>

<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_download')</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->

        <div class="videoWrapper">
            <iframe src="{{ asset('uploads/downloads') }}/{{ $downloads->file }}" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="m-2 mt-1" style="margin-top: -34px !important;">
            <button type="submit" class="btn btn-sm btn-success me-2 float-end submit_button mt-5">@lang('menu.share')</button>
        </div>
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
