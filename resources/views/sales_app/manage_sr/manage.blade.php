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
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6 id="sr_name">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}</h6>
                </div>
                <div class="d-flex gap-2">
                    <x-table-stat :items="[['id' => 'total_customer', 'name' => __('Total Customer'), 'value' => 200], ['id' => 'inactive_customer', 'name' => __('Inactive Customer'), 'value' => 20]]" />
                    <x-back-button />
                </div>
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
                            <div class="col-12">
                                <ul class="list-unstyled">
                                    <li>
                                        <a id="tab_btn" data-show="ledger" class="tab_btn tab_active" href="#">
                                            <i class="fas fa-scroll"></i> @lang('menu.ledger')
                                        </a>
                                    </li>

                                    <li>
                                        <a id="tab_btn" data-show="contract_info_area" class="tab_btn" href="#"><i class="fas fa-info-circle"></i> @lang('menu.sr_info')
                                        </a>
                                    </li>

                                    <li>
                                        <a id="tab_btn" data-show="sales_orders" class="tab_btn" href="#">
                                            <i class="fas fa-shopping-bag"></i> @lang('menu.sales_orders')
                                        </a>
                                    </li>

                                    <li>
                                        <a id="tab_btn" data-show="sale" class="tab_btn" href="#">
                                            <i class="fas fa-shopping-bag"></i> @lang('menu.sales')
                                        </a>
                                    </li>

                                    <li>
                                        <a id="tab_btn" data-show="vouchers" class="tab_btn" href="#">
                                            <i class="far fa-money-bill-alt"></i> @lang('menu.vouchers')
                                        </a>
                                    </li>
                                </ul>
                            </div>
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
                                        <form id="filter_sr_ledger" method="get" class="px-2 filter_form">
                                            <div class="form-group row align-items-end g-2">
                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.customer') </strong></label>
                                                    <select name="customer_account_id" class="form-control select2 form-select" id="ledger_customer_account_id" autofocus>
                                                        <option data-customer_name="All" value="">@lang('menu.all')
                                                        </option>
                                                        @foreach ($customerAccounts as $customerAccount)
                                                            <option data-customer_name="{{ $customerAccount->name . '/' . $customerAccount->phone }}" value="{{ $customerAccount->id }}">
                                                                {{ $customerAccount->name . '/' . $customerAccount->phone }}
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
                                                    <select name="note" class="form-control form-select" id="ledger_note">
                                                        <option value="0">@lang('menu.no')</option>
                                                        <option selected value="1">@lang('menu.yes')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.voucher_details') :</strong></label>
                                                    <select name="voucher_details" class="form-control form-select" id="ledger_voucher_details">
                                                        <option value="0">@lang('menu.no')</option>
                                                        <option value="1">@lang('menu.yes')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.transaction_details') :</strong></label>
                                                    <select name="transaction_details" class="form-control form-select" id="ledger_transaction_details">
                                                        <option value="0">@lang('menu.no')</option>
                                                        <option value="1">@lang('menu.yes')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.inventory_list') :</strong></label>
                                                    <select name="inventory_list" class="form-control form-select" id="ledger_inventory_list">
                                                        <option value="0">@lang('menu.no')</option>
                                                        <option value="1">@lang('menu.yes')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i>
                                                        @lang('menu.filter')</button>
                                                    <a href="{{ route('accounting.accounts.ledger.print', [$user->id, 'userId']) }}" data-prefix="ledger" class="btn btn-sm btn-info" id="print_report"><i class="fas fa-print"></i>
                                                        @lang('menu.print')</a>
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
                                                    <th colspan="4" class="text-white" style="text-align: right!important;">@lang('menu.current_total') : </th>
                                                    <th id="ledger_table_total_debit" class="text-white"></th>
                                                    <th id="ledger_table_total_credit" class="text-white"></th>
                                                    <th id="ledger_table_current_balance" class="text-white"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant contract_info_area d-none pt-3">
                        <div class="row g-1">
                            <div class="col-md-6">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><strong>@lang('menu.basic_information')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><strong>@lang('menu.full_name') :</strong>
                                                    {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }} </p>
                                                <p><strong>@lang('menu.phone') :</strong> {{ $user->phone }} </p>
                                                <p><strong>@lang('menu.email') :</strong> {{ $user->email }} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><strong>@lang('menu.role_permission')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><strong>@lang('menu.user_name') :</strong> {!! $user->username ? $user->username : '<span class="badge bg-secondary">Not-Allowed-to-Login</span>' !!} </p>
                                                <p><strong>@lang('menu.role') :</strong>
                                                    @if ($user->role_type == 1)
                                                        @lang('menu.super_admin')
                                                    @elseif($user->role_type == 2)
                                                        @lang('menu.admin')
                                                    @elseif($user->role_type == 3)
                                                        {{ $user?->roles?->first()?->name }}
                                                    @else
                                                        <span class="badge bg-warning">No-Role</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-1">
                            <div class="col-md-6">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><strong>@lang('menu.personal_information')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><strong>@lang('menu.date_of_birth') :</strong> {{ $user->date_of_birth }}</p>
                                                <p><strong>@lang('menu.gender') :</strong> {{ $user->gender }}</p>
                                                <p><strong>@lang('menu.marital_status') :</strong> {{ $user->marital_status }}</p>
                                                <p><strong>@lang('menu.blood_group') : </strong> {{ $user->blood_group }}</p>
                                                <p><strong>@lang('menu.id_proof_name') : </strong> {{ $user->id_proof_name }}</p>
                                                <p><strong>@lang('menu.id_proof_number') : </strong> {{ $user->id_proof_number }}</p>
                                                <p><strong>@lang('menu.permanent_address') :</strong> {{ $user->permanent_address }}</p>
                                                <p><strong>@lang('menu.current_address') :</strong> {{ $user->current_address }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><strong>@lang('menu.other_information')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><strong>@lang('menu.guardian_name') :</strong> {{ $user->guardian_name }}</p>
                                                <p><strong>@lang('menu.facebook_link') :</strong> {{ $user->facebook_link }}</p>
                                                <p><strong>@lang('menu.twitter_link') :</strong> {{ $user->twitter_link }}</p>
                                                <p><strong>@lang('menu.instagram_link') :</strong> {{ $user->instagram_link }}</p>
                                                <p><strong>@lang('menu.custom_field') 1 :</strong> {{ $user->custom_field_1 }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-1">
                            <div class="col-md-6">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><strong>@lang('menu.bank_information')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><strong>@lang('menu.account_holders_name') :</strong> {{ $user->bank_ac_holder_name }}
                                                </p>
                                                <p><strong>@lang('menu.account_no') :</strong> {{ $user->bank_ac_no }}</p>
                                                <p><strong>@lang('menu.bank_name') :</strong> {{ $user->bank_name }}</p>
                                                <p><strong>@lang('menu.bank') @lang('menu.identifier_code') :</strong>
                                                    {{ $user->bank_identifier_code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                        <form id="filter_sr_orders" method="get" class="px-2 filter_form">
                                            <div class="form-group row align-items-end g-2">
                                                <div class="col-md-2">
                                                    <label><strong>@lang('menu.customer') </strong></label>
                                                    <select name="customer_account_id" class="form-control select2 form-select" id="sales_order_customer_account_id" autofocus>
                                                        <option data-customer_name="All" value="">@lang('menu.all')
                                                        </option>
                                                        @foreach ($customerAccounts as $customerAccount)
                                                            <option data-customer_name="{{ $customerAccount->name . '/' . $customerAccount->phone }}" value="{{ $customerAccount->id }}">
                                                                {{ $customerAccount->name . '/' . $customerAccount->phone }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

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
                                                    <a href="{{ route('sales.sr.print.orders', $user->id) }}" data-prefix="sales_order" class="btn btn-sm btn-info" id="print_report"><i class="fas fa-print"></i> @lang('menu.print')</a>
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
                                    <div class="table-responsive h-350">
                                        <table class="display data_tbl data__table orders_table w-100">
                                            <thead>
                                                <tr>
                                                    <th>@lang('menu.actions')</th>
                                                    <th>@lang('menu.date')</th>
                                                    <th>@lang('menu.exp_date')</th>
                                                    <th>@lang('menu.order_id')</th>
                                                    <th>@lang('menu.customer')</th>
                                                    <th>{{ __("Sr.") }}</th>
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
                                                    <th colspan="9" class="text-white text-end">@lang('menu.total') :
                                                        ({{ json_decode($generalSettings->business, true)['currency'] }})
                                                    </th>
                                                    <th id="total_ordered_qty" class="text-white text-end"></th>
                                                    <th id="total_delivered_qty" class="text-white text-end"></th>
                                                    <th id="do_total_left_qty" class="text-white text-end"></th>
                                                    <th id="total_payable_amount" class="text-white text-end"></th>
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
                                        <form id="filter_sr_sales" method="get" class="px-2 filter_form">
                                            <div class="form-group row align-items-end g-2">
                                                <div class="col-xl-2 col-md-3">
                                                    <label><strong>@lang('menu.customer') </strong></label>
                                                    <select name="customer_account_id" class="form-control select2 form-select" id="sales_customer_account_id" autofocus>
                                                        <option data-customer_name="All" value="">@lang('menu.all')
                                                        </option>
                                                        @foreach ($customerAccounts as $customerAccount)
                                                            <option data-customer_name="{{ $customerAccount->name . '/' . $customerAccount->phone }}" value="{{ $customerAccount->id }}">
                                                                {{ $customerAccount->name . '/' . $customerAccount->phone }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-xl-2 col-md-3">
                                                    <label><strong>@lang('menu.from_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="sales_from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-xl-2 col-md-3">
                                                    <label><strong>@lang('menu.to_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>

                                                        <input type="text" name="to_date" id="sales_to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-xl-2 col-md-3">
                                                    <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                                    <a href="{{ route('sales.sr.print.sales', $user->id) }}" class="btn btn-sm btn-info" id="print_report" data-prefix="sales"><i class="fas fa-print"></i> @lang('menu.print')</a>
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
                                                    <th class="text-start">@lang('menu.sr')</th>
                                                    <th class="text-end">@lang('menu.total_qty')</th>
                                                    <th class="text-end">@lang('menu.net_weight')</th>
                                                    <th class="text-end">@lang('menu.total_amount')</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="7" class="text-white text-end">@lang('menu.total') :
                                                        ({{ json_decode($generalSettings->business, true)['currency'] }})
                                                    </th>

                                                    <th id="total_sold_qty" class="text-white text-end"></th>
                                                    <th id="net_weight" class="text-white text-end"></th>
                                                    <th id="total_payable_amount" class="text-white text-end"></th>
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
                                                <form id="filter_sr_vouchers" class="py-2 px-2 filter_form" method="get">
                                                    <div class="form-group row align-items-end g-2">
                                                        <div class="col-xl-2 col-md-2">
                                                            <label><strong>@lang('menu.customer') </strong></label>
                                                            <select name="customer_id" class="form-control select2 form-select" id="vouchers_customer_account_id" autofocus>
                                                                <option data-customer_name="All" value="">
                                                                    @lang('menu.all')</option>
                                                                @foreach ($customerAccounts as $customerAccount)
                                                                    <option data-customer_name="{{ $customerAccount->name . '/' . $customerAccount->phone }}" value="{{ $customerAccount->id }}">
                                                                        {{ $customerAccount->name . '/' . $customerAccount->phone }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-xl-2 col-md-2">
                                                            <label><strong>@lang('menu.voucher_type') </strong></label>
                                                            <select name="voucher_type" class="form-control form-select" id="vouchers_type" autofocus>
                                                                <option value="">@lang('menu.all')</option>
                                                                <option value="8">@lang('menu.receipts')</option>
                                                                <option value="9">@lang('menu.payments')</option>
                                                                <option value="13">@lang('menu.journals')</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.from_date') :</strong></label>
                                                            <div class="input-group">

                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>

                                                                <input type="text" name="from_date" id="vouchers_from_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.to_date') :</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="to_date" id="vouchers_to_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.note/remarks') :</strong></label>
                                                            <select name="note" class="form-control form-select" id="vouchers_note">
                                                                <option value="0">{{ __('No') }}</option>
                                                                <option selected value="1">{{ __('Yes') }}</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.voucher_details') :</strong></label>
                                                            <select name="voucher_details" class="form-control form-select" id="vouchers_voucher_details">
                                                                <option value="0">{{ __('No') }}</option>
                                                                <option value="1">{{ __('Yes') }}</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.transaction_details') :</strong></label>
                                                            <select name="transaction_details" class="form-control form-select" id="vouchers_transaction_details">
                                                                <option value="0">{{ __('No') }}</option>
                                                                <option value="1">{{ __('Yes') }}</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i>
                                                                @lang('menu.filter')</button>
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
                                                <th class="text-end text-white" colspan="4">@lang('menu.total') : </th>
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

    <!-- Details Modal -->
    <div id="details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();
        //Get customer Ledgers by yajra data table
        var ledger_table = $('.ledger_table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            dom: "lBfrtip",
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],

            "ajax": {
                "url": "{{ route('accounting.accounts.ledger', [$user->id, 'userId']) }}",
                "data": function(d) {
                    d.customer_account_id = $('#ledger_customer_account_id').val();
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
                },
                {
                    data: 'particulars',
                    name: 'particulars'
                },
                {
                    data: 'voucher_type',
                    name: 'voucher_no'
                },
                {
                    data: 'voucher_no',
                    name: 'voucher_no'
                },
                {
                    data: 'debit',
                    name: 'account_ledgers.debit',
                    className: 'text-end'
                },
                {
                    data: 'credit',
                    name: 'account_ledgers.credit',
                    className: 'text-end'
                },
                {
                    data: 'running_balance',
                    name: 'account_ledgers.running_balance',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
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
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                },
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('sales.order.index', ['null', $user->id]) }}",
                "data": function(d) {
                    d.customer_account_id = $('#sales_order_customer_account_id').val();
                    d.from_date = $('#sales_order_from_date').val();
                    d.to_date = $('#sales_order_to_date').val();
                }
            },
            columns: [{
                    data: 'action'
                },
                {
                    data: 'order_date',
                    name: 'order_date'
                },
                {
                    data: 'expire_date',
                    name: 'sales.expire_date'
                },
                {
                    data: 'order_id',
                    name: 'sales.order_id',
                    className: 'fw-bold'
                },
                {
                    data: 'customer',
                    name: 'customers.name'
                },
                {
                    data: 'sr',
                    name: 'sr.name'
                },
                {
                    data: 'current_status',
                    name: 'sales.order_id'
                },
                {
                    data: 'delivery_status',
                    name: 'sales.order_id'
                },
                // {data: 'paid_status', name: 'sales.order_id'},
                {
                    data: 'do_approval',
                    name: 'do_approval'
                },
                {
                    data: 'total_ordered_qty',
                    name: 'total_ordered_qty'
                },
                {
                    data: 'total_delivered_qty',
                    name: 'sr.prefix'
                },
                {
                    data: 'do_total_left_qty',
                    name: 'sr.last_name'
                },
                {
                    data: 'total_payable_amount',
                    name: 'sales.quotation_id'
                },
            ],
            fnDrawCallback: function() {

                var total_ordered_qty = sum_table_col($('.data_tbl'), 'total_ordered_qty');
                $('#total_ordered_qty').text(bdFormat(total_ordered_qty));

                var total_delivered_qty = sum_table_col($('.data_tbl'), 'total_delivered_qty');
                $('#total_delivered_qty').text(bdFormat(total_delivered_qty));

                var do_total_left_qty = sum_table_col($('.data_tbl'), 'do_total_left_qty');
                $('#do_total_left_qty').text(bdFormat(do_total_left_qty));

                var total_payable_amount = sum_table_col($('.data_tbl'), 'total_payable_amount');
                $('#total_payable_amount').text(bdFormat(total_payable_amount));
                $('.data_preloader').hide();
            }
        });

        var sales_table = $('.sales_table').DataTable({
            "processing": true,
            "serverSide": true,

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],

            "ajax": {
                "url": "{{ route('sales.index', ['null', $user->id]) }}",
                "data": function(d) {
                    d.customer_account_id = $('#sales_customer_account_id').val();
                    d.from_date = $('#sales_from_date').val();
                    d.to_date = $('#sales_to_date').val();
                }
            },

            columns: [{
                    data: 'action'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'invoice_id',
                    name: 'sales.invoice_id',
                    className: 'fw-bold'
                },
                {
                    data: 'do_id',
                    name: 'do.do_id',
                    className: 'fw-bold'
                },
                {
                    data: 'do_to_inv_challan_no',
                    name: 'sales.do_to_inv_challan_no'
                },
                {
                    data: 'customer',
                    name: 'customers.name'
                },
                {
                    data: 'sr',
                    name: 'sr.name'
                },
                {
                    data: 'total_sold_qty',
                    name: 'sr.prefix',
                    className: 'text-end'
                },
                {
                    data: 'net_weight',
                    name: 'sr.last_name',
                    className: 'text-end'
                },
                {
                    data: 'total_payable_amount',
                    name: 'total_payable_amount',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {

                var total_sold_qty = sum_table_col($('.data_tbl'), 'total_sold_qty');
                $('#total_sold_qty').text(bdFormat(total_sold_qty));

                var net_weight = sum_table_col($('.data_tbl'), 'net_weight');
                $('#net_weight').text(bdFormat(net_weight));

                var total_payable_amount = sum_table_col($('.data_tbl'), 'total_payable_amount');
                $('#total_payable_amount').text(bdFormat(total_payable_amount));
                $('.data_preloader').hide();
            }
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
                "url": "{{ route('accounting.accounts.voucher.list', [$user->id, 'srUserId']) }}",
                "data": function(d) {
                    d.customer_account_id = $('#vouchers_customer_account_id').val();
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
                },
                {
                    data: 'descriptions',
                    name: 'journals.journal_voucher'
                },
                {
                    data: 'voucher_type',
                    name: 'voucher_no'
                },
                {
                    data: 'voucher_no',
                    name: 'payments.payment_voucher'
                },
                {
                    data: 'debit',
                    name: 'account_ledgers.debit',
                    className: 'text-end'
                },
                {
                    data: 'credit',
                    name: 'account_ledgers.credit',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {

                var debit = sum_table_col($('.data_tbl'), 'debit');
                $('#voucher_table_total_debit').text(bdFormat(debit));

                var credit = sum_table_col($('.data_tbl'), 'credit');
                $('#voucher_table_total_credit').text(bdFormat(credit));
                $('.data_preloader').hide();
            }
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

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_sr_ledger', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            ledger_table.ajax.reload();
            getSrAmountsCustomerWise();
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_sr_orders', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            orders_table.ajax.reload();
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_sr_sales', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            sales_table.ajax.reload();
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_sr_vouchers', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            vouchers_table.ajax.reload();
        });

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

                    getSrAmountsCustomerWise();
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

        $(document).on('click', '.print_challan_btn', function(e) {
            e.preventDefault();

            var body = $('.challan_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 800,
                header: null,
                footer: null,
            });
        });

        //Print Customer ledger
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var prefix = $(this).data('prefix');

            var customer_account_id = $('#' + prefix + '_customer_account_id').val();
            var customer_name = $('#' + prefix + '_customer_account_id').find('option:selected').data('customer_name');
            var from_date = $('#' + prefix + '_from_date').val();
            var to_date = $('#' + prefix + '_to_date').val();
            var note = $('#' + prefix + '_note').val();
            var voucher_details = $('#' + prefix + '_voucher_details').val();
            var transaction_details = $('#' + prefix + '_transaction_details').val();
            var inventory_list = $('#' + prefix + '_inventory_list').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    customer_account_id,
                    customer_name,
                    note,
                    voucher_details,
                    transaction_details,
                    inventory_list,
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
                        printDelay: 1000,
                    });
                }
            });
        });

        function getSrAmountsCustomerWise() {

            filterObj = {
                customer_account_id: $('#ledger_customer_account_id').val(),
                from_date: $('#ledger_from_date').val(),
                to_date: $('#ledger_to_date').val(),
            };

            $.ajax({
                url: "{{ route('sales.sr.closing.balance', $user->id) }}",
                type: 'get',
                data: filterObj,
                success: function(data) {

                    $('#ledger_debit_opening_balance').html('');
                    $('#ledger_credit_opening_balance').html('');
                    $('#ledger_debit_closing_balance').html('');
                    $('#ledger_credit_closing_balance').html('');

                    $('#ledger_table_total_debit').html(bdFormat(data.all_total_debit));
                    $('#ledger_table_total_credit').html(bdFormat(data.all_total_credit));
                    $('#ledger_table_current_balance').html(data.closing_balance_string);

                    if (data.opening_balance_side == 'dr') {

                        $('#ledger_debit_opening_balance').html(bdFormat(data.opening_balance));
                    } else {

                        $('#ledger_credit_opening_balance').html(bdFormat(data.opening_balance));
                    }

                    $('#ledger_total_debit').html(bdFormat(data.curr_total_debit));
                    $('#ledger_total_credit').html(bdFormat(data.curr_total_credit));

                    if (data.closing_balance_side == 'dr') {

                        $('#ledger_debit_closing_balance').html(bdFormat(data.closing_balance));
                    } else {

                        $('#ledger_credit_closing_balance').html(bdFormat(data.closing_balance));
                    }
                }
            });
        }

        getSrAmountsCustomerWise();
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
            element: document.getElementById('sales_from_date'),
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
            element: document.getElementById('sales_to_date'),
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
            format: 'DD-MM-YYYY'
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
            format: 'DD-MM-YYYY',
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
    </script>
@endpush
