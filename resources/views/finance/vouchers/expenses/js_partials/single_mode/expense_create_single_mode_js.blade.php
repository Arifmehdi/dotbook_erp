<script>
    var myArray = [];
    var rowIndex = 1;

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
        var isSingleModeFirstAcField = $(this).data('is_single_mode_first_ac_field');
        var tr = $(this).closest('tr');

        if (keyword == '') {

            tr.find('#account_id').val('');
            tr.find('#default_account_name').val('');
            tr.find('#search_account').val('');
            tr.find('#account_balance').html('');
        }

        delay(function() {
            searchAccount(__keyword, onlyType, isSingleModeFirstAcField);
        }, 200);
    });

    $(document).on('focus', '#search_account', function(e) {

        var tr = $(this).closest('tr');
        var onlyType = $(this).data('only_type');
        var isSingleModeFirstAcField = $(this).data('is_single_mode_first_ac_field');
        var keyword = tr.find('#default_account_name').val();
        var __keyword = keyword.replaceAll('/', '~');
        __keyword = __keyword.replaceAll('#', '^^^');
        delay(function() {
            searchAccount(__keyword, onlyType, isSingleModeFirstAcField);
        }, 200);
    });

    function searchAccount(keyword, onlyType, isSingleModeFirstAcField) {

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
                if (isSingleModeFirstAcField == 1) {

                    li = '';
                } else {

                    li =
                    '<li><a class="select_account text-danger" data-is_end_list="1" data-account_name="" data-default_account_name="" data-account_id="" data-main_group_number="" data-sub_sub_group_number="" href="#">List-End</a></li>';
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
                        '" data-account_id="' + account.id + '" data-main_group_number="' + account
                        .main_group_number + '" data-sub_sub_group_number="' + account
                        .sub_sub_group_number + '" href="#"> ' + account.name + accuntNumber +
                        groupName + '</a>';
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
        var main_group_number = $(this).data('main_group_number');
        var sub_sub_group_number = $(this).data('sub_sub_group_number');
        var is_end_list = $(this).data('is_end_list');

        var tr = $('.unique_id-' + uniqueId).closest('tr');
        var isSingleModeFirstAcField = tr.find('#search_account').data('is_single_mode_first_ac_field');

        var isMainGroupExpenseOrIncome = main_group_number == 3 || main_group_number == 4 ? true : false;

        if (isMainGroupExpenseOrIncome == false) {

            tr.find('.cost_centre_list_for_entry_table_area').empty();
        }

        global_account_id = account_id;
        global_account_name = account_name;
        global_default_account_name = default_account_name;

        if (is_end_list == 1) {

            tr.remove();
            $('#remarks').focus().select();
            return;
        }

        tr.find('#account_id').val(account_id);
        tr.find('#default_account_name').val(default_account_name);
        tr.find('#search_account').val(account_name);
        tr.find('#main_group_number').val(main_group_number);

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
    });

    $(document).on('input keypress', '#debit_amount', function(e) {

        var val = $(this).val();
        var tr = $(this).closest('tr');
        var nxt = tr.next();

        var main_group_number = tr.find('#main_group_number').val();
        var maintain_cost_centre = $('#maintain_cost_centre').val();

        calculateAmount();

        if (e.keyCode == 13) {

            if (val == '' || val == 0) {

                return;
            } else {

                if ((main_group_number == 3 || main_group_number == 4) && maintain_cost_centre == 1) {

                    getCostCentreModal(tr);
                    return;
                } else {

                    tr.find('.cost_centre_list_for_entry_table_area').empty();
                }

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

        var main_group_number = tr.find('#main_group_number').val();
        var maintain_cost_centre = $('#maintain_cost_centre').val();

        var nxt = tr.next();
        calculateAmount();

        if (account_id == '' || (debitAmount == 0 || debitAmount == '')) {

            return;
        } else {

            if ((main_group_number == 3 || main_group_number == 4) && maintain_cost_centre == 1) {

                getCostCentreModal(tr);
                return;
            } else {

                tr.find('.cost_centre_list_for_entry_table_area').empty();
            }

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
            '<input type="text" data-only_type="expense_account" data-is_single_mode_first_ac_field="0" class="form-control fw-bold" id="search_account" autocomplete="off">';
        html += '<input type="hidden" id="account_name">';
        html += '<input type="hidden" id="default_account_name">';
        html += '<input type="hidden" name="account_ids[]" id="account_id">';
        html += '<input type="hidden" name="user_ids[]" id="user_id">';
        html += '<input type="hidden" name="payment_method_ids[]" id="payment_method_id">';
        html += '<input type="hidden" name="transaction_nos[]" id="transaction_no">';
        html += '<input type="hidden" name="cheque_nos[]" id="cheque_no">';
        html += '<input type="hidden" name="cheque_serial_nos[]" id="cheque_serial_no">';
        html += '<input type="hidden" name="cheque_issue_dates[]" id="cheque_issue_date">';
        html += '<input type="hidden" name="indexes[]" id="index" value="' + (rowIndex++) + '">';
        html += '<input type="hidden" name="expense_description_ids[]" id="expense_description_id" value="">';
        html += '<input type="hidden" class="unique_id-' + generate_unique_id + '" id="unique_id" value="' +
            generate_unique_id + '">';
        html += '<input type="hidden" id="main_group_number" value="">';
        html += '<div class="cost_centre_list_for_entry_table_area"></div>';
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

    function getCostCentreModal(tr) {

        var account_id = tr.find('#account_id').val();
        var main_group_number = tr.find('#main_group_number').val();
        var account_name = tr.find('#search_account').val();
        var amount_type = tr.find('#amount_type').val();
        var debit_amount = tr.find('#debit_amount').val();
        var credit_amount = tr.find('#credit_amount').val();
        var index = tr.find('#index').val();

        var costAmount = parseFloat(debit_amount) + parseFloat(credit_amount);

        $('#cost_allocation_account_index').val(index);
        $('#cost_allocation_account_main_group_number').val(main_group_number);
        $('#cost_allocation_amount').val(costAmount);
        $('#show_cost_allocation_account').html(account_name);
        $('#show_cost_allocation_amount').html(bdFormat(costAmount) + ' ' + amount_type + '.');

        $('#cost_centre_table_row_list').empty();

        var costCentreTr = '';
        if (myArray[index] != undefined) {

            var __index = 0;
            myArray[index].forEach(element => {

                costCentreTr += '<tr>';
                costCentreTr += '<td>';
                costCentreTr += '<div class="row py-1">';
                costCentreTr += '<div class="col-12">';
                costCentreTr += '<input type="text" data-only_type="' + (main_group_number == 3 ? 'expense' :
                        'income') + '" class="form-control fw-bold" id="search_cost_centre" value="' + element
                    .cost_centre_name + '" autocomplete="off">';
                costCentreTr += '<input type="hidden" class="voidable" id="cost_centre_name">';
                costCentreTr +=
                    '<input type="hidden" class="voidable" id="default_cost_centre_name" name="default_cost_centre_names[' +
                    index + '][]" value="' + element.cost_centre_name + '">';
                costCentreTr += '<input type="hidden" name="cost_centre_ids[' + index +
                    '][]" class="voidable" id="cost_centre_id" value="' + element.cost_centre_id + '">';
                var generate_cost_centre_unique_id = parseInt(Date.now() + Math.random() + __index);

                costCentreTr += '<input type="hidden" class="cost_centre_row_unique_id-' +
                    generate_cost_centre_unique_id + '" id="cost_centre_row_unique_id" value="' +
                    generate_cost_centre_unique_id + '">';
                costCentreTr += '</div>';
                costCentreTr += '</div>';
                costCentreTr += '</td>';

                costCentreTr += '<td>';
                costCentreTr += '<input type="number" step="any" name="cost_centre_amounts[' + index +
                    '][]" class="form-control fw-bold spinner_hidden text-end" id="cost_centre_amount" value="' +
                    element.cost_centre_amount + '">';
                costCentreTr += '</td>';

                costCentreTr += '<td>';
                costCentreTr += '<div class="row g-0">';
                costCentreTr += '<div class="col-md-6">';

                if (__index == 0) {

                    costCentreTr +=
                        '<a href="#" onclick="return false;" tabindex="-1" class="d-inline"><i class="fas fa-trash-alt text-secondary mt-1"></i></a>';
                } else {

                    costCentreTr +=
                        '<a href="#" id="remove_cost_centre_btn" class="table_tr_remove_btn d-inline"><i class="fas fa-trash-alt text-danger mt-1"></i></a>';
                }

                costCentreTr += '</div>';

                costCentreTr += '<div class="col-md-6">';
                costCentreTr +=
                    '<a href="#" id="add_cost_centre_btn" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>';
                costCentreTr += '</div>';
                costCentreTr += '</div>';
                costCentreTr += '</td>';
                costCentreTr += '</tr>';
                __index++;
            });

            $('#total_cost_centre_amount').val(costAmount);
            $('#show_total_cost_centre_amount').html(bdFormat(costAmount));
        } else {

            costCentreTr += '<tr>';
            costCentreTr += '<td>';
            costCentreTr += '<div class="row py-1">';
            costCentreTr += '<div class="col-12">';
            costCentreTr += '<input type="text" data-only_type="' + (main_group_number == 3 ? 'expense' : 'income') +
                '" class="form-control fw-bold" id="search_cost_centre" autocomplete="off">';
            costCentreTr += '<input type="hidden" class="voidable" id="cost_centre_name">';
            costCentreTr +=
                '<input type="hidden" class="voidable" id="default_cost_centre_name" name="default_cost_centre_names[]">';
            costCentreTr += '<input type="hidden" name="cost_centre_ids[]" class="voidable" id="cost_centre_id">';

            var generate_cost_centre_unique_id = parseInt(Date.now() + Math.random());
            costCentreTr += '<input type="hidden" class="cost_centre_row_unique_id-' + generate_cost_centre_unique_id +
                '" id="cost_centre_row_unique_id" value="' + generate_cost_centre_unique_id + '">';
            costCentreTr += '</div>';
            costCentreTr += '</div>';
            costCentreTr += '</td>';

            costCentreTr += '<td>';
            costCentreTr +=
                '<input type="number" step="any" name="cost_centre_amounts[]" class="form-control fw-bold spinner_hidden text-end" id="cost_centre_amount">';
            costCentreTr += '</td>';

            costCentreTr += '<td>';
            costCentreTr += '<div class="row g-0">';
            costCentreTr += '<div class="col-md-6">';
            costCentreTr +=
                '<a href="#" onclick="return false;" tabindex="-1" class="d-inline"><i class="fas fa-trash-alt text-secondary mt-1"></i></a>';
            costCentreTr += '</div>';

            costCentreTr += '<div class="col-md-6">';
            costCentreTr +=
                '<a href="#" id="add_cost_centre_btn" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>';
            costCentreTr += '</div>';
            costCentreTr += '</div>';
            costCentreTr += '</td>';
            costCentreTr += '</tr>';

            $('#total_cost_centre_amount').val(0);
            $('#show_total_cost_centre_amount').html(bdFormat(0));
        }

        $('#cost_centre_table_row_list').html(costCentreTr);
        $('#costCentreModal').modal('show');

        setTimeout(function() {

            $('#search_cost_centre').focus().select();
        }, 500);
    }
</script>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.expense_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.expense_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_expense_single_entry_form').on('submit', function(e) {
        e.preventDefault();

        $('.expense_loading_btn').show();
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
                $('.expense_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                afterCreateSale();
                toastr.success('Expense is created successfully');
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.expense_loading_btn').hide();
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

            if ($(this).attr('id') == 'maintain_cost_centre') {

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

        $('.error').html('');
        $('.loading_button').hide();
        $('.voidable').val('');
        $('.voidable').html('');
        $('#add_expense_single_entry_form')[0].reset();
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
