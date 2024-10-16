<x-lightpicker />
<x-ckeditor-edit />

<form id="edit_work_space_form" action="{{ route('workspace.update', $ws->id) }}" method="post">
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label><b>@lang('menu.name') </b></label>
            <input required type="text" name="name" class="form-control" placeholder="Workspace Name"
                value="{{ $ws->name }}">
        </div>

        <div class="col-md-6">
            <label><b>@lang('menu.assigned_to') </b></label>
            <select required name="user_ids[]" class="form-control select2 form-select" id="user_ids"
                multiple="multiple">
                <option disabled value="">@lang('menu.select_please') </option>
                @foreach ($users as $user)
                    <option
                        @foreach ($ws->ws_users as $ws_user)
                            {{ $ws_user->user_id == $user->id ? 'SELECTED' : '' }} @endforeach
                        value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('menu.priority') </b></label>
            <select required name="priority" class="form-control form-select" id="priority">
                <option value="">@lang('menu.select_priority')</option>
                <option {{ $ws->priority == 'Low' ? 'SELECTED' : '' }} value="Low">@lang('menu.low')</option>
                <option {{ $ws->priority == 'Medium' ? 'SELECTED' : '' }} value="Medium">@lang('menu.medium')</option>
                <option {{ $ws->priority == 'High' ? 'SELECTED' : '' }} value="High">@lang('menu.high')</option>
                <option {{ $ws->priority == 'Urgent' ? 'SELECTED' : '' }} value="Urgent">@lang('menu.urgent')</option>
            </select>
        </div>

        <div class="col-md-6">
            <label><strong>@lang('menu.status') </strong></label>
            <select required name="status" class="form-control form-select" id="status">
                <option value="">@lang('menu.select_status')</option>
                <option {{ $ws->status == 'New' ? 'SELECTED' : '' }} value="New">New</option>
                <option {{ $ws->status == 'In-Progress' ? 'SELECTED' : '' }} value="In-Progress">@lang('menu.in_progress')
                </option>
                <option {{ $ws->status == 'On-Hold' ? 'SELECTED' : '' }} value="On-Hold">@lang('menu.on_hold')</option>
                <option {{ $ws->status == 'Complated' ? 'SELECTED' : '' }} value="Complated">@lang('menu.completed')
                </option>
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('menu.start_date') </b></label>
            <input required type="text" name="start_date" class="form-control datepicker" id="start_date_edit"
                value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ws->start_date)) }}">
        </div>

        <div class="col-md-6">
            <label><b>@lang('menu.end_date') </b></label>
            <input required type="text" name="end_date" class="form-control datepicker" id="end_date_edit"
                value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ws->end_date)) }}">
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.description') </b></label>
            <textarea name="description" class="form-control ckEditor-edit" id="description" cols="10" rows="3"
                placeholder="Workspace Description.">{{ $ws->description }}</textarea>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('menu.documents') </b></label>
            <input type="file" name="documents[]" class="form-control" multiple id="documents"
                placeholder="Workspace Description.">
        </div>

        <div class="col-md-6">
            <label><b>@lang('menu.estimated_hours') </b></label>
            <input type="text" name="estimated_hours" class="form-control" id="estimated_hours"
                placeholder="@lang('menu.estimated_hours')" value="{{ $ws->estimated_hours }}">
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save_change')</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
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
        element: document.getElementById('start_date_edit'),
        format: _expectedDateFormat
    });

    new Litepicker({
        element: document.getElementById('end_date_edit'),
        format: _expectedDateFormat
    });
</script>
