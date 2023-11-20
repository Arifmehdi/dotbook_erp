@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Expense Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.expense_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <a href="#" class="btn text-white btn-sm" id="print_report"><span><i class="fa-thin fa-print fa-2x"></i><br> @lang('menu.print')</span></a>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>

            <div class="p-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-2 col-md-3">
                                            <label><strong>@lang('menu.expense_group') </strong></label>
                                            <select name="expense_group_id" class="form-control select2 form-select" id="expense_group_id" autofocus>
                                                <option data-expense_group_name="All" value="">@lang('menu.all')
                                                </option>
                                                @foreach ($expenseGroups as $expenseGroup)
                                                    <option data-expense_group_name="{{ $expenseGroup->name . '(' . $expenseGroup->parent_group_name . ')' }}" value="{{ $expenseGroup->id }}">
                                                        {{ $expenseGroup->name . '(' . $expenseGroup->parent_group_name . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-3">
                                            <label><strong>@lang('menu.ledger_or_account') </strong></label>
                                            <select name="expense_account_id" class="form-control select2 form-select" id="expense_account_id" autofocus>
                                                <option data-expense_ac_name="All" value="">@lang('menu.all')</option>
                                                @foreach ($expenseAccounts as $expenseAccount)
                                                    <option data-expense_ac_name="{{ $expenseAccount->name . '(' . $expenseAccount->group_name . ')' }}" value="{{ $expenseAccount->id }}">
                                                        {{ $expenseAccount->name . '(' . $expenseAccount->group_name . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-3">
                                            <label><strong>@lang('menu.from_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-3">
                                            <label><strong>@lang('menu.to_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-3">
                                            <button type="submit" id="filter_button" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.date')</th>
                                                <th class="text-start">@lang('menu.ledger_or_account_name')</th>
                                                <th class="text-start">@lang('menu.voucher_type')</th>
                                                <th class="text-start">@lang('menu.voucher_no')</th>
                                                <th class="text-start">@lang('menu.amount')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="4" class="text-end text-white">@lang('menu.total') :
                                                    {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                                <th class="text-start text-white"><span id="amount">0.00</span></th>
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

    <div id="details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var allExpenseAccounts = @json($expenseAccounts);
        // Set accounts in payment and payment edit form
        $('.select2').select2();

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.expenses.index') }}",
                "data": function(d) {
                    d.expense_group_id = $('#expense_group_id').val();
                    d.expense_account_id = $('#expense_account_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },

            columns: [{
                    data: 'date',
                    name: 'account_ledgers.date'
                },
                {
                    data: 'account_name',
                    name: 'accounts.name'
                },
                {
                    data: 'voucher_type',
                    name: 'journals.voucher_no'
                },
                {
                    data: 'voucher_no',
                    name: 'expanses.voucher_no'
                },
                {
                    data: 'amount',
                    name: 'payments.voucher_no',
                    className: 'fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var amount = sum_table_col($('.data_tbl'), 'amount');
                $('#amount').text(bdFormat(amount));
                $('.data_preloader').hide();
            },
        });

        table.buttons().container().appendTo('#exportButtonsContainer');

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

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            table.ajax.reload();
            $('.data_preloader').show();
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.expenses.print') }}";
            var expense_group_id = $('#expense_group_id').val();
            var expense_group_name = $('#expense_group_id').find('option:selected').data('expense_group_name');
            var expense_account_id = $('#expense_account_id').val();
            var expense_account_name = $('#expense_account_id').find('option:selected').data('expense_ac_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    expense_group_id,
                    expense_group_name,
                    expense_account_id,
                    expense_account_name,
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
                    });
                }
            });
        });

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
                $('.action_hideable').hide();
            });
        });

        $(document).on('click', '#print_modal_details_btn', function(e) {
            e.preventDefault();

            var body = $('.print_details').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 700,
            });
        });

        $('#expense_group_id').on('change', function() {

            var expense_group_id = $(this).val();

            if (expense_group_id == '') {

                $('#expense_account_id').empty();
                $('#expense_account_id').append('<option data-expense_ac_name="All" value="">All</option>');

                $.each(allExpenseAccounts, function(key, account) {

                    $('#expense_account_id').append('<option data-expense_ac_name="' + account.name + '(' +
                        account.group_name + ')' + '" value="' + account.id + '">' + account.name +
                        '(' + account.group_name + ')' + '</option>');
                });
            }

            var url = "{{ route('common.ajax.call.get.account.by.group.id', [':expense_group_id']) }}";
            var route = url.replace(':expense_group_id', expense_group_id);

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(accounts) {

                    $('#expense_account_id').empty();
                    $('#expense_account_id').append(
                        '<option data-expense_ac_name="All" value="">All</option>');

                    $.each(accounts, function(key, account) {

                        $('#expense_account_id').append('<option data-expense_ac_name="' +
                            account.name + '(' + account.group_name + ')' + '" value="' +
                            account.id + '">' + account.name + '(' + account.group_name +
                            ')' + '</option>');
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
