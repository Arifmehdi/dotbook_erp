<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Edit Audit</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_audit_from" action="{{ route('assets.audit.update', $all_audits->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="title" class="form-control add_input" data-name="title"
                        id="title" placeholder="@lang('menu.title')" value="{{ $all_audits->title }}" />

                    <span class="error error_e_title"></span>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label><strong>Auditor </strong> <span class="text-danger">*</span></label>
                    <select required name="auditor_id" class="form-control submit_able form-select" id="auditor_id"
                        autofocus>
                        <option value="">Select Auditor</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $user->id == $all_audits->auditor_id ? 'SELECTED' : '' }}>{{ $user->prefix }}
                                {{ $user->name }} {{ $user->last_name }}</option>
                        @endforeach
                    </select>
                    <span class="error error_e_auditor_id"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Asset Code And Name </strong> <span class="text-danger">*</span></label>
                    <select required name="asset_id" class="form-control submit_able form-select" id="asset_id"
                        autofocus>
                        <option value="">Select Asset</option>
                        @foreach ($assets as $asset)
                            <option value="{{ $asset->id }}"
                                {{ $asset->id == $all_audits->asset_id ? 'SELECTED' : '' }}>{{ $asset->asset_name }} (
                                {{ $asset->asset_code }} )</option>
                        @endforeach
                    </select>
                    <span class="error error_e_asset_id"></span>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label><strong>Audite Date </strong> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="in_audit_date"><i
                                    class="fas fa-calendar-week input_f"></i></span>
                        </div>
                        <input type="text" name="audit_date" id="e_audit_date_input"
                            class="form-control from_date date" autocomplete="off"
                            value="{{ $all_audits->audit_date }}">
                    </div>
                    <span class="error error_e_audit_date"></span>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.status') </strong><span class="text-danger">*</span></label>
                    <select required name="status" class="form-control submit_able form-select" id="status"
                        autofocus>
                        <option value="">@lang('menu.select_status')</option>
                        <option value="1" {{ $all_audits->status == 1 ? 'SELECTED' : '' }}>Accepted</option>
                        <option value="2" {{ $all_audits->status == 2 ? 'SELECTED' : '' }}>Rejected</option>
                    </select>
                    <span class="error error_e_supplier_id"></span>
                </div>

            </div>

            <div class="form-group row mt-1">
                <label><strong>@lang('menu.reason') </strong> <span class="text-danger">*</span></label>
                <div class="col-md-12">
                    <textarea name="reason" rows="3" class="form-control ckEditor" id="reason" placeholder="@lang('menu.reason')">{{ $all_audits->reason }}</textarea>
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
    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_audit_date_input'),
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

    // submit edited form

    $('#edit_audit_from').on('submit', function(e) {
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
                $('.auditTable').DataTable().ajax.reload();
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
