<div class="col-xl-2 col-md-4">
    <label><strong>{{ __('Leave Type') }}</strong></label>
    <select name="leave_type" class="form-control submitable form-select" id="leave_type">
        <option value="">All</option>
    </select>
</div>
<div class="col-xl-2 col-md-4">
    <label><strong>{{ __('Status Type') }}</strong></label>
    <select name="type" class="form-control submitable form-select" id="type">
        <option value="">@lang('menu.all')</option>
        <option value="1">{{ __('Approved') }}</option>
        <option value="2">{{ __('Rejected') }}</option>
    </select>
</div>
<div class="col-xl-2 col-md-4">
    <label><strong>{{ __('Filter By Date') }}</strong></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
        </div>
        <input type="search" name="date_range" id="date_range"
            class="form-control reportrange submitable_input date_range" autocomplete="off">
    </div>
</div>
