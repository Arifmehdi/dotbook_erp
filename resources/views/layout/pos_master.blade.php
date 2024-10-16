<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>Genuine POS</title>
    <!-- Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('/css/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link href="{{ asset('/css/typography.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/body.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/reset.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/gradient.css') }}" rel="stylesheet" type="text/css">

    <!-- Calculator -->
    <link rel="stylesheet" href="{{ asset('css/calculator.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/comon.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/pos.css') }}">
    <link href="{{ asset('/plugins/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pos-theme.css') }}">
    @stack('css')
    <script src="{{ asset('plugins/jquery-3.6.0.js') }}"></script>
    <!--Toaster.js js link-->
    <script src="{{ asset('plugins/toastrjs/toastr.min.js') }}"></script>
    <!--Toaster.js js link end-->

    <script src="{{ asset('plugins/bootstrap.bundle.min.js ') }}"></script>
    <script src="{{ asset('plugins/print_this/printThis.min.js') }}"></script>
    <script src="{{ asset('plugins/Shortcuts-master/shortcuts.js') }}"></script>
    <!--alert js link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('plugins/digital_clock/digital_clock.js') }}"></script>
    <script src="{{ asset('js/number-bdt-formater.js') }}"></script>
</head>

<body
    class="{{ isset(json_decode($generalSettings->system, true)['theme_color']) ? json_decode($generalSettings->system, true)['theme_color'] : 'red-theme' }}">
    <form id="pos_submit_form" action="{{ route('sales.pos.store') }}" method="POST">
        @csrf
        <div class="pos-body">
            <div class="main-wraper">
                @yield('pos_content')
            </div>
        </div>

        <!--Add Payment modal-->
        <div class="modal fade in" id="otherPaymentMethod" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog col-50-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="payment_heading">@lang('menu.choose_payment_method')</h6>
                        <a href="#" class="close-btn" id="cancel_pay_mathod" tabindex="-1"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <!--begin::Form-->
                        <div class="form-group row single_payment">
                            <div class="col-md-4">
                                <label><strong>@lang('menu.payment_method') </strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fas fa-money-check text-dark"></i></span>
                                    </div>
                                    <select name="payment_method_id" class="form-control form-select"
                                        id="payment_method_id">
                                        @foreach ($methods as $method)
                                            <option
                                                data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                                value="{{ $method->id }}">
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label><strong>@lang('menu.debit_account') </strong> </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fas fa-money-check text-dark"></i></span>
                                    </div>
                                    <select name="account_id" class="form-control form-select" id="account_id">
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">
                                                @php
                                                    $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/c)';
                                                @endphp
                                                {{ $account->name . $accountType }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><strong> @lang('menu.payment_note') </strong></label>
                            <textarea name="payment_note" class="form-control form-control-sm" id="note" cols="30" rows="3"
                                placeholder="@lang('menu.note')"></textarea>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i
                                            class="fas fa-spinner"></i></button>
                                    <a href="#" class="btn btn-sm btn-success float-end" id="submit_btn"
                                        data-button_type="1" data-action_id="1" tabindex="-1">@lang('menu.confirm')
                                        (F10)</a>
                                    <button type="button" class="btn btn-sm btn-danger float-end me-2"
                                        id="cancel_pay_mathod">@lang('menu.close')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Add Payment modal End-->

        @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == '1')
            <!--Redeem Point modal-->
            <div class="modal fade" id="pointReedemModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
                data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog col-40-modal" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.reedem_oint')</h6>
                            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                                tabindex="-1"><span class="fas fa-times"></span></a>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label><b>@lang('menu.available_point') </b> </label>
                                <input type="number" step="any" name="available_point" id="available_point"
                                    class="form-control" value="0" readonly>
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><b>@lang('menu.redeemed') </b> </label>
                                    <input type="number" step="any" name="total_redeem_point"
                                        id="total_redeem_point" class="form-control">
                                    <input type="number" step="any" name="pre_redeemed" id="pre_redeemed"
                                        class="d-none" value="0">
                                    <input type="number" step="any" name="pre_redeemed_amount"
                                        id="pre_redeemed_amount" class="d-none" value="0">
                                </div>

                                <div class="col-md-6">
                                    <label><b>@lang('menu.redeem_amount') </b> </label>
                                    <input type="number" step="any" name="redeem_amount" id="redeem_amount"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                                class="fas fa-spinner"></i></button>
                                        <a href="#" class="btn btn-sm btn-success float-end" id="redeem_btn"
                                            tabindex="-1">@lang('menu.redeemed')</a>
                                        <button type="reset" data-bs-dismiss="modal"
                                            class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!--Redeem Point modal-->
    </form>

    <!-- Recent transection list modal-->
    <div class="modal fade" id="recentTransModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.recent_transaction')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="tab_list_area">
                        <ul class="list-unstyled">
                            <li>
                                <a id="tab_btn" class="tab_btn tab_active text-white"
                                    href="{{ url('common/ajax/call/recent/sales/2') }}" tabindex="-1"><i
                                        class="fas fa-info-circle"></i> @lang('menu.final')</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white"
                                    href="{{ url('common/ajax/call/recent/quotations/2') }}" tabindex="-1"><i
                                        class="fas fa-scroll"></i>@lang('menu.quotation')</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white"
                                    href="{{ url('common/ajax/call/recent/drafts/2') }}" tabindex="-1"><i
                                        class="fas fa-shopping-bag"></i> @lang('menu.draft')</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab_contant">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="data_preloader" id="recent_trans_preloader">
                                        <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table modal-table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-startx">@lang('menu.sl')</th>
                                                    <th class="text-startx">@lang('menu.reference')/ @lang('menu.invoice_id')</th>
                                                    <th class="text-startx">@lang('menu.customer')</th>
                                                    <th class="text-startx">@lang('menu.total')</th>
                                                    <th class="text-startx">@lang('menu.actions')</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data-list" id="transection_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="reset" data-bs-dismiss="modal"
                                class="btn btn-sm btn-danger float-end me-0">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Recent transection list modal end-->

    <!-- Hold invoice list modal -->
    <div class="modal fade" id="holdInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.hold_invoices')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                        tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table_area">
                                <div class="data_preloader" id="hold_invoice_preloader">
                                    <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive" id="hold_invoices"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="reset" data-bs-dismiss="modal"
                                class="btn btn-sm btn-danger float-end me-0">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hold invoice list modal End-->

    @if (auth()->user()->can('product_add'))
        <!--Add Product Modal-->
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_product')</h6>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                            tabindex="-1"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="add_product_body"></div>
                </div>
            </div>
        </div>
        <!--Add Product Modal End-->
    @endif

    <!--Add Customer Modal-->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_customer')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                        tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_customer_modal_body"></div>
            </div>
        </div>
    </div>
    <!--Add Customer Modal-->

    <!-- Edit selling product modal-->
    <div class="modal fade" id="suspendedSalesModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="data_preloader" id="suspend_preloader">
                    <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                </div>
                <div class="modal-header">
                    <h6 class="modal-title">@lang('menu.suspended_sales')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                        tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="suspended_sale_list"></div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal end-->
    <!-- Edit selling product modal-->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="product_info">@lang('menu.samsung_a')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                        tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="update_selling_product">
                        @if (auth()->user()->can('view_product_cost_is_sale_screed'))
                            <p>
                                <span class="btn btn-sm btn-primary d-none" id="show_cost_section">
                                    <span>{{ json_decode($generalSettings->business, true)['currency'] }}</span>
                                    <span id="unit_cost">1,200.00</span>
                                </span>

                                <span class="btn btn-sm btn-info text-white"
                                    id="show_cost_button">@lang('menu.cost')</span>
                            </p>
                        @endif

                        <div class="form-group mt-1">
                            <label> <strong>@lang('menu.quantity')</strong> <span class="text-danger">*</span></label>
                            <input type="number" readonly class="form-control edit_input" data-name="Quantity"
                                id="e_quantity" placeholder="@lang('menu.quantity')" value="" />
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>@lang('menu.unit_price_exc_tax')</strong> <span class="text-danger">*</span></label>
                            <input type="number" {{ auth()->user()->can('edit_price_pos_screen')? '': 'readonly' }}
                                step="any" class="form-control form-control-sm edit_input" data-name="Unit price"
                                id="e_unit_price" placeholder="@lang('menu.unit')" value="" />
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if (auth()->user()->can('edit_discount_pos_screen'))
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><strong>@lang('menu.discount_type')</strong> </label>
                                    <select class="form-control form-select" id="e_unit_discount_type">
                                        <option value="2">@lang('menu.percentage')</option>
                                        <option value="1">@lang('menu.fixed')</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>@lang('menu.discount')</strong> </label>
                                    <input type="number" class="form-control" id="e_unit_discount"
                                        value="0.00" />
                                    <input type="hidden" id="e_discount_amount" />
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.tax')</strong> </label>
                                <select class="form-control form-select" id="e_unit_tax"></select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.tax_type')</strong> </label>
                                <select class="form-control form-select" id="e_tax_type">
                                    <option value="1">@lang('menu.exclusive')</option>
                                    <option value="2">@lang('menu.inclusive')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>@lang('menu.sale_unit')</strong> </label>
                            <select class="form-control form-select" id="e_unit"></select>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="submit"
                                    class="btn btn-sm btn-success me-0 float-end">@lang('menu.update')</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="btn btn-sm btn-danger float-end">@lang('menu.close')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal end-->

    <!-- Show stock modal-->
    <div class="modal fade" id="showStockModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content">
                <div class="data_preloader mt-5" id="stock_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                </div>
                <div class="modal-header">
                    <h6 class="modal-title">@lang('menu.item_stocks')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                        tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="stock_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Show stock modal end-->

    <!-- Close Register modal -->
    <div class="modal fade" id="closeRegisterModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" id="close_register_content"></div>
        </div>
    </div>
    <!-- Close Register modal End-->

    <!-- Cash Register Details modal -->
    <div class="modal fade" id="cashRegisterDetailsModal" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" id="cash_register_details_content"></div>
        </div>
    </div>
    <!-- Cash Register Details modal End-->

    <!--Quick Cash receive modal-->
    <div class="modal fade in" id="cashReceiveMethod" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog col-45-modal" role="document">
            <div class="modal-content modal-middle">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">@lang('menu.quick_cash_receive')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                        tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="form-group row ">
                        <div class="col-md-6">
                            <div class="input-box-4 bg-dark">
                                <label class="text-white big_label"><strong>@lang('menu.total_payable') </strong> </label>
                                <input readonly type="text" class="form-control big_field"
                                    id="modal_total_payable" value="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-box-2 bg-info">
                                <label class="text-white big_label"><strong>@lang('menu.change') </strong></label>
                                <input type="text" class="form-control big_field text-info"
                                    id="modal_change_amount" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-6">
                            <div class="input-box bg-success">
                                <label class="text-white big_label"><strong>@lang('menu.cash_receive') </strong> <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="modal_paying_amount"
                                    class="form-control text-success big_field m-paying" id="modal_paying_amount"
                                    value="0" autofocus>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-box-3 bg-danger">
                                <label class="text-white big_label"><strong>@lang('menu.due') </strong> </label>
                                <input type="text" class="form-control text-danger big_field" id="modal_total_due"
                                    value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button display-none"><i
                                        class="fas fa-spinner"></i></button>
                                <a href="#" class="btn btn-sm btn-success float-end" id="submit_btn"
                                    data-button_type="1" data-action_id="1" tabindex="-1">@lang('menu.cash')
                                    (F10)</a>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Quick Cash receive modal End-->

    <!-- Exchange modal -->
    <div class="modal fade" id="exchangeModal"tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content" id="exchange_body">
                <div class="modal-header">
                    <h6 class="modal-title">@lang('menu.exchange')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                        tabindex="-1"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body">
                    <div class="form-area">
                        <form id="search_inv_form" action="{{ route('sales.pos.serc.ex.inv') }}" method="GET">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input required type="text" name="invoice_id" id="invoice_id"
                                        class="form-control" placeholder="Search invoice">
                                </div>

                                <div class="col-md-3">
                                    <input required type="text" name="customer_id" id="customer_id"
                                        class="form-control" placeholder="Search By customer">
                                </div>

                                <div class="col-md-3">
                                    <input required type="text" name="customer_phone" id="customer_phone"
                                        class="form-control" placeholder="Search By phone number">
                                </div>

                                <div class="col-md-2">
                                    <div class="btn_30_blue m-0">
                                        <a id="submit_form_btn" href="#" tabindex="-1"><i
                                                class="fas fa-plus-square"></i> @lang('menu.search')</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="preloader_area">
                            <div class="data_preloader" id="get_inv_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2" id="invoice_description"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Exchange modal End-->

    <!--Add shortcut menu modal-->
    <div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">@lang('menu.add_shortcut_menus')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"
                        tabindex="-1"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="modal-body_shortcuts"></div>
            </div>
        </div>
    </div>

    <!--Data delete form-->
    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
    <!--Data delete form end-->

    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script src="{{ asset('js/pos.js') }}"></script>
    <script src="{{ asset('js/pos-amount-calculation.js') }}"></script>
    <script src="{{ asset('js/sale.exchange.js') }}"></script>
    <script>
        // Get all pos shortcut menus by ajax
        function allPosShortcutMenus() {

            $.ajax({
                url: "{{ route('pos.short.menus.show') }}",
                type: 'get',
                success: function(data) {
                    $('#pos-shortcut-menus').html(data);
                }
            });
        }
        allPosShortcutMenus();

        $('#cash_register_details').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('sales.cash.register.details') }}",
                type: 'get',
                success: function(data) {

                    $('#cash_register_details_content').html(data);
                    $('#cashRegisterDetailsModal').modal('show');
                }
            });
        });

        $('#close_register').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('sales.cash.register.close.modal.view') }}",
                type: 'get',
                success: function(data) {

                    $('#close_register_content').html(data);
                    $('#closeRegisterModal').modal('show');
                }
            });
        });

        $(document).on('click', '#pos_exit_button', function(e) {
                    e.preventDefault();

                    var url = $(this).attr('href');
                    $('#payment_deleted_form').attr('action', url);

                    $.confirm({
                        'title': 'Delete Confirmation',
                        'content': 'Are you sure, you want to exit?',
                        'buttons': {
                            'Yes': {
                                'class': 'yes btn-primary',
                                'action': function() {
                                    window.location = "{{ route('dashboard.dashboard') }}";
                                }
                            },
                            'No': {
                                'class': 'no btn-danger',
                                'action': function() {}
                            });
                    });

                    //Key shortcut for to the settings
                    shortcuts.add('ctrl+q', function() {

                        window.location = "{{ route('settings.general.index') }}";
                    });

                    var scrollContainer = document.querySelector("#pos-shortcut-menus");
                    scrollContainer.addEventListener("wheel", (evt) => {

                        evt.preventDefault();
                        scrollContainer.scrollLeft += evt.deltaY;
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

                    setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));
    </script>
    @stack('js')
</body>

</html>
