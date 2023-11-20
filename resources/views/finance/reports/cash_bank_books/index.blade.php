@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 100%;
            padding: 0px;
        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        th,
        td {
            text-align: left;
            vertical-align: baseline;
        }

        table {
            border: none !important;
        }

        .net_total_balance_footer tr {
            border-top: 1px solid;
            border-bottom: 1px solid;
            line-height: 16px;
        }

        td.trial_balance_area {
            line-height: 17px !important;
        }

        .header_text {
            letter-spacing: 3px;
            border-bottom: 1px solid;
            background-color: #fff !important;
            color: #000 !important
        }

        tr.account_list td {
            border-bottom: 1px solid lightgray;
        }

        tr.account_group_list td {
            border-bottom: 1px solid lightgray;
        }

        .trial_balance_area tbody tr td {
            line-height: 16px;
        }

        .footer_total {
            font-size: 13px !important;
        }
    </style>
@endpush
@section('title', 'Cash/Bank Books - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.cash_bank_books')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="pdf btn text-white btn-sm"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="form_element rounded m-0 h-100">
                        <div class="element-body">
                            <form id="filter_cash_bank_books" action="" method="get">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.from_date')</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.to_date')</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.type_of_grouping')</strong></label>
                                        <select name="showing_type" class="form-control form-select" id="showing_type">
                                            <option value="group_wise">@lang('menu.group_wise')</option>
                                            <option value="ledger_wise">@lang('menu.ledger_wise')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="trial_balance_area">
                                <div id="data-list" class="table-responsive">
                                    <table class="w-100">
                                        <thead>
                                            <tr>
                                                <th class="header_text text-end pe-1">@lang('menu.particulars')</th>
                                                <th class="header_text text-end pe-1">@lang('menu.opening_balance')</th>
                                                <th class="header_text text-end pe-1">@lang('menu.debit')</th>
                                                <th class="header_text text-end pe-1">@lang('menu.credit')</th>
                                                <th class="header_text text-end pe-1">@lang('menu.closing_balance')</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr class="account_group_list">
                                                <td class="text-start fw-bold">Current Asset :</td>
                                                <td class="text-end fw-bold debit_amount">0.00</td>
                                                <td class="text-end fw-bold debit_amount">0.00</td>
                                                <td class="text-end fw-bold credit_amount">0.00</td>
                                                <td class="text-end fw-bold credit_amount">0.00</td>
                                            </tr>
                                        </tbody>

                                        <tfoot class="net_total_balance_footer">
                                            <tr style="font-size:20px!important;">
                                                <td class="text-end fw-bold net_debit_total">@lang('menu.grand_total') :</td>
                                                <td class="text-end fw-bold net_credit_total">0.00</td>
                                                <td class="text-end">0.00</td>
                                                <td class="text-end">0.00</td>
                                                <td class="text-end">0.00</td>
                                            </tr>
                                        </tfoot>
                                    </table>
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
    <script src="{{ asset('plugins/print_this/printThis.js') }}"></script>
    <script>
        // Set accounts in payment and payment edit form
        function getCashBankBooks() {

            $('.data_preloader').show();
            var showing_type = $('#showing_type').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: "{{ route('reports.cash.bank.books.view') }}",
                data: {
                    showing_type,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $('#data-list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getCashBankBooks();

        $('#filter_cash_bank_books').on('submit', function(e) {
            e.preventDefault();

            getCashBankBooks();
        });

        //Print account ledger
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.cash.bank.books.print') }}";

            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var showing_type = $('#showing_type').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    from_date,
                    to_date,
                    showing_type
                },
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                    });
                }
            });
        });

        $(document).on('change', '#showing_type', function(e) {
            var val = $(this).val();

            if (val == 'group_wise') {
                $('.format_of_report_select_area').show();
            } else {
                $('.format_of_report_select_area').hide();
            }
        });
    </script>

    <script>
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
