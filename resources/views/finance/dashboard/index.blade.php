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

@section('title', 'Finance Dashboard - ')

@section('content')

    <div id="dashboard" class="pb-1">
        <div class="main__content">
            <div class="dashboard-bg">
                <div class="d-flex justify-content-between align-items-center pt-2 px-1">
                    <h5 class="mb-0">Finance Dashboard</h5>
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
                        <div class="col-lg-6">
                            <div class="row g-3">
                                <div class="col-md-6 col-sm-6">
                                    <div class="card-counter alert-primary">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-hand-holding-dollar"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">{{ __('Account Receivable') }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6">
                                    <div class="card-counter alert-success">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-envelope-open-dollar"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">{{ __('Account Payable') }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6">
                                    <div class="card-counter alert-info">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-receipt"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">{{ __('Total Receipt') }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6">
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
                                            <h3 class="sub-title">{{ __('Total Payment') }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6">
                                    <div class="card-counter alert-primary">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-chart-mixed"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Gross Profit</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6">
                                    <div class="card-counter alert-success">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-chart-column"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Net Profit Before Tax</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="row g-3">
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="card-counter alert-info">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-chart-simple"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">{{ __('Total Revenue') }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="card-counter alert-danger">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-bag-shopping"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">{{ __('Total Purchase') }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="card-counter alert-success">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-chart-candlestick"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Burn Rate</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="card-counter alert-success">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-money-bill-wave"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Net Operating Cash Flow</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="card-counter alert-success">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-money-bill"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Total Operating Expense</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="card-counter alert-success">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-sack-dollar"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Total Incomes</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card-counter text-dark py-1">
                                        <span>Cash/Bank Balance</span>
                                        <div class="row g-3">
                                            <label class="col-lg-2 col-3 d-block text-right">Account :</label>
                                            <div class="col-lg-10 col-9">
                                                <select class="form-control form-select">
                                                    <option value="0">Business Bank Account</option>
                                                    <option value="1">2</option>
                                                    <option value="2">3</option>
                                                    <option value="3">4</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <span>Closing Balance :</span>
                                            <h2 class="title">$1,760.54</h2>
                                        </div>
                                    </div>
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
                        <h6>Income Statement</h6>
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
                        <table class="table table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="bg-transparent text-dark">Particular</th>
                                    <th class="bg-transparent text-dark">Actual Amount</th>
                                    <th class="bg-transparent text-dark">Variance Act vs Tar</th>
                                    <th class="bg-transparent text-dark">Variance Act vs LY</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Revenue</td>
                                    <td><span class="amount">108,942</span></td>
                                    <td><span class="amount up">0.9% <i class="fa-solid fa-caret-up"></i></span></td>
                                    <td><span class="amount down">0.2% <i class="fa-solid fa-caret-up"></i></span></td>
                                </tr>
                                <tr>
                                    <td>Cost of Goods Sold</td>
                                    <td class="border-bottom"><span class="amount">108,942</span></td>
                                    <td class="border-bottom"><span class="amount up">0.9% <i class="fa-solid fa-caret-up"></i></span></td>
                                    <td class="border-bottom"><span class="amount down">0.2% <i class="fa-solid fa-caret-up"></i></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Gross Profit</td>
                                    <td><span class="amount">108,942</span></td>
                                    <td><span class="amount up">0.9% <i class="fa-solid fa-caret-up"></i></span></td>
                                    <td><span class="amount down">0.2% <i class="fa-solid fa-caret-up"></i></span></td>
                                </tr>
                                <tr>
                                    <td>Total Operating Expenses</td>
                                    <td class="border-bottom"><span class="amount">108,942</span></td>
                                    <td class="border-bottom"><span class="amount up">0.9% <i class="fa-solid fa-caret-up"></i></span></td>
                                    <td class="border-bottom"><span class="amount down">0.2% <i class="fa-solid fa-caret-up"></i></span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Operating Profit (EBIT)</td>
                                    <td><span class="amount">108,942</span></td>
                                    <td><span class="amount up">0.9% <i class="fa-solid fa-caret-up"></i></span></td>
                                    <td><span class="amount down">0.2% <i class="fa-solid fa-caret-up"></i></span></td>
                                </tr>
                                <tr>
                                    <td>Other Non-Operating Income/(Expenses)</td>
                                    <td><span class="amount">108,942</span></td>
                                    <td><span class="amount up">0.9% <i class="fa-solid fa-caret-up"></i></span></td>
                                    <td><span class="amount down">0.2% <i class="fa-solid fa-caret-up"></i></span></td>
                                </tr>
                                <tr>
                                    <td>Finance Expense</td>
                                    <td class="border-bottom"><span class="amount">108,942</span></td>
                                    <td class="border-bottom"><span class="amount up">0.9% <i class="fa-solid fa-caret-up"></i></span></td>
                                    <td class="border-bottom"><span class="amount down">0.2% <i class="fa-solid fa-caret-up"></i></span></td>
                                </tr>
                                <tr>
                                    <td class="fz-16">Net Profit Before Tax</td>
                                    <td class="border-bottom border-2"><span class="amount fz-16">108,942</span></td>
                                    <td class="border-bottom border-2"><span class="amount fz-16 up">0.9% <i class="fa-solid fa-caret-up"></i></span></td>
                                    <td class="border-bottom border-2"><span class="amount fz-16 down">0.2% <i class="fa-solid fa-caret-up"></i></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Operating Expenses</h6>
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
                    <div class="card-body pb-1">
                        <div class="expense-list">
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-expense d-flex align-items-center">
                                <div class="w-60 d-flex justify-content-between pe-2">
                                    <span class="expense-name">Advertising</span>
                                    <span class="expense-amount">$2,309.47</span>
                                </div>
                                <div class="w-40">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="total-expense pt-2 d-flex justify-content-center align-items-center gap-2">
                            <span>Total Operating Expenses</span>
                            <h1>$4,516.27</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Income & Expenses (Last 12 Months)</h6>
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
                        <div id="income_n_expens_last_12_month"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Profit & Lose</h6>
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
                        <div id="profit_n_lose"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Net vs. Gross Working Capital</h6>
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
                        <div id="net_v_gross_working_capital"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Add shortcut menu modal-->
    <div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">@lang('menu.add_shortcut_menus')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
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
        var currentDate = new Date();
        currentDate.setMonth(currentDate.getMonth() - 1);
        var monthNames = [];
        for (var i = 0; i < 12; i++) {
            var month = currentDate.getMonth();
            var year = currentDate.getFullYear();
            var monthName = new Date(year, month - i, 1).toLocaleString('default', {
                month: 'short'
            });
            monthNames.unshift(monthName);
        }
        Highcharts.chart('income_n_expens_last_12_month', {
            chart: {
                height: 400,
                type: 'column'
            },
            title: {
                text: '',
            },
            tooltip: {
                headerFormat: '<span style="font-weight:bold; font-size:12px;">{point.key}</span><br/>',
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            xAxis: {
                categories: monthNames
            },
            series: [{
                name: "Income",
                data: [15, 20, 18, 30, 27, 32, 20, 34, 38, 30, 37, 29],
            }, {
                name: "Expense",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 27, 22, 27, 14],
            }]
        });


        Highcharts.chart('profit_n_lose', {
            chart: {
                height: 400,
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
                title: {
                    text: ''
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
                name: "Profit",
                color: "#81b01a",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 30, 22, 30, 14]
            }, {
                name: "Lose",
                color: "#c23219",
                data: [0, -6, -4, -10, -7, -10, -2, -14, -10, -7, -13, -4]
            }],
        });


        Highcharts.chart('net_v_gross_working_capital', {
            chart: {
                type: 'line'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            var value = this.y;
                            var suffix = '';
                            if (Math.abs(value) >= 1000000000000) {
                                value = value / 1000000000000;
                                suffix = 'Q';
                            } else if (Math.abs(value) >= 1000000000) {
                                value = value / 1000000000;
                                suffix = 'B';
                            } else if (Math.abs(value) >= 1000000) {
                                value = value / 1000000;
                                suffix = 'M';
                            } else if (Math.abs(value) >= 1000) {
                                value = value / 1000;
                                suffix = 'K';
                            }
                            return value.toFixed(1) + suffix;
                        }
                    },
                }
            },
            series: [{
                name: 'Gross Working Capital',
                data: [160, 182, 231, 279, 322, 364, 3112398, 922384, 355, 292, 220, 178]
            }, {
                name: 'Net Working Capital',
                data: [-29, -36, -6, 48, 102, 145, 1001476, 176, 120, 65, 20, -9]
            }]
        });
    </script>
@endpush
