@extends('layout.master')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
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
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.profit') / @lang('menu.loss_report')</h6>
                </div>
                <x-all-buttons>
                    <button class="btn text-white btn-sm px-2" id="print_report"><span><i class="fa-thin fa-print fa-2x"></i><br> @lang('menu.print')</span></button>
                    <x-help-button />
                </x-all-buttons>
            </div>

            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <form id="sale_purchase_profit_filter" action="{{ route('reports.profit.filter.sale.purchase.profit') }}" method="get">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-3 col-md-4">
                                            <label><strong>@lang('menu.from_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="from_date" id="datepicker" class="form-control from_date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-4">
                                            <label><strong>@lang('menu.to_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="to_date" id="datepicker2" class="form-control to_date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <button class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')
                                            </button>
                                        </div>
                                        {{-- <div class="col-md-1">
                                            <a href="#" id="print_report" class="print btn text-white btn-sm px-1"><i
                                                class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</a>
                                        </div> --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-1">
                    <div class="col-12">
                        <div class="sale_purchase_and_profit_area">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div id="data_list">
                                <div class="sale_and_purchase_amount_area">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-lg-6">
                                            <div class="card">
                                                <div class="card-body mt-1">
                                                    <table class="table modal-table table-sm">
                                                        <tbody>
                                                            <tr>
                                                                <th class="text-startx"> @lang('menu.total_stock_adjustment') : </th>
                                                                <td class="text-start"> 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx">@lang('menu.total_expense') : </th>
                                                                <td class="text-start"> 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx">@lang('menu.total_transfer_shipping_charge') : </th>
                                                                <td class="text-start"> 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx">@lang('menu.total_sell_discount') : </th>
                                                                <td class="text-start"> 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx"> @lang('menu.total_customer_reward') : </th>
                                                                <td class="text-start"> 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx">@lang('menu.total_sell_return') : </th>
                                                                <td class="text-start"> 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx">@lang('menu.total_payroll') :</th>
                                                                <td class="text-start"> 0.00</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx">@lang('menu.total_production_cost') :</th>
                                                                <td class="text-start">0.00</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-lg-6">
                                            <div class="card">
                                                <div class="card-body ">
                                                    <table class="table modal-table table-sm">
                                                        <tbody>
                                                            <tr>
                                                                <th class="text-startx">
                                                                    @lang('menu.total_sale') : <br>
                                                                    <small>((Exc. tax, Discount))</small>
                                                                </th>
                                                                <td class="text-start"> 0.0</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx">@lang('menu.total_sell_shipping_charge') : </th>
                                                                <td class="text-start"> 0.0</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx">@lang('menu.total_stock_recovered') : </th>
                                                                <td class="text-start"> 0.0</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-startx">@lang('menu.total_sell_round_off') : </th>
                                                                <td class="text-start"> 0.0</td>
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
        function getSalePurchaseAndProfitData() {
            $('.data_preloader').show();
            $.ajax({
                url: "{{ route('reports.profit.sale.purchase.profit') }}",
                type: 'get',
                success: function(data) {
                    $('#data_list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getSalePurchaseAndProfitData();

        //Send sale purchase profit filter request
        $('#sale_purchase_profit_filter').on('submit', function(e) {
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
            var url = "{{ route('reports.profit.loss.print') }}";
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
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
            element: document.getElementById('datepicker'),
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
            element: document.getElementById('datepicker2'),
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

        $(document).on('click', '.cancelBtn ', function() {
            $('.daterange').val('');
        });
    </script>
@endpush
