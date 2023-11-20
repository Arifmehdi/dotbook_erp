@extends('layout.master')
@push('css')
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
            border: 1px solid #706a6d;
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
            border: 1px solid lightgray;
            margin: 2px 0px;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70;
            color: #fff !important;
        }

        .input-group-text-sale {
            font-size: 7px !important;
        }

        .border_red {
            border: 1px solid red !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        .number-fields label {
            font-size: 10px !important;
        }

        .input_si {
            font-size: 9px !important;
        }

        ul.ton_ul li {
            padding: 2px 6px;
            border: 1px solid gray;
            margin-top: 4px;
        }

        ul.ton_ul li a {
            display: block;
            font-size: 20px;
            font-weight: 400;
            color: #000;
        }

        .form_element {
            margin: 2px 0;
        }

        .style-btn {
            margin-top: 1px;
        }
    </style>
    
@endpush
@section('title', 'Edit Sales Order - ')
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>@lang('menu.edit_sales_order')</h6>
            <x-back-button />
        </div>
        <div class="container-fluid p-15">
            <form id="edit_sales_order_form" action="{{ route('sales.order.update', $order->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="warehouse_id" id="warehouse_id" />

                <div class="form_element mt-0 mb-1">

                    <div class="element-body">
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                @if (auth()->user()->is_marketing_user == 0)
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.sr') </b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input type="hidden" name="user_count" id="user_count" value="1">
                                            <select required name="user_id" id="user_id" class="form-control select2 form-select" data-next="customer_account_id">
                                                <option value="">@lang('menu.select_sr')</option>
                                                @foreach ($users as $user)
                                                    <option {{ $order->sr_user_id == $user->id ? 'SELECTED' : '' }} data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}" value="{{ $user->id }}">
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
                                    <label class="col-4"><b>@lang('menu.customer') </b> <span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <div class="input-group select-customer-input-group">
                                            <div style="display: inline-block; margin-bottom: 2px;" class="select-half">
                                                <select required style="margin-bottom: 2px;" name="customer_account_id" class="form-control select2 form-select" id="customer_account_id" data-next="price_group_id">
                                                    <option value="">@lang('menu.select_customer')</option>
                                                    @foreach ($customerAccounts as $customer)
                                                        <option {{ $order->customer_account_id == $customer->id ? 'SELECTED' : '' }} data-customer_name="{{ $customer->name }}" data-customer_phone="{{ $customer->phone }}" value="{{ $customer->id }}">
                                                            {{ $customer->name . '/' . $customer->phone }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div style="display: inline-block;" class="style-btn">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text add_button mr-1 {{ !auth()->user()->can('customer_add')? 'disabled_element': '' }}" id="addCustomer"><i class="fas fa-plus-square text-dark"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <span class="error error_customer_account_id"></span>
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-4 text-danger"><b>@lang('menu.curr_bal') </b></label>
                                    <div class="col-8">
                                        @php
                                            $accountUtil = new App\Utils\AccountUtil();
                                            $amounts = $accountUtil->accountClosingBalance($order->customer_account_id, $order->sr_user_id);
                                        @endphp
                                        <input readonly type="text" class="form-control fw-bold" id="current_balance" value="{{ $amounts['closing_balance_string'] }}" tabindex="-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="input-group">
                                    <label class="col-4"><b>@lang('menu.order_id') </b></label>
                                    <div class="col-8">
                                        <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control fw-bold" value="{{ $order->order_id }}" data-next="status">
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-4"><b>@lang('menu.status') </b></label>
                                    <div class="col-8">
                                        <select name="status" class="form-control form-select" id="status" data-next="all_price_type">
                                            <option value="3">@lang('menu.order')</option>
                                            <option {{ $order->do_status == 1 ? 'SELECTED' : '' }} value="7">
                                                @lang('menu.delivery_order')</option>
                                        </select>
                                        <span class="error error_status"></span>
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-4"><b>@lang('menu.rate_type')</b> <span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <select required name="all_price_type" class="form-control form-select" id="all_price_type" data-next="date">
                                            <option value="">@lang('menu.select_rate_type')</option>
                                            <option {{ $order->all_price_type == 'PR' ? 'SELECTED' : '' }} value="PR">
                                                PR</option>
                                            <option {{ $order->all_price_type == 'MR' ? 'SELECTED' : '' }} value="MR">
                                                MR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="input-group">
                                    <label class="col-4"> <b>@lang('menu.date') </b> <span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <input type="text" name="date" class="form-control" id="date" autocomplete="off" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($order->order_date)) }}" data-next="price_group_id">
                                        <span class="error error_date"></span>
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-4"><b>@lang('menu.price_group') </b></label>
                                    <div class="col-8">
                                        <select name="price_group_id" class="form-control form-select" id="price_group_id" data-next="sale_account_id">
                                            <option value="">@lang('menu.default_selling_price')</option>
                                            @foreach ($price_groups as $pg)
                                                <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class=" col-4"> <b>@lang('menu.sale_account') </b> <span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <select name="sale_account_id" class="form-control select2 form-select" id="sale_account_id" data-next="expire_date">
                                            @foreach ($saleAccounts as $orderAc)
                                                <option {{ $order->sale_account_id == $orderAc->id ? 'SELECTED' : '' }} value="{{ $orderAc->id }}">{{ $orderAc->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error error_sale_account_id"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="input-group">
                                    <label class="col-4"><b>@lang('menu.expire_date') </b></label>
                                    <div class="col-8">
                                        <input type="text" name="expire_date" class="form-control" id="expire_date" value="{{ $order->expire_date ? $order->expire_date : '' }}" data-next="expire_time" autocomplete="off">
                                        <span class="error error_expire_date"></span>
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-4"><b>@lang('menu.expire_time') </b></label>
                                    <div class="col-8">
                                        <input type="time" name="expire_time" class="form-control" id="expire_time" value="{{ $order->expire_date ? date('H:i:s', strtotime($order->expire_date)) : '' }}" data-next="search_product" autocomplete="off">
                                        <span class="error error_expire_date"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section>
                    <div class="sale-content">
                        <div class="row g-1">
                            <div class="col-xl-9 col-md-7">
                                <div class="item-details-sec mb-1">
                                    <div class="content-inner">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-xl-4">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="fw-bold">@lang('menu.search_item')</label>
                                                    <div class="input-group">
                                                        <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="@lang('menu.search_item_item_code_scan_bar_code')" autocomplete="off" autofocus>
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
                                                <input type="hidden" id="e_unit_cost_inc_tax">
                                                <input type="hidden" id="e_showing_unit_cost_inc_tax">
                                                <input type="hidden" id="e_item_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">
                                                <input type="hidden" id="e_base_unit_name">
                                                <input type="hidden" id="e_tax_amount">
                                                <input type="hidden" id="e_showing_tax_amount">
                                                <input type="hidden" id="e_price_inc_tax">
                                                <input type="hidden" id="e_showing_price_inc_tax">
                                                <input type="hidden" id="e_is_show_emi_on_pos">
                                                <input type="hidden" id="e_base_unit_price_exc_tax">
                                            </div>

                                            <div class="col-xl-8">
                                                <div class="row g-2">
                                                    <div class="col-xl-4 col-md-6">
                                                        <label class="fw-bold">@lang('menu.order_quantity')</label>
                                                        <div class="input-group">
                                                            <input type="number" step="any" class="form-control fw-bold w-60" id="e_showing_quantity" placeholder="@lang('menu.quantity')" value="0.00">
                                                            <input type="hidden" id="e_quantity" value="0.00">
                                                            <select id="e_unit_id" class="form-control w-40 form-select">
                                                                <option value="">@lang('menu.unit')</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-4 col-md-6">
                                                        <label class="fw-bold">@lang('menu.per')
                                                            @lang('menu.unit_price_exc_tax')</label>
                                                        <input {{ auth()->user()->can('edit_price_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_showing_price_exc_tax" placeholder="@lang('menu.price_exclude_tax')" value="0.00">
                                                        <input type="hidden" id="e_price_exc_tax" value="0.00">
                                                    </div>

                                                    <div class="col-xl-4 col-md-6">
                                                        <label class="fw-bold">@lang('menu.pr_Amount')</label>
                                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_pr_amount" value="0.00" tabindex="-1" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-5">
                                                <div class="row g-2">
                                                    <div class="col-xl-6 col-md-6">
                                                        <label class="fw-bold">@lang('menu.discount') (@lang('menu.per')
                                                            @lang('menu.unit'))</label>
                                                        <div class="input-group">
                                                            <input {{ auth()->user()->can('edit_discount_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_showing_discount" placeholder="@lang('menu.discount')" value="0.00">
                                                            <input type="hidden" id="e_discount" value="0.00">
                                                            <select id="e_discount_type" class="form-control form-select">
                                                                <option value="1">@lang('menu.fixed')(0.00)</option>
                                                                <option value="2">@lang('menu.percentage')(%)</option>
                                                            </select>
                                                            <input type="hidden" id="e_discount_amount">
                                                            <input type="hidden" id="e_showing_discount_amount">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-6 col-md-6">
                                                        <label class="fw-bold">@lang('menu.tax') (@lang('menu.per')
                                                            @lang('menu.unit'))</label>
                                                        <select id="e_tax_ac_id" class="form-control form-select">
                                                            <option data-product_tax_percent="0.00" value="">
                                                                @lang('menu.no_tax')</option>
                                                            @foreach ($taxAccounts as $taxAccount)
                                                                <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                    {{ $taxAccount->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-7">
                                                <div class="row g-2 align-items-end">
                                                    <div class="col-xl-4 col-md-6">
                                                        <label class="fw-bold">@lang('menu.tax_type')</label>
                                                        <select id="e_tax_type" class="form-control form-select" tabindex="-1">
                                                            <option value="1">@lang('menu.exclusive')</option>
                                                            <option value="2">@lang('menu.inclusive')</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-xl-4 col-md-6">
                                                        <label class="fw-bold">@lang('menu.sub_total')</label>
                                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                                    </div>

                                                    <div class="col-xl-4 col-md-6 mt-2">
                                                        <div class="btn-box-2">
                                                            <a href="#" class="btn btn-sm btn-success" id="add_item">@lang('menu.add')</a>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table sale-product-table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-startx">@lang('menu.item')</th>
                                                                    <th class="text-center">@lang('menu.quantity')</th>
                                                                    <th>@lang('menu.unit')</th>
                                                                    <th class="text-center">@lang('menu.price_inc_tax')</th>
                                                                    <th class="text-center">@lang('menu.rate_type')</th>
                                                                    <th>@lang('menu.sub_total')</th>
                                                                    <th><i class="fas fa-minus text-dark"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="order_item_list">
                                                                @php
                                                                    $itemUnitsArray = [];
                                                                @endphp
                                                                @foreach ($order->saleProducts as $saleProduct)
                                                                    @php
                                                                        if (isset($saleProduct->product_id)) {
                                                                            $itemUnitsArray[$saleProduct->product_id][] = [
                                                                                'unit_id' => $saleProduct->product->unit->id,
                                                                                'unit_name' => $saleProduct->product->unit->name,
                                                                                'unit_code_name' => $saleProduct->product->unit->code_name,
                                                                                'base_unit_multiplier' => 1,
                                                                                'multiplier_details' => '',
                                                                                'is_base_unit' => 1,
                                                                            ];
                                                                        }

                                                                        if (count($saleProduct?->product?->unit?->childUnits) > 0) {
                                                                            foreach ($saleProduct?->product?->unit?->childUnits as $unit) {
                                                                                $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $saleProduct?->product?->unit?->name . ')';

                                                                                array_push($itemUnitsArray[$saleProduct->product_id], [
                                                                                    'unit_id' => $unit->id,
                                                                                    'unit_name' => $unit->name,
                                                                                    'unit_code_name' => $unit->code_name,
                                                                                    'base_unit_multiplier' => $unit->base_unit_multiplier,
                                                                                    'multiplier_details' => $multiplierDetails,
                                                                                    'is_base_unit' => 0,
                                                                                ]);
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    <tr id="select_item">
                                                                        <td>
                                                                            @php
                                                                                $variant = $saleProduct->product_variant_id ? ' - ' . $saleProduct->variant->variant_name : '';
                                                                                $variantId = $saleProduct->product_variant_id ? $saleProduct->product_variant_id : 'noid';

                                                                                $baseUnitMultiplier = $saleProduct?->saleUnit?->base_unit_multiplier ? $saleProduct?->saleUnit?->base_unit_multiplier : 1;
                                                                            @endphp

                                                                            <span class="product_name">{{ $saleProduct->product->name . $variant }}</span>
                                                                            <input type="hidden" name="is_show_emi_on_pos[]" id="is_show_emi_on_pos" id="dsc" value="{{ $saleProduct->product->is_show_emi_on_pos }}">
                                                                            <input type="hidden" name="descriptions[]" id="descriptions" value="">
                                                                            <input type="hidden" id="item_name" value="{{ $saleProduct->product->name . $variant }}">
                                                                            <input type="hidden" id="product_id" name="product_ids[]" value="{{ $saleProduct->product_id }}">
                                                                            <input type="hidden" name="variant_ids[]" class="variantId-{{ $variantId }}" id="variant_id" value="{{ $variantId }}">
                                                                            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $saleProduct->tax_ac_id }}">
                                                                            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $saleProduct->tax_type }}">
                                                                            <input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="{{ $saleProduct->unit_tax_percent }}">
                                                                            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $saleProduct->unit_tax_amount }}">

                                                                            @php
                                                                                $showingTaxAmount = $saleProduct->unit_tax_amount * $baseUnitMultiplier;
                                                                            @endphp

                                                                            <input type="hidden" id="showing_unit_tax_amount" value="{{ bcadd($showingTaxAmount, 0, 2) }}">
                                                                            @php
                                                                                $showingUnitDiscount = $saleProduct->unit_discount_type == 1 ? $saleProduct->unit_discount * $baseUnitMultiplier : $saleProduct->unit_discount;
                                                                            @endphp
                                                                            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $saleProduct->unit_discount_type }}">
                                                                            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $saleProduct->unit_discount }}">
                                                                            <input type="hidden" id="showing_unit_discount" value="{{ $showingUnitDiscount }}">
                                                                            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $saleProduct->unit_discount_amount }}">
                                                                            <input type="hidden" id="showing_unit_discount_amount" value="{{ $saleProduct->unit_discount_amount * $baseUnitMultiplier }}">
                                                                            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $saleProduct->unit_cost_inc_tax }}">

                                                                            @php
                                                                                $showingUnitCostIncTax = $saleProduct->unit_cost_inc_tax * $baseUnitMultiplier;
                                                                            @endphp

                                                                            <input type="hidden" id="showing_unit_cost_inc_tax" value="{{ bcadd($showingUnitCostIncTax, 0, 2) }}">
                                                                            <input type="hidden" class="unique_id" id="{{ $saleProduct->product_id . $variantId }}" value="{{ $saleProduct->product_id . $variantId }}">
                                                                            <input type="hidden" name="order_product_ids[]" value="{{ $saleProduct->id }}">
                                                                        </td>

                                                                        <td>
                                                                            @php
                                                                                $showingQuantity = $saleProduct->ordered_quantity / $baseUnitMultiplier;
                                                                            @endphp
                                                                            <span id="span_showing_quantity" class="fw-bold">{{ bcadd($showingQuantity, 0, 2) }}</span>
                                                                            <input type="hidden" name="quantities[]" id="quantity" value="{{ $saleProduct->ordered_quantity }}">
                                                                            <input type="hidden" id="showing_quantity" value="{{ bcadd($showingQuantity, 0, 2) }}">
                                                                        </td>

                                                                        <td class="text">
                                                                            <span id="span_unit">{{ $saleProduct?->saleUnit?->name }}</span>
                                                                            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $saleProduct?->saleUnit?->id }}">
                                                                            <input type="hidden" id="base_unit_name" value="{{ $saleProduct?->product?->unit?->name }}">
                                                                        </td>

                                                                        <td>
                                                                            <input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="{{ $saleProduct->unit_price_exc_tax }}">

                                                                            @php
                                                                                $showingPriceExcTax = $saleProduct->unit_price_exc_tax * $baseUnitMultiplier;
                                                                            @endphp

                                                                            <input type="hidden" id="showing_unit_price_exc_tax" value="{{ bcadd($showingPriceExcTax, 0, 2) }}">
                                                                            <input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="{{ $saleProduct->unit_price_inc_tax }}">

                                                                            @php
                                                                                $showingPriceIncTax = $saleProduct->unit_price_inc_tax * $baseUnitMultiplier;
                                                                            @endphp

                                                                            <input type="hidden" id="showing_unit_price_inc_tax" value="{{ bcadd($showingPriceIncTax, 0, 2) }}">
                                                                            <span id="span_showing_unit_price_inc_tax" class="fw-bold">{{ bcadd($showingPriceIncTax, 0, 2) }}</span>
                                                                        </td>

                                                                        <td class="text-center">
                                                                            @php
                                                                                $showPrAmount = $saleProduct->price_type == 'PR' ? '(' . $saleProduct->pr_amount . ')' : '';
                                                                            @endphp
                                                                            <span id="span_price_type">{{ $saleProduct->price_type . $showPrAmount }}</span>
                                                                            <input type="hidden" name="price_types[]" id="price_type" value="{{ $saleProduct->price_type }}">
                                                                            <input type="hidden" name="pr_amounts[]" id="pr_amount" value="{{ $saleProduct->pr_amount }}">
                                                                        </td>

                                                                        <td class="text text-center">
                                                                            <strong><span id="span_subtotal" class="fw-bold">{{ $saleProduct->subtotal }}</span></strong>
                                                                            <input readonly type="hidden" name="subtotals[]" id="subtotal" value="{{ $saleProduct->subtotal }}" tabindex="-1">
                                                                        </td>

                                                                        <td class="text-center">
                                                                            <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row mt-1">
                                            <div class="col-md-4">
                                                <label class="d-flex">
                                                    <select name="comment" class="form-control form-select" id="comment" data-next="sale_note">
                                                        <option value="">@lang('menu.select_comment')</option>
                                                        <option {{ $order->comment == 'Straight Local' ? 'SELECTED' : '' }} value="Straight Local">@lang('menu.straight_local')</option>
                                                        <option {{ $order->comment == 'Straight District' ? 'SELECTED' : '' }} value="Straight District">@lang('menu.straight_district')</option>
                                                        <option {{ $order->comment == 'Bend Local' ? 'SELECTED' : '' }} value="Bend Local">@lang('menu.bend_local')</option>
                                                        <option {{ $order->comment == 'Bend District' ? 'SELECTED' : '' }} value="Bend District">@lang('menu.bend_district')</option>
                                                    </select>
                                                </label>
                                                <input name="sale_note" class="form-control mt-1" id="sale_note" value="{{ $order->sale_note }}" data-next="receiver_phone" placeholder="@lang('menu.sale_note')">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="d-flex">
                                                    <input name="receiver_phone" class="form-control" id="receiver_phone" placeholder="@lang('menu.receiver_phone') Number" value="{{ $order->receiver_phone }}" data-next="shipping_address" />
                                                </label>
                                                <input name="shipping_address" class="form-control mt-1" id="shipping_address" value="{{ $order->shipping_address }}" data-next="price_adjustment_note" placeholder="@lang('menu.shipping_address')">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="d-flex">
                                                    <input name="price_adjustment_note" class="form-control" id="price_adjustment_note" placeholder="@lang('menu.price_adjustment_note')" value="{{ $order->price_adjustment_note }}" data-next="payment_note" />
                                                </label>
                                                <input name="payment_note" class="form-control mt-1" id="payment_note" value="{{ $order->payment_note }}" data-next="order_discount" placeholder="@lang('menu.payment_note')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-5">
                                <div class="item-details-sec mb-3 number-fields">
                                    <div class="content-inner">
                                        <div class="row g-1">
                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.total_qty')</label>
                                                    <div class="col-sm-7">
                                                        <input type="hidden" name="total_item" id="total_item" value="{{ $order->total_item }}" tabindex="-1">
                                                        <input readonly type="number" step="any" name="total_qty" class="form-control" id="total_qty" value="{{ $order->total_ordered_qty }}" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.net_total') </label>
                                                    <div class="col-sm-7">
                                                        <input readonly type="number" step="any" class="form-control" name="net_total_amount" id="net_total_amount" value="{{ $order->net_total_amount }}" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.discount') </label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <input name="order_discount" type="number" step="any" class="form-control" id="order_discount" value="{{ $order->order_discount }}" data-next="order_discount_type">
                                                            <input name="order_discount_amount" type="number" step="any" class="d-none" id="order_discount_amount" value="{{ $order->order_discount_amount }}">

                                                            <select name="order_discount_type" class="form-control form-select" id="order_discount_type" data-next="order_tax_ac_id">
                                                                <option {{ $order->order_discount_type == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.fixed')</option>
                                                                <option {{ $order->order_discount_type == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.percentage')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.order_tax') </label>
                                                    <div class="col-sm-7">
                                                        <select name="order_tax_ac_id" class="form-control form-select" id="order_tax_ac_id" data-next="shipment_charge">
                                                            <option data-order_tax_percent="0.00" value="">
                                                                @lang('menu.no_tax')</option>
                                                            @foreach ($taxAccounts as $taxAccount)
                                                                <option {{ $order->tax_ac_id == $taxAccount->id ? 'SELECTED' : '' }} data-order_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                    {{ $taxAccount->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="number" step="any" class="d-none" name="order_tax_percent" id="order_tax_percent" value="{{ $order->order_tax_percent }}">
                                                        <input type="number" step="any" class="d-none" name="order_tax_amount" id="order_tax_amount" value="{{ $order->order_tax_amount }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.shipment_cost') </label>
                                                    <div class="col-sm-7">
                                                        <input name="shipment_charge" type="number" step="any" class="form-control" id="shipment_charge" value="{{ $order->shipment_charge }}" data-next="receive_amount">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="col-sm-5 text-end fw-bold">@lang('menu.total_order_amt') </label>
                                                    <div class="col-sm-7">
                                                        <input readonly class="form-control" type="number" step="any" name="total_invoice_amount" id="total_invoice_amount" value="{{ $order->total_payable_amount }}" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="payment_body">
                                                    <div class="row g-1">
                                                        <div class="col-12">
                                                            <div class="row">
                                                                <label class="col-sm-5 text-end fw-bold">@lang('menu.receive_amount')
                                                                    >></label>
                                                                <div class="col-sm-7">
                                                                    <input {{ !auth()->user()->can('receipts_add')? 'readonly': '' }} type="number" step="any" name="receive_amount" class="form-control" id="receive_amount" value="0.00" data-next="payment_method_id" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="row">
                                                                <label class="col-sm-5 text-end fw-bold">@lang('menu.receipt_type')
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
                                                            <div class="row">
                                                                <label class="col-sm-5 text-end fw-bold">@lang('menu.debit_account')
                                                                    <span class="text-danger">*</span></label>
                                                                <div class="col-sm-7">
                                                                    <select name="account_id" class="form-control select2 form-select" id="account_id" data-next="transaction_no">
                                                                        <option value="">Select Debit A/c</option>
                                                                        @foreach ($accounts as $account)
                                                                            <option value="{{ $account->id }}">
                                                                                @php
                                                                                    $bank = $account->bank ? ', Bank: ' . $account->bank : '';
                                                                                    $ac_no = $account->account_number ? ', A/c No: ' . '***' . substr($account->account_number, -4) : '';
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
                                                                    <input type="number" step="any" name="transaction_no" class="form-control" id="transaction_no" placeholder="Transaction Number" data-next="cheque_no" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="row">
                                                                <label class="col-sm-5 text-end fw-bold">@lang('menu.cheque_no')</label>
                                                                <div class="col-sm-7">
                                                                    <input type="text" step="any" name="cheque_no" class="form-control" id="cheque_no" placeholder="Cheque Number" data-next="save" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row justify-content-center mt-2">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <div class="loading-btn-box">
                                                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                                    <button type="submit" id="save" class="btn btn-success submit_button">@lang('menu.save_change') </button>
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

    <x-shortcut-key-bar.shortcut-key-bar :items="[['key' => 'Ctrl + Enter', 'value' => __('menu.save_change')], ['key' => 'Alt + C', 'value' => __('menu.add_customer')], ['key' => 'Alt + I', 'value' => __('menu.add_item')]]">
    </x-shortcut-key-bar.shortcut-key-bar>

    <!--Add Customer Opening Balance Modal-->
    <div class="modal fade" id="addCustomerOpeingBalanceModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
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
                                <p><strong>@lang('menu.customer'): </strong> <span class="op_customer_name"></span></p>
                                <p><strong>@lang('menu.phone_no'). : </strong> <span class="op_customer_phone"></span></p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>@lang('menu.sr'): </strong> <span class="op_user_name"></span></p>
                            </div>

                            <div class="col-md-12 mt-2">
                                <label><b>@lang('menu.opening_balance') </b> </label>
                                <div class="input-group">
                                    <input type="number" step="any" name="opening_balance" class="form-control w-65" placeholder="@lang('menu.opening_balance')">
                                    <select name="opening_balance_type" class="select form-control w-35 form-select">
                                        <option value="debit">@lang('menu.debit')</option>
                                        <option value="credit">@lang('menu.credit')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="row">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="never_show_again" id="never_show_again" class="is_show_again">&nbsp;<b>@lang('menu.never_show_again').</b>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
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

        var itemUnitsArray = @json($itemUnitsArray);
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

                toastr.error('Please a rate type first.');
                return;
            }

            $('.variant_list_area').empty();
            $('.select_area').hide();
            var keyWord = $(this).val();
            var __keyWord = keyWord.replaceAll('/', '~');
            var __priceGroupId = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';

            delay(function() {
                searchProduct(__keyWord, __priceGroupId);
            }, 200); //sendAjaxical is the name of remote-command
        });

        function searchProduct(keyWord, priceGroupId) {

            $('#search_product').focus();
            var type = 'sales_order';
            var isShowNotForSaleItem = 1;
            var url =
                "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem', ':priceGroupId', ':type', $order->id]) }}";
            var route = url.replace(':keyWord', keyWord);
            route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);
            route = route.replace(':priceGroupId', priceGroupId);
            route = route.replace(':type', type);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(product) {

                    if (!$.isEmptyObject(product.errorMsg || keyWord == '')) {

                        toastr.error(product.errorMsg);
                        $('#search_product').val("");
                        $('.select_area').hide();
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

                                var price = 0;
                                var __price = price_groups.filter(function(value) {

                                    return value.price_group_id == priceGroupId && value.product_id ==
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
                                $('#e_showing_discount').val(parseFloat(discount.discount_amount).toFixed(2));
                                $('#e_discount_type').val(discount.discount_type);
                                $('#e_showing_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                                $('#e_tax_ac_id').val(product.tax_ac_id);
                                $('#e_tax_type').val(product.tax_type);
                                $('#e_unit_cost_inc_tax').val(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax);
                                $('#e_is_show_emi_on_pos').val(product.is_show_emi_on_pos);
                                $('#e_base_unit_price_exc_tax').val(price);

                                $('#e_unit_id').empty();
                                $('#e_unit_id').append(
                                    '<option value="' + product.unit.id +
                                    '" data-is_base_unit="1" data-unit_name="' + product.unit.name +
                                    '" data-base_unit_multiplier="1">' + product.unit.name + '</option>'
                                );

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

                                        $('#e_unit_id').append(
                                            '<option value="' + unit.id +
                                            '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                            '" data-base_unit_multiplier="' + unit
                                            .base_unit_multiplier + '">' + unit.name +
                                            multiplierDetails + '</option>'
                                        );
                                    });
                                }

                                calculateEditOrAddAmount();
                            } else {

                                var li = "";
                                $.each(product.variants, function(key, variant) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    var price = 0;
                                    var __price = price_groups.filter(function(value) {

                                        return value.price_group_id == priceGroupId && value.product_id == product.id && value.variant_id == variant.id;
                                    });

                                    if (__price.length != 0) {

                                        price = __price[0].price ? __price[0].price : variant.variant_price;
                                    } else {

                                        price = variant.variant_price;
                                    }

                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_code="' + variant.variant_code + '" data-p_price_exc_tax="' + price + '" data-v_name="' + variant.variant_name + '" data-p_cost_inc_tax="' + (variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax) + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if (!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();
                            $('#search_product').val('');

                            var variant_product = product.variant_product;

                            var price = 0;
                            var __price = price_groups.filter(function(value) {

                                return value.price_group_id == priceGroupId && value.product_id == variant_product.product.id && value.variant_id == variant_product.id;
                            });

                            if (__price.length != 0) {

                                price = __price[0].price ? __price[0].price : variant_product.variant_price;
                            } else {

                                price = variant_product.variant_price;
                            }

                            var discount_amount = 0;
                            if (discount.discount_type == 1) {

                                discount_amount = discount.discount_amount
                            } else {

                                discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                            }

                            var name = variant_product.product.name.length > 35 ? variant_product.product.name.substring(0, 35) + '...' : variant_product.product.name;

                            $('#search_product').val(name + variant_product.variant_name);
                            $('#e_item_name').val(name + variant_product.variant_name);
                            $('#e_product_id').val(variant_product.product.id);
                            $('#e_variant_id').val(variant_product.id);
                            $('#e_base_unit_name').val(variant_product.product.unit.name);
                            $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                            $('#e_showing_discount').val(parseFloat(discount.discount_amount).toFixed(2));
                            $('#e_discount_type').val(discount.discount_type);
                            $('#e_showing_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                            $('#e_tax_ac_id').val(variant_product.product.tax_id);
                            $('#e_tax_type').val(variant_product.product.tax_type);
                            $('#e_unit_cost_inc_tax').val(variant_product.update_variant_cost ? variant_product.update_variant_cost.net_unit_cost : variant_product.variant_cost_with_tax);
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

                                    var multiplierDetails = '(1 ' + unit.name + ' = ' + unit
                                        .base_unit_multiplier + '/' + unit.name + ')';

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

                            calculateEditOrAddAmount();
                        } else if (!$.isEmptyObject(product.namedProducts)) {

                            if (product.namedProducts.length > 0) {

                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

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
                                        li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-v_name="' + product.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_code="' + product.variant_code + '" data-p_price_exc_tax="' + price + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
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
                                        li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-v_name="" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="' + product.name + '" data-p_code="' + product.product_code + '" data-p_price_exc_tax="' + price + '" data-tax_type="' + product.tax_type + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_cost_inc_tax="' + product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
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

        // select single product and add stock adjustment table
        function selectProduct(e) {

            var price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';
            $('.select_area').hide();
            $('#search_product').val('');

            var product_id = e.getAttribute('data-p_id');
            var variant_id = e.getAttribute('data-v_id') ? e.getAttribute('data-v_id') : 'noid';
            var is_manage_stock = e.getAttribute('data-is_manage_stock');
            var product_name = e.getAttribute('data-p_name');
            var variant_name = e.getAttribute('data-v_name');
            var product_code = e.getAttribute('data-p_code');
            var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
            var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
            var p_tax_ac_id = e.getAttribute('data-p_tax_ac_id') != null ? e.getAttribute('data-p_tax_ac_id') : '';
            var p_tax_id = e.getAttribute('data-tax_id');
            var p_tax_type = e.getAttribute('data-tax_type');
            var is_show_emi_on_pos = e.getAttribute('data-is_show_emi_on_pos');
            $('#search_product').val('');

            var url = "{{ route('general.product.search.check.product.discount', [':product_id', ':price_group_id']) }}"
            var route = url.replace(':product_id', product_id);
            route = route.replace(':price_group_id', price_group_id);

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(data) {

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
                    $('#display_unit_cost').html(product_cost_inc_tax);
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

                            var multiplierDetails = '(1 ' + unit.name + ' = ' + unit
                                .base_unit_multiplier + '/' + data.unit.name + ')';

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
                                '" data-base_unit_multiplier="' + unit.base_unit_multiplier + '">' +
                                unit.name + multiplierDetails + '</option>'
                            );
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
            var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_showing_price_exc_tax = $('#e_showing_price_exc_tax').val() ? $('#e_showing_price_exc_tax').val() : 0;
            var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
            var all_price_type = $('#all_price_type').val() ? $('#all_price_type').val() : 'N/A';
            var e_pr_amount = $('#all_price_type').val() == 'PR' ? ($('#e_pr_amount').val() ? $('#e_pr_amount').val() : 0) : 0;
            var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
            var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;
            var e_discount_type = $('#e_discount_type').val();
            var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
            var e_showing_discount_amount = $('#e_showing_discount_amount').val() ? $('#e_showing_discount_amount').val() : 0;
            var e_tax_ac_id = $('#e_tax_ac_id').val();
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
            var e_showing_tax_amount = $('#e_showing_tax_amount').val() ? $('#e_showing_tax_amount').val() : 0;
            var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
            var e_tax_type = $('#e_tax_type').val();
            var e_showing_price_inc_tax = $('#e_showing_price_inc_tax').val() ? $('#e_showing_price_inc_tax').val() : 0;
            var e_price_inc_tax = $('#e_price_inc_tax').val() ? $('#e_price_inc_tax').val() : 0;
            var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
            var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val() ? $('#e_showing_unit_cost_inc_tax').val() : 0;
            var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
            var showPrAmount = all_price_type == 'PR' ? '(' + parseFloat(e_pr_amount).toFixed(2) + ')' : '';

            if (e_quantity == '') {

                toastr.error('Quantity field must not be empty.');
                return;
            }

            if (e_product_id == '') {

                toastr.error('Please select a product.');
                return;
            }

            var uniqueId = e_product_id + e_variant_id;

            var uniqueIdValue = $('#' + e_product_id + e_variant_id).val();

            if (uniqueIdValue == undefined) {

                var tr = '';
                tr += '<tr id="select_item">';
                tr += '<td class="text-start">';
                tr += '<span class="product_name" tabindex="-1">' + e_item_name + '</span>';
                tr += '<input type="hidden" id="item_name" value="' + e_item_name + '">';
                tr += '<input type="hidden" name="is_show_emi_on_pos[]" id="is_show_emi_on_pos" value="' + e_is_show_emi_on_pos + '">';
                tr += '<input type="hidden" name="descriptions[]" id="descriptions" value="">';
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
                tr += '<input type="hidden" id="' + e_product_id + e_variant_id + '" value="' + e_product_id + e_variant_id + '">';
                tr += '<input type="hidden" name="order_product_ids[]">';
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
                tr +=
                    '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                tr += '</td>';
                tr += '</tr>';

                $('#order_item_list').append(tr);
                clearEditItemFileds();
                calculateTotalAmount();
            } else {

                var tr = $('#' + uniqueId).closest('tr');

                tr.find('#item_name').val(e_item_name);
                tr.find('#product_id').val(e_product_id);
                tr.find('#variant_id').val(e_variant_id);
                tr.find('#tax_ac_id').val(e_tax_ac_id);
                tr.find('#tax_type').val(e_tax_type);
                tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
                tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
                tr.find('#unit_showing_tax_amount').val(parseFloat(e_showing_tax_amount).toFixed(2));
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
                tr.find('#unit_id').val(e_unit_id);
                tr.find('#span_unit').html(e_unit_name);
                tr.find('#base_unit_name').val(e_base_unit_name);
                tr.find('#unit_price_exc_tax').val(parseFloat(e_price_exc_tax).toFixed(2));
                tr.find('#showing_unit_price_exc_tax').val(parseFloat(e_showing_price_exc_tax).toFixed(2));
                tr.find('#price_type').val(all_price_type);
                tr.find('#pr_amount').val(parseFloat(e_pr_amount).toFixed(2));
                tr.find('#span_price_type').html(all_price_type + showPrAmount);
                tr.find('#unit_price_inc_tax').val(parseFloat(e_price_inc_tax).toFixed(2));
                tr.find('#showing_unit_price_inc_tax').val(parseFloat(e_showing_price_inc_tax).toFixed(2));
                tr.find('#span_showing_unit_price_inc_tax').html(parseFloat(e_showing_price_inc_tax).toFixed(2));
                tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                tr.find('#is_show_emi_on_pos').val(e_is_show_emi_on_pos);
                clearEditItemFileds();
                calculateTotalAmount();
            }

            $('#add_item').html('Add');
        });

        $(document).on('click', '#select_item', function(e) {

            var tr = $(this);
            var item_name = tr.find('#item_name').val();
            var product_id = tr.find('#product_id').val();
            var variant_id = tr.find('#variant_id').val();
            var tax_ac_id = tr.find('#tax_ac_id').val();
            var tax_type = tr.find('#tax_type').val();
            var unit_tax_percent = tr.find('#unit_tax_percent').val();
            var unit_tax_amount = tr.find('#unit_tax_amount').val();
            var showing_unit_tax_amount = tr.find('#showing_unit_tax_amount').val();
            var unit_discount_type = tr.find('#unit_discount_type').val();
            var unit_discount = tr.find('#unit_discount').val();
            var showing_unit_discount = tr.find('#showing_unit_discount').val();
            var unit_discount_amount = tr.find('#unit_discount_amount').val();
            var showing_unit_discount_amount = tr.find('#showing_unit_discount_amount').val();
            var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
            var showing_unit_cost_inc_tax = tr.find('#showing_unit_cost_inc_tax').val();
            var quantity = tr.find('#quantity').val();
            var showing_quantity = tr.find('#showing_quantity').val();
            var base_unit_name = tr.find('#base_unit_name').val();
            var unit_id = tr.find('#unit_id').val();
            var unit_price_exc_tax = tr.find('#unit_price_exc_tax').val();
            var showing_unit_price_exc_tax = tr.find('#showing_unit_price_exc_tax').val();
            var pr_amount = tr.find('#pr_amount').val() ? tr.find('#pr_amount').val() : 0;
            var unit_price_inc_tax = tr.find('#unit_price_inc_tax').val();
            var showing_unit_price_inc_tax = tr.find('#showing_unit_price_inc_tax').val();
            var subtotal = tr.find('#subtotal').val();
            var is_show_emi_on_pos = tr.find('#is_show_emi_on_pos').val();

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

            $('#search_product').val(item_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_base_unit_name').val(base_unit_name);
            $('#e_quantity').val(quantity).focus().select();
            $('#e_showing_quantity').val(showing_quantity).focus().select();
            $('#e_pr_amount').val(pr_amount);
            $('#e_price_exc_tax').val(unit_price_exc_tax);
            $('#e_showing_price_exc_tax').val(showing_unit_price_exc_tax);
            $('#e_base_unit_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
            $('#e_discount_type').val(unit_discount_type);
            $('#e_showing_discount').val(showing_unit_discount);
            $('#e_discount_amount').val(unit_discount_amount);
            $('#e_showing_discount_amount').val(showing_unit_discount_amount);
            $('#e_tax_ac_id').val(tax_ac_id);
            $('#e_tax_amount').val(unit_tax_amount);
            $('#e_showing_tax_amount').val(showing_unit_tax_amount);
            $('#e_tax_type').val(tax_type);
            $('#e_price_inc_tax').val(unit_price_inc_tax);
            $('#e_showing_price_inc_tax').val(showing_unit_price_inc_tax);
            $('#e_subtotal').val(subtotal);
            $('#e_unit_cost_inc_tax').val(unit_cost_inc_tax);
            $('#e_showing_unit_cost_inc_tax').val(showing_unit_cost_inc_tax);
            $('#display_unit_cost').html(showing_unit_cost_inc_tax);
            $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);
            $('#add_item').html('Edit');
        });

        function calculateEditOrAddAmount() {

            var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
            var is_base_unit = $('#e_unit_id').find('option:selected').data('is_base_unit');
            var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
            var e_base_unit_price_exc_tax = $('#e_base_unit_price_exc_tax').val() ? $('#e_base_unit_price_exc_tax').val() : 0;
            var e_showing_price_exc_tax = $('#e_showing_price_exc_tax').val() ? $('#e_showing_price_exc_tax').val() : 0;
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id')
                .find('option:selected').data('product_tax_percent') : 0;
            var e_tax_type = $('#e_tax_type').val();
            var e_pr_amount = $('#e_pr_amount').val() ? $('#e_pr_amount').val() : 0;
            var __pr_amount = $('#all_price_type').val() == 'PR' ? parseFloat(e_pr_amount) : 0;
            var e_discount_type = $('#e_discount_type').val();
            var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;

            var quantity = roundOfValue(e_showing_quantity) * roundOfValue(base_unit_multiplier);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2));

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
            $('#e_showing_tax_amount').val(parseFloat(showing_taxAmount).toFixed(2));
            $('#e_tax_amount').val(parseFloat(taxAmount).toFixed(2));
            $('#e_showing_price_inc_tax').val(parseFloat(showing_unitPriceIncTax).toFixed(2));
            $('#e_price_inc_tax').val(parseFloat(unitPriceIncTax).toFixed(2));

            var subtotal = roundOfValue(unitPriceIncTax) * roundOfValue(quantity);
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
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

            if (e.which == 0) {

                if ($(this).val() != '') {

                    $('#e_tax_type').focus();
                } else {

                    $('#add_item').focus();
                }
            }
        });

        $('#e_tax_type').on('change keypress click', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

                $('#add_item').focus();
            }
        });

        // Calculate total amount functionalitie
        function calculateTotalAmount() {

            var quantities = document.querySelectorAll('#quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            // Update Total Item

            var total_item = 0;
            var total_qty = 0;
            quantities.forEach(function(qty) {

                total_qty += parseFloat(qty.value);
                total_item += 1;
            });

            $('#total_qty').val(parseFloat(total_qty).toFixed(2));
            $('#total_item').val(parseFloat(total_item).toFixed(2));

            // Update Net total Amount
            var netTotalAmount = 0;
            subtotals.forEach(function(subtotal) {

                netTotalAmount += parseFloat(subtotal.value);
            });

            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

            if ($('#order_discount_type').val() == 2) {

                var orderDisAmount = parseFloat(netTotalAmount) / 100 * parseFloat($('#order_discount').val() ? $(
                    '#order_discount').val() : 0);
                $('#order_discount_amount').val(parseFloat(orderDisAmount).toFixed(2));
            } else {

                var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0;
                $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
            }

            var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
            // Calc order tax amount
            var orderTaxPercent = $('#order_tax_ac_id').find('option:selected').data('order_tax_percent') ? $(
                '#order_tax_ac_id').find('option:selected').data('order_tax_percent') : 0;
            var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(
                orderTaxPercent);
            $('#order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));

            // Update Total payable Amount
            var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;

            var calcTotalInvoiceAmount = parseFloat(netTotalAmount) -
                parseFloat(orderDiscountAmount) +
                parseFloat(calcOrderTaxAmount) +
                parseFloat(shipmentCharge);

            $('#total_invoice_amount').val(parseFloat(calcTotalInvoiceAmount).toFixed(2));
        }
        calculateTotalAmount();

        function clearEditItemFileds() {

            $('#search_product').val('').focus();
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
            $('#display_unit_cost').val(0.00);
            $('#e_is_show_discription').val('');
        }

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
            var orderTaxPercent = $(this).find('option:selected').data('order_tax_percent') ? $(this).find(
                'option:selected').data('order_tax_percent') : 0;
            $('#order_tax_percent').val(parseFloat(orderTaxPercent).toFixed(2));
        });

        $(document).on('change', '#all_price_type', function() {

            $(this).val() == 'PR' ? $('#e_pr_amount').prop('readonly', false).attr("tabindex", "") : $('#e_pr_amount').prop('readonly', true).attr("tabindex", "-1").val(0);

            var allPriceType = $(this).val();

            var table = $('.sale-product-table');

            table.find('tbody').find('tr').each(function() {

                $(this).find('#span_price_type').html(allPriceType);
                $(this).find('#price_type').val(allPriceType);
            });
        });
    </script>

    <script>
        $('#customer_account_id').on('change', function() {

            $('#current_balance').val(parseFloat(0).toFixed(2));
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

            $('#current_balance').val(parseFloat(0).toFixed(2));
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

                    $('#current_balance').val(data['closing_balance_string']);
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
                        $('.quick_product_loading_button').hide();
                        $('#addQuickProductModal').modal('hide');
                        toastr.success('Successfully product is added.');

                        var product = data.item;

                        var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' : product.name;

                        $('#search_product').val(name);
                        $('#e_item_name').val(name);
                        $('#e_product_id').val(product.id);
                        $('#e_variant_id').val('noid');
                        $('#e_base_unit_name').val(product.unit.name);
                        $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_showing_price_exc_tax').val(product.product_price);
                        $('#e_discount_type').val(1);
                        $('#e_showing_discount').val(parseFloat(0).toFixed(2));
                        $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
                        $('#e_tax_ac_id').val(product.tax_ac_id);
                        $('#e_tax_type').val(product.tax_type);
                        $('#e_unit_cost_inc_tax').val(product.product_cost_with_tax);
                        $('#e_showing_unit_cost_inc_tax').val(product.product_cost_with_tax);
                        $('#display_unit_cost').html(product.product_cost_with_tax);
                        $('#e_is_show_emi_on_pos').val(product.is_show_emi_on_pos);

                        $('#e_unit_id').empty();
                        $('#e_unit_id').append(
                            '<option value="' + product.unit.id +
                            '" data-is_base_unit="1" data-unit_name="' + product.unit.name +
                            '" data-base_unit_multiplier="1">' + product.unit.name + '</option>'
                        );

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

                                $('#e_unit_id').append(
                                    '<option value="' + unit.id +
                                    '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                    '" data-base_unit_multiplier="' + unit
                                    .base_unit_multiplier + '">' + unit.name +
                                    multiplierDetails + '</option>'
                                );
                            });
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
    </script>

    <script>
        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        $(document).on('click', '.submit_button', function() {

            $(this).prop('type', 'submit');
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object
            if (e.ctrlKey && e.which == 13) {

                $('#save').click();
                return false;
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('#list').empty();
                return false;

                $('.modal').modal('hide');
            } else if (e.altKey && e.which == 67) {

                $('#addCustomer').click();
                return false;
            } else if (e.altKey && e.which == 73) {

                $('#add_product').click();
                return false;
            }
        }

        // Edit Sales Order request by ajax
        $('#edit_sales_order_form').on('submit', function(e) {
            e.preventDefault();

            var totalItem = $('#total_item').val();

            if (parseFloat(totalItem) == 0) {

                toastr.error('Product table is empty.', 'Some thing went wrong.');
                return;
            }

            $('.loading_button').show();
            var url = $(this).attr('action');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('.loading_button').hide();
                    } else {

                        $('.loading_button').hide();
                        toastr.success(data.successMsg);
                        window.location = "{{ url()->previous() }}";
                    }
                },
                error: function(err) {

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
                }
            });
        });

        $('#payment_method_id').on('change', function() {

            var account_id = $(this).find('option:selected').data('account_id');
            setMethodAccount(account_id);
        });

        function setMethodAccount(account_id) {

            if (account_id) {

                $('#sale_receipt_account_id').val(account_id);
            }
        }

        setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));

        $(document).on('click', function(e) {

            if ($(e.target).closest(".select_area").length === 0) {

                $('.select_area').hide();
                $('#list').empty();
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

                if ($(this).attr('id') == 'order_discount' && ($('#order_discount').val() == '' || $(
                        '#order_discount').val() == 0)) {

                    $('#order_tax_ac_id').focus();
                    return;
                }

                if ($(this).attr('id') == 'receive_amount' && ($('#receive_amount').val() == '' || $(
                        '#receive_amount').val() == 0)) {

                    $('#save').focus();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        $("#addCustomerOpeingBalanceModal").on("hidden.bs.modal", function() {

            setTimeout(function() {

                $('#price_group_id').focus();
            }, 100);
        });
    </script>

    <script>
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
            element: document.getElementById('expire_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true,
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

        function roundOfValue(val) {

            return ((parseFloat(val) * 1000) / 1000);
        }
    </script>
@endpush
