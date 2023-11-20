<script>
    // var myArray = new Array(2).fill(null).map(arr => new Array(2).fill(null));
    var myArray = [];
    var rowIndex = 1;
    $('#selected_user_id').select2();
    var ul = '';
    var selectObjClassName = '';
    var uniqueId = '';
    var mainGroupNumber = '';

    $(document).on('keypress', '#search_account', function(e) {

        var getUniqueId = $(this).closest('tr').find('#unique_id').val();
        uniqueId = getUniqueId;
        ul = document.getElementById('account_list');
        selectObjClassName = 'selected_account';

        if (e.which == 13) {

            $('.selected_account').click();
        }
    });

    $(document).on('mousedown', '#account_list a', function(e) {
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

                $.each(accounts, function(key, account) {

                    var groupName = ' (' + account.group_name + ')';
                    var accuntNumber = account.account_number != null ? ' - A/c No.: ' + account
                        .account_number : '';

                    li += '<li>';
                    li += '<a class="select_account ' + (key == 0 && length == 1 ?
                            'selected_account' : '') + '" data-is_customer="' + (account
                            .customer_id != null ? 1 : 0) + '" data-account_name="' + account.name +
                        accuntNumber + '" data-default_account_name="' + account.name +
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
    var global_main_group_number = '';

    $(document).on('keypress', '#amount_type', function(e) {

        var val = $(this).val();

        // $(this).val(val[0].toUpperCase() + (val.slice(1) ? val.slice(1).toLowerCase() : ''));
        $(this).val(val ? val[0].toUpperCase() + val.slice(1) : '');

        var tr = $(this).closest('tr');

        if (e.keyCode == 13) {

            if (val && (val == 'Cr' || val == 'Dr')) {

                tr.find('#search_account').focus().select();
            } else {

                $(this).focus().select();
            }
        }
    });

    $(document).on('click', '.selected_account', function(e) {
        e.preventDefault();

        var tr = $(this).closest('tr');
        var account_id = $(this).data('account_id');
        var is_customer = $(this).data('is_customer');
        var account_name = $(this).data('account_name');
        var default_account_name = $(this).data('default_account_name');
        var main_group_number = $(this).data('main_group_number');
        var sub_sub_group_number = $(this).data('sub_sub_group_number');

        var tr = $('.unique_id-' + uniqueId).closest('tr');

        global_account_id = account_id;
        global_account_name = account_name;
        global_default_account_name = default_account_name;
        global_main_group_number = main_group_number;

        var isMainGroupExpenseOrIncome = main_group_number == 3 || main_group_number == 4 ? true : false;

        if (isMainGroupExpenseOrIncome == false) {

            tr.find('.cost_centre_list_for_entry_table_area').empty();
        }

        if (is_customer == 0) {

            tr.find('#account_id').val(account_id);
            tr.find('#default_account_name').val(default_account_name);
            tr.find('#search_account').val(account_name);
            tr.find('#main_group_number').val(main_group_number);

            getAccountClosingBalance(account_id, uniqueId);
            var payment_method_id = tr.find('#payment_method_id').val();
            var transaction_no = tr.find('#transaction_no').val();
            var cheque_no = tr.find('#cheque_no').val();
            var cheque_serial_no = tr.find('#cheque_serial_no').val();
            var cheque_issue_date = tr.find('#cheque_issue_date').val();
            var remarkable_note = tr.find('#remarkable_note').val();
            var is_transaction_details = $('#is_transaction_details').val();

            if ((sub_sub_group_number == 1 || sub_sub_group_number == 11) && is_transaction_details == 1) {

                $('#trans_payment_method_id').val(payment_method_id);
                $('#trans_transaction_no').val(transaction_no);
                $('#trans_cheque_no').val(cheque_no);
                $('#trans_cheque_serial_no').val(cheque_serial_no);
                $('#trans_cheque_issue_date').val(cheque_issue_date);
                $('#trans_remarkable_note').val(remarkable_note);

                $('#addTransactionDetailsModal').modal('show');

                setTimeout(function() {

                    $('#trans_payment_method_id').focus();
                }, 500);

                return;
            } else {

                tr.find('#payment_method_id').val('');
                tr.find('#transaction_no').val('');
                tr.find('#cheque_no').val('');
                tr.find('#cheque_serial_no').val('');
                tr.find('#cheque_issue_date').val('');
                tr.find('#remarkable_note').val('');
            }
        } else {

            account_id = global_account_id;
            account_name = global_account_name;
            default_account_name = global_default_account_name;

            var user_count = $('#user_count').val('user_count');
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
            }

            return;
        }

        var account_id = tr.find('#account_id').val();

        if (account_id == '') {

            return;
        }

        if (account_id) {

            amountInputDirection(tr);
        }

        calculateAmount();
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

                if (isLeaveTheRemarks() == true && nxt.length == 0) {

                    $('#remarks').focus().select();
                    return;
                }

                if (nxt.length == 0) {

                    addNewRow();
                } else {

                    nxt.find('#amount_type').focus().select();
                }
            }
        }
    });

    $(document).on('input keypress', '#credit_amount', function(e) {

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

                if (isLeaveTheRemarks() == true && nxt.length == 0) {

                    $('#remarks').focus().select();
                    return;
                }

                if (nxt.length == 0) {

                    addNewRow();
                } else {

                    nxt.find('#amount_type').focus().select();
                }
            }
        }
    });

    $(document).on('click', '#add_entry_btn', function(e) {
        e.preventDefault();

        var tr = $(this).closest('tr');
        var account_id = tr.find('#account_id').val();
        var creditAmount = tr.find('#credit_amount').val();
        var debitAmount = tr.find('#debit_amount').val();

        var main_group_number = tr.find('#main_group_number').val();
        var maintain_cost_centre = $('#maintain_cost_centre').val();

        var nxt = tr.next();
        calculateAmount();

        if (account_id == '' || ((creditAmount == 0 || creditAmount == '') && (debitAmount == 0 ||
                debitAmount == ''))) {

            return;
        } else {

            if ((main_group_number == 3 || main_group_number == 4) && maintain_cost_centre == 1) {

                getCostCentreModal(tr);
                return;
            } else {

                tr.find('.cost_centre_list_for_entry_table_area').empty();
            }

            if (isLeaveTheRemarks() == true) {

                $('#remarks').focus().select();
                return;
            }

            if (nxt.length == 0) {

                addNewRow();
            } else {

                nxt.find('#amount_type').focus().select();
            }
        }
    });

    $(document).on('keypress', '.trans_input', function(e) {

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

    $(document).on('keypress', '#trans_remarkable_note', function(e) {

        if (e.keyCode == 13) {

            var tr = $('.unique_id-' + uniqueId).closest('tr');
            var trans_payment_method_id = $('#trans_payment_method_id').val();
            var trans_transaction_no = $('#trans_transaction_no').val();
            var trans_cheque_no = $('#trans_cheque_no').val();
            var trans_cheque_serial_no = $('#trans_cheque_serial_no').val();
            var trans_cheque_issue_date = $('#trans_cheque_issue_date').val();
            var trans_remarkable_note = $('#trans_remarkable_note').val();

            tr.find('#payment_method_id').val(trans_payment_method_id);
            tr.find('#transaction_no').val(trans_transaction_no);
            tr.find('#cheque_no').val(trans_cheque_no);
            tr.find('#cheque_serial_no').val(trans_cheque_serial_no);
            tr.find('#cheque_issue_date').val(trans_cheque_issue_date);
            tr.find('#remarkable_note').val(trans_remarkable_note);
            $('#addTransactionDetailsModal').modal('hide');

            amountInputDirection(tr);
        }
    });

    function addNewRow() {

        var generate_unique_id = parseInt(Date.now() + Math.random());

        var html = '';
        html += '<tr class="removable">';

        html += '<td>';
        html += '<div class="row py-1">';

        html += '<div class="col-2">';
        html +=
            '<input type="text" name="amount_types[]" maxlength="2" id="amount_type" list="type_list" class="form-control fw-bold">';
        html += '<datalist id="type_list">';
        html += '<option value="Dr">Dr</option>';
        html += '<option value="Cr">Cr</option>';
        html += '</datalist>';
        html += '</div>';

        html += '<div class="col-6">';
        html +=
            '<input type="text" data-only_type="all" id="search_account" class="form-control fw-bold" autocomplete="off">';
        html += '<input type="hidden" id="account_name">';
        html += '<input type="hidden" id="default_account_name">';
        html += '<input type="hidden" name="account_ids[]" id="account_id">';
        html += '<input type="hidden" name="user_ids[]" id="user_id">';
        html += '<input type="hidden" name="payment_method_ids[]" id="payment_method_id">';
        html += '<input type="hidden" name="transaction_nos[]" id="transaction_no">';
        html += '<input type="hidden" name="cheque_nos[]" id="cheque_no">';
        html += '<input type="hidden" name="cheque_serial_nos[]" id="cheque_serial_no">';
        html += '<input type="hidden" name="cheque_issue_dates[]" id="cheque_issue_date">';
        html += '<input type="hidden" name="remarkable_notes[]" id="remarkable_note">';
        html += '<input type="hidden" name="journal_entry_ids[]" id="journal_entry_id" value="">';
        html += '<input type="hidden" name="indexes[]" id="index" value="' + (rowIndex++) + '">';
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
        html += '<p class="m-0 p-0 fw-bold" id="show_debit_amount"></p>';
        html +=
            '<input type="number" step="any" name="debit_amounts[]" class="form-control fw-bold display-none spinner_hidden text-end" id="debit_amount" value="0.00">';
        html += '</td>';

        html += '<td>';
        html += '<p class="m-0 p-0 fw-bold" id="show_credit_amount"></p>';
        html +=
            '<input type="number" step="any" name="credit_amounts[]" class="form-control fw-bold display-none spinner_hidden text-end" id="credit_amount" value="0.00">';
        html += '</td>';

        html += '<td>';
        html += '<div class="row g-0">';
        html += '<div class="col-md-6">';
        html +=
            '<a href="#" id="remove_entry_btn" class="table_tr_remove_btn d-inline"><i class="fas fa-trash-alt text-danger mt-1"></i></a>';
        html += '</div>';

        html += '<div class="col-md-6">';
        html +=
            '<a href="#" id="add_entry_btn" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>';
        html += '</div>';
        html += '</div>';
        html += '</td>';
        html += '</tr>';

        $('#journal_account_list').append(html);

        var tr = $('.unique_id-' + generate_unique_id).closest('tr');

        previousTr = tr.prev();
        var previousAmountType = previousTr.find('#amount_type').val();
        tr.find('#amount_type').val(previousAmountType).focus().select();
    }

    function calculateAmount() {

        var debit_amounts = document.querySelectorAll('#debit_amount');
        totalDebitAmount = 0;

        debit_amounts.forEach(function(amount) {

            totalDebitAmount += parseFloat(amount.value ? amount.value : 0);
        });

        $('#show_debit_total').html(bdFormat(totalDebitAmount));
        $('#debit_total').val(parseFloat(totalDebitAmount));

        var credit_amounts = document.querySelectorAll('#credit_amount');
        totalCreditAmount = 0;

        credit_amounts.forEach(function(amount) {

            totalCreditAmount += parseFloat(amount.value ? amount.value : 0);
        });

        $('#show_credit_total').html(bdFormat(totalCreditAmount));
        $('#credit_total').val(parseFloat(totalCreditAmount));
    }

    function amountInputDirection(tr) {
        calculateAmount();

        var nxtTr = tr.next();

        var amount_type = tr.find('#amount_type').val();
        var currentDebitAmount = tr.find('#debit_amount').val();
        var debit_total = $('#debit_total').val() ? $('#debit_total').val() : 0;
        var credit_total = $('#credit_total').val() ? $('#credit_total').val() : 0;

        if (amount_type == 'Cr') {

            var currentCreditValue = tr.find('#credit_amount').val() ? tr.find('#credit_amount').val() : 0;
            __currentCreditValue = parseFloat(currentCreditValue);

            var remainingBalance = parseFloat(debit_total) - (parseFloat(credit_total) - __currentCreditValue);
            var __remainingBalance = parseFloat(remainingBalance) > 0 ? parseFloat(remainingBalance) : 0;

            if (nxtTr.length == 0) {

                tr.find('#credit_amount').val(parseFloat(__remainingBalance > 0 ? __remainingBalance :
                    __currentCreditValue)).show().focus().select();
            } else {

                tr.find('#credit_amount').show().focus().select();
            }

            tr.find('#show_debit_amount').html('');
            tr.find('#debit_amount').val(0).hide();
        } else if (amount_type == 'Dr') {

            var currentDebitValue = tr.find('#debit_amount').val() ? tr.find('#debit_amount').val() : 0;
            __currentDebitValue = parseFloat(currentDebitValue);

            var remainingBalance = parseFloat(credit_total) - (parseFloat(debit_total) - __currentDebitValue);
            var __remainingBalance = parseFloat(remainingBalance) > 0 ? parseFloat(remainingBalance) : 0;

            // tr.find('#debit_amount').val(parseFloat(__remainingBalance)).show().focus().select();
            if (nxtTr.length == 0) {

                tr.find('#debit_amount').val(parseFloat(__remainingBalance > 0 ? __remainingBalance :
                    __currentDebitValue)).show().focus().select();
            } else {

                tr.find('#debit_amount').show().focus().select();
            }

            tr.find('#show_credit_amount').html('');
            tr.find('#credit_amount').val(0).hide();
        }

        calculateAmount();
    }

    function isLeaveTheRemarks() {

        var debit_total = $('#debit_total').val() ? $('#debit_total').val() : 0;
        var credit_total = $('#credit_total').val() ? $('#credit_total').val() : 0;

        if (parseFloat(debit_total) == parseFloat(credit_total)) {

            return true;
        } else {

            return false;
        }
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

        // $('#select_user_form').submit();
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

    function getCustomerClosingBalanceUserWise(user_id, account_id, user_name) {

        var filterObj = {
            user_id: user_id,
            from_date: null,
            to_date: null,
        };

        var url = "{{ route('vouchers.journals.user.wise.customer.closing.balance', ':account_id') }}";
        var route = url.replace(':account_id', account_id);

        $.ajax({
            url: route,
            type: 'get',
            data: filterObj,
            success: function(data) {

                var tr = $('.unique_id-' + uniqueId).closest('tr');

                tr.find('#account_balance').html(data['closing_balance_string']);

                tr.find('#account_id').val(global_account_id);
                tr.find('#account_name').val(global_account_name + ' - SR ' + user_name);
                tr.find('#default_account_name').val(global_default_account_name);
                tr.find('#main_group_number').val(global_main_group_number);
                tr.find('#search_account').val(global_account_name + ' - SR ' + user_name);
                tr.find('#user_id').val(user_id);

                tr.find('#credit_amount').focus().select();
                amountInputDirection(tr);
                $('#selectUserModal').modal('hide');
            }
        });
    }

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

        $('.submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_journal_form').on('submit', function(e) {
        e.preventDefault();

        $('.journal_loading_btn').show();
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
                $('.journal_loading_btn').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                afterCreateSale();
                toastr.success(data);
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.journal_loading_btn').hide();
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
        $('.removable').remove();
        $('#add_journal_form')[0].reset();

        $('#show_debit_total').html(parseFloat(0).toFixed(2));
        $('#show_credit_total').html(parseFloat(0).toFixed(2));
        $('#debit_amount').val(parseFloat(0).toFixed(2));
        $('#credit_amount').val(parseFloat(0).toFixed(2));
        $('#debit_total').val(parseFloat(0).toFixed(2));
        $('#credit_total').val(parseFloat(0).toFixed(2));
        $('#date').focus().select();

        $('.cost_centre_list_for_entry_table_area').empty();
        myArray = [];
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

    $('#date').focus().select();
</script>
