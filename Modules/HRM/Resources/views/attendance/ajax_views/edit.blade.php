<form id="edit_attendance_form" action="{{ route('hrm.persons.update', $attendance->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="id" value="{{ $attendance->id }}">
    <label class="text-navy-blue"><b>{{ __('Employee ID') }} :</b> {{ $attendance->employee->employee_id }} </label><br>
    <label class="text-navy-blue"><b>{{ __('Employee Name') }} :</b> {{ $attendance->employee->name }} </label><br>
    <label class="text-navy-blue"><b>{{ __('Attendance Date') }} : </b>{{ $attendance->at_date?->format(config('hrm.date_format')) }} </label>
    <div class="form-group row">
        <div class="col-md-6">
            <label><b>Clock In Date: </b></label>
            <input required type="date" name="clock_in_ts" class="form-control form-control-sm" value="{{ $attendance->clock_in_ts?->format(config('hrm.date_format')) }}">
        </div>
        <div class="col-md-6">
            <label><b>Clock In :</b></label>
            <input required type="time" name="clock_in" class="form-control form-control-sm" value="{{ $attendance->clock_in }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label><b>Clock Out Date:</b></label>
            <input required type="date" name="clock_out_ts" class="form-control form-control-sm" value="{{ $attendance->clock_out_ts?->format(config('hrm.date_format')) }}">
        </div>
        <div class="col-md-6">
            <label><b>Clock Out :</b></label>
            <input type="time" name="clock_out" class="form-control form-control-sm" @if($attendance->clock_out==NULL)  @else value="{{ $attendance->clock_out }}" @endif>
        </div>
    </div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">Save Change</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">Close</button>
            </div>
        </div>
    </div>
</form>
