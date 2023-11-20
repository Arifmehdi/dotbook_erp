<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Edit Service Consume</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->

        <form id="edit_service_consume_form"
            action="{{ route('assets.consume.services.update', $service_consume->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <div class="col-md-3">
                    <label><strong>Comsume Service Code </strong> <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control add_input" data-name="code" id="code"
                        placeholder="Comsume Service Code" autocomplete="off" value="{{ $service_consume->code }}"
                        disabled />
                </div>

                <div class="col-md-3">
                    <label><strong>Service Type </strong> <span class="text-danger">*</span></label>
                    <select required name="e_service_type" class="form-control submit_able form-select"
                        id="e_service_type" autofocus>
                        <option value="">Select Service Type</option>
                        <option value="1" {{ 1 == $service_consume->maintenance ? 'SELECTED' : '' }}>Upgrade</option>
                        <option value="2" {{ 2 == $service_consume->maintenance ? 'SELECTED' : '' }}>Repair</option>
                    </select>
                    <span class="error error_e_service_type"></span>
                </div>

                <div class="col-md-3">
                    <label><strong>Service Cost </strong></label>
                    <input type="number" name="e_service_cost" class="form-control add_input"
                        data-name="e_service_cost" id="e_service_cost" placeholder="Service Cost"
                        value="{{ $service_consume->cost }}" />
                </div>
                <div class="col-md-3">
                    <label><strong>Service Start </strong> <span class="text-danger">*</span></label>
                    <input type="text" required name="e_start_date" class="form-control add_input"
                        data-name="e_start_date" id="e_start_date" placeholder="Date" autocomplete="off"
                        value="{{ $service_consume->start_date }}" />
                    <span class="error error_e_start_date"></span>
                </div>

            </div>

            <div class="form-group row mt-1">
                <div class="col-md-3">
                    <label><strong>Service End </strong></label>
                    <input type="text" name="e_end_date" class="form-control add_input" data-name="e_end_date"
                        id="e_end_date" placeholder="Date" autocomplete="off"
                        value="{{ $service_consume->end_date }}" />
                </div>
                <div class="col-md-3">
                    <label><strong>@lang('menu.description')</strong></label>
                    <textarea name="e_notes" rows="3" cols="105" id="e_notes" placeholder="Asset Description">{{ $service_consume->note }}"</textarea>
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
    $('#edit_service_consume_form').on('submit', function(e) {

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
                $('.serviceConsumeTable').DataTable().ajax.reload();
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
        element: document.getElementById('e_end_date'),
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
        element: document.getElementById('e_start_date'),
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
