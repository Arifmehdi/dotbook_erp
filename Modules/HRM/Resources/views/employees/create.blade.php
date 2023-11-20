@extends('layout.master')
@section('title', 'Add Employee - ')
@push('css')

    <style>
        .sorting_disabled {
            background: none;
        }



        .select2-search--dropdown .select2-search__field {
            padding: 6px;
        }

        .bg-semi-dark {
            background-color: #efefef;
        }

        .last-employee {
            font-size: 14px;
            padding: 3px 15px;
        }
    </style>
@endpush

@section('content')
    <div class="body-wraper">
        <div class="container-fluid" style="padding: 0px;">
            <form id="add_form" action="{{ route('hrm.employees.store') }}" enctype="multipart/form-data" method="POST">
                @csrf

                <input type="hidden" name="job_candidate_id"
                    value="@isset($job_candidate_info){{ $job_candidate_info?->id }} @endisset">

                <section class="mt-5x">
                    <div class="container-fluid p-0">
                        <div class="form_element m-0 border-0">
                            <div class="sec-name">
                                <h6>{{ __('Create New Employee') }}</h6>
                                <div class="d-flex justify-content-end">
                                    <div class="border rounded text-right mb-0">
                                        <ul class="d-flex gap-5 last-employee">
                                            <li>{{ __('Last Inserted Employee') }}</li>
                                            <li>@lang('menu.name') : {{ $last_inserted_employee?->name }}</li>
                                            <li>{{ __('Employee Id') }} : <span
                                                    id="lastEmployeeId">{{ $last_inserted_employee?->employee_id }}
                                                    123</span> <a role="button" onclick="copyToClipboard()"><i
                                                        class="fa-regular fa-clone"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ url()->previous() }}"
                                        class="btn text-white btn-sm  float-end back-button"><i
                                            class="fa-thin fa-left-to-line fa-2x"></i>
                                        <br>@lang('menu.back')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-15 pb-0">
                        <div class="form_element rounded mb-1">
                            <div class="element-body">
                                <strong class="font-weight-bold"> <span
                                        class="icon-user"></span>{{ __('Basic Information') }}</strong>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Employee Id') }} <strong class="text-danger">*</strong></label>
                                            <input type="text" name="employee_id" class="form-control" required="">
                                            <span class="error error_employee_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Full Name') }}<strong class="text-danger">*</strong></label>
                                            <input type="text" name="name"
                                                value="{{ $job_candidate_info->fullname ?? null }}" class="form-control"
                                                required="">
                                            <span class="error error_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('menu.email')</label>
                                            <input type="text" name="email"
                                                value="{{ $job_candidate_info->email ?? null }}" class="form-control">
                                            <span class="error error_email"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('menu.phone')<strong class="text-danger">*</strong></label>
                                            <input type="text" name="phone"
                                                value="{{ $job_candidate_info->mobile ?? null }}" class="form-control"
                                                required>
                                            <span class="error error_phone"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('menu.shift')<strong class="text-danger">*</strong> </label>
                                            <select class="form-control form-select" name="shift_id" id="shift_id"
                                                required>
                                                <option>{{ __('Select Shift') }}</option>
                                                @foreach ($shifts as $shift)
                                                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                                @endforeach

                                            </select>
                                            <span class="error error_shift_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('menu.alternative_number') </label>
                                            <input type="text" name="alternative_phone" class="form-control" required>
                                            <span class="error error_alternative_phone"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('menu.country') <strong class="text-danger">*</strong> </label>
                                            <select class="form-control form-select" name="country" id="country">
                                                <option selected disabled value="">{{ __('Select Country') }}
                                                </option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->value }}"
                                                        @if ($country->value == 'Bangladesh') selected @endif>
                                                        {{ $country->value }}</option>
                                                @endforeach

                                            </select>
                                            <span class="error error_country"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form_element rounded mb-1">
                            <div class="element-body">
                                <strong class="font-weight-bold"> <span class="icon-user"></span> @lang('menu.personal_information')</strong>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('menu.department') <strong class="text-danger">*</strong></label>
                                            <select class="form-control form-select" name="hrm_department_id" id="hrm_department_id"
                                                required>
                                                <option selected="" disabled="">{{ __('Choose Department') }}
                                                </option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_department_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Section') }} <strong class="text-danger">*</strong></label>
                                            <select class="form-control form-select" name="section_id" id="section_id"
                                                required>
                                                <option selected="" disabled="">{{ __('Choose Section') }}</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_section_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Sub Section') }} <strong class="text-danger">*</strong></label>
                                            <select class="form-control" name="sub_section_id" id="sub_section_id"
                                                required>
                                                <option selected disabled>{{ __('Choose Subsection') }}</option>
                                                @foreach ($subsections as $subsection)
                                                    <option value="{{ $subsection->id }}">{{ $subsection->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_subsection_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Designation') }} <strong class="text-danger">*</strong></label>
                                            <select class="form-control" data-live-search="true" name="designation_id"
                                                id="designation_id" required>
                                                <option selected="" disabled="">{{ __('Choose Designation') }}
                                                </option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_designation_id"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Employee Type') }} <strong
                                                    class="text-danger">*</strong></label>
                                            <select class="form-control form-select" name="employee_type"
                                                id="employee_type">
                                                <option selected disabled>{{ __('Choose Employee Type') }}</option>
                                                @foreach (Modules\HRM\Enums\EmployeeType::cases() as $employeeType)
                                                    <option value="{{ $employeeType->value }}">
                                                        {{ $employeeType->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_employee_type"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Duty Type') }} <strong class="text-danger">*</strong></label>
                                            <select class="form-control form-select" name="duty_type_id"
                                                id="duty_type_id">
                                                <option selected disabled>{{ __('Choose Duty Type') }}</option>
                                                @foreach (Modules\HRM\Enums\EmployeeDutyType::cases() as $dutytype)
                                                    <option value="{{ $dutytype->value }}">{{ $dutytype->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_duty_type_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>{{ __('Joining Date') }} <strong class="text-danger">*</strong>
                                                    <span></span> </label>
                                                <input type="date" name="joining_date" placeholder="MM-DD-YYYY"
                                                    class="form-control" id="joiningDate" required="" required>
                                                <span id="dobString"></span>
                                                <span class="error error_joining_date"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Leave/Expire Date </label>
                                                <input type="date" name="termination_date" placeholder="MM-DD-YYYY"
                                                    id="terminationDate" class="form-control date">
                                                <span class="error error_termination_date"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form_element rounded mb-1">
                            <div class="element-body">
                                <strong class="font-weight-bold"> <span class="icon-user"></span> Salary</strong>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Grade') }} <strong class="text-danger">*</strong></label>
                                            <select class="form-control form-select" name="grade_id" id="grade_id"
                                                required>
                                                <option selected disabled>{{ __('Choose Grade') }}</option>
                                                @foreach ($grades as $grade)
                                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_grade_id"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Starting Gross Salary') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="starting_salary" class="form-control"
                                                id="starting_salary" required>
                                            <span class="error error_starting_salary"></span>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{__('Salary System')}} <strong class="text-danger">*</strong></label>
                                        <select class="form-control form-select" name="salary_template_id" required>
                                            <option value="0">{{ __('Daily Basis Salary') }}</option>
                                            <option value="1">{{ __('Contractual Salary') }}</option>
                                            <option value="2">{{ __('Monthly Salary') }}</option>
                                            <option value="3">{{ __('Custom Salary Type') }}</option>
                                        </select>
                                        <span class="error error_salary_template_id"></span>
                                    </div>
                                </div> --}}

                                </div>
                            </div>
                        </div>

                        <div class="form_element rounded mb-1">
                            <div class="element-body">
                                <strong class="font-weight-bold"> <span class="icon-user"></span>
                                    {{ __('Banking Account Details') }}</strong>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>{{ __('Mobile Banking Provider Name') }}</label>
                                                <select name="mobile_banking_provider" id="mobile_banking_provider"
                                                    class="form-control">
                                                    <option value="">{{ __('Choose Mobile Banking Provider') }}
                                                    </option>
                                                    @foreach (Modules\Core\Enums\MobileBankingProvider::cases() as $providerName)
                                                        <option value="{{ $providerName }}">{{ $providerName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Mobile Banking Account Number') }}</label>
                                            <input type="text" name="mobile_banking_account_number"
                                                placeholder="017XXXXX" class="form-control"
                                                id="mobile_banking_provider_number">
                                            <span class="error error_mobile_banking_account_number"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>{{ __('Bank Name') }}</label>
                                                <input type="text" name="bank_name" placeholder="Eg. Sonali Bank"
                                                    id="branch_name" class="form-control date">
                                                <span class="error error_bank_name"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Bank Branch Name') }}</label>
                                            <input type="text" name="bank_branch_name" placeholder="Branch Name"
                                                class="form-control" id="bank_branch_name">
                                            <span class="error error_bank_branch_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Bank Account Name') }}</label>
                                            <input type="text" name="bank_account_name" placeholder="Account Name"
                                                class="form-control" id="bank_account_name">
                                            <span class="error error_bank_account_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Bank Account Number') }}</label>
                                            <input type="text" name="bank_account_number" placeholder="Account Number"
                                                class="form-control" id="bank_account_number">
                                            <span class="error error_bank_account_number"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form_element rounded mb-1">
                            <div class="element-body">
                                <strong class="font-weight-bold"> <span
                                        class="icon-user"></span>{{ __('Permanent & Present Address') }}</strong>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('Permanent Division') }} <strong class="text-danger"> * </strong>
                                            </label>
                                            <select class="form-control" name="permanent_division_id"
                                                id="permanent_division_id" required>
                                                <option selected="" disabled="">{{ __('Choose Division') }}
                                                </option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_permanent_division_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('Permanent District') }} <strong class="text-danger"> * </strong>
                                            </label>
                                            <select class="form-control" name="permanent_district_id"
                                                id="permanent_district_id" required>
                                                <option selected="" disabled="">{{ __('Choose District') }}
                                                </option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_permanent_district_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('Permanent Thana') }} <strong class="text-danger"> * </strong>
                                            </label>
                                            <select class="form-control" name="permanent_upazila_id"
                                                id="permanent_upazila_id" required>
                                                <option selected="" disabled="">{{ __('Choose Upazila/Thana') }}
                                                </option>
                                                @foreach ($bdUpazila as $upazila)
                                                    <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_permanent_upazila_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('Permanent Union/Post') }} <strong class="text-danger"> *
                                                </strong> </label>
                                            <select class="form-control" name="permanent_union_id"
                                                id="permanent_union_id" required>
                                                <option selected="" disabled="">
                                                    {{ __('Choose Union/Post Office') }}</option>
                                                @foreach ($unions as $union)
                                                    <option value="{{ $union->id }}">{{ $union->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_permanent_union_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Permanent Village/House/Road') }} <strong class="text-danger"> *
                                                </strong></label>
                                            <input type="text" name="permanent_village" class="form-control" required>
                                            <span class="error error_permanent_village"></span>
                                        </div>
                                    </div>

                                </div>
                                {{-- Present Address And Permanent Address Same Function --}}
                                <div class="row mt-2">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('Present Address Same as Permanent Address') }} : &nbsp;
                                                <input type="checkbox" name="p_same" value="1" id="p_same"
                                                    onchange="valueChanged()">
                                            </label>
                                            <span class="error error_p_same"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group adds">
                                            <label>{{ __('Present Division') }} </label>
                                            <select class="form-control" name="present_division_id"
                                                id="present_division_id">
                                                <option selected>{{ __('Choose Division') }}</option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_present_division_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group adds">
                                            <label>{{ __('Present District ') }} </label>
                                            <select class="form-control" name="present_district_id"
                                                id="present_district_id">
                                                <option selected>{{ __('Choose District') }}</option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_present_district_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group adds">
                                            <label> {{ __('Present Upazila/Thana') }} </label>
                                            <select class="form-control" name="present_upazila_id"
                                                id="present_upazila_id">
                                                <option selected>{{ __('Choose Upazila/Thana') }}</option>
                                                @foreach ($bdUpazila as $upazila)
                                                    <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_present_upazila_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group adds">
                                            <label> {{ __('Present Union/Post Office') }} </label>
                                            <select class="form-control form-select" name="present_union_id"
                                                id="present_union_id">
                                                <option selected>{{ __('Choose Union/Post Office') }}</option>
                                                @foreach ($unions as $union)
                                                    <option value="{{ $union->id }}">{{ $union->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_present_union_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group adds">
                                            <label>{{ __('Present Village/House ') }} </label>
                                            <input type="text" name="present_village" class="form-control">
                                            <span class="error error_present_village"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form_element rounded mb-1">
                            <div class="element-body">
                                <strong class="font-weight-bold"> <span
                                        class="icon-user"></span>{{ __('Biographical Information') }} </strong>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Father/Husband Name') }} <strong class="text-danger"> *
                                                </strong> </label>
                                            <input type="text" name="father_name" class="form-control"
                                                placeholder="Father/Husband Name" required>
                                            <span class="error error_father_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Mother\'s Name') }} <strong class="text-danger"> * </strong>
                                            </label>
                                            <input type="text" name="mother_name" class="form-control"
                                                placeholder="Mother Name" required>
                                            <span class="error error_mother_name"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Date of Birth') }} <strong class="text-danger"> * </strong>
                                            </label>
                                            <input type="date" name="dob" placeholder="MM-DD-YYYY"
                                                class="form-control" id="dobDate" required>
                                            <span id="dobString"></span><span class="error error_dob"></span>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('NID No/Birth Certificate') }}<strong class="text-danger"> *
                                                </strong> </label>
                                            <input type="text" name="nid" class="form-control">
                                            <span class="error error_nid"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Employee Photo') }}</label>
                                            <input type="file" name="photo" class="form-control" id="user_image">
                                            <span class="error error_photo"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <img src="{{ asset('images/profile-picture.jpg') }}"
                                            style="height:70px; width:70px; margin-top: 13px;" id="p_avatar"
                                            class="d-none">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Gender') }} <strong class="text-danger"> * </strong> </label>
                                            <select class="form-control form-select" name="gender" id="gender">
                                                <option selected="" disabled>{{ __('Choose Gender') }}</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <span class="error error_gender"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Marital Status') }} <strong class="text-danger"> * </strong>
                                            </label>
                                            <select class="form-control form-select" name="marital_status"
                                                id="marital_status">
                                                <option selected="" disabled="">{{ __('Marital Status') }}
                                                </option>
                                                @foreach (Modules\CORE\Enums\MaritalStatus::cases() as $item)
                                                    <option value="{{ $item->value }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_marital_status"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Blood Group') }} <strong class="text-danger"> * </strong>
                                            </label>
                                            <select class="form-control form-select" name="blood" id="blood">
                                                <option selected="" disabled>{{ __('Blood Group') }}</option>
                                                @foreach ($blood_groups as $blood_group)
                                                    <option value="{{ $blood_group->value }}">{{ $blood_group->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_blood"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Religion') }} <strong class="text-danger"> * </strong> </label>
                                            <select class="form-control form-select" name="religion" id="religion">
                                                <option selected disabled>{{ __('Choose Religion') }}</option>
                                                @foreach (Modules\Core\Enums\ReligionStatus::cases() as $key => $value)
                                                    <option value="{{ $value->value }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_religion"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form_element rounded mb-1">
                            <div class="element-body">
                                <strong class="font-weight-bold"> <span class="icon-user"></span>
                                    {{ __('Emergency Contact Information') }} </strong>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Contact Person Name') }} : <strong class="text-danger"> *
                                                </strong></label>
                                            <input type="text" name="emergency_contact_person_name"
                                                class="form-control" placeholder="Name" required>
                                            <span class="error error_emergency_contact_person_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Contact Person Phone') }} : <strong class="text-danger"> *
                                                </strong></label>
                                            <input type="text" name="emergency_contact_person_phone"
                                                class="form-control" placeholder="Phone" required>
                                            <span class="error error_emergency_contact_person_phone"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Relation') }} : <strong class="text-danger"> *
                                                </strong></label>
                                            <input type="text" name="emergency_contact_person_relation"
                                                placeholder="Relation" class="form-control"
                                                id="emergency_contact_relation" required>
                                            <span id="emergency_contact_relation"></span>
                                            <span class="error error_emergency_contact_person_relation"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form_element rounded mb-1">
                            <div class="element-body">
                                <strong class="font-weight-bold"> <span
                                        class="icon-user"></span>{{ __('LogIn Information') }}</strong>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Login Access') }}<strong class="text-danger"> * </strong>
                                            </label>
                                            <input type="checkbox" name="login_access" value="1" id="login_access"
                                                onchange="login_field()">
                                            <span class="error error_login_access"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group login_field">
                                            <label>{{ __('Username') }}<strong class="text-danger"> * </strong> </label>
                                            <input type="text" name="username" class="form-control" id="username">
                                            <span class="error error_username"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group login_field">
                                            <label>@lang('menu.password')<strong class="text-danger"> * </strong> </label>
                                            <input type="password" name="password" autocomplete="off"
                                                class="form-control" id="password">
                                            <span class="error error_password"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group login_field">
                                            <label>{{ __('Confirm Password') }}<strong class="text-danger"> * </strong>
                                            </label>
                                            <input type="password" name="password_confirmation" autocomplete="off"
                                                class="form-control" id="password_confirmation">
                                            <span class="error error_password"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form_element rounded mb-1">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="loading-btn-box">
                                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                                    class="fas fa-spinner"></i></button>
                                            <button type="submit"
                                                class="btn btn-sm btn-success float-end submit_button">{{ __('Save Employee') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function copyToClipboard() {
            const text = document.getElementById("lastEmployeeId").innerText;
            const textarea = document.createElement("textarea");
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
            toastr.success("Text copied to clipboard!");
        }

        $('#add_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.error').html('');
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data);
                    $('#add_form')[0].reset();
                    $('.loading_button').hide();
                    $('#addModal').modal('hide');
                    $('.error').html('');
                },
                error: function(error) {
                    $('.loading_button').hide();

                    toastr.error(error.responseJSON.message);

                    ///field error.

                    $.each(error.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0].replace(' id ', ' '));

                    });
                }
            });
        });

        $('input').removeAttr('required');
        $('select').removeAttr('required');

        $("#user_image").change(function() {
            var file = $("#user_image").get(0).files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function() {
                    $("#p_avatar").attr("src", reader.result);
                    $("#p_avatar").removeClass("d-none");
                    $("#p_avatar").addClass("d-block");
                }
                reader.readAsDataURL(file);
            }
        });

        // get section by department
        $("#hrm_department_id").change(function() {
            var id = $(this).val();
            $.get("/hrm/get-section-by-department/" + id, function(data) {
                $('select[name="section_id"]').empty();
                $('select[name="sub_section_id"]').empty();
                $("#section_id").append('<option selected disabled>{{ __('Choose Section') }}</option>');
                $("#sub_section_id").append(
                    '<option selected disabled>{{ __('Choose Subsection') }}</option>');
                $.each(data, function(key, data) {
                    $('select[name="section_id"]').append('<option value="' + data.id + '">' + data
                        .name + '</option>');
                });
            })
        });

        // get sub section by section // get designation by section
        $("#section_id").change(function() {
            var id = $(this).val();
            $.get("/hrm/get-sub-section-by-section/" + id, function(data) {
                $('select[name="sub_section_id"]').empty();
                $("#sub_section_id").append(
                    '<option selected disabled>{{ __('Choose Subsection') }}</option>');
                $.each(data, function(key, data) {
                    $('select[name="sub_section_id"]').append('<option value="' + data.id + '">' +
                        data.name + '</option>');
                });
            })
            $.get("/hrm/get-designation-by-section/" + id, function(data) {
                $('select[name="designation_id"]').empty();
                $("#designation_id").append(
                    '<option selected disabled>{{ __('Choose Designation') }}</option>');
                $.each(data, function(key, data) {
                    $('select[name="designation_id"]').append('<option value="' + data.id + '">' +
                        data.name + '</option>');
                });
            })
        });

        // get district by division address
        $("#permanent_division_id").change(function() {
            var id = $(this).val();
            $.get("/core/locations/get-district-by-division/" + id, function(data) {
                $('select[name="permanent_district_id"]').empty();
                $('select[name="permanent_upazila_id"]').empty();
                $('select[name="permanent_union_id"]').empty();
                $("#permanent_district_id").append(
                    '<option selected="" disabled="">{{ __('Choose District') }}</option>');
                $("#permanent_upazila_id").append(
                    '<option selected="" disabled="">{{ __('Choose Upazila/Thana') }}</option>');
                $("#permanent_union_id").append(
                    '<option selected="" disabled="">{{ __('Choose Union/PostOffice') }}</option>');
                $.each(data, function(key, data) {
                    $('select[name="permanent_district_id"]').append('<option value="' + data.id +
                        '">' + data.name + '</option>');
                });
            })
        });

        // get upazila by district address
        $("#permanent_district_id").change(function() {
            var id = $(this).val();
            $.get("/core/locations/get-thana-by-district/" + id, function(data) {
                $('select[name="permanent_upazila_id"]').empty();
                $('select[name="permanent_union_id"]').empty();
                $("#permanent_upazila_id").append(
                    '<option selected="" disabled="">{{ __('Choose Upazila/Thana') }}</option>');
                $("#permanent_union_id").append(
                    '<option selected="" disabled="">{{ __('Choose Union/PostOffice') }}</option>');
                $.each(data, function(key, data) {
                    $('select[name="permanent_upazila_id"]').append('<option value="' + data.id +
                        '">' + data.name + '</option>');
                });
            })
        });

        //get union by upazila.
        $("#permanent_upazila_id").change(function() {
            var id = $(this).val();
            $.get("/core/locations/get-union-by-thana/" + id, function(data) {
                $('select[name="permanent_union_id"]').empty();
                $("#permanent_union_id").append(
                    '<option selected="" disabled="">{{ __('Choose Union/PostOffice') }}</option>');
                $.each(data, function(key, data) {
                    $('select[name="permanent_union_id"]').append('<option value="' + data.id +
                        '">' + data.name + '</option>');
                });
            })
        });

        $("#present_division_id").change(function() {
            var id = $(this).val();
            $.get("/core/locations/get-district-by-division/" + id, function(data) {
                $('select[name="present_district_id"]').empty();
                $('select[name="present_upazila_id"]').empty();
                $('select[name="present_union_id"]').empty();
                $("#present_district_id").append(
                    '<option selected="" disabled="">{{ __('Choose District') }}</option>');
                $("#present_upazila_id").append(
                    '<option selected="" disabled="">{{ __('Choose Upazila/Thana') }}</option>');
                $("#present_union_id").append(
                    '<option selected="" disabled="">{{ __('Choose Union/PostOffice') }}</option>');
                $.each(data, function(key, data) {
                    $('select[name="present_district_id"]').append('<option value="' + data.id +
                        '">' + data.name + '</option>');
                });
            })
        });

        // get upazila by district address
        $("#present_district_id").change(function() {
            var id = $(this).val();
            $.get("/core/locations/get-thana-by-district/" + id, function(data) {
                $('select[name="present_upazila_id"]').empty();
                $('select[name="present_union_id"]').empty();
                $("#present_upazila_id").append(
                    '<option selected="" disabled="">{{ __('Choose Upazila/Thana') }}</option>');
                $("#present_union_id").append(
                    '<option selected="" disabled="">{{ __('Choose Union/PostOffice') }}</option>');
                $.each(data, function(key, data) {
                    $('select[name="present_upazila_id"]').append('<option value="' + data.id +
                        '">' + data.name + '</option>');
                });
            })
        });

        //get union by upazila.
        $("#present_upazila_id").change(function() {
            var id = $(this).val();
            $.get("/core/locations/get-union-by-thana/" + id, function(data) {
                $('select[name="present_union_id"]').empty();
                $("#present_union_id").append(
                    '<option selected="" disabled="">{{ __('Choose Union/PostOffice') }}</option>');
                $.each(data, function(key, data) {
                    $('select[name="present_union_id"]').append('<option value="' + data.id + '">' +
                        data.name + '</option>');
                });
            })
        });

        $('#shift_id').select2();
        $('#sub_section_id').select2();
        $('#section_id').select2();
        $('#sub_section_id').select2();
        $('#hrm_department_id').select2();
        $('#designation_id').select2();
        $('#section_id').select2();
        $('#grade_id').select2();
        $('#employee_type').select2();
        $('#duty_type_id').select2();
        $('#mobile_banking_provider').select2();
        $('#gender').select2();
        $('#marital_status').select2();
        $('#blood').select2();
        $('#religion').select2();
        $('#country').select2();

        $('#permanent_division_id').select2();
        $('#permanent_district_id').select2();
        $('#permanent_upazila_id').select2();
        $('#permanent_union_id').select2();

        $('#present_division_id').select2();
        $('#present_district_id').select2();
        $('#present_upazila_id').select2();
        $('#present_union_id').select2();

        function valueChanged() {
            if ($('#p_same').is(":checked")) {
                $(".adds").hide();
            } else {
                $(".adds").show();
            }
        }

        $(".login_field").hide();

        function login_field() {
            if ($('#login_access').is(":checked"))
                $(".login_field").show();
            else
                $(".login_field").hide();
        }



        $('input[type="checkbox"]').click(function() {
            if ($(this).is(':checked')) {
                $(this).prop('value', 1);
            } else {
                $(this).prop('value', 0);

            }
        });



        function convertDate(gDate) {
            let date = gDate.split('-');
            let newDate = date[1] + '-' + date[0] + '-' + date[2];
            return newDate;
        }

        // new Litepicker({
        //         singleMode: true,
        //         element: document.getElementById('joiningDate'),
        //         dropdowns: {
        //             minYear: new Date().getFullYear() - 50,
        //             maxYear: new Date().getFullYear() + 100,
        //             months: true,
        //             years: true
        //         },
        //         tooltipText: {
        //             one: 'night',
        //             other: 'nights'
        //         },
        //         tooltipNumber: (totalDays) => {
        //             return totalDays - 1;
        //         },
        //         setup: (picker) => {
        //         picker.on('selected', (date1, date2) => {

        //             const joiningDateString = convertDate($('#joiningDate').val());
        //             const dobDateString = convertDate($('#dobDate').val());

        //             if (!dobDateString) {
        //                 const dobDate = new Date(dobDateString);
        //                 const joiningDate = new Date(joiningDateString);

        //                 const age = Math.abs(parseInt(((joiningDate.getTime() - dobDate.getTime()) / 31536000000)));
        //                 document.getElementById('dobString').innerHTML = `<span class="text-danger text-bold">Calculated Age = ${age} years</span>`;
        //             }

        //         });
        //         },
        //         format: 'MM-DD-YYYY'
        //     });

        // new Litepicker({
        //     singleMode: true,
        //     element: document.getElementById('terminationDate'),
        //     dropdowns: {
        //         minYear: new Date().getFullYear() - 50,
        //         maxYear: new Date().getFullYear() + 100,
        //         months: true,
        //         years: true
        //     },
        //     tooltipText: {
        //         one: 'night',
        //         other: 'nights'
        //     },
        //     tooltipNumber: (totalDays) => {
        //         return totalDays - 1;
        //     },
        //     format: 'MM-DD-YYYY'
        // });

        // new Litepicker({
        //     singleMode: true,
        //     element: document.getElementById('dobDate'),
        //     dropdowns: {
        //         minYear: new Date().getFullYear() - 50,
        //         maxYear: new Date().getFullYear() + 100,
        //         months: true,
        //         years: true
        //     },
        //     tooltipText: {
        //         one: 'night',
        //         other: 'nights'
        //     },
        //     tooltipNumber: (totalDays) => {
        //         return totalDays - 1;
        //     },
        //     format: 'MM-DD-YYYY',
        //     setup: (picker)=> {
        //         picker.on('selected', (date1, date2) => {
        //             const joiningDateString = convertDate($('#joiningDate').val());
        //             const joiningDate = new Date(joiningDateString);
        //             const dobDateString = convertDate($('#dobDate').val());

        //             if (dobDateString) {
        //                 const dobDate = new Date(dobDateString);
        //                 const age = Math.abs(parseInt(((joiningDate.getTime() - dobDate.getTime()) / 31536000000)));
        //                 document.getElementById('dobString').innerHTML = `<span class="text-info text-bold">Join Vs DOB difference = ${age} years</span>`;
        //             }
        //         })
        //     }
        // });
    </script>
@endpush
