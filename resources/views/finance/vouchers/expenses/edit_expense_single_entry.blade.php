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

        #cost_centre_table_row_list td {
            line-height: 15px;
        }

        #cost_centre_table_row_list tr {
            cursor: pointer;
        }

        .cost_centre_list_for_entry_table_area table tbody tr td {
            line-height: 1px!important;
            height: 14px;
            font-size: 12px!important;
        }

        .selected_account {
            background-color: #746e70 !important;
            color: #fff !important;
            padding: 0px 3px;
            font-weight: 600;
            display: block;
        }

        .selected_cost_centre {
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

        ul.list-unstyled.cost_centre_list {
            min-height: 361px;
            max-height: 361px;
            overflow-y: scroll;
            overflow-x: hidden;
            padding: 1px 2px;
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

        /* .expense_entry_table_area { max-height: 390px; min-height: 390px; overflow-y: scroll; overflow-x: hidden;} */

        /* TEST */

        .expense_entry_table_area table th {
            box-shadow: 0px 0px 0 2px #e8e8e8 !important;
        }

        .expense_entry_table_area {
            height: 400px;
            overflow: auto;
        }

        .cost_centre_table_area table th {
            box-shadow: 0px 0px 0 2px #e8e8e8 !important;
        }

        /* TEST */

        .expense_entry_table_area input {
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

        a.select_cost_centre {
            font-size: 10px;
            letter-spacing: 1px;
        }

        ul.cost_centre_list li {
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
            padding: 9px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', __('menu.edit_expense_single_entry') . ' - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="form_element mt-0 border-0">
                    <div class="sec-name">
                        <h6>@lang('menu.edit_expense_single_entry') | @lang('menu.voucher_no') : {{ $expense->voucher_no }}</h6>
                        <div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i
                                    class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')</a>
                        </div>
                    </div>
                </div>
            </div>

            <form id="add_expense_single_entry_form" action="{{ route('vouchers.expenses.update', $expense->id) }}"
                method="POST">
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
                                                    <table class="w-100">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="amount_types[]"
                                                                        id="amount_type" value="Cr">
                                                                    @php
                                                                        $accuntNumber = $expense?->singleModeCreditDescription?->account?->account_number ? ' - A/c No.: ' . $expense?->singleModeCreditDescription?->account?->account_number : '';
                                                                    @endphp

                                                                    <input type="text"
                                                                        data-only_type="bank_or_cash_accounts"
                                                                        data-is_single_mode_first_ac_field="1"
                                                                        id="search_account" class="form-control fw-bold"
                                                                        value="{{ $expense?->singleModeCreditDescription?->account?->name . $accuntNumber }}"
                                                                        autocomplete="off">
                                                                    <input type="hidden" id="account_name" class="voidable"
                                                                        value="{{ $expense?->singleModeCreditDescription?->account?->name . $accuntNumber }}">
                                                                    <input type="hidden" id="default_account_name"
                                                                        class="voidable"
                                                                        value="{{ $expense?->singleModeCreditDescription?->account?->name }}">
                                                                    <input type="hidden" name="account_ids[]"
                                                                        id="account_id" class="voidable"
                                                                        value="{{ $expense?->singleModeCreditDescription?->account_id }}">
                                                                    <input type="hidden" name="payment_method_ids[]"
                                                                        id="payment_method_id" class="voidable"
                                                                        value="{{ $expense?->singleModeCreditDescription?->payment_method_id }}">
                                                                    <input type="hidden" name="transaction_nos[]"
                                                                        id="transaction_no" class="voidable"
                                                                        value="{{ $expense?->singleModeCreditDescription?->transaction_no }}">
                                                                    <input type="hidden" name="cheque_nos[]" id="cheque_no"
                                                                        class="hidden"
                                                                        value="{{ $expense?->singleModeCreditDescription?->cheque_no }}">
                                                                    <input type="hidden" name="cheque_serial_nos[]"
                                                                        id="cheque_serial_no" class="voidable"
                                                                        value="{{ $expense?->singleModeCreditDescription?->cheque_serial_no }}">
                                                                    <input type="hidden" name="cheque_issue_dates[]"
                                                                        id="cheque_issue_date" class="voidable"
                                                                        value="{{ $expense?->singleModeCreditDescription?->cheque_issue_date }}">
                                                                    <input type="hidden" name="indexes[]" id="index"
                                                                        value="0">
                                                                    <input type="hidden" name="expense_description_ids[]"
                                                                        id="expense_description_id"
                                                                        value="{{ $expense?->singleModeCreditDescription?->id }}">
                                                                    @php
                                                                        $uniqueId = uniqid();
                                                                    @endphp
                                                                    <input type="hidden"
                                                                        class="unique_id-{{ $uniqueId }}"
                                                                        id="unique_id" value="{{ $uniqueId }}">
                                                                    <input type="hidden" name="debit_amounts[]"
                                                                        id="debit_amount" value="0.00">
                                                                    <input type="hidden" name="credit_amounts[]"
                                                                        id="credit_amount"
                                                                        value="{{ $expense->credit_total }}">
                                                                    <input type="hidden" id="main_group_number"
                                                                        class="voidable"
                                                                        value="{{ $expense?->singleModeCreditDescription?->account?->group?->main_group_number }}">
                                                                    <div class="cost_centre_list_for_entry_table_area">
                                                                    </div>
                                                                    @php
                                                                        $amounts = $accountUtil->accountClosingBalance($expense?->singleModeCreditDescription?->account_id);
                                                                        $balance = $amounts['closing_balance_string'];
                                                                    @endphp
                                                                    <p class="fw-bold text-muted curr_bl">Curr. Bal : <span
                                                                            id="account_balance"
                                                                            class="fw-bold text-dark">{{ $balance }}</span>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label class="col-md-4"><b>@lang('menu.date')</b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" id="date"
                                                        class="form-control"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($expense->date)) }}"
                                                        placeholder="@lang('menu.date')" autocomplete="off">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-md-7"><b>@lang('menu.transaction_details')</b></label>
                                                <div class="col-5">
                                                    <select name="is_transaction_details" class="form-control form-select"
                                                        id="is_transaction_details">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option
                                                            {{ $expense->is_transaction_details == 0 ? 'selected' : '' }}
                                                            value="0">@lang('menu.no')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-md-8"><b>@lang('menu.maintain_cost_centre')</b></label>
                                                <div class="col-4">
                                                    <select name="maintain_cost_centre" class="form-control form-select"
                                                        id="maintain_cost_centre">
                                                        <option value="1">@lang('menu.yes')</option>
                                                        <option {{ $expense->maintain_cost_centre == 0 ? 'selected' : '' }}
                                                            value="0">@lang('menu.no')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="expense_entry_table_area">
                                        <table class="display data__table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">@lang('descriptions')</th>
                                                    <th class="text-end">@lang('menu.amount')</th>
                                                    <th class="text-center">...</th>
                                                </tr>
                                            </thead>

                                            <tbody id="single_mode_account_list">
                                                @foreach ($expense->singleModeDebitDescriptions as $debitDescription)
                                                    @php
                                                        $rowIndex = $loop->index + 1;
                                                    @endphp

                                                    <tr data-active_disabled="1">
                                                        <td>
                                                            <div class="row py-1">
                                                                <div class="col-2">
                                                                    <input readonly type="text"
                                                                        name="amount_types[]" id="amount_type"
                                                                        class="form-control fw-bold" value="Dr"
                                                                        tabindex="-1">
                                                                </div>

                                                                <div class="col-6">
                                                                    @php
                                                                        $accuntNumber = $debitDescription?->account?->account_number ? ' - A/c No.: ' . $debitDescription?->account?->account_number : '';
                                                                    @endphp

                                                                    <input type="text" data-only_type="all"
                                                                        data-is_single_mode_first_ac_field="0"
                                                                        class="form-control fw-bold"
                                                                        id="search_account"
                                                                        value="{{ $debitDescription?->account?->name . $accuntNumber }}"
                                                                        autocomplete="off">
                                                                    <input type="hidden" id="account_name"
                                                                        value="{{ $debitDescription?->account?->name . $accuntNumber }}">
                                                                    <input type="hidden" id="default_account_name"
                                                                        value="{{ $debitDescription?->account?->name }}">
                                                                    <input type="hidden" name="account_ids[]"
                                                                        id="account_id"
                                                                        value="{{ $debitDescription->account_id }}">
                                                                    <input type="hidden" name="payment_method_ids[]"
                                                                        id="payment_method_id"
                                                                        value="{{ $debitDescription->payment_method_id }}">
                                                                    <input type="hidden" name="transaction_nos[]"
                                                                        id="transaction_no"
                                                                        value="{{ $debitDescription->transaction_no }}">
                                                                    <input type="hidden" name="cheque_nos[]"
                                                                        id="cheque_no"
                                                                        value="{{ $debitDescription->cheque_no }}">
                                                                    <input type="hidden" name="cheque_serial_nos[]"
                                                                        id="cheque_serial_no"
                                                                        value="{{ $debitDescription->cheque_serial_no }}">
                                                                    <input type="hidden" name="cheque_issue_dates[]"
                                                                        id="cheque_issue_date"
                                                                        value="{{ $debitDescription->cheque_issue_date }}">
                                                                    <input type="hidden"
                                                                        name="expense_description_ids[]"
                                                                        id="expense_description_id"
                                                                        value="{{ $debitDescription->id }}">

                                                                    @php
                                                                        $uniqueId = uniqid();
                                                                    @endphp
                                                                    <input type="hidden"
                                                                        class="unique_id-{{ $uniqueId }}"
                                                                        id="unique_id" value="{{ $uniqueId }}">
                                                                    <input type="hidden" id="main_group_number"
                                                                        class="voidable"
                                                                        value="{{ $debitDescription?->account?->group?->main_group_number }}">
                                                                    <input type="hidden" name="indexes[]"
                                                                        id="index" value="{{ $rowIndex }}">
                                                                    <div class="cost_centre_list_for_entry_table_area">
                                                                        @if (count($debitDescription->voucherEntryCostCentres))
                                                                            <table class="w-100">
                                                                                <tbody>
                                                                                    @foreach ($debitDescription->voucherEntryCostCentres as $row)
                                                                                        <tr>
                                                                                            <td class="w-60">
                                                                                                {{ $row?->costCentre?->name }}
                                                                                                <input type="hidden"
                                                                                                    name="cost_centre_ids[{{ $rowIndex }}][]"
                                                                                                    value="{{ $row->cost_centre_id }}"
                                                                                                    class="voidable">
                                                                                            </td>

                                                                                            <td>
                                                                                                :
                                                                                                {{ App\Utils\Converter::format_in_bdt($row->amount) }}
                                                                                                <input type="hidden"
                                                                                                    name="cost_centre_amounts[{{ $rowIndex }}][]"
                                                                                                    value="{{ $row->amount }}">
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="col-4">
                                                                    @php
                                                                        $amounts = $accountUtil->accountClosingBalance($debitDescription->account_id);
                                                                        $balance = $amounts['closing_balance_string'];
                                                                    @endphp
                                                                    <p class="fw-bold text-muted curr_bl">Curr. Bal :
                                                                        <span id="account_balance"
                                                                            class="fw-bold text-dark">{{ $balance }}</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <input type="number" step="any"
                                                                name="debit_amounts[]"
                                                                class="form-control fw-bold spinner_hidden text-end"
                                                                id="debit_amount"
                                                                value="{{ $debitDescription->amount }}">
                                                            <input type="hidden" name="credit_amounts[]"
                                                                id="credit_amount" value="0.00">
                                                        </td>

                                                        <td>
                                                            <a href="#" id="remove_entry_btn"
                                                                class="table_tr_remove_btn"><i
                                                                    class="fas fa-trash-alt text-danger mt-1"></i></a>
                                                            <a href="#" id="add_entry_btn"
                                                                class="table_tr_add_btn ms-1"><i
                                                                    class="fa-solid fa-plus text-success mt-1"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <th class="text-center" colspan="1">@lang('menu.total') :</th>
                                                    <th class="text-end" id="show_debit_total">
                                                        {{ App\Utils\Converter::format_in_bdt($expense->debit_total) }}
                                                    </th>
                                                    <th class="text-center">...</th>
                                                </tr>
                                            </tfoot>
                                        </table>
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

                        <div class="col-12">
                            <div class="form_element m-0 rounded">
                                <div class="element-body p-0 pb-1 pt-1">
                                    <div class="row align-items-center">
                                        <div class="col-md-9">
                                            <input type="hidden" name="debit_total" id="debit_total"
                                                value="{{ $expense->debit_total }}">
                                            <input type="hidden" name="credit_total" id="credit_total"
                                                value="{{ $expense->credit_total }}">

                                            <div class="input-group">
                                                <label class="pe-2"><b>@lang('menu.remarks')</b></label>
                                                <input type="text" name="remarks" class="form-control" id="remarks"
                                                    value="{{ $expense->note }}" data-next="expense_submit_button"
                                                    placeholder="@lang('menu.remarks')">
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-end">
                                            <div class="row justify-content-center">
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <div class="loading-btn-box">
                                                        <button type="button"
                                                            class="btn loading_button expense_loading_btn display-none"><i
                                                                class="fas fa-spinner"></i></button>
                                                        <button type="button" id="expense_submit_button"
                                                            class="btn w-auto btn-success px-5 expense_submit_button">@lang('menu.save')</button>
                                                    </div>
                                                </div>
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

    @include('finance.vouchers.modal_partials.add_transaction_details_modal')
    @include('finance.vouchers.cost_centers.modal_partial.cost_centre_modal')
    <input type="hidden" id="search_product">
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @include('finance.vouchers.expenses.js_partials.single_mode.expense_edit_single_mode_js')
    @include('finance.vouchers.cost_centers.js_partial.cost_centre_js')
    <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script>
@endpush
