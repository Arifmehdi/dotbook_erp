@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    

    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .modal-table tr th {
            background-color: #fff !important;
        }
    </style>
@endpush
@section('title', $account->name . ' - Account Ledger')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name  d-flex justify-contents-between align-items-center">
                <h6 id="ledger_heading_area">@lang('menu.account_ledgers') - <span id="ledger_heading">{{ $account->name }}
                        {{ $account->phone ? '/' . $account->phone : '' }}
                        {{ $account->account_number ? '/' . $account->account_number : '' }}</span></h6>
                <x-all-buttons>
                    <x-slot name="after">
                        <a href="#" id="print_report" class="pdf btn text-white btn-sm"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></a>
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1 mb-1">
                <div class="col-md-4">
                    <div class="form_element rounded m-0">
                        <div class="element-body">
                            <table class="table modal-table table-sm m-0">
                                <tbody>
                                    <tr>
                                        <th colspan="3" class="text-center">@lang('menu.account_summery')</th>
                                    </tr>
                                    <tr>
                                        <th class="text-end"></th>
                                        <th class="text-end">@lang('menu.debit')</th>
                                        <th class="text-end">@lang('menu.credit')</th>
                                    </tr>

                                    <tr>
                                        <th class="text-end">@lang('menu.opening_balance') :</th>
                                        <th class="text-end" id="debit_opening_balance"></th>
                                        <th class="text-end" id="credit_opening_balance"></th>
                                    </tr>

                                    <tr>
                                        <th class="text-end">@lang('menu.current_total') :</th>
                                        <th class="text-end" id="total_debit"></th>
                                        <th class="text-end" id="total_credit"></th>
                                    </tr>

                                    <tr>
                                        <th class="text-end">@lang('menu.closing_balance') :</th>
                                        <th class="text-end" id="debit_closing_balance"></th>
                                        <th class="text-end" id="credit_closing_balance"></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form_element rounded m-0 h-100">
                        <div class="element-body">
                            <form id="filter_account_ledgers" method="get" class="px-2">
                                <div class="form-group row g-2 align-items-end">
                                    @if ($account?->group?->sub_sub_group_number == 6)

                                        @if (auth()->user()->is_marketing_user == 0)

                                            <div class="col-xl-3 col-md-3">
                                                <label><strong>{{ __('Sr.') }} </strong></label>
                                                <select name="user_id" class="form-control select2" id="user_id" autofocus>
                                                    <option data-user_name="" value="">@lang('menu.all')</option>
                                                    @foreach ($users as $user)
                                                        <option {{ $userId == $user->id ? 'SELECTED' : '' }} data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}" value="{{ $user->id }}">
                                                            {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="col-xl-3 col-md-3">
                                                <label><strong>{{ __('Sr.') }} </strong></label>
                                                <select name="user_id" class="form-control select2" id="user_id" autofocus>
                                                    <option data-user_name="{{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}" value="{{ auth()->user()->id }}">
                                                        {{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="col-md-3">
                                        <label><strong>{{ __('From Date') }} :</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>{{ __('To Date') }} :</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>{{ __('Note/Remarks') }} :</strong></label>
                                        <select name="note" class="form-control form-select" id="note">
                                            <option value="0">@lang('menu.no')</option>
                                            <option selected value="1">@lang('menu.yes')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>{{ __('Voucher Details') }} :</strong></label>
                                        <select name="voucher_details" class="form-control form-select" id="voucher_details">
                                            <option value="0">@lang('menu.no')</option>
                                            <option value="1">@lang('menu.yes')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>{{ __('Transaction Details') }} :</strong></label>
                                        <select name="transaction_details" class="form-control form-select" id="transaction_details">
                                            <option value="0">@lang('menu.no')</option>
                                            <option value="1">@lang('menu.yes')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>{{ __('Inventory List') }} :</strong></label>
                                        <select name="inventory_list" class="form-control form-select" id="inventory_list">
                                            <option value="0">@lang('menu.no')</option>
                                            <option value="1">@lang('menu.yes')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm text-white btn-info py-1 px-2"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>

                                    <div class="col-md-1">
                                        <a href="{{ route('accounting.accounts.edit', [$account->id]) }}" class="btn btn-sm btn-primary py-1 px-2 btn-block" id="edit">
                                            @lang('menu.edit')</a>
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
                        <div class="card-body pb-2">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-startx">{{ __('Date') }}</th>
                                            <th class="text-startx">{{ __('Particulars') }}</th>
                                            <th class="text-startx">{{ __('Voucher Type') }}</th>
                                            <th class="text-startx">{{ __('Voucher No') }}</th>
                                            <th class="text-startx">{{ __('Debit') }}</th>
                                            <th class="text-startx">{{ __('Credit') }}</th>
                                            <th class="text-startx">{{ __('Running Balance') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="4" class="text-white" style="text-align: right!important;"> {{ __('Current Total') }} : </th>
                                            <th id="table_total_debit" class="text-white"></th>
                                            <th id="table_total_credit" class="text-white"></th>
                                            <th id="table_current_balance" class="text-white"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <form id="deleted_form" action="" method="post">
                            @method('DELETE')
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Add/Edit Account modal-->
    <div class="modal fade" id="accountAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account modal End-->

    <!--Add/Edit Account Group modal-->
    <div class="modal fade" id="accountGroupAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account Group modal End-->

    <div id="details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();

        var account_ledger_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
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
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('accounting.accounts.ledger', [$account->id, 'accountId']) }}",
                "data": function(d) {
                    d.user_id = $('#user_id').val();
                    d.user_name = $('#user_id').find('option:selected').data('user_name');
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.note = $('#note').val();
                    d.transaction_details = $('#transaction_details').val();
                    d.voucher_details = $('#voucher_details').val();
                    d.inventory_list = $('#inventory_list').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'account_ledgers.date'
                },
                {
                    data: 'particulars',
                    name: 'particulars'
                },
                {
                    data: 'voucher_type',
                    name: 'voucher_no'
                },
                {
                    data: 'voucher_no',
                    name: 'voucher_no'
                },
                {
                    data: 'debit',
                    name: 'account_ledgers.debit',
                    className: 'text-end'
                },
                {
                    data: 'credit',
                    name: 'account_ledgers.credit',
                    className: 'text-end'
                },
                {
                    data: 'running_balance',
                    name: 'account_ledgers.running_balance',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        account_ledger_table.buttons().container().appendTo('#exportButtonsContainer');

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_account_ledgers', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            account_ledger_table.ajax.reload(null, false);

            getAccountClosingBalance();
        });

        //Print account ledger
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('accounting.accounts.ledger.print', [$account->id, 'accountId']) }}";

            var user_id = $('#user_id').val();
            var user_name = $('#user_id').find('option:selected').data('user_name');
            var customer_name = '';
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var note = $('#note').val();
            var transaction_details = $('#transaction_details').val();
            var voucher_details = $('#voucher_details').val();
            var inventory_list = $('#inventory_list').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id,
                    user_name,
                    customer_name,
                    from_date,
                    to_date,
                    note,
                    transaction_details,
                    voucher_details,
                    inventory_list
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

        function getAccountClosingBalance() {

            var user_id = $('#user_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            var filterObj = {
                user_id: user_id ? user_id : null,
                from_date: from_date ? from_date : null,
                to_date: to_date ? to_date : null,
            };

            var url = "{{ route('vouchers.journals.account.closing.balance', $account->id) }}";

            $.ajax({
                url: url,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    $('#debit_opening_balance').html('');
                    $('#credit_opening_balance').html('');
                    $('#debit_closing_balance').html('');
                    $('#credit_closing_balance').html('');

                    $('#table_total_debit').html(data.all_total_debit > 0 ? bdFormat(data.all_total_debit) : '');
                    $('#table_total_credit').html(data.all_total_credit ? bdFormat(data.all_total_credit) : '');
                    $('#table_current_balance').html(data.closing_balance > 0 ? data.closing_balance_string : '');

                    if (data.opening_balance_side == 'dr') {

                        $('#debit_opening_balance').html(data.opening_balance > 0 ? bdFormat(data.opening_balance) : '');
                    } else {

                        $('#credit_opening_balance').html(data.opening_balance > 0 ? bdFormat(data.opening_balance) : '');
                    }

                    $('#total_debit').html(data.curr_total_debit > 0 ? bdFormat(data.curr_total_debit) : '');
                    $('#total_credit').html(data.curr_total_credit > 0 ? bdFormat(data.curr_total_credit) : '');

                    if (data.closing_balance_side == 'dr') {

                        $('#debit_closing_balance').html(data.closing_balance > 0 ? bdFormat(data.closing_balance) : '');
                    } else {

                        $('#credit_closing_balance').html(data.closing_balance > 0 ? bdFormat(data.closing_balance) : '');
                    }
                }
            });
        }

        getAccountClosingBalance();

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

        $(document).on('click', '#addAccountGroupBtn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var group_id = $(this).data('group_id');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#accountGroupAddOrEditModal').html(data);
                    $('#accountGroupAddOrEditModal').modal('show');


                    setTimeout(function() {

                        $('#account_group_name').focus();
                    }, 500);
                }
            })
        });

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#accountAddOrEditModal').empty();
                    $('#accountAddOrEditModal').html(data);
                    $('#accountAddOrEditModal').modal('show');

                    $('.data_preloader').hide();

                    setTimeout(function() {

                        $('#account_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
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
