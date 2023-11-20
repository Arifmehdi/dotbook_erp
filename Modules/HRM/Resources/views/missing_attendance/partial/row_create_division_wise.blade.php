
<tr>
    <td>
        <p class="m-0 mt-2">{{ $employee->name . ' - ' . $employee->employee_id }}</p>
        <input type="hidden" name="user_ids[{{ $employee->id }}]" value="{{ $employee->id }}" />
    </td>

    <td>
        <input required type="date" name="start_dates[{{ $employee->id }}]" class="form-control form-control-sm"/>
    </td>

    <td>
        <input required type="time" name="clock_ins[{{ $employee->id }}]" class="form-control form-control-sm" />
    </td>

    <td>
        <input required type="time" name="clock_outs[{{ $employee->id }}]" placeholder="Clock Out"
            class="form-control form-control-sm" />
    </td>

    <td>
        <input type="text" name="clock_in_notes[{{ $employee->id }}]" class="form-control form-control-sm"
            placeholder="Clock in note" />
    </td>

    <td>
        <input type="text" name="clock_out_notes[{{ $employee->id }}]" class="form-control form-control-sm"
            placeholder="clock out note" />
    </td>

    <td>
        <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1">X</button>
    </td>
</tr>
