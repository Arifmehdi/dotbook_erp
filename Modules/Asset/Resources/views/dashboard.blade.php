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
                        <div class="d-flex justify-content-between align-items-center pt-2 px-1">
                            <h5 class="mb-0">Asset Dashboardd</h5>
                            <div class="select-dropdown">
                                <select name="" id="">
                                    <option value="">@lang('menu.current_day')</option>
                                    <option value="">@lang('menu.this_week')</option>
                                    <option value="">@lang('menu.this_month')</option>
                                    <option value="">@lang('menu.this_year')</option>
                                    <option value="">@lang('menu.all_time')</option>
                                </select>
                            </div>
                        </div>

                    {{-- Cards --}}
                        <div class="px-1 py-2">
                            <div class="row g-3">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter alert-primary">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-users"></i>
                                            </div>
                                            <h1 class="title">
                                                0.00
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">@lang('menu.total_user')</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter alert-success">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-layer-group"></i>
                                            </div>
                                            <h1 class="title">
                                                {{ $asset_count }}
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">@lang('menu.total_asset')</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter alert-info">
                                        <div class="top-box">
                                            <div class="icon">
                                                <i class="fa-light fa-list-check"></i>
                                            </div>
                                            <h1 class="title">
                                                {{ $allocation }}
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Allocated Asset</h3>
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
                                                {{ $asset_count - $allocation }}
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Allocable Asset</h3>
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
                                                <?php
                                                    $total_amount = 0;
                                                    foreach($asset as $key=> $items){
                                                        $total_amount += $items['unit_price'] * $items['quantity'];
                                                    }
                                                ?>
                                                {{ $total_amount }} Taka
                                            </h1>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">@lang('menu.total_asset') @lang('menu.value')</h3>
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
                                            <h3 class="sub-title">Depreciation</h3>
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
                                                {{ $request }}
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
                                                0.000
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

                    <div class="p-15">
                        <div class="row g-1">
                            <div class="col-lg-6">
                                <div class="card mb-0">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6>Asset Location</h6>
                                        <div class="d-flex align-items-center">
                                            <a href="#" class="lh-1">Show All List</a>
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
                                    </div>
                                    <div class="card-body">
                                        <div class="container">
                                            <div class="map"></div>
                                        </div>
                                    </div>
                                    <div class="asset-details">
                                        <div class="table-responsive">
                                            <table class="table table-boderless">
                                                <thead>
                                                    <tr>
                                                        <th>Location</th>
                                                        <th>Number Of Assets</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Lagos</td>
                                                        <td>193</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Lagos</td>
                                                        <td>193</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Lagos</td>
                                                        <td>193</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-0">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6>Categorised Assets</h6>
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
                                        <div id="categorisedAssets"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card mb-0">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6>Recently Tagged Assets</h6>
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
                                    <div class="card-body py-2 px-1">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>a</th>
                                                        <th>b</th>
                                                        <th>c</th>
                                                        <th>d</th>
                                                        <th>e</th>
                                                        <th>f</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
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
