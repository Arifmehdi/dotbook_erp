@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        .selected_requisition {
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

        .element-body {
            overflow: initial !important;
        }
    </style>
@endpush
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>{{ __('Import Purchase Order') }}</h6>
                <x-back-button />
            </div>
            <form id="add_import_form" action="{{ route('lc.imports.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action" value="">
                <section class="p-15">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>Exporter <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <div class="input-group">
                                                        <select name="exporter_id" class="form-control add_input" data-name="Supplier" id="exporter_id" required>
                                                            <option value="">Select Exporter</option>
                                                            @foreach ($exporters as $exporter)
                                                                <option value="{{ $exporter->id }}">
                                                                    {{ $exporter->name . '(' . $exporter->exporter_id . ')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @can('exporters_create')
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button" data-href="{{ route('lc.exporters.add.quick.exporter.modal') }}" id="quickAddButton"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                    <span class="error error_exporter_id"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>LC No <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <div class="input-group">
                                                        <select name="lc_id" class="form-control add_input" data-name="LC" id="lc_id" required>
                                                            <option value="">Select LC No</option>
                                                            @foreach ($lcs as $lc)
                                                                {{-- @php
                                                                $openingDate = date('d/m/Y', strtotime($lc->opening_date));
                                                                $lastDate = date('d/m/Y', strtotime($lc->last_date));
                                                                $expireDate = date('d/m/Y', strtotime($lc->expire_date));
                                                            @endphp --}}

                                                                <option value="{{ $lc->id }}">{{ $lc->lc_no }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <span class="error error_exporter_id"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.order_date') </b></label>
                                                <div class="col-8">
                                                    <input type="text" name="order_date" class="form-control changeable" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="date" placeholder="dd-mm-yyyy" autocomplete="off">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>IMPO No </b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="import_po_no" id="import_po_no" class="form-control" placeholder="Import Purchase Order no" autocomplete="off">
                                                    <span class="error error_import_po_no"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.ledger') A/c <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="ledger_account_id" class="form-control add_input" id="ledger_account_id" data-name="Ledger A/c" required>
                                                        @foreach ($purchaseAccounts as $purchaseAccount)
                                                            <option value="{{ $purchaseAccount->id }}">
                                                                {{ $purchaseAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_ledger_account_id"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>Receive Date </b></label>
                                                <div class="col-8">
                                                    <input type="text" name="receive_date" class="form-control changeable" id="receive_date" placeholder="DD-MM-YYYY" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>Proforma No </b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="proforma_no" id="proforma_no" class="form-control" placeholder="Proforma Invoice No" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>Import From <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="goods_country_id" class="form-control add_input" id="goods_country_id" data-name="Import From" required>
                                                        <option value="">Select Import From</option>
                                                        @foreach ($currencies as $currency)
                                                            <option value="{{ $currency->id }}">{{ $currency->country }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_goods_country_id"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>Destination <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="destination_country_id" class="form-control add_input" id="destination_country_id" data-name="Destination" required>
                                                        <option value="">Select Destination</option>
                                                        @foreach ($currencies as $currency)
                                                            <option value="{{ $currency->id }}">{{ $currency->country }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_destination_country_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>Delivery Terms</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="terms_of_delivery" id="terms_of_delivery" class="form-control" placeholder="Terms Of Delivery" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>Payment Terms</b> </label>
                                                <div class="col-8">
                                                    <textarea name="terms_of_payment" id="terms_of_payment" cols="3" rows="3" class="form-control" placeholder="Terms Of Payment"></textarea>
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
                                        <div class="row">
                                            <div class="col-lx-3 col-md-5">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('menu.item_search')</label>
                                                    <div class="input-group ">
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_item_item_code_scan_bar_code')" autofocus>
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

                                            <div class="col-xl-9">
                                                <div class="row align-items-end">
                                                    <input type="hidden" id="e_unique_id">
                                                    <input type="hidden" id="e_item_name">
                                                    <input type="hidden" id="e_product_id">
                                                    <input type="hidden" id="e_variant_id">
                                                    <input type="hidden" id="e_discount_amount">
                                                    <input type="hidden" id="e_unit_cost_with_discount">
                                                    <input type="hidden" id="e_unit_cost_inc_tax">

                                                    <div class="col-xl-3 col-md-4 mt-1">
                                                        <label><b>@lang('menu.quantity') </b> </label>
                                                        <div class="input-group">
                                                            <input type="number" step="any" class="form-control w-60" id="e_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                                            <select id="e_unit" class="form-control w-40 form-select">
                                                                <option value="">@lang('menu.unit')</option>
                                                                @foreach ($units as $unit)
                                                                    <option value="{{ $unit->name }}">
                                                                        {{ $unit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-2 col-md-4 mt-1">
                                                        <label><b>@lang('menu.unit_cost')<span id="currency_code"></span> </b>
                                                        </label>
                                                        <input type="number" step="any" class="form-control" id="e_unit_cost_exc_tax" value="0.00" placeholder="0.00" autocomplete="off">
                                                    </div>

                                                    <div class="col-xl-3 col-md-4 mt-1">
                                                        <label><b>@lang('menu.discount') </b> </label>
                                                        <div class="input-group">
                                                            <input type="number" step="any" class="form-control w-60" id="e_discount" value="0.00" placeholder="0.00" autocomplete="off">

                                                            <select id="e_discount_type" class="form-control w-40 form-select">
                                                                <option value="1">@lang('menu.fixed')(0.00)</option>
                                                                <option value="2">@lang('menu.percentage')(%)</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-2 col-md-4 mt-1">
                                                        <label><b>@lang('menu.sub_total') </b> </label>
                                                        <input readonly type="number" step="any" class="form-control" id="e_subtotal" value="0.00" placeholder="0.00" tabindex="-1">
                                                    </div>

                                                    <div class="col-xl-2 col-md-4 mt-2">
                                                        {{-- lee_lc_2 --}}
                                                        {{-- which permission should i give here? --}}
                                                        <a href="#" class="btn btn-sm btn-success px-2" id="add_item">@lang('menu.add')</a>
                                                        <input type="reset" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger px-2" value="@lang('menu.reset')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-4 mt-1">
                                                <label><b>Item Description </b> </label>

                                                <input type="text" step="any" class="form-control" id="e_description" placeholder="Item Description" autocomplete="off">
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
                                                                    <th>@lang('menu.unit_cost')</th>
                                                                    <th>@lang('menu.discount')</th>
                                                                    <th>Unit Cost With Discount</th>
                                                                    <th>@lang('menu.sub_total')</th>
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="import_item_list"></tbody>
                                                            <tfoot>
                                                                <th colspan="5" class="text-end">@lang('menu.net_total') :
                                                                </th>
                                                                <th id="td_net_total_amount">0.00</th>
                                                                <th>---</th>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="net_total_amount" id="net_total_amount">
                                                <input type="hidden" name="total_item" id="total_item">
                                                <input type="hidden" name="total_qty" id="total_qty">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-1">
                        <div class="col-md-6">
                            <div class="form_element rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>LC Amount </b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="lc_amount" class="form-control" id="lc_amount" placeholder="0.00">
                                                            <span class="error error_lc_amount"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>Currency </b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="currency_id" id="currency_id" class="form-control">
                                                                <option value="">Select Currency</option>
                                                                @foreach ($currencies as $currency)
                                                                    <option value="{{ $currency->id }}">
                                                                        {{ $currency->code }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_currency_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>Currency Rate </b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="currency_rate" id="currency_rate" class="form-control" placeholder="0.00">
                                                            <span class="error error_currency_rate"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.total_amount')({{ json_decode($generalSettings->business, true)['currency'] }})
                                                            </b></label>
                                                        <div class="col-8">
                                                            <input readonly type="text" name="total_amount" class="form-control add_input" id="total_amount" data-name="Total Amount" placeholder="@lang('menu.total_amount')" autocomplete="off" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>LC Margin Amount </b></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="lc_margin_amount" class="form-control" id="lc_margin_amount" data-name="LC Margin Amount" placeholder="LC Margin Amount" autocomplete="off" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>Insurance Company </b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select name="insurance_company_id" class="form-control" id="insurance_company_id">
                                                                    <option value="">Select Insurance Company
                                                                    </option>
                                                                    @foreach ($insuranceCompanies as $insuranceCompany)
                                                                        <option value="{{ $insuranceCompany->id }}">
                                                                            {{ $insuranceCompany->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-href="{{ route('lc.insurance.companies.add.quick.modal') }}" id="quickAddButton"><i class="fas fa-plus-square text-dark"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>Insurance Payable </b></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="insurance_payable_amt" class="form-control" id="insurance_payable_amt" placeholder="0.00" autocomplete="off" />
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
                            <div class="form_element rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>Shipment Mode </b></label>
                                                        <div class="col-8">
                                                            <select name="shipment_mode" class="form-control form-select">
                                                                <option value="1">C N F</option>
                                                                <option value="2">FOB</option>
                                                                <option value="3">FCA</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>CNF Agent </b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select name="cnf_agent_id" class="form-control" id="cnf_agent_id">
                                                                    <option value="">Select CNF Agent</option>
                                                                    @foreach ($cnfAgents as $cnfAgent)
                                                                        <option value="{{ $cnfAgent->id }}">
                                                                            {{ $cnfAgent->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-href="{{ route('lc.cnf.agents.add.quick.modal') }}" id="quickAddButton"><i class="fas fa-plus-square text-dark"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>Mode Of Amount </b></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="mode_of_amount" class="form-control" id="mode_of_amount" data-name="Mode Of Amount" placeholder="0.00" autocomplete="off" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>Advising Bank </b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select name="advising_bank_id" class="form-control add_input" id="advising_bank_id" data-name="Advising Bank">
                                                                    <option value="">Select Advising Bank</option>
                                                                    @foreach ($advisingBanks as $advisingBank)
                                                                        <option value="{{ $advisingBank->id }}">
                                                                            {{ $advisingBank->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-href="{{ route('lc.advising.bank.add.quick.modal') }}" id="quickAddButton"><i class="fas fa-plus-square text-dark"></i></span>
                                                                </div>
                                                            </div>
                                                            <span class="error error_advising_bank_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>Issuing Bank </b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="issuing_bank_id" class="form-control add_input" id="issuing_bank_id" data-name="Issuing Bank">
                                                                <option value="">Select Issuing Bank</option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
                                                                        {{ $bank->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_issuing_bank_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>Opening Bank </b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="opening_bank_id" class="form-control" id="opening_bank_id" data-name="Opening Bank">
                                                                <option value="">Select Opening Bank</option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
                                                                        {{ $bank->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_opening_bank_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>Transactional Credit Account </b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="account_id" class="form-control" id="account_id" data-name="Credit A/c">
                                                                <option value="">Select Credit Account</option>
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}">
                                                                        @php
                                                                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/c)';
                                                                            $bank = $account->bank ? ', BK : ' . $account->bank : '';
                                                                            $ac_no = $account->account_number ? ', A/c No : ' . $account->account_number : '';
                                                                            $balance = ', BL : ' . $account->balance;
                                                                        @endphp
                                                                        {{ $account->name . $accountType . $bank . $ac_no . $balance }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_account_id"></span>
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
                    <div class="row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                @can('import_purchase_order_create')
                                    <button type="submit" id="save_and_print" value="1" class="btn btn-success submit_button me-2">@lang('menu.save_and_print')</button>
                                    <button type="submit" id="save" value="2" class="btn btn-success submit_button">@lang('menu.save')</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </section>

            </form>
        </div>
    </div>

    <!-- Add Quick Exporter Modal -->
    <div class="modal fade" id="quickAddModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <!-- Add Quick Exporter Modal End-->

    @if (auth()->user()->can('product_add'))
        <!--Add Quick Product Modal-->
        <div class="modal fade" id="addQuickProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document" id="quick_product_add_modal_contant"></div>
        </div>
        <!--Add Quick Product Modal End-->
    @endif
@endsection
@push('scripts')
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
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
            var keyWord = $(this).val();
            var __keyWord = keyWord.replaceAll('/', '~');
            delay(function() {
                searchProduct(__keyWord);
            }, 200); //sendAjaxical is the name of remote-command
        });

        function searchProduct(keyWord) {

            $('.variant_list_area').empty();
            $('.select_area').hide();

            var isShowNotForSaleItem = 1;
            var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
            var route = url.replace(':keyWord', keyWord);
            route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(product) {

                    if (!$.isEmptyObject(product.errorMsg)) {

                        toastr.error(product.errorMsg);
                        $('#search_product').val('');
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

                                var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' :
                                    product.name;

                                var unique_id = product.id + 'noid';

                                $('#search_product').val(name);
                                $('#e_unique_id').val(unique_id);
                                $('#e_item_name').val(name);
                                $('#e_product_id').val(product.id);
                                $('#e_variant_id').val('noid');
                                $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                                $('#e_unit').val(product.unit.name);
                                $('#e_unit_cost_exc_tax').val(product.product_cost);
                                $('#e_discount').val(parseFloat(0).toFixed(2));
                                $('#e_discount_type').val(1);
                                $('#e_discount_amount').val(parseFloat(0).toFixed(2));
                                $('#e_unit_cost_with_discount').val(parseFloat(product.product_cost).toFixed(
                                    2));
                                $('#e_subtotal').val(parseFloat(product.product_cost).toFixed(2));
                            } else {

                                var li = "";
                                var tax_percent = product.tax_ac_id != null ? product.tax.tax_percent : 0.00;

                                $.each(product.variants, function(key, variant) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="' + product.id + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-unit="' + product.unit.name + '" data-v_code="' + variant.variant_code + '" data-v_cost="' + variant.variant_cost + '" data-v_name="' + variant.variant_name + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if (!$.isEmptyObject(product.namedProducts)) {

                            if (product.namedProducts.length > 0) {

                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    var tax_percent = product.tax_ac_id != null ? product.tax_percent : 0.00;

                                    if (product.is_variant == 1) {

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="' + product.id + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-unit="' + product.unit_name + '" data-v_code="' + product.variant_code + '" data-v_cost="' + product.variant_cost + '" data-v_name="' + product.variant_name + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                        li += '</li>';
                                    } else {

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_single_product" onclick="singleProduct(this); return false;" data-p_id="' + product.id + '" data-p_name="' + product.name + '" data-unit="' + product.unit_name + '" data-p_code="' + product.product_code + '" data-p_cost="' + product.product_cost + '" data-p_name="' + product.name + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                        li += '</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        } else if (!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();

                            var variant_product = product.variant_product;

                            var name = variant_product.product.name.length > 35 ? product.name.substring(0, 35) + '...' : variant_product.product.name;

                            var unique_id = variant_product.product.id + variant_product.id;

                            $('#e_unique_id').val(unique_id);
                            $('#search_product').val(name + ' - ' + variant_product.variant_name);
                            $('#e_item_name').val(name + ' - ' + variant_product.variant_name);
                            $('#e_product_id').val(variant_product.product.id);
                            $('#e_variant_id').val(variant_product.id);
                            $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_unit').val(variant_product.product.unit.name);
                            $('#e_unit_cost_exc_tax').val(variant_product.variant_cost);
                            $('#e_discount').val(parseFloat(0).toFixed(2));
                            $('#e_discount_type').val(1);
                            $('#e_discount_amount').val(parseFloat(0).toFixed(2));
                            $('#e_unit_cost_with_discount').val(parseFloat(variant_product.variant_cost)
                                .toFixed(2));
                            $('#e_subtotal').val(parseFloat(variant_product.variant_cost).toFixed(2));
                        }
                    } else {

                        $('#search_product').addClass('is-invalid');
                    }
                }
            });
        }

        // select single product and add purchase table
        var keyName = 1;

        function singleProduct(e) {

            if (keyName == 13 || keyName == 1) {

                document.getElementById('search_product').focus();
            }

            $('.select_area').hide();
            $('#search_product').val('');

            var product_id = e.getAttribute('data-p_id');
            var product_name = e.getAttribute('data-p_name');
            var product_unit = e.getAttribute('data-unit');
            var product_code = e.getAttribute('data-p_code');
            var product_cost = e.getAttribute('data-p_cost');

            var unique_id = product_id + 'noid';

            $('#e_unique_id').val(unique_id);
            $('#search_product').val(product_name);
            $('#e_item_name').val(product_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val('noid');
            $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
            $('#e_unit').val(product_unit);
            $('#e_unit_cost_exc_tax').val(parseFloat(product_cost).toFixed(2));
            $('#e_discount').val(parseFloat(0).toFixed(2));
            $('#e_discount_type').val(1);
            $('#e_discount_amount').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_with_discount').val(parseFloat(product_cost).toFixed(2));
            $('#e_subtotal').val(parseFloat(product_cost).toFixed(2));
        }

        // select variant product and add purchase table
        function salectVariant(e) {

            if (keyName == 13 || keyName == 1) {

                document.getElementById('search_product').focus();
            }

            $('.select_area').hide();
            $('#search_product').val("");
            var product_id = e.getAttribute('data-p_id');
            var product_name = e.getAttribute('data-p_name');
            var product_unit = e.getAttribute('data-unit');
            var variant_id = e.getAttribute('data-v_id');
            var variant_name = e.getAttribute('data-v_name');
            var variant_code = e.getAttribute('data-v_code');
            var variant_cost = e.getAttribute('data-v_cost');

            var unique_id = product_id + variant_id;

            $('#e_unique_id').val(unique_id);
            $('#search_product').val(product_name + ' -' + variant_name);
            $('#e_item_name').val(product_name + ' -' + variant_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
            $('#e_unit').val(product_unit);
            $('#e_unit_cost_exc_tax').val(parseFloat(variant_cost).toFixed(2));
            $('#e_discount').val(parseFloat(0).toFixed(2));
            $('#e_discount_type').val(1);
            $('#e_discount_amount').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_with_discount').val(parseFloat(variant_cost).toFixed(2));
            $('#e_subtotal').val(parseFloat(variant_cost).toFixed(2));
        }

        $('#add_item').on('click', function(e) {
            e.preventDefault();

            var e_unique_id = $('#e_unique_id').val();
            var e_item_name = $('#e_item_name').val();
            var e_product_id = $('#e_product_id').val();
            var e_variant_id = $('#e_variant_id').val();
            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_unit = $('#e_unit').val();
            var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
            var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
            var e_discount_type = $('#e_discount_type').val();
            var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
            var e_unit_cost_with_discount = $('#e_unit_cost_with_discount').val() ? $('#e_unit_cost_with_discount')
                .val() : 0;
            var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;

            var e_description = $('#e_description').val();

            if (e_product_id == '') {

                toastr.error('Please select a item.');
                return;
            }

            if (e_quantity == '') {

                toastr.error('Quantity field must not be empty.');
                return;
            }

            if (e_unit == '') {

                toastr.error('Please select a unit.');
                return;
            }

            var uniqueId = e_product_id + e_variant_id;

            var uniqueIdValue = $('#' + e_product_id + e_variant_id).val();

            if (uniqueIdValue == undefined) {

                var tr = '';
                tr += '<tr id="select_item">';
                tr += '<td>';
                tr += '<span id="span_item_name">' + e_item_name + '</span>';
                tr += '<input type="hidden" id="item_name" value="' + e_item_name + '">';
                tr += '<input type="hidden" name="descriptions[]" id="description" value="' + e_description + '">';
                tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + e_product_id + '">';
                tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + e_variant_id + '">';
                tr += '<input type="hidden" id="' + uniqueId + '" value="' + uniqueId + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly type="number" step="any" name="quantities[]" class="form-control" id="quantity" value="' +
                    e_quantity + '" autocomplete="off">';
                tr += '<span id="span_unit">' + e_unit + '</span>';
                tr += '<input type="hidden" name="units[]" step="any" id="unit" value="' + e_unit + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly type="text" name="unit_costs_exc_tax[]" class="form-control" id="unit_cost_exc_tax" value="' +
                    e_unit_cost_exc_tax + '" tabindex="-1">';

                tr += '<td>';
                tr += '<input readonly type="text" name="unit_discounts[]" class="form-control" id="unit_discount" value="' + parseFloat(e_discount).toFixed(2) + '" tabindex="-1">';
                tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + parseFloat(e_discount_amount).toFixed(2) + '">';
                tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + e_discount_type + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly name="unit_costs_with_discount[]" type="text" class="form-control" id="unit_cost_with_discount" value="' +
                    parseFloat(e_unit_cost_with_discount).toFixed(2) + '" tabindex="-1">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly type="text" name="subtotals[]" value="' + parseFloat(e_subtotal).toFixed(2) + '" id="subtotal" class="form-control" tabindex="-1">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                tr += '</td>';
                tr += '</tr>';

                $('#import_item_list').prepend(tr);
                clearEditItemFileds();
                calculateTotalAmount();
            } else {

                var tr = $('#' + uniqueId).closest('tr');
                tr.find('#item_name').val(e_item_name);
                tr.find('#span_item_name').html(e_item_name);
                tr.find('#description').val(e_description);
                tr.find('#product_id').val(e_product_id);
                tr.find('#variant_id').val(e_variant_id);
                tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                tr.find('#span_unit').html(e_unit);
                tr.find('#unit').val(e_unit);
                tr.find('#unit_cost_exc_tax').val(parseFloat(e_unit_cost_exc_tax).toFixed(2));
                tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
                tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
                tr.find('#unit_discount_type').val(e_discount_type);
                tr.find('#unit_cost_with_discount').val(parseFloat(e_unit_cost_with_discount).toFixed(2));
                tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                clearEditItemFileds();
                calculateTotalAmount();
            }
        });

        $(document).on('click', '#select_item', function(e) {

            var tr = $(this);
            var item_name = tr.find('#item_name').val();
            var description = tr.find('#description').val();
            var lot_number = tr.find('#lot_number').val();
            var product_id = tr.find('#product_id').val();
            var variant_id = tr.find('#variant_id').val();
            var quantity = tr.find('#quantity').val();
            var unit = tr.find('#unit').val();
            var unit_cost_exc_tax = tr.find('#unit_cost_exc_tax').val();
            var unit_discount = tr.find('#unit_discount').val();
            var unit_discount_amount = tr.find('#unit_discount_amount').val();
            var unit_discount_type = tr.find('#unit_discount_type').val();
            var unit_cost_with_discount = tr.find('#unit_cost_with_discount').val();
            var subtotal = tr.find('#subtotal').val();

            $('#search_product').val(item_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
            $('#e_unit').val(unit);
            $('#e_unit_cost_exc_tax').val(parseFloat(unit_cost_exc_tax).toFixed(2));
            $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
            $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
            $('#e_discount_type').val(unit_discount_type);
            $('#e_unit_cost_with_discount').val(parseFloat(unit_cost_with_discount).toFixed(2));
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
            $('#e_description').val(description);
        });

        function calculateEditOrAddAmount() {

            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
            var e_discount_type = $('#e_discount_type').val();
            var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;

            var discount_amount = 0;

            if (e_discount_type == 1) {

                discount_amount = e_discount
            } else {

                discount_amount = (parseFloat(e_unit_cost_exc_tax) / 100) * parseFloat(e_discount);
            }

            $('#e_discount_amount').val(parseFloat(discount_amount).toFixed(2));

            var costWithDiscount = parseFloat(e_unit_cost_exc_tax) - parseFloat(discount_amount);

            $('#e_unit_cost_with_discount').val(parseFloat(costWithDiscount).toFixed(2));

            var subtotal = parseFloat(costWithDiscount) * parseFloat(e_quantity);

            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        }

        function calculateTotalAmount() {

            var quantities = document.querySelectorAll('#quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            var total_item = 0;
            var total_qty = 0;

            quantities.forEach(function(qty) {

                total_item += 1;
                total_qty += parseFloat(qty.value)
            });

            $('#total_qty').val(parseFloat(total_qty));
            $('#total_item').val(parseFloat(total_item));

            //Update Net Total Amount
            var netTotalAmount = 0;
            subtotals.forEach(function(subtotal) {

                netTotalAmount += parseFloat(subtotal.value);
            });

            $('#td_net_total_amount').html(parseFloat(netTotalAmount).toFixed(2));
            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
        }

        function clearEditItemFileds() {

            $('#search_product').val('').focus();
            $('#e_unique_id').val('');
            $('#search_product').val('');
            $('#e_item_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_quantity').val(parseFloat(0).toFixed(2));
            $('#e_unit').val('');
            $('#e_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
            $('#e_discount').val(parseFloat(0).toFixed(2));
            $('#e_discount_type').val(1);
            $('#e_discount_amount').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_with_discount').val(parseFloat(0).toFixed(2));
            $('#e_subtotal').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_description').val('');
            $('add_item').html('Add');
        }

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#e_quantity', function() {

            calculateEditOrAddAmount();
        });

        // Change tax percent and clculate row amount
        $(document).on('input', '#e_unit_cost_exc_tax', function() {

            calculateEditOrAddAmount();
        });

        // Input discount and clculate row amount
        $(document).on('input', '#e_discount_type', function() {

            calculateEditOrAddAmount();
        });

        // Input discount and clculate row amount
        $(document).on('input', '#e_discount', function() {

            calculateEditOrAddAmount();
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

        //Add purchase request by ajax
        $('#add_import_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();

            var url = $(this).attr('action');

            $('.submit_button').prop('type', 'button');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    $('.error').html('');
                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'ERROR');
                    } else if (data.successMsg) {

                        toastr.success(data.successMsg);
                        $('#add_import_form')[0].reset();
                        $('#import_item_list').empty();
                        $('#search_product').prop('disabled', false);
                    } else {

                        toastr.success('Successfully LC Import is Created.');
                        $('#add_import_form')[0].reset();
                        $('#import_item_list').empty();
                        $('#search_product').prop('disabled', false);
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

                    $('.submit_button').prop('type', 'sumbit');
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

        $(document).on('click', '#quickAddButton', function() {

            var url = $(this).data('href');

            $.get(url, function(data) {

                $('#quickAddModal').html(data);
                $('#quickAddModal').modal('show');
            });
        });

        // set sub category in form field
        $('#lc_id').on('change', function() {

            var lc_id = $(this).val();

            if (lc_id == '') {

                $('#currency_id').val('');
                $('#currency_code').html('');
            }

            var url = "{{ route('common.ajax.call.get.lc', ':lc_id') }}";
            var route = url.replace(':lc_id', lc_id);

            $.get(route, function(lc) {

                $('#currency_id').val(lc.currency_id);
                $('#currency_code').html('(' + lc.currency_code + ')');
            });
        });

        $(document).keypress(".scanable", function(event) {

            if (event.which == '13') {

                event.preventDefault();
            }
        });

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

        $('.submit_button').on('click', function() {

            var value = $(this).val();
            $('#action').val(value);
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save_and_print').click();
                return false;
            } else if (e.shiftKey && e.which == 13) {

                $('#save').click();
                return false;
            }
        }

        $(document).keypress(".scanable", function(event) {

            if (event.which == '10' || event.which == '13') {

                event.preventDefault();
            }
        });

        // Automatic remove searching product is found signal
        setInterval(function() {

            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function() {

            $('#search_product').removeClass('is-valid');
        }, 1000);

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
            element: document.getElementById('receive_date'),
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
        $(document).on('input', '#lc_amount', function(e) {

            calculateAmount();
        });

        $(document).on('input', '#currency_rate', function(e) {

            calculateAmount();
        });

        function calculateAmount() {

            var lc_amount = $('#lc_amount').val() ? $('#lc_amount').val() : 0;
            var rate = $('#currency_rate').val() ? $('#currency_rate').val() : 0;

            var totalAmount = parseFloat(lc_amount) * parseFloat(rate);
            $('#total_amount').val(parseFloat(totalAmount).toFixed(2));

            $('#total_payable_amt').val(parseFloat(totalPayableAmount).toFixed(2));
        }
    </script>
    @if (auth()->user()->can('product_add'))
        <script>
            $('#add_product').on('click', function() {

                $.ajax({
                    url: "{{ route('common.ajax.call.add.quick.product.modal') }}",
                    type: 'get',
                    success: function(data) {

                        $('#quick_product_add_modal_contant').html(data);
                        $('#addQuickProductModal').modal('show');
                    }
                });
            });

            // Add product by ajax
            $(document).on('submit', '#add_quick_product_form', function(e) {
                e.preventDefault();

                $('.quick_loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {

                        $('#addQuickProductModal').modal('hide');
                        toastr.success('Successfully item is added.');

                        var product = data.item;
                        var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' :
                            product.name;

                        $('#search_product').val(name);
                        $('#e_item_name').val(name);
                        $('#e_product_id').val(product.id);
                        $('#e_variant_id').val('noid');
                        $('#e_unit').val(product.unit.name);
                        $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_unit_cost_exc_tax').val(parseFloat(product.product_cost).toFixed(2));
                        $('#e_discount').val(parseFloat(0).toFixed(2));
                        $('#e_discount_type').val(1);
                        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
                        $('#e_subtotal').val(parseFloat(product.product_cost).toFixed(2));
                    },
                    error: function(err) {

                        $('.quick_loading_button').hide();

                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        } else if (err.status == 500) {

                            toastr.error('Server Error. Please contact to the support team.');
                            return;
                        } else if (err.status == 403) {

                            toastr.error('Access Denied.');
                            return;
                        }

                        toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                        $.each(err.responseJSON.errors, function(key, error) {

                            $('.error_quick_' + key + '').html(error[0]);
                        });
                    }
                });
            });
        </script>
    @endif
@endpush
