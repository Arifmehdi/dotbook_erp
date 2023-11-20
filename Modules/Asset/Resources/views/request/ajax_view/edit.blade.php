<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Edit Request</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_request_form" action="{{ route('assets.request.update', $asset_request->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.title') </strong></label>
                    <input type="text" name="title" required data-name="Title" class="form-control "
                        data-name="title" id="title" placeholder="@lang('menu.title')"
                        value="{{ $asset_request->title }}" />
                    <span class="error error_title"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Asset Code And Name </strong> </label>
                    <select name="asset_id" required class="form-control submit_able " data-name="Asset Code And Name"
                        id="asset_id" autofocus>
                        <option value="">Select Asset</option>
                        @foreach ($assets as $asset)
                            <option value="{{ $asset->id }}"
                                {{ $asset->id == $asset_request->asset_id ? 'Selected' : '' }}>{{ $asset->asset_name }} (
                                {{ $asset->asset_code }} )</option>
                        @endforeach
                    </select>
                    <span class="error error_asset_id"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Request For </strong> </label>
                    <select name="request_for_id" required class="form-control submit_able " data-name="Request For"
                        id="request_for_id" autofocus>
                        <option value="">Select Request For</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $asset_request->request_for_id == $user->id ? 'Selected' : '' }}>{{ $user->prefix }}
                                {{ $user->name }}</option>
                        @endforeach
                    </select>
                    <span class="error error_request_for_id"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.date') </strong> </label>
                    <input type="text" name="date" required class="form-control " data-name="Date"
                        id="edit_request_date" placeholder="Request Date" value={{ $asset_request->date }} />
                    <span class="error error_date"></span>
                </div>
            </div>

            <div class="form-group row mt-1">
                <label><strong>@lang('menu.description')</strong></label>
                <div class="col-md-12">
                    <textarea name="description" rows="3" class="form-control ckEditor" id="description" placeholder="Description">{{ $asset_request->description }}</textarea>
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
    $('#edit_request_form').on('submit', function(e) {
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

                toastr.success(data);
                $('.loading_button').hide();
                $('#editModal').modal('hide');
                $('.assetTable').DataTable().ajax.reload();
                $('.add_input').addClass('bdr-red');
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
        element: document.getElementById('edit_request_date'),
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
