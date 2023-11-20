<script>
     $('#addSupplier').on('click', function () {

        $.get("{{ route('contacts.supplier.create.basic.modal') }}", function(data) {

            $('#add_supplier_basic_modal').html(data);
            $('#add_supplier_basic_modal').modal('show');
        });
    });

    function calculateTotalAmount() {

        var quantities = document.querySelectorAll('#quantity');
        var showing_quantities = document.querySelectorAll('#showing_quantity');
        var linetotals = document.querySelectorAll('#linetotal');
        var total_item = 0;
        var total_qty = 0;
        var showing_total_qty = 0;

        quantities.forEach(function(qty){

            total_item += 1;
            total_qty += parseFloat(qty.value);
        });

        showing_quantities.forEach(function(qty){

            showing_total_qty += parseFloat(qty.value);
        });

        $('#total_qty').val(parseFloat(total_qty));
        $('#showing_total_qty').val(parseFloat(showing_total_qty));
        $('#total_item').val(parseFloat(total_item));
    }

    calculateTotalAmount();

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

                        if(product.variants.length == 0){

                            $('.select_area').hide();

                            var unique_id = product.id+'noid';

                            $('#search_product').val(name);
                            $('#e_unique_id').val(unique_id);
                            $('#e_item_name').val(name);
                            $('#e_product_id').val(product.id);
                            $('#e_variant_id').val('noid');
                            $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_lot_number').val('');

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

                            $('#add_item').html('Add');
                            calculateEditOrAddAmount();
                        }else{

                            var li = "";

                            $.each(product.variants, function(key, variant){

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                li += '<li>';
                                li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-v_name="'+variant.variant_name+'" data-p_code="'+variant.variant_code+'" href="#"><img style="width:20px; height:20px;" src="'+product.thumbnail_photo+'"> '+ product.name +'</a>';
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

                                if (product.is_variant == 1) {

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="'+product.id+'" data-v_id="'+product.variant_id+'" data-p_name="'+product.name+'" data-v_name="'+product.variant_name+'" data-p_code="'+product.variant_code+'" href="#"><img style="width:20px; height:20px;" src="'+product.thumbnail_photo+'"> '+product.name+' - '+product.variant_name+'</a>';
                                    li +='</li>';
                                }else{

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-p_id="'+product.id+'" data-v_id="" data-p_name="'+product.name+'" data-v_name="" data-p_code="'+product.product_code+'" href="#"><img style="width:20px; height:20px;" src="'+product.thumbnail_photo+'"> '+product.name+'</a>';
                                    li +='</li>';
                                }
                            });

                            $('.variant_list_area').html(li);
                            $('.select_area').show();
                        }
                    }else if(!$.isEmptyObject(product.variant_product)){

                        $('.select_area').hide();

                        var name = variant_product.product.name.length > 35 ? product.name.substring(0, 35) + '...' : variant_product.product.name;

                        var unique_id = variant_product.product.id+variant_product.id;

                        $('#e_unique_id').val(unique_id);
                        $('#search_product').val(name +' - '+variant_product.variant_name);
                        $('#e_item_name').val(name+' - '+variant_product.variant_name);
                        $('#e_product_id').val(variant_product.product.id);
                        $('#e_variant_id').val(variant_product.id);
                        $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_lot_number').val('');

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

                        $('#add_item').html('Add');

                        calculateEditOrAddAmount();
                    }
                } else {

                    $('#search_product').addClass('is-invalid');
                }
            }
        });
    }

    // select single product and add purchase table
    function selectProduct(e){

        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var product_code = e.getAttribute('data-p_code');

        var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
        var route = url.replace(':product_id', product_id);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(baseUnit) {

                $('#search_product').val(product_name+(variant_name ? ' - '+variant_name : '' ));
                $('#e_item_name').val(product_name+(variant_name ? ' - '+variant_name : '' ));
                $('#e_product_id').val(product_id);
                $('#e_variant_id').val(variant_id ? variant_id : 'noid');
                $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                $('#e_lot_number').val('');

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

                $('#add_item').html('Add');

                calculateEditOrAddAmount();
            }
        });
    }

    $('#add_item').on('click', function (e) {
        e.preventDefault();

        var e_unique_id = $('#e_unique_id').val();
        var e_item_name = $('#e_item_name').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_unit_id = $('#e_unit_id').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
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

        if (e_unit_id == '' || e_unit_id == null) {

            toastr.error('Please select a unit.');
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
            tr += '<input type="hidden" name="short_descriptions[]" id="description" value="'+e_description+'">';
            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="'+e_product_id+'">';
            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="'+e_variant_id+'">';
            tr += '<input type="hidden" name="receive_stock_product_ids[]" value="">';
            tr += '<input type="hidden" name="purchase_order_product_ids[]" value="">';
            tr += '<input type="hidden" id="'+uniqueId+'" value="'+uniqueId+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_showing_quantity" class="fw-bold">'+parseFloat(e_showing_quantity).toFixed(2)+'</span>';
            tr += '<input type="hidden" id="showing_quantity" value="'+e_showing_quantity+'"">';
            tr += '<input type="hidden" name="quantities[]" id="quantity" value="'+e_quantity+'">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="span_showing_unit" class="fw-bold">'+e_unit_name+'</span>';
            tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="'+e_unit_id+'">';
            tr += '</td>';

            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                tr += '<td>';
                tr += '<span id="span_showing_lot_number" class="fw-bold">'+e_lot_number+'</span>';
                tr += '<input  type="hidden" name="lot_numbers[]" id="lot_number" value="'+e_lot_number+'">';
                tr += '</td>';
            @endif

            tr += '<td class="text-start">';
            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger"></i></a>';
            tr += '</td>';
            tr += '</tr>';

            $('#recieved_item_list').prepend(tr);
            clearEditItemFileds();
            calculateTotalAmount();
        }else {

            var tr = $('#'+uniqueId).closest('tr');
            tr.find('#item_name').val(e_item_name);
            tr.find('#span_item_name').html(e_item_name);
            tr.find('#description').val(e_description);
            tr.find('#lot_number').val(e_lot_number);
            tr.find('#span_showing_lot_number').html(e_lot_number);
            tr.find('#product_id').val(e_product_id);
            tr.find('#variant_id').val(e_variant_id);
            tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
            tr.find('#showing_quantity').val(parseFloat(e_showing_quantity).toFixed(2));
            tr.find('#span_showing_quantity').html(parseFloat(e_showing_quantity).toFixed(2));
            tr.find('#span_showing_unit').html(e_unit_name);
            tr.find('#unit_id').val(e_unit_id);
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

        $('#e_unit_id').empty();
        itemUnitsArray[product_id].forEach(function(unit) {

            $('#e_unit_id').append('<option '+ (unit_id == unit.unit_id ? 'selected' : '')  +' value="'+unit.unit_id+'" data-is_base_unit="'+unit.is_base_unit+'" data-unit_name="'+unit.unit_name+'" data-base_unit_multiplier="'+unit.base_unit_multiplier+'">'+unit.unit_name+unit.multiplier_details+'</option>');
        });

        $('#search_product').val(item_name);
        $('#e_item_name').val(item_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_showing_quantity').val(parseFloat(showing_quantity).toFixed(2)).focus().select();
        $('#e_lot_number').val(lot_number);
        $('#e_description').val(description);
        $('#add_item').html('Edit');
    });

    function calculateEditOrAddAmount() {

        var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
        var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
        var quantity = parseFloat(e_showing_quantity) * parseFloat(base_unit_multiplier);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2));
    }

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input keypress', '#e_showing_quantity', function(e) {

        calculateEditOrAddAmount();
        if(e.which == 13) {

            if ($(this).val() != '') {

                $('#e_unit_id').focus().select();
            }
        }
    });

    $(document).on('input keypress click', '#e_unit_id', function(e) {

        calculateEditOrAddAmount();

        var e_lot_number = $('#e_lot_number').val();
        if(e.which == 0) {

            if (e_lot_number != undefined) {

                $('#e_lot_number').focus().select();
            }else{

                $('#e_description').focus().select();
            }
        }
    });

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input keypress', '#e_lot_number', function(e) {

        if(e.which == 13) {

            $('#e_description').focus().select();
        }
    });

    // Quantity increase or dicrease and clculate row amount
    $(document).on('input keypress', '#e_description', function(e) {

        if(e.which == 13) {

            $('#add_item').focus();
        }
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

    document.getElementById('search_product').focus();

     function clearEditItemFileds() {

        $('#search_product').val('').focus();
        $('#e_unique_id').val('');
        $('#e_item_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_quantity').val(parseFloat(0).toFixed(2));
        $('#e_showing_quantity').val(parseFloat(0).toFixed(2));
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
                url:"{{ route('common.ajax.call.add.quick.product.modal') }}",
                type:'get',
                success:function(data){

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
        })

        // Add product by ajax
        $(document).on('submit', '#add_quick_product_form',function(e) {
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
                    $('#e_description').val('');
                    $('#e_lot_number').val('');

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

                            var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + product.unit.name+')';

                            itemUnitsArray[product.id].push({
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

                    $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#add_item').html('Add');

                    calculateEditOrAddAmount();
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

                        $('.error_quick_' + key + '').html(error[0]);
                    });
                }
            });

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
        });
    @endif
</script>
