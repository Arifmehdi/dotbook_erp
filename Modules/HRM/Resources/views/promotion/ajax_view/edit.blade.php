<form id="update_form" action="{{ route('hrm.promotions.update', $promotional_employee->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Employee</label>
                <select class="selectpicker form-control form-select" id="employee_id" data-live-search="true"
                    name="employee_id" required>
                    <option disabled="">>-- Choose employee --< </option>
                            @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @if ($promotional_employee->employee->id == $employee->id) selected @endif>
                        {{ $employee->employee_id }} - {{ $employee->name }} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Department : <strong class="text-danger">*</strong></label>
                <select class="selectpicker form-control form-select" name="new_department_id" id="select_department">
                    <option selected="" disabled="">>-- Choose Department --<< /option>
                            @foreach ($departments as $row)
                    <option value="{{ $row->id }}" @if ($promotional_employee->new_department_id == $row->id) selected @endif>
                        {{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Section : <strong class="text-danger">*</strong></label>
                <select class="selectpicker form-control form-select" name="new_section_id" id="select_section">
                    <option selected="" disabled="">>-- Choose Section --<< /option>
                            @foreach ($sections as $section)
                    <option value="{{ $section->id }}" @if ($promotional_employee->new_section_id == $section->id) selected @endif>
                        {{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Sub Section : <strong class="text-danger">*</strong></label>
                <select class="selectpicker form-control form-select" name="new_subsection_id" id="select_subsection">
                    <option selected="" disabled="">>-- Choose Sub-Section --<< /option>
                            @foreach ($subSections as $subSection)
                    <option value="{{ $subSection->id }}" @if ($promotional_employee->new_subsection_id == $subSection->id) selected @endif>
                        {{ $subSection->name }}</option>
                    @endforeach
                </select>
                @error('sub_section_id ')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label>Designation</label>
                <select class="form-control selectpicker form-select" data-live-search="true" name="new_designation_id"
                    id="select_designation" required>
                    <option selected="" disabled="">>-- Chosee Designation --<< /option>
                            @foreach ($designation as $row)
                    <option value="{{ $row->id }}" @if ($promotional_employee->new_designation_id == $row->id) selected @endif>
                        {{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label>Promoted By</label>
                <select id="" class="form-control form-select" name="user_id">
                    @foreach ($admin_type_employee as $admin)
                        <option value="{{ $admin->id }}" @selected($promotional_employee->user_id == $admin->id)>
                            {{ $admin->employee_id }}-{{ $admin->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label>Promoted Date</label>
                <input type="date" class="form-control ap_endday" name="promoted_date"
                    value="{{ $promotional_employee->promoted_date }}">
            </div>
        </div>
    </div>
    <div class="float-end">
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
    </div>
</form>
