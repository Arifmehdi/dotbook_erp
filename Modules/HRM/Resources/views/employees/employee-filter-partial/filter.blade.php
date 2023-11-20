<div class="col-xl-2 col-md-4">
    <label><strong>Department </strong></label>
    <select name="hrm_department_id" class="form-control submitable form-select" id="hrm_department_id">
        <option value="">All</option>
        @foreach ($departments as $key => $department)
            <option value="{{ $department->id }}">{{ $department->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-xl-2 col-md-4">
    <label><strong>Section </strong></label>
    <select name="section_id" class="form-control submitable form-select" id="section_id">
        <option value="" selected>All</option>
        @foreach ($sections as $key => $section)
            <option value="{{ $section->id }}">{{ $section->name }}</option>
        @endforeach
    </select>
</div>
<div class="col-xl-2 col-md-4">
    <label><strong>Designation </strong></label>
    <select name="designation_id" class="form-control submitable form-select" id="designation_id">
        <option value="">All</option>
        @foreach ($designations as $key => $designation)
            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-xl-2 col-md-4">
    <label><strong>Grade </strong></label>
    <select name="grade_id" class="form-control submitable form-select" id="grade_id">
        <option value selected>All</option>
        @foreach ($grades as $key => $grade)
            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-xl-2 col-md-4">
    <label><strong>Type Status </strong></label>
    <select name="employment_status" class="form-control submitable form-select" id="employment_status">
        <option value="">All</option>
        <option value="1" selected>Active</option>
        <option value="3">Left</option>
        <option value="2">Resigned</option>
    </select>
</div>

<div class="col-xl-2 col-md-4">
    <label><strong>Joining Date </strong></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
        </div>
        <input type="text" name="date_range" id="date_range"
            class="form-control reportrange submitable_input date_range" autocomplete="off">
    </div>
</div>

<div class="col-xl-2 col-md-4">
    <label><strong>Employee </strong></label>
    <select name="employee_id" class="form-control submitable form-select" id="employee_id">
        <option value selected>All</option>
        @foreach ($employees as $key => $employee)
            <option value="{{ $employee->id }}">{{ $employee->employee_id }} - {{ $employee->name }}</option>
        @endforeach
    </select>
</div>
