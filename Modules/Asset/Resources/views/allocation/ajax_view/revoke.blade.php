<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Revoke {{ $allocation->code }}</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
            <span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="revoke_form" action="{{ route('assets.revoke.insert') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="allocation_id" value="{{ $allocation->id }}">
            <input type="hidden" name="allocated_to" value="{{ $allocation->allocated_to }}">
            <div class="form-group row mt-1">

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.quantity') </strong> <span class="text-danger">*</span></label>
                    <input required type="number" name="quantity" class="form-control edit_input" data-name="quantity"
                        id="quantity" placeholder="@lang('menu.quantity')" value="" />
                    <span class="error error_r_quantity"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Revoke Date </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="revoke_date" class="form-control edit_input"
                        data-name="Revoke Date" id="revoke_date" placeholder="Date" value="" />
                    <span class="error error_r_revoke_date"></span>
                </div>

                <div class="col-xl-3 col-md-12">
                    <label><strong>@lang('menu.description')</strong></label>
                    <textarea name="description" rows="1" class="w-100 ckEditor" id="description" placeholder="Description"></textarea>
                </div>
            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                class="fas fa-spinner"></i></button>
                        <button type="submit" class="btn btn-sm btn-success float-end submit_button">Save
                            Changes</button>
                        <button type="reset" data-bs-dismiss="modal"
                            class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#revoke_form').on('submit', function(e) {
        e.preventDefault();

        // click submit button
        $('.loading_button').show(); // show loading button which id is loading_button
        var url = $(this).attr('action'); // get the url with id. Just alert the url and you will know.

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,

            success: function(data) {

                $('.loading_button').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.success(data);
                $('.allocationTable').DataTable().ajax.reload();
                $('#editModal').modal('hide');
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

                    $('.error_r_' + key + '').html(error[0]);
                });
            }
        });
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('revoke_date'),
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
