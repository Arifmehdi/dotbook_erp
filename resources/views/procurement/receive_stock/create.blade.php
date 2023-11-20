@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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
            font-size: 10px;
            padding: 2px 2px;
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

        .invoice_search_result {
            position: absolute;
            width: 100%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 3px;
            margin-top: 1px;
        }

        .invoice_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .invoice_search_result ul li a {
            color: #6b6262;
            font-size: 10px;
            display: block;
            padding: 0px 3px;
        }

        .invoice_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .selected_requisition {
            background-color: #645f61;
            color: #fff !important;
        }

        .po_search_result {
            position: absolute;
            width: 100%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 3px;
            margin-top: 1px;
        }

        .po_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .po_search_result ul li a {
            color: #6b6262;
            font-size: 10px;
            display: block;
            padding: 0px 3px;
        }

        .po_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .selected_po {
            background-color: #645f61;
            color: #fff !important;
        }

        .element-body {
            overflow: initial !important;
        }

        span.select2-dropdown.select2-dropdown--below {
            border-top: 1px solid gray;
        }

        span.select2-dropdown.select2-dropdown--below {
            width: 283px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            width: 137px;
        }

        .sale-item-sec {
            min-height: 220px;
        }
    </style>
@endpush
@section('title', 'Create Stock Receive - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.create_receive_stock')</h6>
                <x-back-button />
            </div>
            <div class="p-15">
                <form id="add_receive_stock_form" action="{{ route('purchases.receive.stocks.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="hidden" name="action" id="action" value="">
                    <div class="row g-0">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><span class="text-danger">*</span> <b>@lang('menu.supplier')</b></label>
                                            <div class="col-8">
                                                <div class="input-group select-customer-input-group">
                                                    <select required name="supplier_account_id" class="form-control select2 form-select" id="supplier_account_id" data-next="requisition_no">
                                                        <option value="">@lang('menu.select_supplier')</option>
                                                        @foreach ($supplierAccounts as $supplierAccount)
                                                            <option value="{{ $supplierAccount->id }}">
                                                                {{ $supplierAccount->name . '(' . $supplierAccount->phone . ')' }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <div style="display: inline-block;margin-top:0px;" class="style-btn">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button mr-1 {{ !auth()->user()->can('supplier_add')? 'disabled_element': '' }}" id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="error error_supplier_account_id"></span>
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>@lang('menu.req_no')</b></label>
                                            <div class="col-8">
                                                <input type="text" name="requisition_no" id="requisition_no" class="form-control fw-bold" data-next="po_id" placeholder="Search Requistion" autocomplete="off">
                                                <div class="invoice_search_result display-none">
                                                    <ul id="requisition_list" class="list-unstyled"></ul>
                                                </div>
                                                <input type="hidden" name="requisition_id" id="requisition_id">
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>@lang('menu.po_id')</b></label>
                                            <div class="col-8">
                                                <input type="text" name="po_id" id="po_id" class="form-control fw-bold" data-next="challan_no" placeholder="Search Purchase order" autocomplete="off">
                                                <div class="po_search_result display-none">
                                                    <ul id="po_list" class="list-unstyled"></ul>
                                                </div>
                                                <input type="hidden" name="purchase_order_id" id="purchase_order_id">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.challan_no')</b></label>
                                            <div class="col-8">
                                                <input type="text" name="challan_no" id="challan_no" class="form-control" data-next="challan_date" placeholder="Supplier Challan No" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>@lang('menu.challan_date')</b></label>
                                            <div class="col-8">
                                                <input type="text" name="challan_date" id="challan_date" class="form-control" data-next="net_weight" placeholder="Supplier Challan Date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>@lang('menu.weight_and_vehicle')</b></label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" step="any" name="net_weight" class="form-control" id="net_weight" data-next="vehicle_no" placeholder="Net Weight" autocomplete="off">
                                                    <input type="text" name="vehicle_no" class="form-control" id="vehicle_no" data-next="date" placeholder="Vehicle No." autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.voucher_no')</b></label>
                                            <div class="col-8">
                                                <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" placeholder="@lang('menu.voucher_no')" autocomplete="off">
                                                <span class="error error_voucher_no"></span>
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b><span class="text-danger">*</span> @lang('menu.date')</b></label>
                                            <div class="col-8">
                                                <input type="text" name="date" class="form-control changeable" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="date" data-next="warehouse_id" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                                <span class="error error_date"></span>
                                            </div>
                                        </div>

                                        @if (count($warehouses) > 0)

                                            <input name="warehouse_count" value="YES" type="hidden" />
                                            <div class="input-group mt-1">
                                                <label class="col-4"><span class="text-danger">*</span>
                                                    <b>@lang('menu.warehouse')</b></label>
                                                <div class="col-8">
                                                    <select required class="form-control form-select" name="warehouse_id" id="warehouse_id" data-next="search_product">
                                                        <option value="">@lang('menu.select_warehouse')</option>
                                                        @foreach ($warehouses as $w)
                                                            <option value="{{ $w->id }}">
                                                                {{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_warehouse_id"></span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.store_location')</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control changeable" value="{{ json_decode($generalSettings->business, true)['shop_name'] }}" />
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.total_item')</b></label>
                                            <div class="col-8">
                                                <input readonly name="total_item" type="text" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>@lang('menu.total_quantity')</b></label>
                                            <div class="col-8">
                                                <input readonly type="text" class="form-control fw-bold" id="showing_total_qty" value="0.00" tabindex="-1">
                                                <input type="hidden" name="total_qty" class="form-control fw-bold" id="total_qty" value="0.00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <section>
                        <div class="sale-content">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <div class="item-details-sec rounded mt-0 mb-1">
                                        <div class="content-inner">

                                            <div class="row align-items-end">
                                                <div class="col-xl-4 col-md-5">
                                                    <div class="searching_area" style="position: relative;">
                                                        <label><strong>@lang('menu.search_item')</strong></label>
                                                        <div class="input-group ">
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

                                                <input type="hidden" id="e_unique_id">
                                                <input type="hidden" id="e_item_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">

                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>@lang('menu.quantity')</strong></label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" class="form-control w-60 fw-bold" id="e_showing_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                                        <input type="hidden" id="e_quantity">
                                                        <select id="e_unit_id" class="form-control w-40 form-select">
                                                            <option value="">@lang('menu.unit')</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                                    <div class="col-xl-2 col-md-4 mt-1">
                                                        <label><strong>@lang('menu.lot_number')</strong></label>
                                                        <input type="text" step="any" class="form-control fw-bold" id="e_lot_number" placeholder="@lang('menu.lot_number')" autocomplete="off">
                                                    </div>
                                                @endif

                                                <div class="col-xl-2 col-md-4 mt-1">
                                                    <label><strong>@lang('menu.short_description')</strong></label>
                                                    <input type="text" step="any" class="form-control fw-bold" id="e_description" placeholder="@lang('menu.short_description')" autocomplete="off">
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <a href="#" class="btn btn-sm btn-success" id="add_item">@lang('menu.add')</a>
                                                    <a href="#" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger">@lang('menu.reset')</a>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="sale-item-sec">
                                                    <div class="sale-item-inner">
                                                        <div class="table-responsive">
                                                            <table class="display data__table table-striped">
                                                                <thead class="staky">
                                                                    <tr>
                                                                        <th>@lang('menu.item')</th>
                                                                        <th>@lang('menu.quantity')</th>
                                                                        <th>@lang('menu.unit')</th>
                                                                        @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                                                            <th>@lang('menu.lot_number')</th>
                                                                        @endif
                                                                        <th><i class="fas fa-trash-alt"></i></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="recieved_item_list"></tbody>
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
                    </section>

                    <section>
                        <div class="row g-1">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="input-group mt-1">
                                                        <label class="col-1"><b>@lang('menu.receive_notes') </b></label>
                                                        <div class="col-11">
                                                            <input name="note" class="form-control fw-bold" id="note" data-next="save_and_print" placeholder="@lang('menu.receive_notes')">
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

                    <div class="submitBtn p-15 pt-0">
                        <div class="row justify-content-center">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                    <button type="button" id="save_and_print" value="1" class="btn btn-success submit_button me-2">@lang('menu.save_and_print')</button>
                                    <button type="button" id="save" value="2" class="btn btn-success submit_button">@lang('menu.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (auth()->user()->can('supplier_add'))
        <!-- Add Supplier Modal -->
        <div class="modal fade" id="add_supplier_basic_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="add_supplier_detailed_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <!-- Add Supplier Modal End -->
    @endif

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @include('procurement.receive_stock.partials.receiveStocksCreateJsScript')
    <script>
        $('.select2').select2();
        var itemUnitsArray = [];

        var ul = document.getElementById('list');
        var selectObjClassName = 'selectProduct';

        $('#requisition_no').mousedown(function(e) {

            afterClickOrFocusRequisitionNo();
        }).focus(function(e) {

            ul = document.getElementById('requisition_list')
            selectObjClassName = 'selected_requisition';
        });

        function afterClickOrFocusRequisitionNo() {

            ul = document.getElementById('requisition_list')
            selectObjClassName = 'selected_requisition';
            $('#requisition_no').val('');
            $('#requisition_id').val('');
            $('#po_id').val('');
            $('#purchase_order_id').val('');
            $('#recieved_item_list').empty();

            $('#search_product').prop('disabled', false);
            calculateTotalAmount();
        }

        $('#po_id').mousedown(function(e) {

            afterClickOrFocusPoId();
        }).focus(function(e) {

            ul = document.getElementById('po_list')
            selectObjClassName = 'selected_po';
        });

        function afterClickOrFocusPoId() {

            ul = document.getElementById('requisition_list')
            selectObjClassName = 'selected_requisition';
            $('#requisition_no').val('');
            $('#requisition_id').val('');
            $('#po_id').val('');
            $('#purchase_order_id').val('');
            $('#recieved_item_list').empty();

            $('#search_product').prop('disabled', false);
            calculateTotalAmount();
        }

        function afterFocusSearchItemField() {

            ul = document.getElementById('list');
            selectObjClassName = 'selectProduct';
        }

        $('#search_product').focus(function(e) {

            afterFocusSearchItemField();
        });

        $('#requisition_no').on('input', function() {

            $('.invoice_search_result').hide();

            var requisition_no = $(this).val();

            if (requisition_no === '') {

                $('.invoice_search_result').hide();
                $('#requisition_id').val('');
                $('#search_product').prop('disabled', false);
                return;
            }

            var url = "{{ route('common.ajax.call.search.requisitions', ':requisition_no') }}";
            var route = url.replace(':requisition_no', requisition_no);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.invoice_search_result').hide();
                    } else {

                        $('.invoice_search_result').show();
                        $('#requisition_list').html(data);
                    }
                }
            });
        });

        // Work will be running from here...
        $(document).on('click', '#selected_requisition', function(e) {
            e.preventDefault();

            if ($(this).data('is_approved') == 0) {

                $('#requisition_no').focus().select();
                toastr.error('The Requisition not yet to be appreved');
                return;
            }

            var requisition_no = $(this).html();
            var requisition_id = $(this).data('id');

            var url = "{{ route('common.ajax.call.get.requisition.products.for.receive.stock', ':requisition_id') }}";
            var route = url.replace(':requisition_id', requisition_id);
            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('#requisition_no').focus().select();
                        return;
                    }

                    itemUnitsArray = jQuery.parseJSON(data.units);

                    $('#requisition_no').val(requisition_no.trim());
                    $('#requisition_id').val(requisition_id);
                    $('#recieved_item_list').html(data.view);

                    $('#search_product').prop('disabled', true);
                    $('#requisition_list').empty();

                    $('.invoice_search_result').hide();

                    calculateTotalAmount();
                }
            });
        });

        $('#po_id').on('input', function() {

            $('.po_search_result').hide();

            var po_id = $(this).val();

            if (po_id === '') {

                $('.po_search_result').hide();
                $('#po_id').val('');
                $('#search_product').prop('disabled', false);
                return;
            }

            var url = "{{ route('common.ajax.call.search.po', ':po_id') }}";
            var route = url.replace(':po_id', po_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.po_search_result').hide();
                    } else {

                        $('.po_search_result').show();
                        $('#po_list').html(data);
                    }
                }
            });
        });

        // Work will be running from here...
        $(document).on('click', '#selected_po', function(e) {
            e.preventDefault();

            var po_id = $(this).data('po_id');
            var purchase_order_id = $(this).data('purchase_order_id');
            var supplier_account_id = $(this).data('supplier_account_id');
            var warehouse_id = $(this).data('warehouse_id');
            var requisition_no = $(this).data('requisition_no');
            var requisition_id = $(this).data('requisition_id');

            var url = "{{ route('common.ajax.call.po.products', ':purchase_order_id') }}";
            var route = url.replace(':purchase_order_id', purchase_order_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('#po_id').focus().select();
                        return;
                    }

                    itemUnitsArray = jQuery.parseJSON(data.units);

                    $('#po_id').val(po_id);
                    $('#purchase_order_id').val(purchase_order_id);
                    $('#supplier_account_id').val(supplier_account_id).trigger('change');
                    $('#warehouse_id').val(warehouse_id);
                    $('#requisition_no').val(requisition_no);
                    $('#requisition_id').val(requisition_id);
                    $('#recieved_item_list').html(data.view);

                    $('#search_product').prop('disabled', true);

                    $('.invoice_search_result').hide();
                    $('.po_search_result').hide();
                    $('#requisition_list').empty();
                    $('#po_list').empty();

                    calculateTotalAmount();
                }
            });
        });

        $(document).on('keyup', 'body', function(e) {

            if (e.keyCode == 13) {

                $('.' + selectObjClassName).click();
                $('.invoice_search_result').hide();
                $('.select_area').hide();
                $('#list').empty();
                $('#requisition_list').empty();
            }
        });

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
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('.invoice_search_result').hide();
                $('#requisition_list').empty();
                $('#list').empty();
                return false;
            }
        }

        //Add purchase request by ajax
        $('#add_receive_stock_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');

            isAjaxIn = false;
            isAllowSubmit = false;
            $.ajax({
                beforeSend: function() {
                    isAjaxIn = true;
                },
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.error').html('');
                    $('.loading_button').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'ERROR');
                    } else if (data.successMsg) {

                        toastr.success(data.successMsg);
                        $('#add_receive_stock_form')[0].reset();
                        $('#purchase_list').empty();
                        $('#requisition_id').val('');
                        $('#search_product').prop('disabled', false);

                        $("#supplier_account_id").select2("destroy");
                        $("#supplier_account_id").select2();
                    } else {

                        toastr.success('Successfully stock is received.');
                        $('#add_receive_stock_form')[0].reset();
                        $('#recieved_item_list').empty();
                        $('#requisition_id').val('');
                        $('#search_product').prop('disabled', false);

                        $("#supplier_account_id").select2("destroy");
                        $("#supplier_account_id").select2();

                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });
                    }
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

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
        });

        $(document).on('click', function(e) {

            if (
                $(e.target).closest(".select_area").length === 0 ||
                $(e.target).closest(".invoice_search_result").length === 0 ||
                $(e.target).closest(".po_search_result").length === 0
            ) {

                $('.select_area').hide();
                $('.invoice_search_result').hide();
                $('.po_search_result').hide();
                $('#requisition_list').empty();
                $('#list').empty();
                $('#po_list').empty();
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

                if ($(this).attr('id') == 'warehouse_id' && $('#search_product').is(':disabled') == true) {

                    $('#e_showing_quantity').focus().select();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                var warehouse_id = $('#warehouse_id').val();

                if ($(this).attr('id') == 'date' && warehouse_id == undefined) {

                    if ($('#search_product').is(':disabled') == true) {

                        $('#e_showing_quantity').focus().select();
                        return;
                    } else {

                        $('#search_product').focus().select();
                        return;
                    }
                }

                if ($(this).attr('id') == 'requisition_no' && $('#requisition_no').val() != '') {

                    $('#challan_no').focus().select();
                    return;
                }

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

        new Litepicker({
            singleMode: true,
            element: document.getElementById('challan_date'),
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

        document.getElementById('supplier_account_id').focus();
    </script>

    <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script>
@endpush
