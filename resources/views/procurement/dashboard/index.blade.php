@extends('layout.master')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('title', 'Procurement Dashboard - ')

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
                                            <i class="fa-light fa-cart-shopping"></i>
                                        </div>
                                        <h1 class="title">
                                            0.00
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Cost of Purchase Order</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
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
                                        <h3 class="sub-title">Cost Reduction</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-info">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-piggy-bank"></i>
                                        </div>
                                        <h1 class="title">
                                            0.00
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Cost Savings</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-danger">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-triangle-exclamation"></i>
                                        </div>
                                        <h1 class="title">
                                            0.00
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Cost Avoidance</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-primary">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-chart-line"></i>
                                        </div>
                                        <h1 class="title">
                                            0.00
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Procurement ROI</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-success">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-file-invoice"></i>
                                        </div>
                                        <h1 class="title">
                                            0.00
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Invoice Count</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-15">
                    <div class="row g-1">
                        <div class="col-md-4">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Top Items by Purchase</h6>
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
                                <div class="card-body p-0">
                                    <div id="top_item_by_purchase"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Average Purchase Cost per SKU</h6>
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
                                <div class="card-body p-0">
                                    <div id="average_purchase_cost_per_sku"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Credit Perid vs Credit Terms</h6>
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
                                <div class="card-body p-0">
                                    <div id="credit_perid_vs_credit_terms"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>On Time Delivery vs Vendor Lead Time</h6>
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
                                <div class="card-body p-0">
                                    <div id="on_time_delivery_vs_vendor_time"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Top Supplier by Cost Reduction</h6>
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
                                                    <th>Supplier</th>
                                                    <th>Address</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Supplier 01</td>
                                                    <td>New York, USA</td>
                                                    <td>$20.434</td>
                                                </tr>
                                                <tr>
                                                    <td>Supplier 02</td>
                                                    <td>New York, USA</td>
                                                    <td>$20.434</td>
                                                </tr>
                                                <tr>
                                                    <td>Supplier 03</td>
                                                    <td>New York, USA</td>
                                                    <td>$20.434</td>
                                                </tr>
                                                <tr>
                                                    <td>Supplier 04</td>
                                                    <td>New York, USA</td>
                                                    <td>$20.434</td>
                                                </tr>
                                                <tr>
                                                    <td>Supplier 05</td>
                                                    <td>New York, USA</td>
                                                    <td>$20.434</td>
                                                </tr>
                                                <tr>
                                                    <td>Supplier 06</td>
                                                    <td>New York, USA</td>
                                                    <td>$20.434</td>
                                                </tr>
                                                <tr>
                                                    <td>Supplier 07</td>
                                                    <td>New York, USA</td>
                                                    <td>$20.434</td>
                                                </tr>
                                                <tr>
                                                    <td>Supplier 08</td>
                                                    <td>New York, USA</td>
                                                    <td>$20.434</td>
                                                </tr>
                                                <tr>
                                                    <td>Supplier 09</td>
                                                    <td>New York, USA</td>
                                                    <td>$20.434</td>
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
                                    <h6>Top Savings Opportunities</h6>
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
                                                    <th>Product Name</th>
                                                    <th>Min Unit Cost</th>
                                                    <th>Last Unit Cost</th>
                                                    <th>Max Unit Cost</th>
                                                    <th>Quantity</th>
                                                    <th>Potential Savings</th>
                                                    <th>Current Savings</th>
                                                    <th>Spend</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Product 1</td>
                                                    <td>$2.20</td>
                                                    <td>$256.36</td>
                                                    <td>$552.23</td>
                                                    <td>507</td>
                                                    <td>
                                                        <div class="progress rounded-0">
                                                            <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>$2,369</td>
                                                    <td>$156.563</td>
                                                </tr>
                                                <tr>
                                                    <td>Product 2</td>
                                                    <td>$2.20</td>
                                                    <td>$256.36</td>
                                                    <td>$552.23</td>
                                                    <td>507</td>
                                                    <td>
                                                        <div class="progress rounded-0">
                                                            <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>$2,369</td>
                                                    <td>$156.563</td>
                                                </tr>
                                                <tr>
                                                    <td>Product 3</td>
                                                    <td>$2.20</td>
                                                    <td>$256.36</td>
                                                    <td>$552.23</td>
                                                    <td>507</td>
                                                    <td>
                                                        <div class="progress rounded-0">
                                                            <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>$2,369</td>
                                                    <td>$156.563</td>
                                                </tr>
                                                <tr>
                                                    <td>Product 4</td>
                                                    <td>$2.20</td>
                                                    <td>$256.36</td>
                                                    <td>$552.23</td>
                                                    <td>507</td>
                                                    <td>
                                                        <div class="progress rounded-0">
                                                            <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>$2,369</td>
                                                    <td>$156.563</td>
                                                </tr>
                                                <tr>
                                                    <td>Product 5</td>
                                                    <td>$2.20</td>
                                                    <td>$256.36</td>
                                                    <td>$552.23</td>
                                                    <td>507</td>
                                                    <td>
                                                        <div class="progress rounded-0">
                                                            <div class="progress-bar" role="progressbar" aria-label="Basic example" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>$2,369</td>
                                                    <td>$156.563</td>
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
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script>
        Highcharts.chart('top_item_by_purchase', {
            chart: {
                type: 'bar',
                height: 320
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5'],
                title: {
                    text: null
                },
                gridLineWidth: 1,
                lineWidth: 0
            },
            yAxis: {
                min: 0,
                title: {
                    text: null
                },
                gridLineWidth: 0
            },
            tooltip: {
                valueSuffix: ' millions'
            },
            plotOptions: {
                bar: {
                    borderRadius: 0,
                    dataLabels: {
                        enabled: true
                    },
                    groupPadding: 0.1
                }
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Sales',
                data: [3202, 839, 727, 721, 631]
            }]
        });


        Highcharts.chart('average_purchase_cost_per_sku', {
            chart: {
                type: 'column',
                height: 320
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ['SKU 1', 'SKU 2', 'SKU 3', 'SKU 3'],
                crosshair: true,
            },
            yAxis: {
                min: 0,
                title: {
                    text: null
                }
            },
            tooltip: {
                valueSuffix: ' $'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                {
                    name: 'Distribution',
                    color: "#7fe36d",
                    data: [406292, 260000, 107000, 68300]
                }
            ]
        });


        Highcharts.chart('credit_perid_vs_credit_terms', {
            chart: {
                type: 'bubble',
                plotBorderWidth: 1,
                zoomType: 'xy',
                height: 320
            },

            title: {
                text: null
            },

            xAxis: {
                gridLineWidth: 1,
                accessibility: {
                    rangeDescription: 'Range: 0 to 100.'
                }
            },

            yAxis: {
                startOnTick: false,
                endOnTick: false,
                accessibility: {
                    rangeDescription: 'Range: 0 to 100.'
                },
                title: {
                    text: null
                },
            },

            series: [{
                name: 'Credit Terms',
                data: [
                    [9, 81, 63],
                    [98, 5, 89],
                    [51, 50, 73],
                    [41, 22, 14],
                    [58, 24, 20],
                    [78, 37, 34],
                    [55, 56, 53],
                    [18, 45, 70],
                    [42, 44, 28],
                    [3, 52, 59],
                    [31, 18, 97],
                    [79, 91, 63],
                    [93, 23, 23],
                    [44, 83, 22]
                ],
                marker: {
                    fillColor: {
                        radialGradient: { cx: 0.4, cy: 0.3, r: 0.7 },
                        stops: [
                            [0, 'rgba(255,255,255,0.5)'],
                            [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0.5).get('rgba')]
                        ]
                    }
                },
                tooltip: {
                    pointFormat: '{point.x}, {point.y}, {point.z}'
                }
            }, {
                name: 'Credit Perid',
                data: [
                    [42, 38, 20],
                    [6, 18, 1],
                    [1, 93, 55],
                    [57, 2, 90],
                    [80, 76, 22],
                    [11, 74, 96],
                    [88, 56, 10],
                    [30, 47, 49],
                    [57, 62, 98],
                    [4, 16, 16],
                    [46, 10, 11],
                    [22, 87, 89],
                    [57, 91, 82],
                    [45, 15, 98]
                ],
                marker: {
                    fillColor: {
                        radialGradient: { cx: 0.4, cy: 0.3, r: 0.7 },
                        stops: [
                            [0, 'rgba(255,255,255,0.5)'],
                            [1, Highcharts.color(Highcharts.getOptions().colors[1]).setOpacity(0.5).get('rgba')]
                        ]
                    }
                },
                tooltip: {
                    pointFormat: '{point.x}, {point.y}, {point.z}'
                }
            }]
        });


        Highcharts.chart('on_time_delivery_vs_vendor_time', {
            chart: {
                type: 'bubble',
                plotBorderWidth: 1,
                zoomType: 'xy',
                height: 340
            },

            title: {
                text: null
            },

            xAxis: {
                gridLineWidth: 1,
                accessibility: {
                    rangeDescription: 'Range: 0 to 100.'
                },
                title: {
                    text: 'Avg Vendor Lead Time'
                },
            },

            yAxis: {
                startOnTick: false,
                endOnTick: false,
                accessibility: {
                    rangeDescription: 'Range: 0 to 100.'
                },
                title: {
                    text: 'On Time Delivery'
                },
            },

            series: [{
                name: 'On Time Delivery',
                data: [
                    9, 81, 63, 98, 5, 89, 51, 50, 73, 41, 22, 14, 58, 24
                ],
                marker: {
                    fillColor: {
                        radialGradient: { cx: 0.4, cy: 0.3, r: 0.7 },
                        stops: [
                            [0, 'rgba(255,255,255,0.5)'],
                            [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0.5).get('rgba')]
                        ]
                    }
                },
                tooltip: {
                    pointFormat: '{point.y}, {point.x}'
                }
            }, {
                name: 'Avg Vendor Lead Time',
                data: [
                    42, 38, 20, 6, 18, 1, 1, 93, 55, 57, 2, 90, 80, 76
                ],
                marker: {
                    fillColor: {
                        radialGradient: { cx: 0.4, cy: 0.3, r: 0.7 },
                        stops: [
                            [0, 'rgba(255,255,255,0.5)'],
                            [1, Highcharts.color(Highcharts.getOptions().colors[1]).setOpacity(0.5).get('rgba')]
                        ]
                    }
                },
                tooltip: {
                    pointFormat: '{point.y}, {point.x}'
                }
            }]
        });
    </script>
@endpush
