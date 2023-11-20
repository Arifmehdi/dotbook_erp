<tr data-user_id="{{ $employee->id }}">
    <td>
        <p class="m-0 mt-2">{{ $employee->employee_id }}</p>
        <input type="hidden" name="user_ids[{{ $employee->id }}]" value="{{ $employee->id }}" />
    </td>

    <td>
        <p class="m-0 mt-2">
            @if (File::exists($employee->photo))
                <img style="height:40px; width:40px;" src="{{ asset($employee->photo) }}">
            @else
                <img class="rounded-circle overflow-hidden" style="height:40px; width:40px;"
                    src="{{ asset('images/profile-picture.jpg') }}">
            @endif
        </p>
    </td>

    <td>
        <p class="m-0 mt-2">{{ $employee->name }}</p>
        <input type="hidden" name="user_ids[{{ $employee->id }}]" value="{{ $employee->id }}" />
    </td>

    <td>
        <p class="m-0 mt-2">{{ $employee->phone }}</p>
    </td>

    <td>
        <p class="m-0 mt-2">{{ $employee->email }}</p>
    </td>

    <td>
        <p class="m-0 mt-2">{{ $employee->presentDistrict?->name }}</p>
    </td>

    <td>
        <p class="m-0 mt-2">{{ $employee->joining_date }}</p>
    </td>

    <td>
        <button type="button" name="remove" class="btn btn-sm btn-danger btn_remove mt-1">X</button>
    </td>
</tr>
