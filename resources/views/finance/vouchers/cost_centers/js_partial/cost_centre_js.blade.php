<script>
    var costCentreRowUniqueId = '';
    $(document).on('mousedown keypress', '#search_cost_centre', function(e) {

        var getUniqueId = $(this).closest('tr').find('#cost_centre_row_unique_id').val();
        costCentreRowUniqueId = getUniqueId;
        ul = document.getElementById('cost_centre_list');
        selectObjClassName = 'selected_cost_centre';

        if(e.which == 13) {

            $('.selected_cost_centre').click();
        }
    });

    $(document).on('focus', '#search_cost_centre', function(e) {

        var val = $(this).val();

        var getUniqueId = $(this).closest('tr').find('#cost_centre_row_unique_id').val();
        costCentreRowUniqueId = getUniqueId;
        ul = document.getElementById('cost_centre_list');
        selectObjClassName = 'selected_cost_centre';
    });

    $(document).on('mouseup', '#cost_centre_list a', function(e) {
        e.preventDefault();

        $('.select_cost_centre').removeClass('selected_cost_centre');
        $(this).addClass('selected_cost_centre');
        $(this).click();
    });

    $(document).on('input', '#search_cost_centre', function(e) {

        var keyword = $(this).val();
        var __keyword = keyword.replaceAll('/', '~');
        var onlyType = $(this).data('only_type');
        var tr = $(this).closest('tr');

        if (keyword == '') {

            tr.find('#cost_centre_id').val('');
            tr.find('#default_cost_centre_name').val('');
            tr.find('#search_cost_centre').val('');
        }

        delay(function() { searchCostCentre(__keyword, onlyType); }, 200);
    });

    $(document).on('focus', '#search_cost_centre', function(e) {

        var tr = $(this).closest('tr');
        var onlyType = $(this).data('only_type');
        var keyword = tr.find('#default_cost_centre_name').val();
        var __keyword = keyword.replaceAll('/', '~');
        delay(function() { searchCostCentre(__keyword, onlyType); }, 200);
    });

    function searchCostCentre(keyword, onlyType) {

        var __keyword = keyword ? keyword : 'NULL';

        var url = "{{ route('common.ajax.call.search.cost.centre', [':__keyword', ':onlyType']) }}";
        var route = url.replace(':__keyword', __keyword);
        route = route.replace(':onlyType', onlyType);

        $.ajax({
            url: route,
            dataType: 'json',
            success:function(cost_centres) {

                var length = cost_centres.length;
                $('.select_cost_centre').removeClass('selected_cost_centre');
                var li = '';

                $.each(cost_centres, function(key, cost_centre){

                    var categoryName = ' ('+cost_centre.category_name+')';

                    li += '<li>';
                    li += '<a href="#" class="select_cost_centre '+( key == 0 && length == 1 ? 'selected_cost_centre' : '' ) +'"  data-cost_centre_name="' + cost_centre.name + categoryName +'" data-default_cost_centre_name="'+cost_centre.name+'" data-cost_centre_id="' + cost_centre.id + '"> ' + cost_centre.name + categoryName + '</a>';
                    li +='</li>';
                });

                $('#cost_centre_list').html(li);
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Please check the connetion.');
                    return;
                }
            }
        });
    }

    $(document).on('click', '.selected_cost_centre', function(e) {
        e.preventDefault();

        var cost_centre_id = $(this).data('cost_centre_id');
        var cost_centre_name = $(this).data('cost_centre_name');
        var default_cost_centre_name = $(this).data('default_cost_centre_name');
        var cost_allocation_account_index = $('#cost_allocation_account_index').val();

        var tr = $('.cost_centre_row_unique_id-'+costCentreRowUniqueId).closest('tr');

        tr.find('#cost_centre_id').val(cost_centre_id);
        tr.find('#cost_centre_id').attr('name', 'cost_centre_ids['+cost_allocation_account_index+'][]');
        tr.find('#cost_centre_amount').attr('name', 'cost_centre_amounts['+cost_allocation_account_index+'][]');
        tr.find('#default_cost_centre_name').attr('name', 'default_cost_centre_names['+cost_allocation_account_index+'][]');
        tr.find('#cost_centre_name').val(cost_centre_name);
        tr.find('#default_cost_centre_name').val(default_cost_centre_name);
        tr.find('#search_cost_centre').val(default_cost_centre_name);

        var cost_centre_id = tr.find('#cost_centre_id').val();

        if (cost_centre_id == '') {

            return;
        }

        if(cost_centre_id){

            costCentreAmountInputDirection(tr);
        }

        calculateCostCentreAmount();
    });

    function costCentreAmountInputDirection(tr) {
        calculateCostCentreAmount();

        var nxtTr = tr.next();
        var cost_allocation_amount = $('#cost_allocation_amount').val() ? $('#cost_allocation_amount').val() : 0;
        var total_cost_centre_amount = $('#total_cost_centre_amount').val() ? $('#total_cost_centre_amount').val() : 0;

        var costAllocationAmount = parseFloat(cost_allocation_amount);
        var totalCostCentreAmount = parseFloat(total_cost_centre_amount);

        var currentCostCentreValue = tr.find('#cost_centre_amount').val() ? tr.find('#cost_centre_amount').val() : 0;
            __currentCostCentreValue = parseFloat(currentCostCentreValue);

        var remainingAmount = parseFloat(costAllocationAmount) - (parseFloat(totalCostCentreAmount) - parseFloat(__currentCostCentreValue));
        var __remainingAmount = parseFloat(remainingAmount) > 0 ? parseFloat(remainingAmount) : 0;

        if (nxtTr.length == 0) {

            tr.find('#cost_centre_amount').val(0)
            calculateCostCentreAmount();
            tr.find('#cost_centre_amount').val(parseFloat(__remainingAmount > 0 ? __remainingAmount : __currentCostCentreValue).toFixed(2)).show().focus().select();
        } else {

            tr.find('#cost_centre_amount').show().focus().select();
        }

        calculateCostCentreAmount();
    }

    function calculateCostCentreAmount() {

        var cost_centre_amount = document.querySelectorAll('#cost_centre_amount');
        total_cost_centreAmount = 0;

        cost_centre_amount.forEach(function(amount) {

            total_cost_centreAmount += parseFloat(amount.value ? amount.value : 0);
        });

        $('#show_total_cost_centre_amount').html(bdFormat(total_cost_centreAmount));
        $('#total_cost_centre_amount').val(parseFloat(total_cost_centreAmount));
    }

    $(document).on('input keypress', '#cost_centre_amount', function(e) {

        var val = $(this).val();

        var tr = $(this).closest('tr');
        var nxt = tr.next();
        calculateCostCentreAmount();
        if (e.keyCode == 13) {

            if (val == '' || val == 0) {

                return;
            }else {

                $('#cost_centre_list').empty();
                if (isLeaveToSaveButton() == true && nxt.length == 0) {

                    $('#cost_center_save_btn').focus();
                    return;
                }

                if (nxt.length == 0) {

                    addCostCentreNewRow();
                }else{

                    nxt.find('#search_cost_centre').focus().select();
                }
            }
        }
    });

    function addCostCentreNewRow() {

        var cost_allocation_account_main_group_number = $('#cost_allocation_account_main_group_number').val();
        var generate_unique_id = parseInt(Date.now() + Math.random());

        var html = '';
        html += '<tr class="removable">';

        html += '<td>';
        html += '<div class="row py-1">';

        html += '<div class="col-12">';
        html += '<input type="text" data-only_type="'+ (cost_allocation_account_main_group_number == 3 ? 'expense' : 'income') +'" class="form-control fw-bold" id="search_cost_centre" autocomplete="off">';
        html += '<input type="hidden" id="cost_centre_name" class="voidable">';
        html += '<input type="hidden" id="default_cost_centre_name" class="voidable">';
        html += '<input type="hidden" name="cost_centre_ids[]" id="cost_centre_id" class="voidable">';
        html += '<input type="hidden" class="cost_centre_row_unique_id-'+generate_unique_id+'" id="cost_centre_row_unique_id" value="'+generate_unique_id+'">';
        html += '<input type="hidden" id="main_group_number" value="">';
        html += '</div>';
        html += '</td>';

        html += '<td>';
        html += '<input type="number" step="any" name="cost_centre_amounts[]" class="form-control fw-bold spinner_hidden text-end" id="cost_centre_amount" value="0.00">';
        html += '</td>';

        html += '<td>';
        html += '<div class="row g-0">';
        html += '<div class="col-md-6">';
        html += '<a href="#" id="remove_cost_centre_btn" class="table_tr_remove_btn d-inline"><i class="fas fa-trash-alt text-danger mt-1"></i></a>';
        html += '</div>';

        html += '<div class="col-md-6">';
        html += '<a href="#" id="add_cost_centre_btn" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>';
        html += '</div>';
        html += '</div>';
        html += '</td>';
        html += '</tr>';

        $('#cost_centre_table_row_list').append(html);

        var tr = $('.cost_centre_row_unique_id-'+generate_unique_id).closest('tr');
        tr.find('#search_cost_centre').focus().select();
    }

    function isLeaveToSaveButton() {

        var cost_allocation_amount = $('#cost_allocation_amount').val() ? $('#cost_allocation_amount').val() : 0;
        var total_cost_centre_amount = $('#total_cost_centre_amount').val() ? $('#total_cost_centre_amount').val() : 0;

        if (parseFloat(total_cost_centre_amount) >= parseFloat(cost_allocation_amount)) {

            return true;
        }else{

            return false;
        }
    }

    $(document).on('click', '#remove_cost_centre_btn',function(e){
        e.preventDefault();

        var tr = $(this).closest('tr');
        previousTr = tr.prev();
        nxtTr = tr.next();
        tr.remove();

        if (nxtTr.length == 1) {

            nxtTr.find('#search_cost_centre').focus().select();
        }else if (previousTr.length == 1) {

            previousTr.find('#search_cost_centre').focus().select();
        }

        calculateCostCentreAmount();
    });

    $(document).on('click', '#add_cost_centre_btn',function(e){
        e.preventDefault();

        var tr = $(this).closest('tr');
        var cost_centre_id = tr.find('#cost_centre_id').val();
        var cost_centre_amount = tr.find('#cost_centre_amount').val();

        var nxt = tr.next();
        calculateCostCentreAmount();

        if (cost_centre_id == '' || ((cost_centre_amount == 0 || cost_centre_amount == ''))) {

            return;
        }else {

            if (isLeaveToSaveButton() == true) {

                $('#cost_center_save_btn').focus();
                return;
            }

            if (nxt.length == 0) {

                addCostCentreNewRow();
            }else{

                nxt.find('#search_cost_centre').focus().select();
            }
        }
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.cost_center_submit_button').prop('type', 'button');
    });

    var isAllowCostCentreSubmit = true;
    $(document).on('click', '.cost_center_submit_button',function () {

        if (isAllowCostCentreSubmit) {

            $(this).prop('type', 'submit');
        }else{

            $(this).prop('type', 'button');
        }
    });

    $(document).on('submit', '#select_cost_center_form', function(e){
        e.preventDefault();

        $('.cost_centre_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        var cost_allocation_account_index = $('#cost_allocation_account_index').val();

        isCostCentreAjaxIn = false;
        isAllowCostCentreSubmit = false;

        $.ajax({
            url : url,
            type : 'post',
            data : request,
            dataType : 'json',
            success:function(data) {

                isCostCentreAjaxIn = true;
                isAllowCostCentreSubmit = true;
                $('.cost_centre_loading_btn').hide();

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg);
                    return;
                }

                myArray[cost_allocation_account_index] = data[cost_allocation_account_index];

                var table = '<table class="w-100"><tbody>';
                myArray[cost_allocation_account_index].forEach(element => {

                    var tr = '<tr>';

                    tr += '<td class="w-60">';
                    tr += element.cost_centre_name;
                    tr += '<input type="hidden" name="cost_centre_ids['+cost_allocation_account_index+'][]" value="'+ element.cost_centre_id +'" class="voidable">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += ': '+bdFormat(element.cost_centre_amount);
                    tr += '<input type="hidden" name="cost_centre_amounts['+cost_allocation_account_index+'][]" value="'+ element.cost_centre_amount +'">';
                    tr += '</td>';

                    tr += '</tr>';

                    table += tr;
                });

                table += '</tbody></table>';

                var tr = $('.unique_id-'+uniqueId).closest('tr');

                tr.find('#cost_centre_table_row_list').empty();
                tr.find('.cost_centre_list_for_entry_table_area').html(table);

                var nxt = tr.next();

                $('#costCentreModal').modal('hide');
                var mode = $('#mode').val();

                if (mode == 2 && checkDoubleEntryIsBothSideEqual() == true && nxt.length == 0) {

                    $('#remarks').focus().select();
                    return;
                }

                if (nxt.length == 0) {

                    addNewRow();
                }else{

                    nxt.find('#search_account').focus().select();
                }

                return;
            }, error: function(err) {

                isCostCentreAjaxIn = true;
                isAllowCostCentreSubmit = true;
                $('.cost_centre_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if(err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                } else if(err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }
            }
        });

        if (isCostCentreAjaxIn == false) {

            isAllowCostCentreSubmit = true;
        }
    });

    function checkDoubleEntryIsBothSideEqual() {

        var debit_total = $('#debit_total').val() ? $('#debit_total').val() : 0;
        var credit_total = $('#credit_total').val() ? $('#credit_total').val() : 0;

        if (parseFloat(debit_total) == parseFloat(credit_total)) {

            return true;
        }else{

            return false;
        }
    }

    $(document).on('change', '#maintain_cost_centre', function(e) {

        var val = $(this).val();
        if (val == 0) {

            $('.cost_centre_list_for_entry_table_area').hide();
        }else{

            $('.cost_centre_list_for_entry_table_area').show();
        }
    });
</script>
