@extends('layout.master')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('title', 'Dashboard - ')

@section('content')

    <div id="dashboard">
        <div class="row">
            <div class="main__content">
                <div class="dashboard-bg">
                    <div class="row px-1 pt-2">
                        <div class="btn-group-wrap d-flex justify-content-between align-items-center">
                            <input type="hidden" id="date_range" value="">

                            <div class="button-group">
                                <label class="button-group__btn" id="date" data-value="">
                                    <input type="radio" name="group" />
                                    <span class="button-group__label">@lang('menu.current_day')</span>
                                </label>

                                <label class="button-group__btn">
                                    <input type="radio" name="group" id="date" data-value="" />
                                    <span class="button-group__label">@lang('menu.this_week')</span>
                                </label>

                                <label class="button-group__btn" id="date" data-value="">
                                    <input type="radio" checked name="group" />
                                    <span class="button-group__label">@lang('menu.this_month')</span>
                                </label>

                                <label class="button-group__btn" id="date" data-value="">
                                    <input type="radio" name="group" />
                                    <span class="button-group__label">@lang('menu.this_year')</span>
                                </label>

                                <label class="button-group__btn" id="date" data-value="all_time">
                                    <input type="radio" name="group" />
                                    <span class="button-group__label">@lang('menu.all_time')</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Cards --}}
                    <div class="mx-1 my-2">
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
                                        <h3 class="sub-title">@lang('menu.total_orders')</h3>
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
                                        <h3 class="sub-title">@lang('menu.total_due')</h3>
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
                                        <h3 class="sub-title">@lang('menu.pending_orders')</h3>
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

                <div class="p-15">
                    <div class="row g-1">
                        <div class="col-md-6">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Sales Last 30 Days</h6>
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
                                    <div id="sales_last_30_days"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Sales Current Financial Year</h6>
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
                                    <div id="sales_current_financial_year"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Due Against Sales</h6>
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
                                <div class="card-body p-2">
                                    <div class="table-responsive p-0">
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Invoice No.</th>
                                                    <th>Due Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>987</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>987</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>987</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>987</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>987</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>987</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>987</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>987</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Sales Order</h6>
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
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>Date</th>
                                                    <th>Order No.</th>
                                                    <th>Customer Name</th>
                                                    <th>Contact Number</th>
                                                    <th>Location</th>
                                                    <th>Status</th>
                                                    <th>Shipping Status</th>
                                                    <th>Quantity Remaining</th>
                                                    <th>Added By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>321</td>
                                                    <td>654</td>
                                                    <td>987</td>
                                                    <td>147</td>
                                                    <td>963</td>
                                                    <td>741</td>
                                                    <td>369</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>321</td>
                                                    <td>654</td>
                                                    <td>987</td>
                                                    <td>147</td>
                                                    <td>963</td>
                                                    <td>741</td>
                                                    <td>369</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>321</td>
                                                    <td>654</td>
                                                    <td>987</td>
                                                    <td>147</td>
                                                    <td>963</td>
                                                    <td>741</td>
                                                    <td>369</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>321</td>
                                                    <td>654</td>
                                                    <td>987</td>
                                                    <td>147</td>
                                                    <td>963</td>
                                                    <td>741</td>
                                                    <td>369</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>321</td>
                                                    <td>654</td>
                                                    <td>987</td>
                                                    <td>147</td>
                                                    <td>963</td>
                                                    <td>741</td>
                                                    <td>369</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>321</td>
                                                    <td>654</td>
                                                    <td>987</td>
                                                    <td>147</td>
                                                    <td>963</td>
                                                    <td>741</td>
                                                    <td>369</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>321</td>
                                                    <td>654</td>
                                                    <td>987</td>
                                                    <td>147</td>
                                                    <td>963</td>
                                                    <td>741</td>
                                                    <td>369</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>321</td>
                                                    <td>654</td>
                                                    <td>987</td>
                                                    <td>147</td>
                                                    <td>963</td>
                                                    <td>741</td>
                                                    <td>369</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Pending Delivery Order</h6>
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
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>Date</th>
                                                    <th>Invoice No.</th>
                                                    <th>Customer Name</th>
                                                    <th>Contact Number</th>
                                                    <th>Location</th>
                                                    <th>Shipping Status</th>
                                                    <th>Payment Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Sales Return</h6>
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
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Parent Sale</th>
                                                    <th>Voucher No.</th>
                                                    <th>Return Quantity</th>
                                                    <th>Created By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>963</td>
                                                    <td>147</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>963</td>
                                                    <td>147</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>963</td>
                                                    <td>147</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>963</td>
                                                    <td>147</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>963</td>
                                                    <td>147</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>963</td>
                                                    <td>147</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>963</td>
                                                    <td>147</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                    <td>963</td>
                                                    <td>147</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Top Sold Items</h6>
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
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Sold Quantity</th>
                                                    <th>Average Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                                <tr>
                                                    <td>123</td>
                                                    <td>456</td>
                                                    <td>789</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
        Highcharts.chart('sales_last_30_days', {
            chart: {
                height: 400,
            },
            title: {
                text: '',
            },
            tooltip: {
                headerFormat: '<span style="font-weight:bold; font-size:12px;">{point.key}</span><br/>',
            },
            yAxis: {
                title: {
                    text: 'Total Sales (BDT)'
                }
            },
            xAxis: {
                categories: generateLast30Days()
            },
            series: [{
                name: "Sales",
                data: [5, 12, 8, 0, 0, 0, 0, 0, 30, 22, 0, 14, 0, 25, 0, 17, 0, 0, 26, 0, 0, 21, 0, 13, 29, 20, 28, 16, 23, 24]
            }],
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
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }


        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        Highcharts.chart('sales_current_financial_year', {
            chart: {
                height: 400,
                type: 'column'
            },
            title: {
                text: "",
            },
            xAxis: {
                categories: [
                    'Jan - ' + currentYear,
                    'Feb - ' + currentYear,
                    'Mar - ' + currentYear,
                    'Apr - ' + currentYear,
                    'May - ' + currentYear,
                    'Jun - ' + currentYear,
                    'Jul - ' + currentYear,
                    'Aug - ' + currentYear,
                    'Sep - ' + currentYear,
                    'Oct - ' + currentYear,
                    'Nov - ' + currentYear,
                    'Dec - ' + currentYear
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total Sales (BDT)'
                }
            },
            tooltip: {
                headerFormat: '<span style="display:inline-block;font-size:12px;margin-bottom:5px"><strong>{point.key}</strong></span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f} Sales</b></td></tr>',
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
                name: "Sales",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 30, 22, 30, 14]
            }],
        });
    </script>
@endpush
