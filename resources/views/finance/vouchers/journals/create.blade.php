@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #journal_account_list td {
            line-height: 15px;
        }

        #journal_account_list tr {
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
            min-height: 442px;
            max-height: 442px;
            overflow-y: scroll;
            overflow-x: hidden;
            padding: 1px 2px;
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

        /* TEST */
        .journal_entry_table_area {
            height: 426px;
            overflow: auto;
        }

        /* .journal_entry_table_area table th { background: #0f2f5e!important; box-shadow: 0px 0px 0 2px #e8e8e8!important; } */

        .cost_centre_table_area {
            height: 400px;
            border: 1px solid black;
            overflow: auto;
        }

        /* .cost_centre_table_area table th { background: #0f2f5e!important; box-shadow: 0px 0px 0 2px #e8e8e8!important; } */
        /* TEST */

        .journal_entry_table_area input {
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
            padding: 11px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Add Journal Voucher - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="form_element mt-0 border-0">
                    <div class="sec-name">
                        <h6>@lang('menu.add_journal_voucher')</h6>
                        <x-back-button />
                    </div>
                </div>
            </div>

            <form id="add_journal_form" action="{{ route('vouchers.journals.store') }}" method="POST">
                @csrf
                <input type="hidden" id="mode" value="2">
                <section class="p-15">
                    <div class="row g-1">
                        <div class="col-xl-9 col-md-7">
                            <div class="form_element m-0 mb-1 rounded">
                                <div class="element-body">
                                    <div class="row g-lg-4">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-md-3 text-end pe-2"><b>@lang('menu.date')</b> <span class="text-danger">*</span></label>
                                                <div class="col-9">
                                                    <input type="text" name="date" id="date" class="form-control" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" data-next="is_transaction_details" placeholder="@lang('menu.date')" autocomplete="off">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <label class="col-md-6 text-end pe-2"><b>@lang('menu.add_transaction_details')</b></label>
                                                <div class="col-6">
                                                    <select name="is_transaction_details" class="form-control form-select" id="is_transaction_details" data-next="maintain_cost_centre">
                                                        @php
                                                            $addTransactionDetails = '1';
                                                            if (isset($generalSettings->accounting_vouchers) && isset(json_decode($generalSettings->accounting_vouchers, true)['add_transaction_details']) && json_decode($generalSettings->accounting_vouchers, true)['add_transaction_details'] == '0') {
                                                                $addTransactionDetails = '0';
                                                            }
                                                        @endphp
                                                        <option {{ $addTransactionDetails == '1' ? 'SELECTED' : '' }} value="1">@lang('menu.yes')</option>
                                                        <option {{ $addTransactionDetails == '0' ? 'SELECTED' : '' }} value="0">@lang('menu.no')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-md-6 text-end pe-2"><b>@lang('menu.maintain_cost_centre')</b></label>
                                                <div class="col-6">
                                                    <select name="maintain_cost_centre" class="form-control form-select" id="maintain_cost_centre" data-next="search_account">
                                                        @php
                                                            $maintainCostCentre = '1';
                                                            if (isset($generalSettings->accounting_vouchers) && isset(json_decode($generalSettings->accounting_vouchers, true)['maintain_cost_centre']) && json_decode($generalSettings->accounting_vouchers, true)['maintain_cost_centre'] == '0') {
                                                                $maintainCostCentre = '0';
                                                            }
                                                        @endphp
                                                        <option {{ $maintainCostCentre == '1' ? 'SELECTED' : '' }} value="1">@lang('menu.yes')</option>
                                                        <option {{ $maintainCostCentre == '0' ? 'SELECTED' : '' }} value="0">@lang('menu.no')</option>
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
                                            <div class="journal_entry_table_area">
                                                <div class="table-responsive">
                                                    <table class="display data__table table-striped">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th class="text-start">@lang('menu.descriptions')</th>
                                                                <th class="text-end">@lang('menu.debit')</th>
                                                                <th class="text-end">@lang('menu.credit')</th>
                                                                <th class="text-center">...</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="journal_account_list">
                                                            <tr>
                                                                <td>
                                                                    <div class="row py-1">
                                                                        <div class="col-2">
                                                                            <input readonly type="text" name="amount_types[]" id="amount_type" maxlength="2" class="form-control fw-bold" value="Dr" tabindex="-1">
                                                                        </div>

                                                                        <div class="col-6">
                                                                            <input type="text" data-only_type="all" class="form-control fw-bold" id="search_account" autocomplete="off">
                                                                            <input type="hidden" id="account_name" class="voidable">
                                                                            <input type="hidden" id="default_account_name" class="voidable">
                                                                            <input type="hidden" name="account_ids[]" id="account_id" class="voidable">
                                                                            <input type="hidden" name="user_ids[]" id="user_id" class="voidable">
                                                                            <input type="hidden" name="payment_method_ids[]" id="payment_method_id" class="voidable">
                                                                            <input type="hidden" name="transaction_nos[]" id="transaction_no" class="voidable">
                                                                            <input type="hidden" name="cheque_nos[]" id="cheque_no" class="voidable">
                                                                            <input type="hidden" name="cheque_serial_nos[]" id="cheque_serial_no" class="voidable">
                                                                            <input type="hidden" name="cheque_issue_dates[]" id="cheque_issue_date" class="voidable">
                                                                            <input type="hidden" name="remarkable_notes[]" id="remarkable_note" class="voidable">
                                                                            <input type="hidden" name="indexes[]" id="index" value="0">
                                                                            <input type="hidden" name="journal_entry_ids[]" id="journal_entry_id" value="">
                                                                            @php
                                                                                $uniqueId = uniqid();
                                                                            @endphp
                                                                            <input type="hidden" class="unique_id-{{ $uniqueId }}" id="unique_id" value="{{ $uniqueId }}">
                                                                            <input type="hidden" id="main_group_number" class="voidable">
                                                                            <div class="cost_centre_list_for_entry_table_area">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-4">
                                                                            <p class="fw-bold text-muted curr_bl">
                                                                                @lang('menu.curr_bal') : <span id="account_balance" class="fw-bold text-dark voidable"></span>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <p class="m-0 p-0 fw-bold" id="show_debit_amount"></p>
                                                                    <input type="number" step="any" name="debit_amounts[]" class="form-control fw-bold spinner_hidden display-none text-end" id="debit_amount" value="0.00">
                                                                </td>

                                                                <td>
                                                                    <p class="m-0 p-0 fw-bold" id="show_credit_amount">
                                                                    </p>
                                                                    <input type="number" step="any" name="credit_amounts[]" class="form-control fw-bold spinner_hidden display-none text-end" id="credit_amount" value="0.00">
                                                                </td>

                                                                <td>
                                                                    <div class="row g-0">
                                                                        <div class="col-md-6">
                                                                            <a href="#" onclick="return false;" tabindex="-1" class="d-inline"><i class="fas fa-trash-alt text-secondary mt-1"></i></a>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <a href="#" id="add_entry_btn" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>

                                                        <tfoot>
                                                            <tr>
                                                                <th class="text-center" colspan="1">@lang('menu.total')
                                                                    :</th>
                                                                <th class="text-end" id="show_debit_total">0.00</th>
                                                                <th class="text-end" id="show_credit_total">0.00</th>
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
                                        <input type="text" name="remarks" class="form-control" id="remarks" data-next="journal_submit_btn" placeholder="@lang('menu.remarks')">
                                    </div>
                                </div>

                                <div class="col-md-3 text-end">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="loading-btn-box">
                                                <button type="button" class="btn loading_button journal_loading_btn display-none"><i class="fas fa-spinner"></i></button>
                                                <button type="button" id="journal_submit_btn" class="btn w-auto btn-success px-5 submit_button">@lang('menu.save')</button>
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

    @include('finance.vouchers.modal_partials.select_user_modal')
    @include('finance.vouchers.modal_partials.add_transaction_details_modal')
    @include('finance.vouchers.cost_centers.modal_partial.cost_centre_modal')
    {{-- <div class="modal fade" id="serverSideModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div> --}}
    <input type="hidden" id="search_product">
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @include('finance.vouchers.journals.js_partials.journal_create_js')
    @include('finance.vouchers.cost_centers.js_partial.cost_centre_js')
    {{-- <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script> --}}
    <script src="{{ asset('plugins/select_li/selectli.custom.vouchers.js') }}"></script>
@endpush
