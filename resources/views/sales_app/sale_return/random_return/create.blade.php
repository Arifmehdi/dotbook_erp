@extends('layout.master')
@push('css')
    <style>
        .data_preloader {
            top: 2.3%
        }

        .selected_invoice {
            background-color: #645f61;
            color: #fff !important;
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

        .selectProduct {
            background-color: #645f61;
            color: #fff !important;
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

        .element-body {
            overflow: initial !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Add Sales Return - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.add_sales_return')</h6>
                <x-all-buttons />
            </div>
            <form id="add_sale_return_form" action="{{ route('sale.return.random.store') }}" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <section class="p-15">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element m-0 rounded">
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
                                                <label class="col-4"><b>@lang('menu.customer') </b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select required name="customer_account_id" class="form-control select2 form-select" id="customer_account_id" data-next="sale_invoice_id">
                                                        <option value="">@lang('menu.select_customer')</option>
                                                        @foreach ($customerAccounts as $customerAccont)
                                                            <option data-customer_name="{{ $customerAccont->name }}" data-customer_phone="{{ $customerAccont->phone }}" value="{{ $customerAccont->id }}">
                                                                {{ $customerAccont->name . '/' . $customerAccont->phone }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_customer_account_id"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4 text-danger"><b>@lang('menu.curr_bal') </b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control fw-bold" id="current_balance" value="0.00" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('menu.sale_invoice_id')</b> </label>
                                                <div class="col-8">
                                                    <div style="position: relative;">
                                                        <input type="text" name="sale_invoice_id" id="sale_invoice_id" class="form-control fw-bold" data-next="warehouse_id" placeholder="Search Sale Invoice ID" autocomplete="off">
                                                        <input type="hidden" name="sale_id" id="sale_id" class="resetable" value="">

                                                        <div class="invoice_search_result display-none">
                                                            <ul id="invoice_list" class="list-unstyled"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if (count($warehouses) > 0)

                                                <input type="hidden" name="warehouse_count" value="YES" />
                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.warehouse') </b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select required class="form-control form-select" name="warehouse_id" id="warehouse_id" data-next="all_price_type">
                                                            <option value="">@lang('menu.select_warehouse')</option>
                                                            @foreach ($warehouses as $w)
                                                                <option value="{{ $w->id }}">
                                                                    {{ $w->warehouse_name . '/' . $w->warehouse_code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.store_location') </b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{ json_decode($generalSettings->business, true)['shop_name'] }}" data-next="all_price_type" />
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.rate_type') </b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select required name="all_price_type" class="form-control form-select" id="all_price_type" data-next="date">
                                                        <option value="">@lang('menu.select_rate_type')</option>
                                                        <option value="PR">PR</option>
                                                        <option value="MR">MR</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.return_date') <span class="text-danger">*</span></b></label>

                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="date" data-next="price_group_id" autocomplete="off">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b> @lang('menu.price_group') </b></label>
                                                <div class="col-8">
                                                    <select name="price_group_id" class="form-control form-select" id="price_group_id" data-next="sale_account_id">
                                                        <option value="">@lang('menu.default_selling_price')</option>
                                                        @foreach ($price_groups as $pg)
                                                            <option {{ json_decode($generalSettings->sale, true)['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">{{ $pg->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('menu.voucher_no')</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" data-next="sale_account_id" placeholder="@lang('menu.voucher_no')" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.sales_ledger') <span class="text-danger">*</span></b></label>

                                                <div class="col-8">
                                                    <select name="sale_account_id" class="form-control form-select" id="sale_account_id" data-next="search_product">
                                                        @foreach ($saleAccounts as $saleAccount)
                                                            <option value="{{ $saleAccount->id }}">
                                                                {{ $saleAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_sale_return_account_id"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sale-content py-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row g-xxl-4 g-3">
                                            <div class="col-xl-6">
                                                <div class="searching_area" style="position: relative;">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-barcode text-dark input_f"></i>
                                                            </span>
                                                        </div>

                                                        <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="Search Product by product code(SKU) / Scan bar code" autocomplete="off">
                                                    </div>

                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-xxl-4 g-3 align-items-end">
                                            <div class="hidden_fields">
                                                <input type="hidden" id="e_unique_id">
                                                <input type="hidden" id="e_unit_cost_inc_tax">
                                                <input type="hidden" id="e_item_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">
                                                <input type="hidden" id="e_tax_amount">
                                                <input type="hidden" id="e_showing_tax_amount">
                                                <input type="hidden" id="e_price_inc_tax">
                                                <input type="hidden" id="e_showing_price_inc_tax">
                                                <input type="hidden" id="e_base_unit_price_exc_tax">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">@lang('menu.return_quantity')</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control fw-bold w-60" id="e_showing_return_quantity" placeholder="@lang('menu.return_quantity')" value="0.00">
                                                    <input type="hidden" id="e_return_quantity" value="0.00">
                                                    <select id="e_unit_id" class="form-control w-40 form-select">
                                                        <option value="">@lang('menu.select_unit')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">@lang('menu.price_exc_tax')</label>
                                                <input {{ auth()->user()->can('edit_price_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_showing_price_exc_tax" placeholder="@lang('menu.price_exclude_tax')" value="0.00">
                                                <input type="hidden" id="e_price_exc_tax" value="0.00">
                                            </div>

                                            <div class="col-xl-1 col-md-6">
                                                <label class="fw-bold">@lang('menu.pr_Amount')</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control fw-bold" id="e_pr_amount" value="0.00" tabindex="-1" />
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">@lang('menu.discount')</label>
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

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">@lang('menu.tax')</label>
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
                                                        <option value="1">@lang('menu.exclusive')</option>
                                                        <option value="2">@lang('menu.inclusive')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">@lang('menu.sub_total')</label>
                                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                            </div>

                                            <div class="col-xl-1 col-md-6">
                                                <div class="btn-box-2">
                                                    <a href="#" class="btn btn-sm btn-success" id="add_item">@lang('menu.add')</a>
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
                                                                    <th>@lang('menu.product')</th>
                                                                    <th>@lang('menu.unit_price_inc_tax')</th>
                                                                    <th>@lang('menu.sold_quantity')</th>
                                                                    <th>@lang('menu.return_quantity')</th>
                                                                    <th>@lang('menu.unit')</th>
                                                                    <th>@lang('menu.sub_total')</th>
                                                                    <th><i class="fas fa-minus text-white"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="return_item_list"></tbody>
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
                    <div class="row g-1">
                        <div class="col-md-6">
                            <div class="form_element m-0 rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.total_item') </b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.total_return_qty') </b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.net_total_amount') </b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="net_total_amount" id="net_total_amount" class="form-control fw-bold" value="0" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element m-0 rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.return_discount') </b></label>
                                                <div class="col-8">
                                                    <div class="input-group">
                                                        <input name="return_discount" type="number" class="form-control fw-bold" id="return_discount" value="0.00" data-next="return_discount_type">

                                                        <select name="return_discount_type" class="form-control form-select" id="return_discount_type" data-next="return_tax_ac_id">
                                                            <option value="1">@lang('menu.fixed')(0.00)</option>
                                                            <option value="2">@lang('menu.percentage')(%)</option>
                                                        </select>

                                                        <input name="return_discount_amount" type="number" step="any" class="d-none" id="return_discount_amount" value="0.00">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.return_tax') </b></label>

                                                <div class="col-8">
                                                    <select name="return_tax_ac_id" class="form-control form-select" id="return_tax_ac_id" data-next="save_and_print">
                                                        <option data-return_tax_percent="0.00" value="">
                                                            @lang('menu.no_tax')</option>
                                                        @foreach ($taxAccounts as $taxAccount)
                                                            <option data-return_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <input name="return_tax_percent" type="number" step="any" class="d-none" id="return_tax_percent" value="0.00">
                                                    <input name="return_tax_amount" type="number" step="any" class="d-none" id="return_tax_amount" value="0.00">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.total_return_amount') </b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_return_amount" id="total_return_amount" class="form-control fw-bold" value="0.00" placeholder="@lang('menu.total_return_amount')" tabindex="-1">
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
                                <button type="button" id="save_and_print" value="1" class="btn btn-success submit_button">@lang('menu.save_and_print')</button>
                                <button type="button" id="save" value="2" class="btn btn-success submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();
        var itemUnitsArray = [];
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

        var ul = document.getElementById('list');
        var selectObjClassName = 'selectProduct';
        $('#sale_invoice_id').mousedown(function(e) {

            afterClickOrFocusSaleInvoiceId();
        }).focus(function(e) {

            ul = document.getElementById('invoice_list')
            selectObjClassName = 'selected_invoice';
        });

        function afterClickOrFocusSaleInvoiceId() {

            $('#sale_invoice_id').val('');
            $('#customer_account_id').val('').trigger('change');
            $('#user_id').val('').trigger('change');
            $('#current_balance').val(0.00);
            $('#sale_id').val('');
            $('#search_product').prop('disabled', false);
            $('#return_item_list').empty();
            $('.invoice_search_result').hide();
            $('#invoice_list').empty();
            calculateTotalAmount();
        }

        function afterFocusSearchItemField() {

            ul = document.getElementById('list');
            selectObjClassName = 'selectProduct';
            $('#sale_id').val('');
        }

        $('#search_product').focus(function(e) {

            afterFocusSearchItemField();
        });

        $('#sale_invoice_id').on('input', function() {

            $('.invoice_search_result').hide();

            var invoice_id = $(this).val();

            if (invoice_id === '') {

                $('.invoice_search_result').hide();
                $('#sale_id').val('');
                $('#sale_products').prop('disabled', true);
                $('#search_product').prop('disabled', false);
                return;
            }

            var url = "{{ route('common.ajax.call.invoice.search.list', [':invoice_id']) }}";
            var route = url.replace(':invoice_id', invoice_id);

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

        $(document).on('click', '#selected_invoice', function(e) {
            e.preventDefault();

            var sale_invoice_id = $(this).html();

            var sale_id = $(this).data('sale_id');
            var all_price_type = $(this).data('all_price_type') ? $(this).data('all_price_type') : 'MR';
            var customer_account_id = $(this).data('customer_account_id');
            var customer_curr_balance = $(this).data('current_balance');
            var user_id = $(this).data('user_id');

            var url = "{{ route('common.ajax.call.get.sale.products', [':sale_id']) }}";
            var route = url.replace(':sale_id', sale_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(saleProducts) {

                    if (!$.isEmptyObject(saleProducts.errorMsg)) {

                        toastr.error(saleProducts.errorMsg);
                        $('#sale_invoice_id').val().focus().select();
                        return;
                    }

                    $('#sale_invoice_id').val(sale_invoice_id.trim());
                    $('#sale_id').val(sale_id);
                    $('#all_price_type').val(all_price_type);
                    $('#user_id').val(user_id).trigger('change');
                    $('#customer_account_id').val(customer_account_id).trigger('change');
                    $('#current_balance').val(customer_curr_balance);
                    $('.invoice_search_result').hide();
                    $('#return_item_list').empty();

                    all_price_type == 'PR' ? $('#e_pr_amount').prop('readonly', false).attr("tabindex", "") : $('#e_pr_amount').prop('readonly', true).attr("tabindex", "-1").val(0);

                    $('#search_product').prop('disabled', true);
                    var tr = '';
                    $.each(saleProducts, function(key, saleProduct) {

                        itemUnitsArray[saleProduct.product_id] = [{
                            'unit_id': saleProduct.product.unit.id,
                            'unit_name': saleProduct.product.unit.name,
                            'unit_code_name': saleProduct.product.unit.code_name,
                            'base_unit_multiplier': 1,
                            'multiplier_details': '',
                            'is_base_unit': 1,
                        }];

                        if (saleProduct.product.unit.child_units.length > 0) {

                            saleProduct.product.unit.child_units.forEach(function(unit) {

                                var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + saleProduct.product.unit.name + ')';

                                itemUnitsArray[saleProduct.product_id].push({
                                    'unit_id': unit.id,
                                    'unit_name': unit.name,
                                    'unit_code_name': unit.code_name,
                                    'base_unit_multiplier': unit.base_unit_multiplier,
                                    'multiplier_details': multiplierDetails,
                                    'is_base_unit': 1,
                                });

                                $('#e_unit_id').append('<option value="' + unit.id +
                                    '" data-is_base_unit="0" data-unit_name="' +
                                    unit.name + '" data-base_unit_multiplier="' +
                                    unit.base_unit_multiplier + '">' + unit.name +
                                    multiplierDetails + '</option>');
                            });
                        }

                        var variantName = saleProduct.variant != null ? ' - ' + saleProduct.variant.variant_name : '';
                        var variantId = saleProduct.variant != null ? ' - ' + saleProduct.variant.id : 'noid';

                        var baseUnitMultiplier = 1;
                        if (saleProduct.sale_unit != null) {

                            baseUnitMultiplier = saleProduct.sale_unit.base_unit_multiplier != null ? saleProduct.sale_unit.base_unit_multiplier : 1;
                        }

                        tr += '<tr id="select_item">';
                        tr += '<td class="text-start">';
                        tr += '<span class="product_name">' + saleProduct.product.name + variantName + '</span>';
                        tr += '<input type="hidden" id="item_name" value="' + saleProduct.product.name + variantName + '">';
                        tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + saleProduct.product_id + '">';
                        tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' +
                            variantId + '">';
                        tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + (saleProduct.tax_ac_id != null ? saleProduct.tax_ac_id : '') + '">';
                        tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + saleProduct.tax_type + '">';
                        tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + saleProduct.unit_tax_percent + '">';
                        tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + saleProduct.unit_tax_amount + '">';
                        var showingTaxAmount = saleProduct.unit_tax_amount * baseUnitMultiplier;
                        tr += '<input type="hidden" id="showing_unit_tax_amount" value="' + parseFloat(showingTaxAmount).toFixed(2) + '">';
                        tr += '<input type="hidden" name="price_types[]" id="price_type" value="' + all_price_type + '">';
                        tr += '<input type="hidden" name="pr_amounts[]" id="pr_amount" value="' + parseFloat(saleProduct.pr_amount).toFixed(2) + '">';
                        tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + saleProduct.unit_discount_type + '">';
                        tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + saleProduct.unit_discount + '">';
                        tr += '<input type="hidden" id="showing_unit_discount" value="' + saleProduct.unit_discount + '">';
                        tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + saleProduct.unit_discount_amount + '">';
                        tr += '<input type="hidden" id="showing_unit_discount_amount" value="' + saleProduct.unit_discount_amount + '">';
                        tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + saleProduct.unit_cost_inc_tax + '">';
                        tr += '<input type="hidden" name="sale_product_ids[]" id="sale_product_id" value="' + saleProduct.id + '">';
                        tr += '<input type="hidden" id="' + saleProduct.product_id + variantId + '" value="' + saleProduct.product_id + variantId + '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(saleProduct.unit_price_exc_tax).toFixed(2) + '">';
                        var showingPriceExcTax = saleProduct.unit_price_exc_tax * baseUnitMultiplier;
                        tr += '<input type="hidden" id="showing_unit_price_exc_tax" value="' + parseFloat(showingPriceExcTax).toFixed(2) + '">';
                        tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(saleProduct.unit_price_inc_tax).toFixed(2) + '">';
                        var showingPriceIncTax = saleProduct.unit_price_inc_tax * baseUnitMultiplier;
                        tr += '<input type="hidden" id="showing_unit_price_inc_tax" value="' + parseFloat(showingPriceIncTax).toFixed(2) + '">';
                        tr += '<span id="showing_span_unit_price_inc_tax" class="fw-bold">' + parseFloat(showingPriceIncTax).toFixed(2) + '</span>';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<span id="span_sold_qty" class="fw-bold">' + (saleProduct.quantity / baseUnitMultiplier) + '/' + saleProduct.sale_unit.name + '</span>';
                        tr += '<input type="hidden" name="sold_quantities[]" value="' + saleProduct.quantity + '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input type="hidden" name="return_quantities[]" id="return_quantity" value="0.00">';
                        tr += '<input type="hidden" id="showing_return_quantity" value="0.00">';
                        tr += '<span id="showing_span_return_quantity" class="fw-bold">0.00</span>';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<span id="showing_span_unit" class="fw-bold">' + saleProduct.sale_unit.name + '</span>';
                        tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + saleProduct.sale_unit.id + '">';
                        tr += '</td>';

                        tr += '<td class="text text-center">';
                        tr += '<span id="span_subtotal" class="fw-bold">0.00</span>';
                        tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="0.00" tabindex="-1">';
                        tr += '</td>';

                        tr += '<td class="text-center">';
                        tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                        tr += '</td>';
                        tr += '</tr>';
                    });

                    $('#return_item_list').html(tr);
                }
            });
        });

        $(document).on('keyup', 'body', function(e) {

            if (e.keyCode == 13) {

                $('.' + selectObjClassName).click();
                $('.invoice_search_result').hide();
                $('.select_area').hide();
                $('#list').empty();
                $('#invoice_list').empty();
            }
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

            if ($('#customer_account_id').val() == '') {

                toastr.error('Please select a listed customer first.');
                $(this).val('');
                return;
            }

            var keyWord = $(this).val();
            var __keyWord = keyWord.replaceAll('/', '~');

            delay(function() {
                searchProduct(__keyWord);
            }, 200);
        });

        function searchProduct(keyWord) {

            var price_group_id = $('#price_group_id').val();

            var isShowNotForSaleItem = 1;
            var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
            var route = url.replace(':keyWord', keyWord);
            route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

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

                                    return value.price_group_id == price_group_id && value.product_id ==
                                        product.id;
                                });

                                if (__price.length != 0) {

                                    price = __price[0].price ? __price[0].price : product.product_price;
                                } else {

                                    price = product.product_price;
                                }

                                var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' :
                                    product.name;

                                $('#search_product').val(name);
                                $('#e_item_name').val(name);
                                $('#e_product_id').val(product.id);
                                $('#e_variant_id').val('noid');
                                $('#e_showing_return_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                                $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                                $('#e_tax_ac_id').val(product.tax_ac_id);
                                $('#e_tax_type').val(product.tax_type);
                                $('#e_unit_cost_inc_tax').val(product.product_cost_with_tax);
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

                                        $('#e_unit_id').append('<option value="' + unit.id +
                                            '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                            '" data-base_unit_multiplier="' + unit
                                            .base_unit_multiplier + '">' + unit.name +
                                            multiplierDetails + '</option>');
                                    });
                                }

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

                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_code="' + variant.variant_code + '" data-p_price_exc_tax="' + price + '" data-p_cost_inc_tax="' + (variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax) + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if (!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();
                            $('#search_product').val('');

                            if (product.is_manage_stock == 1) {

                                $('#stock_quantity').val(parseFloat(qty_limit).toFixed(2));
                            }

                            var variant_product = product.variant_product;

                            var price = 0;
                            var __price = price_groups.filter(function(value) {

                                return value.price_group_id == price_group_id && value.product_id == variant_product.product.id && value.variant_id == variant_product.id;
                            });

                            if (__price.length != 0) {

                                price = __price[0].price ? __price[0].price : variant_product.variant_price;
                            } else {

                                price = variant_product.variant_price;
                            }

                            var name = variant_product.product.name.length > 35 ? variant_product.product.name.substring(0, 35) + '...' : variant_product.product.name;

                            $('#search_product').val(name + ' - ' + variant_product.variant_name);
                            $('#e_item_name').val(name + ' - ' + variant_product.variant_name);
                            $('#e_product_id').val(variant_product.product.id);
                            $('#e_variant_id').val(variant_product.id);
                            $('#e_showing_return_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                            $('#e_tax_ac_id').val(variant_product.product.tax_id);
                            $('#e_tax_type').val(variant_product.product.tax_type);
                            $('#e_unit_cost_inc_tax').val(variant_product.variant_cost_with_tax);
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

                                    $('#e_unit_id').append('<option value="' + unit.id +
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
                                        li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-v_name="' + product.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_code="' + product.variant_code + '" data-p_price_exc_tax="' + price + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
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
                                        li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-v_name="" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="' + product.name + '" data-p_code="' + product.product_code + '" data-p_price_exc_tax="' + price + '" data-tax_type="' + product.tax_type + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-description="' + product.is_show_emi_on_pos + '" data-p_cost_inc_tax="' + product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                        li += '</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        }
                    } else {

                        $('#search_product').addClass('is-invalid');
                        toastr.error('Product not found.', 'Failed');
                        $('#search_product').select();
                    }
                }
            });
        }

        function selectProduct(e) {

            var price_group_id = $('#price_group_id').val() ? $('#price_group_id').val() : 'no_id';

            $('.select_area').hide();
            $('#search_product').val('');

            var product_id = e.getAttribute('data-p_id');
            var variant_id = e.getAttribute('data-v_id');
            var is_manage_stock = e.getAttribute('data-is_manage_stock');
            var product_name = e.getAttribute('data-p_name');
            var variant_name = e.getAttribute('data-v_name');
            var product_code = e.getAttribute('data-p_code');
            var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
            var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
            var p_tax_ac_id = e.getAttribute('data-p_tax_ac_id') != null ? e.getAttribute('data-p_tax_ac_id') : '';
            var p_tax_id = e.getAttribute('data-tax_id');
            var p_tax_type = e.getAttribute('data-tax_type');
            $('#search_product').val('');

            var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
            var route = url.replace(':product_id', product_id);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(baseUnit) {

                    var price = 0;
                    var __price = price_groups.filter(function(value) {

                        return value.price_group_id == price_group_id && value.product_id == product_id;
                    });

                    if (__price.length != 0) {

                        price = __price[0].price ? __price[0].price : product_price_exc_tax;
                    } else {

                        price = product_price_exc_tax;
                    }

                    var name = product_name.length > 35 ? product_name.substring(0, 35) + '...' : product_name;

                    $('#search_product').val(name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_item_name').val(name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_product_id').val(product_id);
                    $('#e_variant_id').val(variant_id ? variant_id : 'noid');
                    $('#e_showing_return_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                    $('#e_tax_ac_id').val(p_tax_ac_id);
                    $('#e_tax_type').val(p_tax_type);
                    $('#e_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));
                    $('#e_base_unit_price_exc_tax').val(price);

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append(
                        '<option value="' + baseUnit.id +
                        '" data-is_base_unit="1" data-unit_name="' + baseUnit.name +
                        '" data-base_unit_multiplier="1">' + baseUnit.name + '</option>'
                    );

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

                            var multiplierDetails = '(1 ' + unit.name + ' = ' + unit
                                .base_unit_multiplier + '/' + baseUnit.name + ')';

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
            var e_return_quantity = $('#e_return_quantity').val() ? $('#e_return_quantity').val() : 0;
            var e_showing_return_quantity = $('#e_showing_return_quantity').val() ? $('#e_showing_return_quantity').val() : 0;
            var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
            var e_showing_price_exc_tax = $('#e_showing_price_exc_tax').val() ? $('#e_showing_price_exc_tax').val() : 0;
            var all_price_type = $('#all_price_type').val() ? $('#all_price_type').val() : 'N/A';
            var e_pr_amount = $('#all_price_type').val() == 'PR' ? ($('#e_pr_amount').val() ? $('#e_pr_amount').val() : 0) : 0.00;
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

            if (e_return_quantity == '') {

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
                tr += '<span class="product_name">' + e_item_name + '</span>';
                tr += '<input type="hidden" id="item_name" value="' + e_item_name + '">';
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
                tr += '<input type="hidden" name="price_types[]" id="price_type" value="' + all_price_type + '">';
                tr += '<input type="hidden" name="pr_amounts[]" id="pr_amount" value="' + parseFloat(e_pr_amount).toFixed(2) + '">';
                tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + e_unit_cost_inc_tax + '">';
                tr += '<input type="hidden" name="sale_product_ids[]" id="sale_product_id" value="">';
                tr += '<input type="hidden" id="' + e_product_id + e_variant_id + '" value="' + e_product_id + e_variant_id + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="showing_span_unit_price_inc_tax" class="fw-bold">' + parseFloat(e_showing_price_exc_tax).toFixed(2) + '</span>';
                tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(e_price_exc_tax).toFixed(2) + '">';
                tr += '<input type="hidden" id="showing_unit_price_exc_tax" value="' + parseFloat(e_showing_price_exc_tax).toFixed(2) + '">';
                tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(e_price_inc_tax).toFixed(2) + '">';
                tr += '<input type="hidden" id="showing_unit_price_inc_tax" value="' + parseFloat(e_showing_price_inc_tax).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="span_sold_qty" class="fw-bold">0.00</span>';
                tr += '<input type="hidden" name="sold_quantities[]" value="0.00">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span id="showing_span_return_quantity" class="fw-bold">' + parseFloat(e_showing_return_quantity).toFixed(2) + '</span>';
                tr += '<input type="hidden" id="showing_return_quantity" value="' + parseFloat(e_showing_return_quantity).toFixed(2) + '">';
                tr += '<input type="hidden" name="return_quantities[]" id="return_quantity" value="' + parseFloat(e_return_quantity).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td class="text">';
                tr += '<span id="showing_span_unit" class="fw-bold">' + e_unit_name + '</span>';
                tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                tr += '</td>';

                tr += '<td class="text text-center">';
                tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
                tr += '</td>';

                tr += '<td class="text-center">';
                tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                tr += '</td>';
                tr += '</tr>';

                $('#return_item_list').append(tr);
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
                tr.find('#showing_unit_tax_amount').val(parseFloat(e_showing_tax_amount).toFixed(2));
                tr.find('#unit_discount_type').val(e_discount_type);
                tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
                tr.find('#showing_unit_discount').val(parseFloat(e_showing_discount).toFixed(2));
                tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
                tr.find('#showing_unit_discount_amount').val(parseFloat(e_showing_discount_amount).toFixed(2));
                tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                tr.find('#showing_span_return_quantity').html(parseFloat(e_showing_return_quantity).toFixed(2));
                tr.find('#showing_return_quantity').val(parseFloat(e_showing_return_quantity).toFixed(2));
                tr.find('#return_quantity').val(parseFloat(e_return_quantity).toFixed(2));
                tr.find('#showing_span_unit').html(e_unit_name);
                tr.find('#unit_id').val(e_unit_id);
                tr.find('#unit_price_exc_tax').val(parseFloat(e_price_exc_tax).toFixed(2));
                tr.find('#showing_unit_price_exc_tax').val(parseFloat(e_showing_price_exc_tax).toFixed(2));
                tr.find('#price_type').val(all_price_type);
                tr.find('#pr_amount').val(parseFloat(e_pr_amount).toFixed(2));
                tr.find('#unit_price_inc_tax').val(parseFloat(e_price_inc_tax).toFixed(2));
                tr.find('#showing_unit_price_inc_tax').val(parseFloat(e_showing_price_inc_tax).toFixed(2));
                tr.find('#showing_span_unit_price_inc_tax').html(parseFloat(e_showing_price_inc_tax).toFixed(2));
                tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
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
            var return_quantity = tr.find('#return_quantity').val();
            var showing_return_quantity = tr.find('#showing_return_quantity').val();
            var unit_id = tr.find('#unit_id').val();
            var unit_price_exc_tax = tr.find('#unit_price_exc_tax').val();
            var showing_unit_price_exc_tax = tr.find('#showing_unit_price_exc_tax').val();
            var pr_amount = tr.find('#pr_amount').val() ? tr.find('#pr_amount').val() : 0;
            var unit_price_inc_tax = tr.find('#unit_price_inc_tax').val();
            var showing_unit_price_inc_tax = tr.find('#showing_unit_price_inc_tax').val();
            var subtotal = tr.find('#subtotal').val();

            $('#search_product').val(item_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_return_quantity').val(parseFloat(return_quantity).toFixed(2));
            $('#e_showing_return_quantity').val(parseFloat(showing_return_quantity).toFixed(2)).focus().select();
            $('#e_pr_amount').val(pr_amount);
            $('#e_price_exc_tax').val(unit_price_exc_tax);
            $('#e_showing_price_exc_tax').val(showing_unit_price_exc_tax);
            $('#e_discount_type').val(unit_discount_type);
            $('#e_discount').val(unit_discount);
            $('#e_showing_discount').val(showing_unit_discount);
            $('#e_discount_amount').val(unit_discount_amount);
            $('#e_showing_discount_amount').val(showing_unit_discount_amount);
            $('#e_tax_ac_id').val(tax_ac_id);
            $('#e_tax_amount').val(unit_tax_amount);
            $('#e_showing_tax_amount').val(showing_unit_tax_amount);
            $('#e_tax_type').val(tax_type);
            $('#e_price_inc_tax').val(unit_price_inc_tax);
            $('#e_showing_price_inc_tax').val(showing_unit_price_inc_tax);
            $('#e_base_unit_price_exc_tax').val(unit_price_exc_tax);
            $('#e_subtotal').val(subtotal);
            $('#e_unit_cost_inc_tax').val(unit_cost_inc_tax);

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

            $('#add_item').html('Edit');
        });

        function calculateEditOrAddAmount() {

            var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
            var is_base_unit = $('#e_unit_id').find('option:selected').data('is_base_unit');
            var e_showing_return_quantity = $('#e_showing_return_quantity').val() ? $('#e_showing_return_quantity').val() : 0;
            var e_base_unit_price_exc_tax = $('#e_base_unit_price_exc_tax').val() ? $('#e_base_unit_price_exc_tax').val() : 0;
            var e_showing_price_exc_tax = $('#e_showing_price_exc_tax').val() ? $('#e_showing_price_exc_tax').val() : 0.00;
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
            var e_tax_type = $('#e_tax_type').val();
            var e_pr_amount = $('#e_pr_amount').val() ? $('#e_pr_amount').val() : 0;
            var __pr_amount = $('#all_price_type').val() == 'PR' ? parseFloat(e_pr_amount) : 0;
            var e_discount_type = $('#e_discount_type').val();
            var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;

            var quantity = roundOfValue(e_showing_return_quantity) * roundOfValue(base_unit_multiplier);
            $('#e_return_quantity').val(parseFloat(quantity).toFixed(2));

            var unitPriceExcTax = 0;
            unitPriceExcTax = roundOfValue(e_showing_price_exc_tax) / roundOfValue(base_unit_multiplier);
            $('#e_price_exc_tax').val(roundOfValue(unitPriceExcTax));
            $('#e_base_unit_price_exc_tax').val(roundOfValue(unitPriceExcTax));

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

        $('#e_showing_return_quantity').on('input keypress', function(e) {

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

                if ($(this).val() && $(this).val() > 0) {

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

                if ($(this).val() && $(this).val() > 0) {

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

            var quantities = document.querySelectorAll('#return_quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            // Update Total Item

            var total_item = 0;
            var total_qty = 0;
            quantities.forEach(function(qty) {

                total_item += 1;
                total_qty += parseFloat(qty.value);
            });

            $('#total_item').val(parseFloat(total_item));
            $('#total_qty').val(parseFloat(total_qty));

            // Update Net total Amount
            var netTotalAmount = 0;
            subtotals.forEach(function(subtotal) {

                netTotalAmount += parseFloat(subtotal.value);
            });

            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

            if ($('#return_discount_type').val() == 2) {

                var returnDisAmount = parseFloat(netTotalAmount) / 100 * parseFloat($('#return_discount').val() ? $('#return_discount').val() : 0);
                $('#return_discount_amount').val(parseFloat(returnDisAmount).toFixed(2));
            } else {

                var returnDiscount = $('#return_discount').val() ? $('#return_discount').val() : 0;
                $('#return_discount_amount').val(parseFloat(returnDiscount).toFixed(2));
            }

            var returnDiscountAmount = $('#return_discount_amount').val() ? $('#return_discount_amount').val() : 0;

            // Calc order tax amount
            var returnTaxPercent = $('#return_tax_ac_id').find('option:selected').data('return_tax_percent') ? $('#return_tax_ac_id').find('option:selected').data('return_tax_percent') : 0;
            var calReturnTaxAmount = (parseFloat(netTotalAmount) - parseFloat(returnDiscountAmount)) / 100 * parseFloat(returnTaxPercent);

            $('#return_tax_amount').val(parseFloat(calReturnTaxAmount).toFixed(2));

            var calcTotalAmount = parseFloat(netTotalAmount) - parseFloat(returnDiscountAmount) + parseFloat(
                calReturnTaxAmount);

            $('#total_return_amount').val(parseFloat(calcTotalAmount).toFixed(2));
        }

        $(document).on('input', '#return_discount', function() {

            calculateTotalAmount();
        });

        $(document).on('change', '#return_tax_ac_id', function() {

            calculateTotalAmount();
            var returnTaxPercent = $(this).find('option:selected').data('return_tax_percent') ? $(this).find(
                'option:selected').data('return_tax_percent') : 0;
            $('#return_tax_percent').val(parseFloat(returnTaxPercent).toFixed(2));
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

        // Remove product form purchase product list (Table)
        $(document).on('click', '#remove_product_btn', function(e) {
            e.preventDefault();

            $(this).closest('tr').remove();
            calculateTotalAmount();
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
                $('.invoice_search_result').hide();
                $('#list').empty();
                $('#invoice_list').empty();
                return false;
            }
        }

        //Add sales return request by ajax
        $('#add_sale_return_form').on('submit', function(e) {
            e.preventDefault();

            var totalQty = $('#total_qty').val();

            if (parseFloat(totalQty) == 0) {

                toastr.error('Return Quantity Must Not Be 0.00', 'Some thing went wrong.');
                return;
            }

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
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    if (!$.isEmptyObject(data.successMsg)) {

                        toastr.success(data.successMsg);
                        afterCreateSaleReturn();
                    } else {

                        toastr.success('Successfully sale return is created.');
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });
                        afterCreateSaleReturn();
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

        function clearEditItemFileds() {

            if ($('#search_product').is(':disabled') == true) {

                $('#e_showing_return_quantity').val(0.00).focus().select();
            } else {

                $('#search_product').val('').focus();
            }

            $('#search_product').val('');
            $('#e_item_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_return_quantity').val(0.00);
            $('#e_showing_return_quantity').val(0.00);
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
            $('#e_showing_ax_amount').val(parseFloat(0).toFixed(2));
            $('#e_tax_type').val(1);
            $('#e_price_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_showing_price_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_subtotal').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_inc_tax').val(0);
        }

        function afterCreateSaleReturn() {

            $('.loading_button').hide();
            $('#sale_id').val('');
            $('#add_sale_return_form')[0].reset();
            $('#return_item_list').empty();

            $('.current_balance').val(0);

            $('#search_product').prop('disabled', false);

            $("#customer_account_id").select2("destroy");
            $("#customer_account_id").select2();

            $("#user_id").select2("destroy");
            $("#user_id").select2();

            $("#user_id").focus();
        }

        // Automatic remove searching product is found signal
        setInterval(function() {

            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function() {

            $('#search_product').removeClass('is-valid');
        }, 1000);

        $('#customer_account_id').on('change', function() {

            $('#current_balance').val(parseFloat(0).toFixed(2));
            var customer_account_id = $(this).val();

            var user_id = $('#user_id').val();

            if (user_id == '' && customer_account_id) {

                $("#customer_account_id").val("");
                $("#customer_account_id").select2("destroy");
                $("#customer_account_id").select2();
                toastr.error('Please Select a sr first.');
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
                }
            });
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

                if ($(this).attr('id') == 'sale_account_id' && $('#search_product').is(':disabled') == true) {

                    $('#e_return_quantity').focus().select();
                    return;
                }

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

        function roundOfValue(val) {

            return ((parseFloat(val) * 1000) / 1000);
        }

        document.getElementById('user_id').focus();
    </script>
    <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script>
@endpush
