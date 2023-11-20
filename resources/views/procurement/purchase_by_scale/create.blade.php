@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .selected_challan {
            background-color: #645f61;
            color: #fff !important;
        }

        .invoice_search_result {
            position: absolute;
            width: 374%;
            ;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 99999;
            padding: 3px;
            margin-top: 1px;
            border: 1px solid black;
        }

        .invoice_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .invoice_search_result ul li a {
            color: #6b6262;
            font-size: 15px;
            display: block;
            padding: 5px 3px;
            line-height: 15px;
        }

        .invoice_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 88.3%;
            z-index: 9999999;
            padding: 0;
            left: 6%;
            display: none;
            border: 1px solid #706a6d;
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 0px 2px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 11px;
            padding: 2px 2px;
            display: block;
            border: 1px solid lightgray;
            margin: 2px 0px;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70 !important;
            color: #fff !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        .custome-input-group {
            font-size: 11px !important;
            padding-left: 4px !important;
            padding-right: 3px !important;
        }

        .element-body {
            overflow: initial !important;
        }

        .item-details-sec {
            overflow: initial !important;
        }

        .weight_input {
            height: 30px!important;
            max-height: 30px!important;
            font-size: 20px;
            font-weight: 500;
        }

        .sale-item-sec {
            height: 298px !important;
        }

        a#printBtn {
            padding: 0px 4px;
            color: black;
        }

        .head_btn {
            color: #fff !important;
        }

        .weight-details-modal-content {
            margin-top: 46px;
        }

        .select2-container--default .select2-selection--single {
            margin-bottom: 1px;
        }

        .select2-container .select2-selection--single {
            overflow: hidden;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: inline-block;
            width: 143px;
        }

        .select2-selection:focus {
            box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%);
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe;
            outline: 0;
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

        .do_table_area a {
            border: 1px solid gray;
            padding: 1px;
            color: black;
        }

        .do_table_area {
            max-height: 275px;
            min-height: 275px;
            overflow: scroll;
            overflow-x: hidden;
        }

        .do_table_area table  {
            border-collapse: separate;
            font-family: Arial, Helvetica, sans-serif!important;
        }

        .do_table_area table thead tr th {
            line-height: 17px;
            padding: 0px 3px !important;
        }

        .do_table_area table tbody tr td {
            line-height: 17px;
            padding: 0px 3px !important;
            font-size: 14px !important;
        }

        .do_table_area table tbody tr:hover {
            background: #dbdbdb !important;
        }

        input#search_vehicle_input {
            width: 69px;
            font-size: 9px;
            padding-left: 2px;
            font-weight: 700;
        }

        span#weight_connect {
            height: 30px;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Purchase By Scale - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <form id="complete_purchase_by_scale_form" action="{{ route('purchases.by.scale.completed') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="purchase_by_scale_id" id="purchase_by_scale_id">
                <input type="hidden" name="first_weight" id="first_weight">

                <section class="mt-5x">
                    <div class="container-fluid p-0">
                        <div>
                            <div class="main__content">
                                <div class="sec-name">
                                    <div class="name-head">
                                        <h6>{{ __('Purchase By Scale') }}</h6>
                                    </div>
                                    <x-all-buttons>
                                        <x-slot name="before">
                                            <a href="#" id="printChallanBtn" class="head_btn print_challan btn text-white btn-sm px-3"><span><i class="fa-thin fa-file-invoice  fa-2x"></i><br>@lang('menu.challan')</span></a>
                                            <a href="{{ route('purchases.by.scale.get.weight.details') }}" id="weightDetailsBtn" class="head_btn print_weight btn text-white btn-sm px-3"><span><i class="fa-thin fa-scale-balanced fa-2x"></i><br>@lang('menu.weight')</span></a>
                                            <a href="#" id="printWeightWithoutProductBtn" class="head_btn print_weight btn text-white btn-sm px-3"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print_weight')</span></a>
                                            <a href="#" id="printWeightWithProductBtn" class="head_btn print_weight btn text-white btn-sm px-3"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print_weighted_items')</span></a>
                                            <x-help-button />
                                        </x-slot>
                                    </x-all-buttons>
                                </div>
                            </div>
                            <div class="p-15 pb-0">
                                <div class="form_element rounded my-0">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.challan_no') </b> </label>
                                                    <div class="col-8">
                                                        <div style="position: relative;">
                                                            <div class="input-group">
                                                                <input type="text" name="search_challan_no" id="search_challan_no" class="form-control scanable" data-next="supplier_account_id" placeholder="Search Challan" autocomplete="off" autofocus>
                                                            </div>

                                                            <div class="invoice_search_result display-none">
                                                                <ul id="invoice_list" class="list-unstyled"></ul>
                                                            </div>
                                                        </div>
                                                        <span class="error error_search_challan_no"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.supplier') </b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div style="display: inline-block;" class="select-half">
                                                                <select required name="supplier_account_id" id="supplier_account_id" class="form-control select2" data-next="challan_date">
                                                                    <option value="">@lang('menu.select_supplier')</option>
                                                                    @foreach ($supplierAccounts as $supplier)
                                                                        <option value="{{ $supplier->id }}">
                                                                            {{ $supplier->name . '/' . $supplier->phone }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="style-btn">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button {{ !auth()->user()->can('supplier_add')? 'disabled_element': '' }}" id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="error error_supplier_account_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.voucher_no') </b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" placeholder="@lang('menu.voucher_no')" autocomplete="off" tabindex="-1">
                                                        <span class="error error_voucher_no"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.date') <span class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="date" class="form-control" id="date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" tabindex="-1">
                                                        <span class="error error_date"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.challan_date') </b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="text" name="challan_date" id="challan_date" class="form-control" data-next="vehicle_number" placeholder="@lang('menu.challan_date')" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.vehicle_no')</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="vehicle_number" id="vehicle_number" class="form-control fw-bold" data-next="driver_name" placeholder="@lang('menu.vehicle_number')" autocomplete="off">
                                                        <span class="error error_vehicle_number"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.driver_name')</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="driver_name" class="form-control" id="driver_name" data-next="do_driver_phone" placeholder="@lang('menu.driver_name')">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.driver_phone')</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="driver_phone" class="form-control" id="do_driver_phone" data-next="weight" placeholder="@lang('menu.driver_phone')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group ">
                                                    <label class="col-4"><b> @lang('menu.weight') </b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="weight_connect" style="cursor: pointer;"><i class="fas fa-plug"></i></span>
                                                            </div>
                                                            <input readonly type="number" step="any" name="weight" class="form-control weight_input" id="weight" value="0.00" placeholder="Weight" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.net_weight')</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="number" step="any" name="net_weight" class="form-control weight_input" id="net_weight" value="0.00" placeholder="@lang('menu.net_weight')" autocomplete="off" tabindex="-1">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.last_weight')</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="number" step="any" name="last_weight" class="form-control weight_input" id="last_weight" value="0.00" placeholder="@lang('menu.last_weight')" autocomplete="off" tabindex="-1">
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="col-12">
                                                            <a href="#" onclick="return false;" class="btn btn-sm bg-secondary text-white float-end display-none" id="save_car_blur_btn">@lang('menu.save_weight')</a>
                                                            <a href="{{ route('purchases.by.scale.save.weight') }}" class="btn btn-sm btn-success float-end" id="save_weight">@lang('menu.save_weight')</a>
                                                        </div>
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
                    <div class="sale-content">
                        <div class="row g-1 p-15">
                            <div class="col-md-8">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table sale-product-table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-startx">@lang('menu.sl')</th>
                                                                    <th class="text-startx">@lang('menu.item')</th>
                                                                    <th class="text-startx">@lang('menu.item_weight_by_scale')</th>
                                                                    <th class="text-center">@lang('menu.wastage')</th>
                                                                    <th class="text-center">@lang('menu.net_item_weight')</th>
                                                                    <th>@lang('menu.unit')</th>
                                                                    <th>@lang('menu.remark')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="item_list"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <input type="hidden" name="total_qty" id="total_qty">
                                <input type="hidden" name="total_item" id="total_item">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="do_table_area">
                                            <table class="table table-sm selectable">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('menu.vehicle_no'). <input type="text" id="search_vehicle_input" class="search_vehicle_input" placeholder="Search" autocomplete="off"></th>
                                                        <th>@lang('menu.supplier')</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="challan_table_rows_area"></tbody>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="" class="btn btn-sm btn-danger float-end d-inline" id="vehicle_done">@lang('menu.done')</a>
                                                <a href="" class="btn btn-sm btn-success float-end d-inline me-2" id="vehicle_refresh"> @lang('menu.refresh')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row p-15">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <div class="btn-box">
                                <button type="button" class="btn loading_button purchase_by_weight_loading_btn d-none"><i class="fas fa-spinner"></i></button>
                                <button type="submit" id="completed_btn" class="btn w-auto btn-success submit_button" data-status="1">@lang('menu.completed')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-shortcut-key-bar.shortcut-key-bar :items="[['key' => 'Ctrl + Enter', 'value' => __('menu.completed')], ['key' => 'Alt + S', 'value' => __('menu.add_supplier')], ['key' => 'Enter', 'value' => __('menu.connect_to_weight')], ['key' => 'Alt + W', 'value' => __('menu.save_weight')], ['key' => 'Alt + X', 'value' => __('menu.weight_details')], ['key' => 'Alt + Z', 'value' => __('menu.print_weight')], ['key' => 'Alt + V', 'value' => __('menu.print_weighted_items')]]">
    </x-shortcut-key-bar.shortcut-key-bar>

    @if (auth()->user()->can('supplier_add'))
        <!-- Add Supplier Modal -->
        <div class="modal fade" id="add_supplier_basic_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="add_supplier_detailed_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <!-- Add Supplier Modal End -->
    @endif

    <!-- Weight Details Modal -->
    <div class="modal fade" id="weightDetailsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!-- Weight Details Modal End-->
    <input type="hidden" name="search_product" class="scanable" id="search_product">
