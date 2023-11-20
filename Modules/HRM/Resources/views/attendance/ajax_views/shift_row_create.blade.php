@php use Carbon\Carbon; @endphp

@if (count($employees) > 0)
@foreach ($employees as $employee)

@php
$attendance = Modules\HRM\Entities\Attendance::where('shift_id', $employee->shift->id)->first();

@endphp
@if ($attendance)
<tr data-employee_id="{{ $attendance->employee_id }}">
    <td>{{ $attendance->index +1 }}</td>
    <td>
        <p class="m-0 mt-2 text-navy-blue">{{ $attendance->employee->name . ' - ' . $attendance->employee->employee_id }}</p>
        <input type="hidden" name="employee_ids[{{ $attendance->employee->id }}]" value="{{ $attendance->employee->id }}" />
    </td>
    <td>
        <p class="m-0 text-navy-blue">{{ $attendance->clock_in_ts }}</p>
        @php
        $startTime = Carbon::parse($attendance->clock_in_ts);
        $totalDuration = $startTime->diffForHumans();
        @endphp
        <small class="m-0 text-muted">(Clock In - {{ $totalDuration }})</small>
        <input type="hidden" name="clock_ins[{{ $attendance->employee_id }}]" value="{{ $attendance->clock_in }}" />
    </td>

    <td>
        <input type="time"  name="clock_outs[{{ $attendance->employee_id }}]" value="{{ $attendance->clock_out }}" placeholder="Clock Out" class="form-control form-control-sm" />
    </td>
    <td>
        <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1">X</button>
    </td>
</tr>
@else
<tr data-employee_id="{{ $employee->id }}">
    <td>{{ $employee->index +1 }}</td>
    <td>
        <p class="m-0 mt-2">{{ $employee->name . ' - ' . $employee->employee_id }}</p>
        <input type="hidden" name="employee_ids[{{ $employee->id }}]" value="{{ $employee->id }}" />
    </td>
    <td>
        <input required type="time" name="clock_ins[{{ $employee->id }}]" class="form-control form-control-sm" id="" />
    </td>
    <td>
        <input type="time" name="clock_outs[{{ $employee->id }}]" placeholder="Clock Out" class="form-control form-control-sm" />
    </td>
    <td>
        <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1">X</button>
    </td>
</tr>
@endif
@endforeach
@else
<tr>
    <td colspan="6" class="text-center">No Data Found</td>
</tr>
@endif
