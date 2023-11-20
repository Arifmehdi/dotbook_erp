<div class="">
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="card-img-actions d-inline-block mb-3">
                    </div>
                    <div class="profile-top p-0">
                        @if (isset($employee->photo) && file_exists(public_path("uploads/employees/$employee->photo")))
                            <img class="img-fluid rounded-circle" id="photo"
                                src="{{ asset('uploads/employees/' . $employee->photo) }}" width="175" height="180"
                                alt="Image not found">
                        @else
                            <div class="part-img">
                                <img src="{{ asset('images/profile-picture.jpg') }}" alt="Not found">
                            </div>
                        @endif
                    </div>
                    <h6 class="font-weight-semibold mb-0 mt-3">{{ $employee->id ?? '' }}</h6>
                    <h6 class="font-weight-semibold mb-0">{{ $employee->name ?? '' }}</h6>
                    <span class="d-block text-muted">{{ __('Section') }}:
                        {{ $employee->section->name ?? 'Section Name Not Available' }}</span>
                    <span class="d-block text-muted">{{ __('Grade') }}: {{ $employee->grade->name ?? '' }} </span>
                    <span class="d-block mb-3"><b>{{ __('Employee') }}: {{ $employee->employee_id ?? '' }} </b></span>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-12 border rounded">
                    <div class="py-2">
                        <h5 class="card-title border-bottom py-2">@lang('menu.personal_information')</h5>
                    </div>
                    <div>
                        <table class="table table-borderless">
                            <tr>
                                <th class="bg-transparent text-dark">@lang('menu.name') </th>
                                <td>: {{ $employee->name ?? '' }}</td>
                                <th class="bg-transparent text-dark">@lang('menu.phone') </th>
                                <td>: {{ $employee->phone ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark">@lang('menu.email') </th>
                                <td>: {{ $employee->email ?? '' }}</td>
                                <th class="bg-transparent text-dark">@lang('menu.country') </th>
                                <td>: {{ $employee->country ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark">@lang('menu.city') </th>
                                <td>: {{ $employee->permanentDistrict->name ?? '' }}</td>
                                <th class="bg-transparent text-dark">@lang('menu.zip_code') </th>
                                <td>: {{ $employee->zipcode ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark">{{ __('Father') }} </th>
                                <td>: {{ $employee->father_name ?? '' }}</td>
                                <th class="bg-transparent text-dark">{{ __('Mother') }} </th>
                                <td>: {{ $employee->mother_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark">{{ __('Religion') }} </th>
                                <td>: {{ $employee->religion ?? '' }}</td>
                                <th class="bg-transparent text-dark">@lang('menu.district') </th>
                                <td>: {{ $employee->district_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark">@lang('menu.upazilla') </th>
                                <td>: {{ $employee->upazila_name ?? '' }} </td>
                                <th class="bg-transparent text-dark">@lang('menu.union') </th>
                                <td>: {{ $employee->union_name ?? '' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-12 border rounded">
                    <div class="py-2">
                        <h5 class="card-title border-bottom py-2">{{ __('Biographical Information') }}</h5>
                    </div>
                    <div>
                        <table class="table-borderless">
                            <tr>
                                <th class="bg-transparent text-dark"> @lang('menu.date_of_birth') </th>
                                <td>: {{ $employee->dob ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark"> @lang('menu.gender') </th>
                                <td>: {{ $employee->gender ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark"> @lang('menu.marital_status') </th>
                                <td>: {{ $employee->marital_status ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark"> @lang('menu.blood_group') </th>
                                <td>: {{ $employee->blood ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark"> {{ __('Present Address') }} </th>
                                <td>: {{ $employee->present_address ?? '' }} </td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark"> {{ __('Permanent Address') }} </th>
                                <td>: {{ $employee->permanent_address ?? '' }} </td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark"> @lang('menu.nid_no') </th>
                                <td>: {{ $employee->nid ?? '' }} </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2 g-1">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header header-elements-inline bg-dark text-white ">
                    <h5 class="card-title">{{ __('Official Information') }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped ">
                        <tbody>
                            <tr>
                                <td><strong>{{ __('Department') }}</strong></td>
                                <td>{{ $employee->hrmDepartment->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Section') }}</strong></td>
                                <td>{{ $employee->section->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Designation') }}</strong></td>
                                <td>{{ $employee->designation->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Joining Date') }}</strong></td>
                                <td>{{ $employee->joining_date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Starting Salary') }}</strong> </td>
                                <td>{{ $employee->starting_salary ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Present Salary') }}</strong> </td>
                                <td>{{ $employee->salary ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Grade') }}</strong> </td>
                                <td>{{ $employee->grade->name ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header header-elements-inline bg-dark text-white">
                    <h5 class="card-title">{{ __('Payment Information') }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td><strong>{{ __('Mobile Banking Provider') }}</strong></td>
                                <td>{{ $employee->mobile_banking_provider ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Mobile Account Number') }}</strong></td>
                                <td>{{ $employee->mobile_banking_account_number ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>@lang('menu.bank_name')</strong></td>
                                <td>{{ $employee->bank_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>@lang('menu.bank_branch_name')</strong></td>
                                <td>{{ $employee->bank_branch_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Bank Account Name') }}</strong></td>
                                <td>{{ $employee->bank_account_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Bank Account Number') }}</strong></td>
                                <td>{{ $employee->bank_account_number ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>@lang('menu.status')</strong></td>
                                @if ($employee->employment_status == 1)
                                    <td><span class="text-success">{{ __('Active') }}</span></td>
                                @else
                                    <td><span class="text-danger">{{ __('In-active') }}</span></td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header header-elements-inline bg-dark text-white">
                    <h5 class="card-title">@lang('menu.other_information')</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td><strong>{{ __('Emergency Person') }}</strong></td>
                                <td>{{ $employee->emergency_contact_person_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Emergency Contact') }}</strong></td>
                                <td>{{ $employee->emergency_contact_person_phone ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Emergency Relation') }}</strong></td>
                                <td>{{ $employee->emergency_contact_person_relation ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Home Phone') }}</strong></td>
                                <td>{{ $employee->home_phone ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
