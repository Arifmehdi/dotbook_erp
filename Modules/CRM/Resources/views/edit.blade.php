@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>Edit Expense</h6>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i
                    class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
        </div>
        <form id="edit_expanse_form" action="{{ route('expanses.update', $expense->id) }}" enctype="multipart/form-data"
            method="POST">
            @csrf
            <section class="p-15">
                <div class="row">
                    <div class="col-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.voucher_no') </b> </label>
                                            <div class="col-8">
                                                <input readonly type="text" name="voucher_no" id="voucher_no"
                                                    class="form-control" placeholder="@lang('menu.voucher_no')"
                                                    value="{{ $expense->voucher_no }}" autofocus>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.expense_date') </b> </label>
                                            <div class="col-8">
                                                <input required type="text" name="date"
                                                    class="form-control datepicker changeable"
                                                    value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($expense->date)) }}"
                                                    id="datepicker">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <div class="heading_area mb-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="text-muted m-0 p-0 ps-1 float-start mt-1"><b>@lang('menu.descriptions')</b></p>
                                        </div>

                                        <div class="col-md-6">
                                            <a href="{{ route('expense.account.quick.add.modal') }}"
                                                class="btn btn-sm btn-success float-end"
                                                id="addNewExpenseAc"><b>@lang('menu.add_new_expense_ac')</b></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="expense_description_table">
                                            <div class="table-responsive">
                                                <table class="table modal-table table-sm">
                                                    <tbody id="description_body">
                                                        @foreach ($expense->expenseDescriptions as $description)
                                                            <tr>
                                                                <td id="index">
                                                                    <b><span
                                                                            class="serial">{{ $loop->index + 1 }}</span></b>
                                                                    <input class="index-{{ $loop->index + 1 }}"
                                                                        type="hidden" id="index">
                                                                </td>

                                                                <td>
                                                                    <select required name="expense_account_ids[]"
                                                                        class="form-control expense_account_id special_text"
                                                                        id="expense_account_id">
                                                                        <option data-expense_ac_balance="" value="">
                                                                            @lang('menu.select_expense_ac')</option>
                                                                        @php
                                                                            $selectedAcBalance = '';
                                                                        @endphp
                                                                        @foreach ($expenseAccounts as $expenseAccount)
                                                                            @php

                                                                                $accountType = '';
                                                                                if ($expenseAccount->account_type == 7) {
                                                                                    $accountType = 'Direct Expense : ';
                                                                                } elseif ($expenseAccount->account_type == 8) {
                                                                                    $accountType = 'Indirect Expense : ';
                                                                                } elseif ($expenseAccount->account_type == 9) {
                                                                                    $accountType = 'Current Asset : ';
                                                                                } elseif ($expenseAccount->account_type == 15) {
                                                                                    $accountType = 'Fixed Asset A/c : ';
                                                                                } elseif ($expenseAccount->account_type == 16) {
                                                                                    $accountType = 'Investments A/c : ';
                                                                                } elseif ($expenseAccount->account_type == 21) {
                                                                                    $accountType = 'Payroll A/c : ';
                                                                                }

                                                                                if ($description->expense_account_id == $expenseAccount->id) {
                                                                                    $selectedAcBalance = $expenseAccount->balance;
                                                                                }
                                                                            @endphp
                                                                            <option class="special_text"
                                                                                {{ $description->expense_account_id == $expenseAccount->id ? 'SELECTED' : '' }}
                                                                                data-expense_ac_balance="{{ $expenseAccount->balance }}"
                                                                                value="{{ $expenseAccount->id }}">
                                                                                {{ $accountType . $expenseAccount->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <p><strong>@lang('menu.curr_balance') :</strong> <span
                                                                            id="expense_ac_closing_balance">{{ $selectedAcBalance }}</span>
                                                                    </p>
                                                                </td>

                                                                <td>
                                                                    <input required type="number" name="amounts[]"
                                                                        step="any" class="form-control" id="amount"
                                                                        value="{{ $description->amount }}"
                                                                        placeholder="Amount">
                                                                </td>

                                                                <td>
                                                                    @if ($loop->index == 0)
                                                                        <div class="btn_30_blue">
                                                                            <a id="addMore" href="#"><i
                                                                                    class="fas fa-plus-square"></i></a>
                                                                        </div>
                                                                    @else
                                                                        <a href="#" class="action-btn c-delete"
                                                                            id="remove_btn"><span
                                                                                class="fas fa-trash px-2 py-3"></span></a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="2" class="text-end">@lang('menu.total') :</th>
                                                            <th colspan="2" id="expense_total">
                                                                {{ $expense->total_amount }}</th>
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

                <div class="row mb-1">
                    <div class="col-12">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-2"><b>@lang('menu.tax') </b> </label>
                                            <div class="col-10">
                                                <div class="input-group">
                                                    <select name="tax_percent" class="form-control form-select"
                                                        id="tax_percent">
                                                        <option data-tax_account_id="" value="0.00">@lang('menu.no_tax')
                                                        </option>
                                                        @foreach ($taxAccounts as $taxAc)
                                                            <option
                                                                {{ $taxAc->id == $expense->tax_account_id ? 'SELECTED' : '' }}
                                                                data-tax_account_id="{{ $taxAc->id }}"
                                                                value="{{ $taxAc->tax_percent }}">{{ $taxAc->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="tax_account_id" class="form-control"
                                                        id="tax_account_id" value="{{ $expense->tax_account_id }}">
                                                    <input readonly type="number" name="tax_amount" class="form-control"
                                                        id="tax_amount" placeholder="{{ $expense->tax_amount }}"
                                                        tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-2"><b>@lang('menu.net_total') </b> </label>
                                            <div class="col-10">
                                                <input readonly name="net_total_amount" type="number" step="any"
                                                    id="net_total_amount" class="form-control"
                                                    value="{{ $expense->net_total_amount }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-2"><b>@lang('menu.expense_note') </b> </label>
                                            <div class="col-10">
                                                <textarea class="form-control ckEditor" name="expense_note" cols="10" rows="3"
                                                    placeholder="@lang('menu.expense_note')">{{ $expense->note }}</textarea>
                                                <input readonly type="hidden" name="total_amount"
                                                    data-name="Total amount" id="total_amount"
                                                    value="{{ $expense->total_amount }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('expanses.partials.expensePaymentEditSection')
            </section>
        </form>
    </div>

    <div class="modal fade" id="addQuickExpenseAcModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Set accounts in payment and payment edit form
        // Set accounts in payment and payment edit form
        var expenseAccountsArr = [];

        function setExpenseAccounts() {

            $.ajax({
                url: "{{ route('expenses.accounts.by.ajax') }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(expenseAccounts) {

                    expenseAccountsArr = expenseAccounts;

                    $.each(expenseAccounts, function(key, expenseAccount) {

                        var accountType = '';
                        if (expenseAccount.account_type == 7) {

                            accountType = 'Direct Expense : ';
                        } else if (expenseAccount.account_type == 8) {

                            accountType = 'Indirect Expense : ';
                        } else if (expenseAccount.account_type == 9) {

                            accountType = 'Current Asset : ';
                        } else if (expenseAccount.account_type == 15) {

                            accountType = 'Fixed Asset A/c : ';
                        } else if (expenseAccount.account_type == 16) {

                            accountType = 'Investments A/c : ';
                        } else if (expenseAccount.account_type == 21) {

                            accountType = 'Payroll A/c : ';
                        } else {

                            accountType = 'Misc. Expense A/c : ';
                        }
                    });
                }
            });
        }
        setExpenseAccounts();

        // Calculate amount
        function calculateAmount() {

            var indexs = document.querySelectorAll('#index');
            indexs.forEach(function(index) {

                var className = index.getAttribute("class");
                var rowIndex = $('.' + className).closest('tr').index();
                $('.' + className).closest('tr').find('.serial').html(rowIndex + 1);
            });

            var amounts = document.querySelectorAll('#amount');
            totalAmount = 0;

            amounts.forEach(function(amount) {

                totalAmount += parseFloat(amount.value ? amount.value : 0);
            });

            $('#total_amount').val(parseFloat(totalAmount).toFixed(2));
            $('#expense_total').html(parseFloat(totalAmount).toFixed(2));

            var tax_percent = $('#tax_percent').val() ? $('#tax_percent').val() : 0;
            var tax_amount = parseFloat(totalAmount) / 100 * parseFloat(tax_percent);
            $('#tax_amount').val(parseFloat(tax_amount).toFixed(2));

            var netTotalAmount = parseFloat(totalAmount) + parseFloat(tax_amount);
            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

            $('#total_payable').html(parseFloat(netTotalAmount).toFixed(2));
            var previousePaid = $('#previous_paid').val();
            var currentPayable = parseFloat(netTotalAmount) - parseFloat(previousePaid);
            $('#current_payable').val(parseFloat(currentPayable).toFixed(2));

            var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
            var totalDue = parseFloat(currentPayable) - parseFloat(payingAmount);
            $('#due').val(parseFloat(totalDue).toFixed(2));
        }

        $(document).on('input', '#amount', function() {

            calculateAmount();
        });

        $('#tax_percent').on('change', function() {

            calculateAmount();
        });

        $('#paying_amount').on('input', function() {

            calculateAmount();
        });

        $(document).on('click', '#remove_btn', function(e) {
            e.preventDefault();

            $(this).closest('tr').remove();
            calculateAmount();
        });

        //Add purchase request by ajax
        $('#edit_expanse_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'ERROR');
                        $('.loading_button').hide();
                    }

                    if (!$.isEmptyObject(data.successMsg)) {

                        $('.loading_button').hide();
                        toastr.success(data.successMsg);
                        window.location = "{{ route('vouchers.expenses.index') }}";
                    }
                },
                error: function(err) {

                    $('.loading_button').hide();
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

                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        var index = 1;
        $(document).on('click', '#addMore', function(e) {
            e.preventDefault();
            var html = '';
            html += '<tr class="more_accounts">';
            html += '<td>';
            html += '<b><span class="serial">' + (index + 1) + '</span></b>';
            html += '<input class="index-' + (index + 1) + '" type="hidden" id="index">';
            html += '</td>';
            html += '<td>';
            html +=
                '<select required name="expense_account_ids[]" class="form-control expense_account_id special_text form-select" id="expense_account_id">';
            html += '<option data-expense_ac_balance="" value="">Select Expense A/c</option>';

            $.each(expenseAccountsArr, function(key, expenseAccount) {

                var accountType = '';
                if (expenseAccount.account_type == 7) {

                    accountType = 'Direct Expense : ';
                } else if (expenseAccount.account_type == 8) {

                    accountType = 'Indirect Expense : ';
                } else if (expenseAccount.account_type == 9) {

                    accountType = 'Current Asset : ';
                } else if (expenseAccount.account_type == 15) {

                    accountType = 'Fixed Asset A/c : ';
                } else if (expenseAccount.account_type == 16) {

                    accountType = 'Investments A/c : ';
                } else if (expenseAccount.account_type == 21) {

                    accountType = 'Payroll A/c : ';
                } else {

                    accountType = 'Misc. Expense A/c : ';
                }

                html += '<option class="special_text" data-expense_ac_balance="' + expenseAccount.balance +
                    '" value="' + expenseAccount.id + '">' + accountType + expenseAccount.name +
                    '</option>';
            });

            html += '</select>';
            html +=
                '<p class="p-0 m-0"><strong>Curr. Balance :</strong> <span id="expense_ac_closing_balance"></span></p>';
            html += '</td>';

            html += '<td>';
            html +=
                '<input required type="number" name="amounts[]" step="any" class="form-control" id="amount" value="" placeholder="Amount">';
            html += '</td>';

            html += '<td>';
            html +=
                '<a href="#" class="action-btn c-delete" id="remove_btn"><span class="fas fa-trash px-2 py-3"></span></a>';
            html += '</td>';
            html += '</tr>';
            $('#description_body').append(html);

            calculateAmount();
            index++;
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save').click();
                return false;
            }
        }

        $(document).on('change', '#expense_account_id', function() {

            var expenseAcBalance = $(this).find('option:selected').data('expense_ac_balance');
            $(this).closest('tr').find('#expense_ac_closing_balance').html(expenseAcBalance);
        });

        $(document).on('change', '#account_id', function() {

            var accountBalance = $(this).find('option:selected').data('account_balance');
            $('#account_closing_balance').html(accountBalance);
        });

        $(document).on('change', '#tax_percent', function() {

            var tax_account_id = $(this).find('option:selected').data('tax_account_id');
            $('#tax_account_id').val(tax_account_id);
        });


        $(document).on('click', '#addNewExpenseAc', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#addQuickExpenseAcModal').html(data);
                    $('#addQuickExpenseAcModal').modal('show');
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
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
            format: _expectedDateFormat,
        });
    </script>
@endpush
