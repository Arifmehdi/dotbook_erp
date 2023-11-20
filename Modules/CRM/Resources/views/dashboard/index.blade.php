@extends('layout.master')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">
    <style>
        .expense-list {
            border: 1px solid rgba(0, 0, 0, .1);
            border-radius: 3px;
            padding: 10px;
            height: 240px;
            overflow: auto;
        }

        .amount {
            font-weight: 500;
        }

        .fz-16 {
            font-size: 26px !important;
        }

        .amount.up {
            color: #22b92f;
        }

        .amount.down {
            color: #e42f2f;
        }

        .amount.down i {
            transform: rotate(180deg);
        }

        .border-bottom.border-2 {
            border-width: 0 !important;
            border-bottom-width: 2px !important;
        }
    </style>
@endpush

@section('title', 'CRM Dashboard - ')

@section('content')

    <div id="dashboard" class="pb-1">
        <div class="main__content">
            <div class="dashboard-bg">
                <div class="d-flex justify-content-between align-items-center pt-2 px-1">
                    <h5 class="mb-0">CRM Dashboard</h5>
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
                                        <i class="fa-light fa-circle-user"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Contact') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-success">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-buildings"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Company') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-info">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-hand-holding-seedling"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Opportunity') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-danger">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-file-invoice"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Quote') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-danger">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-money-bill-wave"></i>
                                    </div>
                                    <h1 class="title">
                                        7 / 11
                                    </h1>
                                </div>
                                <div class="numbers px-1 pt-1">
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" aria-label="Basic example"
                                            style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <h3 class="sub-title">{{ __('Invoices Awaiting Payment') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-danger">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-chart-line-up"></i>
                                    </div>
                                    <h1 class="title">
                                        7 / 51
                                    </h1>
                                </div>
                                <div class="numbers px-1 pt-1">
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" aria-label="Basic example"
                                            style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <h3 class="sub-title">{{ __('Converted Leads') }}</h3>
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
                        <h6>Recent Contacts</h6>
                        <div class="d-flex gap-2 align-items-center">
                            <a href="#">View More</a>
                            <div class="dropdown">
                                <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" aria-expanded="false">
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
                    </div>
                    <div class="card-body">
                        <div class="table-responsive px-0">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Create Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Recent Companies</h6>
                        <div class="d-flex gap-2 align-items-center">
                            <a href="#">View More</a>
                            <div class="dropdown">
                                <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" aria-expanded="false">
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
                    </div>
                    <div class="card-body">
                        <div class="table-responsive px-0">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Create Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>john@doe.com</td>
                                        <td>015 0000 0000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Recent Opportunities</h6>
                        <div class="d-flex gap-2 align-items-center">
                            <a href="#">View More</a>
                            <div class="dropdown">
                                <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" aria-expanded="false">
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
                    </div>
                    <div class="card-body">
                        <div class="table-responsive px-0">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Owner</th>
                                        <th>Amount</th>
                                        <th>Create Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>15,000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>15,000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>15,000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>15,000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>15,000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>15,000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>15,000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>15,000</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Recent Quotes</h6>
                        <div class="d-flex gap-2 align-items-center">
                            <a href="#">View More</a>
                            <div class="dropdown">
                                <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" aria-expanded="false">
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
                    </div>
                    <div class="card-body">
                        <div class="table-responsive px-0">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Owner</th>
                                        <th>Quoteation Date</th>
                                        <th>Create Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>July 3rd 2023</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>July 3rd 2023</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>July 3rd 2023</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>July 3rd 2023</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>July 3rd 2023</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>July 3rd 2023</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>July 3rd 2023</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                    <tr>
                                        <td>John Doe</td>
                                        <td>Speeddigit Soft Solution</td>
                                        <td>July 3rd 2023</td>
                                        <td>July 2nd 2023</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card m-0">
                    <div class="row g-0 p-0">
                        <div class="col-md-4">
                            <div class="card-header">
                                <h6><i class="fa-light fa-file-invoice"></i> Invoice Overview</h6>
                            </div>
                            <div class="card-body">
                                <div class="overview-list">
                                    <ul>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>0 Draft</span>
                                                <span>0.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar" role="progressbar" aria-label="Basic example"
                                                    style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>7 Not Sent</span>
                                                <span>63.64%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>3 Unpaid</span>
                                                <span>27.27%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>4 Partially Paid</span>
                                                <span>36.36%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>0 Overdue</span>
                                                <span>0.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>4 Paid</span>
                                                <span>36.36%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body border-top">
                                <div class="card m-0">
                                    <div class="card-body px-3 py-2">
                                        <h6 class="text-warning mb-2">Outstanding Invoices</h6>
                                        <h6 class="text-primary">$16,864.80</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-header">
                                <h6><i class="fa-light fa-file"></i> Estimate Overview</h6>
                            </div>
                            <div class="card-body">
                                <div class="overview-list">
                                    <ul>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>6 Draft</span>
                                                <span>42.86%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-dark" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>6 Not Sent</span>
                                                <span>42.86%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>3 Sent</span>
                                                <span>41.43%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>0 Expired</span>
                                                <span>0.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>5 Declined</span>
                                                <span>35.71%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>0 Accepted</span>
                                                <span>0.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body border-top">
                                <div class="card m-0">
                                    <div class="card-body px-3 py-2">
                                        <h6 class="text-Secondary mb-2">Past Due Invoices</h6>
                                        <h6 class="text-primary">$0.00</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-header">
                                <h6><i class="fa-light fa-file-lines"></i> Proposal Overview</h6>
                            </div>
                            <div class="card-body">
                                <div class="overview-list">
                                    <ul>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>0 Draft</span>
                                                <span>0.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>0 Sent</span>
                                                <span>0.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>1 Open</span>
                                                <span>50.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>0 Revised</span>
                                                <span>0.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>0 Declined</span>
                                                <span>0.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="progress-text d-flex justify-content-between">
                                                <span>1 Accepted</span>
                                                <span>50.00%</span>
                                            </div>
                                            <div class="progress h-5">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    aria-label="Basic example" style="width: 25%" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body border-top">
                                <div class="card m-0">
                                    <div class="card-body px-3 py-2">
                                        <h6 class="text-success mb-2">Paid Invoices</h6>
                                        <h6 class="text-primary">$15,487.00</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Payment Records</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-expanded="false">
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
                    <div class="card-body p-0">
                        <div id="payment_records"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Leads Overview</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-expanded="false">
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
                    <div class="card-body p-0">
                        <div id="leads_overview"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Tickets Awaiting Reply by Status</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-expanded="false">
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
                    <div class="card-body p-0">
                        <div id="ticket_awaiting_reply_by_status"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Tickets Awaiting Reply by Department</h6>
                        <div class="dropdown">
                            <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-expanded="false">
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
                    <div class="card-body p-0">
                        <div id="ticket_awaiting_reply_by_department"></div>
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
        Highcharts.chart('payment_records', {
            chart: {
                type: 'column',
                height: 390
            },
            title: {
                text: '',
            },
            xAxis: {
                categories: ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            series: [{
                name: 'This Week Payments',
                data: [60, 100, 95, 21, 100, 10, 20]
            }, {
                name: 'Last Week Payments',
                data: [90, 40, 110, 95, 105, 15, 25]
            }]
        });


        const colors = Highcharts.getOptions().colors.map((c, i) =>
            Highcharts.color(Highcharts.getOptions().colors[0])
            .brighten((i - 3) / 7)
            .get()
        );
        Highcharts.chart('leads_overview', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: 400
            },
            title: {
                text: '',
            },
            tooltip: {
                pointFormat: '{point.percentage:.1f}%</b>'
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
                    colors,
                    borderRadius: 5,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b><br>{point.y}',
                        distance: -50,
                        filter: {
                            property: 'percentage',
                            operator: '>',
                            value: 4
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Leads',
                data: [{
                        name: 'New',
                        y: 74
                    },
                    {
                        name: 'Contacted',
                        y: 68
                    },
                    {
                        name: 'Qualified',
                        y: 64
                    },
                    {
                        name: 'Working',
                        y: 72
                    },
                    {
                        name: 'Proposal Sent',
                        y: 67
                    },
                    {
                        name: 'Customer',
                        color: '#13ab50',
                        y: 70
                    },
                    {
                        name: 'Lost Leads',
                        color: '#d13111',
                        y: 75
                    }
                ]
            }]
        });


        Highcharts.chart('ticket_awaiting_reply_by_status', {
            chart: {
                renderTo: 'chart-container',
                type: 'pie'
            },
            title: {
                text: ''
            },
            plotOptions: {
                pie: {
                    innerSize: '50%',
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        distance: -40
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Tickets Awaiting Reply',
                data: [{
                        name: 'In Progress',
                        y: 74
                    },
                    {
                        name: 'On Hold',
                        y: 68
                    }
                ]
            }]
        });


        Highcharts.chart('ticket_awaiting_reply_by_department', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: 400
            },
            title: {
                text: '',
            },
            tooltip: {
                pointFormat: '{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    innerSize: '50%',
                    allowPointSelect: true,
                    cursor: 'pointer',
                    borderRadius: 5,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b><br>{point.y}',
                        distance: -50,
                        filter: {
                            property: 'percentage',
                            operator: '>',
                            value: 4
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Share',
                data: [{
                        name: 'Marketing',
                        color: "#09e6bd",
                        y: 74
                    },
                    {
                        name: 'Sales',
                        color: "#08aecf",
                        y: 38
                    }
                ]
            }]
        });
    </script>
@endpush
