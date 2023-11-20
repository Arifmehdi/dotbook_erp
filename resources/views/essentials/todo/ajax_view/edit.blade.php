<x-ckeditor-edit />
<x-lightpicker />
<form id="edit_todo_form" action="{{ route('todo.update', $todo->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <div class="col-md-12">
            <label><b>@lang('menu.task') <span class="text-danger">*</span></b></label>
            <input required type="text" name="task" class="form-control" placeholder="@lang('menu.task')"
                value="{{ $todo->task }}">
        </div>
    </div>

    <div class="form-group mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.assigned_to') <span class="text-danger">*</span></b></label>
            <select required name="user_ids[]" class="form-control select2 form-select" multiple="multiple">
                <option disabled value="">@lang('menu.select_please')</option>
                @foreach ($users as $user)
                    <option
                        @foreach ($todo->todo_users as $todo_user)
                        {{ $todo_user->user_id == $user->id ? 'SELECTED' : '' }} @endforeach
                        value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('menu.priority') <span class="text-danger">*</span> </b></label>
            <select required name="priority" class="form-control form-select">
                <option value="">@lang('menu.select_priority')</option>
                <option {{ $todo->priority == 'Low' ? 'SELECTED' : '' }} value="Low">@lang('menu.low')</option>
                <option {{ $todo->priority == 'Medium' ? 'SELECTED' : '' }} value="Medium">@lang('menu.medium')</option>
                <option {{ $todo->priority == 'High' ? 'SELECTED' : '' }} value="High">@lang('menu.high')</option>
                <option {{ $todo->priority == 'Urgent' ? 'SELECTED' : '' }} value="Urgent">@lang('menu.urgent')</option>
            </select>
        </div>

        <div class="col-md-6">
            <label><strong>@lang('menu.status') <span class="text-danger">*</span> </strong></label>
            <select required name="status" class="form-control form-select">
                <option value="">@lang('menu.select_status')</option>
                <option {{ $todo->status == 'New' ? 'SELECTED' : '' }} value="New">@lang('menu.new')</option>
                <option {{ $todo->status == 'In-Progress' ? 'SELECTED' : '' }} value="In-Progress">@lang('menu.in_progress')
                </option>
                <option {{ $todo->status == 'On-Hold' ? 'SELECTED' : '' }} value="On-Hold">@lang('menu.on_hold')</option>
                <option {{ $todo->status == 'Complated' ? 'SELECTED' : '' }} value="Complated">@lang('menu.completed')
                </option>
            </select>
        </div>
    </div>


    <div class="form-group mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.due_date') <span class="text-danger">*</span> </b></label>
            <input required type="text" name="due_date" class="form-control" id="due_date_edit"
                value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($todo->due_date)) }}">
        </div>
    </div>

    <div class="form-group mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.description') </b></label>
            <textarea name="description" class="form-control ckEditor-edit" id="description" cols="10" rows="3"
                placeholder="Workspace Description.">{{ $todo->description }}</textarea>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save_change')</button>
                <button type="button" class="btn btn-sm btn-danger float-end me-2"
                    id="close_form">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>
<script>
    $('.select2').select2();

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('due_date_edit'),
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
        format: _expectedDateFormat
    });
</script>
