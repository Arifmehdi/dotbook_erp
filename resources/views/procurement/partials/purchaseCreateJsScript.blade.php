<script>
    $('.collapse_table').on('click', function () {

        $('.last_p_product_list').toggle(500);
    });

    $('#addSupplier').on('click', function () {

        $.get("{{ route('contacts.supplier.create.basic.modal') }}", function(data) {
            $('#add_supplier_basic_modal').html(data);
            $('#add_supplier_basic_modal').modal('show');
        });
    });

    function calculateTotalAmount() {

        var quantities = document.querySelectorAll('#showing_quantity');
        var linetotals = document.querySelectorAll('#linetotal');
        var total_item = 0;
        var total_qty = 0;

        quantities.forEach(function(qty){

            total_item += 1;
            total_qty += parseFloat(qty.value)
        });

        $('#total_qty').val(parseFloat(total_qty));
        $('#total_item').val(parseFloat(total_item));

        //Update Net Total Amount
        var netTotalAmount = 0;
        linetotals.forEach(function(linetotal){

            netTotalAmount += parseFloat(linetotal.value);
        });

        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

        // Order discount calculate
        var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0;
        var orderDiscountType = $('#order_discount_type').val();

        var orderDiscountAmount = 0;
        if (orderDiscountType == 1) {

            orderDiscountAmount = parseFloat(orderDiscount).toFixed(2);
            $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        } else {

            orderDiscountAmount = parseFloat(netTotalAmount) / 100 * parseFloat(orderDiscount);
            $('#order_discount_amount').val(parseFloat(orderDiscountAmount).toFixed(2));
        }
        // Order discount calculate End

        // ait deduction calculate
        var aitDeduction = $('#ait_deduction').val() ? $('#ait_deduction').val() : 0;
        var aitDeductionType = $('#ait_deduction_type').val();

        var aitDeductionAmount = 0;
        if (aitDeductionType == 1) {

            aitDeductionAmount = parseFloat(aitDeduction).toFixed(2);
            $('#ait_deduction_amount').val(parseFloat(aitDeduction).toFixed(2));
        } else {

            aitDeductionAmount = parseFloat(netTotalAmount) / 100 * parseFloat(aitDeduction);
            $('#ait_deduction_amount').val(parseFloat(aitDeductionAmount).toFixed(2));
        }
        // ait deduction calculate end

        var netTotalWithDiscount = parseFloat(netTotalAmount) - orderDiscountAmount;

        var purchaseTaxPercent = $('#purchase_tax_ac_id').find('option:selected').data('purchase_tax_percent') ? $('#purchase_tax_ac_id').find('option:selected').data('purchase_tax_percent') : 0;

        var purchaseTaxAmount = parseFloat(netTotalWithDiscount) / 100 * parseFloat(purchaseTaxPercent);

        $('#purchase_tax_amount').val(parseFloat(purchaseTaxAmount).toFixed(2));

        var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;

        var totalInvoiceAmount = parseFloat(netTotalWithDiscount)
                + parseFloat(purchaseTaxAmount)
                + parseFloat(shipmentCharge)
                - parseFloat(aitDeductionAmount);

        // Calc Additional expense with item cost
        var total_additional_expense = $('#total_additional_expense').val() ? $('#total_additional_expense').val() : 0;
        var total_expense_with_item = parseFloat(totalInvoiceAmount) + parseFloat(total_additional_expense);
        $('#total_expense_with_item').val(parseFloat(total_expense_with_item).toFixed(2));
        // Calc Additional expense with item cost End

        $('#total_invoice_amount').val(parseFloat(totalInvoiceAmount).toFixed(2));

        var debit_amount = $('#debit_amount').val() ? $('#debit_amount').val() : 0;
        var credit_amount = $('#credit_amount').val() ? $('#credit_amount').val() : 0;

        var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;

        var currentDebitAmount = parseFloat(debit_amount) + parseFloat(payingAmount);
        var currentCreditAmount = parseFloat(credit_amount) + parseFloat(totalInvoiceAmount);

        var currentBalance = bdFormat(0) + ' Dr.';
        if (currentDebitAmount > currentCreditAmount) {

            var sum = currentDebitAmount - currentCreditAmount;
            currentBalance = bdFormat(sum) + ' Dr.';
        }else if (currentCreditAmount > currentDebitAmount) {

            var sum = currentCreditAmount - currentDebitAmount;
            currentBalance = bdFormat(sum) + ' Cr.';
        }

        $('#current_balance').val(currentBalance);
    }

    function calculateAdditionalExpense() {
        var labour_cost = $('#labour_cost').val() ? $('#labour_cost').val() : 0;
        var transport_cost = $('#transport_cost').val() ? $('#transport_cost').val() : 0;
        var scale_charge = $('#scale_charge').val() ? $('#scale_charge').val() : 0;
        var others = $('#others').val() ? $('#others').val() : 0;
        var total_additional_expense = parseFloat(scale_charge) + parseFloat(transport_cost) + parseFloat(labour_cost) + parseFloat(others);
        $('#total_additional_expense').val(parseFloat(total_additional_expense).toFixed(2));

        var total_purchase_amount = $('#total_purchase_amount').val() ? $('#total_purchase_amount').val() : 0;
        var total_expense_with_item = parseFloat(total_additional_expense) + parseFloat(total_purchase_amount);
        $('#total_expense_with_item').val(parseFloat(total_expense_with_item).toFixed(2));
    }

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input', '.add_ex', function(){

        calculateAdditionalExpense();
    });

    var delay = (function() {

        var timer = 0;
        return function(callback, ms) {

            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#search_product').on('input', function(e) {

        $('.variant_list_area').empty();
        $('.select_area').hide();
        var keyWord = $(this).val();
        var __keyWord = keyWord.replaceAll('/', '~');
        delay(function() { searchProduct(__keyWord); }, 200); //sendAjaxical is the name of remote-command
    });

    function searchProduct(keyWord) {

        $('.variant_list_area').empty();
        $('.select_area').hide();

        var isShowNotForSaleItem = 1;
        var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
        var route = url.replace(':keyWord', keyWord);
            route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

        $.ajax({
            url: route,
            dataType: 'json',
            success:function(product){

                if (!$.isEmptyObject(product.errorMsg)) {

                    toastr.error(product.errorMsg);
                    $('#search_product').val('');
                    return;
                }

                if(
                    !$.isEmptyObject(product.product) ||
                    !$.isEmptyObject(product.variant_product) ||
                    !$.isEmptyObject(product.namedProducts)
                ){

                    $('#search_product').addClass('is-valid');
                    if(!$.isEmptyObject(product.product)){

                        var product = product.product;

                        if(product.variants.length == 0) {

                            $('.select_area').hide();

                            var name = product.name.length > 35 ? product.name.substring(0, 35)+'...' : product.name;

                            var unique_id = product.id+'noid';

                            $('#search_product').val(name);
                            $('#e_unique_id').val(unique_id);
                            $('#e_item_name').val(name);
                            $('#e_product_id').val(product.id);
                            $('#e_variant_id').val('noid');
                            $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_showing_unit_cost_exc_tax').val(product.product_cost);
                            $('#e_showing_discount').val(parseFloat(0).toFixed(2));
                            $('#e_discount_type').val(1);
                            $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
                            $('#e_tax_type').val(parseFloat(product.tax_type).toFixed(2));
                            $('#e_tax_ac_id').val(product.tax_ac_id);
                            $('#e_profit_margin').val(parseFloat(product.profit).toFixed(2));
                            $('#e_showing_selling_price').val(parseFloat(product.product_price).toFixed(2));
                            $('#e_lot_number').val('');
                            $('#e_base_unit_cost_exc_tax').val(parseFloat(product.product_cost).toFixed(2));

                            $('#e_unit_id').empty();
                            $('#e_unit_id').append('<option value="'+product.unit.id+'" data-is_base_unit="1" data-unit_name="'+product.unit.name+'" data-base_unit_multiplier="1">'+product.unit.name+'</option>');

                            itemUnitsArray[product.id] = [
                                {
                                    'unit_id' : product.unit.id,
                                    'unit_name' : product.unit.name,
                                    'unit_code_name' : product.unit.code_name,
                                    'base_unit_multiplier' : 1,
                                    'multiplier_details' : '',
                                    'is_base_unit' : 1,
                                }
                            ];

                            if (product.unit.child_units.length > 0) {

                                product.unit.child_units.forEach(function(unit){

                                    var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + unit.name+')';

                                    itemUnitsArray[product.id].push({
                                        'unit_id' : unit.id,
                                        'unit_name' : unit.name,
                                        'unit_code_name' : unit.code_name,
                                        'base_unit_multiplier' : unit.base_unit_multiplier,
                                        'multiplier_details' : multiplierDetails,
                                        'is_base_unit' : 1,
                                    });

                                    $('#e_unit_id').append('<option value="'+unit.id+'" data-is_base_unit="0" data-unit_name="'+unit.name+'" data-base_unit_multiplier="'+unit.base_unit_multiplier+'">'+unit.name+multiplierDetails+'</option>');
                                });
                            }

                            calculateEditOrAddAmount();
                            $('#add_item').html('Add');
                        } else {

                            var li = "";
                            $.each(product.variants, function(key, variant){

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                li += '<li>';
                                li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-v_name="'+variant.variant_name+'" data-p_tax_ac_id="'+(product.tax_ac_id != null ? product.tax_ac_id : '')+'" data-tax_type="'+product.tax_type+'" data-p_code="'+variant.variant_code+'" data-p_cost_exc_tax="'+variant.variant_cost+'" data-p_profit="'+variant.variant_profit+'" data-p_price="'+variant.variant_price+'" href="#"><img style="width:20px; height:20px;" src="'+product.thumbnail_photo+'"> '+ product.name +'</a>';
                                li +='</li>';
                            });

                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    }else if(!$.isEmptyObject(product.namedProducts)){

                        if(product.namedProducts.length > 0) {

                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function (key, product) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                var tax_percent = product.tax_ac_id != null ? product.tax_percent : 0.00;

                                if (product.is_variant == 1) {

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="'+product.id+'" data-v_id="'+product.variant_id+'" data-p_name="'+product.name+'" data-v_name="'+product.variant_name+'" data-p_tax_ac_id="'+(product.tax_ac_id != null ? product.tax_ac_id : '')+'" data-tax_type="'+product.tax_type+'" data-v_code="'+product.variant_code+'" data-p_cost_exc_tax="'+product.variant_cost+'" data-p_profit="'+product.variant_profit+'" data-p_price="'+product.variant_price+'" href="#"><img style="width:20px; height:20px;" src="'+product.thumbnail_photo+'"> '+product.name+' - '+product.variant_name+'</a>';
                                    li +='</li>';
                                }else{

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-p_id="'+product.id+'" data-p_name="'+product.name+'" data-p_tax_ac_id="'+(product.tax_ac_id != null ? product.tax_ac_id : '')+'" data-tax_type="'+product.tax_type+'" data-p_code="'+product.product_code+'" data-p_cost_exc_tax="'+product.product_cost+'" data-p_profit="'+product.profit+'" data-p_price="'+product.product_price+'" href="#"><img style="width:20px; height:20px;" src="'+product.thumbnail_photo+'"> '+product.name+'</a>';
                                    li +='</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }else if(!$.isEmptyObject(product.variant_product)) {

                        $('.select_area').hide();

                        var variant_product = product.variant_product;

                        var name = variant_product.product.name.length > 35 ? product.name.substring(0, 35) + '...' : variant_product.product.name;

                        var unique_id = variant_product.product.id+variant_product.id;

                        $('#e_unique_id').val(unique_id);
                        $('#search_product').val(name +' - '+variant_product.variant_name);
                        $('#e_item_name').val(name+' - '+variant_product.variant_name);
                        $('#e_product_id').val(variant_product.product.id);
                        $('#e_variant_id').val(variant_product.id);
                        $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_showing_unit_cost_exc_tax').val(variant_product.variant_cost);
                        $('#e_discount_type').val(1);
                        $('#e_showing_discount').val(parseFloat(0).toFixed(2));
                        $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
                        $('#e_tax_type').val(parseFloat(variant_product.product.tax_type).toFixed(2));
                        $('#e_tax_ac_id').val(variant_product.product.tax_ac_id);
                        $('#e_profit_margin').val(parseFloat(variant_product.variant_profit).toFixed(2));
                        $('#e_showing_selling_price').val(parseFloat(variant_product.variant_price).toFixed(2));
                        $('#e_lot_number').val('');
                        $('#e_base_unit_cost_exc_tax').val(parseFloat(variant_product.variant_cost).toFixed(2));

                        $('#e_unit_id').empty();
                        $('#e_unit_id').append('<option value="'+variant.product.unit.id+'" data-is_base_unit="1" data-unit_name="'+variant.product.unit.name+'" data-base_unit_multiplier="1">'+variant.product.unit.name+'</option>');

                        itemUnitsArray[variant.product.id] = [
                            {
                                'unit_id' : variant.product.unit.id,
                                'unit_name' : variant.product.unit.name,
                                'unit_code_name' : variant.product.unit.code_name,
                                'base_unit_multiplier' : 1,
                                'multiplier_details' : '',
                                'is_base_unit' : 1,
                            }
                        ];

                        if (variant.product.unit.child_units.length > 0) {

                            variant.product.unit.child_units.forEach(function(unit){

                                var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + unit.name+')';

                                itemUnitsArray[variant.product.id].push({
                                    'unit_id' : unit.id,
                                    'unit_name' : unit.name,
                                    'unit_code_name' : unit.code_name,
                                    'base_unit_multiplier' : unit.base_unit_multiplier,
                                    'multiplier_details' : multiplierDetails,
                                    'is_base_unit' : 0,
                                });

                                $('#e_unit_id').append('<option value="'+unit.id+'" data-is_base_unit="0" data-unit_name="'+unit.name+'" data-base_unit_multiplier="'+unit.base_unit_multiplier+'">'+unit.name+multiplierDetails+'</option>');
                            });
                        }

                        calculateEditOrAddAmount();
                        $('#add_item').html('Add');
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                }
            }
        });
    }

    // select single product and add purchase table
    function selectProduct(e) {

        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var tax_ac_id = e.getAttribute('data-p_tax_ac_id');
        var tax_type = e.getAttribute('data-tax_type');
        var product_code = e.getAttribute('data-p_code');
        var product_cost = e.getAttribute('data-p_cost_exc_tax');
        var product_profit = e.getAttribute('data-p_profit');
        var product_price = e.getAttribute('data-p_price');

        var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
        var route = url.replace(':product_id', product_id);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(baseUnit) {

                var unique_id = product_id+variant_id;

                $('#e_unique_id').val(unique_id);
                $('#search_product').val(product_name+(variant_name ? ' - '+variant_name : '' ));
                $('#e_item_name').val(product_name+(variant_name ? ' - '+variant_name : '' ));
                $('#e_product_id').val(product_id);
                $('#e_variant_id').val(variant_id ? variant_id : 'noid');
                $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                $('#e_showing_unit_cost_exc_tax').val(parseFloat(product_cost).toFixed(2));
                $('#e_showing_discount').val(parseFloat(0).toFixed(2));
                $('#e_discount_type').val(1);
                $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
                $('#e_tax_type').val(tax_type);
                $('#e_tax_ac_id').val(tax_ac_id);
                $('#e_profit_margin').val(parseFloat(product_profit).toFixed(2));
                $('#e_showing_selling_price').val(parseFloat(product_price).toFixed(2));
                $('#e_lot_number').val('');
                $('#e_base_unit_cost_exc_tax').val(parseFloat(product_cost).toFixed(2));

                $('#e_unit_id').empty();
                $('#e_unit_id').append('<option value="'+baseUnit.id+'" data-is_base_unit="1" data-unit_name="'+baseUnit.name+'" data-base_unit_multiplier="1">'+baseUnit.name+'</option>');

                itemUnitsArray[product_id] = [
                    {
                        'unit_id' : baseUnit.id,
                        'unit_name' : baseUnit.name,
                        'unit_code_name' : baseUnit.code_name,
                        'base_unit_multiplier' : 1,
                        'multiplier_details' : '',
                        'is_base_unit' : 1,
                    }
                ];

                if (baseUnit.child_units.length > 0) {

                    baseUnit.child_units.forEach(function(unit){

                        var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + baseUnit.name+')';

                        itemUnitsArray[product_id].push({
                            'unit_id' : unit.id,
                            'unit_name' : unit.name,
                            'unit_code_name' : unit.code_name,
                            'base_unit_multiplier' : unit.base_unit_multiplier,
                            'multiplier_details' : multiplierDetails,
                            'is_base_unit' : 0,
                        });

                        $('#e_unit_id').append('<option value="' + unit.id + '" data-is_base_unit="0" data-unit_name="' + unit.name + '" data-base_unit_multiplier="' + unit.base_unit_multiplier + '">' + unit.name + multiplierDetails + '</option>');
                    });
                }

                calculateEditOrAddAmount();

                $('#add_item').html('Add');
            }
        })
    }

    $('#add_item').on('click', function (e) {
        e.preventDefault();

        var e_unique_id = $('#e_unique_id').val();
        var e_item_name = $('#e_item_name').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
        var e_unit_id = $('#e_unit_id').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
        var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
        var e_showing_unit_cost_exc_tax = $('#e_showing_unit_cost_exc_tax').val() ? $('#e_showing_unit_cost_exc_tax').val() : 0;
        var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
        var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;
        var e_discount_type = $('#e_discount_type').val();
        var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
        var e_showing_discount_amount = $('#e_showing_discount_amount').val() ? $('#e_showing_discount_amount').val() : 0;
        var e_tax_ac_id = $('#e_tax_ac_id').val();
        var e_tax_type = $('#e_tax_type').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
        var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
        var e_showing_tax_amount = $('#e_showing_tax_amount').val() ? $('#e_showing_tax_amount').val() : 0;
        var e_unit_cost_with_discount = $('#e_unit_cost_with_discount').val() ? $('#e_unit_cost_with_discount').val() : 0;
        var e_showing_unit_cost_with_discount = $('#e_showing_unit_cost_with_discount').val() ? $('#e_showing_unit_cost_with_discount').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
        var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val() ? $('#e_showing_unit_cost_inc_tax').val() : 0;
        var e_linetotal = $('#e_linetotal').val() ? $('#e_linetotal').val() : 0;
        var e_profit_margin = $('#e_profit_margin').val() ? $('#e_profit_margin').val() : 0;
        var e_selling_price = $('#e_selling_price').val() ? $('#e_selling_price').val() : 0;
        var e_showing_selling_price = $('#e_showing_selling_price').val() ? $('#e_showing_selling_price').val() : 0;
        var e_lot_number = $('#e_lot_number').val();
        var e_description = $('#e_description').val();

        if (e_product_id == '') {

            toastr.error('Please select a item.');
            return;
        }

        if (e_showing_quantity == '') {

            toastr.error('Quantity field must not be empty.');
            return;
        }

        var uniqueId = e_product_id+e_variant_id;

        var uniqueIdValue = $('#'+e_product_id+e_variant_id).val();

        if (uniqueIdValue == undefined) {

            var tr = '';
            tr += '<tr id="select_item">';
            tr += '<td>';
            tr += '<span id="span_item_name">'+e_item_name+'</span>';
            tr += '<input type="hidden" id="item_name" value="'+e_item_name+'">';
            tr += '<input type="hidden" name="descriptions[]" id="description" value="'+e_description+'">';
            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="'+e_product_id+'">';
            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="'+e_variant_id+'">';
            tr += '<input type="hidden" id="'+uniqueId+'" value="'+uniqueId+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_showing_quantity_unit" class="fw-bold">'+parseFloat(e_showing_quantity).toFixed(2)+'/'+e_unit_name+'</span>';
            tr += '<input type="hidden" step="any" id="showing_quantity" value="'+e_showing_quantity+'">';
            tr += '<input type="hidden" name="quantities[]" id="quantity" value="'+e_quantity+'">';
            tr += '<input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="'+e_unit_id+'">';

            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')

                tr += '<p class="p-0 m-0 fw-bold">Lot No : <span id="span_lot_number">'+e_lot_number+'</span>';
                tr += '<input type="hidden" name="lot_numbers[]" id="lot_number" value="'+e_lot_number+'">';
            @endif
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_showing_unit_cost_exc_tax" class="fw-bold">'+parseFloat(e_showing_unit_cost_exc_tax).toFixed(2)+'</span>';
            tr += '<input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="'+e_unit_cost_exc_tax+'">';
            tr += '<input type="hidden" id="showing_unit_cost_exc_tax" value="'+e_showing_unit_cost_exc_tax+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_showing_discount_amount" class="fw-bold">'+parseFloat(e_showing_discount_amount).toFixed(2)+'</span>';
            tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="'+e_discount_type+'">';
            tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="'+parseFloat(e_discount).toFixed(2)+'">';
            tr += '<input type="hidden" id="showing_unit_discount" value="'+parseFloat(e_showing_discount).toFixed(2)+'">';
            tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="'+parseFloat(e_discount_amount).toFixed(2)+'">';
            tr += '<input type="hidden" id="showing_unit_discount_amount" value="'+parseFloat(e_showing_discount_amount).toFixed(2)+'">';
            tr += '<input type="hidden" value="'+parseFloat(e_subtotal).toFixed(2)+'" name="subtotals[]" id="subtotal">';
            tr += '<input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="'+parseFloat(e_unit_cost_with_discount).toFixed(2)+'">';
            tr += '<input type="hidden" id="showing_unit_cost_with_discount" value="'+parseFloat(e_showing_unit_cost_with_discount).toFixed(2)+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_tax_percent" class="fw-bold">'+e_tax_percent+'%'+'</span>';
            tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="'+e_tax_ac_id+'">';
            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="'+e_tax_type+'">';
            tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="'+e_tax_percent+'">';
            tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="'+parseFloat(e_tax_amount).toFixed(2)+'">';
            tr += '<input type="hidden" id="showing_unit_tax_amount" value="'+parseFloat(e_showing_tax_amount).toFixed(2)+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_showing_unit_cost_inc_tax" class="fw-bold">'+parseFloat(e_showing_unit_cost_inc_tax).toFixed(2)+'</span>';
            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="'+parseFloat(e_unit_cost_inc_tax).toFixed(2)+'">';
            tr += '<input type="hidden" id="showing_unit_cost_inc_tax" value="'+parseFloat(e_showing_unit_cost_inc_tax).toFixed(2)+'">';
            tr += '<input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="'+parseFloat(e_unit_cost_inc_tax).toFixed(2)+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_linetotal" class="fw-bold">'+parseFloat(e_linetotal).toFixed(2)+'</span>';
            tr += '<input type="hidden" name="linetotals[]" value="'+parseFloat(e_linetotal).toFixed(2)+'" id="linetotal">';
            tr += '</td>';

            @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                tr += '<td>';
                tr += '<span id="span_profit" class="fw-bold">'+parseFloat(e_profit_margin).toFixed(2)+'</span>';
                tr += '<input type="hidden" name="profits[]" id="profit" value="'+parseFloat(e_profit_margin).toFixed(2)+'">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="span_showing_selling_price" class="fw-bold">'+parseFloat(e_showing_selling_price).toFixed(2)+'</span>';
                tr += '<input type="hidden" name="selling_prices[]" id="selling_price" value="'+parseFloat(e_selling_price).toFixed(2)+'">';
                tr += '<input type="hidden" id="showing_selling_price" value="'+parseFloat(e_showing_selling_price).toFixed(2)+'">';
                tr += '</td>';
            @endif

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
            tr += '</td>';
            tr += '</tr>';

            $('#purchase_list').prepend(tr);
            clearEditItemFileds();
            calculateTotalAmount();
        } else {

            var tr = $('#'+uniqueId).closest('tr');
            tr.find('#item_name').val(e_item_name);
            tr.find('#span_item_name').html(e_item_name);
            tr.find('#product_id').val(e_product_id);
            tr.find('#variant_id').val(e_variant_id);
            tr.find('#description').val(e_description);
            tr.find('#lot_number').val(e_lot_number);
            tr.find('#span_lot_number').html(e_lot_number);
            tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
            tr.find('#showing_quantity').val(parseFloat(e_showing_quantity).toFixed(2));
            tr.find('#span_showing_quantity_unit').html(parseFloat(e_showing_quantity).toFixed(2)+'/'+e_unit_name);
            tr.find('#unit_id').val(e_unit_id);
            tr.find('#unit_cost_exc_tax').val(parseFloat(e_unit_cost_exc_tax).toFixed(2));
            tr.find('#showing_unit_cost_exc_tax').val(parseFloat(e_showing_unit_cost_exc_tax).toFixed(2));
            tr.find('#span_showing_unit_cost_exc_tax').html(parseFloat(e_showing_unit_cost_exc_tax).toFixed(2));
            tr.find('#unit_discount_type').val(e_discount_type);
            tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
            tr.find('#showing_unit_discount').val(parseFloat(e_showing_discount).toFixed(2));
            tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
            tr.find('#showing_unit_discount_amount').val(parseFloat(e_showing_discount_amount).toFixed(2));
            tr.find('#span_showing_discount_amount').html(parseFloat(e_showing_discount_amount).toFixed(2));
            tr.find('#unit_cost_with_discount').val(parseFloat(e_unit_cost_with_discount).toFixed(2));
            tr.find('#showing_unit_cost_with_discount').val(parseFloat(e_showing_unit_cost_with_discount).toFixed(2));
            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
            tr.find('#tax_ac_id').val(e_tax_ac_id);
            tr.find('#tax_type').val(e_tax_type);
            tr.find('#span_tax_percent').html(parseFloat(e_tax_percent).toFixed(2)+'%');
            tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
            tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
            tr.find('#showing_unit_tax_amount').val(parseFloat(e_showing_tax_amount).toFixed(2));
            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
            tr.find('#showing_unit_cost_inc_tax').val(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
            tr.find('#span_showing_unit_cost_inc_tax').html(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
            tr.find('#net_unit_cost').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
            tr.find('#linetotal').val(parseFloat(e_linetotal).toFixed(2));
            tr.find('#span_linetotal').html(parseFloat(e_linetotal).toFixed(2));
            tr.find('#profit').val(parseFloat(e_profit_margin).toFixed(2));
            tr.find('#span_profit').html(parseFloat(e_profit_margin).toFixed(2));
            tr.find('#selling_price').val(parseFloat(e_selling_price).toFixed(2));
            tr.find('#showing_selling_price').val(parseFloat(e_showing_selling_price).toFixed(2));
            tr.find('#span_showing_selling_price').html(parseFloat(e_showing_selling_price).toFixed(2));
            clearEditItemFileds();
            calculateTotalAmount();
        }
    });

    $(document).on('click', '#select_item',function (e) {

        var tr = $(this);
        var item_name = tr.find('#item_name').val();
        var description = tr.find('#description').val();
        var lot_number = tr.find('#lot_number').val();
        var product_id = tr.find('#product_id').val();
        var variant_id = tr.find('#variant_id').val();
        var quantity = tr.find('#quantity').val();
        var showing_quantity = tr.find('#showing_quantity').val();
        var unit_id = tr.find('#unit_id').val();
        var unit_cost_exc_tax = tr.find('#unit_cost_exc_tax').val();
        var showing_unit_cost_exc_tax = tr.find('#showing_unit_cost_exc_tax').val();
        var unit_discount_type = tr.find('#unit_discount_type').val();
        var unit_discount = tr.find('#unit_discount').val();
        var showing_unit_discount = tr.find('#showing_unit_discount').val();
        var unit_discount_amount = tr.find('#unit_discount_amount').val();
        var showing_unit_discount_amount = tr.find('#showing_unit_discount_amount').val();
        var unit_cost_with_discount = tr.find('#unit_cost_with_discount').val();
        var showing_unit_cost_with_discount = tr.find('#showing_unit_cost_with_discount').val();
        var subtotal = tr.find('#subtotal').val();
        var tax_ac_id = tr.find('#tax_ac_id').val();
        var tax_type = tr.find('#tax_type').val();
        var unit_tax_amount = tr.find('#unit_tax_amount').val();
        var showing_unit_tax_amount = tr.find('#showing_unit_tax_amount').val();
        var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
        var showing_unit_cost_inc_tax = tr.find('#showing_unit_cost_inc_tax').val();
        var linetotal = tr.find('#linetotal').val();
        var profit = tr.find('#profit').val();
        var selling_price = tr.find('#selling_price').val();
        var showing_selling_price = tr.find('#showing_selling_price').val();

        $('#e_unit_id').empty();

        itemUnitsArray[product_id].forEach(function(unit) {

            $('#e_unit_id').append('<option '+ (unit_id == unit.unit_id ? 'selected' : '')  +' value="'+unit.unit_id+'" data-is_base_unit="'+unit.is_base_unit+'" data-unit_name="'+unit.unit_name+'" data-base_unit_multiplier="'+unit.base_unit_multiplier+'">'+unit.unit_name+unit.multiplier_details+'</option>');
        });

        $('#search_product').val(item_name);
        $('#e_item_name').val(item_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_showing_quantity').val(parseFloat(showing_quantity).toFixed(2));
        $('#e_unit_cost_exc_tax').val(parseFloat(unit_cost_exc_tax).toFixed(2));
        $('#e_base_unit_cost_exc_tax').val(parseFloat(unit_cost_exc_tax).toFixed(2));
        $('#e_showing_unit_cost_exc_tax').val(parseFloat(showing_unit_cost_exc_tax).toFixed(2));
        $('#e_discount_type').val(unit_discount_type);
        $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
        $('#e_showing_discount').val(parseFloat(showing_unit_discount).toFixed(2));
        $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
        $('#e_showing_discount_amount').val(parseFloat(showing_unit_discount_amount).toFixed(2));
        $('#e_tax_ac_id').val(tax_ac_id);
        $('#e_tax_type').val(tax_type);
        $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
        $('#e_showing_tax_amount').val(parseFloat(showing_unit_tax_amount).toFixed(2));
        $('#e_unit_cost_with_discount').val(parseFloat(unit_cost_with_discount).toFixed(2));
        $('#e_showing_unit_cost_with_discount').val(parseFloat(showing_unit_cost_with_discount).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
        $('#e_showing_unit_cost_inc_tax').val(parseFloat(showing_unit_cost_inc_tax).toFixed(2));
        $('#e_linetotal').val(parseFloat(linetotal).toFixed(2));
        $('#e_profit_margin').val(parseFloat(profit).toFixed(2));
        $('#e_selling_price').val(parseFloat(selling_price).toFixed(2));
        $('#e_showing_selling_price').val(parseFloat(showing_selling_price).toFixed(2));
        $('#e_lot_number').val(lot_number);
        $('#e_description').val(description);

        var attr = $('#e_showing_quantity').attr('readonly');

        if (attr == undefined) {

            $('#e_showing_quantity').focus().select();
        }else {

            $('#e_showing_unit_cost_exc_tax').focus().select();
        }

        $('#add_item').html('Edit');
    });

    function calculateEditOrAddAmount() {

        var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
        var is_base_unit = $('#e_unit_id').find('option:selected').data('is_base_unit');
        var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
        var e_showing_unit_cost_exc_tax = $('#e_showing_unit_cost_exc_tax').val() ? $('#e_showing_unit_cost_exc_tax').val() : 0;
        var e_base_unit_cost_exc_tax = $('#e_base_unit_cost_exc_tax').val() ? $('#e_base_unit_cost_exc_tax').val() : 0;
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;
        var e_tax_type = $('#e_tax_type').val();
        var e_discount_type = $('#e_discount_type').val();
        var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;

        var quantity = roundOfValue(e_showing_quantity) * roundOfValue(base_unit_multiplier);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2));

        var unitCostExcTax = roundOfValue(e_showing_unit_cost_exc_tax) / roundOfValue(base_unit_multiplier);
        $('#e_unit_cost_exc_tax').val(roundOfValue(unitCostExcTax));
        $('#e_base_unit_cost_exc_tax').val(roundOfValue(unitCostExcTax));

        var showing_discount_amount = 0;
        var discount_amount = 0;
        var unit_discount = 0
        if (e_discount_type == 1) {

            showing_discount_amount = roundOfValue(e_showing_discount);
            discount_amount = roundOfValue(e_showing_discount) / roundOfValue(base_unit_multiplier);
            unit_discount = roundOfValue(e_showing_discount) / roundOfValue(base_unit_multiplier);
        } else {

            showing_discount_amount = (roundOfValue(e_showing_unit_cost_exc_tax) / 100) * roundOfValue(e_showing_discount);
            discount_amount = roundOfValue(showing_discount_amount) / roundOfValue(base_unit_multiplier);
            unit_discount = roundOfValue(e_showing_discount);
        }

        var showingCostWithDiscount = roundOfValue(e_showing_unit_cost_exc_tax) - roundOfValue(showing_discount_amount);
        var costWithDiscount = roundOfValue(showingCostWithDiscount) / roundOfValue(base_unit_multiplier);
        $('#e_showing_unit_cost_with_discount').val(parseFloat(roundOfValue(showingCostWithDiscount)).toFixed(2));
        $('#e_unit_cost_with_discount').val(parseFloat(roundOfValue(costWithDiscount)).toFixed(2));

        var subtotal = roundOfValue(costWithDiscount) * roundOfValue(quantity);
        $('#e_subtotal').val(parseFloat(roundOfValue(subtotal)).toFixed(2));

        var showingTaxAmount = roundOfValue(showingCostWithDiscount) / 100 * roundOfValue(e_tax_percent);
        var taxAmount = roundOfValue(showingTaxAmount) / roundOfValue(base_unit_multiplier);
        var showingUnitCostIncTax = roundOfValue(showingCostWithDiscount) + roundOfValue(showingTaxAmount);
        var unitCostIncTax = roundOfValue(showingUnitCostIncTax) / roundOfValue(base_unit_multiplier);

        if (e_tax_type == 2) {

            var inclusiveTax = 100 + roundOfValue(e_tax_percent);
            var calcTax = roundOfValue(showingCostWithDiscount) / roundOfValue(inclusiveTax) * 100;
            var __tax_amount = roundOfValue(showingCostWithDiscount) - roundOfValue(calcTax);
            showingTaxAmount = __tax_amount;
            taxAmount = roundOfValue(showingTaxAmount) / roundOfValue(base_unit_multiplier);
            showingUnitCostIncTax = roundOfValue(showingCostWithDiscount) + roundOfValue(__tax_amount);
            unitCostIncTax = roundOfValue(showingUnitCostIncTax) / roundOfValue(base_unit_multiplier);
        }

        $('#e_tax_amount').val(parseFloat(roundOfValue(taxAmount)).toFixed(2));
        $('#e_showing_tax_amount').val(parseFloat(roundOfValue(showingTaxAmount)).toFixed(2));
        $('#e_discount').val(parseFloat(roundOfValue(unit_discount)).toFixed(2));
        $('#e_discount_amount').val(parseFloat(roundOfValue(discount_amount)).toFixed(2));
        $('#e_showing_discount_amount').val(parseFloat(roundOfValue(showing_discount_amount)).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(roundOfValue(unitCostIncTax)).toFixed(2));
        $('#e_showing_unit_cost_inc_tax').val(parseFloat(roundOfValue(showingUnitCostIncTax)).toFixed(2));

        var linetotal = parseFloat(unitCostIncTax) * parseFloat(quantity);
        $('#e_linetotal').val(parseFloat(linetotal).toFixed(2));

         // Update selling price
        var profit = $('#e_profit_margin').val() ? $('#e_profit_margin').val() : 0;
        var showingCostWithDiscount = $('#e_showing_unit_cost_with_discount').val();
        var showingSellingPrice = parseFloat(showingCostWithDiscount) / 100 * parseFloat(profit) + parseFloat(showingCostWithDiscount);
        $('#e_showing_selling_price').val(parseFloat(showingSellingPrice).toFixed(2));
        var sellingPrice = parseFloat(showingSellingPrice) / parseFloat(base_unit_multiplier);
        $('#e_selling_price').val(parseFloat(sellingPrice).toFixed(2));
    }

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input keypress', '#e_showing_quantity', function(e){

        calculateEditOrAddAmount();

        if(e.which == 13) {

            if ($(this).val() != '') {

                $('#e_unit_id').focus();
            }
        }
    });

    $('#e_unit_id').on('change keypress click', function (e) {

        var isBaseUnit = $(this).find('option:selected').data('is_base_unit');
        var baseUnitCostExcTax = $('#e_base_unit_cost_exc_tax').val() ? $('#e_base_unit_cost_exc_tax').val() : 0;
        var base_unit_multiplier = $(this).find('option:selected').data('base_unit_multiplier');
        var unit_discount_type = $('#e_discount_type').val();

        var showingUnitCostExcTax = roundOfValue(baseUnitCostExcTax) * roundOfValue(base_unit_multiplier);
        $('#e_showing_unit_cost_exc_tax').val(parseFloat(showingUnitCostExcTax).toFixed(2));

        if(e.which == 0) {

            $('#e_showing_unit_cost_exc_tax').focus().select();
        }

        calculateEditOrAddAmount();
    });

    // Change tax percent and clculate row amount
    $(document).on('input keypress', '#e_showing_unit_cost_exc_tax', function(e){

        calculateEditOrAddAmount();

        if(e.which == 13) {

            if ($(this).val() != '') {

                $('#e_showing_discount').focus().select();
            }
        }
    });

    // Input discount and clculate row amount
    $(document).on('input keypress', '#e_showing_discount', function(e) {

        calculateEditOrAddAmount();

        if(e.which == 13) {

            if ($(this).val() != '' && $(this).val() > 0) {

                $('#e_discount_type').focus().select();
            }else{

                $('#e_tax_ac_id').focus();
            }
        }
    });

    // Input discount and clculate row amount
    $(document).on('change keypress click', '#e_discount_type', function(e) {

        calculateEditOrAddAmount();

        if(e.which == 0) {

            $('#e_tax_ac_id').focus();
        }
    });

    // Change tax percent and clculate row amount
    $('#e_tax_ac_id').on('change keypress click', function (e) {

        calculateEditOrAddAmount();

        if(e.which == 0) {

            if ($(this).val() != '') {

                $('#e_tax_type').focus();
            }else {

                $('#e_lot_number').focus().select();
            }
        }
    });

    // Change tax percent and clculate row amount
    $(document).on('change keypress click', '#e_tax_type', function(e){

        calculateEditOrAddAmount();

        if(e.which == 0) {

            $('#e_lot_number').focus().select();
        }
    });

    $(document).on('input keypress', '#e_lot_number', function(e) {

        calculateEditOrAddAmount();

        if(e.which == 13) {

            $('#e_description').focus().select();
        }
    });

    $(document).on('input keypress', '#e_description', function(e) {

        calculateEditOrAddAmount();

        var xMargin = $('#e_profit_margin').val();
        if(e.which == 13) {

            if (xMargin != undefined) {

                $('#e_profit_margin').focus().select();
            }else {

                $('#add_item').focus();
            }
        }
    });

    // Input profit margin and clculate row amount
    $(document).on('input keypress', '#e_profit_margin', function(e){

        calculateEditOrAddAmount();
        if(e.which == 13) {

            $('#e_showing_selling_price').focus().select();
        }
    });

    $(document).on('input keypress', '#e_showing_selling_price',function(e) {

        var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
        var showing_selling_price = $(this).val() ? $(this).val() : 0;
        var showingUnitCostWithDiscount = $('#e_showing_unit_cost_with_discount').val() ? $('#e_showing_unit_cost_with_discount').val() : 0;
        var profitAmount = parseFloat(showing_selling_price) - parseFloat(showingUnitCostWithDiscount);
        var __cost = parseFloat(showingUnitCostWithDiscount) > 0 ? parseFloat(showingUnitCostWithDiscount) : parseFloat(profitAmount);
        var xMargin = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __xMargin = xMargin ? xMargin : 0;
        $('#e_profit_margin').val(parseFloat(__xMargin).toFixed(2));

        var sellingPrice = parseFloat(showing_selling_price) / parseFloat(base_unit_multiplier);
        $('#e_selling_price').val(parseFloat(sellingPrice).toFixed(2));

        if(e.which == 13) {

            $('#add_item').focus();
        }
    });

    $(document).on('blur', '#paying_amount', function(e){

        if ($(this).val() == '') {

            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    // Input order discount and clculate total amount
    $(document).on('input', '#order_discount', function(){

        calculateTotalAmount();
    });

    // change order discount type and clculate total amount
    $(document).on('change', '#order_discount_type', function(){

        calculateTotalAmount();
    });

    // Input ait deduction and clculate total amount
    $(document).on('input', '#ait_deduction', function(){

        calculateTotalAmount();
    });

    // change ait deduction type and clculate total amount
    $(document).on('change', '#ait_deduction_type', function(){

        calculateTotalAmount();
    });

    // Input shipment charge and clculate total amount
    $(document).on('input', '#shipment_charge', function(){

        calculateTotalAmount();
    });

    $(document).on('change', '#purchase_tax_ac_id', function(){

        calculateTotalAmount();
        var purchaseTaxPercent = $(this).find('option:selected').data('purchase_tax_percent') ? $(this).find('option:selected').data('purchase_tax_percent') : 0;
        $('#purchase_tax_percent').val(parseFloat(purchaseTaxPercent).toFixed(2));
    });

    // Input paying amount and clculate due amount
    $(document).on('input', '#paying_amount', function(){

        calculateTotalAmount();
    });

    // Remove product form purchase product list (Table)
    $(document).on('click', '#remove_product_btn',function(e){
        e.preventDefault();

        $(this).closest('tr').remove();

        calculateTotalAmount();

        setTimeout(function () {

            clearEditItemFileds();
        }, 5);
    });

    setInterval(function() {

        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function(){

        $('#search_product').removeClass('is-valid');
    }, 1000);

    $('#payment_method_id').on('change', function () {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#account_id').val(account_id);
        }else if(account_id === ''){

            $('#account_id option:first-child').prop("selected", true);
        }
    }

    setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));

    function clearEditItemFileds() {

        $('#search_product').val('').focus();
        $('#e_unique_id').val('');
        $('#e_item_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_quantity').val(parseFloat(0).toFixed(2));
        $('#e_showing_quantity').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_showing_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_discount').val(parseFloat(0).toFixed(2));
        $('#e_showing_discount').val(parseFloat(0).toFixed(2));
        $('#e_discount_type').val(1);
        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_ac_id').val('');
        $('#e_tax_type').val(1);
        $('#e_showing_tax_amount').val(0);
        $('#e_tax_amount').val(0);
        $('#e_unit_cost_with_discount').val(parseFloat(0).toFixed(2));
        $('#e_showing_unit_cost_with_discount').val(parseFloat(0).toFixed(2));
        $('#e_subtotal').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_showing_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_linetotal').val(parseFloat(0).toFixed(2));
        $('#e_profit_margin').val(parseFloat(0).toFixed(2));
        $('#e_selling_price').val(parseFloat(0).toFixed(2));
        $('#e_showing_selling_price').val(parseFloat(0).toFixed(2));
        $('#e_lot_number').val('');
        $('#e_description').val('');
        $('#add_item').html('Add');
    }

    $('#reset_add_or_edit_item_fields').on('click', function (e) {
        e.preventDefault();

        clearEditItemFileds();
    });
</script>

<script>
    @if (auth()->user()->can('product_add'))

        $('#add_product').on('click', function () {

            $.ajax({
                url : "{{ route('common.ajax.call.add.quick.product.modal') }}",
                type : 'get',
                success : function(data){

                    $('#quick_product_add_modal_contant').html(data);
                    $('#addQuickProductModal').modal('show');

                    setTimeout(function () {

                        $('#product_name').focus().select();
                    }, 500);
                }
            });
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.quick_product_submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.quick_product_submit_button', function () {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            }else {

                $(this).prop('type', 'button');
            }
        });

        // Add product by ajax
        $(document).on('submit', '#add_quick_product_form', function(e) {
            e.preventDefault();

            $('.quick_product_loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            isAjaxIn = false;
            isAllowSubmit = false;

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.quick_product_loading_button').hide();
                    $('#addQuickProductModal').modal('hide');
                    toastr.success('Successfully product is added.');

                    var product = data.item;
                    var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;

                    $('#search_product').val(name);
                    $('#e_item_name').val(name);
                    $('#e_product_id').val(product.id);
                    $('#e_variant_id').val('noid');
                    $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_showing_unit_cost_exc_tax').val(parseFloat(product.product_cost).toFixed(2));
                    $('#e_showing_discount').val(parseFloat(0).toFixed(2));
                    $('#e_discount_type').val(1);
                    $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
                    $('#e_tax_ac_id').val(product.tax_ac_id)
                    $('#e_tax_type').val(product.tax_type);
                    $('#e_profit_margin').val(parseFloat(product.profit).toFixed(2));
                    $('#e_showing_selling_price').val(parseFloat(product.product_price).toFixed(2));
                    $('#e_description').val('');
                    $('#e_lot_number').val('');
                    $('#e_base_unit_cost_exc_tax').val(parseFloat(product.product_cost).toFixed(2));

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append('<option value="'+product.unit.id+'" data-is_base_unit="1" data-unit_name="'+product.unit.name+'" data-base_unit_multiplier="1">'+product.unit.name+'</option>');

                    itemUnitsArray[product.id] = [
                        {
                            'unit_id' : product.unit.id,
                            'unit_name' : product.unit.name,
                            'unit_code_name' : product.unit.code_name,
                            'base_unit_multiplier' : 1,
                            'multiplier_details' : '',
                            'is_base_unit' : 1,
                        }
                    ];

                    if (product.unit.child_units.length > 0) {

                        product.unit.child_units.forEach(function(unit){

                            var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + unit.name+')';

                            itemUnitsArray[product.id].push({
                                'unit_id' : unit.id,
                                'unit_name' : unit.name,
                                'unit_code_name' : unit.code_name,
                                'base_unit_multiplier' : unit.base_unit_multiplier,
                                'multiplier_details' : multiplierDetails,
                                'is_base_unit' : 1,
                            });

                            $('#e_unit_id').append('<option value="'+unit.id+'" data-is_base_unit="0" data-unit_name="'+unit.name+'" data-base_unit_multiplier="'+unit.base_unit_multiplier+'">'+unit.name+multiplierDetails+'</option>');
                        });
                    }

                    calculateEditOrAddAmount();

                    $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#add_item').html('Add');
                },error: function(err) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.quick_product_loading_button').hide();

                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }else if (err.status == 500) {

                        toastr.error('Server Error. Please contact to the support team.');
                        return;
                    }else if (err.status == 403) {

                        toastr.error('Assess denied.');
                        return;
                    }

                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_quick_product_' + key + '').html(error[0]);
                    });
                }
            });

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
        });
    @endif

    function roundOfValue(val) {

        return ((parseFloat(val) * 1000) / 1000);
    }
</script>
