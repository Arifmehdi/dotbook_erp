@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 120%;
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
            font-size: 10px;
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

        .input-group-text-sale {
            font-size: 7px !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        .border_red {
            border: 1px solid red !important;
        }

        #closing_balance {
            font-weight: 600;
        }

        input[type=number]#quantity::-webkit-inner-spin-button,
        input[type=number]#quantity::-webkit-outer-spin-button {
            opacity: 1;
            margin: 0;
        }

        span.select2-dropdown.select2-dropdown--below {
            border-top: 1px solid gray;
        }

        span.select2-dropdown.select2-dropdown--below {
            width: 283px !important;
        }

        .bigdrop {
            width: auto !important;
        }

        .number-fields label {
            font-size: 10px !important;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Create Sales - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <form id="add_sale_form" action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <input type="hidden" value="1" name="status" id="status">
                <div class="row g-0">
                    <div class="form_element mt-0 border-0">
                        <div class="sec-name">
                            <h6>@lang('menu.create_sale')</h6>
                            <x-all-buttons />
                        </div>
                    </div>
                </div>
                <section class="p-15">
                    <div class="sale-content">
                        <div class="row g-1">
                            <div class="col-xl-9 col-md-7">
                                <div class="form_element m-0 mb-1 rounded">
                                    <div class="element-body">
                                        <div class="row g-lg-4 g-1">
                                            <div class="col-xl-4 col-md-6">
                                                @if (auth()->user()->is_marketing_user == 0)
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Sr.') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="hidden" name="user_count" id="user_count" value="1">
                                                            <select required name="user_id" id="user_id" class="form-control select2 form-select" data-next="customer_account_id">
                                                                <option value="">@lang('menu.select_sr')</option>
                                                                @foreach ($users as $user)
                                                                    <option data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}" value="{{ $user->id }}">
                                                                        {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_user_id"></span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <input type="hidden" data-user_name="{{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name . '/' . auth()->user()->phone }}" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
                                                @endif

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Customer') }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <div class="input-group select-customer-input-group">
                                                            <div class="select-half">
                                                                <select required name="customer_account_id" class="form-control select2 add_input form-select" id="customer_account_id" data-next="sale_account_id">
                                                                    <option value="">@lang('menu.select_customer')</option>
                                                                    @foreach ($customerAccounts as $customerAccont)
                                                                        <option data-customer_name="{{ $customerAccont->name }}" data-customer_phone="{{ $customerAccont->phone }}" value="{{ $customerAccont->id }}">
                                                                            {{ $customerAccont->name . '/' . $customerAccont->phone }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="style-btn">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button {{ !auth()->user()->can('customer_add')? 'disabled_element': '' }}" id="addCustomer">
                                                                        <i class="fas fa-plus-square text-dark"></i>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <span class="error error_customer_account_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4 text-danger"><b>{{ __('Closing Bal.') }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control fw-bold" id="closing_balance" placeholder="0.00" autocomplete="off" tabindex="-1">
                                                        <input readonly type="hidden" id="debit_amount" value="0.00">
                                                        <input readonly type="hidden" id="credit_amount" value="0.00">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Sales Ledger') }} <span class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <select name="sale_account_id" class="form-control select2 form-select" id="sale_account_id" data-next="price_group_id">
                                                            @foreach ($saleAccounts as $saleAccount)
                                                                <option value="{{ $saleAccount->id }}">
                                                                    {{ $saleAccount->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_sale_account_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Invoice ID') }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control fw-bold" placeholder="@lang('menu.invoice_id')" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.price_group')</b></label>
                                                    <div class="col-8">
                                                        <select name="price_group_id" class="form-control form-select" id="price_group_id" data-next="all_price_type">
                                                            <option value="">@lang('menu.default_selling_price')</option>
                                                            @foreach ($price_groups as $pg)
                                                                <option {{ json_decode($generalSettings->sale, true)['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">
                                                                    {{ $pg->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Rate Type') }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select required name="all_price_type" class="form-control form-select" id="all_price_type" data-next="warehouse_id">
                                                            <option value="">{{ __('Select Rate Type') }}</option>
                                                            <option value="PR">PR</option>
                                                            <option value="MR">MR</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Warehouse') }}</b></label>
                                                    <div class="col-8">
                                                        <select name="warehouse_id" class="form-control form-select" id="warehouse_id" data-next="date">
                                                            <option value="">@lang('menu.select_warehouse')</option>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option data-w_name="{{ $warehouse->name . '/' . $warehouse->code }}" value="{{ $warehouse->id }}">
                                                                    {{ $warehouse->name . '/' . $warehouse->code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>{{ __('Date') }} <span class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="date" class="form-control" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" autocomplete="off" id="date" data-next="invoice_schema">
                                                        <span class="error error_date"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Invoice Schema') }}</b></label>
                                                    <div class="col-8">
                                                        <select name="invoice_schema" class="form-control form-select" id="invoice_schema" data-next="search_product">
                                                            <option value="">@lang('menu.none')</option>
                                                            @foreach ($invoice_schemas as $inv_schema)
                                                                <option value="{{ $inv_schema->format == 2 ? date('Y') . '/' . $inv_schema->start_from : $inv_schema->prefix . $inv_schema->start_from }}">
                                                                    {{ $inv_schema->format == 2 ? date('Y') . '/' . $inv_schema->start_from : $inv_schema->prefix . $inv_schema->start_from }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Attachment') }}</b></label>
                                                    <div class="col-8">
                                                        <input type="file" name="attachment" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row g-1 align-items-end">
                                            <div class="col-md-4">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="fw-bold">{{ __('Search Item') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="@lang('menu.search_item_item_code_scan_bar_code')" autocomplete="off">
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

                                            <div class="hidden_fields d-none">
                                                <input type="hidden" id="e_unique_id">
                                                <input type="hidden" id="e_unit_cost_inc_tax">
                                                <input type="hidden" id="e_item_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">
                                                <input type="hidden" id="e_base_unit_name">
                                                <input type="hidden" id="e_tax_amount">
                                                <input type="hidden" id="e_showing_tax_amount">
                                                <input type="hidden" id="e_is_show_emi_on_pos">
                                                <input type="hidden" id="e_price_inc_tax">
                                                <input type="hidden" id="e_showing_price_inc_tax">
                                                <input type="hidden" id="e_base_unit_price_exc_tax">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Quantity') }}</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control fw-bold w-60" id="e_showing_quantity" placeholder="@lang('menu.quantity')" value="0.00">
                                                    <input type="hidden" id="e_quantity" value="0.00">
                                                    <select id="e_unit_id" class="form-control w-40 form-select">
                                                        <option value="">{{ __('Unit') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Per Unit Price (Exc. Tax)') }}</label>
                                                <input {{ auth()->user()->can('edit_price_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_showing_price_exc_tax" placeholder="@lang('menu.price_exclude_tax')" value="0.00">
                                                <input type="hidden" id="e_price_exc_tax" value="0.00">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Pr Amount') }}</label>
                                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_pr_amount" value="0.00" tabindex="-1" />
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Discount') }}</label>
                                                <div class="input-group">
                                                    <input {{ auth()->user()->can('edit_discount_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_showing_discount" placeholder="@lang('menu.discount')" value="0.00">

                                                    <input type="hidden" id="e_discount" value="0.00">

                                                    <select id="e_discount_type" class="form-control form-select">
                                                        <option value="1">@lang('menu.fixed')(0.00)</option>
                                                        <option value="2">@lang('menu.percentage')(%)</option>
                                                    </select>

                                                    <input type="hidden" id="e_showing_discount_amount">
                                                    <input type="hidden" id="e_discount_amount">
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <label class="fw-bold">{{ __('Tax') }}</label>
                                                <div class="input-group">
                                                    <select id="e_tax_ac_id" class="form-control form-select">
                                                        <option data-product_tax_percent="0.00" value="">
                                                            @lang('menu.no_tax')</option>
                                                        @foreach ($taxAccounts as $taxAccount)
                                                            <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <select id="e_tax_type" class="form-control form-select" tabindex="-1">
                                                        <option value="1">{{ __('Exclusive') }}</option>
                                                        <option value="2">{{ __('Inclusive') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <div class="row g-1">
                                                    <div class="col-xl-6 col-md-6">
                                                        <label class="fw-bold">{{ __('IMEI/SL No./Other Info') }}</label>
                                                        <input type="number" step="any" class="form-control fw-bold" id="e_descriptions" value="" placeholder="IMEI/SL No./Other Info.">
                                                    </div>

                                                    <div class="col-xl-6 col-md-6">
                                                        <label class="fw-bold">{{ __('Subtotal') }}</label>
                                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6 d-flex align-items-center gap-2">

                                                <a href="#" class="btn btn-sm btn-success" id="add_item">{{ __('Add') }}</a>
                                                <input type="reset" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger" value="{{ __('Reset') }}">
                                            </div>

                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text add_button p-1 m-0">{{ __('Stock') }}</span>
                                                    </div>

                                                    <input type="text" readonly class="form-control text-success fw-bold" autocomplete="off" id="stock_quantity" placeholder="{{ __('Stock Quantity') }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive scrollable-table">
                                                        <table class="display data__table table sale-product-table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-startx">{{ __('Item') }}</th>
                                                                    <th class="text-startx">{{ __('Stock Location') }}
                                                                    </th>
                                                                    <th class="text-center">{{ __('Quantity') }}</th>
                                                                    <th>{{ __('Unit') }}</th>
                                                                    <th class="text-center">
                                                                        {{ __('Per Unit Price (Inc.Tax)') }}</th>
                                                                    <th class="text-center">@lang('menu.rate_type')</th>
                                                                    <th>{{ __('Rate Type') }}</th>
                                                                    <th><i class="fas fa-minus"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_item_list"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-details-sec mt-1">
                                    <div class="content-inner">
                                        <div class="row mt-1 pb-3">
                                            <div class="col-md-4">
                                                <label><b>{{ __('Sale Note') }}</b></label>
                                                <input name="sale_note" class="form-control" id="sale_note" data-next="shipping_address" placeholder="{{ __('Sale Note') }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label><b>@lang('menu.shipping_address') </b> </label>
                                                <input name="shipping_address" class="form-control" id="shipping_address" data-next="payment_note" placeholder="@lang('menu.shipping_address')">
                                            </div>

                                            <div class="col-md-4">
                                                <label><b>@lang('menu.payment_note') </b></label>
                                                <input name="payment_note" class="form-control" id="payment_note" data-next="order_discount" placeholder="@lang('menu.payment_note')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-5">
                                <div class="item-details-sec mb-1">
                                    <div class="content-inner py-2">
                                        <div class="row no-gutters">
                                            <ul class="list-unstyled d-flex" style="gap:10px;">
                                                <li style=" width: calc(50% - 5px); min-width: auto;"><button type="button" style="max-height: none;" class="w-100 btn px-0 py-3 btn-primary text-white resent-tn">@lang('menu.recent_transaction')</button>
                                                </li>
                                                <li style=" width: calc(50% - 5px); min-width: auto;"><button type="button" style="max-height: none;" class="w-100 btn py-3 btn-success text-white show_stock">@lang('menu.show_stock')</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-details-sec number-fields">
                                    <div class="content-inner">
                                        <div class="row g-1">
                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.total_item') </label>
                                                    <div class="col-sm-7">
                                                        <input readonly type="number" step="any" name="total_item" id="total_item" class="form-control" value="0.00" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.total_qty') </label>
                                                    <div class="col-sm-7">
                                                        <input readonly type="number" name="total_qty" class="form-control" id="total_qty" value="0.00" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.net_total') </label>
                                                    <div class="col-sm-7">
                                                        <input readonly type="number" step="any" class="form-control" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.discount')</label>

                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <input name="order_discount" type="number" step="any" class="form-control w-60" id="order_discount" data-next="order_discount_type" value="{{ json_decode($generalSettings->sale, true)['default_sale_discount'] }}">
                                                            <input name="order_discount_amount" step="any" type="number" class="d-none" id="order_discount_amount" value="0.00" tabindex="-1">

                                                            <select name="order_discount_type" class="form-control w-40 form-select" id="order_discount_type" data-next="order_tax_ac_id">
                                                                <option {{ json_decode($generalSettings->sale, true)['default_sale_discount_type'] == '1' ? 'SELECTED' : '' }} value="1">@lang('menu.fixed')</option>
                                                                <option {{ json_decode($generalSettings->sale, true)['default_sale_discount_type'] == '2' ? 'SELECTED' : '' }} value="2">@lang('menu.percentage')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.sale_tax') </label>
                                                    <div class="col-sm-7">
                                                        <select name="order_tax_ac_id" class="form-control form-select" id="order_tax_ac_id" data-next="shipment_charge">
                                                            <option data-order_tax_percent="0.00" value="">
                                                                @lang('menu.no_tax')</option>
                                                            @foreach ($taxAccounts as $taxAccount)
                                                                <option {{ json_decode($generalSettings->sale, true)['default_tax_id'] == $taxAccount->id ? 'SELECTED' : '' }} data-order_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                    {{ $taxAccount->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="number" step="any" class="d-none" name="order_tax_percent" id="order_tax_percent" value="0.00">
                                                        <input type="number" step="any" class="d-none" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.shipment_cost')</label>
                                                    <div class="col-sm-7">
                                                        <input name="shipment_charge" type="number" step="any" class="form-control" id="shipment_charge" data-next="receive_amount" value="0.00">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.total_invoice_amt')</label>
                                                    <div class="col-sm-7">
                                                        <input readonly type="number" step="any" name="total_invoice_amount" class="form-control" id="total_invoice_amount" value="0.00" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="payment_body row g-1">
                                                    <div class="col-12">
                                                        <div class="row align-items-center">
                                                            <label class="col-sm-5 text-end fw-bold">@lang('menu.receive_amount')
                                                                >></label>
                                                            <div class="col-sm-7">
                                                                <input {{ !auth()->user()->can('receipts_add')? 'readonly': '' }} type="number" step="any" name="receive_amount" class="form-control form-control-lg fw-bold fs-5" id="receive_amount" data-next="payment_method_id" value="0.00" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="row">
                                                            <label class="col-sm-5 text-end fw-bold">@lang('menu.payment_type')
                                                            </label>
                                                            <div class="col-sm-7">
                                                                <select name="payment_method_id" class="form-control form-select" id="payment_method_id" data-next="account_id">
                                                                    @foreach ($methods as $method)
                                                                        <option data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}" value="{{ $method->id }}">
                                                                            {{ $method->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="row cash_bank_account_area">
                                                            <label class="col-sm-5 text-end fw-bold">@lang('menu.debit_account')
                                                                <span class="text-danger">*</span></label>
                                                            <div class="col-sm-7">
                                                                <select name="account_id" class="form-control form-select" id="account_id" data-next="transaction_no">
                                                                    <option value="">@lang('menu.select_debit_ac')</option>
                                                                    @foreach ($accounts as $account)
                                                                        <option value="{{ $account->id }}">
                                                                            @php
                                                                                $bank = $account->bank ? ', ' . __('menu.bank') . ':' . $account->bank : '';
                                                                                $ac_no = $account->account_number ? ', ' . __('menu.ac_no') . ':' . '***' . substr($account->account_number, -4) : '';
                                                                            @endphp
                                                                            {{ $account->name . $bank . $ac_no }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error error_account_id"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="row">
                                                            <label class="col-sm-5 text-end fw-bold">@lang('menu.transaction_no')</label>
                                                            <div class="col-sm-7">
                                                                <input type="number" step="any" name="transaction_no" class="form-control" id="transaction_no" data-next="cheque_no" placeholder="Transaction Number" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="row">
                                                            <label class="col-sm-5 text-end fw-bold">@lang('menu.cheque_no')</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" step="any" name="cheque_no" class="form-control" id="cheque_no" data-next="save_and_print" placeholder="Cheque Number" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="row">
                                                            <label class="col-sm-5 text-end text-danger fw-bold">@lang('menu.curr_balance')
                                                            </label>
                                                            <div class="col-sm-7">
                                                                <input readonly type="text" step="any" class="form-control fw-bold" name="current_balance" id="current_balance" value="0.00" tabindex="-1">
                                                                <input type="hidden" name="current_balance_amount" id="current_balance_amount">
                                                                <input type="hidden" name="current_balance_side" id="current_balance_side">
                                                                <input type="hidden" name="sales_ledger_amount" id="sales_ledger_amount">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="submitBtn">
                                            <div class="row justify-content-center">
                                                <div class="col-12 text-end">
                                                    <div class="btn-box d-flex loading-btn-box flex-wrap">
                                                        <button type="button" class="btn loading_button display-none"><i class="fas fa-spinner text-white"></i> </button>
                                                        <button type="button" class="btn py-3 btn-success">@lang('menu.payment')</button>
                                                        <button type="submit" id="save_and_print" class="btn py-3 btn-success submit_button" data-status="1" value="save_and_print">@lang('menu.save_and_print')</button>
                                                        <button type="button" class="btn py-3 btn-danger" data-status="1" value="save">@lang('menu.reset_data')</button>
                                                        <button type="submit" id="save" class="btn py-3 btn-success submit_button" data-status="1" value="save">@lang('menu.save')</button>
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
            </form>
        </div>
    </div>

    <!--Add Customer Opening Balance Modal-->
    <div class="modal fade" id="addCustomerOpeingBalanceModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_customer_opening_balance')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form id="add_customer_opening_balance" action="{{ route('contacts.customers.opening.balance.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="op_user_id" name="user_id">
                        <input type="hidden" id="op_customer_account_id" name="customer_account_id">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <p><strong>@lang('menu.customer') : </strong><span class="op_customer_name"></span></p>
                                <p><strong>@lang('menu.phone_no') : </strong><span class="op_customer_phone"></span></p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>@lang('menu.sr') : </strong><span class="op_user_name"></span></p>
                            </div>

                            <div class="col-md-12 mt-2">
                                <label><b>@lang('menu.opening_balance') : </b> </label>
                                <div class="input-group">
                                    <input type="number" step="any" name="opening_balance" class="form-control w-65" placeholder="@lang('menu.opening_balance')">
                                    <select name="opening_balance_type" class="form-control w-35 form-select">
                                        <option value="debit">@lang('menu.debit')</option>
                                        <option value="credit">@lang('menu.credit')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="row">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="never_show_again" id="never_show_again" class="is_show_again">&nbsp;<b>@lang('menu.never_show_again')</b>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn op_btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                    <button name="action" value="save" type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Add Customer Opening Balance Modal End-->

    <!--Add Customer Modal-->
    <div class="modal fade" id="add_customer_basic_modal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <div class="modal fade" id="add_customer_detailed_modal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add Customer Modal-->

    @if (auth()->user()->can('product_add'))
        <!--Add Product Modal-->
        <div class="modal fade" id="addQuickProductModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop">
            <div class="modal-dialog four-col-modal" role="document" id="quick_product_add_modal_contant"></div>
        </div>
        <!--Add Product Modal End-->

        <div class="modal fade" id="unitAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="brandAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="warrantyAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="categoryAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="subcategoryAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    @endif

@endsection
@push('scripts')
    {{-- @include('sales.partials.addSaleCreateJsScript') --}}
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        $('.select2').select2();

        var itemUnitsArray = [];
        var branch_name = "{{ json_decode($generalSettings->business, true)['shop_name'] }}";

        var price_groups = '';

        function getPriceGroupProducts() {

            $.ajax({
                url: "{{ route('sales.product.price.groups') }}",
                success: function(data) {
                    price_groups = data;
                }
            });
        }
        getPriceGroupProducts();

        var delay = (function() {
            var timer = 0;
            return function(callback, ms) {

                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $('#search_product').on('input', function(e) {

            var all_price_type = $('#all_price_type').val();

            if (all_price_type == '') {

                toastr.error('Please select rate type first.');
                return;
            }

            $('.variant_list_area').empty();
            $('.select_area').hide();
            var keyWord = $(this).val();
            var __keyWord = keyWord.replaceAll('/', '~');
            var price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';
            delay(function() {
                searchProduct(__keyWord, price_group_id);
            }, 200); //sendAjaxical is the name of remote-command
        });

        function searchProduct(keyWord, price_group_id) {

            $('#search_product').focus();

            var type = 'sales';
            var isShowNotForSaleItem = 0;
            var url =
                "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem', ':price_group_id', ':type']) }}";
            var route = url.replace(':keyWord', keyWord);
            route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);
            route = route.replace(':price_group_id', price_group_id);
            route = route.replace(':type', type);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(product) {

                    if (!$.isEmptyObject(product.errorMsg || keyWord == '')) {

                        toastr.error(product.errorMsg);
                        $('#search_product').val("");
                        $('.select_area').hide();
                        $('#stock_quantity').val(parseFloat(0).toFixed(2));
                        return;
                    }

                    var discount = product.discount;

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
                                $('#search_product').val('');

                                if (product.is_manage_stock == 1) {

                                    $('#stock_quantity').val(parseFloat(product.quantity).toFixed(2));
                                }

                                var price = 0;
                                var __price = price_groups.filter(function(value) {

                                    return value.price_group_id == price_group_id && value.product_id ==
                                        product.id;
                                });

                                if (__price.length != 0) {

                                    price = __price[0].price ? __price[0].price : product.product_price;
                                } else {

                                    price = product.product_price;
                                }

                                var discount_amount = 0;
                                if (discount.discount_type == 1) {

                                    discount_amount = discount.discount_amount
                                } else {

                                    discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                                }

                                var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' :
                                    product.name;

                                $('#search_product').val(name);
                                $('#e_item_name').val(name);
                                $('#e_product_id').val(product.id);
                                $('#e_variant_id').val('noid');
                                $('#e_base_unit_name').val(product.unit.name);
                                $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                                $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                                $('#e_showing_discount').val(discount.discount_amount);
                                $('#e_discount_type').val(discount.discount_type);
                                $('#e_showing_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                                $('#e_tax_ac_id').val(product.tax_ac_id != null ? product.tax_ac_id : '');
                                $('#e_tax_type').val(product.tax_type);
                                $('#e_unit_cost_inc_tax').val(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax);
                                $('#display_unit_cost').html(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax);
                                $('#e_is_show_emi_on_pos').val(product.is_show_emi_on_pos);
                                $('#e_base_unit_price_exc_tax').val(price);

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
                            } else {

                                var li = "";
                                $.each(product.variants, function(key, variant) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    var price = 0;
                                    var __price = price_groups.filter(function(value) {

                                        return value.price_group_id == price_group_id && value.product_id == product.id && value.variant_id == variant.id;
                                    });

                                    if (__price.length != 0) {

                                        price = __price[0].price ? __price[0].price : variant.variant_price;
                                    } else {

                                        price = variant.variant_price;
                                    }

                                    var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;

                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_code="' + variant.variant_code + '" data-p_price_exc_tax="' + price + '" data-p_cost_inc_tax="' + (variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax) + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if (!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();
                            $('#search_product').val('');

                            var variant = product.variant_product;

                            if (variant.product.is_manage_stock == 1) {

                                $('#stock_quantity').val(parseFloat(variant.variant_quantity).toFixed(2));
                            }

                            var price = 0;
                            var __price = price_groups.filter(function(value) {

                                return value.price_group_id == price_group_id && value.product_id ==
                                    variant.product.id && value.variant_id == variant.id;
                            });

                            if (__price.length != 0) {

                                price = __price[0].price ? __price[0].price : variant.variant_price;
                            } else {

                                price = variant.variant_price;
                            }

                            var discount_amount = 0;
                            if (discount.discount_type == 1) {

                                discount_amount = discount.discount_amount
                            } else {

                                discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                            }

                            var name = variant.product.name.length > 35 ? variant.product.name.substring(0,
                                35) + '...' : variant.product.name;

                            $('#search_product').val(name + ' - ' + variant.variant_name);
                            $('#e_item_name').val(name + ' - ' + variant.variant_name);
                            $('#e_product_id').val(variant.product.id);
                            $('#e_variant_id').val(variant.id);
                            $('#e_base_unit_name').val(variant.product.unit.name);
                            $('#e_unit').val(variant.product.unit.name);
                            $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                            $('#e_showing_discount').val(parseFloat(discount.discount_amount).toFixed(2));
                            $('#e_discount_type').val(discount.discount_type);
                            $('#e_showing_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                            $('#e_tax_ac_id').val(variant.product.tax_ac_id != null ? variant.product.tax_ac_id : '');
                            $('#e_tax_type').val(variant.product.tax_type);
                            $('#e_unit_cost_inc_tax').val(variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax);
                            $('#display_unit_cost').html(variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax);
                            $('#e_is_show_emi_on_pos').val(product.is_show_emi_on_pos);
                            $('#e_base_unit_price_exc_tax').val(price);

                            $('#e_unit_id').empty();

                            $('#e_unit_id').append(
                                '<option value="' + variant.product.unit.id +
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

                                    $('#e_unit_id').append(
                                        '<option value="' + unit.id +
                                        '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                        '" data-base_unit_multiplier="' + unit
                                        .base_unit_multiplier + '">' + unit.name +
                                        multiplierDetails + '</option>'
                                    );
                                });
                            }

                            $('#add_item').html('Add');
                            calculateEditOrAddAmount();
                        } else if (!$.isEmptyObject(product.namedProducts)) {

                            if (product.namedProducts.length > 0) {


                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;
                                    var tax_percent = product.tax_percent != null ? product.tax_percent : 0;

                                    if (product.is_variant == 1) {

                                        var price = 0;
                                        var __price = price_groups.filter(function(value) {

                                            return value.price_group_id == price_group_id && value.product_id == product.id && value.variant_id == product.variant_id;
                                        });

                                        if (__price.length != 0) {

                                            price = __price[0].price ? __price[0].price : product.variant_price;
                                        } else {

                                            price = product.variant_price;
                                        }

                                        li += '<li>';
                                        li += '<a class="select_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-v_name="' + product.variant_name + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_code="' + product.variant_code + '" data-p_price_exc_tax="' + price + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                        li += '</li>';
                                    } else {

                                        var price = 0;
                                        var __price = price_groups.filter(function(value) {

                                            return value.price_group_id == price_group_id && value.product_id == product.id;
                                        });

                                        if (__price.length != 0) {

                                            price = __price[0].price ? __price[0].price : product.product_price;
                                        } else {

                                            price = product.product_price;
                                        }

                                        li += '<li>';
                                        li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="' + product.name + '" data-v_name="" data-p_code="' + product.product_code + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_price_exc_tax="' + price + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_cost_inc_tax="' + product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                        li += '</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        }
                    } else {

                        $('#search_product').addClass('is-invalid');
                    }
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Please check the connetion.');
                        return;
                    }
                }
            });
        }

        // select single/variant product and show stock
        var keyName = 1;

        function selectProduct(e) {

            var price_group_id = price_group_id ? price_group_id : 'no_id';

            $('.select_area').hide();
            $('#search_product').val('');

            if (keyName == 13 || keyName == 1) {

                document.getElementById('search_product').focus();
            }

            var product_id = e.getAttribute('data-p_id');
            var variant_id = e.getAttribute('data-v_id') ? e.getAttribute('data-v_id') : 'noid';
            var is_manage_stock = e.getAttribute('data-is_manage_stock');
            var product_name = e.getAttribute('data-p_name');
            var variant_name = e.getAttribute('data-v_name');
            var product_code = e.getAttribute('data-p_code');
            var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
            var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
            var p_tax_ac_id = e.getAttribute('data-p_tax_ac_id') != null ? e.getAttribute('data-p_tax_ac_id') : '';
            var p_tax_type = e.getAttribute('data-tax_type');
            var is_show_emi_on_pos = e.getAttribute('data-is_show_emi_on_pos');
            $('#search_product').val('');

            var url = "{{ route('general.product.search.check.product.discount.with.stock', [':product_id', ':variant_id', ':price_group_id']) }}"
            var route = url.replace(':product_id', product_id);
            route = route.replace(':variant_id', variant_id);
            route = route.replace(':price_group_id', price_group_id);

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(data) {

                    if ($.isEmptyObject(data.errorMsg)) {

                        if (is_manage_stock == 1) {

                            $('#stock_quantity').val(parseFloat(data.stock).toFixed(2));
                        }

                        if (is_show_emi_on_pos == 0) {

                            $('#e_descriptions').prop('readonly', true);
                        } else {

                            $('#e_descriptions').prop('readonly', false);
                        }

                        var price = 0;
                        var __price = price_groups.filter(function(value) {

                            return value.price_group_id == price_group_id && value.product_id == product_id;
                        });

                        if (__price.length != 0) {

                            price = __price[0].price ? __price[0].price : product_price_exc_tax;
                        } else {

                            price = product_price_exc_tax;
                        }

                        var discount = data.discount;

                        var discount_amount = 0;
                        if (discount.discount_type == 1) {

                            discount_amount = discount.discount_amount
                        } else {

                            discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                        }

                        var name = product_name.length > 35 ? product_name.substring(0, 35) + '...' : product_name;

                        $('#search_product').val(name + (variant_name ? ' - ' + variant_name : ''));
                        $('#e_item_name').val(name + (variant_name ? ' - ' + variant_name : ''));
                        $('#e_product_id').val(product_id);
                        $('#e_variant_id').val(variant_id);
                        $('#e_base_unit_name').val(data.unit.name);
                        $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                        $('#e_showing_discount').val(parseFloat(discount.discount_amount).toFixed(2));
                        $('#e_discount_type').val(discount.discount_type);
                        $('#e_showing_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                        $('#e_tax_ac_id').val(p_tax_ac_id);
                        $('#e_tax_type').val(p_tax_type);
                        $('#e_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));
                        $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);
                        $('#e_base_unit_price_exc_tax').val(price);

                        $('#e_unit_id').empty();
                        $('#e_unit_id').append(
                            '<option value="' + data.unit.id +
                            '" data-is_base_unit="1" data-unit_name="' + data.unit.name +
                            '" data-base_unit_multiplier="1">' + data.unit.name + '</option>'
                        );

                        itemUnitsArray[product_id] = [{
                            'unit_id': data.unit.id,
                            'unit_name': data.unit.name,
                            'unit_code_name': data.unit.code_name,
                            'base_unit_multiplier': 1,
                            'multiplier_details': '',
                            'is_base_unit': 1,
                        }];

                        if (data.unit.child_units.length > 0) {

                            data.unit.child_units.forEach(function(unit) {

                                var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + data.unit.name + ')';

                                itemUnitsArray[product_id].push({
                                    'unit_id': unit.id,
                                    'unit_name': unit.name,
                                    'unit_code_name': unit.code_name,
                                    'base_unit_multiplier': unit.base_unit_multiplier,
                                    'multiplier_details': multiplierDetails,
                                    'is_base_unit': 0,
                                });

                                $('#e_unit_id').append(
                                    '<option value="' + unit.id +
                                    '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                    '" data-base_unit_multiplier="' + unit.base_unit_multiplier +
                                    '">' + unit.name + multiplierDetails + '</option>'
                                );
                            });
                        }

                        $('#add_item').html('Add');

                        calculateEditOrAddAmount();
                    } else {

                        toastr.error(data.errorMsg);
                    }
                }
            });
        }

        $('#add_item').on('click', function(e) {
            e.preventDefault();

            var all_price_type = $('#all_price_type').val();

            if (all_price_type == '') {

                toastr.error('Please select rate type first.');
                return;
            }

            var e_unique_id = $('#e_unique_id').val();
            var e_item_name = $('#e_item_name').val();
            var e_product_id = $('#e_product_id').val();
            var e_variant_id = $('#e_variant_id').val();
            var e_base_unit_name = $('#e_base_unit_name').val();
            var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
            var e_unit_id = $('#e_unit_id').val();
            var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
            var e_showing_price_exc_tax = $('#e_showing_price_exc_tax').val() ? $('#e_showing_price_exc_tax').val() : 0;
            var all_price_type = $('#all_price_type').val() ? $('#all_price_type').val() : 'N/A';
            var e_pr_amount = $('#all_price_type').val() == 'PR' ? ($('#e_pr_amount').val() ? $('#e_pr_amount').val() : 0) : 0;
            var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
            var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;
            var e_discount_type = $('#e_discount_type').val();
            var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
            var e_showing_discount_amount = $('#e_showing_discount_amount').val() ? $('#e_showing_discount_amount').val() : 0;
            var e_tax_ac_id = $('#e_tax_ac_id').val();
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
            var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
            var e_showing_tax_amount = $('#e_showing_tax_amount').val() ? $('#e_showing_tax_amount').val() : 0;
            var e_tax_type = $('#e_tax_type').val();
            var e_price_inc_tax = $('#e_price_inc_tax').val() ? $('#e_price_inc_tax').val() : 0;
            var e_showing_price_inc_tax = $('#e_showing_price_inc_tax').val() ? $('#e_showing_price_inc_tax').val() : 0;
            var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
            var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val() ? $('#e_showing_unit_cost_inc_tax').val() : 0;
            var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
            var e_descriptions = $('#e_descriptions').val();
            var stock_quantity = $('#stock_quantity').val();
            var showPrAmount = all_price_type == 'PR' ? '(' + parseFloat(e_pr_amount).toFixed(2) + ')' : '';

            var warehouse_id = $('#warehouse_id').val();
            var warehouse_name = $('#warehouse_id').find('option:selected').data('w_name');

            var stock_location_name = '';
            if (warehouse_id) {

                stock_location_name = warehouse_name;
            } else {

                stock_location_name = branch_name;
            }

            if (e_showing_quantity == '') {

                toastr.error('Quantity field must not be empty.');
                return;
            }

            if (e_product_id == '') {

                toastr.error('Please select an item.');
                return;
            }

            var route = '';
            if (e_variant_id != 'noid') {

                var url = "{{ route('general.product.search.variant.product.stock', [':e_product_id', ':e_variant_id', ':warehouse_id']) }}";
                route = url.replace(':e_product_id', e_product_id);
                route = route.replace(':e_variant_id', e_variant_id);
                route = route.replace(':warehouse_id', warehouse_id);
            } else {

                var url = "{{ route('general.product.search.single.product.stock', [':e_product_id', ':warehouse_id']) }}";
                route = url.replace(':e_product_id', e_product_id);
                route = route.replace(':warehouse_id', warehouse_id);
            }

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    if ($.isEmptyObject(data.errorMsg)) {

                        var stockLocationMessage = warehouse_id ? ' in selected warehouse' : ' in the company';
                        if (parseFloat(e_quantity) > parseFloat(data.stock)) {

                            toastr.error('Current stock is ' + parseFloat(data.stock) + '/' + e_base_unit_name + stockLocationMessage);
                            return;
                        }

                        var uniqueIdForPreventDuplicateEntry = e_product_id + e_variant_id + warehouse_id;
                        var uniqueIdValue = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).val();

                        if (uniqueIdValue == undefined) {

                            var tr = '';
                            tr += '<tr id="select_item">';
                            tr += '<td class="text-start">';
                            tr += '<span class="product_name">' + e_item_name + '</span>';
                            tr += '<input type="hidden" id="item_name" value="' + e_item_name + '">';
                            tr += '<input type="hidden" name="is_show_emi_on_pos" id="is_show_emi_on_pos" value="' + e_is_show_emi_on_pos + '">';
                            tr += '<input type="hidden" name="descriptions[]" id="descriptions" value="' + e_descriptions + '">';
                            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + e_product_id + '">';
                            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + e_variant_id + '">';
                            tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + e_tax_ac_id + '">';
                            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + e_tax_type + '">';
                            tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + e_tax_percent + '">';
                            tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(e_tax_amount).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_unit_tax_amount" value="' + parseFloat(e_showing_tax_amount).toFixed(2) + '">';
                            tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + e_discount_type + '">';
                            tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + e_discount + '">';
                            tr += '<input type="hidden" id="showing_unit_discount" value="' + e_showing_discount + '">';
                            tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + e_discount_amount + '">';
                            tr += '<input type="hidden" id="showing_unit_discount_amount" value="' + e_showing_discount_amount + '">';
                            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + e_unit_cost_inc_tax + '">';
                            tr += '<input type="hidden" id="showing_unit_cost_inc_tax" value="' + e_showing_unit_cost_inc_tax + '">';
                            tr += '<input type="hidden" id="current_stock" value="' + stock_quantity + '">';
                            tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + warehouse_id + '" value="' + e_product_id + e_variant_id + warehouse_id + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + warehouse_id + '">';
                            tr += '<span id="stock_location_name">' + stock_location_name + '</span>';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span id="span_showing_quantity" class="fw-bold">' + parseFloat(e_showing_quantity).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_quantity" value="' + parseFloat(e_showing_quantity).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text">';
                            tr += '<b><span id="span_unit">' + e_unit_name + '</span></b>';
                            tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                            tr += '<input type="hidden" id="base_unit_name" value="' + e_base_unit_name + '">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(e_price_exc_tax).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_unit_price_exc_tax" value="' + parseFloat(e_showing_price_exc_tax).toFixed(2) + '">';
                            tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(e_price_inc_tax).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_unit_price_inc_tax" value="' + parseFloat(e_showing_price_inc_tax).toFixed(2) + '">';
                            tr += '<span id="span_showing_unit_price_inc_tax" class="fw-bold">' + parseFloat(e_showing_price_inc_tax).toFixed(2) + '</span>';
                            tr += '</td>';

                            tr += '<td class="text-center">';
                            tr += '<span id="span_price_type" tabindex="-1">' + all_price_type + showPrAmount + '</span>';
                            tr += '<input type="hidden" name="price_types[]" id="price_type" value="' + all_price_type + '">';
                            tr += '<input type="hidden" name="pr_amounts[]" id="pr_amount" value="' + parseFloat(e_pr_amount).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text text-center">';
                            tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
                            tr += '</td>';

                            tr += '<td class="text-center">';
                            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';

                            $('#sale_item_list').append(tr);
                            clearEditItemFileds();
                            calculateTotalAmount();
                        } else {

                            var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

                            tr.find('#item_name').val(e_item_name);
                            tr.find('#product_id').val(e_product_id);
                            tr.find('#variant_id').val(e_variant_id);
                            tr.find('#tax_ac_id').val(e_tax_ac_id);
                            tr.find('#tax_type').val(e_tax_type);
                            tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
                            tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
                            tr.find('#showing_unit_tax_amount').val(parseFloat(e_showing_tax_amount).toFixed(2));
                            tr.find('#unit_discount_type').val(e_discount_type);
                            tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
                            tr.find('#showing_unit_discount').val(parseFloat(e_showing_discount).toFixed(2));
                            tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
                            tr.find('#showing_unit_discount_amount').val(parseFloat(e_showing_discount_amount).toFixed(2));
                            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                            tr.find('#showing_unit_cost_inc_tax').val(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
                            tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                            tr.find('#showing_quantity').val(parseFloat(e_showing_quantity).toFixed(2));
                            tr.find('#span_showing_quantity').html(parseFloat(e_showing_quantity).toFixed(2));
                            tr.find('#span_unit').html(e_unit_name);
                            tr.find('#base_unit_name').val(e_base_unit_name);
                            tr.find('#unit_id').val(e_unit_id);
                            tr.find('#unit_price_exc_tax').val(parseFloat(e_price_exc_tax).toFixed(2));
                            tr.find('#showing_unit_price_exc_tax').val(parseFloat(e_showing_price_exc_tax).toFixed(2));
                            tr.find('#price_type').val(all_price_type);
                            tr.find('#pr_amount').val(parseFloat(e_pr_amount).toFixed(2));
                            tr.find('#span_price_type').html(all_price_type + showPrAmount);
                            tr.find('#unit_price_inc_tax').val(parseFloat(e_price_inc_tax).toFixed(2));
                            tr.find('#showing_unit_price_inc_tax').val(parseFloat(e_showing_price_inc_tax).toFixed(2));
                            tr.find('#span_showing_unit_price_inc_tax').html(parseFloat(e_showing_price_inc_tax).toFixed(2));
                            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                            tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                            tr.find('#is_show_emi_on_pos').val(e_is_show_emi_on_pos);
                            tr.find('#descriptions').val(e_descriptions);

                            tr.find('.unique_id').val(e_product_id + e_variant_id + warehouse_id);
                            tr.find('.unique_id').attr('id', e_product_id + e_variant_id + warehouse_id);
                            tr.find('#warehouse_id').val(warehouse_id);
                            tr.find('#stock_location_name').html(stock_location_name);

                            clearEditItemFileds();
                            calculateTotalAmount();
                        }

                        $('#add_item').html('Add');
                    } else {

                        toastr.error(data.errorMsg);
                    }
                }
            })
        });

        $(document).on('click', '#select_item', function(e) {

            var tr = $(this);
            var unique_id = tr.find('#unique_id').val();
            var warehouse_id = tr.find('#warehouse_id').val();
            var stock_location_name = tr.find('#stock_location_name').html();
            var item_name = tr.find('#item_name').val();
            var product_id = tr.find('#product_id').val();
            var variant_id = tr.find('#variant_id').val();
            var tax_ac_id = tr.find('#tax_ac_id').val();
            var tax_type = tr.find('#tax_type').val();
            var unit_tax_amount = tr.find('#unit_tax_amount').val();
            var showing_unit_tax_amount = tr.find('#showing_unit_tax_amount').val();
            var unit_discount_type = tr.find('#unit_discount_type').val();
            var unit_discount = tr.find('#unit_discount').val();
            var showing_unit_discount = tr.find('#showing_unit_discount').val();
            var unit_discount_amount = tr.find('#unit_discount_amount').val();
            var showing_unit_discount_amount = tr.find('#showing_unit_discount_amount').val();
            var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
            var showing_unit_cost_inc_tax = tr.find('#showing_unit_cost_inc_tax').val();
            var qty_limit = tr.find('#qty_limit').val();
            var quantity = tr.find('#quantity').val();
            var showing_quantity = tr.find('#showing_quantity').val();
            var unit_id = tr.find('#unit_id').val();
            var base_unit_name = tr.find('#base_unit_name').val();
            var unit_price_exc_tax = tr.find('#unit_price_exc_tax').val();
            var showing_unit_price_exc_tax = tr.find('#showing_unit_price_exc_tax').val();
            var pr_amount = tr.find('#pr_amount').val();
            var unit_price_inc_tax = tr.find('#unit_price_inc_tax').val();
            var showing_unit_price_inc_tax = tr.find('#showing_unit_price_inc_tax').val();
            var subtotal = tr.find('#subtotal').val();
            var is_show_emi_on_pos = tr.find('#is_show_emi_on_pos').val();
            var descriptions = tr.find('#descriptions').val();
            var current_stock = tr.find('#current_stock').val();

            $('#e_unit_id').empty();

            itemUnitsArray[product_id].forEach(function(unit) {

                $('#e_unit_id').append(
                    '<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                    ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                    '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                    .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details +
                    '</option>'
                );
            });

            if (is_show_emi_on_pos == 0) {

                $('#e_descriptions').prop('readonly', true);
            } else {

                $('#e_descriptions').prop('readonly', false);
            }

            $('#search_product').val(item_name);
            $('#e_unique_id').val(unique_id);
            $('#warehouse_id').val(warehouse_id);
            $('#e_stock_location_name').val(stock_location_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_unit_id').val(unit_id);
            $('#e_base_unit_name').val(base_unit_name);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2));
            $('#e_showing_quantity').val(parseFloat(showing_quantity).toFixed(2)).focus().select();
            $('#e_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
            $('#e_showing_price_exc_tax').val(parseFloat(showing_unit_price_exc_tax).toFixed(2));
            $('#e_base_unit_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
            $('#e_pr_amount').val(parseFloat(pr_amount).toFixed(2));
            $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
            $('#e_showing_discount').val(parseFloat(showing_unit_discount).toFixed(2));
            $('#e_discount_type').val(unit_discount_type);
            $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
            $('#e_showing_discount_amount').val(parseFloat(showing_unit_discount_amount).toFixed(2));
            $('#e_tax_ac_id').val(tax_ac_id);
            $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
            $('#e_showing_tax_amount').val(parseFloat(showing_unit_tax_amount).toFixed(2));
            $('#e_tax_type').val(tax_type);
            $('#e_price_inc_tax').val(parseFloat(unit_price_inc_tax).toFixed(2));
            $('#e_showing_price_inc_tax').val(parseFloat(showing_unit_price_inc_tax).toFixed(2));
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
            $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(showing_unit_cost_inc_tax).toFixed(2));
            $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);
            $('#e_descriptions').val(descriptions);
            $('#stock_quantity').val(parseFloat(current_stock).toFixed(2));

            $('#add_item').html('Edit');
        });

        function calculateEditOrAddAmount() {

            var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
            var is_base_unit = $('#e_unit_id').find('option:selected').data('is_base_unit');
            var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
            var e_base_unit_price_exc_tax = $('#e_base_unit_price_exc_tax').val() ? $('#e_base_unit_price_exc_tax').val() : 0;
            var e_showing_price_exc_tax = $('#e_showing_price_exc_tax').val() ? $('#e_showing_price_exc_tax').val() : 0.00;
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;
            var e_tax_type = $('#e_tax_type').val();
            var e_pr_amount = $('#e_pr_amount').val() ? $('#e_pr_amount').val() : 0;
            var __pr_amount = $('#all_price_type').val() == 'PR' ? parseFloat(e_pr_amount) : 0;
            var e_discount_type = $('#e_discount_type').val();
            var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;

            var quantity = roundOfValue(e_showing_quantity) * roundOfValue(base_unit_multiplier);
            $('#e_quantity').val(parseFloat(quantity));

            var unitPriceExcTax = 0;
            unitPriceExcTax = roundOfValue(e_showing_price_exc_tax) / roundOfValue(base_unit_multiplier);
            $('#e_price_exc_tax').val(roundOfValue(unitPriceExcTax));
            $('#e_base_unit_price_exc_tax').val(roundOfValue(unitPriceExcTax));

            var showingUnitCostIncTax = roundOfValue(e_unit_cost_inc_tax) * roundOfValue(base_unit_multiplier);
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(showingUnitCostIncTax).toFixed(2));
            $('#display_unit_cost').html(parseFloat(showingUnitCostIncTax).toFixed(2));

            var showing_discount_amount = 0;
            var discount_amount = 0;
            var unit_discount = 0
            if (e_discount_type == 1) {

                showing_discount_amount = roundOfValue(e_showing_discount);
                discount_amount = roundOfValue(e_showing_discount) / roundOfValue(base_unit_multiplier);
                unit_discount = roundOfValue(e_showing_discount) / roundOfValue(base_unit_multiplier);
            } else {

                showing_discount_amount = (roundOfValue(e_showing_price_exc_tax) / 100) * roundOfValue(e_showing_discount);
                discount_amount = roundOfValue(showing_discount_amount) / roundOfValue(base_unit_multiplier);
                unit_discount = roundOfValue(e_showing_discount);
            }

            var priceWithDiscount = roundOfValue(e_showing_price_exc_tax) - roundOfValue(showing_discount_amount);

            var showing_taxAmount = roundOfValue(priceWithDiscount) / 100 * roundOfValue(e_tax_percent);
            var taxAmount = roundOfValue(showing_taxAmount) / roundOfValue(base_unit_multiplier);

            var showing_unitPriceIncTax = roundOfValue(priceWithDiscount) + roundOfValue(showing_taxAmount) + roundOfValue(__pr_amount);
            var unitPriceIncTax = roundOfValue(showing_unitPriceIncTax) / roundOfValue(base_unit_multiplier);

            if (e_tax_type == 2) {

                var inclusiveTax = 100 + roundOfValue(e_tax_percent);
                var calcTax = roundOfValue(priceWithDiscount) / roundOfValue(inclusiveTax) * 100;
                var __tax_amount = roundOfValue(priceWithDiscount) - roundOfValue(calcTax);
                showing_taxAmount = __tax_amount;
                taxAmount = showing_taxAmount / roundOfValue(base_unit_multiplier);
                showing_unitPriceIncTax = roundOfValue(priceWithDiscount) + roundOfValue(showing_taxAmount) + roundOfValue(__pr_amount);
                unitPriceIncTax = roundOfValue(showing_unitPriceIncTax) / roundOfValue(base_unit_multiplier);
            }

            $('#e_discount').val(parseFloat(roundOfValue(unit_discount)));
            $('#e_discount_amount').val(parseFloat(roundOfValue(discount_amount)));
            $('#e_showing_discount_amount').val(parseFloat(roundOfValue(showing_discount_amount)));
            $('#e_showing_tax_amount').val(parseFloat(showing_taxAmount));
            $('#e_tax_amount').val(parseFloat(taxAmount));
            $('#e_showing_price_inc_tax').val(parseFloat(showing_unitPriceIncTax));
            $('#e_price_inc_tax').val(parseFloat(unitPriceIncTax));

            var subtotal = roundOfValue(unitPriceIncTax) * roundOfValue(quantity);
            $('#e_subtotal').val(parseFloat(subtotal));
        }

        $('#e_showing_quantity').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#e_unit_id').focus();
                }
            }
        });

        $('#e_unit_id').on('change keypress click', function(e) {

            var isBaseUnit = $(this).find('option:selected').data('is_base_unit');
            var baseUnitPrice = $('#e_base_unit_price_exc_tax').val() ? $('#e_base_unit_price_exc_tax').val() : 0;
            var base_unit_multiplier = $(this).find('option:selected').data('base_unit_multiplier');
            var showingPriceExcTax = roundOfValue(baseUnitPrice) * roundOfValue(base_unit_multiplier);

            $('#e_showing_price_exc_tax').val(parseFloat(showingPriceExcTax).toFixed(2));

            if (e.which == 0) {

                $('#e_showing_price_exc_tax').focus().select();
            }

            calculateEditOrAddAmount();
        });

        $('#e_showing_price_exc_tax').on('input keypress', function(e) {

            calculateEditOrAddAmount();
            var all_price_type = $('#all_price_type').val();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    if (all_price_type == 'PR') {

                        $('#e_pr_amount').focus().select();
                    } else {

                        $('#e_showing_discount').focus().select();
                    }
                }
            }
        });

        $('#e_pr_amount').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                $('#e_showing_discount').focus().select();
            }
        });

        $('#e_showing_discount').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '' && $(this).val() > 0) {

                    $('#e_discount_type').focus();
                } else {

                    $('#e_tax_ac_id').focus();
                }
            }
        });

        $('#e_discount_type').on('change keypress click', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

                $('#e_tax_ac_id').focus();
            }
        });

        $('#e_tax_ac_id').on('change keypress click', function(e) {

            calculateEditOrAddAmount();
            var val = $(this).val();
            var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
            if (e.which == 0) {

                if (val) {

                    $('#e_tax_type').focus();
                } else {

                    if (e_is_show_emi_on_pos == 1) {

                        $('#e_descriptions').focus().select();
                    } else {

                        $('#add_item').focus();
                    }
                }
            }
        });

        $('#e_tax_type').on('change keypress click', function(e) {

            calculateEditOrAddAmount();
            var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();

            if (e.which == 0) {

                if (e_is_show_emi_on_pos == 1) {

                    $('#e_descriptions').focus().select();
                } else {

                    $('#add_item').focus();
                }
            }
        });

        $('#e_descriptions').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                $('#add_item').focus();
            }
        });

        // Calculate total amount functionalitie
        function calculateTotalAmount() {

            var quantities = document.querySelectorAll('#quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            var unitTaxAmounts = document.querySelectorAll('#unit_tax_amount');

            var total_item = 0;
            var total_qty = 0;
            quantities.forEach(function(qty) {

                total_item += 1;
                total_qty += parseFloat(qty.value);
            });

            $('#total_item').val(parseFloat(total_item));
            $('#total_qty').val(parseFloat(total_qty).toFixed(2));

            // Update Net total Amount
            var netTotalAmount = 0;
            var itemTotalTaxAmount = 0;
            var i = 0;
            subtotals.forEach(function(subtotal) {

                netTotalAmount += parseFloat(subtotal.value);
                itemTotalTaxAmount += (quantities[i].value ? quantities[i].value : 0) * (unitTaxAmounts[i].value ? unitTaxAmounts[i].value : 0);
            });

            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

            if ($('#order_discount_type').val() == 2) {

                var orderDisAmount = parseFloat(netTotalAmount) / 100 * parseFloat($('#order_discount').val() ? $('#order_discount').val() : 0);
                $('#order_discount_amount').val(parseFloat(orderDisAmount).toFixed(2));
            } else {

                var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0
                $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
            }

            // Calc order tax amount
            var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
            var orderTaxPercent = $('#order_tax_ac_id').find('option:selected').data('order_tax_percent') ? $('#order_tax_ac_id').find('option:selected').data('order_tax_percent') : 0;
            var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTaxPercent);
            $('#order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));

            // Update Total payable Amount
            var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;

            var calcTotalInvoiceAmount = parseFloat(netTotalAmount) -
                parseFloat(orderDiscountAmount) +
                parseFloat(calcOrderTaxAmount) +
                parseFloat(shipmentCharge);

            $('#total_invoice_amount').val(parseFloat(calcTotalInvoiceAmount).toFixed(2));

            var receiveAmount = $('#receive_amount').val() ? $('#receive_amount').val() : 0;

            var debit_amount = $('#debit_amount').val() ? $('#debit_amount').val() : 0;
            var credit_amount = $('#credit_amount').val() ? $('#credit_amount').val() : 0;

            var currentDebitAmount = parseFloat(debit_amount) + parseFloat(calcTotalInvoiceAmount);
            var currentCreditAmount = parseFloat(credit_amount) + parseFloat(receiveAmount);

            var currentInvoiceAmount = $('#current_invoice_amount').val();
            var currentCustomerAccountId = $('#current_customer_account_id').val();
            var selectedCustomerAccountId = $('#customer_account_id').val();

            if (currentCustomerAccountId == selectedCustomerAccountId) {

                currentDebitAmount -= parseFloat(currentInvoiceAmount);
            }

            var currentBalance = bdFormat(0) + ' Dr.';
            var currentBalanceAmount = 0;
            var currentBalanceSide = 'Dr';
            if (currentDebitAmount > currentCreditAmount) {

                var sum = currentDebitAmount - currentCreditAmount;
                currentBalance = bdFormat(sum) + ' Dr.';
                currentBalanceAmount = parseFloat(sum);
                currentBalanceSide = 'Dr';
            } else if (currentCreditAmount > currentDebitAmount) {

                var sum = currentCreditAmount - currentDebitAmount;
                currentBalance = bdFormat(sum) + ' Cr.';
                currentBalanceAmount = parseFloat(sum);
                currentBalanceSide = 'Cr';
            }

            $('#current_balance').val(currentBalance);
            $('#current_balance_amount').val(parseFloat(currentBalanceAmount).toFixed(2));
            $('#current_balance_side').val(currentBalanceSide);

            var salesLedgerAmount = parseFloat(netTotalAmount) +
                parseFloat(shipmentCharge) -
                parseFloat(orderDiscountAmount) -
                parseFloat(itemTotalTaxAmount) -
                parseFloat(calcOrderTaxAmount);

            $('#sales_ledger_amount').val(salesLedgerAmount);
        }

        function clearEditItemFileds() {

            $('#search_product').val('').focus();
            $('#e_unique_id').val('');
            $('#e_item_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_base_unit_name').val('');
            $('#e_quantity').val(parseFloat(0).toFixed(2));
            $('#e_showing_quantity').val(parseFloat(0).toFixed(2));
            $('#e_price_exc_tax').val(parseFloat(0).toFixed(2));
            $('#e_showing_price_exc_tax').val(parseFloat(0).toFixed(2));
            $('#e_pr_amount').val(parseFloat(0).toFixed(2));
            $('#e_discount_type').val(1);
            $('#e_discount').val(parseFloat(0).toFixed(2));
            $('#e_showing_discount').val(parseFloat(0).toFixed(2));
            $('#e_discount_amount').val(parseFloat(0).toFixed(2));
            $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
            $('#e_tax_ac_id').val('');
            $('#e_tax_amount').val(parseFloat(0).toFixed(2));
            $('#e_showing_tax_amount').val(parseFloat(0).toFixed(2));
            $('#e_tax_type').val(1);
            $('#e_price_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_showing_price_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_subtotal').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_inc_tax').val(0);
            $('#e_showing_unit_cost_inc_tax').val(0);
            $('#e_is_show_discription').val('');
            $('#stock_quantity').val(parseFloat(0).toFixed(2));
            $('#add_item').html('Add');
        }

        // Remove product form purchase product list (Table)
        $(document).on('click', '#remove_product_btn', function(e) {
            e.preventDefault();

            $(this).closest('tr').remove();

            calculateTotalAmount();

            setTimeout(function() {

                clearEditItemFileds();
            }, 5);
        });

        // Input order discount and clculate total amount
        $(document).on('input', '#order_discount', function() {

            calculateTotalAmount();
        });

        // Input order discount type and clculate total amount
        $(document).on('change', '#order_discount_type', function() {

            calculateTotalAmount();
        });

        // Input shipment charge and clculate total amount
        $(document).on('input', '#shipment_charge', function() {

            calculateTotalAmount();
        });

        // chane purchase tax and clculate total amount
        $(document).on('change', '#order_tax_ac_id', function() {

            calculateTotalAmount();
            var orderTaxPercent = $(this).find('option:selected').data('order_tax_percent') ? $(this).find('option:selected').data('order_tax_percent') : 0;
            $('#order_tax_percent').val(parseFloat(orderTaxPercent).toFixed(2));
        });

        // Input paying amount and clculate due amount
        $(document).on('input', '#receive_amount', function() {

            calculateTotalAmount();
        });

        $(document).on('change', '#all_price_type', function() {

            $(this).val() == 'PR' ? $('#e_pr_amount').prop('readonly', false).attr("tabindex", "") : $(
                '#e_pr_amount').prop('readonly', true).attr("tabindex", "-1").val(0);

            var allPriceType = $(this).val();

            var table = $('.sale-product-table');

            table.find('tbody').find('tr').each(function() {

                $(this).find('#span_price_type').html(allPriceType);
                $(this).find('#price_type').val(allPriceType);
            });
        });
    </script>

    <script>
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
                $('#list').empty();
                $('.modal').modal('hide');
                return false;
            }
        }

        //Add Sale request by ajax
        $('#add_sale_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');

            var totalItem = $('#total_item').val();

            if (parseFloat(totalItem) == 0) {

                $('.loading_button').hide();
                toastr.error('Item table is empty.');
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
            $('#previous_due').html(parseFloat(0).toFixed(2));
            $('#total_invoice_payable').html(parseFloat(0).toFixed(2));
            $('#add_sale_form')[0].reset();
            $('#sale_item_list').empty();
            $("#customer_account_id").select2("destroy");
            $("#user_id").select2("destroy");
            $("#sale_account_id").select2("destroy");

            $("#customer_account_id").select2();
            $("#user_id").select2();
            $("#sale_account_id").select2();
            document.getElementById('user_id').focus();
        }

        $('#reset_add_or_edit_item_fields').on('click', function(e) {
            e.preventDefault();
            clearEditItemFileds();
        });

        // Automatic remove searching product is found signal
        setInterval(function() {

            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function() {

            $('#search_product').removeClass('is-valid');
        }, 1000);

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

                if ($(this).attr('id') == 'order_discount' && ($('#order_discount').val() == '' || $('#order_discount').val() == 0)) {

                    $('#order_tax_ac_id').focus();
                    return;
                }

                if ($(this).attr('id') == 'receive_amount' && ($('#receive_amount').val() == '' || $('#receive_amount').val() == 0)) {

                    $('#save_and_print').focus();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        $("#addCustomerOpeingBalanceModal").on("hidden.bs.modal", function() {

            setTimeout(function() {

                $('#sale_account_id').focus();
            }, 100);
        });

        $(document).on('click', function(e) {

            if ($(e.target).closest(".select_area").length === 0) {

                $('.select_area').hide();
                $('#list').empty();
            }
        });

        setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));

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
    </script>

    <script>
        $('#customer_account_id').on('change', function() {

            $('#closing_balance').val(parseFloat(0).toFixed(2));
            var customer_account_id = $(this).val();

            var user_id = $('#user_id').val();

            if (user_id == '') {

                $("#customer_account_id").val("");
                $("#customer_account_id").select2("destroy");
                $("#customer_account_id").select2();
                toastr.error('Please Select a user.');
                return;
            }

            if (customer_account_id) {

                getCustomerAmountsUserWise(user_id, customer_account_id);
            }
        });

        $('#user_id').on('change', function() {

            $('#closing_balance').val(parseFloat(0).toFixed(2));
            var customer_account_id = $('#customer_account_id').val();

            var user_id = $(this).val();

            if (user_id == '') {

                return;
            }

            if (customer_account_id) {

                getCustomerAmountsUserWise(user_id, customer_account_id);
            }
        });

        $('#add_customer_opening_balance').on('submit', function(e) {
            e.preventDefault();

            $('.op_loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.op_loading_button').hide();
                    var user_id = $('#user_id').val();
                    var customer_account_id = $('#customer_account_id').val();
                    getCustomerAmountsUserWise(user_id, customer_account_id, false);
                    $('#addCustomerOpeingBalanceModal').modal('hide');
                    $('#add_customer_opening_balance')[0].reset();
                },
                error: function(err) {
                    $('.op_loading_button').hide();

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

        function getCustomerAmountsUserWise(user_id, customer_account_id, is_show_modal = true) {

            filterObj = {
                user_id: user_id,
                from_date: null,
                to_date: null,
            };

            var url = "{{ route('vouchers.journals.user.wise.customer.closing.balance', ':account_id') }}";
            var route = url.replace(':account_id', customer_account_id);

            $.ajax({
                url: route,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    $('#closing_balance').val(data['closing_balance_string']);
                    $('#debit_amount').val(data['all_total_debit']);
                    $('#credit_amount').val(data['all_total_credit']);
                    $('.op_customer_name').html($('#customer_account_id').find('option:selected').data('customer_name'));
                    $('.op_customer_phone').html($('#customer_account_id').find('option:selected').data('customer_phone'));
                    $('.op_user_name').html($('#user_id').find('option:selected').data('user_name'));
                    $('#op_user_id').val(user_id);
                    $('#op_customer_account_id').val(customer_account_id);

                    calculateTotalAmount();

                    if (is_show_modal) {

                        if (data['opening_balance'] == 0) {

                            $('#addCustomerOpeingBalanceModal').modal('show');
                        }
                    }
                }
            });
        }

        $('#addCustomer').on('click', function() {

            $.get("{{ route('contacts.customers.create.basic.modal') }}", function(data) {

                $('#add_customer_basic_modal').html(data);
                $('#add_customer_basic_modal').modal('show');

            });
        });

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
                        $('.error').html('');
                        $('.quick_product_loading_button').hide();
                        $('#addQuickProductModal').modal('hide');
                        toastr.success('Item create Successfully.');

                        var product = data.item;
                        $('#stock_quantity').val(parseFloat(product.quantity).toFixed(2));

                        var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' :
                            product.name;

                        var warehouse_id = data.warehouse_id != null ? data.warehouse_id : '';
                        $('#warehouse_id').val(warehouse_id);

                        $('#search_product').val(name);
                        $('#e_item_name').val(name);
                        $('#e_product_id').val(product.id);
                        $('#e_variant_id').val('noid');
                        $('#e_base_unit_name').val(product.unit.name);
                        $('#e_showing_quantity').val(parseFloat(product.quantity).toFixed(2)).focus()
                            .select();
                        $('#e_showing_price_exc_tax').val(parseFloat(product.product_price).toFixed(2));
                        $('#e_pr_amount').val(parseFloat(0).toFixed(2));
                        $('#e_showing_discount').val(parseFloat(0).toFixed(2));
                        $('#e_discount_type').val(1);
                        $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
                        $('#e_tax_ac_id').val(product.tax_ac_id);
                        $('#e_tax_type').val(product.tax_type);
                        $('#e_unit_cost_inc_tax').val(product.product_cost_with_tax);
                        $('#e_showing_unit_cost_inc_tax').val(product.product_cost_with_tax);
                        $('#display_unit_cost').html(product.product_cost_with_tax);
                        $('#stock_quantity').val(parseFloat(product.quantity).toFixed(2));
                        $('#e_is_show_emi_on_pos').val(product.is_show_emi_on_pos);

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

                        if (product.is_show_emi_on_pos) {

                            $('#e_descriptions').prop('readonly', true);
                        } else {

                            $('#e_descriptions').prop('readonly', false);
                        }

                        calculateEditOrAddAmount();

                        $('#add_item').html('Add');
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

                            $('.error_quick_product_' + key + '').html(error[0]);
                        });
                    }
                });

                if (isAjaxIn == false) {

                    isAllowSubmit = true;
                }
            });
        @endif

        document.getElementById('user_id').focus();

        function roundOfValue(val) {

            return ((parseFloat(val) * 1000) / 1000);
        }
    </script>
@endpush
