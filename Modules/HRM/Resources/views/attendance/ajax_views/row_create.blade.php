
@if ($attendance)
    <tr data-employee_id="{{ $attendance->employee_id }}">
        <td class="border">{{ $currentRow }}</td>
        <td class="border">
            <p class="m-0 mt-2 text-navy-blue">{{  $employee->name.' - '.$employee->employee_id }}</p>
            <input type="hidden" name="employee_ids[{{ $employee->id }}]" value="{{ $employee->id }}" />
        </td>

        <td class="border">
            <p class="m-0 text-navy-blue">{{ $attendance->clock_in_ts }}</p>
            @php
                $startTime = Carbon\Carbon::parse($attendance->clock_in_ts);
                $totalDuration = $startTime->diffForHumans();
            @endphp
            <small class="m-0 text-muted">(Clock In - {{ $totalDuration }})</small>
            <input type="hidden" name="clock_ins[{{ $attendance->employee_id }}]" value="{{ $attendance->clock_in }}" />
        </td>

        <td class="border">
            <input type="time" name="clock_outs[{{ $attendance->employee_id }}]" placeholder="Clock Out"
                class="form-control form-control-sm" />
        </td>

        <td class="border">
            <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1">X</button>
        </td>
    </tr>
@else
    <tr data-employee_id="{{ $employee->id }}">
        <td class="border">{{ $currentRow }}</td>
        <td class="border">
            <p class="m-0 mt-2">{{ $employee->name.' - '.$employee->employee_id }}</p>
            <input type="hidden" name="employee_ids[{{ $employee->id }}]" value="{{ $employee->id }}" />
        </td>

        <td class="border">
            <input required type="time" name="clock_ins[{{ $employee->id }}]" class="form-control form-control-sm"
                id="" />
        </td>

        <td class="border">
            <input type="time" name="clock_outs[{{ $employee->id }}]" placeholder="Clock Out"
                class="form-control form-control-sm" />
        </td>

        <td class="border">
            <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1">X</button>
        </td>
    </tr>
@endif
{{-- @if ($attendance)
    <tr data-employee_id="{{ $attendance->employee_id }}">
        <td class="border">{{ $attendance->index +1 }}</td>
        <td class="border">
            <p class="m-0 mt-2 text-navy-blue">{{  $employee->name.' - '.$employee->employee_id }}</p>
            <input type="hidden" name="employee_ids[{{ $attendance->employee_id }}]" value="{{ $attendance->employee_id }}" />
        </td>

        <td class="border">
            <p class="m-0 text-navy-blue">{{ $attendance->clock_in_ts }}</p>
            @php
                $startTime = Carbon\Carbon::parse($attendance->clock_in_ts);
                $totalDuration = $startTime->diffForHumans();
            @endphp
            <small class="m-0 text-muted">(Clock In - {{ $totalDuration }})</small>
            <input type="hidden" name="clock_ins[{{ $attendance->employee_id }}]" value="{{ $attendance->clock_in }}" />
        </td>

        <td class="border">
            <input type="time" name="clock_outs[{{ $attendance->employee_id }}]" placeholder="Clock Out"
                class="form-control form-control-sm" />
        </td>

        <td class="border">
            <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1">X</button>
        </td>
    </tr>
@else
    <tr data-employee_id="{{ $employee->id }}">
        <td class="border">{{ $employee->index +1 }}</td>
        <td class="border">
            <p class="m-0 mt-2">{{ $employee->name.' - '.$employee->employee_id }}</p>
            <input type="hidden" name="employee_ids[{{ $attendance->employee_id }}]" value="{{ $attendance->employee_id }}" />
        </td>

        <td class="border">
            <input required type="time" name="clock_ins[{{ $attendance->employee_id }}]" class="form-control form-control-sm"
                id="" />
        </td>

        <td class="border">
            <input type="time" name="clock_outs[{{ $attendance->employee_id }}]" placeholder="Clock Out"
                class="form-control form-control-sm" />
        </td>

        <td class="border">
            <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1">X</button>
        </td>
    </tr>
@endif --}}
