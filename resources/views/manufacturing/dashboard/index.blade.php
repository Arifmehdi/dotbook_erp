@extends('layout.master')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">
    <style>
        .plant-productivity-target,
        .plant-productivity-target-status {
            display: block;
            background: #f2f2f2;
            padding: 0 10px;
            line-height: 35px;
        }

        .plant-productivity-target-status {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            font-weight: 500;
        }

        .plant-productivity-target-status.status-up {
            background: #10cda1;
            color: #ffffff;
        }

        .plant-productivity-target-status.status-down {
            background: #dc2143;
            color: #ffffff;
        }
    </style>
@endpush

@section('title', 'Manufacturing Dashboard - ')

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
                                <div class="card-counter alert-success">
                                    <div class="top-box">
                                        <div class="icon">
                                            <i class="fa-light fa-money-check"></i>
                                        </div>
                                        <h1 class="title">
                                            0.00
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Produced QTY</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-primary">
                                    <div class="d-flex justify-content-between">
                                        <div class="chart">
                                            <div id="produced_quality"></div>
                                        </div>
                                        <div>
                                            <div class="top-box justify-content-end">
                                                <h1 class="title">
                                                    0.00
                                                </h1>
                                            </div>
                                            <div class="numbers px-1">
                                                <h3 class="sub-title">Production Quality</h3>
                                            </div>
                                        </div>
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
                                            0.00
                                        </h1>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Production Cost</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-info">
                                    <div class="d-flex justify-content-between">
                                        <div class="chart">
                                            <div id="cost_effciency"></div>
                                        </div>
                                        <div>
                                            <div class="top-box justify-content-end">
                                                <h1 class="title">
                                                    0.00
                                                </h1>
                                            </div>
                                            <div class="numbers px-1">
                                                <h3 class="sub-title">Cost Effciency</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-danger">
                                    <div class="d-flex justify-content-between">
                                        <div class="chart">
                                            <div id="avg_lead_time"></div>
                                        </div>
                                        <div>
                                            <div class="top-box justify-content-end">
                                                <h1 class="title">
                                                    0.00
                                                </h1>
                                            </div>
                                            <div class="numbers px-1">
                                                <h3 class="sub-title">Avg. Lead Time</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-counter alert-primary">
                                    <div class="d-flex justify-content-between">
                                        <div class="chart">
                                            <div id="production_fulfilment"></div>
                                        </div>
                                        <div>
                                            <div class="top-box justify-content-end">
                                                <h1 class="title">
                                                    0.00
                                                </h1>
                                            </div>
                                            <div class="numbers px-1">
                                                <h3 class="sub-title">Production Fulfilment</h3>
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
                        <div class="col-md-3">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Current Order Status</h6>
                                    <div class="dropdown">
                                        <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div id="current_order_status"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Cost by Operation/Item Group</h6>
                                    <div class="dropdown">
                                        <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div id="actual_cost_by_group"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Current Tracking Status Over Time</h6>
                                    <div class="dropdown">
                                        <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div id="current_tracking_status"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Lead Time Over Time</h6>
                                    <div class="dropdown">
                                        <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body pb-0 px-2">
                                    <div id="lead_time_over_time"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Production Costs Over Time</h6>
                                    <div class="dropdown">
                                        <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body pb-0 px-2">
                                    <div id="production_cost_over_time"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Overall Plant Productivity</h6>
                                    <div class="dropdown">
                                        <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h2>80.15%</h2>
                                        <div id="overall_plant_productivity"></div>
                                    </div>
                                    <div class="row g-1">
                                        <div class="col-8">
                                            <span class="plant-productivity-target">
                                                Target : <span class="text-danger">82.10%</span>
                                            </span>
                                        </div>
                                        <div class="col-4">
                                            <span class="plant-productivity-target-status status-up"><i
                                                    class="fa-solid fa-up-long"></i> 50%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Lost Units : Causes</h6>
                                    <div class="dropdown">
                                        <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div id="lost_units_cause"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card m-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6>Units Lost : Machine</h6>
                                    <div class="dropdown">
                                        <button class="btn py-0 text-dark" type="button" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end rounded-1">
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-0">
                                                    <label class="form-check-label d-flex align-items-center">
                                                        <input class="form-check-input mb-1" type="checkbox"
                                                            value="">
                                                        Default checkbox
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div id="lost_units_machine"></div>
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
        var produced_quality_options = {
            chart: {
                type: 'donut',
                width: 120,
                height: 150,
                toolbar: {
                    show: false
                }
            },
            series: [94, 15],
            labels: ['Good', 'Bad'],
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 90,
                    expandOnClick: false,
                    donut: {
                        size: '65%',
                        background: 'transparent',
                        labels: {
                            show: false
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0
            },
            legend: {
                show: false
            },
        };
        var chart = new ApexCharts(document.querySelector("#produced_quality"), produced_quality_options);
        chart.render();


        var cost_effciency_options = {
            chart: {
                type: 'donut',
                width: 120,
                height: 150,
                toolbar: {
                    show: false
                }
            },
            series: [94, 5],
            labels: ['Good', 'Bad'],
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 90,
                    expandOnClick: false,
                    donut: {
                        size: '65%',
                        background: 'transparent',
                        labels: {
                            show: false
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0
            },
            legend: {
                show: false
            },
        };
        var chart = new ApexCharts(document.querySelector("#cost_effciency"), cost_effciency_options);
        chart.render();


        var avg_lead_time_options = {
            chart: {
                type: 'donut',
                width: 120,
                height: 150,
                toolbar: {
                    show: false
                }
            },
            series: [84, 25],
            labels: ['Good', 'Bad'],
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 90,
                    expandOnClick: false,
                    donut: {
                        size: '65%',
                        background: 'transparent',
                        labels: {
                            show: false
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0
            },
            legend: {
                show: false
            },
        };
        var chart = new ApexCharts(document.querySelector("#avg_lead_time"), avg_lead_time_options);
        chart.render();


        var production_fulfilment_options = {
            chart: {
                type: 'donut',
                width: 120,
                height: 150,
                toolbar: {
                    show: false
                }
            },
            series: [90, 47],
            labels: ['Good', 'Bad'],
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 90,
                    expandOnClick: false,
                    donut: {
                        size: '65%',
                        background: 'transparent',
                        labels: {
                            show: false
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0
            },
            legend: {
                show: false
            },
        };
        var chart = new ApexCharts(document.querySelector("#production_fulfilment"), production_fulfilment_options);
        chart.render();


        Highcharts.chart('current_order_status', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: 350
            },
            title: {
                text: null,
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
                    innerSize: '70%',
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}',
                        distance: -15,
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Current Order',
                colorByPoint: true,
                data: [{
                    name: 'Final',
                    y: 70,
                }, {
                    name: 'Hold',
                    y: 44
                }]
            }]
        });


        Highcharts.chart('actual_cost_by_group', {
            chart: {
                type: 'bar',
                height: 350
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ['Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5', 'Product 6',
                    'Product 7', 'Product 8'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: null
                }
            },
            plotOptions: {
                series: {
                    groupPadding: 0,
                    pointPadding: 0.1,
                    borderWidth: 0,
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                },
            },
            series: [{
                name: 'Series 1',
                color: '#ff932e',
                data: [4, 4, 6, 15, 12, 8, 17, 13]
            }, {
                name: 'Series 2',
                color: '#ffec59',
                data: [5, 3, 12, 6, 11, 5, 7, 9]
            }, {
                name: 'Series 3',
                color: '#42b4ff',
                data: [5, 15, 8, 5, 8, 4, 9, 5]
            }]
        });


        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        Highcharts.chart('current_tracking_status', {
            chart: {
                type: 'column',
                height: 350
            },
            title: {
                text: null
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
                    text: null
                }
            },
            plotOptions: {
                series: {
                    groupPadding: 0,
                    pointPadding: 0.15,
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                },
            },
            series: [{
                name: 'Series 1',
                data: [4, 4, 6, 15, 12, 8, 17, 13, 15, 12, 6, 11]
            }, {
                name: 'Series 2',
                data: [5, 3, 12, 6, 11, 5, 7, 9, 15, 8, 5, 8]
            }, {
                name: 'Series 3',
                data: [5, 15, 8, 5, 8, 4, 9, 5, 4, 6, 15, 12]
            }, {
                name: 'Series 4',
                data: [5, 15, 12, 6, 11, 5, 9, 5, 3, 12, 6, 11]
            }]
        });


        Highcharts.chart('lead_time_over_time', {
            chart: {
                height: 300,
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
                    text: null
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
                series: {
                    groupPadding: 0.15,
                    dataLabels: {
                        enabled: false,
                        format: '{point.y:.0f}',
                        inside: false
                    }
                },
                column: {
                    pointPadding: 0,
                    borderWidth: 0,
                    borderRadius: 0
                }
            },
            series: [{
                name: "Series 1",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 30, 22, 30, 14]
            }, {
                name: "Series 2",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 30, 22, 30, 14]
            }, {
                type: "spline",
                color: "#f50a50",
                name: "Series 3",
                data: [18, 17, 20, 25, 24, 29, 23, 29, 32, 25, 37, 31],
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}',
                    inside: false
                }
            }],
        });


        Highcharts.chart('production_cost_over_time', {
            chart: {
                height: 300,
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
                    text: null
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
                series: {
                    groupPadding: 0.15,
                    dataLabels: {
                        enabled: false,
                        format: '{point.y:.0f}',
                        inside: false
                    }
                },
                column: {
                    pointPadding: 0,
                    borderWidth: 0,
                    borderRadius: 0
                }
            },
            series: [{
                name: "Series 1",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 30, 22, 30, 14]
            }, {
                name: "Series 2",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 30, 22, 30, 14]
            }, {
                type: "spline",
                color: "#f50a50",
                name: "Series 3",
                data: [18, 17, 20, 25, 24, 29, 23, 29, 32, 25, 37, 31],
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}',
                    inside: false
                }
            }],
        });


        var overall_plant_productivity_options = {
            chart: {
                type: 'area',
                height: 60,
                width: 150,
                sparkline: {
                    enabled: true
                }
            },
            series: [{
                data: [3, 6, 4, 7, 5, 9, 4]
            }],
            stroke: {
                curve: 'smooth',
                width: 1
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3
                }
            },
            tooltip: {
                // enabled: false
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            xaxis: {
                type: 'numeric',
                labels: {
                    show: false
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#overall_plant_productivity"), overall_plant_productivity_options);
        chart.render();


        Highcharts.chart('lost_units_cause', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: 143,
                spacingTop: 0,
                spacingBottom: 0
            },
            title: {
                text: null
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
                    innerSize: "50%",
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}',
                        distance: -15
                    },
                    showInLegend: true
                }
            },
            legend: {
                align: 'right',
                verticalAlign: 'middle',
                layout: 'vertical',
                itemMarginTop: 5,
                itemMarginBottom: 5,
                itemStyle: {
                    lineHeight: 'normal'
                }
            },
            navigation: {
                buttonOptions: {
                    enabled: false
                }
            },
            series: [{
                name: 'Lost Units',
                colorByPoint: true,
                data: [{
                    name: 'Tooling Error',
                    y: 70,
                }, {
                    name: 'Operator Damage',
                    y: 45
                }, {
                    name: 'Physical Damage',
                    y: 60
                }, {
                    name: 'Other Causes',
                    y: 55
                }]
            }]
        });


        Highcharts.chart('lost_units_machine', {
            chart: {
                height: 143,
                type: 'column',
                marginBottom: 65,
                spacingBottom: 0
            },
            title: {
                text: "",
            },
            xAxis: {
                categories: [
                    'Jan - ' + currentYear.toString().slice(-2),
                    'Feb - ' + currentYear.toString().slice(-2),
                    'Mar - ' + currentYear.toString().slice(-2),
                    'Apr - ' + currentYear.toString().slice(-2),
                    'May - ' + currentYear.toString().slice(-2),
                    'Jun - ' + currentYear.toString().slice(-2),
                    'Jul - ' + currentYear.toString().slice(-2),
                    'Aug - ' + currentYear.toString().slice(-2),
                    'Sep - ' + currentYear.toString().slice(-2),
                    'Oct - ' + currentYear.toString().slice(-2),
                    'Nov - ' + currentYear.toString().slice(-2),
                    'Dec - ' + currentYear.toString().slice(-2)
                ],
                crosshair: true,
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                },
                lineWidth: 0,
                offset: -10
            },
            yAxis: {
                min: 0,
                title: {
                    text: null
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
            legend: {
                marginBottom: 100
            },
            plotOptions: {
                series: {
                    groupPadding: 0.15,
                    dataLabels: {
                        enabled: false,
                        format: '{point.y:.0f}',
                        inside: false
                    }
                },
                column: {
                    pointPadding: 0,
                    borderWidth: 0,
                }
            },
            series: [{
                name: "Series 1",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 30, 22, 30, 14]
            }, {
                name: "Series 2",
                data: [5, 12, 8, 20, 17, 26, 10, 24, 30, 22, 30, 14]
            }, {
                name: "Series 3",
                data: [18, 17, 20, 25, 24, 29, 23, 29, 32, 25, 37, 31]
            }],
        });
    </script>
@endpush
