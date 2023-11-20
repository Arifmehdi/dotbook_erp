@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
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

    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ $supplier->name }}</h6>
                </div>
                <div><a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i
                            class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back') </a></div>
            </div>
        </div>
        <div class="p-15">
            <div class="card">
                <div class="card-body">
                    <div class="tab_list_area">
                        <ul class="list-unstyled">
                            <li>
                                <a id="tab_btn" data-show="ledger" class="tab_btn tab_active" href="#">
                                    <i class="fas fa-scroll"></i>@lang('menu.ledger')
                                </a>
                            </li>

                            <li>
                                <a id="tab_btn" data-show="contract_info_area" class="tab_btn" href="#">
                                    <i class="fas fa-info-circle"></i> @lang('menu.contact_info')
                                </a>
                            </li>

                            <li>
                                <a id="tab_btn" data-show="purchase_orders" class="tab_btn" href="#">
                                    <i class="fas fa-shopping-bag"></i> @lang('menu.purchase_orders')
                                </a>
                            </li>

                            <li>
                                <a id="tab_btn" data-show="purchases" class="tab_btn" href="#">
                                    <i class="fas fa-shopping-bag"></i> @lang('menu.purchases')
                                </a>
                            </li>

                            <li>
                                <a id="tab_btn" data-show="vouchers" class="tab_btn" href="#">
                                    <i class="far fa-money-bill-alt"></i> @lang('menu.vouchers')
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab_contant ledger">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 col-lg-4">
                                @include('contacts::suppliers.partials.account_summery_by_ledger')
                            </div>

                            <div class="col-lg-8 col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading">
                                        <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_supplier_ledgers" method="get" class="px-2 filter_form">
                                            <div class="form-group row align-items-end g-2">
                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.from_date') :</strong></label>
                                                    <div class="input-group">

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
                                                        </div>

                                                        <input type="text" name="from_date" id="ledger_from_date"
                                                            class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.to_date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="ledger_to_date"
                                                            class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.note/remarks') :</strong></label>
                                                    <select name="note" class="form-control form-select"
                                                        id="ledger_note">
                                                        <option value="0">No</option>
                                                        <option selected value="1">Yes</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.voucher_details') :</strong></label>
                                                    <select name="voucher_details" class="form-control form-select"
                                                        id="ledger_voucher_details">
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.transaction_details') :</strong></label>
                                                    <select name="transaction_details" class="form-control form-select"
                                                        id="ledger_transaction_details">
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.inventory_list') :</strong></label>
                                                    <select name="inventory_list" class="form-control form-select"
                                                        id="ledger_inventory_list">
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-sm btn-info"><i
                                                            class="fa-solid fa-filter-list"></i>
                                                        @lang('menu.filter')</button>
                                                    <button type="submit" id="print_ledger"
                                                        class="btn btn-sm btn-info"><i class="fa-light fa-print"></i>
                                                        @lang('menu.print')</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="data_preloader d-none" id="ledger_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>

                            <div class="col-md-12">
                                <div class="ledger_list_table">
                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table ledger_table w-100">
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
                                                    <th colspan="4" class="text-white"
                                                        style="text-align: right!important;">@lang('menu.current_total') : </th>
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
                        <div class="row justify-content-center">
                            <div class="col-xl-8 col-md-6">
                                <ul class="list-unstyled d-flex"><br>
                                    <li><strong>@lang('menu.supplier_name'):</strong></li>
                                    <li>{{ $supplier->name }}</li>
                                </ul>
                                <ul class="list-unstyled d-flex"><br>
                                    <li><strong><i class="fas fa-map-marker-alt"></i> Address:</strong></li>
                                    <li>{{ $supplier->address }}</li>
                                </ul>
                                <ul class="list-unstyled d-flex"><br>
                                    <li><strong><i class="fas fa-briefcase"></i> @lang('menu.business_name'):</strong></li>
                                    <li>{{ $supplier->business_name }}</li>
                                </ul>
                                <ul class="list-unstyled d-flex"><br>
                                    <li><strong><i class="fas fa-phone-square"></i> @lang('menu.phone'):</strong></li>
                                    <li>{{ $supplier->phone }}</li>
                                </ul>
                                <ul class="list-unstyled d-flex"><br>
                                    <li><strong><i class="fas fa-info"></i> Tex Number:</strong></li>
                                    <li><span class="tax_number">{{ $supplier->tax_number }}</span></li>
                                </ul>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <ul class="list-unstyled list-unstyled-2 d-flex">
                                    <li>
                                        <strong>@lang('menu.total_purchase') : </strong>
                                    </li>

                                    <li>
                                        <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        <span
                                            class="total_purchase">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase) }}</span>
                                    </li>
                                </ul>

                                <ul class="list-unstyled list-unstyled-2 d-flex">
                                    <li>
                                        <strong> @lang('menu.total_paid') : </strong>
                                    </li>

                                    <li>
                                        <b> {{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        <span
                                            class="total_paid">{{ App\Utils\Converter::format_in_bdt($supplier->total_paid) }}</span>
                                    </li>
                                </ul>

                                <ul class="list-unstyled list-unstyled-2 d-flex">
                                    <li>
                                        <strong> Total Less : </strong>
                                    </li>

                                    <li>
                                        <b> {{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        <span
                                            class="total_less">{{ App\Utils\Converter::format_in_bdt($supplier->total_less) }}</span>
                                    </li>
                                </ul>

                                <ul class="list-unstyled list-unstyled-2 d-flex">
                                    <li>
                                        <strong> @lang('menu.total_purchase_due') :</strong>
                                    </li>

                                    <li>
                                        <b> {{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        <span
                                            class="total_purchase_due">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_due) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant purchases d-none">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="account_summary_area">
                                    <div class="heading">
                                        <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_supplier_purchases" method="get" class="px-2">
                                            <div class="form-group row align-items-end g-2 mb-3">
                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.from_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="purchase_from_date"
                                                            class="form-control " autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.to_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="purchase_to_date"
                                                            class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-sm btn-info"><i
                                                            class="fa-solid fa-filter-list"></i>
                                                        @lang('menu.filter')</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="data_preloader d-none" id="purchase_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>

                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table purchase_table w-100">
                                            <thead>
                                                <tr>
                                                    <th>@lang('menu.actions')</th>
                                                    <th>@lang('menu.date')</th>
                                                    <th>@lang('short.p_invoice_id')</th>
                                                    <th>@lang('menu.requisition_no')</th>
                                                    <th>@lang('menu.po_id')</th>
                                                    <th>@lang('menu.rs_voucher')</th>
                                                    <th>@lang('menu.note')</th>
                                                    <th>@lang('menu.departments')</th>
                                                    <th>@lang('menu.supplier')</th>
                                                    <th>@lang('menu.add_expense')</th>
                                                    <th>@lang('menu.total_invoice_amount')</th>
                                                    <th>@lang('menu.created_by')</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">

                                                    <th colspan="9" class="text-end text-white">@lang('menu.total')
                                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                                    </th>
                                                    <th id="total_additional_expense" class="text-white"></th>
                                                    <th id="total_purchase_amount" class="text-white"></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant purchase_orders d-none">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="account_summary_area">
                                    <div class="heading">
                                        <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_supplier_orders" method="get" class="px-2">
                                            <div class="form-group row align-items-end g-2 mb-3">
                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.from_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date"
                                                            id="purchase_order_from_date" class="form-control"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('menu.to_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="purchase_order_to_date"
                                                            class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-sm btn-info"><i
                                                            class="fa-solid fa-filter-list"></i>
                                                        @lang('menu.filter')</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="data_preloader d-none" id="order_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table purchase_orders_table w-100">
                                            <thead>
                                                <tr>
                                                    <th>@lang('menu.actions')</th>
                                                    <th>@lang('menu.date')</th>
                                                    <th>@lang('menu.po_id')</th>
                                                    <th>@lang('menu.requisition_no')</th>
                                                    <th>@lang('menu.supplier')</th>
                                                    <th>@lang('menu.created_by')</th>
                                                    <th>@lang('menu.receiving_status')</th>
                                                    {{-- <th>@lang('menu.payment_status')</th> --}}
                                                    <th>@lang('menu.total_ordered_amount')</th>
                                                    {{-- <th>@lang('menu.paid')</th>
                                                <th>@lang('menu.payment_due')</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="7" class="text-white text-end">@lang('menu.total')
                                                        ({{ json_decode($generalSettings->business, true)['currency'] }})
                                                    </th>
                                                    <th class="text-white text-end" id="total_purchase_amount"></th>
                                                    {{-- <th class="text-white text-end" id="paid"></th>
                                                <th class="text-white text-end" id="due"></th> --}}
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
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
                                                <form id="filter_supplier_vouchers" method="get"
                                                    class="px-2 filter_form">
                                                    <div class="form-group row align-items-end g-2">
                                                        <div class="col-xl-2 col-md-2">
                                                            <label><strong>@lang('menu.voucher_type') </strong></label>
                                                            <select name="voucher_type" class="form-control form-select"
                                                                id="vouchers_type" autofocus>
                                                                <option value="">@lang('menu.all')</option>
                                                                <option value="9">@lang('menu.payments')</option>
                                                                <option value="8">@lang('menu.receipts')</option>
                                                                <option value="13">@lang('menu.journals')</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.from_date') :</strong></label>
                                                            <div class="input-group">

                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i
                                                                            class="fas fa-calendar-week input_f"></i></span>
                                                                </div>

                                                                <input type="text" name="from_date"
                                                                    id="vouchers_from_date" class="form-control"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.to_date') :</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i
                                                                            class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="to_date"
                                                                    id="vouchers_to_date" class="form-control"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.note/remarks') :</strong></label>
                                                            <select name="note" class="form-control form-select"
                                                                id="vouchers_note">
                                                                <option value="0">No</option>
                                                                <option selected value="1">Yes</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.voucher_details') :</strong></label>
                                                            <select name="voucher_details"
                                                                class="form-control form-select"
                                                                id="vouchers_voucher_details">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.transaction_details') :</strong></label>
                                                            <select name="transaction_details"
                                                                class="form-control form-select"
                                                                id="vouchers_transaction_details">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-sm btn-info"><i
                                                                    class="fa-solid fa-filter-list"></i>
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
                                                <th>@lang('menu.date')</th>
                                                <th>@lang('menu.description')</th>
                                                <th>@lang('menu.voucher_type')</th>
                                                <th>@lang('menu.voucher_no')</th>
                                                <th>@lang('menu.debit')</th>
                                                <th>@lang('menu.credit')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th class="text-white" colspan="4">@lang('menu.total') : </th>
                                                <th class="text-white" id="voucher_table_total_debit"></th>
                                                <th class="text-white" id="voucher_table_total_credit"></th>
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

        <form id="deleted_form" action="" method="post">
            @method('DELETE')
            @csrf
        </form>

        <div id="details"></div>
    @endsection
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
            integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
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
                    "url": "{{ route('accounting.accounts.ledger', [$supplier->supplier_account_id, 'accountId']) }}",
                    "data": function(d) {
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

            var table = $('.purchase_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],

                "ajax": {
                    "url": "{{ route('purchases.index', $supplier->supplier_account_id) }}",
                    "data": function(d) {
                        d.from_date = $('#purchase_from_date').val();
                        d.to_date = $('#purchase_to_date').val();
                    }
                },
                columns: [{
                        data: 'action'
                    }, {
                        data: 'date',
                        name: 'date'
                    }, {
                        data: 'invoice_id',
                        name: 'purchases.invoice_id'
                    }, {
                        data: 'requisition_no',
                        name: 'rs_requisitions.rs_requisition_no'
                    }, {
                        data: 'po_id',
                        name: 'po.po_id'
                    }, {
                        data: 'rs_voucher_no',
                        name: 'receive_stocks.rs_voucher_no'
                    }, {
                        data: 'purchase_note',
                        name: 'purchases.purchase_note'
                    }, {
                        data: 'department',
                        name: 'departments.name'
                    }, {
                        data: 'supplier_name',
                        name: 'suppliers.name'
                    },
                    // {data: 'status',name: 'purchase_requisitions.requisition_no'},
                    // {data: 'payment_status', name: 'expanses.invoice_id'},
                    {
                        data: 'total_additional_expense',
                        name: 'purchase_requisitions.requisition_no',
                        className: 'text-end'
                    }, {
                        data: 'total_purchase_amount',
                        name: 'expanses.invoice_id',
                        className: 'text-end'
                    },
                    // {data: 'paid',name: 'paid', name: 'rs_requisitions.requisition_no', className: 'text-end'},
                    // {data: 'due',name: 'due', name: 'receive_stocks.voucher_no', className: 'text-end'},
                    {
                        data: 'created_by',
                        name: 'created_by.created_name'
                    },
                ],
                fnDrawCallback: function() {

                    var total_additional_expense = sum_table_col($('.data_tbl'), 'total_additional_expense');
                    $('#total_additional_expense').text(bdFormat(total_additional_expense));

                    var total_purchase_amount = sum_table_col($('.data_tbl'), 'total_purchase_amount');
                    $('#total_purchase_amount').text(bdFormat(total_purchase_amount));

                    // var paid = sum_table_col($('.data_tbl'), 'paid');
                    // $('#paid').text(bdFormat(paid));

                    // var due = sum_table_col($('.data_tbl'), 'due');
                    // $('#due').text(bdFormat(due));

                    $('.data_preloader').hide();
                }
            });

            purchase_order_table = $('.purchase_orders_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                "ajax": {
                    "url": "{{ route('purchases.order.index', $supplier->supplier_account_id) }}",
                    "data": function(d) {
                        d.from_date = $('#purchase_order_date').val();
                        d.to_date = $('#purchase_order_to_date').val();
                    }
                },
                columns: [{
                        data: 'action',
                        name: 'purchases.invoice_id'
                    }, {
                        data: 'date',
                        name: 'purchases.date'
                    }, {
                        data: 'invoice_id',
                        name: 'purchases.invoice_id'
                    }, {
                        data: 'requisition_no',
                        name: 'purchase_requisitions.requisition_no'
                    }, {
                        data: 'supplier_name',
                        name: 'suppliers.name'
                    }, {
                        data: 'created_by',
                        name: 'created_by.name'
                    }, {
                        data: 'status',
                        name: 'purchases.po_receiving_status'
                    },
                    // {data: 'payment_status', name: 'purchase_requisitions.requisition_no', className: 'text-end'},
                    {
                        data: 'total_purchase_amount',
                        name: 'total_purchase_amount',
                        className: 'text-end'
                    },
                    // {data: 'paid', name: 'purchases.paid', className: 'text-end'},
                    // {data: 'due', name: 'purchases.due', className: 'text-end'},
                ],
                fnDrawCallback: function() {
                    var total_purchase_amount = sum_table_col($('.data_tbl'), 'total_purchase_amount');
                    $('#total_purchase_amount').text(bdFormat(total_purchase_amount));
                    // var paid = sum_table_col($('.data_tbl'), 'paid');
                    // $('#paid').text(bdFormat(paid));
                    // var due = sum_table_col($('.data_tbl'), 'due');
                    // $('#due').text(bdFormat(due));
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
                    "url": "{{ route('accounting.accounts.voucher.list', [$supplier->supplier_account_id, 'accountId']) }}",
                    "data": function(d) {
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

                    var debit = sum_table_col($('.data_tbl'), 'voucher_debit');
                    $('#voucher_table_total_debit').text(bdFormat(debit));

                    var credit = sum_table_col($('.data_tbl'), 'voucher_credit');
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
            $(document).on('submit', '#filter_supplier_purchases', function(e) {
                e.preventDefault();

                $('#purchase_preloader').show();
                $('.purchase_table').DataTable().ajax.reload();
            });

            //Submit filter form by select input changing
            $(document).on('submit', '#filter_supplier_orders', function(e) {
                e.preventDefault();

                $('#order_preloader').show();
                $('.purchase_orders_table').DataTable().ajax.reload();
            });

            //Submit filter form by select input changing
            $(document).on('submit', '#filter_supplier_ledgers', function(e) {
                e.preventDefault();

                $('#ledger_preloader').show();
                ledger_table.ajax.reload();
                getAccountClosingBalance();
            });

            //Submit filter form by select input changing
            $(document).on('submit', '#filter_supplier_vouchers', function(e) {
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

                        $('.data_preloader').hide();
                        $('#details').html(data);
                        $('#detailsModal').modal('show');
                        $('.action_hideable').hide();
                        $('.action_hideable').removeClass('d-inline-block');
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
                    loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                    removeInline: false,
                    printDelay: 500,
                    header: null,
                });
            });

            // Print Packing slip
            $(document).on('click', '#print_supplier_copy', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
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
                });
            });

            $(document).on('click', '#add_payment', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        $('#paymentModal').html(data);
                        $('#paymentModal').modal('show');
                    }
                });
            });

            //Print Ledger
            //Print Customer ledger
            $(document).on('click', '#print_ledger', function(e) {
                e.preventDefault();

                var url =
                    "{{ route('accounting.accounts.ledger.print', [$supplier->supplier_account_id, 'accountId']) }}";

                var supplier_name = '';
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
                        supplier_name,
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

            $(document).on('click', '#print_report', function(e) {
                e.preventDefault();

                var url = "{{ route('reports.purchases.report.print') }}";

                var supplier_id = "{{ $supplier->id }}";
                var from_date = $('#purchase_from_date').val();
                var to_date = $('#purchase_to_date').val();

                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        supplier_id,
                        from_date,
                        to_date
                    },
                    success: function(data) {

                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                            removeInline: false,
                            printDelay: 500,
                            formValues: false,
                        });
                    }
                });
            });

            function getAccountClosingBalance() {

                var from_date = $('#ledger_from_date').val();
                var to_date = $('#ledger_to_date').val();

                var filterObj = {
                    user_id: null,
                    from_date: from_date ? from_date : null,
                    to_date: to_date ? to_date : null,
                };

                var url = "{{ route('vouchers.journals.account.closing.balance', $supplier->supplier_account_id) }}";

                $.ajax({
                    url: url,
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

            getAccountClosingBalance();
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
                element: document.getElementById('purchase_from_date'),
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
                element: document.getElementById('purchase_to_date'),
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
                element: document.getElementById('purchase_order_from_date'),
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
                element: document.getElementById('purchase_order_to_date'),
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
