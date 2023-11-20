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

@section('title', 'Inventory Dashboard - ')

@section('content')

    <div id="dashboard" class="pb-1">
        <div class="main__content">
            <div class="dashboard-bg">
                <div class="d-flex justify-content-between align-items-center pt-2 px-1">
                    <h5 class="mb-0">Inventory Dashboard</h5>
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
                                        <i class="fa-light fa-boxes-stacked"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Total Item') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-success">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-hand-holding-box"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Stock In Hand') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-info">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-box-check"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Items Available For Sale') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-danger">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-arrow-down-to-square"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">{{ __('Items Quantity In') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-primary">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-arrow-up-from-square"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">Items Quantity Out</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card-counter alert-success">
                                <div class="top-box">
                                    <div class="icon">
                                        <i class="fa-light fa-ballot-check"></i>
                                    </div>
                                    <h1 class="title">
                                        0.00
                                    </h1>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">Items Committed For Sale</h3>
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
            <div class="col-md-5">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Stock Days of Supply</h6>
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
                    <div class="card-body px-0 pb-0">
                        <div id="stock_days_of_supply"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="row g-1 gy-lg-0 h-100 mt-0">
                    <div class="col-md-4">
                        <div class="card-counter-2 text-center">
                            <div class="card-top">
                                <h3 class="fw-bold">13.333%</h3>
                                <h5 class="mb-4">Percentage of Out of Stock</h5>
                            </div>
                            <div class="card-bottom">
                                <h2><strong>2</strong></h2>
                                <h5>
                                    <span class="d-block">Out of Stock</span>
                                </h5>
                                <hr>
                                <h2><strong>15</strong></h2>
                                <h5>
                                    <span class="d-block">In Stock</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-counter-2 text-center">
                            <div class="card-top">
                                <h3 class="fw-bold">2.17%</h3>
                                <h5 class="mb-4">Return Rate</h5>
                            </div>
                            <div class="card-bottom">
                                <h2><strong>124</strong></h2>
                                <h5>
                                    <span class="d-block">Returned Units</span>
                                </h5>
                                <hr>
                                <h2><strong>5,717</strong></h2>
                                <h5>
                                    <span class="d-block">Total Units</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-counter-2 text-center">
                            <div class="card-top">
                                <h3 class="fw-bold">11.11%</h3>
                                <h5 class="mb-4">Black Order Rate</h5>
                            </div>
                            <div class="card-bottom">
                                <h2><strong>10</strong></h2>
                                <h5>
                                    <span class="d-block">Black Orders</span>
                                </h5>
                                <hr>
                                <h2><strong>90</strong></h2>
                                <h5>
                                    <span class="d-block">Total Orders</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Inventory and Cost</h6>
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
                    <div class="card-body px-0 pb-0">
                        <div id="inventory_n_cost"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card m-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Product Details</h6>
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
                    <div class="table-responsive product-details-table pt-1">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Date</th>
                                    <th>Units In Hand</th>
                                    <th>Units In Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Product 1</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="down"><i class="fa-solid fa-arrow-down"></i></span></td>
                                    <td><span class="down">171 Units</span></td>
                                </tr>
                                <tr>
                                    <td>Product 2</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="up"><i class="fa-solid fa-arrow-up"></i></span></td>
                                    <td><span>171 Units</span></td>
                                </tr>
                                <tr>
                                    <td>Product 1</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="down"><i class="fa-solid fa-arrow-down"></i></span></td>
                                    <td><span class="down">171 Units</span></td>
                                </tr>
                                <tr>
                                    <td>Product 2</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="up"><i class="fa-solid fa-arrow-up"></i></span></td>
                                    <td><span>171 Units</span></td>
                                </tr>
                                <tr>
                                    <td>Product 1</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="down"><i class="fa-solid fa-arrow-down"></i></span></td>
                                    <td><span class="down">171 Units</span></td>
                                </tr>
                                <tr>
                                    <td>Product 2</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="up"><i class="fa-solid fa-arrow-up"></i></span></td>
                                    <td><span>171 Units</span></td>
                                </tr>
                                <tr>
                                    <td>Product 1</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="down"><i class="fa-solid fa-arrow-down"></i></span></td>
                                    <td><span class="down">171 Units</span></td>
                                </tr>
                                <tr>
                                    <td>Product 2</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="up"><i class="fa-solid fa-arrow-up"></i></span></td>
                                    <td><span>171 Units</span></td>
                                </tr>
                                <tr>
                                    <td>Product 1</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="down"><i class="fa-solid fa-arrow-down"></i></span></td>
                                    <td><span class="down">171 Units</span></td>
                                </tr>
                                <tr>
                                    <td>Product 1</td>
                                    <td>4/16/2023</td>
                                    <td>160 Units <span class="down"><i class="fa-solid fa-arrow-down"></i></span></td>
                                    <td><span class="down">171 Units</span></td>
                                </tr>
                            </tbody>
                        </table>
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
        var currentDate = new Date();
        var lastSixMonths = [];
        var monthNames = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];
        for (var i = 6; i >= 1; i--) {
            var monthIndex = currentDate.getMonth() - i;
            var year = currentDate.getFullYear();
            if (monthIndex < 0) {
                monthIndex += 12;
                year -= 1;
            }
            var monthName = monthNames[monthIndex];
            lastSixMonths.push(monthName + ' ' + year);
        }
        Highcharts.chart('stock_days_of_supply', {
            chart: {
                height: 250,
                type: 'line'
            },
            title: {
                text: "",
            },
            xAxis: {
                categories: lastSixMonths,
                crosshair: true
            },
            yAxis: {
                title: {
                    text: 'Days'
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
                },
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
                            return value.toFixed(1) + suffix + ' days';
                        }
                    },
                }
            },
            series: [{
                name: "Profit",
                color: "#81b01a",
                data: [5, 12, 8, 20, 17, 26]
            }],
        });


        Highcharts.chart('inventory_n_cost', {
            chart: {
                type: 'column',
                height: 300
            },
            title: {
                text: '',
            },
            xAxis: {
                categories: ['WareHouse 1', 'WareHouse 2', 'WareHouse 3', 'WareHouse 4', 'WareHouse 5']
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
            plotOptions: {
                column: {
                    stacking: 'normal',
                }
            },
            series: [{
                name: 'Administrative Cost',
                color: "#9dd5f2",
                data: [60000, 100000, 195000, 210000, 100000]
            }, {
                name: 'Damage',
                color: "#67b9f0",
                data: [90000, 40000, 110000, 95000, 195000]
            }, {
                name: 'Loss',
                color: "#1a5f82",
                data: [114000, 95000, 20000, 60000, 80000]
            },{
                name: 'Storage Cost',
                color: "#f2f7a1",
                data: [48000, 40000, 100000, 100000, 147000]
            }, {
                name: 'Handling Cost',
                color: "#bfc930",
                data: [152000, 190000, 171000, 228000, 300000]
            }]
        });
    </script>
@endpush