@endsection
@push('scripts')
    {{-- Weight Machine JS --}}
    <script src="{{ asset('js/weight-machine.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();
        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";

        var branch_name = "{{ json_decode($generalSettings->business, true)['shop_name'] }}";

        var lastCarWeight = 0;

        var ul = document.getElementById('invoice_list');
        var selectObjClassName = 'selected_challan';
        $('#search_challan_no').mousedown(function(e) {

            afterClickOrFocusOrderId();
        }).focus(function(e) {

            afterClickOrFocusOrderId();
        });

        function afterClickOrFocusOrderId() {

            ul = document.getElementById('invoice_list');
            selectObjClassName = 'selected_challan';
            $('#search_challan_no').val('');
            $('#challan_date').val('');
            $('#purchase_by_scale_id').val('');
            $('#voucher_no').val('');
            $('#vehicle_number').val('');
            $('#driver_name').val('');
            $('#driver_phone').val('');
            $('#weight').val(0);
            $('#first_weight').val('');
            $('#last_weight').val(0);
            $('#net_weight').val(0);
            $('#supplier_account_id').val('');
            $('#item_list').empty();

            $("#supplier_account_id").select2("destroy");
            $("#supplier_account_id").select2();

            $('#complete_purchase_by_scale_form')[0].reset();
        }

        $('#search_challan_no').on('input', function() {

            $('.invoice_search_result').hide();

            var key_word = $(this).val();

            if (key_word === '') {

                $('.invoice_search_result').hide();
                $('#invoice_list').empty();
                $('#purchase_by_scale_id').val('');
                return;
            }

            var url = "{{ route('common.ajax.call.purchase.weight.search.challan.list', ':key_word') }}";
            var route = url.replace(':key_word', key_word);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.invoice_search_result').hide();
                    } else {

                        if (key_word == '') {

                            return;
                        }

                        $('.invoice_search_result').show();
                        $('#invoice_list').html(data);
                    }
                }
            });
        });

        $(document).on('click', '#selected_challan', function(e) {
            e.preventDefault();

            var challan_no = $(this).data('challan_no');

            var purchase_by_scale_id = $(this).data('id');
            var supplier_account_id = $(this).data('supplier_account_id');
            var date = $(this).data('date');
            var challan_date = $(this).data('challan_date');
            var voucher_no = $(this).data('voucher_no');
            var vehicle_number = $(this).data('vehicle_number');
            var net_weight = $(this).data('net_weight');
            var first_weight = $(this).data('first_weight');
            var last_weight = $(this).data('last_weight');
            var driver_name = $(this).data('driver_name');
            var driver_phone = $(this).data('driver_phone');

            var vehicle_done_href = $(this).data('vehicle_done_href') != undefined ? $(this).data(
                'vehicle_done_href') : '#';

            $('#vehicle_done').attr('href', vehicle_done_href);

            var url = "{{ route('purchase.by.scale.weights.by.items', ':purchase_by_scale_id') }}";
            var route = url.replace(':purchase_by_scale_id', purchase_by_scale_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $('#weight').val(0);
                    $('#search_challan_no').val(challan_no);
                    $('#purchase_by_scale_id').val(purchase_by_scale_id);
                    $('#supplier_account_id').val(supplier_account_id).trigger('change');
                    $('#voucher_no').val(voucher_no);
                    $('#challan_no').val(challan_no);
                    $('#date').val(date);
                    $('#challan_date').val(challan_date);
                    $('#vehicle_number').val(vehicle_number);
                    $('#first_weight').val(parseFloat(first_weight).toFixed(2));
                    $('#last_weight').val(parseFloat(last_weight).toFixed(2));
                    $('#net_weight').val(parseFloat(net_weight).toFixed(2));
                    $('#driver_name').val(driver_name);
                    $('#driver_phone').val(driver_phone);

                    $('.invoice_search_result').hide();
                    $('#item_list').empty();

                    $('#item_list').html(data);

                    if (supplier_account_id) {

                        $('#weight').focus().select();
                    }

                    calculateTotalAmount();
                }
            });
        });

        $(document).on('input', '#do_input_qty', function(e) {

            calculateSaleProcessingItem();
        });

        function calculateTotalAmount() {

            var net_weights = document.querySelectorAll('#net_weight');

            var total_item = 0;
            var total_qty = 0;
            net_weights.forEach(function(net_weight) {

                var tr = net_weight.closest('tr');

                total_qty += parseFloat(net_weight.value);
                total_item += 1;
            });

            $('#total_item').val(parseFloat(total_item));
            $('#total_qty').val(parseFloat(total_qty).toFixed(2));
        }

        $(document).on('keyup', 'body', function(e) {

            if (e.keyCode == 13) {

                $('.' + selectObjClassName).click();
                $('.invoice_search_result').hide();
                $('.select_area').hide();
                $('#list').empty();
                $('#invoice_list').empty();
            }
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

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

        function afterCreateSale() {

            $('.loading_button').hide();
            $('.hidden').val(parseFloat(0).toFixed(2));
            $('#confirm_purchase_by_scale_form')[0].reset();
            $('#item_list').empty();
            $('#purchase_by_scale_id').val('');
        }

        $(document).on('click', '#weight_connect', function() {

            getWeight().then(weight => {

                var getCurrentCarWeight = weight;
                $('#weight').val(parseFloat(getCurrentCarWeight).toFixed(2));
                var firstWeight = $('#first_weight').val() ? $('#first_weight').val() : 0;
                var netWeight = parseFloat(firstWeight) - parseFloat(getCurrentCarWeight);
                var __newWeight = parseFloat(netWeight) < 0 ? parseFloat(getCurrentCarWeight) : parseFloat(
                    netWeight);
                $('#net_weight').val(parseFloat(__newWeight).toFixed(2));
            });
        });

        $(document).on('input', '#weight', function() {

            var weight = $(this).val();
            var getCurrentCarWeight = weight;
            var firstWeight = $('#first_weight').val() ? $('#first_weight').val() : 0;
            var netWeight = parseFloat(firstWeight) - parseFloat(getCurrentCarWeight);
            var __newWeight = parseFloat(netWeight) < 0 ? parseFloat(weight) : parseFloat(netWeight);
            $('#net_weight').val(parseFloat(__newWeight).toFixed(2));
        });

        // Make print
        $(document).on('keypress', '#weight', function(e) {

            if (e.which == 13) {

                $('#weight_connect').click();
            }
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#save_weight').on('click', function(e) {
            e.preventDefault();

            $(this).hide();
            $('#save_car_blur_btn').show();

            var supplier_account_id = $('#supplier_account_id').val();
            var search_challan_no = $('#search_challan_no').val();
            var voucher_no = $('#voucher_no').val();
            var challan_date = $('#challan_date').val();
            var vehicle_number = $('#vehicle_number').val();
            var driver_phone = $('#driver_phone').val();
            var weight = $('#weight').val();
            var net_weight = $('#net_weight').val();
            var last_weight = $('#last_weight').val();
            var voucher_id = $('#voucher_id').val();
            var date = $('#date').val();
            var purchase_by_scale_id = $('#purchase_by_scale_id').val();

            url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'post',
                data: {
                    supplier_account_id,
                    search_challan_no,
                    voucher_no,
                    challan_date,
                    vehicle_number,
                    driver_phone,
                    voucher_id,
                    date,
                    purchase_by_scale_id,
                    weight,
                    net_weight,
                    last_weight
                },
                success: function(data) {

                    $('.error').html('');
                    $('#save_weight').show();
                    $('#save_car_blur_btn').hide();

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('.loading_button').hide();
                        return;
                    }

                    toastr.success('Weight has been saved successfully.');

                    $('#first_weight').val(parseFloat(data.details.first_weight).toFixed(2));
                    $('#last_weight').val(parseFloat(data.details.net_weight).toFixed(2));
                    $('#last_weight').val(parseFloat(data.details.last_weight).toFixed(2));
                    $('#purchase_by_scale_id').val(data.details.id);
                    $('#voucher_no').val(data.details.voucher_no);

                    if (data.count > 0) {

                        var url = "{{ route('purchases.by.scale.get.weight.details') }}";
                        $.ajax({
                            url: url,
                            type: 'get',
                            data: {
                                purchase_by_scale_id
                            },
                            success: function(data) {

                                $('#weightDetailsModal').html(data);
                                $('#weightDetailsModal').modal('show');
                            }
                        });
                    }
                    getChallanList();
                },
                error: function(err) {

                    $('.error').html('');
                    $('#save_weight').show();
                    $('#save_car_blur_btn').hide();

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server Error. Please contact to the support team.');
                        return;
                    }

                    toastr.error('Please fill all required fields.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $('#vehicle_done').on('click', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            if (url == '#') {

                toastr.error('Do is not selected.');
                return;
            }

            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure to done the do?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {

                            $.ajax({
                                url: url,
                                type: 'post',
                                success: function(data) {

                                    if (!$.isEmptyObject(data.errorMsg)) {

                                        toastr.error(data.errorMsg);
                                        $('.loading_button').hide();
                                        return;
                                    }

                                    if (data) {

                                        getChallanList();
                                    }
                                },
                                error: function(err) {

                                    if (err.status == 0) {

                                        toastr.error(
                                            'Net Connetion Error. Reload This Page.');
                                        return;
                                    } else if (err.status == 500) {

                                        toastr.error(
                                            'Server Error. Please contact to the support team.'
                                        );
                                        return;
                                    }
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {}
                    }
                }
            });
        });

        $(document).on('click', '#vehicle_refresh', function(e) {
            e.preventDefault();

            getChallanList();
        });

        function getChallanList() {

            $('#search_vehicle_input').val('');
            search_table('');

            var url = "{{ route('common.ajax.call.purchase.weight.challan.list') }}";

            $.get(url, function(data) {

                $('#challan_table_rows_area').html(data);
            });
        }
        getChallanList();

        // Make print
        $(document).on('click', '#weightDetailsBtn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var purchase_by_scale_id = $('#purchase_by_scale_id').val();

            if (!purchase_by_scale_id) {
                toastr.error('Please select a weight voucher.');
                return;
            }

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    purchase_by_scale_id
                },
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $('#weightDetailsModal').html(data);
                    $('#weightDetailsModal').modal('show');
                }
            });
        });

        // Make print
        $(document).on('click', '#printChallanBtn', function(e) {
            e.preventDefault();

            var purchase_by_scale_id = $('#purchase_by_scale_id').val();

            if (!purchase_by_scale_id) {

                toastr.error('Please select a weight voucher.');
                return;
            }

            var url = "{{ route('purchase.by.scale.print.weight.challan', ':purchase_by_scale_id') }}";
            var route = url.replace(':purchase_by_scale_id', purchase_by_scale_id);

            $.ajax({
                url: route,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

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
            });
        });

        // Make print
        $(document).on('click', '#printWeightWithoutProductBtn', function(e) {
            e.preventDefault();

            var purchase_by_scale_id = $('#purchase_by_scale_id').val();

            if (!purchase_by_scale_id) {
                toastr.error('Please select a weight voucher.');
                return;
            }

            var url = "{{ route('purchase.by.scale.print.weight', ['without_product', ':purchase_by_scale_id']) }}";
            var route = url.replace(':purchase_by_scale_id', purchase_by_scale_id);

            $.ajax({
                url: route,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

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
            });
        });

        $(document).on('click', '#printWeightWithProductBtn', function(e) {
            e.preventDefault();

            var purchase_by_scale_id = $('#purchase_by_scale_id').val();

            if (!purchase_by_scale_id) {
                toastr.error('Please select a weight voucher.');
                return;
            }

            var url = "{{ route('purchase.by.scale.print.weight', ['with_product', ':purchase_by_scale_id']) }}";
            var route = url.replace(':purchase_by_scale_id', purchase_by_scale_id);

            $.ajax({
                url: route,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

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
            });
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#completed_btn').click();
                return false;
            } else if (e.altKey && e.which == 83) {

                $('#addSupplier').click();
                return false;
            } else if (e.altKey && e.which == 87) {

                $('#save_weight').click();
                return false;
            } else if (e.altKey && e.which == 88) {

                $('#weightDetailsBtn').click();
                return false;
            } else if (e.altKey && e.which == 90) {

                $('#printWeightWithoutProductBtn').click();
                return false;
            } else if (e.altKey && e.which == 86) {

                $('#printWeightWithProductBtn').click();
                return false;
            } else if (e.which == 27) {

                $('.invoice_search_result').hide();
                $('#invoice_list').empty();
                return false;
            }
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
            } else {

                $(this).prop('type', 'button');
            }
        });

        //Add purchase request by ajax
        $('#complete_purchase_by_scale_form').on('submit', function(e) {
            e.preventDefault();

            $('.purchase_by_weight_loading_btn').show();
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
                    $('.error').html('');
                    $('.purchase_by_weight_loading_btn').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else if (data.successMsg) {

                        $('#complete_purchase_by_scale_form')[0].reset();
                        $('#item_list').empty();

                        $("#supplier_account_id").select2("destroy");
                        $("#supplier_account_id").select2();
                        toastr.success(data.successMsg);
                        getChallanList();
                    }
                },
                error: function(err) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.purchase_by_weight_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
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

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                $('#' + nextId).focus().select();
            }
        });

        $('#addSupplier').on('click', function() {

            $.get("{{ route('contacts.supplier.create.basic.modal') }}", function(data) {

                $('#add_supplier_basic_modal').html(data);
                $('#add_supplier_basic_modal').modal('show');

            });
        });

        $('#search_vehicle_input').keyup(function() {

            search_table($(this).val());
        });

        function search_table(value) {

            $('#challan_table_rows_area tr').each(function() {

                var found = 'false';
                $(this).each(function() {

                    if ($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {

                        found = 'true';
                    }
                });

                if (found == 'true') {

                    $(this).show();
                } else {

                    $(this).hide();
                }
            });
        }

        $(document).on('click', function(e) {

            if ($(e.target).closest(".invoice_search_result").length === 0) {

                $('.invoice_search_result').hide();
                $('#invoice_list').empty();
            }
        });
    </script>

    <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script>
@endpush
