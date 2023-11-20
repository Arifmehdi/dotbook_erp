<style>
    @page {
        /* size:21cm 29.7cm; */
        margin: 1cm 1cm 1cm 1cm;
        * //* margin:20px 20px 10px; */mso-title-page:yes;mso-page-orientation: portrait;mso-header: header;mso-footer: footer;}
</style>
@extends('layout.master')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.min.css') }}" />
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .sale_and_purchase_amount_area table tbody tr th,
        td {
            color: #32325d;
        }

        .report_data_area {
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
@section('title', 'Sales Representative Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.sales_representative_report')</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
                </a>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="sec-name">
                        <div class="col-md-12">
                            <form>
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label><strong>@lang('menu.user') </strong></label>
                                        <select name="user_id" class="form-control submit_able form-select" id="user_id" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>@lang('menu.date') @lang('menu.range') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                            </div>
                                            <input readonly type="text" name="date_range" id="date_range" class="form-control daterange submitable_input" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row margin_row mt-1">
                <div class="report_data_area">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="report_data">
                        <div class="sale_and_expense_sum_area">
                            <div class="card-body card-custom">

                                <div class="heading">
                                    <h6 class="text-muted">@lang('menu.total_sale_total_sales_return') :
                                        {{ json_decode($generalSettings->business, true)['currency'] }} <span id="sale_amount"></span></h6>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="tax_sum">
                                            <h6 class="text-muted">@lang('menu.expense') :
                                                {{ json_decode($generalSettings->business, true)['currency'] }} <span id="expense_amount"></span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="user_sale_and_expense_list">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab_list_area">
                                        <ul class="list-unstyled">
                                            <li>
                                                <a id="tab_btn" data-show="sales" class="tab_btn tab_active" href="#"><i class="fas fa-info-circle"></i> Seles</a>
                                            </li>

                                            <li>
                                                <a id="tab_btn" data-show="expense" class="tab_btn" href="#">
                                                    <i class="fas fa-scroll"></i> @lang('menu.expense')</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="tab_contant sales">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="display data_tbl data__table" id="sale_table">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('menu.date')</th>
                                                                <th>@lang('menu.invoice_id')</th>
                                                                <th>@lang('menu.customer')</th>
                                                                <th>@lang('menu.payment_status')</th>
                                                                <th>@lang('menu.total_amount')</th>
                                                                <th>@lang('menu.total_return')</th>
                                                                <th>@lang('menu.total_paid')</th>
                                                                <th>@lang('menu.total_remaining')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="bg-secondary">
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th class="text-white">@lang('menu.total') :</th>
                                                                <th class="text-white">
                                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                    <span id="total_amount"></span>
                                                                </th>
                                                                <th class="text-white">
                                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                    <span id="total_return"></span>
                                                                </th>
                                                                <th class="text-white">
                                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                    <span id="paid"></span>
                                                                </th>
                                                                <th class="text-white">
                                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                    <span id="due"></span>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab_contant expense d-none">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="display data_tbl data__table w-100" id="expense_table">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('menu.date')</th>
                                                                <th>@lang('menu.reference_no')</th>
                                                                <th>@lang('menu.expense_for')</th>
                                                                <th>@lang('menu.payment_status')</th>
                                                                <th>@lang('menu.total_amount')</th>
                                                                <th>@lang('menu.total_paid')</th>
                                                                <th>@lang('menu.total_due')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="bg-secondary">
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th class="text-white">@lang('menu.total') :</th>
                                                                <th class="text-white">
                                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                    <span id="ex_total_amount"></span>
                                                                </th>
                                                                <th class="text-white">
                                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                    <span id="ex_paid"></span>
                                                                </th>
                                                                <th class="text-white">
                                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                    <span id="ex_due"></span>
                                                                </th>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script>
        var sale_table = $('#sale_table').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: 'Pdf',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-primary'
                },
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [
                [3, 'desc']
            ],
            "ajax": {
                "url": "{{ route('reports.sale.representive.index') }}",
                "data": function(d) {
                    d.user_id = $('#user_id').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columnDefs: [{
                "targets": [4],
                "orderable": false,
                "searchable": false
            }],
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
                    name: 'customers.name'
                },
                {
                    data: 'payment_status',
                    name: 'payment_status'
                },
                {
                    data: 'total_amount',
                    name: 'total_payable_amount'
                },
                {
                    data: 'total_return',
                    name: 'sale_return_amount'
                },
                {
                    data: 'paid',
                    name: 'paid'
                },
                {
                    data: 'due',
                    name: 'due'
                },
            ],
            fnDrawCallback: function() {
                var total_amount = sum_table_col($('.data_tbl'), 'total_amount');
                $('#total_amount').html(parseFloat(total_amount).toFixed(2));
                var total_return = sum_table_col($('.data_tbl'), 'total_return');
                $('#total_return').html(parseFloat(total_return).toFixed(2));
                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').html(parseFloat(paid).toFixed(2));
                var due = sum_table_col($('.data_tbl'), 'due');
                $('#due').html(parseFloat(due).toFixed(2));

                var total_sale = parseFloat(total_amount) - parseFloat(total_return);
                $('#sale_amount').html(parseFloat(total_sale).toFixed(2));
            },

        });

        var ex_table = $('#expense_table').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: 'Pdf',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-primary'
                },
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [
                [0, 'desc']
            ],
            "ajax": {
                "url": "{{ route('reports.sale.representive.expenses') }}",
                "data": function(d) {
                    d.user_id = $('#user_id').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columnDefs: [{
                "targets": [4],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'invoice_id',
                    name: 'invoice_id'
                },
                {
                    data: 'user',
                    name: 'users.name'
                },
                {
                    data: 'payment_status',
                    name: 'payment_status'
                },
                {
                    data: 'total_amount',
                    name: 'net_total_amount'
                },
                {
                    data: 'paid',
                    name: 'paid'
                },
                {
                    data: 'due',
                    name: 'due'
                },
            ],
            fnDrawCallback: function() {
                var ex_total = sum_table_col($('.data_tbl'), 'ex_total');
                $('#expense_amount').html(parseFloat(ex_total).toFixed(2));
                $('#ex_total_amount').html(parseFloat(ex_total).toFixed(2));
                var ex_paid = sum_table_col($('.data_tbl'), 'ex_paid');
                $('#ex_paid').html(parseFloat(ex_paid).toFixed(2));
                var ex_due = sum_table_col($('.data_tbl'), 'ex_due');
                $('#ex_due').html(parseFloat(ex_due).toFixed(2));
            },
        });
    </script>

    <script type="text/javascript">
        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function() {
            sale_table.ajax.reload();
            ex_table.ajax.reload();
        });

        //Submit filter form by date-range field blur
        $(document).on('blur', '.submitable_input', function() {
            setTimeout(function() {
                sale_table.ajax.reload();
                ex_table.ajax.reload();
            }, 500);
        });

        //Submit filter form by date-range apply button
        $(document).on('click', '.applyBtn', function() {
            setTimeout(function() {
                $('.submitable_input').addClass('.form-control:focus');
                $('.submitable_input').blur();
            }, 500);
        });

        function sum_table_col(table, class_name) {
            var sum = 0;
            table.find('tbody').find('tr').each(function() {
                if (parseFloat($(this).find('.' + class_name).data('value'))) {
                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });
            return sum;
        }

        $(function() {
            var start = moment().startOf('year');
            var end = moment().endOf('year');
            $('.daterange').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',
                startDate: start,
                endDate: end,
                locale: {
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year')
                        .subtract(1, 'year')
                    ],
                }
            });
            $('.daterange').val('');
        });

        $(document).on('click', '.cancelBtn ', function() {
            $('.daterange').val('');
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').addClass('d-none');
            var show_content = $(this).data('show');
            $('.' + show_content).removeClass('d-none');
            $(this).addClass('tab_active');
        });
    </script>
@endpush
