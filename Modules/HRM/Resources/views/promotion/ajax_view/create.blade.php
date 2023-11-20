<form id="add_form" action="{{ route('hrm.promotions.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Employee | ( Current Department : <span class="department"> </span> > <span class="division">
                    </span> > <span class="subsection"> </span> > <span class="designation"> </span> ) </label>
                <select class="selectpicker form-control  form-select" id="employee_id" data-live-search="true"
                    name="employee_id" required>
                    <option disabled selected="">>-- Chosee employee --< </option>
                            @foreach ($employee as $row)
                    <option value="{{ $row->id }}">{{ $row->employee_id }}-{{ $row->name }}</option>
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
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Section : <strong class="text-danger">*</strong></label>
                <select class="selectpicker form-control form-select" name="new_section_id" id="select_section">
                    <option selected="" disabled="">>-- Choose Section --<< /option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Sub Section : <strong class="text-danger">*</strong></label>
                <select class="selectpicker form-control form-select" name="new_subsection_id" id="select_subsection">
                    <option selected="" disabled="">>-- Choose Sub-Section --<< /option>
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
                <label>{{ __('Designation') }}</label>
                <select class="form-control selectpicker form-select" data-live-search="true" name="new_designation_id"
                    id="select_designation" required>
                    <option selected="" disabled="">>-- Chosee Designation --<< /option>
                            @foreach ($designations as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label>Promoted By</label>
                <select id="" class="form-control form-select" name="user_id">
                    <option value="">{{ __('Select Promoted By') }} </option>
                    @foreach ($admin_type_employee as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->employee_id }}-{{ $admin->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label>Promoted Date</label>
                <input type="date" class="form-control ap_endday" name="promoted_date">
            </div>
        </div>
    </div>
    <div class="float-end">
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
    </div>
</form>
