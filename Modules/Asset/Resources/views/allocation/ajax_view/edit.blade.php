<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Edit Allocation</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                <span class="fas fa-times"></span>
            </a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="edit_asset_form" action="{{ route('assets.allocation.update', $allocation->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group row mt-1">
                    <div class="col-xl-3 col-md-6">
                        <label><strong>Allocation Code </strong> <span class="text-danger">*</span></label>
                        <input type="text" required name="asset_code" class="form-control" data-name="Asset Code"
                            id="asset_code" placeholder="Allocation Code" value="{{ $allocation->code }}" disabled />
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><strong>Asset Name </strong></label>
                        <input type="text" name="asset_id" class="form-control edit_input" data-name="Asset Name"
                            id="asset_id" placeholder="Asset Name" value="{{ $asset_id?->asset_name }}" disabled />

                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><strong>Allocated To </strong> <span class="text-danger">*</span></label>
                        <select required name="allocated_to" class="form-control submit_able form-select" id="asset_id"
                            autofocus>
                            <option value="">@lang('menu.select_user')</option>
                            @foreach ($users as $item)
                                <option {{ $item->id == $allocation->allocated_to ? 'SELECTED' : '' }}
                                    value="{{ $item->id }}">
                                    {{ $item->prefix . ' ' . $item->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error error_e_allocated_to"></span>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><strong>@lang('menu.quantity') </strong> <span class="text-danger">*</span></label>
                        <input required type="number" name="quantity" class="form-control edit_input"
                            data-name="Asset Name" id="asset_name" placeholder="@lang('menu.quantity')"
                            value="{{ $allocation->quantity }}" />
                        <span class="error error_e_quantity"></span>
                    </div>


                    <div class="col-xl-3 col-md-6">
                        <label><strong>Allocated From </strong> <span class="text-danger">*</span></label>
                        <input required type="text" name="start_date" class="form-control edit_input"
                            data-name="Asset Name" id="editable_start_date" placeholder="Asset Name"
                            value="{{ $allocation->start_date }}" />
                        <span class="error error_e_start_date"></span>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><strong>Allocated Upto </strong> <span class="text-danger">*</span></label>
                        <input required type="text" name="end_date" class="form-control edit_input"
                            data-name="Asset Name" id="editable_end_date" placeholder="Asset Name"
                            value="{{ $allocation->end_date }}" />
                        <span class="error error_e_end_date"></span>
                    </div>

                    <div class="col-xl-6 col-md-12">
                        <label><strong>@lang('menu.description')</strong> <span class="text-danger">*</span></label>
                        <textarea name="description" rows="4" cols="68" id="description" placeholder="Allocation Description"
                            class="form-control ckEditor">{{ $allocation->description }}</textarea>
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
</div>

<script>
    $('#edit_asset_form').on('submit', function(e) { // click edit submit button

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



                toastr.success(data);
                $('#editModal').modal('hide');
                $('.allocationTable').DataTable().ajax.reload();
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
        element: document.getElementById('editable_start_date'),
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('editable_end_date'),
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
