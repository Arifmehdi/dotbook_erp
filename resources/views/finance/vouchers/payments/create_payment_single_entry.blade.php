@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #single_mode_account_list td {
            line-height: 15px;
        }

        #single_mode_account_list tr {
            cursor: pointer;
        }

        .selected_account {
            background-color: #746e70 !important;
            color: #fff !important;
            padding: 0px 3px;
            font-weight: 600;
            display: block;
        }

        ul.list-unstyled.account_list {
            min-height: 437px;
            max-height: 437px;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        .spinner_hidden::-webkit-outer-spin-button,
        .spinner_hidden::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        .spinner_hidden input[type=number] {
            -moz-appearance: textfield;
        }

        /* .payment_entry_table_area { max-height: 390px; min-height: 390px; overflow-y: scroll; overflow-x: hidden;} */

        /* TEST */
        .payment_entry_table_area {
            height: 410px;
            overflow: auto;
        }

        /* .payment_entry_table_area table th {background: #0f2f5e!important; box-shadow: 0px 0px 0 2px #e8e8e8!important;} */
        /* TEST */

        .payment_entry_table_area input {
            font-size: 12px;
        }

        .spinner_hidden {
            border: 1px solid #fff;
        }

        .curr_bl {
            font-size: 10px;
        }

        a.select_account {
            font-size: 11px;
            letter-spacing: 1px;
        }

        ul.account_list li {
            border-bottom: 1px solid #d1c6c6;
        }

        .table_tr_remove_btn:focus {
            box-sizing: border-box;
            box-shadow: 0 0 0 0.18rem rgb(231 49 49 / 50%);
            border: none;
            padding: 0px 1px;
            border-radius: 2px;
        }

        .table_tr_add_btn:focus {
            box-sizing: border-box;
            box-shadow: 0 0 0 0.18rem rgb(49 231 71 / 59%);
            border: none;
            padding: 0px 1px;
            border-radius: 2px;
        }

        .content-inner {
            padding: 14px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Add Payment (Single Entry) - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="form_element mt-0 border-0">
                    <div class="sec-name">
                        <h6>@lang('menu.add_payment_single_entry')</h6>
                        <x-back-button />
                    </div>
                </div>
            </div>

            <form id="add_payment_single_entry_form" action="{{ route('vouchers.payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="mode" value="1">
                <section class="p-15">
                    <div class="row g-1">
                        <div class="col-xl-9 col-md-7">

                            <div class="form_element m-0 mb-1 rounded">
                                <div class="element-body">
                                    <div class="row g-lg-4">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-md-5 text-end pe-2"><b>@lang('menu.credit_account')</b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-7">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" name="amount_types[]"
                                                                            id="amount_type" value="Cr">
                                                                        <input type="text"
                                                                            data-only_type="bank_or_cash_accounts"
                                                                            data-is_single_mode_first_ac_field="1"
                                                                            id="search_account" class="form-control fw-bold"
                                                                            autocomplete="off">
                                                                        <input type="hidden" id="account_name"
                                                                            class="voidable">
                                                                        <input type="hidden" id="default_account_name"
                                                                            class="voidable">
                                                                        <input type="hidden" name="account_ids[]"
                                                                            id="account_id"
                                                                            class="voidable credit_account_id">
                                                                        <input type="hidden" name="user_ids[]"
                                                                            id="user_id" class="voidable">
                                                                        <input type="hidden" name="payment_method_ids[]"
                                                                            id="payment_method_id" class="voidable">
                                                                        <input type="hidden" name="transaction_nos[]"
                                                                            id="transaction_no" class="voidable">
                                                                        <input type="hidden" name="cheque_nos[]"
                                                                            id="cheque_no" class="hidden">
                                                                        <input type="hidden" name="cheque_serial_nos[]"
                                                                            id="cheque_serial_no" class="voidable">
                                                                        <input type="hidden" name="cheque_issue_dates[]"
                                                                            id="cheque_issue_date" class="voidable">
                                                                        @php
                                                                            $uniqueId = uniqid();
                                                                        @endphp
                                                                        <input type="hidden"
                                                                            class="unique_id-{{ $uniqueId }}"
                                                                            id="unique_id" value="{{ $uniqueId }}">
                                                                        <input type="hidden" name="debit_amounts[]"
                                                                            id="debit_amount" value="0.00">
                                                                        <input type="hidden" name="credit_amounts[]"
                                                                            id="credit_amount" value="0.00">
                                                                        <p class="fw-bold text-muted curr_bl">Curr. Bal :
                                                                            <span id="account_balance"
                                                                                class="fw-bold text-dark"></span></p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-md-3 text-end pe-2"><b>@lang('menu.date')</b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-9">
                                                    <input type="text" name="date" id="date"
                                                        class="form-control"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}"
                                                        data-next="is_transaction_details" placeholder="@lang('menu.date')"
                                                        autocomplete="off">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <label class="col-md-7 text-end pe-2"><b>@lang('menu.add_transaction_details')</b></label>
                                                <div class="col-5">
                                                    <select name="is_transaction_details" class="form-control form-select"
                                                        id="is_transaction_details">
                                                        @php
                                                            $addTransactionDetails = '1';
                                                            if (isset($generalSettings->accounting_vouchers) && isset(json_decode($generalSettings->accounting_vouchers, true)['add_transaction_details']) && json_decode($generalSettings->accounting_vouchers, true)['add_transaction_details'] == '0') {
                                                                $addTransactionDetails = '0';
                                                            }
                                                        @endphp
                                                        <option {{ $addTransactionDetails == '1' ? 'SELECTED' : '' }}
                                                            value="1">@lang('menu.yes')</option>
                                                        <option {{ $addTransactionDetails == '0' ? 'SELECTED' : '' }}
                                                            value="0">@lang('menu.no')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="payment_entry_table_area">
                                                <table class="display table table-sm">
                                                    <thead class="staky">
                                                        <tr>
                                                            <th class="text-start">@lang('descriptions')</th>
                                                            <th class="text-end">@lang('menu.amount')</th>
                                                            <th class="text-center">...</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="single_mode_account_list"></tbody>

                                                    <tfoot>
                                                        <tr>
                                                            <th class="text-center" colspan="1">@lang('menu.total') :
                                                            </th>
                                                            <th class="text-end" id="show_debit_total">0.00</th>
                                                            <th class="text-center">...</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-5">
                            <div class="item-details-sec number-fields">
                                <div class="content-inner">

                                    <p><strong>@lang('menu.list_of_accounts')</strong></p>
                                    <ul class="list-unstyled account_list" id="account_list">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element m-0 mt-1 rounded">
                        <div class="element-body p-0 pb-1 pt-1">
                            <div class="row align-items-center">
                                <div class="col-md-9">
                                    <input type="hidden" name="debit_total" id="debit_total" value="0">
                                    <input type="hidden" name="credit_total" id="credit_total" value="0">

                                    <div class="input-group">
                                        <label class="px-2"><b>@lang('menu.remarks')</b></label>
                                        <input type="text" name="remarks" class="form-control" id="remarks"
                                            data-next="payment_submit_button" placeholder="@lang('menu.remarks')">
                                    </div>
                                </div>

                                <div class="col-md-3 text-end">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="loading-btn-box">
                                                <button type="button"
                                                    class="btn loading_button payment_loading_btn display-none"><i
                                                        class="fas fa-spinner"></i></button>
                                                <button type="button" id="payment_submit_button"
                                                    class="btn w-auto btn-success px-5 payment_submit_button">@lang('menu.save')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <div class="modal fade" id="selectUserModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.assign_sr')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fas fa-times"></span>
                    </a>
                </div>

                <div class="modal-body">
                    <form id="select_user_form" action="" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                @if (!auth()->user()->can('view_own_sale'))
                                    <div class="form-group">
                                        <label><b>@lang('menu.reference_by_sr')</b> <span class="text-danger">*</span></label>
                                        <div class="col-12">
                                            <input type="hidden" name="user_count" id="user_count" value="1">
                                            <select required name="user_id" id="selected_user_id"
                                                class="form-control select2 form-select">
                                                <option value="">@lang('menu.select_ac')</option>
                                                @foreach ($users as $user)
                                                    <option
                                                        data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}"
                                                        value="{{ $user->id }}">
                                                        {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <select required name="user_id" id="selected_user_id"
                                        class="form-control select2 form-select">
                                        <option selected
                                            data-user_name="{{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name . '/' . auth()->user()->phone }}"
                                            value="{{ auth()->user()->id }}">
                                            {{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name . '/' . auth()->user()->phone }}
                                        </option>
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-end">
                                <a href="#" class="btn btn-sm btn-success assignUserBtn" id="assignUserBtn"
                                    autofocus>@lang('menu.add')</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTransactionDetailsModal" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.transaction_details')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fas fa-times"></span>
                    </a>
                </div>

                <div class="modal-body">
                    <form id="add_transaction_details_form" action="" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <label class="col-md-3"><b>@lang('menu.type') :</b></label>
                                    <div class="col-md-9">
                                        <select id="trans_payment_method_id" class="form-control trans_input form-select">
                                            <option value="">Salect Payment Type</option>
                                            @foreach ($paymentMethods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="input-group">
                                    <label class="col-md-3"><b>@lang('menu.transaction_no') :</b></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control trans_input" id="trans_transaction_no"
                                            placeholder="@lang('menu.transaction_no')">
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-md-3"><b>@lang('menu.cheque_no') :</b></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control trans_input" id="trans_cheque_no"
                                            placeholder="@lang('menu.cheque_no')">
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-md-3"><b>@lang('menu.cheque_serial_no') :</b></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control trans_input"
                                            id="trans_cheque_serial_no" placeholder="@lang('menu.cheque_serial_no')">
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-md-3"><b>@lang('menu.cheque_issue_date') :</b></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control trans_input"
                                            id="trans_cheque_issue_date" placeholder="@lang('menu.cheque_issue_date')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="search_product">
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('#selected_user_id').select2();
        var ul = '';
        var selectObjClassName = '';
        var uniqueId = '';

        $(document).on('keypress', '#search_account', function(e) {

            var getUniqueId = $(this).closest('tr').find('#unique_id').val();
            uniqueId = getUniqueId;
            ul = document.getElementById('account_list');
            selectObjClassName = 'selected_account';

            if (e.which == 13) {

                $('.selected_account').click();
            }
        });

        $(document).on('mouseup', '#account_list a', function(e) {
            e.preventDefault();

            $('.select_account').removeClass('selected_account');
            $(this).addClass('selected_account');
            $(this).find('#selected_account').click();
        });

        $(document).on('focus', '#search_account', function(e) {

            var val = $(this).val();

            if (val) {

                $('#account_list').empty();
            }

            var getUniqueId = $(this).closest('tr').find('#unique_id').val();
            uniqueId = getUniqueId;
            ul = document.getElementById('account_list');
            selectObjClassName = 'selected_account';
        });

        $(document).on('blur', '#search_account', function(e) {

            ul = '';
            selectObjClassName = '';
        });

        var delay = (function() {
            var timer = 0;
            return function(callback, ms) {

                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $(document).on('input', '#search_account', function(e) {

            var keyword = $(this).val();
            var __keyword = keyword.replaceAll('/', '~');
            __keyword = __keyword.replaceAll('#', '^^^');
            var onlyType = $(this).data('only_type');
            var tr = $(this).closest('tr');

            if (keyword == '') {

                tr.find('#account_id').val('');
                tr.find('#default_account_name').val('');
                tr.find('#search_account').val('');
                tr.find('#account_balance').html('');
            }

            delay(function() {
                searchAccount(__keyword, onlyType);
            }, 200);
        });

        $(document).on('focus', '#search_account', function(e) {

            var tr = $(this).closest('tr');
            var onlyType = $(this).data('only_type');
            var keyword = tr.find('#default_account_name').val();
            var __keyword = keyword.replaceAll('/', '~');
            __keyword = __keyword.replaceAll('#', '^^^');
            delay(function() {
                searchAccount(__keyword, onlyType);
            }, 200);
        });

        function searchAccount(keyword, onlyType) {

            var __keyword = keyword ? keyword : 'NULL';

            var url = "{{ route('common.ajax.call.search.account', [':__keyword', ':onlyType']) }}";
            var route = url.replace(':__keyword', __keyword);
            route = route.replace(':onlyType', onlyType);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(accounts) {

                    var length = accounts.length;
                    $('.select_account').removeClass('selected_account');
                    var li = '';
                    if (onlyType == 'all') {

                        li =
                        '<li><a class="select_account text-danger" data-is_end_list="1" data-is_customer="0" data-account_name="" data-default_account_name="" data-account_id="" data-sub_sub_group_number="" href="#">List-End</a></li>';
                    } else {

                        li = '';
                    }

                    $.each(accounts, function(key, account) {

                        var groupName = ' (' + account.group_name + ')';
                        var accuntNumber = account.account_number != null ? ' - A/c No.: ' + account
                            .account_number : '';

                        li += '<li>';
                        li += '<a class="select_account ' + (key == 0 && length == 1 ?
                                'selected_account' : '') + '" data-is_end_list="0" data-is_customer="' +
                            (account.customer_id != null ? 1 : 0) + '" data-account_name="' + account
                            .name + accuntNumber + '" data-default_account_name="' + account.name +
                            '" data-account_id="' + account.id + '" data-sub_sub_group_number="' +
                            account.sub_sub_group_number + '" href="#"> ' + account.name +
                            accuntNumber + groupName + '</a>';
                        li += '</li>';
                    });

                    $('#account_list').html(li);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Please check the connetion.');
                        return;
                    }
                }
            });
        }

        var global_account_id = '';
        var global_account_name = '';
        var global_default_account_name = '';

        $(document).on('click', '.selected_account', function() {

            var account_id = $(this).data('account_id');
            var is_customer = $(this).data('is_customer');
            var account_name = $(this).data('account_name');
            var default_account_name = $(this).data('default_account_name');
            var sub_sub_group_number = $(this).data('sub_sub_group_number');
            var is_end_list = $(this).data('is_end_list');

            var tr = $('.unique_id-' + uniqueId).closest('tr');
            var isSingleModeFirstAcField = tr.find('#search_account').data('is_single_mode_first_ac_field');

            global_account_id = account_id;
            global_account_name = account_name;
            global_default_account_name = default_account_name;

            if (is_end_list == 1) {

                tr.remove();
                $('#remarks').focus().select();
                return;
            }

            if (is_customer == 0) {

                tr.find('#account_id').val(account_id);
                tr.find('#default_account_name').val(default_account_name);
                tr.find('#search_account').val(account_name);

                getAccountClosingBalance(account_id, uniqueId);

                var is_transaction_details = $('#is_transaction_details').val();

                if ((sub_sub_group_number == 1 || sub_sub_group_number == 11) && is_transaction_details == 1) {

                    var payment_method_id = tr.find('#payment_method_id').val();
                    var transaction_no = tr.find('#transaction_no').val();
                    var cheque_no = tr.find('#cheque_no').val();
                    var cheque_serial_no = tr.find('#cheque_serial_no').val();
                    var cheque_issue_date = tr.find('#cheque_issue_date').val();

                    $('#trans_payment_method_id').val(payment_method_id);
                    $('#trans_transaction_no').val(transaction_no);
                    $('#trans_cheque_no').val(cheque_no);
                    $('#trans_cheque_serial_no').val(cheque_serial_no);
                    $('#trans_cheque_issue_date').val(cheque_issue_date);

                    $('#addTransactionDetailsModal').modal('show');

                    setTimeout(function() {

                        $('#trans_payment_method_id').focus().select();
                    }, 500);

                    return;
                } else {

                    tr.find('#payment_method_id').val('');
                    tr.find('#transaction_no').val('');
                    tr.find('#cheque_no').val('');
                    tr.find('#cheque_serial_no').val('');
                    tr.find('#cheque_issue_date').val('');
                }

                if (isSingleModeFirstAcField == 1) {

                    var singleModeAccountList = $('#single_mode_account_list');
                    if (isEmpty(singleModeAccountList)) {

                        addNewRow();
                    }

                    $('#date').focus().select();
                } else {

                    tr.find('#debit_amount').focus().select();
                }
            } else {

                var user_count = $('#user_count').val('user_count');
                account_id = global_account_id;
                account_name = global_account_name;
                default_account_name = global_default_account_name;

                if (user_count) {

                    var previousSelectedUser = tr.find('#user_id').val();

                    $('#selected_user_id').val(previousSelectedUser).trigger('change');
                    $('#selectUserModal').modal('show');
                    setTimeout(function() {

                        var selected_user_id = $('#selected_user_id').val();
                        if (selected_user_id == '') {

                            $('#selected_user_id').focus();
                        } else {

                            $('.assignUserBtn').focus();
                        }
                    }, 500);

                    return;
                } else {

                    var user_id = $('#selected_user_id').val();
                    var user_name = $('#selected_user_id').data('user_name');
                    getCustomerClosingBalanceUserWise(user_id, account_id, user_name);
                    return;
                }
            }
        });

        $(document).on('input keypress', '#debit_amount', function(e) {

            var val = $(this).val();
            var tr = $(this).closest('tr');
            var nxt = tr.next();
            calculateAmount();

            if (e.keyCode == 13) {

                if (val == '' || val == 0) {

                    return;
                } else {

                    if (nxt.length == 0) {

                        addNewRow();
                    } else {

                        nxt.find('#search_account').focus().select();
                    }
                }
            }
        });

        $(document).on('click', '#add_entry_btn', function(e) {
            e.preventDefault();

            var tr = $(this).closest('tr');
            var account_id = tr.find('#account_id').val();
            var debitAmount = tr.find('#debit_amount').val();

            var nxt = tr.next();
            calculateAmount();

            if (account_id == '' || (debitAmount == 0 || debitAmount == '')) {

                return;
            } else {

                if (nxt.length == 0) {

                    addNewRow();
                } else {

                    nxt.find('#search_account').focus().select();
                }
            }
        });

        $(document).on('input keypress', '.trans_input', function(e) {

            if (e.keyCode == 13) {

                var nextI = $("input").index(this) + 1;
                next = $("input").eq(nextI);
                next.focus().select();
                // $('#remarks').focus().select();
            }
        });

        $('#trans_payment_method_id').click(function(e) {

            if (e.which == 0) {

                $("#trans_transaction_no").focus().select();
            }
        });

        $(document).on('keypress', '#trans_cheque_issue_date', function(e) {

            if (e.keyCode == 13) {

                var tr = $('.unique_id-' + uniqueId).closest('tr');
                var trans_payment_method_id = $('#trans_payment_method_id').val();
                var trans_transaction_no = $('#trans_transaction_no').val();
                var trans_cheque_no = $('#trans_cheque_no').val();
                var trans_cheque_serial_no = $('#trans_cheque_serial_no').val();
                var trans_cheque_issue_date = $('#trans_cheque_issue_date').val();

                tr.find('#payment_method_id').val(trans_payment_method_id);
                tr.find('#transaction_no').val(trans_transaction_no);
                tr.find('#cheque_no').val(trans_cheque_no);
                tr.find('#cheque_serial_no').val(trans_cheque_serial_no);
                tr.find('#cheque_issue_date').val(trans_cheque_issue_date);
                $('#addTransactionDetailsModal').modal('hide');

                var isSingleModeFirstAcField = tr.find('#search_account').data('is_single_mode_first_ac_field');

                if (isSingleModeFirstAcField == 1) {

                    var singleModeAccountList = $('#single_mode_account_list');

                    if (isEmpty(singleModeAccountList)) {

                        addNewRow();
                    }

                    $('#date').focus().select();
                } else {

                    tr.find('#debit_amount').focus().select();
                }
            }
        });

        function addNewRow() {

            var generate_unique_id = parseInt(Date.now() + Math.random());

            var html = '';
            html += '<tr data-active_disabled="1">';

            html += '<td>';
            html += '<div class="row py-1">';

            html += '<div class="col-2">';
            html +=
                '<input readonly type="text" name="amount_types[]" id="amount_type" class="form-control fw-bold" value="Dr" tabindex="-1">';
            html += '</div>';

            html += '<div class="col-6">';
            html +=
                '<input type="text" data-only_type="all" data-is_single_mode_first_ac_field="0" class="form-control fw-bold" id="search_account" autocomplete="off">';
            html += '<input type="hidden" id="account_name">';
            html += '<input type="hidden" id="default_account_name">';
            html += '<input type="hidden" name="account_ids[]" id="account_id">';
            html += '<input type="hidden" name="user_ids[]" id="user_id">';
            html += '<input type="hidden" name="payment_method_ids[]" id="payment_method_id">';
            html += '<input type="hidden" name="transaction_nos[]" id="transaction_no">';
            html += '<input type="hidden" name="cheque_nos[]" id="cheque_no">';
            html += '<input type="hidden" name="cheque_serial_nos[]" id="cheque_serial_no">';
            html += '<input type="hidden" name="cheque_issue_dates[]" id="cheque_issue_date">';
            html += '<input type="hidden" class="unique_id-' + generate_unique_id + '" id="unique_id" value="' +
                generate_unique_id + '">';
            html += '</div>';
            html += '<div class="col-4">';
            html +=
                '<p class="fw-bold text-muted curr_bl">Curr. Bal. : <span id="account_balance" class="fw-bold text-dark"></span></p>';
            html += '</div>';

            html += '</div>';

            html += '</td>';

            html += '<td>';
            html +=
                '<input type="number" step="anyu" name="debit_amounts[]" class="form-control fw-bold spinner_hidden text-end" id="debit_amount" value="0.00">';
            html += '<input type="hidden" name="credit_amounts[]" id="credit_amount" value="0.00">';
            html += '</td>';

            html += '<td>';
            html +=
                '<a href="#" id="remove_entry_btn" class="table_tr_remove_btn"><i class="fas fa-trash-alt text-danger mt-1"></i></a>';
            html +=
                '<a href="#" id="add_entry_btn" class="table_tr_add_btn ms-1"><i class="fa-solid fa-plus text-success mt-1"></i></a>';
            html += '</td>';

            html += '</tr>';

            $('#single_mode_account_list').append(html);

            var tr = $('.unique_id-' + generate_unique_id).closest('tr');

            previousTr = tr.prev();
            tr.find('#search_account').focus().select();
            calculateAmount();
        }

        function calculateAmount() {

            var debit_amounts = document.querySelectorAll('#debit_amount');
            totalDebitAmount = 0;

            debit_amounts.forEach(function(amount) {

                totalDebitAmount += parseFloat(amount.value ? amount.value : 0);
            });

            $('#show_debit_total').html(bdFormat(totalDebitAmount));
            $('#credit_total').val(parseFloat(totalDebitAmount));
            $('#debit_total').val(parseFloat(totalDebitAmount));
            $('#credit_amount').val(parseFloat(totalDebitAmount));
        }

        function isEmpty(el) {

            return !$.trim(el.html());
        }

        $(document).on('click', '#remove_entry_btn', function(e) {
            e.preventDefault();

            var tr = $(this).closest('tr');
            previousTr = tr.prev();
            nxtTr = tr.next();
            tr.remove();

            if (nxtTr.length == 1) {

                nxtTr.find('#search_account').focus().select();
            } else if (previousTr.length == 1) {

                previousTr.find('#search_account').focus().select();
            }

            calculateAmount();
        });

        $('#selected_user_id').on('select2:select', function(e) {

            var assignUserBtn = $('.assignUserBtn');

            setTimeout(function() {

                assignUserBtn.focus();
            }, 100);
        });

        $(document).on('click', '#assignUserBtn', function() {

            $('#select_user_form').submit();
        });

        $(document).on('submit', '#select_user_form', function(e) {
            e.preventDefault();

            var user_id = $('#selected_user_id').val();
            var user_name = $('#selected_user_id').find('option:selected').data('user_name');
            getCustomerClosingBalanceUserWise(user_id, global_account_id, user_name);
            return;
        });

        function getAccountClosingBalance(account_id, _uniqueId) {

            var filterObj = {
                user_id: null,
                from_date: null,
                to_date: null,
            };

            var url = "{{ route('vouchers.journals.account.closing.balance', ':account_id') }}";
            var route = url.replace(':account_id', account_id);

            $.ajax({
                url: route,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    var tr = $('.unique_id-' + _uniqueId).closest('tr');
                    tr.find('#account_balance').html(data['closing_balance_string']);
                }
            });
        }

        function getCustomerClosingBalanceUserWise(user_id, account_id, user_name) {

            var filterObj = {
                user_id: user_id,
                from_date: null,
                to_date: null,
            };

            var url = "{{ route('vouchers.receipts.user.wise.customer.closing.balance', ':account_id') }}";
            var route = url.replace(':account_id', account_id);

            $.ajax({
                url: route,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    $('#selectUserModal').modal('hide');
                    var tr = $('.unique_id-' + uniqueId).closest('tr');

                    tr.find('#account_balance').html(data['closing_balance_string']);

                    tr.find('#account_id').val(global_account_id);
                    tr.find('#account_name').val(global_account_name + ' - SR ' + user_name);
                    tr.find('#default_account_name').val(global_default_account_name);
                    tr.find('#search_account').val(global_account_name + ' - SR ' + user_name);
                    tr.find('#user_id').val(user_id);
                    tr.find('#debit_amount').focus().select();
                }
            });
        }
    </script>

    <script>
        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.payment_submit_button').prop('type', 'button');
        });

        isAllowSubmit = true;
        $(document).on('click', '.payment_submit_button', function() {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        $('#add_payment_single_entry_form').on('submit', function(e) {
            e.preventDefault();

            $('.payment_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            isAjaxIn = false;
            isAllowSubmit = false;

            $.ajax({
                beforeSend: function() {
                    isAjaxIn = true;
                },
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.payment_loading_btn').hide();
                    $('.error').html('');
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }
                    afterCreateSale();
                    toastr.success('Payment is created successfully');
                },
                error: function(err) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.payment_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    } else if (err.status == 403) {

                        toastr.error('Access Denied');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
        });


        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                if ($(this).attr('id') == 'is_transaction_details') {

                    var singleModeAccountList = $('#single_mode_account_list');

                    var creditAccountId = $('.credit_account_id').val();

                    if (creditAccountId == '') {

                        toastr.error('Please select a credit account first.');
                        return;
                    }

                    $('#single_mode_account_list #search_account')[0].focus().select();

                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                $('#' + nextId).focus().select();
            }
        });

        function afterCreateSale() {

            $('.loading_button').hide();
            $('.voidable').val('');
            $('.voidable').html('');
            $('#add_payment_single_entry_form')[0].reset();
            $('#single_mode_account_list').empty();
            $('#show_debit_total').html(parseFloat(0).toFixed(2));
            $('#debit_amount').val(parseFloat(0).toFixed(2));
            $('#credit_amount').val(parseFloat(0).toFixed(2));
            $('#debit_total').val(parseFloat(0).toFixed(2));
            $('#credit_total').val(parseFloat(0).toFixed(2));
            $('#search_account').focus();
        }

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

        new Litepicker({
            singleMode: true,
            element: document.getElementById('date'),
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
            format: _expectedDateFormat,
        });


        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.altKey && e.which == 84) {

                var isTransactionDetails = $('#is_transaction_details').val();
                if (isTransactionDetails == 0) {
                    $('#is_transaction_details').val(1);
                } else if (isTransactionDetails == 1) {
                    $('#is_transaction_details').val(0);
                }
                return false;
            }
        }

        $('#search_account').focus();
    </script>
    <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script>
@endpush
