@extends('layout.master')
@section('content')
    @push('css')
        <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .contract_info_area ul li strong {
                color: #495677
            }

            .account_summary_area .heading h5 {
                background: var(--main-color);
                color: white
            }

            .contract_info_area ul li strong i {
                color: #495b77;
                font-size: 13px;
            }
        </style>
        
    @endpush
@section('title', $customer->name . ' - ')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>{{ $customer->name }}</h6>
            </div>
            <x-back-button />
        </div>
    </div>

    <div class="p-15">
        <div class="card">
            <div class="card-body">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                </div>

                <div class="tab_list_area">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <ul class="list-unstyled">

                                <li>
                                    <a id="tab_btn" data-show="ledger" class="tab_btn tab_active" href="#">
                                        <i class="fas fa-scroll"></i>@lang('menu.ledger')
                                    </a>
                                </li>

                                <li>
                                    <a id="tab_btn" data-show="contract_info_area" class="tab_btn" href="#"><i class="fas fa-info-circle">
                                        </i>@lang('menu.contact_info')
                                    </a>
                                </li>

                                <li>
                                    <a id="tab_btn" data-show="sales_orders" class="tab_btn" href="#">
                                        <i class="fas fa-shopping-bag"></i> Sales Orders
                                    </a>
                                </li>

                                <li>
                                    <a id="tab_btn" data-show="sale" class="tab_btn" href="#">
                                        <i class="fas fa-shopping-bag"></i> Sales
                                    </a>
                                </li>

                                <li>
                                    <a id="tab_btn" data-show="vouchers" class="tab_btn" href="#">
                                        <i class="far fa-money-bill-alt"></i> Vouchers
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {{-- <div class="col-lg-4">
                                <div class="row g-0 dot-shadow-wrap dot-shadow-wrap-sm">
                                    <div class="col-md-4 border">
                                        <div class="dot-shadow dot-shadow-sm">
                                            <p class="text-center" id="total_customer">15</p>
                                            <p class="text-center" style="font-size:12px">Total Customers</p>
                                        </div>
                                    </div>

                                    <div class="col-md-4 border">
                                        <div class="dot-shadow dot-shadow-sm">
                                            <p class="text-center" id="active_customer">15</p>
                                            <p class="text-center" style="font-size:12px">Active Customers</p>
                                        </div>
                                    </div>

                                    <div class="col-md-4 border">
                                        <div class="dot-shadow dot-shadow-sm">
                                            <p class="text-center" id="inactive_customer">0</p>
                                            <p class="text-center" style="font-size:12px">Inactive Customers</p>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                    </div>
                </div>

                <div class="tab_contant ledger">
                    <div class="row mb-2">
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            @include('contacts::customers.partials.account_summery_area_by_ledgers')
                        </div>

                        <div class="col-md-8 col-sm-12 col-lg-8">
                            <div class="account_summary_area">
                                <div class="heading py-1">
                                    <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                </div>

                                <div class="account_summary_table">
                                    <form id="filter_customer_ledgers" method="get" class="px-2 filter_form">
                                        <div class="form-group row align-items-end g-2">
                                            <div class="col-xl-3 col-md-3">
                                                <label><strong>{{ __('Sr.') }}</strong></label>
                                                <select name="user_id" class="form-control select2" id="ledger_user_id" autofocus>
                                                    <option data-user_name="" value="">
                                                        @lang('menu.all')</option>
                                                    @foreach ($users as $user)
                                                        <option data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}" value="{{ $user->id }}">
                                                            {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><strong>@lang('menu.from_date') :</strong></label>
                                                <div class="input-group">

                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                    </div>

                                                    <input type="text" name="from_date" id="ledger_from_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label><strong>@lang('menu.to_date') :</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                    </div>
                                                    <input type="text" name="to_date" id="ledger_to_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label><strong>@lang('menu.note/remarks') :</strong></label>
                                                <select name="note" class="form-control" id="ledger_note">
                                                    <option value="0">{{ __('No') }}</option>
                                                    <option selected value="1">{{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><strong>@lang('menu.voucher_details') :</strong></label>
                                                <select name="voucher_details" class="form-control" id="ledger_voucher_details">
                                                    <option value="0">{{ __('No') }}</option>
                                                    <option value="1">{{ __('Yes') }}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><strong>@lang('menu.transaction_details') :</strong></label>
                                                <select name="transaction_details" class="form-control" id="ledger_transaction_details">
                                                    <option value="0">{{ __('No') }}</option>
                                                    <option value="1">{{ __('Yes') }}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><strong>@lang('menu.inventory_list') :</strong></label>
                                                <select name="inventory_list" class="form-control" id="ledger_inventory_list">
                                                    <option value="0">{{ __('No') }}</option>
                                                    <option value="1">{{ __('Yes') }}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                                <button type="submit" id="print_ledger" class="btn btn-sm btn-info"><i class="fa-solid fa-print"></i> @lang('menu.print')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="ladger_table">
                                <div class="table-responsive" id="payment_list_table">
                                    <table class="display data_tbl data__table ledger_table">
                                        <thead>
                                            <tr>
                                                <th class="text-startx">@lang('menu.date')</th>
                                                <th class="text-startx">@lang('menu.particulars')</th>
                                                <th class="text-startx">@lang('menu.voucher_type')</th>
                                                <th class="text-startx">@lang('menu.voucher_no')</th>
                                                <th class="text-startx">@lang('menu.debit')</th>
                                                <th class="text-startx">@lang('menu.credit')</th>
                                                <th class="text-startx">@lang('menu.running_balance')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="4" class="text-white" style="text-align: right!important;">@lang('menu.current_total')
                                                    : </th>
                                                <th id="ledger_table_total_debit" class="text-white"></th>
                                                <th id="ledger_table_total_credit" class="text-white">
                                                </th>
                                                <th id="ledger_table_current_balance" class="text-white">
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant contract_info_area d-none pt-3">
                    <div class="row justify-content-center">
                        <div class="col-md-5">
                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong>@lang('menu.customer_name') :</strong></li>
                                <li><span class="name">{{ $customer->name }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fas fa-map-marker-alt"></i>
                                        {{ __('Address') }}:</strong></li>
                                <li><span class="address">{{ $customer->address }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fas fa-briefcase"></i> @lang('menu.business_name'):</strong>
                                </li>
                                <li><span class="business">{{ $customer->business_name }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fas fa-phone-square"></i>
                                        @lang('menu.phone'):</strong></li>
                                <li><span class="phone">{{ $customer->phone }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fa-solid fa-id-card-clip"></i>
                                        @lang('menu.nid_no'):</strong></li>
                                <li><span class="phone">{{ $customer->nid_no }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fa-regular fa-trademark"></i>@lang('menu.trade_license_no'):</strong>
                                </li>
                                <li><span class="phone">{{ $customer->trade_license_no }}</span></li>
                            </ul>
                        </div>

                        <div class="col-md-5">
                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fa-solid fa-envelope"></i>
                                        @lang('menu.email_address'):</strong></li>
                                <li><span class="tax_number">{{ $customer->email }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fa-light fa-phone-office"></i>
                                        {{ __('Land Line') }}</strong></li>
                                <li><span class="phone">{{ $customer->landline }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fa-solid fa-phone-rotary"></i>
                                        {{ __('Alternative Phone No.') }}:</strong></li>
                                <li><span class="phone">{{ $customer->alternative_phone }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fas fa-info"></i>@lang('menu.tax_number'):</strong></li>
                                <li><span class="tax_number">{{ $customer->tax_number }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fa-duotone fa-person"></i> {{ __('Known Person') }}
                                        : </strong></li>
                                <li><span class="phone">{{ $customer->known_person }}</span></li>
                            </ul>

                            <ul class="list-unstyled d-flex">
                                <br>
                                <li><strong><i class="fa-solid fa-phone-rotary"></i>@lang('menu.known_person_phone')
                                        :</strong></li>
                                <li><span class="phone">{{ $customer->known_person_phone }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="tab_contant sales_orders d-none">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="account_summary_area">
                                <div class="heading py-1">
                                    <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                </div>

                                <div class="account_summary_table">
                                    <form id="filter_customer_orders" method="get" class="px-2 filter_form">
                                        <div class="form-group row align-items-end g-2">
                                            @if (!auth()->user()->can('view_own_sale'))
                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Sr.') }}</strong></label>
                                                    <select name="user_id" class="form-control select2" id="sales_order_user_id" autofocus>
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}">
                                                                {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="col-md-2">
                                                <label><strong>@lang('menu.from_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                    </div>
                                                    <input type="text" name="from_date" id="sales_order_from_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label><strong>@lang('menu.to_date') </strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                    </div>

                                                    <input type="text" name="to_date" id="sales_order_to_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="table_area">
                                <div class="table-responsive">
                                    <table class="display data_tbl data__table orders_table w-100">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.actions')</th>
                                                <th>@lang('menu.date')</th>
                                                <th>@lang('menu.exp_date')</th>
                                                <th>@lang('menu.order_id')</th>
                                                <th>@lang('menu.customer')</th>
                                                <th>{{ __('Sr.') }}</th>
                                                <th>@lang('menu.curr_status')</th>
                                                <th>@lang('menu.delivery_status')</th>
                                                {{-- <th>@lang('menu.payment_status')</th> --}}
                                                <th>@lang('menu.do_approval')</th>
                                                <th>@lang('menu.ordered_qty')</th>
                                                <th>@lang('menu.delivered_qty')</th>
                                                <th>@lang('menu.left_qty')</th>
                                                <th>@lang('menu.total_amount')</th>
                                                {{-- <th>@lang('menu.total_paid')</th>
                                                    <th>@lang('menu.payment_due')</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="9" class="text-white text-end">
                                                    @lang('menu.total') :
                                                    ({{ json_decode($generalSettings->business, true)['currency'] }})
                                                </th>
                                                <th id="order_table_total_ordered_qty" class="text-white text-end"></th>
                                                <th id="order_table_total_delivered_qty" class="text-white text-end"></th>
                                                <th id="order_table_do_total_left_qty" class="text-white text-end"></th>
                                                <th id="order_table_total_payable_amount" class="text-white text-end"></th>
                                                {{-- <th id="paid" class="text-white text-end"></th>
                                                    <th id="due" class="text-white text-end"></th> --}}
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant sale d-none">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="account_summary_area">
                                <div class="heading py-1">
                                    <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                </div>

                                <div class="account_summary_table">
                                    <form id="filter_customer_sales" method="get" class="px-2 filter_form">
                                        <div class="form-group row align-items-end g-2">
                                            @if (!auth()->user()->can('view_own_sale'))
                                                <div class="col-xl-2 col-md-3">
                                                    <label><strong>{{ __('Sr.') }}</strong></label>
                                                    <select name="user_id" class="form-control select2" id="sale_user_id" autofocus>
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}">
                                                                {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="col-xl-2 col-md-3">
                                                <label><strong>@lang('menu.from_date')</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                    </div>
                                                    <input type="text" name="from_date" id="sale_from_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-3">
                                                <label><strong>@lang('menu.to_date')</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                    </div>

                                                    <input type="text" name="to_date" id="sale_to_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-3">
                                                <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                                <a href="#" class="btn btn-sm btn-info" id="print_sale_statement"><i class="fas fa-print"></i> @lang('menu.print')</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table_area">
                                <div class="table-responsive">
                                    <table class="display data_tbl data__table sales_table w-100">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.actions')</th>
                                                <th class="text-start">@lang('menu.date')</th>
                                                <th class="text-start">@lang('menu.invoice_id')</th>
                                                <th class="text-start">@lang('menu.do_id')</th>
                                                <th class="text-start">@lang('menu.hand_challan_no')</th>
                                                <th class="text-start">@lang('menu.customer')</th>
                                                <th class="text-start">{{ __('Sr.') }}</th>
                                                <th class="text-end">@lang('menu.total_qty')</th>
                                                <th class="text-end">@lang('menu.net_weight')</th>
                                                <th class="text-end">@lang('menu.total_amount')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="7" class="text-white text-end">
                                                    @lang('menu.total') :
                                                    ({{ json_decode($generalSettings->business, true)['currency'] }})
                                                </th>
                                                <th id="sale_table_total_sold_qty" class="text-white text-end"></th>
                                                <th id="sale_table_net_weight" class="text-white text-end"></th>
                                                <th id="sale_table_total_payable_amount" class="text-white text-end"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab_contant vouchers d-none">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="account_summary_area">
                                <div class="heading py-1">
                                    <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                </div>

                                <div class="account_summary_table">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="filter_customer_vouchers" method="get" class="px-2 filter_form">
                                                <div class="form-group row align-items-end g-2">
                                                    <div class="col-xl-2 col-md-2">
                                                        <label><strong>{{ __("Sr.") }}</strong></label>
                                                        <select name="user_id" class="form-control select2" id="vouchers_user_id" autofocus>
                                                            <option value="">@lang('menu.all')
                                                            </option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}">
                                                                    {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-xl-2 col-md-2">
                                                        <label><strong>@lang('menu.voucher_type') </strong></label>
                                                        <select name="voucher_type" class="form-control" id="vouchers_type" autofocus>
                                                            <option value="">@lang('menu.all')</option>
                                                            <option value="8">@lang('menu.receipts')</option>
                                                            <option value="9">@lang('menu.payments')</option>
                                                            <option value="13">@lang('menu.journals')</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><strong>@lang('menu.from_date')</strong></label>
                                                        <div class="input-group">

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                            </div>

                                                            <input type="text" name="from_date" id="vouchers_from_date" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><strong>@lang('menu.to_date')</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                            </div>
                                                            <input type="text" name="to_date" id="vouchers_to_date" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><strong>@lang('menu.note/remarks')</strong></label>
                                                        <select name="note" class="form-control" id="vouchers_note">
                                                            <option value="0">No</option>
                                                            <option selected value="1">Yes</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><strong>@lang('menu.voucher_details')</strong></label>
                                                        <select name="voucher_details" class="form-control" id="vouchers_voucher_details">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><strong>@lang('menu.transaction_details')</strong></label>
                                                        <select name="transaction_details" class="form-control" id="vouchers_transaction_details">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="display data_tbl data__table vouchers_table w-100">
                                    <thead>
                                        <tr class="text-start">
                                            <th class="text-startx">@lang('menu.date')</th>
                                            <th class="text-startx">@lang('menu.description')</th>
                                            <th class="text-startx">@lang('menu.voucher_type')</th>
                                            <th class="text-startx">@lang('menu.voucher_no')</th>
                                            <th class="text-startx">@lang('menu.debit')</th>
                                            <th class="text-startx">@lang('menu.credit')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th class="text-end text-white" colspan="4">
                                                @lang('menu.total') : </th>
                                            <th class="text-end text-white" id="voucher_table_total_debit"></th>
                                            <th class="text-end text-white" id="voucher_table_total_credit"></th>
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
</div>

<form id="deleted_form" action="" method="post">
    @method('DELETE')
    @csrf
</form>

<form id="payment_deleted_form" action="" method="post">
    @method('DELETE')
    @csrf
</form>

<!-- Details Modal -->
<div id="details"></div>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $('.select2').select2();
    // Get customer Ledgers by yajra data table
    @if (auth()->user()->can('accounts_ledger'))
        var ledger_table = $('.ledger_table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            dom: "lBfrtip",
            "pageLength": parseInt(
                "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],

            "ajax": {
                "url": "{{ route('accounting.accounts.ledger', [$customer->customer_account_id, 'accountId']) }}",
                "data": function(d) {
                    d.user_id = $('#ledger_user_id').val();
                    d.user_name = $('#ledger_user_id').find('option:selected').data('user_name');
                    d.from_date = $('#ledger_from_date').val();
                    d.to_date = $('#ledger_to_date').val();
                    d.note = $('#ledger_note').val();
                    d.transaction_details = $('#ledger_transaction_details').val();
                    d.voucher_details = $('#ledger_voucher_details').val();
                    d.inventory_list = $('#ledger_inventory_list').val();
                }
            },
            columns: [{
                data: 'date',
                name: 'account_ledgers.date'
            }, {
                data: 'particulars',
                name: 'particulars'
            }, {
                data: 'voucher_type',
                name: 'voucher_no'
            }, {
                data: 'voucher_no',
                name: 'voucher_no'
            }, {
                data: 'debit',
                name: 'account_ledgers.debit',
                className: 'text-end'
            }, {
                data: 'credit',
                name: 'account_ledgers.credit',
                className: 'text-end'
            }, {
                data: 'running_balance',
                name: 'account_ledgers.running_balance',
                className: 'text-end'
            }, ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_customer_ledgers', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            ledger_table.ajax.reload();

            filterObj = {
                user_id: $('#ledger_user_id').val(),
                from_date: $('.from_date').val(),
                to_date: $('.to_date').val(),
            };

            getAccountClosingBalance();
        });
    @endif

    //Get customer Sales by yajra data table
    var sales_table = $('.sales_table').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],

        "ajax": {
            "url": "{{ route('sales.index', $customer->customer_account_id) }}",
            "data": function(d) {
                d.user_id = $('#sale_user_id').val();
                d.from_date = $('#sale_from_date').val();
                d.to_date = $('#sale_to_date').val();
            }
        },

        columns: [{
            data: 'action'
        }, {
            data: 'date',
            name: 'date'
        }, {
            data: 'invoice_id',
            name: 'sales.invoice_id',
            className: 'fw-bold'
        }, {
            data: 'do_id',
            name: 'do.do_id',
            className: 'fw-bold'
        }, {
            data: 'do_to_inv_challan_no',
            name: 'sales.do_to_inv_challan_no'
        }, {
            data: 'customer',
            name: 'customers.name'
        }, {
            data: 'sr',
            name: 'sr.name'
        }, {
            data: 'total_sold_qty',
            name: 'sr.prefix',
            className: 'text-end'
        }, {
            data: 'net_weight',
            name: 'sr.last_name',
            className: 'text-end'
        }, {
            data: 'total_payable_amount',
            name: 'total_payable_amount',
            className: 'text-end'
        }, ],
        fnDrawCallback: function() {

            var total_sold_qty = sum_table_col($('.data_tbl'), 'total_sold_qty');
            $('#sale_table_total_sold_qty').text(bdFormat(total_sold_qty));

            var net_weight = sum_table_col($('.data_tbl'), 'net_weight');
            $('#sale_table_net_weight').text(bdFormat(net_weight));

            var total_payable_amount = sum_table_col($('.data_tbl'), 'total_payable_amount');
            $('#sale_table_total_payable_amount').text(bdFormat(total_payable_amount));
            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_customer_sales', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        sales_table.ajax.reload();
    });

    var orders_table = $('.orders_table').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [{
            extend: 'excel',
            text: '<i class="fas fa-file-excel"></i> Excel',
            className: 'btn btn-primary',
            exportOptions: {
                columns: 'th:not(:first-child)'
            }
        }, {
            extend: 'pdf',
            text: '<i class="fas fa-file-pdf"></i> Pdf',
            className: 'btn btn-primary',
            exportOptions: {
                columns: 'th:not(:first-child)'
            }
        }, {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Print',
            className: 'btn btn-primary',
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
            }
        }, ],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('sales.order.index', $customer->customer_account_id) }}",
            "data": function(d) {
                d.user_id = $('#sales_order_user_id').val();
                d.from_date = $('#sales_order_from_date').val();
                d.to_date = $('#sales_order_to_date').val();
            }
        },
        columns: [{
            data: 'action'
        }, {
            data: 'order_date',
            name: 'order_date'
        }, {
            data: 'expire_date',
            name: 'sales.expire_date'
        }, {
            data: 'order_id',
            name: 'sales.order_id',
            className: 'fw-bold'
        }, {
            data: 'customer',
            name: 'customers.name'
        }, {
            data: 'sr',
            name: 'sr.name'
        }, {
            data: 'current_status',
            name: 'sales.order_id'
        }, {
            data: 'delivery_status',
            name: 'sales.order_id'
        }, {
            data: 'do_approval',
            name: 'do_approval'
        }, {
            data: 'total_ordered_qty',
            name: 'total_ordered_qty'
        }, {
            data: 'total_delivered_qty',
            name: 'sr.prefix'
        }, {
            data: 'do_total_left_qty',
            name: 'sr.last_name'
        }, {
            data: 'total_payable_amount',
            name: 'sales.quotation_id'
        }, ],
        fnDrawCallback: function() {

            var total_ordered_qty = sum_table_col($('.data_tbl'), 'total_ordered_qty');
            $('#order_table_total_ordered_qty').text(bdFormat(total_ordered_qty));

            var total_delivered_qty = sum_table_col($('.data_tbl'), 'total_delivered_qty');
            $('#order_table_total_delivered_qty').text(bdFormat(total_delivered_qty));

            var do_total_left_qty = sum_table_col($('.data_tbl'), 'do_total_left_qty');
            $('#order_table_do_total_left_qty').text(bdFormat(do_total_left_qty));

            var total_payable_amount = sum_table_col($('.data_tbl'), 'total_payable_amount');
            $('#order_table_total_payable_amount').text(bdFormat(total_payable_amount));
            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_customer_orders', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        orders_table.ajax.reload();
    });

    var vouchers_table = $('.vouchers_table').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": true,
        dom: "lBfrtip",
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],

        "ajax": {
            "url": "{{ route('accounting.accounts.voucher.list', [$customer->customer_account_id, 'accountId']) }}",
            "data": function(d) {
                d.user_id = $('#vouchers_user_id').val();
                d.from_date = $('#vouchers_from_date').val();
                d.to_date = $('#vouchers_to_date').val();
                d.note = $('#vouchers_note').val();
                d.transaction_details = $('#vouchers_transaction_details').val();
                d.voucher_details = $('#vouchers_voucher_details').val();
                d.voucher_type = $('#vouchers_type').val();
            }
        },
        columns: [{
            data: 'date',
            name: 'account_ledgers.date'
        }, {
            data: 'descriptions',
            name: 'journals.journal_voucher'
        }, {
            data: 'voucher_type',
            name: 'voucher_no'
        }, {
            data: 'voucher_no',
            name: 'payments.payment_voucher'
        }, {
            data: 'debit',
            name: 'account_ledgers.debit',
            className: 'text-end'
        }, {
            data: 'credit',
            name: 'account_ledgers.credit',
            className: 'text-end'
        }, ],
        fnDrawCallback: function() {

            var debit = sum_table_col($('.data_tbl'), 'debit');
            $('#voucher_table_total_debit').text(bdFormat(debit));

            var credit = sum_table_col($('.data_tbl'), 'credit');
            $('#voucher_table_total_credit').text(bdFormat(credit));
            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_customer_vouchers', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        vouchers_table.ajax.reload();
    });

    function sum_table_col(table, class_name) {
        var sum = 0;
        table.find('tbody').find('tr').each(function() {

            if (parseFloat($(this).find('.' + class_name).data('value'))) {

                sum += parseFloat(
                    $(this).find('.' + class_name).data('value')
                );
            }
        });

        return sum;
    }

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();

        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').addClass('d-none');
        var show_content = $(this).data('show');
        $('.' + show_content).removeClass('d-none');
        $(this).addClass('tab_active');
    });

    // Show details modal with data
    $(document).on('click', '#details_btn', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        $('.data_preloader').show();

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
                $('.action_hideable').hide();
            }
        });
    });

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);

        $.confirm({
            'title': 'Delete Confirmation',
            'message': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-danger',
                    'action': function() {
                        $('#deleted_form').submit();
                    }
                },
                'No': {
                    'class': 'no btn-primary',
                    'action': function() {}
                }
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_form', function(e) {
        e.preventDefault();

        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.data_tbl').DataTable().ajax.reload();
                toastr.error(data);

                var filterObj = {
                    user_id: $('#p_user_id').val(),
                    from_date: $('#p_from_date').val(),
                    to_date: $('#p_to_date').val(),
                };
            }
        });
    });

    // Make print
    $(document).on('click', '#print_modal_details_btn', function(e) {
        e.preventDefault();

        var body = $('.print_details').html();
        var header = $('.heading_area').html();

        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{ asset('css/print/sale.print.css') }}",
            removeInline: false,
            printDelay: 500,
            header: null,
        });
    });


    @if (auth()->user()->can('accounts_ledger'))
        //Print Customer ledger
        $(document).on('click', '#print_ledger', function(e) {
            e.preventDefault();

            var url = "{{ route('accounting.accounts.ledger.print', [$customer->customer_account_id, 'accountId']) }}";


            var user_id = $('#ledger_user_id').val();
            var user_name = $('#ledger_user_id').find('option:selected').data('user_name');
            var customer_name = '';
            var from_date = $('#ledger_from_date').val();
            var to_date = $('#ledger_to_date').val();
            var note = $('#ledger_note').val();
            var transaction_details = $('#ledger_transaction_details').val();
            var voucher_details = $('#ledger_voucher_details').val();
            var inventory_list = $('#ledger_inventory_list').val();

            $.ajax({

                url: url,
                type: 'get',
                data: {
                    user_id,
                    user_name,
                    customer_name,
                    from_date,
                    to_date,
                    note,
                    transaction_details,
                    voucher_details,
                    inventory_list
                },
                success: function(data) {

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
    @endif

    //Print purchase Payment report
    $(document).on('click', '#print_sale_statement', function(e) {
        e.preventDefault();

        var url = "{{ route('reports.sales.report.print') }}";

        var customer_id = "{{ $customer->id }}";
        var from_date = $('#sale_from_date').val();
        var to_date = $('#sale_to_date').val();

        $.ajax({
            url: url,
            type: 'get',
            data: {
                customer_id,
                from_date,
                to_date
            },
            success: function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 500,
                    header: "",
                    pageTitle: "",
                });
            }
        });
    });

    @if (auth()->user()->can('accounts_ledger'))

        function getAccountClosingBalance() {

            var user_id = $('#ledger_user_id').val();
            var from_date = $('#ledger_from_date').val();
            var to_date = $('#ledger_to_date').val();

            var filterObj = {
                user_id: user_id ? user_id : null,
                from_date: from_date ? from_date : null,
                to_date: to_date ? to_date : null,
            };

            var url = "{{ route('vouchers.journals.account.closing.balance', $customer->customer_account_id) }}";

            $.ajax({

                url: url,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    $('#ledger_debit_opening_balance').html('');
                    $('#ledger_credit_opening_balance').html('');
                    $('#ledger_debit_closing_balance').html('');
                    $('#ledger_credit_closing_balance').html('');

                    $('#ledger_table_total_debit').html(data.all_total_debit > 0 ? bdFormat(data.all_total_debit) : '');
                    $('#ledger_table_total_credit').html(data.all_total_credit > 0 ? bdFormat(data.all_total_credit) : '');
                    $('#ledger_table_current_balance').html(data.closing_balance > 0 ? data.closing_balance_string : '');

                    if (data.opening_balance_side == 'dr') {

                        $('#ledger_debit_opening_balance').html(data.opening_balance > 0 ? bdFormat(data.opening_balance) : '');
                    } else {

                        $('#ledger_credit_opening_balance').html(data.opening_balance > 0 ? bdFormat(data.opening_balance) : '');
                    }

                    $('#ledger_total_debit').html(data.curr_total_debit > 0 ? bdFormat(data.curr_total_debit) : '');
                    $('#ledger_total_credit').html(data.curr_total_credit > 0 ? bdFormat(data.curr_total_credit) : '');

                    if (data.closing_balance_side == 'dr') {

                        $('#ledger_debit_closing_balance').html(data.closing_balance > 0 ? bdFormat(data.closing_balance) : '');
                    } else {

                        $('#ledger_credit_closing_balance').html(data.closing_balance > 0 ? bdFormat(data.closing_balance) : '');

                    }
                }
            });
        }

        getAccountClosingBalance();
    @endif
</script>

<script type="text/javascript">
    new Litepicker({
        singleMode: true,
        element: document.getElementById('ledger_from_date'),
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
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('ledger_to_date'),
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
        format: 'DD-MM-YYYY',
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('sale_from_date'),
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
        format: 'DD-MM-YYYY',
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('sale_to_date'),
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
        format: 'DD-MM-YYYY',
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('vouchers_from_date'),
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
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('vouchers_to_date'),
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
        format: 'DD-MM-YYYY',
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('sales_order_from_date'),
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
        format: 'DD-MM-YYYY',
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('sales_order_to_date'),
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
        format: 'DD-MM-YYYY',
    });
</script>
@endpush
