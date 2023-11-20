@extends('layout.master')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">
    <style>
        body {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        body::-webkit-scrollbar {
            display: none;
        }
    </style>
@endpush

@section('title', 'Dashboard - ')

@section('content')

    @if (auth()->user()->can('dash_data'))
        <div class="row mx-3 pt-lg-3 pt-2 switch_bar_cards">
            <div class="switch_bar">
                <a href="{{ route('short.menus.modal.form') }}" class="bar-link" id="addShortcutBtn">
                    <span><i class="fas fa-plus-square"></i></span>
                    <p>@lang('menu.add_shortcut')</p>
                </a>
            </div>
        </div>

        <div id="dashboard" class="p-15">
            <div class="main__content">
                <div class="">
                    <div class="row mt-3 justify-content-between">
                        <div class="col-xl-10 col-md-9">
                            <div class="dashboard-nav">
                                <div class="dashboard-nav-arrow">
                                    <button id="leftArrowB" disabled><i class="fa-regular fa-angle-left"></i></button>
                                    <button id="rightArrowB"><i class="fa-regular fa-angle-right"></i></button>
                                </div>
                                <ul class="dashboard-nav-list">
                                    <li><a href="#">Master Dashboard</a></li>
                                    <li><a href="{{ route('sales.dashboard.index') }}">Sales Dashboard</a></li>
                                    <li><a href="{{ route('suppliers.dashboard') }}">Procurement Dashboard</a></li>
                                    <li><a href="{{ route('dashboard.stock.alert') }}">Inventory Dashboard</a></li>
                                    <li><a href="{{ route('finance.dashboard.index') }}">Finance Dashboard</a></li>
                                    <li><a href="#">LC Management Dashboard</a></li>
                                    <li><a href="{{ route('hrm.hrm-dashboard') }}">HRM Dashboard</a></li>
                                    <li><a href="#">Manufacturing Dashboard</a></li>
                                    <li><a href="#">Communication Dashboard</a></li>
                                    <li><a href="#">Utilities Dashboard</a></li>
                                    <li><a href="#">CRM Dashboard</a></li>
                                    <li><a href="{{ route('assets.dashboard') }}">Assest Dashboard</a></li>
                                    <li><a href="#">Project Management Dashboard</a></li>
                                    <li><a href="#">Weight Scale Dashboard</a></li>
                                    <li><a href="#">Contacts Dashboard</a></li>
                                    <li><a href="#">Modules Dashboard</a></li>
                                    @if (Route::has('website.dashboard'))
                                        <li><a href="{{ route('website.dashboard') }}">Websites Dashboard</a></li>
                                    @endif
                                    <li><a href="{{ route('settings.dashboard.settings') }}">Setting Dashboard</a></li>
                                </ul>
                                <div class="dropdowns">
                                    <ul class="dashboard-dropdown-menu" id="dashboard_one">
                                        <li><a href="#">Dropdown Item 1</a></li>
                                        <li><a href="#">Dropdown Item</a></li>
                                        <li><a href="#">Dropdown Item</a></li>
                                        <li><a href="#">Dropdown Item</a></li>
                                    </ul>
                                    <ul class="dashboard-dropdown-menu" id="dashboard_two">
                                        <li><a href="#">Dropdown Item 2</a></li>
                                        <li><a href="#">Dropdown Item</a></li>
                                        <li><a href="#">Dropdown Item</a></li>
                                        <li><a href="#">Dropdown Item</a></li>
                                    </ul>
                                </div>
                                <div class="dropdown">
                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="fa-regular fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="masterDashboard">
                                                <label class="form-check-label" for="masterDashboard">
                                                    Master Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="salesDashboard">
                                                <label class="form-check-label" for="salesDashboard">
                                                    Sales Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="procurementDashborad">
                                                <label class="form-check-label" for="procurementDashborad">
                                                    Procurement Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="inventroyDashboard">
                                                <label class="form-check-label" for="inventroyDashboard">
                                                    Inventory Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="financeDashboard">
                                                <label class="form-check-label" for="financeDashboard">
                                                    Finance Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="lcManagementDashboard">
                                                <label class="form-check-label" for="lcManagementDashboard">
                                                    LC Management Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="hrmDashboard">
                                                <label class="form-check-label" for="hrmDashboard">
                                                    HRM Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="manufacturingDashboard">
                                                <label class="form-check-label" for="manufacturingDashboard">
                                                    Manufacturing Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="communicationDashboard">
                                                <label class="form-check-label" for="communicationDashboard">
                                                    Communication Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="utilitiesDashboard">
                                                <label class="form-check-label" for="utilitiesDashboard">
                                                    Utilities Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="crmDashboard">
                                                <label class="form-check-label" for="crmDashboard">
                                                    CRM Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="assetDashboard">
                                                <label class="form-check-label" for="assetDashboard">
                                                    Assest Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="projectManagementDashboard">
                                                <label class="form-check-label" for="projectManagementDashboard">
                                                    Project Management Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="weightScaleDashboard">
                                                <label class="form-check-label" for="weightScaleDashboard">
                                                    Weight Scale Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="contactsDashboard">
                                                <label class="form-check-label" for="contactsDashboard">
                                                    Contacts Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="modulesDashboard">
                                                <label class="form-check-label" for="modulesDashboard">
                                                    Modules Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="websitesDashboard">
                                                <label class="form-check-label" for="websitesDashboard">
                                                    Websites Dashboard
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input ms-0" type="checkbox" value="" id="settingDashboard">
                                                <label class="form-check-label" for="settingDashboard">
                                                    Setting Dashboard
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-3">
                            <div class="btn-group-wrap dashboard-filtration d-flex gap-2 justify-content-end align-items-center">
                                <input type="hidden" id="date_range" value="{{ $thisMonth }}">
                                <div class="select-dropdown">
                                    <select name="date" id="date">
                                        <option value="{{ $toDay }}">@lang('menu.current_day')</option>
                                        <option value="{{ $thisWeek }}">@lang('menu.this_week')</option>
                                        <option value="{{ $thisMonth }}">@lang('menu.this_month')</option>
                                        <option value="{{ $thisYear }}">@lang('menu.this_year')</option>
                                        <option value="all_time">@lang('menu.all_time')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cards --}}
                    <div class="card-counter-wrap my-2">
                        <div class="row g-2">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-primary">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-receipt"></i>
                                        </div>
                                        <h1 class="title">
                                            <i class="fas fa-sync fa-spin card_preloader"></i>
                                            <span class="card_amount" id="total_purchase"></span>
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">@lang('menu.total_purchase')</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-success">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-money-check"></i>
                                        </div>
                                        <h1 class="title">
                                            <i class="fas fa-sync fa-spin card_preloader"></i>
                                            <span class="card_amount" id="total_sale"></span>
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">@lang('menu.total_sale')</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-info">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-clipboard"></i>
                                        </div>
                                        <h1 class="title">
                                            <i class="fas fa-sync fa-spin card_preloader"></i>
                                            <span class="card_amount" id="total_purchase_due"></span>
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">@lang('menu.supplier_balance')</h3>
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
                                            <i class="fas fa-sync fa-spin card_preloader"></i>
                                            <span class="card_amount" id="total_sale_due"></span>
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">@lang('menu.customer_balance')</h3>
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
                                            <i class="fas fa-sync fa-spin card_preloader"></i>
                                            <span class="card_amount" id="total_expense"></span>
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">@lang('menu.expense')</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-danger">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-user"></i>
                                        </div>
                                        <h1 class="title">
                                            <i class="fas fa-sync fa-spin card_preloader"></i>
                                            <span class="card_amount" id="total_user"></span>
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">@lang('menu.total_user')</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-primary">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-list"></i>
                                        </div>
                                        <h1 class="title">
                                            <i class="fas fa-sync fa-spin card_preloader"></i>
                                            <span id="total_product"></span>
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">@lang('menu.total_products')</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-success">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-balance-scale"></i>
                                        </div>
                                        <h1 class="title">
                                            <i class="fas fa-sync fa-spin card_preloader"></i>
                                            <span class="card_amount" id="total_adjustment"></span>
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">@lang('menu.total_adjustment')</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-1">
                <div class="col-12">
                    <div class="card mb-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>
                                @lang('menu.stock_alert_of')
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
                            <div class="table-responsive p-0">
                                <table id="stock_alert_table" class="display data__table data_tble stock_table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.serial')</th>
                                            <th>@lang('menu.item')</th>
                                            <th>@lang('menu.item_code') (SKU)</th>
                                            <th>@lang('menu.current_stock')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card m-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>@lang('menu.sales_order')</h6>
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
                            <div class="table-responsive p-0">
                                <table id="sales_order_table" class="display data__table data_tble order_table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.invoice_id')</th>
                                            <th>@lang('menu.customer')</th>
                                            <th>@lang('menu.created_by')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card m-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>@lang('menu.sales_payment_due')</h6>
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
                            <div class="table-responsive p-0">

                                <table id="sales_payment_due_table" class="display data__table data_tble due_table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.customer')</th>
                                            <th>@lang('menu.invoice_id')</th>
                                            <th>@lang('menu.due_amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card m-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>@lang('menu.purchase_payment_due')</h6>
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
                            <div class="table-responsive p-0">

                                <table id="purchase_payment_due_table" class="display data__table data_tble purchase_due_table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.supplier')</th>
                                            <th>@lang('short.p_invoice_id')</th>
                                            <th>@lang('menu.due_amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
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
    @else
        <div id="dashboard" class="pb-5">
            <div class="row">
                <div class="main__content">
                </div>
            </div>
            <br><br><br>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="text-primary display-5">@lang('menu.welcome'),
                        <strong>{{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}!</strong>
                    </h1>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')

    @if (auth()->user()->can('dash_data'))
        <script>
            $(document).on('change', '#date', function() {
                var date_range = $(this).data('value');
                $('#date_range').val(date_range);
                getCardAmount();
                sale_order_table.ajax.reload();
                sale_due_table.ajax.reload();
                purchase_due_table.ajax.reload();
            });

            var table = $('.stock_table').DataTable({
                dom: "Bfrtip",
                buttons: ["excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                searchable: true,
                "ajax": {
                    "url": "{{ route('dashboard.stock.alert') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                ],
            });

            var sale_order_table = $('.order_table').DataTable({
                dom: "Bfrtip",
                buttons: ["excel", "pdf", "print"],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('dashboard.sale.order') }}",
                    "data": function(d) {
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'invoice_id',
                        name: 'invoice_id'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                ],
            });

            var sale_due_table = $('.due_table').DataTable({
                dom: "Bfrtip",
                buttons: ["excel", "pdf", "print"],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('dashboard.sale.due') }}",
                    "data": function(d) {
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'invoice_id',
                        name: 'invoice_id'
                    },
                    {
                        data: 'due',
                        name: 'due'
                    },
                ],
            });

            var purchase_due_table = $('.purchase_due_table').DataTable({
                dom: "Bfrtip",
                buttons: ["excel", "pdf", "print"],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('dashboard.purchase.due') }}",
                    "data": function(d) {
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'sup_name',
                        name: 'sup_name'
                    },
                    {
                        data: 'invoice_id',
                        name: 'invoice_id'
                    },
                    {
                        data: 'due',
                        name: 'due'
                    },
                ],
            });

            // var __currency = "{{ json_decode($generalSettings->business, true)['currency'] }}";

            function getCardAmount() {
                var date_range = $('#date_range').val();
                $('.card_preloader').show();
                $('.card_amount').html('');
                $.ajax({
                    url: "{{ route('dashboard.card.data') }}",
                    type: 'get',
                    data: {
                        date_range
                    },
                    success: function(data) {
                        $('.card_preloader').hide();
                        $('#total_purchase').html(data.totalPurchase);
                        $('#total_sale').html(data.total_sale);
                        $('#total_purchase_due').html(data.totalPurchaseDue);
                        $('#total_sale_due').html(data.totalSaleDue);
                        $('#total_expense').html(data.totalExpense);
                        $('#total_user').html(data.users);
                        $('#total_product').html(data.products);
                        $('#total_adjustment').html(data.total_adjustment);
                    }
                });
            }
            getCardAmount();

            $(document).on('click', '#addShortcutBtn', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.get(url, function(data) {
                    $('#modal-body_shortcuts').html(data);
                    $('#shortcutMenuModal').modal('show');
                });
            });

            $(document).on('change', '#check_menu', function() {
                $('#add_shortcut_menu').submit();
            });

            $(document).on('submit', '#add_shortcut_menu', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        allShortcutMenus();
                        //toastr.success(data);
                    }
                });
            });

            // Get all shortcut menus by ajax
            function allShortcutMenus() {
                $.ajax({
                    url: "{{ route('short.menus.show') }}",
                    type: 'get',
                    success: function(data) {
                        $('.switch_bar_cards').html(data);
                    }
                });
            }
            allShortcutMenus();

            // Scrollable dashboard
            $(document).ready(function() {
                if ($('.dashboard-nav-list').length) {
                    var scrollerB = document.querySelector('.dashboard-nav-list');
                    var leftArrowB = document.getElementById('leftArrowB');
                    var directionB = 0;
                    var activeB = false;
                    var maxB = 10;
                    var VxB = 0;
                    var xB = 0.0;
                    var prevTimeB = 0;
                    var fB = 0.2;
                    var prevScrollB = 0;

                    function physicsB(time) {
                        var diffTimeB = time - prevTimeB;
                        if (!activeB) {
                            diffTimeB = 80;
                            activeB = true;
                        }
                        prevTimeB = time;

                        VxB = (directionB * maxB * fB + VxB * (1 - fB)) * (diffTimeB / 20);

                        xB += VxB;
                        var thisScrollB = scrollerB.scrollLeft;
                        var nextScrollB = Math.floor(thisScrollB + VxB);

                        if (Math.abs(VxB) > 0.5 && nextScrollB !== prevScrollB) {
                            scrollerB.scrollLeft = nextScrollB;
                            requestAnimationFrame(physicsB);
                        } else {
                            VxB = 0;
                            activeB = false;
                        }
                        prevScrollB = nextScrollB;
                    }
                    leftArrowB.addEventListener('mousedown', function() {
                        directionB = -1;
                        if (!activeB) {
                            requestAnimationFrame(physicsB);
                        }
                    });
                    leftArrowB.addEventListener('mouseup', function() {
                        directionB = 0;
                    });
                    rightArrowB.addEventListener('mousedown', function() {
                        directionB = 1;
                        if (!activeB) {
                            requestAnimationFrame(physicsB);
                        }
                    });
                    rightArrowB.addEventListener('mouseup', function(event) {
                        directionB = 0;
                    });
                    $(scrollerB).on('scroll', function() {
                        if ($(this).scrollLeft() < 1) {
                            $(leftArrowB).prop('disabled', true);
                        } else {
                            $(leftArrowB).prop('disabled', false);
                        }
                        if ($(this).scrollLeft() + 30 + $(this).outerWidth() >= $(this)[0].scrollWidth) {
                            $(rightArrowB).prop('disabled', true);
                        } else {
                            $(rightArrowB).prop('disabled', false);
                        }
                    });
                }
            })
        </script>
    @endif

@endpush
