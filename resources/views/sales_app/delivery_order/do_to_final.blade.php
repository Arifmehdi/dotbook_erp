@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .selected_do {
            background-color: #645f61;
            color: #fff !important;
        }

        #do_table_rows_area .selected_do {
            background: #c9ccf7 !important;
            color: #000 !important;
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

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 318%;
            z-index: 9999999;
            padding: 0;
            left: 0%;
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

        input[type=number]#quantity::-webkit-inner-spin-button,
        input[type=number]#quantity::-webkit-outer-spin-button {
            opacity: 1;
            margin: 0;
        }

        .process_order_section {
            border: 1px solid #e3e3e3;
            padding: 6px 7px;
            border-radius: 5px;
        }

        .process_order_section label {
            font-size: 11px !important;
            margin: 0px !important;
            padding: 0px !important;
        }

        .do_input_group span {
            cursor: pointer;
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

        .do_table_area a {
            border: 1px solid gray;
            padding: 1px;
            color: black;
        }

        .do_table_area {
            max-height: 324px;
            min-height: 324px;
            overflow: scroll;
            overflow-x: hidden;
        }

        .sale-item-sec {
            max-height: 277px !important;
            min-height: 277px !important;
        }

        .do_table_area table {
            border-collapse: separate;
            font-family: Arial, Helvetica, sans-serif!important;
        }

        .do_table_area table tbody tr:hover {
            background: #dbdbdb !important;
        }

        .do_table_area table thead tr th {
            line-height: 17px;
            padding: 0px 10px!important;
        }

        .do_table_area table tbody tr td {
            font-size: 14px !important;
            line-height: 17px;
            padding: 0px 3px!important;
        }

        input#search_vehicle_input {
            width: 69px;
            font-size: 10px;
            padding-left: 2px;
            font-weight: 700;
        }

        input#edo_quantity {
            font-size: 12px;
        }

        .total_item_and_quantity_area input {
            font-size: 15px;
        }

        .do_item_table tfoot {
            line-height: 0px !important
        }

        span#do_car_weight_connect {
            height: 30px;
        }

        span#do_weight_connect {
            height: 30px;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'D/o To Invoice - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            {{-- <button type="button" onclick="fetchWeight()" class="btn btn-primary mt-3">Get Weight</button> --}}
            <form id="add_sale_form" action="{{ route('sales.delivery.order.to.final.confirm') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="sale_id" id="sale_id">
                <input type="hidden" name="weight_id" id="weight_id" value="">
                <input type="hidden" name="status" id="status" value="7">

                <section class="mt-5x">
                    <div class="container-fluid p-0">
                        <div>
                            <div class="main__content">
                                <div class="sec-name">
                                    <div class="name-head">
                                        <h6>@lang('menu.do_to_invoice') </h6>
                                    </div>

                                    <x-all-buttons>
                                        <a href="{{ route('sales.delivery.print.invoice') }}" id="printBtn" class="head_btn print_invoice btn text-white btn-sm px-3"><span><i class="fa-thin fa-receipt fa-2x"></i><br> @lang('menu.invoice')</span></a>
                                        <a href="{{ route('sales.delivery.print.challan') }}" id="printBtn" class="head_btn print_challan btn text-white btn-sm px-3"><span><i class="fa-thin fa-file-invoice  fa-2x"></i><br>@lang('menu.challan')</span></a>
                                        <a href="{{ route('sales.delivery.get.weight.details') }}" id="weightDetailsBtn" class="head_btn print_weight btn text-white btn-sm px-3"><span><i class="fa-thin fa-scale-balanced fa-2x"></i><br>@lang('menu.weight')</span></a>
                                        <a href="{{ route('sales.delivery.print.gate.pass') }}" id="printBtn" class="head_btn print_gate_pass btn text-white btn-sm px-3"><span><i class="fa-thin fa-person-to-door fa-2x"></i><br>@lang('menu.gate_pass')</span></a>
                                        <a href="{{ route('sales.print.do') }}" id="printBtn" class="head_btn print_do btn text-white btn-sm px-3"><span><i class="fa-thin fa-memo-circle-check fa-2x"></i><br>DO</span></a>
                                        <a href="{{ route('sales.delivery.order.print.bills.against.do') }}" id="printBtn" class="head_btn print_do_bills btn text-white btn-sm px-3"><span><i class="fa-thin fa-ballot fa-2x"></i><br>@lang('menu.bill')</span></a>
                                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                                    </x-all-buttons>
                                </div>
                            </div>
                            <div class="p-15 pb-0">
                                <div class="form_element rounded my-0">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.do_id')</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <div style="position: relative;">
                                                            <div class="input-group">
                                                                <input type="text" name="search_do_id" id="search_do_id" class="form-control scanable fw-bold" data-next="do_to_inv_challan_no" placeholder="Search DO ID" autocomplete="off" autofocus>

                                                                <div class="input-group-prepend">
                                                                    <span id="edit_do" data-href="{{ route('sales.edit.do.modal') }}" class="input-group-text add_button {{ !auth()->user()->can('do_edit')? 'disabled_element': '' }}"><i class="fa-solid fa-pen-to-square input_f"></i></span>
                                                                </div>
                                                            </div>

                                                            <div class="invoice_search_result display-none">
                                                                <ul id="invoice_list" class="list-unstyled"></ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.customer') </b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="hidden" name="customer_account_id" id="customer_account_id">
                                                            <input readonly type="text" class="form-control fw-bold" id="customer" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.challan_no')</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input required type="text" name="do_to_inv_challan_no" class="form-control" id="do_to_inv_challan_no" data-next="date" placeholder="@lang('menu.hand_challan_no')">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.search_invoice')</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="text" id="search_invoice" class="form-control" placeholder="Search Invoice" autocomplete="off">
                                                            <span id="previous_data_btn" data-href="{{ route('sales.delivery.order.previous.invoice') }}" class="fa-solid fa-receipt mt-1 ms-2 me-1" style="font-size: 15px; cursor:pointer;"></span>
                                                            <span id="previous_data_btn" data-href="{{ route('sales.delivery.order.previous.weight') }}" class="fa-regular fa-scale-balanced mt-1 ms-1" style="font-size: 15px; cursor:pointer;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.invoice_id') </b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control fw-bold" placeholder="@lang('menu.invoice_id')" autocomplete="off" tabindex="-1">
                                                        <span class="error error_invoice_id"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.date') <span class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="date" class="form-control" id="date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" data-next="challan_date" autocomplete="off">
                                                        <span class="error error_date"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.challan_date')</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="text" name="do_to_inv_challan_date" id="challan_date" class="form-control" data-next="shipping_address" placeholder="Hand Challan Date" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Ship. Address') }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="text" name="shipping_address" id="shipping_address" class="form-control" data-next="do_car_number" placeholder="{{ __('Shipping Address') }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.vehicle_no')</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input type="text" name="do_car_number" id="do_car_number" class="form-control fw-bold" data-next="do_driver_name" placeholder="DO @lang('menu.vehicle_number')" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.driver_name') </b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="do_driver_name" class="form-control" id="do_driver_name" data-next="do_driver_phone" placeholder="@lang('menu.driver_name')">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.driver_phone')</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="do_driver_phone" class="form-control" id="do_driver_phone" data-next="receiver_phone" placeholder="@lang('menu.driver_phone')">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Receiver Phone') }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="text" name="receiver_phone" id="receiver_phone" class="form-control" data-next="do_car_weight" placeholder="{{ __('Item Receiver Phone No.') }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="input-group ">
                                                    <label class="col-4"><b>@lang('menu.vehicle_weight')</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="do_car_weight_connect" style="cursor: pointer;"><i class="fas fa-plug"></i></span>
                                                            </div>
                                                            <input readonly type="number" step="any" name="do_car_weight" class="form-control weight_input" id="do_car_weight" value="0.00" placeholder="DO Car Weight" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.net_weight') </b></label>
                                                    <div class="col-8">
                                                        <input readonly type="number" step="any" name="do_car_net_weight" class="form-control weight_input" id="do_car_net_weight" value="0.00" placeholder="DO Car Weight" autocomplete="off" tabindex="-1">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.last_weight') </b></label>

                                                    <div class="col-8">
                                                        <input readonly type="number" step="any" name="do_car_last_weight" class="form-control weight_input" id="do_car_last_weight" value="0.00" placeholder="@lang('menu.last_weight')" autocomplete="off" tabindex="-1">
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="col-12">
                                                            <a href="#" onclick="return false;"class="btn btn-sm bg-secondary text-white float-end display-none" id="save_car_blur_btn">@lang('menu.save_vehicle')</a>
                                                            <a href="{{ route('sales.delivery.save.car') }}" class="btn btn-sm btn-success float-end" id="save_car">@lang('menu.save_vehicle')</a>
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="process_order_section">
                                                    <div class="row align-items-end g-2">
                                                        <div class="col-xl-11">
                                                            <div class="hidden_input">
                                                                <input type="hidden" id="do_sale_product_id">
                                                                <input type="hidden" id="do_product_name">
                                                                <input type="hidden" id="do_is_manage_stock">
                                                                <input type="hidden" id="do_product_id">
                                                                <input type="hidden" id="do_variant_id">
                                                                <input type="hidden" id="do_unit">
                                                                <input type="hidden" id="do_price_type">
                                                                <input type="hidden" id="do_pr_amount">
                                                                <input type="hidden" id="do_unit_cost_inc_tax">
                                                                <input type="hidden" id="do_unit_discount_type">
                                                                <input type="hidden" id="do_unit_discount_amount">
                                                                <input type="hidden" id="do_unit_tax_type">
                                                                <input type="hidden" id="do_unit_tax_amount">
                                                                <input type="hidden" id="do_unit_price_exc_tax">
                                                                <input type="hidden" id="do_unit_price_inc_tax">
                                                                <input type="hidden" id="do_unit_discount">
                                                                <input type="hidden" id="do_unit_tax_percent">
                                                                <input type="hidden" id="do_subtotal">
                                                            </div>

                                                            <div class="row align-items-end g-2">
                                                                <div class="col-xl-3 col-md-6">
                                                                    <label><strong>@lang('menu.warehouse') </strong></label>
                                                                    <select name="warehouse_id" class="form-control form-select" id="warehouse_id">
                                                                        <option value="">@lang('menu.select_warehouse')</option>
                                                                        @php
                                                                            $warehouseCount = count($warehouses);
                                                                        @endphp
                                                                        @foreach ($warehouses as $warehouse)
                                                                            <option {{ $warehouseCount == 1 ? 'SELECTED' : '' }} data-w_name="{{ $warehouse->name . '/' . $warehouse->code }}" value="{{ $warehouse->id }}">
                                                                                {{ $warehouse->name . '/' . $warehouse->code }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-xl-3 col-md-6">
                                                                    <label><strong>@lang('menu.item_name') </strong></label>
                                                                    <input readonly type="text" id="do_item_name" class="form-control weight_input" placeholder="@lang('menu.item_name')" tabindex="-1">
                                                                </div>

                                                                <div class="col-xl-3 col-md-6">
                                                                    <label><strong>@lang('menu.do_left_qty_weight') </strong></label>
                                                                    <input readonly type="number" id="do_left_qty" step="any" class="form-control weight_input" value="0.00" tabindex="-1">
                                                                </div>

                                                                <div class="col-xl-3 col-md-6">
                                                                    <label><strong>@lang('short.delivered_qty')/@lang('menu.weight')
                                                                        </strong></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend do_input_group">
                                                                            <span class="input-group-text" data-demo_value="0" id="do_weight_connect">
                                                                                <i class="fas fa-plug"></i>
                                                                            </span>
                                                                        </div>
                                                                        <input type="number" id="do_input_qty" step="any" class="form-control weight_input" value="0.00" autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-1">
                                                            <a href="#" id="add_product" class="btn btn-sm btn-success">@lang('menu.add')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table sale-product-table do_item_table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-start">@lang('menu.item')</th>
                                                                    <th class="text-start">@lang('menu.stock_location')</th>
                                                                    <th class="text-start">@lang('short.delivery_order_left_qty')</th>
                                                                    <th class="text-start">@lang('menu.deliver_quantity')</th>
                                                                    <th class="text-start">@lang('menu.unit')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_list"></tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="2">@lang('menu.total') :</th>
                                                                    <th><span class="text-start d-block" id="total_left_qty">0.00</span></th>
                                                                    <th><span class="text-start d-block" id="total_deliver_qty">0.00</span></th>
                                                                    <th>---</th>
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

                            <div class="col-md-4">
                                <input type="hidden" name="total_qty" id="total_qty">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="net_total_amount" id="net_total_amount">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="do_table_area">
                                            <table class="table table-sm selectable">
                                                <thead>
                                                    <tr>
                                                        <th class="flex_th">@lang('menu.vehicle_no'). <input type="text" id="search_vehicle_input" class="search_vehicle_input" placeholder="Search" autocomplete="off"></th>
                                                        <th>@lang('menu.do_id')</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="do_table_rows_area"></tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="" class="btn btn-sm btn-danger float-end d-inline" id="do_done">@lang('menu.done')</a>
                                                <a href="" class="btn btn-sm btn-success float-end d-inline me-2" id="vehicle_refresh">
                                                    @lang('menu.refresh')
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row justify-content-center mt-1 mx-1">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="save_and_print" class="btn btn-success submit_button me-2" data-status="1" value="save_and_print">@lang('menu.save_and_print')</button>
                            <button type="submit" id="save" class="btn btn-success submit_button" data-status="1" value="save">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editDoModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <input type="hidden" id="search_product">
    </div>

    <div class="modal fade" id="weightDetailsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

@endsection
@push('scripts')
    {{-- Weight Machine JS --}}
    <script src="{{ asset('js/weight-machine.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/DateComparer.js') }}"></script>

    <script>
        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";

        var branch_name = "{{ json_decode($generalSettings->business, true)['shop_name'] }}";

        var lastCarWeight = 0;

        var ul = document.getElementById('invoice_list');
        var selectObjClassName = 'selected_do';

        var tbody = '';
        var trSelectObjClassName = 'selected_do';

        $('#search_do_id').mousedown(function(e) {

            afterClickOrFocusOrderId();
        }).focus(function(e) {

            afterClickOrFocusOrderId();
        });

        $('#search_vehicle_input').mousedown(function(e) {

            tbody = document.getElementById('do_table_rows_area');
            trSelectObjClassName = 'selected_do';
            ul = '';
        }).focus(function(e) {

            tbody = document.getElementById('do_table_rows_area');
            trSelectObjClassName = 'selected_do';
            ul = '';
        });

        function afterClickOrFocusOrderId() {

            ul = document.getElementById('invoice_list');
            selectObjClassName = 'selected_do';
            $('#search_do_id').val('');
            $('#sale_id').val('');
            $('#invoice_id').val('');
            $('#do_car_number').val('');
            $('#do_driver_name').val('');
            $('#do_driver_phone').val('');
            $('#do_car_weight').val(0);
            $('#do_car_last_weight').val(0);
            $('#do_car_net_weight').val(0);
            $('#customer_account_id').val('');
            $('#total_left_qty').html(parseFloat(0).toFixed(2));
            $('#total_deliver_qty').html(parseFloat(0).toFixed(2));
            $('#customer').val('');
            $('#sale_list').empty();

            tbody = '';
        }

        $('#search_do_id').on('input', function() {

            $('.invoice_search_result').hide();

            var key_word = $(this).val();

            if (key_word === '') {

                $('.invoice_search_result').hide();
                $('#sale_id').val('');
                return;
            }

            var url = "{{ route('common.ajax.call.search.sales.do.ids', ':key_word') }}";
            var route = url.replace(':key_word', key_word);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.invoice_search_result').hide();
                    } else {

                        $('.invoice_search_result').show();
                        $('#invoice_list').html(data);
                    }
                }
            });
        });

        $(document).on('click', '#selected_do', function(e) {
            e.preventDefault();

            $('#do_car_weight').val(0);
            var expire_date = $(this).data('expire_date');

            var isExpired = DateComparer.isExpired(expire_date, 'Y-m-d');

            if (isExpired) {

                toastr.error('Order date is expired!');
                $('#search_do_id').focus().select();
                return;
            }

            var search_do_id = $(this).data('do_id');

            var sale_id = $(this).data('sale_id');
            var weight_id = $(this).data('weight_id') != undefined ? $(this).data('weight_id') : null;

            var customer_account_id = $(this).data('customer_account_id');

            var customer = $(this).data('customer');
            var total_item = $(this).data('total_item');
            var net_total_amount = $(this).data('net_total_amount');
            var sale_note = $(this).data('sale_note');
            var invoice_id = $(this).data('invoice_id');
            var do_car_number = $(this).data('do_car_number');
            var do_net_weight = $(this).data('do_net_weight');
            var do_car_last_weight = $(this).data('do_car_last_weight');
            var do_driver_name = $(this).data('do_driver_name');
            var do_driver_phone = $(this).data('do_driver_phone');
            var shipping_address = $(this).data('shipping_address');
            var receiver_phone = $(this).data('receiver_phone');

            var do_done_href = $(this).data('do_done_href') != undefined ? $(this).data('do_done_href') : '#';

            $('#do_done').attr('href', do_done_href);

            var url = "{{ route('common.ajax.call.get.do.products', [':sale_id', ':weight_id']) }}";
            var route = url.replace(':sale_id', sale_id);
            route = route.replace(':weight_id', weight_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('#search_do_id').focus().select();
                        return;
                    }

                    if (do_car_number) {

                        $('#do_car_weight').focus();
                    }

                    $('#search_do_id').val(search_do_id.trim());
                    $('#sale_id').val(sale_id);
                    $('#weight_id').val(weight_id);
                    $('#customer_account_id').val(customer_account_id);
                    $('#customer').val(customer);
                    $('#invoice_id').val(data.invoice_id);
                    $('#do_car_number').val(do_car_number);
                    $('#sale_note').val(sale_note);
                    $('#do_car_net_weight').val(parseFloat(do_net_weight).toFixed(2));
                    $('#do_car_last_weight').val(parseFloat(do_car_last_weight).toFixed(2));
                    $('#do_driver_name').val(do_driver_name);
                    $('#do_driver_phone').val(do_driver_phone);
                    $('#shipping_address').val(shipping_address);
                    $('#receiver_phone').val(receiver_phone);

                    $('.invoice_search_result').hide();
                    $('#sale_list').empty();

                    $.each(data.do_products, function(key, do_product) {

                        var variant = do_product.variant_name != null ? ' - ' + do_product
                            .variant_name : '';
                        var tr = '';
                        tr += '<tr id="edit_product" style="cursor: pointer;">';
                        tr += '<td class="text-start">';
                        tr += '<a href="#" id="item_name" style="color:#000;">';
                        tr += '<span class="product_name">' + do_product.product_name + variant + '</span>';
                        tr += '</a>';
                        tr += '<input type="hidden" name="sale_product_ids[]" class="sale_product_id" id="' + do_product.id + '" value="' + do_product.id + '">';
                        tr += '<input type="hidden" name="is_manage_stocks[]" id="is_manage_stock" value="' + do_product.is_manage_stock + '">';
                        tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + do_product.product_id + '">';
                        tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + (do_product.variant_id ? do_product.variant_id : 'noid') + '">';
                        tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + (do_product.tax_ac_id != null ? do_product.tax_ac_id : '') + '">';
                        tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + do_product.unit_tax_percent + '">';
                        tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(do_product.unit_tax_amount).toFixed(2) + '">';
                        tr += '<input type="hidden" name="unit_discount_types[]" value="' + do_product.unit_discount_type + '" id="unit_discount_type">';
                        tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + do_product.unit_discount + '">';
                        tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + do_product.unit_discount_amount + '">';
                        tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + (do_product.variant_cost_with_tax == null ? do_product.product_cost_with_tax : do_product.variant_cost_with_tax) + '">';
                        tr += '<input type="hidden" id="tax_type" value="' + do_product.tax_type + '">';
                        tr += '<input type="hidden" name="descriptions[]">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + (do_product.stock_warehouse_id != null ? do_product.stock_warehouse_id : '') + '">';

                        if (do_product.stock_warehouse_id != null) {

                            tr += '<span id="span_stock_location_name">' + do_product.w_name + '/' + do_product.w_code + '</span>';
                        } else {

                            tr += '<span id="span_stock_location_name">' + branch_name + '</span>';
                        }

                        tr += '</td>';
                        tr += '<td>';
                        tr += '<span id="span_do_left_quantity" class="fw-bold">' + parseFloat(do_product.do_left_qty).toFixed(2) + '</span>';
                        tr += '<input readonly type="hidden" id="do_left_quantity" value="' + parseFloat(do_product.do_left_qty).toFixed(2) + '" tabindex="-1">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<span id="span_quantity" class="fw-bold">0.00</span>';
                        tr += '<input type="hidden" name="quantities[]" id="quantity" value="0.00">';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<span id="span_unit" class="fw-bold">' + (do_product.base_unit_name != null ? do_product.base_unit_name : do_product.sale_unit_name) + '</span>';
                        tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + (do_product.base_unit_id != null ? do_product.base_unit_id : do_product.sale_unit_id) + '">';
                        tr += '<input type="hidden" name="price_types[]" id="price_type" value="' + do_product.price_type + '" tabindex="-1">';
                        tr += '<input type="hidden" name="pr_amounts[]" id="pr_amount" value="' + do_product.pr_amount + '" tabindex="-1">';
                        tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(do_product.unit_price_exc_tax).toFixed(2) + '" tabindex="-1">';
                        tr += '<input type="hidden" name="unit_prices[]" id="unit_price" value="' + parseFloat(do_product.unit_price_inc_tax).toFixed(2) + '" tabindex="-1">';
                        tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="0.00" tabindex="-1">';
                        tr += '</td>';

                        tr += '</tr>';
                        $('#sale_list').prepend(tr);

                        calculateTotalAmount();
                        clearOrderItemProcessSection();
                    })
                },
                error: function(data) {

                    toastr.error('D/O Not Found.');
                }
            });
        });

        function calculateSaleProcessingItem() {

            var do_input_qty = $('#do_input_qty').val() ? $('#do_input_qty').val() : 0;
            var do_unit_price_exc_tax = $('#do_unit_price_exc_tax').val() ? $('#do_unit_price_exc_tax').val() : 0;
            var do_unit_discount_type = $('#do_unit_discount_type').val();
            var do_unit_discount = $('#do_unit_discount').val() ? $('#do_unit_discount').val() : 0;
            var do_unit_tax_type = $('#do_unit_tax_type').val();
            var do_unit_tax_percent = $('#do_unit_tax_percent').val() ? $('#do_unit_tax_percent').val() : 0;

            var unitDiscountAmount = 0;

            if (do_unit_discount_type == 2) {

                unitDiscountAmount = (parseFloat(do_unit_price_exc_tax) / 100 * parseFloat(do_unit_discount));
            } else {

                unitDiscountAmount = parseFloat(do_unit_discount);
            }

            var unitPriceWithDiscount = parseFloat(do_unit_price_exc_tax) - parseFloat(unitDiscountAmount);

            var unitTaxAmount = parseFloat(unitPriceWithDiscount) / 100 * parseFloat(do_unit_tax_percent);

            if (do_unit_tax_type == 2) {

                var inclusiveTax = 100 + parseFloat(do_unit_tax_percent);
                var calc = parseFloat(unitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
                calsUninTaxAmount = parseFloat(unitPriceWithDiscount) - parseFloat(calc);
            }

            var unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(unitTaxAmount);
            var subTotal = parseFloat(do_input_qty) * parseFloat(unitPriceIncTax);

            $('#do_unit_tax_amount').val(parseFloat(unitTaxAmount).toFixed(2));
            $('#do_unit_discount_amount').val(parseFloat(unitDiscountAmount).toFixed(2));
            $('#do_unit_price_inc_tax').val(parseFloat(unitPriceIncTax).toFixed(2));
            $('#do_subtotal').val(parseFloat(subTotal).toFixed(2));
        }

        $(document).on('keypress click', '#warehouse_id', function(e) {

            if (e.which == 0) {

                $('#do_input_qty').focus().select();
            }
        });

        $(document).on('input keypress', '#do_input_qty', function(e) {

            calculateSaleProcessingItem();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#add_product').focus();
                }
            }
        });

        $(document).on('input', '#do_unit_price_exc_tax', function(e) {

            calculateSaleProcessingItem();
        });

        $(document).on('input', '#do_unit_discount', function(e) {

            calculateSaleProcessingItem();
        });

        $(document).on('input', '#do_unit_tax_percent', function(e) {

            calculateSaleProcessingItem();
        });

        $(document).on('click', '#add_product', function(e) {
            e.preventDefault();

            var do_sale_product_id = $('#do_sale_product_id').val();
            var do_input_qty = $('#do_input_qty').val();
            var do_left_qty = $('#do_left_qty').val();

            if (do_sale_product_id == '') {

                toastr.error("{{ __('Please select an item.') }}");
                return;
            } else if (parseFloat(do_input_qty) > parseFloat(do_left_qty)) {

                toastr.error("{{ __('Deliver quantity must not be greater than do left quantity.') }}");
                return;
            }

            selectProduct();
        });

        function selectProduct() {

            var warehouse_id = $('#warehouse_id').val();
            var warehouse_name = $('#warehouse_id').find('option:selected').data('w_name');

            var sale_product_id = $('#do_sale_product_id').val();
            var do_left_qty = $('#do_left_qty').val();
            var input_quantity = $('#do_input_qty').val();
            var product_id = $('#do_product_id').val();
            var variant_id = $('#do_variant_id').val();
            var is_manage_stock = $('#do_is_manage_stock').val();
            var product_name = $('#do_product_name').val();
            var price_type = $('#do_price_type').val();
            var pr_amount = $('#do_pr_amount').val();
            var product_cost_inc_tax = $('#do_unit_cost_inc_tax').val();
            var product_price_exc_tax = $('#do_unit_price_exc_tax').val();
            var product_price_inc_tax = $('#do_unit_price_inc_tax').val();
            var unit_discount_type = $('#do_unit_discount_type').val();
            var unit_discount = $('#do_unit_discount').val();
            var unit_discount_amount = $('#do_unit_discount_amount').val();
            var p_tax_percent = $('#do_unit_tax_percent').val();
            var p_tax_amount = $('#do_unit_tax_amount').val();
            var p_tax_type = $('#do_unit_tax_type').val();
            var subtotal = $('#do_subtotal').val();

            var route = '';
            if (variant_id) {

                var url = "{{ route('general.product.search.variant.product.stock', [':product_id', ':variant_id', ':warehouse_id']) }}";
                route = url.replace(':product_id', product_id);
                route = route.replace(':variant_id', variant_id);
                route = route.replace(':warehouse_id', warehouse_id);
            } else {

                var url = "{{ route('general.product.search.single.product.stock', [':product_id', ':warehouse_id']) }}";
                route = url.replace(':product_id', product_id);
                route = route.replace(':warehouse_id', warehouse_id);
            }

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(data) {

                    if ($.isEmptyObject(data.errorMsg)) {

                        var stockLocationMessage = warehouse_id ? ' in selected warehouse' : ' in the company';
                        if (parseFloat(input_quantity) > data.stock) {

                            toastr.error('Current stock is ' + parseFloat(data.stock) + stockLocationMessage);
                            return;
                        }

                        var tr = $('#' + sale_product_id).closest('tr');
                        tr.find('.product_name').html(product_name);
                        tr.find('.sale_product_id').val(sale_product_id);
                        tr.find('#is_manage_stock').val(is_manage_stock);
                        tr.find('#product_id').val(product_id);
                        tr.find('#variant_id').val(variant_id ? variant_id : 'noid');
                        tr.find('#unit_tax_percent').val(p_tax_percent);
                        tr.find('#unit_tax_amount').val(parseFloat(p_tax_amount).toFixed(2));
                        tr.find('#unit_discount_type').val(unit_discount_type);
                        tr.find('#unit_discount').val(unit_discount);
                        tr.find('#unit_discount_amount').val(unit_discount_amount);
                        tr.find('#unit_cost_inc_tax').val(product_cost_inc_tax);
                        tr.find('#tax_type').val(p_tax_type);
                        tr.find('#warehouse_id').val(warehouse_id);
                        tr.find('#span_stock_location_name').html(warehouse_name ? warehouse_name : branch_name);
                        tr.find('#span_do_left_quantity').html(parseFloat(do_left_qty).toFixed(2));
                        tr.find('#do_left_quantity').val(parseFloat(do_left_qty).toFixed(2));
                        tr.find('#span_quantity').html(parseFloat(input_quantity).toFixed(2));
                        tr.find('#quantity').val(parseFloat(input_quantity).toFixed(2));
                        tr.find('#price_type').val(price_type);
                        tr.find('#pr_amount').val(parseFloat(pr_amount).toFixed(2));
                        tr.find('#unit_price_exc_tax').val(parseFloat(product_price_exc_tax).toFixed(2));
                        tr.find('#unit_price').val(parseFloat(product_price_inc_tax).toFixed(2));
                        tr.find('#subtotal').val(parseFloat(subtotal).toFixed(2));

                        calculateTotalAmount();
                        clearOrderItemProcessSection();
                    } else {

                        toastr.error(data.errorMsg);
                    }
                }
            });
        }

        $(document).on('click', '#edit_product', function(e) {
            e.preventDefault();

            $('#show_cost_section').hide();
            // var parentTableRow = $(this).closest('tr');
            var parentTableRow = $(this);
            // tableRowIndex = parentTableRow.index();
            var do_left_quantity = parentTableRow.find('#do_left_quantity').val() ? parentTableRow.find('#do_left_quantity').val() : 0;
            var net_quantity = parentTableRow.find('#net_quantity').val() ? parentTableRow.find('#net_quantity').val() : 0;
            var rest_quantity = parentTableRow.find('#rest_quantity').val() ? parentTableRow.find('#rest_quantity').val() : 0;
            var warehouse_id = parentTableRow.find('#warehouse_id').val();
            var quantity = parentTableRow.find('#quantity').val();
            var sale_product_id = parentTableRow.find('.sale_product_id').val();
            var product_id = parentTableRow.find('#product_id').val();
            var variant_id = parentTableRow.find('#variant_id').val() == 'noid' ? '' : parentTableRow.find('#variant_id').val();
            var unit_cost_inc_tax = parentTableRow.find('#unit_cost_inc_tax').val();
            var product_name = parentTableRow.find('.product_name').html();
            var product_variant = parentTableRow.find('.product_variant').html();
            var product_code = parentTableRow.find('.product_code').html();
            var price_type = parentTableRow.find('#price_type').val();
            var pr_amount = parentTableRow.find('#pr_amount').val();
            var unit_price_exc_tax = parentTableRow.find('#unit_price_exc_tax').val();
            var unit_price_inc_tax = parentTableRow.find('#unit_price').val();
            var unit_tax_percent = parentTableRow.find('#unit_tax_percent').val();
            var unit_tax_amount = parentTableRow.find('#unit_tax_amount').val();
            var unit_tax_type = parentTableRow.find('#tax_type').val();
            var unit_discount_type = parentTableRow.find('#unit_discount_type').val();
            var unit_discount = parentTableRow.find('#unit_discount').val();
            var unit_discount_amount = parentTableRow.find('#unit_discount_amount').val();
            var product_unit = parentTableRow.find('#unit').val();
            var subTotal = parentTableRow.find('#subtotal').val();
            var is_manage_stock = parentTableRow.find('#is_manage_stock').val();
            // Set modal heading
            var heading = product_name;

            @if ($warehouseCount != 1)

                $('#warehouse_id').val(warehouse_id);
            @endif

            $('#do_sale_product_id').val(sale_product_id);
            $('#do_input_qty').val(quantity).focus().select();
            $('#do_product_name').val(product_name);
            $('#do_item_name').val(product_name);
            $('#do_product_id').val(product_id);
            $('#do_variant_id').val(variant_id);
            $('#do_unit').val(product_unit);
            $('#do_price_type').val(price_type);
            $('#do_pr_amount').val(pr_amount);
            $('#do_unit_price_exc_tax').val(unit_price_exc_tax);
            $('#do_unit_price_inc_tax').val(unit_price_inc_tax);
            $('#do_unit_cost_inc_tax').val(unit_cost_inc_tax);
            $('#do_left_qty').val(do_left_quantity);
            $('#do_unit_discount').val(unit_discount);
            $('#do_unit_discount_type').val(unit_discount_type);
            $('#do_unit_discount_amount').val(unit_discount_amount);
            $('#do_tax_type').val(tax_type);
            $('#do_unit_tax_percent').val(unit_tax_percent);
            $('#do_unit_tax_amount').val(unit_tax_amount);
            $('#do_is_manage_stock').val(is_manage_stock);
            $('#do_subtotal').val(subTotal);
        });

        function calculateTotalAmount() {

            var quantities = document.querySelectorAll('#quantity');
            var do_left_quantity = document.querySelectorAll('#do_left_quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            // Update Total Item

            var total_item = 0;
            var total_qty = 0;
            quantities.forEach(function(qty) {

                var tr = qty.closest('tr');

                total_qty += parseFloat(qty.value);

                if (parseFloat(total_qty) > 0) {

                    total_item += 1;
                }
            });

            $('#total_qty').val(parseFloat(total_qty).toFixed(2));
            $('#total_deliver_qty').html(parseFloat(total_qty).toFixed(2));
            $('#total_item').val(parseFloat(total_item));

            var totalLeftQty = 0;
            do_left_quantity.forEach(function(leftQty) {

                totalLeftQty += parseFloat(leftQty.value);
            });

            $('#total_left_qty').html(parseFloat(totalLeftQty).toFixed(2));

            var net_total_amount = 0;
            subtotals.forEach(function(subtotal) {

                net_total_amount += parseFloat(subtotal.value);
            });

            $('#net_total_amount').val(parseFloat(net_total_amount).toFixed(2));
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

        $(document).on('click', function(e) {

            if ($(e.target).closest(".invoice_search_result").length === 0) {

                $('.invoice_search_result').hide();
                $('#invoice_list').empty();

                $('.select_area').hide();
                $('#list').empty();
                $('#do_table_rows_area tr').removeClass('selected_do');
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

        function clearOrderItemProcessSection() {

            $('#do_left_qty').val(parseFloat(0).toFixed(2));
            $('#do_input_qty').val(parseFloat(0).toFixed(2));
            $('#do_unit_price_exc_tax').val(parseFloat(0).toFixed(2));
            $('#do_unit_discount').val(parseFloat(0).toFixed(2));
            $('#do_subtotal').val(parseFloat(0).toFixed(2));
            $('#do_sale_product_id').val('');
            $('#do_product_name').val('');
            $('#do_item_name').val('');
            $('#do_is_manage_stock').val('');
            $('#do_product_id').val('');
            $('#do_variant_id').val('');
            $('#do_unit').val('');
            $('#do_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#do_unit_discount_type').val(0);
            $('#do_unit_discount_amount').val(parseFloat(0).toFixed(2));
            $('#do_unit_tax_type').val(1);
            $('#do_unit_tax_amount').val(parseFloat(0).toFixed(2));
            $('#do_unit_tax_percent').val('0.00');

            @if ($warehouseCount != 1)

                $('#warehouse_id').val('');
            @endif
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
            } else if (e.which == 27) {

                $('.invoice_search_result').hide();
                $('#invoice_list').empty();
                return false;
            }
        }

        $('#add_sale_form').on('submit', function(e) {
            e.preventDefault();

            stockErrors = 0;

            $('.loading_button').show();
            var url = $(this).attr('action');

            var totalItem = $('#total_item').val();

            if (parseFloat(totalItem) == 0) {

                $('.loading_button').hide();
                toastr.error('All items Quantity is 0.');
                return;
            }

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

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('.loading_button').hide();
                        return;
                    }

                    if (!$.isEmptyObject(data.finalMsg)) {

                        toastr.success(data.finalMsg);
                        getVehicleList();
                        afterCreateSale();
                    } else {

                        toastr.success('Successfully sale is created.');
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });

                        afterCreateSale();
                        getVehicleList();
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

                        toastr.error('Server Error. Please contact to the support team.');
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

        function afterCreateSale() {

            $('.loading_button').hide();
            $('.hidden').val(parseFloat(0).toFixed(2));
            $('#add_sale_form')[0].reset();
            $('#sale_list').empty();
            $('#sale_id').val('');
            $('#total_left_qty').html(parseFloat(0).toFixed(2));
            $('#total_deliver_qty').html(parseFloat(0).toFixed(2));
            clearOrderItemProcessSection();
            countSalesOrdersQuotationDo();
        }

        $(document).on('click', '#do_weight_connect', function() {

            getWeight().then(weight => {

                $('#do_input_qty').val(parseFloat(weight).toFixed(2));
                calculateSaleProcessingItem();
            });
        });

        $(document).on('click', '#do_car_weight_connect', function() {

            getWeight().then(weight => {

                // var do_car_net_weight = parseFloat(getCurrentCarWeight) / 1000;
                var getCurrentCarWeight = weight;
                $('#do_car_weight').val(parseFloat(getCurrentCarWeight).toFixed(2));
                var dorCarLastWeight = $('#do_car_last_weight').val() ? $('#do_car_last_weight').val() : 0;
                var netWeight = parseFloat(getCurrentCarWeight) - parseFloat(dorCarLastWeight);
                $('#do_car_net_weight').val(parseFloat(netWeight).toFixed(2));
                lastCarWeight = parseFloat(getCurrentCarWeight).toFixed(2);
            });
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#save_car').on('click', function(e) {
            e.preventDefault();

            $(this).hide();
            $('#save_car_blur_btn').show();

            var do_car_number = $('#do_car_number').val();
            var do_driver_name = $('#do_driver_name').val();
            var do_driver_phone = $('#do_driver_phone').val();
            var do_car_weight = $('#do_car_weight').val();
            var invoice_id = $('#invoice_id').val();
            var date = $('#date').val();
            var sale_id = $('#sale_id').val();
            var weight_id = $('#weight_id').val();
            url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'post',
                data: {
                    do_car_weight,
                    do_car_number,
                    do_driver_name,
                    do_driver_phone,
                    invoice_id,
                    date,
                    sale_id,
                    weight_id
                },
                success: function(data) {

                    $('#save_car').show();
                    $('#save_car_blur_btn').hide();

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('.loading_button').hide();
                        $('#do_car_weight').focus().select();
                        return;
                    }

                    toastr.success('Car weight has been saved successfully.');
                    $('#weight_id').val(data.id);
                    $('#do_car_last_weight').val(parseFloat(data.do_car_last_weight).toFixed(2));
                    $('#invoice_id').val(data.reserve_invoice_id);
                    getVehicleList();
                },
                error: function(err) {

                    $('#save_car').show();
                    $('#save_car_blur_btn').hide();

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server Error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $('#edit_do').on('click', function() {

            var url = $(this).data('href');

            var sale_id = $('#sale_id').val();
            var url = $(this).data('href');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    sale_id
                },
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('.loading_button').hide();
                        return;
                    }

                    $('#editDoModal').html(data);
                    $('#editDoModal').modal('show');
                    ul = document.getElementById('list')
                    selectObjClassName = 'selectProduct';


                    setTimeout(function() {

                        $('#search_product').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server Error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $('#do_done').on('click', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            if (url == '#' || url == '') {

                toastr.error('Vehicle is not selected.');
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

                                        getVehicleList();
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

        function getVehicleList() {

            $('#search_vehicle_input').val('');

            search_table('');

            var url = "{{ route('common.ajax.call.do.list') }}";

            $.get(url, function(data) {

                $('#do_table_rows_area').html(data);
                $('#do_done').attr('href', '#');
            });
        }
        getVehicleList();

        // Make print
        $(document).on('click', '#weightDetailsBtn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var sale_id = $('#sale_id').val();
            var weight_id = $('#weight_id').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    sale_id,
                    weight_id
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

        $(document).on('click', '#printBtn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var sale_id = $('#sale_id').val();
            var weight_id = $('#weight_id').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    sale_id,
                    weight_id
                },
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
                    });
                }
            });
        });

        // Make print
        $(document).on('click', '#previous_data_btn', function(e) {
            e.preventDefault();

            var url = $(this).data('href');
            var invoice_id = $('#search_invoice').val();

            if (invoice_id == '') {

                toastr.error('Invoice ID is empty.');
                return;
            }

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    invoice_id
                },
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
        $(document).on('focus', '#item_name', function(e) {
            e.preventDefault();

            $('.display tbody tr').removeClass('active_tr');
            $(this).closest('tr').addClass('active_tr');
        });

        // Make print
        $(document).on('keypress', '#do_car_weight', function(e) {

            if (e.which == 13) {

                $('#do_car_weight_connect').click();
            }
        });

        // Make print
        $(document).on('click', '#vehicle_refresh', function(e) {
            e.preventDefault();

            getVehicleList();
        });

        // #do_table_rows_area
        //#search_vehicle_input
        $('#search_vehicle_input').keyup(function() {
            search_table($(this).val());
        });

        function search_table(value) {

            $('#do_table_rows_area tr').each(function() {
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

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                $('#' + nextId).focus().select();
            }
        });
    </script>

    <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script>
    {{-- <script src="{{ asset('plugins/select_li/selectTr.custom.js') }}"></script> --}}
@endpush
