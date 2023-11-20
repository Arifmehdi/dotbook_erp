{{-- <div class="col-xl-3 col-md-4">
    <label><strong>{{ __('Employee') }} </strong></label>
    <select name="employee_id" class="form-control submitable form-select" id="employee_id">
        <option value="">@lang('menu.all')</option>
    </select>
</div> --}}

<div class="col-xl-2 col-md-4">
    <label><strong>{{ __('Month') }}</strong></label>
    <select name="month" class="form-control submitable form-select" id="month">
        <option value="">All</option>
    </select>
</div>
<div class="col-xl-2 col-md-4">
    <label><strong>{{ __('Year') }}</strong></label>
    <select name="year" class="form-control submitable form-select" id="year">
        <option value="">@lang('menu.all')</option>
    </select>
</div>
<div class="col-xl-2 col-md-4">
    <label><strong>{{ __('Adjustment Type') }}</strong></label>
    <select name="type" class="form-control submitable form-select" id="type">
        <option value="">@lang('menu.all')</option>
        <option value="1">{{ __('Addition') }}</option>
        <option value="2">{{ __('Deduction') }}</option>
    </select>
</div>
<div class="col-xl-2 col-md-4">
    <label><strong>{{ __('Created Date') }}</strong></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
        </div>
        <input type="search" name="date_range" id="date_range"
            class="form-control reportrange submitable_input date_range" autocomplete="off">
    </div>
</div>
