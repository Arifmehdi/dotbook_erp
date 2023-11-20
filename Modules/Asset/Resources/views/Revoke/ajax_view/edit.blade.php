<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Edit Revoke</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_revoke_form" action="{{ route('assets.revoke.update', $revoke->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>Revoke Code </strong> <span class="text-danger">*</span></label>
                    <input type="text" name="quantity" class="form-control edit_input" data-name="Quantity"
                        id="quantity" placeholder="Revoke Code" value="{{ $revoke->revoke_code }}" disabled />
                    <span class="error error_e_quantity"></span>
                </div>

                {{-- <div class="col-xl-3 col-md-6">
                    <input type="hidden" name="allocation_id" value="{{ $revoke->allocation_id }}">
                    <label><strong>Asset Name </strong> <span class="text-danger">*</span></label>
                    <select name="asset_id" class="form-control submit_able form-select" id="asset_id" autofocus>
                        <option value="">Select Asset</option>
                        @foreach ($asset_id as $asset)
                            <option value="{{ $asset->id }}"
                                {{ $asset->id == $revoke->asset_id ? 'SELECTED' : '' }}>{{ $asset->asset_name }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.quantity') </strong> <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control edit_input" data-name="Quantity"
                        id="quantity" placeholder="@lang('menu.quantity')" value="{{ $revoke->quantity }}" />
                    <span class="error error_e_quantity"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Revoke Date </strong> <span class="text-danger">*</span></label>
                    <input type="text" name="date" class="form-control edit_input" data-name="Date" id="date"
                        placeholder="Date" value="{{ $revoke->revoke_date }}" />
                    <span class="error error_e_date"></span>
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-12">
                    <label><strong>@lang('menu.description')</strong></label>
                    <textarea name="description" rows="4" class="form-control ckEditor" id="description"
                        placeholder="Allocation Description">{{ $revoke->reason }}</textarea>
                </div>
            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                class="fas fa-spinner"></i></button>
                        <button type="submit"
                            class="btn btn-sm btn-success float-end submit_button">@lang('menu.save_change')</button>
                        <button type="reset" data-bs-dismiss="modal"
                            class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#edit_revoke_form').on('submit', function(e) { // click edit submit button

        e.preventDefault(); // what

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

                $('#editModal').modal('hide');
                $('.revokeTable').DataTable().ajax.reload();
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
        element: document.getElementById('date'),
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
