@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 100%;
            z-index: 9999999;
            padding: 0;
            left: 0%;
            display: none;
            border: 1px solid var(--main-color);
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 4px 4px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 11px;
            padding: 2px 3px;
            display: block;
            border: 1px solid gray;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70;
            color: #fff !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        h6.collapse_table:hover {
            background: lightgray;
            padding: 3px;
            cursor: pointer;
        }

        .c-delete:focus {
            border: 1px solid gray;
            padding: 2px;
        }

        /* .input-group-text{padding: 4.5px 8px;} */
    </style>
@endpush
@section('title', 'Add Purchase Requisition - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <form id="add_requisition_form" action="{{ route('purchases.requisition.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action" value="">
                <section class="mt-5x">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="sec-name">
                                <h6>@lang('menu.add_purchase_requisition')</h6>
                                <x-all-buttons />
                            </div>
                        </div>
                    </div>
                </section>
                <div class="p-15">
                    <div class="row">
                        <div class="col-12">
                            <div class="form_element rounded mt-0 mb-1">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.requisition_no')</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="requisition_no" id="requisition_no" class="form-control fw-bold" placeholder="Purchase requisition No" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><span class="text-danger">*</span>
                                                    <b>@lang('menu.departments') </b></label>
                                                <div class="col-8">
                                                    <div class="input-group select-customer-input-group">
                                                        <select required name="department_id" class="form-control select2 form-select" id="department_id" data-next="date">
                                                            <option value="">@lang('menu.select_department')</option>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}">
                                                                    {{ $department->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button" id="addDepartment"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                    <span class="error error_department_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b><span class="text-danger">*</span>
                                                        @lang('menu.requisition_date') </b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control" id="date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" data-next="requester_id" placeholder="dd-mm-yyyy" autocomplete="off">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><span class="text-danger">*</span>
                                                    <b>@lang('menu.requested_by') </b></label>
                                                <div class="col-8">
                                                    <div class="input-group select-customer-input-group">
                                                        <select required name="requester_id" class="form-control select2 form-select" data-name="requestedBy" id="requester_id" data-next="search_product">
                                                            <option value="">@lang('menu.requested_by')</option>
                                                            @foreach ($requesters as $requester)
                                                                @php
                                                                    $phone = $requester->phone_number ? '/' . $requester->phone_number : '';
                                                                @endphp
                                                                <option value="{{ $requester->id }}">{{ $requester->name . $phone }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div style="display: inline-block;margin-top:0px;" class="style-btn">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button" id="addRequster"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="error error_requester_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.total_item') </b> </label>
                                                <div class="col-8">
                                                    <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.total_quantity') </b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="total_qty" step="any" class="form-control fw-bold" id="total_qty" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sale-content mb-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row align-items-end">
                                            <div class="col-xl-3 col-md-4">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="fw-bold">@lang('menu.search_item')</label>
                                                    <div class="input-group">

                                                        <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_item_item_code_scan_bar_code')" autofocus>

                                                        @if (auth()->user()->can('product_add'))
                                                            <div class="input-group-prepend">
                                                                <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark input_f"></i></span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <input type="hidden" id="e_item_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">
                                                <input type="hidden" id="e_last_purchase_cost">
                                                <input type="hidden" id="e_current_stock">
                                                <input type="hidden" id="e_base_unit_name">

                                                <label class="fw-bold">@lang('menu.quantity')</label>
                                                <input type="number" step="any" class="form-control fw-bold" id="e_showing_quantity" placeholder="@lang('menu.quantity')">
                                                <input type="hidden" id="e_quantity">
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label class="fw-bold">@lang('menu.unit')</label>
                                                <select id="e_unit_id" class="form-control form-select">
                                                    <option value="">@lang('menu.unit')</option>
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label class="fw-bold">@lang('menu.purpose')</label>
                                                <input type="text" class="form-control fw-bold" id="e_purpose" placeholder="@lang('menu.purpose')">
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <label class="fw-bold">@lang('menu.requisition_type')</label>
                                                <select id="e_requisition_type" class="form-control form-select">
                                                    <option data-pr_type_name="@lang('menu.normal')" value="1">
                                                        @lang('menu.normal')</option>
                                                    <option data-pr_type_name="@lang('menu.emergency')" value="2">
                                                        @lang('menu.emergency')</option>
                                                </select>
                                            </div>

                                            <div class="col-xl-1 col-md-4">
                                                <div class="btn-box-2">
                                                    <a href="#" class="btn btn-sm btn-success" id="add_item">@lang('menu.add')</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('menu.item')</th>
                                                                    <th>@lang('menu.last_purchase_price')</th>
                                                                    <th>@lang('menu.current_stock')</th>
                                                                    <th>@lang('menu.requisition_quantity')</th>
                                                                    <th>@lang('menu.unit')</th>
                                                                    <th>@lang('menu.purpose')</th>
                                                                    <th>@lang('menu.requisition_type')</th>
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="requisition_item_list"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-1">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label><b>@lang('menu.requisition_note')</b></label>
                                            <input name="note" class="form-control" data-next="save_and_print" placeholder="@lang('menu.requisition_note')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                <button type="button" id="save_and_print" value="1" class="btn btn-success sb submit_button me-2">@lang('menu.save_and_print')</button>
                                <button type="button" id="save" value="2" class="btn btn-success sb submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-shortcut-key-bar.shortcut-key-bar :items="[['key' => 'Ctrl + Enter', 'value' => __('menu.save_and_print')], ['key' => 'Shift + Enter', 'value' => __('menu.save')], ['key' => 'Alt + G', 'value' => __('menu.add_department')], ['key' => 'Alt + R', 'value' => __('menu.add_requester')]]">
    </x-shortcut-key-bar.shortcut-key-bar>

    <div class="modal fade" id="requisitionDepartmentAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>

    <div class="modal fade" id="requestAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop">
    </div>
    @if (auth()->user()->can('product_add'))
        <!--Add Quick Product Modal-->
        <div class="modal fade" id="addQuickProductModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop">
            <div class="modal-dialog four-col-modal" role="document" id="quick_product_add_modal_contant"></div>
        </div>
        <!--Add Quick Product Modal End-->

        <div class="modal fade" id="unitAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="brandAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="warrantyAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="categoryAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="subcategoryAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    @endif

@endsection
@push('scripts')
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();

        var itemUnitsArray = [];

        $('#addRequster').on('click', function() {

            $.get("{{ route('requesters.create') }}", function(data) {

                $('#requestAddOrEditModal').html(data);
                $('#requestAddOrEditModal').modal('show');

                setTimeout(function() {

                    document.getElementById('requester_name').focus();
                }, 500);
            });
        });

        $('#addDepartment').on('click', function() {

            $.get("{{ route('requisitions.departments.create') }}", function(data) {

                $('#requisitionDepartmentAddOrEditModal').html(data);
                $('#requisitionDepartmentAddOrEditModal').modal('show');

                setTimeout(function() {
                    document.getElementById('department_name').focus();
                }, 500);
            });
        });

        var delay = (function() {
            var timer = 0;
            return function(callback, ms) {

                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $('#search_product').on('input', function(e) {

            $('.variant_list_area').empty();
            $('.select_area').hide();
            var keyWord = $(this).val();
            var __keyWord = keyWord.replaceAll('/', '~');

            delay(function() {
                searchProduct(__keyWord);
            }, 200); //sendAjaxical is the name of remote-command
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
                success: function(product) {

                    if (!$.isEmptyObject(product.errorMsg)) {

                        toastr.error(product.errorMsg);
                        $('#search_product').val('');
                        return;
                    }

                    if (
                        !$.isEmptyObject(product.product) ||
                        !$.isEmptyObject(product.variant_product) ||
                        !$.isEmptyObject(product.namedProducts)
                    ) {

                        $('#search_product').addClass('is-valid');
                        if (!$.isEmptyObject(product.product)) {

                            var product = product.product;

                            if (product.variants.length == 0) {

                                $('.select_area').hide();
                                $('#search_product').val(product.name);
                                $('#e_item_name').val(product.name);
                                $('#e_product_id').val(product.id);
                                $('#e_variant_id').val('noid');
                                $('#e_base_unit_name').val(product.unit.name);
                                $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();

                                $('#e_requisition_type').val(1);
                                $('#e_last_purchase_cost').val(product.product_cost_with_tax);
                                $('#e_current_stock').val(product.quantity);

                                $('#e_unit_id').empty();
                                $('#e_unit_id').append('<option value="' + product.unit.id +
                                    '" data-is_base_unit="1" data-unit_name="' + product.unit.name +
                                    '" data-base_unit_multiplier="1">' + product.unit.name + '</option>');

                                itemUnitsArray[product.id] = [{
                                    'unit_id': product.unit.id,
                                    'unit_name': product.unit.name,
                                    'unit_code_name': product.unit.code_name,
                                    'base_unit_multiplier': 1,
                                    'multiplier_details': '',
                                    'is_base_unit': 1,
                                }];

                                if (product.unit.child_units.length > 0) {

                                    product.unit.child_units.forEach(function(unit) {

                                        var multiplierDetails = '(1 ' + unit.name + ' = ' + unit
                                            .base_unit_multiplier + '/' + unit.name + ')';

                                        itemUnitsArray[product.id].push({
                                            'unit_id': unit.id,
                                            'unit_name': unit.name,
                                            'unit_code_name': unit.code_name,
                                            'base_unit_multiplier': unit.base_unit_multiplier,
                                            'multiplier_details': multiplierDetails,
                                            'is_base_unit': 1,
                                        });

                                        $('#e_unit_id').append('<option value="' + unit.id +
                                            '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                            '" data-base_unit_multiplier="' + unit
                                            .base_unit_multiplier + '">' + unit.name +
                                            multiplierDetails + '</option>');
                                    });
                                }

                                $('#add_item').html('Add');

                                calculateEditOrAddAmount();
                            } else {

                                var li = "";
                                $.each(product.variants, function(key, variant) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-base_unit_name="' + product.unit_name + '" data-v_name="' + variant.variant_name + '" data-p_code="' + variant.variant_code + '" data-current_stock="' + variant.variant_quantity + '" data-p_cost_exc_tax="' + variant.variant_cost + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if (!$.isEmptyObject(product.namedProducts)) {

                            if (product.namedProducts.length > 0) {

                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    if (product.is_variant == 1) {

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-base_unit_name="' + product.unit_name + '" data-v_name="' + product.variant_name + '" data-current_stock="' + product.variant_quantity + '" data-p_code="' + product.variant_code + '"  data-p_code="' + product.variant_code + '" data-p_cost_exc_tax="' + product.variant_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                        li += '</li>';
                                    } else {

                                        li += '<li class="mt-1">';
                                        li += '<a  class="select_single_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-current_stock="' + product.quantity + '" data-p_name="' + product.name + '" data-base_unit_name="' + product.unit_name + '" data-v_name="" data-p_code="' + product.product_code + '" data-p_cost_exc_tax="' + product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                        li += '</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        } else if (!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();
                            var variant_product = product.variant_product;
                            var variant_name = variant_product.variant_name;

                            $('#search_product').val(variant_product.product.name + ' -' + variant_name);
                            $('#e_item_name').val(variant_product.product.name + ' -' + variant_name);
                            $('#e_product_id').val(variant_product.product.id);
                            $('#e_variant_id').val(variant_product.id);
                            $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_base_unit_name').val(variant_product.product.unit.name);
                            $('#e_requisition_type').val(1);
                            $('#e_last_purchase_cost').val(variant_product.variant_cost_with_tax);
                            $('#e_current_stock').val(variant_product.variant_quantity);

                            $('#e_unit_id').empty();
                            $('#e_unit_id').append('<option value="' + variant.product.unit.id +
                                '" data-is_base_unit="1" data-unit_name="' + variant.product.unit.name +
                                '" data-base_unit_multiplier="1">' + variant.product.unit.name + '</option>'
                            );

                            itemUnitsArray[variant.product.id] = [{
                                'unit_id': variant.product.unit.id,
                                'unit_name': variant.product.unit.name,
                                'unit_code_name': variant.product.unit.code_name,
                                'base_unit_multiplier': 1,
                                'multiplier_details': '',
                                'is_base_unit': 1,
                            }];

                            if (variant.product.unit.child_units.length > 0) {

                                variant.product.unit.child_units.forEach(function(unit) {

                                    var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + unit.name + ')';

                                    itemUnitsArray[variant.product.id].push({
                                        'unit_id': unit.id,
                                        'unit_name': unit.name,
                                        'unit_code_name': unit.code_name,
                                        'base_unit_multiplier': unit.base_unit_multiplier,
                                        'multiplier_details': multiplierDetails,
                                        'is_base_unit': 0,
                                    });

                                    $('#e_unit_id').append('<option value="' + unit.id +
                                        '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                        '" data-base_unit_multiplier="' + unit
                                        .base_unit_multiplier + '">' + unit.name +
                                        multiplierDetails + '</option>');
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
        function selectProduct(e) {

            $('.select_area').hide();
            $('#list').empty();
            $('#search_product').val('');

            var product_id = e.getAttribute('data-p_id');
            var variant_id = e.getAttribute('data-v_id');
            var product_name = e.getAttribute('data-p_name');
            var variant_name = e.getAttribute('data-v_name');
            var base_unit_name = e.getAttribute('data-base_unit_name');
            var current_stock = e.getAttribute('data-current_stock');
            var product_code = e.getAttribute('data-p_code');
            var product_cost_exc_tax = e.getAttribute('data-p_cost_exc_tax');

            var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
            var route = url.replace(':product_id', product_id);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(baseUnit) {

                    $('#search_product').val(product_name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_item_name').val(product_name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_product_id').val(product_id);
                    $('#e_base_unit_name').val(base_unit_name);
                    $('#e_variant_id').val(variant_id ? variant_id : 'noid');
                    $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_requisition_type').val(1);
                    $('#e_last_purchase_cost').val(product_cost_exc_tax);
                    $('#e_current_stock').val(current_stock);

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append('<option value="' + baseUnit.id +
                        '" data-is_base_unit="1" data-unit_name="' + baseUnit.name +
                        '" data-base_unit_multiplier="1">' + baseUnit.name + '</option>');

                    itemUnitsArray[product_id] = [{
                        'unit_id': baseUnit.id,
                        'unit_name': baseUnit.name,
                        'unit_code_name': baseUnit.code_name,
                        'base_unit_multiplier': 1,
                        'multiplier_details': '',
                        'is_base_unit': 1,
                    }];

                    if (baseUnit.child_units.length > 0) {

                        baseUnit.child_units.forEach(function(unit) {

                            var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + baseUnit.name + ')';

                            itemUnitsArray[product_id].push({
                                'unit_id': unit.id,
                                'unit_name': unit.name,
                                'unit_code_name': unit.code_name,
                                'base_unit_multiplier': unit.base_unit_multiplier,
                                'multiplier_details': multiplierDetails,
                                'is_base_unit': 0,
                            });

                            $('#e_unit_id').append('<option value="' + unit.id +
                                '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                '" data-base_unit_multiplier="' + unit.base_unit_multiplier + '">' +
                                unit.name + multiplierDetails + '</option>');
                        });
                    }

                    $('#add_item').html('Add');

                    calculateEditOrAddAmount();
                }
            });
        }

        $('#add_item').on('click', function(e) {

            e.preventDefault();
            var e_item_name = $('#e_item_name').val();
            var e_product_id = $('#e_product_id').val();
            var e_variant_id = $('#e_variant_id').val();
            var e_unit_id = $('#e_unit_id').val();
            var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
            var e_base_unit_name = $('#e_base_unit_name').val();
            var e_quantity = $('#e_quantity').val();
            var e_showing_quantity = $('#e_showing_quantity').val();
            var e_requisition_type = $('#e_requisition_type').val();
            var e_requisition_type_name = $('#e_requisition_type').find('option:selected').data('pr_type_name');
            var e_last_purchase_cost = $('#e_last_purchase_cost').val();
            var e_current_stock = $('#e_current_stock').val();
            var e_purpose = $('#e_purpose').val();

            if (e_quantity == '') {

                toastr.error('Quantity field must not be empty.');
                return;
            }

            if (e_unit_id == '') {

                toastr.error('Please select a unit.');
                return;
            }

            if (e_product_id == '') {

                toastr.error('Please select a item.');
                return;
            }

            var uniqueId = e_product_id + e_variant_id;

            var uniqueIdValue = $('#' + uniqueId).val();

            if (uniqueIdValue == undefined) {

                var tr = '';
                tr += '<tr id="select_item">';
                tr += '<td>';
                tr += '<span id="span_item_name">' + e_item_name + '</span>';
                tr += '<input type="hidden" id="item_name" value="' + e_item_name + '">';
                tr += '<input type="hidden" name="descriptions[]" id="description" value="">';
                tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + e_product_id + '">';
                tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + e_variant_id + '">';
                tr += '<input type="hidden" id="' + uniqueId + '" value="' + uniqueId + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="span_last_unit_cost" class="fw-bold">' + parseFloat(e_last_purchase_cost).toFixed(2) + ' Per: ' + e_base_unit_name + '</span>';
                tr += '<input type="hidden" name="last_unit_costs[]" id="last_unit_cost" value="' + parseFloat(e_last_purchase_cost).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="span_current_stock" class="fw-bold">' + parseFloat(e_current_stock).toFixed(2) + '/' + e_base_unit_name + '</span>';
                tr += '<input type="hidden" name="current_stocks[]" id="current_stock" value="' + parseFloat(
                    e_current_stock).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="span_showing_quantity" class="fw-bold">' + parseFloat(e_showing_quantity).toFixed(2) + '</span>';
                tr += '<input type="hidden" id="showing_quantity" value="' + parseFloat(e_showing_quantity).toFixed(2) + '">';
                tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="span_unit_name" class="fw-bold">' + e_unit_name + '</span>';
                tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                tr += '<input type="hidden" id="base_unit_name" value="' + e_base_unit_name + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="span_purpose" class="fw-bold">' + e_purpose + '</span>';
                tr += '<input type="hidden" name="purposes[]" id="purpose" value="' + e_purpose + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="span_pr_type_name" class="fw-bold">' + e_requisition_type_name + '</span>';
                tr += '<input type="hidden" name="pr_types[]" id="pr_type" value="' + e_requisition_type + '">';
                tr += '</td>';

                tr += '<td>';
                tr +=
                    '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                tr += '</td>';

                tr += '</tr>';

                $('#requisition_item_list').append(tr);
                clearEditItemFileds();
                calculateTotalAmount();
            } else {

                var tr = $('#' + uniqueId).closest('tr');

                tr.find('#span_item_name').html(e_item_name);
                tr.find('#item_name').val(e_item_name);
                tr.find('#product_id').val(e_product_id);
                tr.find('#variant_id').val(e_variant_id);
                tr.find('#showing_quantity').val(parseFloat(e_showing_quantity).toFixed(2));
                tr.find('#span_showing_quantity').html(parseFloat(e_showing_quantity).toFixed(2));
                tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                tr.find('#unit_id').val(e_unit_id);
                tr.find('#span_unit_name').html(e_unit_name);
                tr.find('#pr_type').val(e_requisition_type);
                tr.find('#pr_type_name').val(e_requisition_type_name);
                tr.find('#span_purpose').html(e_purpose);
                tr.find('#purpose').val(e_purpose);
                tr.find('#span_current_stock').html(parseFloat(e_current_stock).toFixed(2) + '/' + e_base_unit_name);
                tr.find('#current_stock').val(parseFloat(e_current_stock).toFixed(2));
                tr.find('#span_last_unit_cost').html(parseFloat(e_last_purchase_cost).toFixed(2) + ' Per : ' + e_base_unit_name);
                tr.find('#last_unit_cost').val(parseFloat(e_last_purchase_cost).toFixed(2));
                clearEditItemFileds();
                calculateTotalAmount();
            }
        });

        $(document).on('click', '#select_item', function(e) {

            var tr = $(this);
            var item_name = tr.find('#item_name').val();
            var product_id = tr.find('#product_id').val();
            var variant_id = tr.find('#variant_id').val();
            var unit_id = tr.find('#unit_id').val();
            var showing_quantity = tr.find('#showing_quantity').val();
            var quantity = tr.find('#quantity').val();
            var base_unit_name = tr.find('#base_unit_name').val();
            var pr_type = tr.find('#pr_type').val();
            var purpose = tr.find('#purpose').val();
            var current_stock = tr.find('#current_stock').val();
            var last_unit_cost = tr.find('#last_unit_cost').val();

            $('#e_unit_id').empty();
            itemUnitsArray[product_id].forEach(function(unit) {

                $('#e_unit_id').append('<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                    ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                    '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                    .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details +
                    '</option>');
            });

            $('#search_product').val(item_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_base_unit_name').val(base_unit_name);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2));
            $('#e_showing_quantity').val(parseFloat(showing_quantity).toFixed(2)).focus().select();
            $('#e_requisition_type').val(pr_type);
            $('#e_last_purchase_cost').val(parseFloat(last_unit_cost).toFixed(2));
            $('#e_current_stock').val(parseFloat(current_stock).toFixed(2));
            $('#e_purpose').val(purpose);

            $('#add_item').html('Edit');
        });

        function calculateEditOrAddAmount() {

            var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
            var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
            var quantity = parseFloat(e_showing_quantity) * parseFloat(base_unit_multiplier);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2));
        }

        function calculateTotalAmount() {
            var quantities = document.querySelectorAll('#showing_quantity');
            var total_item = 0;
            var total_qty = 0;

            quantities.forEach(function(qty) {
                total_item += 1;
                var __total_qty = parseFloat(qty.value) ? parseFloat(qty.value) : 0;
                total_qty += parseFloat(__total_qty)
            });

            $('#total_qty').val(parseFloat(total_qty));
            $('#total_item').val(parseFloat(total_item));
        }

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input keypress', '#e_showing_quantity', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#e_unit_id').focus().select();
                }
            }
        });

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input keypress click', '#e_unit_id', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

                $('#e_purpose').focus().select();
            }
        });

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input keypress', '#e_purpose', function(e) {

            if (e.which == 13) {

                $('#e_requisition_type').focus().select();
            }
        });

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input keypress click', '#e_requisition_type', function(e) {

            if (e.which == 0) {

                $('#add_item').focus();
            }
        });

        // Remove product form purchase product list (Table)
        $(document).on('click', '#remove_product_btn', function(e) {
            e.preventDefault();

            $(this).closest('tr').remove();
            calculateTotalAmount();
            setTimeout(function() {

                clearEditItemFileds();
            }, 5);
        });

        function clearEditItemFileds() {

            $('#search_product').val('').focus();
            $('#e_item_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_base_unit_name').val();
            $('#e_quantity').val(parseFloat(0).toFixed(2));
            $('#e_showing_quantity').val(parseFloat(0).toFixed(2));
            $('#e_requisition_type').val(1);
            $('#e_last_purchase_cost').val(parseFloat(0).toFixed(2));
            $('#e_current_stock').val(parseFloat(0).toFixed(2));
            $('#e_purpose').val('');
            $('#add_item').html('Add');
        }

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.submit_button', function() {

            var value = $(this).val();
            $('#action').val(value);

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            }
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save_and_print').click();
                return false;
            } else if (e.shiftKey && e.which == 13) {

                $('#save').click();
                return false;
            } else if (e.altKey && e.which == 71) {

                $('#addDepartment').click();
            } else if (e.altKey && e.which == 82) {

                $('#addRequster').click();
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('#variant_list_area').empty();
                return false;
            }
        }

        //Add purchase requisition request by ajax
        $('#add_requisition_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
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
                cache: false,
                success: function(data) {
                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.error').html('');
                    $('.loading_button').hide();

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'ERROR');
                        return;
                    } else if (data.successMsg) {

                        toastr.success(data.successMsg);
                        $('#add_requisition_form')[0].reset();
                        $('#requisition_item_list').empty();

                        $("#department_id").select2("destroy");
                        $("#department_id").select2();
                        $("#requester_id").select2("destroy");
                        $("#requester_id").select2();
                        document.getElementById('department_id').focus();
                    } else {

                        toastr.success('Successfully Purchase Requisition is Created.');
                        $('#add_requisition_form')[0].reset();
                        $("#department_id").select2("destroy");
                        $("#department_id").select2();
                        $("#requester_id").select2("destroy");
                        $("#requester_id").select2();
                        $('#requisition_item_list').empty();
                        document.getElementById('department_id').focus();

                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });
                    }

                    return;
                },
                error: function(err) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }

                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });

                    return;
                }
            });

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
        });

        $('select').on('select2:close', function(e) {

            var nextId = $(this).data('next');

            $('#' + nextId).focus();

            setTimeout(function() {

                $('#' + nextId).focus();
            }, 100);
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

        $('body').keyup(function(e) {

            if (e.keyCode == 13 || e.keyCode == 9) {

                if ($(".selectProduct").attr('href') == undefined) {

                    return;
                }

                $(".selectProduct").click();

                $('#list').empty();
                keyName = e.keyCode;
            }
        });

        $(document).on('click', '#select_product', function(e) {

            e.preventDefault();
        });

        document.getElementById('search_product').focus();

        setInterval(function() {
            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function() {
            $('#search_product').removeClass('is-valid');
        }, 1000);

        $(document).on('click', function(e) {

            if ($(e.target).closest(".select_area").length === 0 || $(e.target).closest(".invoice_search_result")
                .length === 0) {

                $('.select_area').hide();
                $('#variant_list_area').empty();
            }
        });
    </script>

    <script>
        @if (auth()->user()->can('product_add'))

            $('#add_product').on('click', function() {

                $.ajax({
                    url: "{{ route('common.ajax.call.add.quick.product.modal') }}",
                    type: 'get',
                    success: function(data) {

                        $('#quick_product_add_modal_contant').html(data);
                        $('#addQuickProductModal').modal('show');

                        setTimeout(function() {

                            $('#product_name').focus().select();
                        }, 500);
                    }
                });
            });

            $(document).on('click keypress focus blur change', '.form-control', function(event) {

                $('.quick_product_submit_button').prop('type', 'button');
            });

            var isAllowSubmit = true;
            $(document).on('click', '.quick_product_submit_button', function() {

                if (isAllowSubmit) {

                    $(this).prop('type', 'submit');
                } else {

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

                        $('#search_product').val(product.name);
                        $('#e_item_name').val(product.name);
                        $('#e_product_id').val(product.id);
                        $('#e_variant_id').val('noid');
                        $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_last_purchase_cost').val(parseFloat(product.product_cost_with_tax)
                            .toFixed(2));
                        $('#e_current_stock').val(parseFloat(product.quantity).toFixed(2));
                        $('#e_base_unit_name').val(product.unit.name);

                        $('#e_unit_id').empty();
                        $('#e_unit_id').append('<option value="' + product.unit.id +
                            '" data-is_base_unit="1" data-unit_name="' + product.unit.name +
                            '" data-base_unit_multiplier="1">' + product.unit.name + '</option>');

                        itemUnitsArray[product.id] = [{
                            'unit_id': product.unit.id,
                            'unit_name': product.unit.name,
                            'unit_code_name': product.unit.code_name,
                            'base_unit_multiplier': 1,
                            'multiplier_details': '',
                            'is_base_unit': 1,
                        }];

                        if (product.unit.child_units.length > 0) {

                            product.unit.child_units.forEach(function(unit) {

                                var multiplierDetails = '(1 ' + unit.name + ' = ' + unit
                                    .base_unit_multiplier + '/' + product.unit.name + ')';

                                itemUnitsArray[product.id].push({
                                    'unit_id': unit.id,
                                    'unit_name': unit.name,
                                    'unit_code_name': unit.code_name,
                                    'base_unit_multiplier': unit.base_unit_multiplier,
                                    'multiplier_details': multiplierDetails,
                                    'is_base_unit': 0,
                                });

                                $('#e_unit_id').append('<option value="' + unit.id +
                                    '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                    '" data-base_unit_multiplier="' + unit
                                    .base_unit_multiplier + '">' + unit.name +
                                    multiplierDetails + '</option>');
                            });
                        }

                        $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#add_item').html('Add');
                        calculateEditOrAddAmount();
                    },
                    error: function(err) {

                        isAjaxIn = true;
                        isAllowSubmit = true;
                        $('.quick_product_loading_button').hide();

                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        } else if (err.status == 500) {

                            toastr.error('Server Error. Please contact to the support team.');
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

        document.getElementById('department_id').focus();
    </script>
@endpush
