
    <label class="text-navy-blue"><b>{{ __('Employee ID') }} :</b> {{ $attendance->employee->employee_id }} </label><br>
    <label class="text-navy-blue"><b>{{ __('Employee Name') }} :</b> {{ $attendance->employee->name }} </label><br>
    <label class="text-navy-blue"><b>
        {{ __('Attendance Date') }} :</b> {{ $attendance->at_date?->format(config('hrm.date_format'))}}
    </label>

    <div class="form-group row">
        <div class="col-md-6">
            <label><b>Clock In Date-Time:</b> {{ $attendance->clock_in_ts?->format(config('hrm.datetime_format')) }}</label>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label><b>Clock Out Date-Time:</b> {{$attendance->clock_out_ts?->format(config('hrm.datetime_format')) }}</label>
        </div>
    </div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end">Close</button>
            </div>
        </div>
    </div>
