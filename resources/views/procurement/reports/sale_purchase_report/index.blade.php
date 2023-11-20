@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .sale_and_purchase_amount_area table tbody tr th,
        td {
            color: #32325d;
        }

        .sale_purchase_and_profit_area {
            position: relative;
        }

        .data_preloader {
            top: 2.3%
        }

        .sale_and_purchase_amount_area table tbody tr th {
            text-align: left;
        }

        .sale_and_purchase_amount_area table tbody tr td {
            text-align: left;
        }
    </style>
@endpush
@section('title', 'Purchases & Sales Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __("Purchase Sale Compare") }}</h6>
                </div>
                <x-all-buttons>
                    <a href="#" class="btn text-white btn-sm px-2"><span><i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')</span></a>
                    <a href="#" class="btn text-white btn-sm px-2"><span><i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')</span></a>
                    <a href="#" class="btn text-white btn-sm px-1" id="print_report"><span><i class="fa-thin fa-print fa-2x"></i><br> @lang('menu.print')</span></a>
                    <x-help-button />
                </x-all-buttons>
            </div>

            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <form id="sale_purchase_filter" action="{{ route('reports.profit.sales.filter.purchases.amounts') }}" method="get">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-md-3">
                                            <label><strong>@lang('menu.from_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label><strong>@lang('menu.to_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="sale_purchase_and_profit_area">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div id="data_list">
                                <div class="sale_and_purchase_amount_area">
                                    <div class="row g-1">
                                        <div class="col-md-12 col-sm-12 col-lg-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="heading">
                                                        <h6 class="text-primary"><b>@lang('menu.purchase')</b></h6>
                                                    </div>

                                                    <table class="table modal-table table-sm">
                                                        <tbody>
                                                            <tr>
                                                                <th>@lang('menu.total_purchase') :</th>
                                                                <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th>@lang('menu.purchase_including_tax') : </th>
                                                                <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th>@lang('menu.purchase_due') : </th>
                                                                <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-lg-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="heading">
                                                        <h6 class="text-primary"><b>@lang('menu.sales')</b></h6>
                                                    </div>

                                                    <table class="table modal-table table-sm">
                                                        <tbody>
                                                            <tr>
                                                                <th>@lang('menu.total_sale') :</th>
                                                                <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th>@lang('menu.sale_including_tax') : </th>
                                                                <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th> @lang('menu.sale_date'): </th>
                                                                <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
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
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function salePurchaseDueAmounts() {
            $('.data_preloader').show();
            $.ajax({
                url: "{{ route('reports.profit.sales.purchases.amounts') }}",
                type: 'get',
                success: function(data) {
                    $('#data_list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        salePurchaseDueAmounts();

        //Send sale purchase amount filter request
        $('#sale_purchase_filter').on('submit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'get',
                data: request,
                success: function(data) {
                    $('#data_list').html(data);
                    $('.data_preloader').hide();
                }
            });
        });

        //Print Profit/Loss
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.sales.purchases.print') }}";
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    from_date,
                    to_date
                },
                success: function(data) {
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('to_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
