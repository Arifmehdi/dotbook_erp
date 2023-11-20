@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>@lang('menu.edit_income')</h6>
            <x-back-button />
        </div>
        <form id="edit_income_form" action="{{ route('income.update', $income->id) }}" enctype="multipart/form-data"
            method="POST">
            @csrf
            <section class="p-15">
                <div class="row mb-1">
                    <div class="col-12">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <label class=" col-4"><b>@lang('menu.voucher_no')</b></label>
                                            <div class="col-8">
                                                <input readonly type="text" name="voucher_no" id="voucher_no"
                                                    class="form-control" value="{{ $income->voucher_no }}"
                                                    placeholder="@lang('menu.voucher_no')">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <label class=" col-4"><b>@lang('menu.income') @lang('menu.date') </b> <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-8">
                                                <input required type="text" name="date"
                                                    class="form-control changeable"
                                                    value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($income->report_date)) }}"
                                                    id="datepicker">
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
                                <div class="heading_area mb-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="text-muted m-0 p-0 ps-1 float-start mt-1"><b>@lang('menu.descriptions')</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ route('income.account.quick.add.modal') }}"
                                                class="btn btn-sm btn-success float-end"
                                                id="addNewIncomeAc"><b>@lang('menu.add_new_incomes_ac')</b></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="income_description_table">
                                            <div class="table-responsive">
                                                <table class="table modal-table table-sm">
                                                    <tbody id="description_body">
                                                        @foreach ($income->incomeDescriptions as $description)
                                                            <tr>
                                                                <td id="index">
                                                                    <b><span
                                                                            class="serial">{{ $loop->index + 1 }}</span></b>
                                                                    <input class="index-{{ $loop->index + 1 }}"
                                                                        type="hidden" id="index">
                                                                </td>

                                                                <td>
                                                                    <select required name="income_account_ids[]"
                                                                        class="form-control income_account_id special_text"
                                                                        id="income_account_id">
                                                                        <option data-income_ac_balance="" value="">
                                                                            @lang('menu.select_incomes_ac')</option>
                                                                        @php
                                                                            $selectedAcBalance = '';
                                                                        @endphp
                                                                        @foreach ($incomeAccounts as $incomeAccount)
                                                                            @php
                                                                                $accountType = '';
                                                                                if ($incomeAccount->account_type == 24) {
                                                                                    $accountType = 'Direct Income : ';
                                                                                } elseif ($incomeAccount->account_type == 25) {
                                                                                    $accountType = 'Indirect Income : ';
                                                                                } else {
                                                                                    $accountType = 'Misc. Income A/c : ';
                                                                                }

                                                                                if ($description->income_account_id == $incomeAccount->id) {
                                                                                    $selectedAcBalance = $incomeAccount->balance;
                                                                                }
                                                                            @endphp
                                                                            <option class="special_text"
                                                                                {{ $description->income_account_id == $incomeAccount->id ? 'SELECTED' : '' }}
                                                                                data-income_ac_balance="{{ $incomeAccount->balance }}"
                                                                                value="{{ $incomeAccount->id }}">
                                                                                {{ $accountType . $incomeAccount->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <p><strong>@lang('menu.curr_balance') :</strong> <span
                                                                            id="income_ac_closing_balance">{{ $selectedAcBalance }}</span>
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
                                                                        <a id="addMore" href="#"
                                                                            class="btn btn-sm btn-primary py-1 px-2"><i
                                                                                class="fas fa-plus-square"></i></a>
                                                                    @else
                                                                        <a href="#"
                                                                            class="btn btn-sm btn-danger py-1 px-2"
                                                                            id="remove_btn"><span
                                                                                class="fas fa-trash"></span></a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="2" class="text-end">@lang('menu.total') :</th>
                                                            <th colspan="2" id="income_total">
                                                                {{ $income->total_amount }}</th>
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
                                            <label class=" col-2"><b>@lang('menu.income_note') </b></label>
                                            <div class="col-8">
                                                <textarea name="note" class="form-control ckEditor" cols="10" rows="3" placeholder="@lang('menu.income_note')">{{ $income->note }}</textarea>
                                                <input type="hidden" name="total_amount" id="total_amount"
                                                    value="{{ $income->total_amount }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('income.partials.incomePaymentEditSection')
            </section>
        </form>
    </div>

    <div class="modal fade" id="addQuickIncomeAcModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Set accounts in payment and payment edit form
        var incomeAccountsArr = [];

        function setIncomeAccounts() {
            $.ajax({
                url: "{{ route('income.accounts.by.ajax') }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(incomeAccounts) {

                    incomeAccountsArr = incomeAccounts;
                }
            });
        }
        setIncomeAccounts();

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
            $('#income_total').html(parseFloat(totalAmount).toFixed(2));
            $('#total_receivable').html(parseFloat(totalAmount).toFixed(2));
            var previouseReceived = $('#previous_received').val();
            var currentReceivable = parseFloat(totalAmount) - parseFloat(previouseReceived);
            $('#current_receivable').val(parseFloat(currentReceivable).toFixed(2));
            var receivedAmount = $('#received_amount').val() ? $('#received_amount').val() : 0;
            var due = parseFloat(currentReceivable) - parseFloat(receivedAmount);

            $('#due').val(parseFloat(due).toFixed(2));
        }

        $('#payment_method_id').on('change', function() {

            var account_id = $(this).find('option:selected').data('account_id');
            setMethodAccount(account_id);
        });

        $(document).on('input', '#amount', function() {

            calculateAmount();
        });

        $('#received_amount').on('input', function() {

            calculateAmount();
        });

        $(document).on('click', '#remove_btn', function(e) {
            e.preventDefault();

            $(this).closest('tr').remove();
            calculateAmount();
        });

        $('#edit_income_form').on('submit', function(e) {
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

                    $('.error').html('');
                    $('.loading_button').hide();
                    toastr.success(data);
                    window.location = "{{ route('income.index') }}";
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
                '<select required name="income_account_ids[]" class="form-control income_account_id special_text form-select" id="income_account_id">';
            html += '<option data-income_ac_balance="" value="">Select Income A/c</option>';

            $.each(incomeAccountsArr, function(key, incomeAccount) {

                var accountType = '';
                if (incomeAccount.account_type == 24) {

                    accountType = 'Direct Income : ';
                } else if (incomeAccount.account_type == 25) {

                    accountType = 'Indirect Income : ';
                } else {

                    accountType = 'Misc. Income A/c : ';
                }

                html += '<option class="special_text" data-income_ac_balance="' + incomeAccount.balance +
                    '" value="' + incomeAccount.id + '">' + accountType + incomeAccount.name + '</option>';
            });

            html += '</select>';
            html += '<p><strong>Curr. Balance :</strong> <span id="income_ac_closing_balance"></span></p>';
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

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save_and_print').click();
                return false;
            } else if (e.shiftKey && e.which == 13) {

                $('#save').click();
                return false;
            }
        }

        $('#payment_method_id').on('change', function() {

            var account_id = $(this).find('option:selected').data('account_id');
            setMethodAccount(account_id);
        });

        function setMethodAccount(account_id) {

            if (account_id) {

                $('#account_id').val(account_id);
            } else if (account_id === '') {

                $('#account_id option:first-child').prop("selected", true);
            }
        }

        setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));

        $(document).on('change', '#income_account_id', function() {

            var incomeAcBalance = $(this).find('option:selected').data('income_ac_balance');
            $(this).closest('tr').find('#income_ac_closing_balance').html(incomeAcBalance);
        });

        $(document).on('change', '#account_id', function() {

            var accountBalance = $(this).find('option:selected').data('account_balance');
            $('#account_closing_balance').html(accountBalance);
        });

        $(document).on('click', '#addNewIncomeAc', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#addQuickIncomeAcModal').html(data);
                    $('#addQuickIncomeAcModal').modal('show');
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
    </script>
@endpush
