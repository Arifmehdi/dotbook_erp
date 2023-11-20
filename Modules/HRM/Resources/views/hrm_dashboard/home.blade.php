@extends('layout.master')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('title', 'HRM Dashboard - ')

@section('content')

    <div id="dashboard" class="pb-1">
        <div class="main__content">
            <div class="dashboard-bg">
                <div class="d-flex justify-content-between align-items-center pt-2 px-1">
                    <h5 class="mb-0">HRM Dashboard</h5>
                    <div class="select-dropdown">
                        <select name="date" id="date">
                            <option value="" selected>@lang('menu.current_day')</option>
                            <option value="">@lang('menu.this_week')</option>
                            <option value="">@lang('menu.this_month')</option>
                            <option value="">@lang('menu.this_year')</option>
                            <option value="">@lang('menu.all_time')</option>
                        </select>
                    </div>
                </div>

                {{-- Cards --}}
                <div class="px-1 pt-2">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-primary">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-users"></i>
                                    </div>
                                    <h1 class="title">
                                        {{ $employee_number }}
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Active Employee') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-success">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-user-check"></i>
                                    </div>
                                    <h1 class="title">
                                        {{ $attendances_number }}
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Presents') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-info">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-user-xmark"></i>
                                    </div>
                                    <h1 class="title">
                                        {{ $absent_number }}
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Absents') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-danger">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-paper-plane"></i>
                                    </div>
                                    <h1 class="title">
                                        {{ $leave_count }}
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Leaves') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-info">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-file-invoice-dollar"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Income') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-danger">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-chart-line-down"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Expense') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-primary">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-recycle"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                        {{-- {{ $request }} --}}
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">Asset Request</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-success">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-briefcase"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">@lang('menu.total_asset_in_service')</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-15">
        <div class="row g-1">
            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Monthwise Employee Joined History</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div id="monthly_employee_joined_history"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Department Wise Blood Group Statics</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div id="department_wise_blood_group_statics"></div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Job Status Wise Employee (%)</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div id="job_status_wise_employee"></div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Religious Diversity Graph</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div id="religious_diversity_graph"></div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Gender Wise Employee (Active)</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div id="gender_wise_employee"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Leave Status</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Leave Type</th>
                                        <th>Allowed Balance</th>
                                        <th>Availed Balance</th>
                                        <th>Current Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Md. Nur Hossain Rasel</td>
                                        <td>Annual Leave</td>
                                        <td>15.0</td>
                                        <td>12.0</td>
                                        <td>3.0</td>
                                    </tr>
                                    <tr>
                                        <td>Md. Nur Hossain Rasel</td>
                                        <td>Annual Leave</td>
                                        <td>15.0</td>
                                        <td>12.0</td>
                                        <td>3.0</td>
                                    </tr>
                                    <tr>
                                        <td>Md. Nur Hossain Rasel</td>
                                        <td>Annual Leave</td>
                                        <td>15.0</td>
                                        <td>12.0</td>
                                        <td>3.0</td>
                                    </tr>
                                    <tr>
                                        <td>Md. Nur Hossain Rasel</td>
                                        <td>Annual Leave</td>
                                        <td>15.0</td>
                                        <td>12.0</td>
                                        <td>3.0</td>
                                    </tr>
                                    <tr>
                                        <td>Md. Nur Hossain Rasel</td>
                                        <td>Annual Leave</td>
                                        <td>15.0</td>
                                        <td>12.0</td>
                                        <td>3.0</td>
                                    </tr>
                                    <tr>
                                        <td>Md. Nur Hossain Rasel</td>
                                        <td>Annual Leave</td>
                                        <td>15.0</td>
                                        <td>12.0</td>
                                        <td>3.0</td>
                                    </tr>
                                    <tr>
                                        <td>Md. Nur Hossain Rasel</td>
                                        <td>Annual Leave</td>
                                        <td>15.0</td>
                                        <td>12.0</td>
                                        <td>3.0</td>
                                    </tr>
                                    <tr>
                                        <td>Md. Nur Hossain Rasel</td>
                                        <td>Annual Leave</td>
                                        <td>15.0</td>
                                        <td>12.0</td>
                                        <td>3.0</td>
                                    </tr>
                                    <tr>
                                        <td>Md. Nur Hossain Rasel</td>
                                        <td>Annual Leave</td>
                                        <td>15.0</td>
                                        <td>12.0</td>
                                        <td>3.0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-6 col-xl-12 col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Monthwise Leave Application Statistics</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div id="monthwise_leave_application_statistics"></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Last 30 Days Attendance Summary</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div id="last_30_days_attendance_summary"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>{{ __('Recent Award') }}</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="display data__table data_tble due_table" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ __('SL.') }}</th>
                                        <th class="text-center">{{ __('Employee') }}</th>
                                        <th class="text-center">{{ __('Gift') }}</th>
                                        <th class="text-center">{{ __('Award By') }}</th>
                                        <th class="text-center">{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($award_object as $key => $award)
                                        <tr>
                                            <td class="text-center">{{ $loop->index + 1 }}</td>
                                            <td>{{ $award?->employee->name }}</td>
                                            <td>{{ $award->gift_item }}</td>
                                            <td>{{ $award->award_by }}</td>
                                            <td>
                                                {{-- {{ date(config('hrm.date_format'),strtotime($award->created_at)) }} <br> --}}
                                                {{ '(' . \Carbon\Carbon::parse($award->created_at)->diffForHumans() . ')' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"><span
                                                    class="d-block text-center">{{ __('There has no data') }}</span></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>{{ __('Latest Notice') }}</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="display data__table data_tble due_table" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ __('SL.') }}</th>
                                        <th class="text-center">{{ __('Title') }}</th>
                                        <th class="text-center">{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($notice_object as $key => $notice)
                                        <tr>
                                            <td class="text-center">{{ $loop->index + 1 }}</td>
                                            <td>{{ $notice->title }}</td>
                                            <td>
                                                {{-- {{ date(config('hrm.date_format'),strtotime($notice->created_at)) }} <br> --}}
                                                {{ '(' . \Carbon\Carbon::parse($notice->created_at)->diffForHumans() . ')' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"><span
                                                    class="d-block text-center">{{ __('There has no data') }}</span></td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>
                            {{ __('New Arrival') }} -
                            <b>
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}
                            </b>
                        </h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center">
                                            <input class="form-check-input mb-1" type="checkbox" value="">
                                            Default checkbox
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="stock_alert_table" class="display data__table data_tble stock_table"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('SL.') }}</th>
                                        <th>{{ __('Employee ID') }}</th>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Section') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Present Address') }}</th>
                                        <th>{{ __('Joining Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employee_latest as $employee)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $employee?->employee_id }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee?->section?->name }}</td>
                                            <td>{{ $employee->phone }}</td>
                                            <td>{{ $employee?->present_village }}</td>
                                            <td>{{ $employee->joining_date }}</td>
                                        </tr>
                                    @empty
                                        <td colspan="7"><span
                                                class="d-block text-center">{{ __('There has no data') }}</span></td>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Add shortcut menu modal-->
    <div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">@lang('menu.add_shortcut_menus')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="modal-body_shortcuts">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        Highcharts.chart('monthly_employee_joined_history', {
            chart: {
                height: 380,
            },
            title: {
                text: '',
            },
            tooltip: {
                headerFormat: '<span style="font-weight:bold; font-size:12px;">{point.key}</span><br/>',
            },
            yAxis: {
                title: {
                    text: 'Number of Employees Joined'
                }
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            series: [{
                name: "Joined",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 27, 22, 27, 14],
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
                            var linkUrl = 'https://example.com';
                            window.open(linkUrl, '_blank');
                        }
                    }
                }
            }]
        });

        Highcharts.chart('department_wise_blood_group_statics', {
            chart: {
                height: 380,
                type: 'column'
            },
            title: {
                text: ""
            },
            xAxis: {
                categories: ['Easytrax', 'Tradehub', 'No branch assigned'],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Number Of Employee'
                }
            },
            tooltip: {
                headerFormat: '<span style="display:inline-block;font-size:12px;margin-bottom:5px"><strong>{point.key}</strong></span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f} Person</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.1,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'A+',
                color: '#7ea8ed',
                data: [44, 55, 57],
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
                            var linkUrl = 'https://example.com';
                            window.open(linkUrl, '_blank');
                        }
                    }
                }
            }, {
                name: 'A-',
                color: '#363636',
                data: [76, 85, 101],
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
                            var linkUrl = 'https://example.com';
                            window.open(linkUrl, '_blank');
                        }
                    }
                }
            }, {
                name: 'B+',
                color: '#77d497',
                data: [35, 41, 36],
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
                            var linkUrl = 'https://example.com';
                            window.open(linkUrl, '_blank');
                        }
                    }
                }
            }, {
                name: 'B-',
                color: '#e8a751',
                data: [44, 55, 57],
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
                            var linkUrl = 'https://example.com';
                            window.open(linkUrl, '_blank');
                        }
                    }
                }
            }, {
                name: 'AB+',
                color: '#b67bdb',
                data: [76, 85, 101],
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
                            var linkUrl = 'https://example.com';
                            window.open(linkUrl, '_blank');
                        }
                    }
                }
            }, {
                name: 'AB-',
                color: '#f073d5',
                data: [35, 41, 36],
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
                            var linkUrl = 'https://example.com';
                            window.open(linkUrl, '_blank');
                        }
                    }
                }
            }, {
                name: 'O+',
                color: '#73c6f0',
                data: [76, 85, 101],
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
                            var linkUrl = 'https://example.com';
                            window.open(linkUrl, '_blank');
                        }
                    }
                }
            }, {
                name: 'O-',
                color: '#22734f',
                data: [35, 41, 36],
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
                            var linkUrl = 'https://example.com';
                            window.open(linkUrl, '_blank');
                        }
                    }
                }
            }],
        });


        Highcharts.chart('job_status_wise_employee', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: 325,
            },
            title: {
                text: '',
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: ({point.percentage:.1f} %)<br>Total: {point.y}'
                    }
                }
            },
            series: [{
                name: 'Employee',
                colorByPoint: true,
                data: [{
                    name: 'Active',
                    y: {{  $all_employee_active  }},
                }, {
                    name: 'Not In Service',
                    y: 0
                }, {
                    name: 'Terminated',
                    y: {{ $all_employee_terminated }}
                }, {
                    name: 'Left',
                    y: {{ $all_employee_left }}
                }, {
                    name: 'Resigned',
                    y: {{ $all_employee_resigned }}
                }, {
                    name: 'Dismissed',
                    y: 3
                }, {
                    name: 'Not Defined',
                    y: 5
                }]
            }]
        });

        Highcharts.chart('religious_diversity_graph', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: 325,
            },
            title: {
                text: '',
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: ({point.percentage:.1f} %)<br>Total: {point.y}'
                    }
                }
            },
            series: [{
                name: 'Employee',
                colorByPoint: true,
                data: [
                    {
                    name: "Muslim",
                    y: {{ $employee_muslim }},
                    },
                    {
                    name: "Hindu",
                    y: {{ $employee_hindu }},
                    },
                    {
                    name: "Buddhist",
                    y: {{ $employee_buddhist }},
                    },
                    {
                    name: "Others",
                    y: {{ $employee_religion_other }},
                    },
                    {
                    name: "Christain",
                    y: {{ $employee_christian }},
                    },
                    {
                    name: "Not Define",
                    y: {{ $employee_religion_not_define }},
                    },
                ]
            }]
        });


        Highcharts.chart('gender_wise_employee', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: 325,
            },
            title: {
                text: '',
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: ({point.percentage:.1f} %)<br>Total: {point.y}'
                    }
                }
            },
            series: [{
                name: 'Employee',
                colorByPoint: true,
                data: [{
                    name: 'Male',
                    y: {{ $employee_male }},
                }, {
                    name: 'Female',
                    y: {{ $employee_female }}
                }, {
                    name: 'Others',
                    y: {{ $employee_others }}
                },
                {
                    name: 'Not Defined',
                    y: {{ $employee_not_defined }}
                }]
            }]
        });


        Highcharts.chart('monthwise_leave_application_statistics', {
            chart: {
                height: 330,
                type: 'column'
            },
            title: {
                text: "",
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Number of Leave Application'
                }
            },
            tooltip: {
                headerFormat: '<span style="display:inline-block;font-size:12px;margin-bottom:5px"><strong>{point.key}</strong></span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.1,
                    borderWidth: 0
                }
            },
            series: [{
                name: "Annual Leave",
                color: "#83ba2d",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 30, 22, 30, 14]
            }],
        });


        Highcharts.chart('last_30_days_attendance_summary', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: generateLast30Days()
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Employee Count'
                }
            },
            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: 'number'
                }
            },
            series: [{
                name: 'Present',
                color: "#99bf5c",
                data: [15, 17, 18, 16, 16, 19, 0, 17, 18, 14, 19, 0, 15, 0, 12, 16, 17, 19, 13, 14, 0, 14, 12, 14, 14, 14, 14, 0, 14, 14]
            }, {
                name: 'Absent',
                color: "#ff837a",
                data: [0, 1, 3, 0, 3, 0, 0, 3, 2, 3, 0, 0, 8, 0, 3, 0, 4, 3, 2, 3, 0, 4, 3, 2, 3, 0, 4, 0, 2, 3]
            }, {
                name: 'Extreme Delay',
                color: "#80d0d1",
                data: [1, 2, 0, 0, 0, 1, 0, 2, 0, 2, 1, 0, 0, 0, 2, 1, 0, 0, 1, 0, 0, 0, 2, 0, 2, 0, 0, 0, 1, 0]
            },{
                name: 'Delay',
                color: "#85d4a9",
                data: [4, 4, 2, 4, 4, 4, 0, 2, 4, 4, 4, 0, 2, 0, 4, 4, 4, 2, 4, 4, 0, 4, 2, 4, 4, 4, 4, 0, 4, 4]
            }, {
                name: 'Weekend',
                color: "#dbd9d9",
                data: [0, 0, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 0, 25, 0, 0]
            }, {
                name: 'Leave',
                color: "#808080",
                data: [1, 0, 0, 1, 2, 1, 0, 2, 1, 2, 1, 0, 0, 0, 2, 1, 2, 2, 1, 2, 0, 2, 2, 1, 2, 1, 2, 0, 1, 2]
            },{
                name: 'Visit',
                color: "#8fd0ff",
                data: [4, 1, 2, 4, 0, 0, 0, 2, 4, 4, 4, 0, 0, 0, 4, 4, 4, 2, 4, 4, 0, 4, 2, 4, 4, 4, 4, 0, 4, 4]
            }, {
                name: 'Holiday',
                color: "#a1a1a1",
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }]
        });
        function generateLast30Days() {
            var categories = [];
            var today = new Date();
            for (var i = 29; i >= 0; i--) {
                var date = new Date(today);
                date.setDate(date.getDate() - i);
                var dateString = formatDate(date);
                categories.push(dateString);
            }
            return categories;
        }
        function formatDate(date) {
            var options = {day: '2-digit', month: '2-digit'};
            return date.toLocaleDateString('en-US', options);
        }
    </script>
@endpush
