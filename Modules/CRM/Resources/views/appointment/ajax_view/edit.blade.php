<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Appointment</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_appointment_form" action="{{ route('crm.appointment.update', $appointment->id) }}"
            method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Shedule Date</b> <span class="text-danger">*</span></label>
                        <input type="text" name="schedule_date" class="form-control add_input"
                            data-name="Schedule Date" id="e_schedule_date" placeholder="Schedule Date"
                            value="{{ $appointment->schedule_date }}" />
                        <span class="error error_schedule_date"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Shedule Time</b> </label>
                        <input type="time" name="schedule_time" class="form-control add_input"
                            data-name="Schedule Time" id="schedule_time" placeholder="Schedule Time"
                            value="{{ $appointment->schedule_time }}" />
                        <span class="error error_schedule_time"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Customer</b> <span class="text-danger">*</span></label>
                        <select required name="customer_id" class="form-control submit_able form-select"
                            id="customer_id">
                            <option class="selected" value="">-- Select Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ $customer->id == $appointment->customer_id ? 'SELECTED' : '' }}>
                                    {{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_customer_id"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Appointment With</b> <span class="text-danger">*</span></label>
                        <select required name="appointor_id" class="form-control submit_able form-select"
                            id="appointor_id">
                            <option class="selected" value="" selected disabled>-- Select Appointor --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $appointment->appointor_id == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                </option>
                            @endforeach

                        </select>
                        <span class="error error_appointor_id"></span>
                    </div>
                </div>
            </div>

            <div class="form-group mt-1">
                <label><b>@lang('menu.description')</b> </label>
                <textarea name="description" class="form-control form-control-sm ckEditor" id="description" cols="10"
                    rows="5" placeholder="@lang('menu.description')">{{ $appointment->description }}</textarea>
            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                class="fas fa-spinner"></i></button>
                        <button type="submit"
                            class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
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
        element: document.getElementById('e_schedule_date'),
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

    $('#edit_appointment_form').on('submit', function(e) {
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
                $('.appointmentTable').DataTable().ajax.reload();
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
