@extends('layout.master')
@push('css')
@endpush
@section('title', 'Add Item - ')
@php
    $productSerial = new App\Utils\InvoiceVoucherRefIdUtil();
@endphp
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>@lang('menu.add_item')</h6>
            <x-back-button />
        </div>
        <div class="container-fluid p-0">
            <form id="add_product_form" action="{{ route('products.add.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" id="product_serial" value="{{ str_pad($productSerial->getLastId('products'), 4, '0', STR_PAD_LEFT) }}">
                <input type="hidden" id="code_prefix" value="{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}">
                <section class="p-15">
                    <div class="row g-1">
                        <div class="col-lg-8 order-2">
                            <div class="col-md-12">
                                <div class="form_element m-0 rounded">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.item_name') </b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input required type="text" name="name" class="form-control" id="name" data-next="code" placeholder="@lang('menu.item_name')" autofocus>
                                                        <span class="error error_name"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.item_code') <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Item code(SKU) must be unique. If you leave this field empty, it will be generated automatically." class="fas fa-info-circle tp"></i></b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="code" class="form-control" autocomplete="off" id="code" data-next="unit_id" placeholder="@lang('menu.item_code')">
                                                        <input type="hidden" name="auto_generated_code" id="auto_generated_code">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.unit')</b> <span class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <div class="input-group select-customer-input-group">
                                                            <select required name="unit_id" class="form-control select2" id="unit_id" data-next="barcode_type">
                                                                <option value="">@lang('menu.select_unit')</option>
                                                                @php
                                                                    $defaultUnit = json_decode($generalSettings->product, true)['default_unit_id'];
                                                                @endphp
                                                                @foreach ($units as $unit)
                                                                    <option {{ $defaultUnit == $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">
                                                                        {{ $unit->name . ' (' . $unit->code_name . ')' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button {{ !auth()->user()->can('units')? 'disabled_element': '' }}" id="addUnit"><i class="fas fa-plus-square input_i"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error error_unit_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.barcode_type')</b></label>
                                                    <div class="col-8">
                                                        <select class="form-control" name="barcode_type" id="barcode_type" data-next="category_id">
                                                            <option value="CODE128">@lang('menu.code') 128 (C128)</option>
                                                            <option value="CODE39">@lang('menu.code') 39 (C39)</option>
                                                            <option value="EAN13">EAN-13</option>
                                                            <option value="UPC">@lang('menu.upc')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.category')</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group select-customer-input-group">
                                                                <select class="form-control category select2" name="category_id" id="category_id" data-next="sub_category_id">
                                                                    <option value="">@lang('menu.select_category')</option>
                                                                    @foreach ($categories as $category)
                                                                        <option data-cate_name="{{ $category->name }}" value="{{ $category->id }}">
                                                                            {{ $category->name . '/' . $category->code }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" id="addCategory"><i class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                            <span class="error error_category_id"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1' && json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group select-customer-input-group">
                                                        <label class="col-4"><b>@lang('menu.sub_category')</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group select-customer-input-group">
                                                                <select name="sub_category_id" class="form-control select2" id="sub_category_id" data-next="brand_id">
                                                                    <option value="">@lang('menu.select_category_first')</option>
                                                                </select>

                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" id="addSubcategory"><i class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row mt-1">
                                            @if (json_decode($generalSettings->product, true)['is_enable_brands'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.brand') </b> </label>
                                                        <div class="col-8">
                                                            <div class="input-group select-customer-input-group">
                                                                <select name="brand_id" class="form-control select2" id="brand_id" data-next="alert_quantity">
                                                                    <option value="">@lang('menu.select_brand')</option>
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}">
                                                                            {{ $brand->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button {{ !auth()->user()->can('brand')? 'disabled_element': '' }}" id="addBrand"><i class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('menu.alert_quantity') </b> </label>
                                                    <div class="col-8">
                                                        <input type="number" step="any" name="alert_quantity" class="form-control " autocomplete="off" id="alert_quantity" data-next="warranty_id" value="0">
                                                        <span class="error error_alert_quantity"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            @if (json_decode($generalSettings->product, true)['is_enable_warranty'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.warranty') </b> </label>
                                                        <div class="col-8">
                                                            <div class="input-group select-customer-input-group">
                                                                <select name="warranty_id" class="form-control select2" id="warranty_id" data-next="purchase_type">
                                                                    <option value="">@lang('menu.select_warranty')</option>
                                                                    @foreach ($warranties as $warranty)
                                                                        <option value="{{ $warranty->id }}">
                                                                            {{ $warranty->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button {{ !auth()->user()->can('warranties')? 'disabled_element': '' }}" id="addWarranty"><i class="fas fa-plus-square input_i"></i><span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.purchase_type') </b> </label>
                                                    <div class="col-8">
                                                        <select name="purchase_type" id="purchase_type" class="form-control" data-next="product_condition">
                                                            <option value="1">@lang('menu.direct_purchase')</option>
                                                            <option value="2">@lang('menu.purchase_by_weight_scale')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('menu.condition') </b> </label>
                                                    <div class="col-8">
                                                        <select class="form-control" name="product_condition" id="product_condition" data-next="stock_type">
                                                            <option value="New">@lang('menu.new')</option>
                                                            <option value="Used">@lang('menu.used')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('menu.stock_type') </b> </label>
                                                    <div class="col-8">
                                                        <select class="form-control" name="stock_type" id="stock_type" data-next="product_cost">
                                                            <option value="1">@lang('menu.manageable_stock')</option>
                                                            <option value="0">@lang('menu.service_digital_item')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form_element rounded mb-0 mt-1">
                                    <div class="element-body">
                                        <div class="form_part">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.unit_cost')</b> </label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="product_cost" class="form-control fw-bold" autocomplete="off" id="product_cost" placeholder="0.00" data-next="tax_ac_id">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.unit_cost_inc_tax')</b></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" readonly name="product_cost_with_tax" class="form-control fw-bold" id="product_cost_with_tax" placeholder="0.00" data-next="tax_ac_id" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if (json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1')
                                                <div class="row mt-1">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.tax')</b></label>
                                                            <div class="col-8">
                                                                <select class="form-control" name="tax_ac_id" id="tax_ac_id" data-next="tax_type">
                                                                    <option data-tax_percent="0" value="">
                                                                        @lang('menu.no_tax')</option>
                                                                    @foreach ($taxAccounts as $tax)
                                                                        <option data-tax_percent="{{ $tax->tax_percent }}" value="{{ $tax->id }}">
                                                                            {{ $tax->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.tax_type') </b> </label>
                                                            <div class="col-8">
                                                                <select name="tax_type" class="form-control" id="tax_type" data-next="profit">
                                                                    <option value="1">@lang('menu.exclusive')</option>
                                                                    <option value="2">@lang('menu.inclusive')</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.profit_margin')(%)</b></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="profit" class="form-control fw-bold" id="profit" value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : '' }}" data-next="product_price" placeholder="0.00" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.price_exc_tax')</b></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="product_price" class="form-control fw-bold" id="product_price" data-next="is_variant" placeholder="0.00" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.has_variant') </b> </label>
                                                        <div class="col-8">
                                                            <select name="is_variant" class="form-control" id="is_variant" data-next="variants">
                                                                <option value="0">@lang('menu.no')</option>
                                                                <option value="1">@lang('menu.yes')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="dynamic_variant_create_area display-none">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="add_more_btn">
                                                                <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-end" href="#">@lang('menu.add_more')</a>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="table-responsive mt-1">
                                                                <table class="table modal-table table-sm">
                                                                    <thead>
                                                                        <tr class="text-center bg-primary variant_header">
                                                                            <th class="text-white text-start">@lang('menu.select_variant')</th>
                                                                            <th class="text-white text-start">@lang('menu.variant_code') <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Variant code(SKU) must be unique." class="fas fa-info-circle tp"></i></th>
                                                                            <th colspan="2" class="text-white text-start">@lang('menu.default_cost')</th>
                                                                            <th class="text-white text-start">@lang('menu.profit')(%)</th>
                                                                            <th class="text-white text-start">@lang('menu.default_price') (@lang('menu.exc_tax'))</th>
                                                                            <th class="text-white text-start">@lang('menu.variant_image')</th>
                                                                            <th><i class="fas fa-trash-alt text-white"></i>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody class="dynamic_variant_body">
                                                                        <tr>
                                                                            <td class="text-start">
                                                                                <select class="form-control form-control" name="" id="variants"></select>
                                                                                <input type="text" name="variant_combinations[]" id="variant_combination" class="form-control reqireable fw-bold" placeholder="Variant Combination">
                                                                            </td>

                                                                            <td class="text-start">
                                                                                <input type="text" name="variant_codes[]" id="variant_code" class="form-control reqireable fw-bold" placeholder="Variant Code">
                                                                            </td>

                                                                            <td class="text-start">
                                                                                <input type="number" name="variant_costings[]" step="any" class="form-control requireable fw-bold" placeholder="Cost" id="variant_costing">
                                                                            </td>

                                                                            <td class="text-start">
                                                                                <input type="number" step="any" name="variant_costings_with_tax[]" class="form-control requireable fw-bold" placeholder="Cost inc.tax" id="variant_costing_with_tax">
                                                                            </td>

                                                                            <td class="text-start">
                                                                                <input type="number" step="any" name="variant_profits[]" class="form-control requireable fw-bold" placeholder="Profit" value="0.00" id="variant_profit">
                                                                            </td>

                                                                            <td class="text-start">
                                                                                <input type="number" step="any" name="variant_prices_exc_tax[]" class="form-control requireable fw-bold" placeholder="@lang('menu.price_include_tax')" id="variant_price_exc_tax">
                                                                            </td>

                                                                            <td class="text-start">
                                                                                <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                                                                            </td>

                                                                            <td class="text-start">
                                                                                <a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
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

                            <div class="col-md-12">
                                <div class="form_element rounded mb-0 mt-1">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.type')</b></label>
                                                    <div class="col-8">
                                                        <select name="type" class="form-control" id="type" data-next="is_show_in_ecom">
                                                            <option value="1">@lang('menu.general')</option>
                                                            <option value="2">@lang('menu.combo')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('menu.displayed_in_ecom') </b> </label>
                                                    <div class="col-8">
                                                        <select name="is_show_in_ecom" class="form-control" id="is_show_in_ecom" data-next="weight">
                                                            <option value="0">@lang('menu.no')</option>
                                                            <option value="1">@lang('menu.yes')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('menu.weight') </b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="weight" class="form-control" id="weight" data-next="is_show_emi_on_pos" placeholder="Weight">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('menu.enable_imei_or_sl_no') </b> </label>
                                                    <div class="col-8">
                                                        <select name="is_show_emi_on_pos" class="form-control" id="is_show_emi_on_pos" data-next="is_not_for_sale">
                                                            <option value="0">@lang('menu.no')</option>
                                                            <option value="1">@lang('menu.yes')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.thumbnail_photo') </b> </label>
                                                    <div class="col-8">
                                                        <input type="file" name="photo" class="form-control" id="photo">
                                                        <span class="error error_photo"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('menu.show_not_for_sale') </b> </label>
                                                    <div class="col-8">
                                                        <select name="is_not_for_sale" class="form-control" id="is_not_for_sale" data-next="save_and_new">
                                                            <option value="0">@lang('menu.no')</option>
                                                            <option value="1">@lang('menu.yes')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form_element rounded mb-0 mt-1">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-2"> <b>@lang('menu.description') </b> </label>
                                                    <div class="col-10">
                                                        <textarea name="product_details" class="ckEditor form-control" cols="50" rows="5" tabindex="4" style="display: none; width: 653px; height: 160px;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-2"> <b>@lang('menu.image') <i data-bs-toggle="tooltip" data-bs-placement="top" title="This photo will be shown in e-commerce. You can upload multiple file. Per photo max size 2MB." class="fas fa-info-circle tp"></i> </b> </label>
                                                    <div class="col-10">
                                                        <input type="file" name="image[]" class="form-control" id="image" accept="image" multiple>
                                                        <span class="error error_image"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 d-flex justify-content-end mt-1">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                    <button type="button" name="action" value="save_and_new" class="btn btn-success product_submit_button me-2" id="save_and_new">@lang('menu.save_and_add_another')</button>
                                    <button type="button" name="action" value="save" class="btn btn-success product_submit_button" id="save">@lang('menu.save')</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 order-lg-2">
                            <div class="card">
                                <div class="card-header">
                                    <h6>@lang('menu.all_item')</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" id="data_list">
                                        <table class="display table-hover data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>@lang('menu.item')</th>
                                                    <th>@lang('menu.unit_cost')</th>
                                                    <th>@lang('menu.unit_price')</th>
                                                    <th>@lang('menu.actions')</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <x-shortcut-key-bar.shortcut-key-bar :items="[['key' => 'Ctrl + Enter', 'value' => __('menu.save_and_add_another')], ['key' => 'Shift + Enter', 'value' => __('menu.save')], ['key' => 'Alt + U', 'value' => __('menu.add_unit')], ['key' => 'Alt + N', 'value' => __('menu.add_category')], ['key' => 'Alt + M', 'value' => __('menu.add_sub_category')], ['key' => 'Alt + B', 'value' => __('menu.add_brand')], ['key' => 'Alt + W', 'value' => __('menu.add_warranty')]]">
    </x-shortcut-key-bar.shortcut-key-bar>

    @include('inventories.products.partials.all-modals')

    <div class="modal fade" id="unitAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <div class="modal fade" id="brandAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <div class="modal fade" id="warrantyAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <div class="modal fade" id="categoryAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <div class="modal fade" id="subcategoryAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

@endsection


@push('scripts')
    <script>
        $('.select2').select2();
        var table = $('.data_tbl').DataTable({
            processing: true,
            serverSide: true,
            searchable: true,
            aSorting: [
                [0, 'desc']
            ],
            "lengthMenu": [25, 100, 500, 1000, 2000],
            ajax: "{{ route('products.add.view') }}",
            columns: [{
                    data: 'name',
                    name: 'products.name'
                },
                {
                    data: 'product_cost',
                    name: 'products.product_cost',
                    className: 'fw-bold'
                },
                {
                    data: 'product_price',
                    name: 'products.product_price',
                    className: 'fw-bold'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
        });

        // Set parent category in parent category form field
        $('.combo_price').hide();
        $('.combo_pro_table_field').hide();

        function costCalculate() {

            var tax_percent = $('#tax_ac_id').find('option:selected').data('tax_percent');
            var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
            var tax_type = $('#tax_type').val();
            var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent);

            if (tax_type == 2) {

                var __tax_percent = 100 + parseFloat(tax_percent);
                var calc_tax = parseFloat(product_cost) / parseFloat(__tax_percent) * 100;
                calc_product_cost_tax = parseFloat(product_cost) - parseFloat(calc_tax);
            }

            var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
            $('#product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
            var profit = $('#profit').val() ? $('#profit').val() : 0;

            if (parseFloat(profit) > 0) {

                var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
                var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
                $('#product_price').val(parseFloat(product_price).toFixed(2));
            }

            // calc package product profit
            var netTotalComboPrice = $('#total_combo_price').val() ? $('#total_combo_price').val() : 0;
            var calcTotalComboPrice = parseFloat(netTotalComboPrice) / 100 * parseFloat(profit) + parseFloat(
                netTotalComboPrice);
            $('#combo_price').val(parseFloat(calcTotalComboPrice).toFixed(2));
        }

        $(document).on('input', '#product_cost', function() {

            costCalculate();
        });

        $(document).on('input', '#product_price', function() {

            var selling_price = $(this).val() ? $(this).val() : 0;
            var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
            var profitAmount = parseFloat(selling_price) - parseFloat(product_cost);
            var __cost = parseFloat(product_cost) > 0 ? parseFloat(product_cost) : parseFloat(profitAmount);
            var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
            var __calcProfit = calcProfit ? calcProfit : 0;
            $('#profit').val(parseFloat(__calcProfit).toFixed(2));
        });

        $('#tax_ac_id').on('change', function() {

            costCalculate();
        });

        $('#tax_type').on('change', function() {

            costCalculate();
        });

        $(document).on('input', '#profit', function() {

            costCalculate();
        });

        // Variant all functionality
        var variantsWithChild = '';

        function getAllVariant() {
            $.ajax({
                url: "{{ route('products.add.get.all.from.variant') }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(variants) {
                    variantsWithChild = variants;
                    $('#variants').append('<option value="">Create Combination</option>');
                    $.each(variants, function(key, val) {
                        $('#variants').append('<option value="' + val.id + '">' + val.bulk_variant_name + '</option>');
                    });
                }
            });
        }
        getAllVariant();

        var variant_row_index = 0;
        $(document).on('change', '#variants', function() {
            var id = $(this).val();
            var parentTableRow = $(this).closest('tr');
            variant_row_index = parentTableRow.index();

            $('.modal_variant_child').empty();

            var html = '';

            var variant = variantsWithChild.filter(function(variant) {
                return variant.id == id;
            });

            $.each(variant[0].bulk_variant_child, function(key, child) {
                html += '<li class="modal_variant_child_list">';
                html += '<a class="select_variant_child" data-child="' + child.child_name + '" href="#">' + child.child_name + '</a>';
                html += '</li>';
            });

            $('.modal_variant_child').html(html);
            $('#VairantChildModal').modal('show');
            $(this).val('');
        });

        $(document).on('click', '.select_variant_child', function(e) {

            e.preventDefault();
            var child = $(this).data('child');
            var parent_tr = $('.dynamic_variant_body tr:nth-child(' + (variant_row_index + 1) + ')');
            var child_value = parent_tr.find('#variant_combination').val();
            var filter = child_value == '' ? '' : ',';
            var variant_combination = parent_tr.find('#variant_combination').val(child_value + filter + child);
            $('#VairantChildModal').modal('hide');
        });

        $(document).on('input', '#variant_costing', function() {

            var parentTableRow = $(this).closest('tr');
            variant_row_index = parentTableRow.index();
            calculateVariantAmount(variant_row_index);
        });

        $(document).on('input', '#variant_profit', function() {

            var parentTableRow = $(this).closest('tr');
            variant_row_index = parentTableRow.index();
            calculateVariantAmount(variant_row_index);
        });

        function calculateVariantAmount(variant_row_index) {

            var parent_tr = $('.dynamic_variant_body tr:nth-child(' + (variant_row_index + 1) + ')');
            var tax = $('#tax_ac_id').find('option:selected').data('tax_percent');
            var variant_costing = parent_tr.find('#variant_costing');
            var variant_costing_with_tax = parent_tr.find('#variant_costing_with_tax');
            var variant_profit = parent_tr.find('#variant_profit').val() ? parent_tr.find('#variant_profit').val() : 0.00;
            var variant_price_exc_tax = parent_tr.find('#variant_price_exc_tax');

            var tax_rate = parseFloat(variant_costing.val()) / 100 * tax;
            var cost_with_tax = parseFloat(variant_costing.val()) + tax_rate;
            variant_costing_with_tax.val(parseFloat(cost_with_tax).toFixed(2));

            var profit = parseFloat(variant_costing.val()) / 100 * parseFloat(variant_profit) + parseFloat(variant_costing.val());
            variant_price_exc_tax.val(parseFloat(profit).toFixed(2));
        }

        var variant_code_sequel = 0;
        // Select Variant and show variant creation area
        $(document).on('change', '#is_variant', function() {

            var product_cost = $('#product_cost').val();
            var product_cost_with_tax = $('#product_cost_with_tax').val();
            var profit = $('#profit').val();
            var product_price = $('#product_price').val();

            if ($(this).val() == 1 && (product_cost == '' || product_price == '')) {

                $(this).val(0);
                alert('After creating the variant, product cost and product price field must not be empty.');
                return;
            }

            var code = $('#code').val();
            var auto_generated_code = $('#auto_generated_code').val();
            var variant_code = code ? code + '-' + (++variant_code_sequel) : auto_generated_code + '-' + (++variant_code_sequel);

            $('#variant_code').val(variant_code);
            $('#variant_costing').val(parseFloat(product_cost).toFixed(2));
            $('#variant_costing_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
            $('#variant_price_exc_tax').val(parseFloat(product_price).toFixed(2));
            $('#variant_profit').val(parseFloat(profit).toFixed(2));

            if ($(this).val() == 1) {

                $('.dynamic_variant_create_area').show(500);
                $('#variant_combination').prop('required', true);
                $('#variant_costing').prop('required', true);
                $('#variant_costing_with_tax').prop('required', true);
                $('#variant_profit').prop('required', true);
                $('#variant_price_exc_tax').prop('required', true);
            } else {

                $('.dynamic_variant_create_area').hide(500);
                $('#variant_combination').prop('required', false);
                $('#variant_costing').prop('required', false);
                $('#variant_costing_with_tax').prop('required', false);
                $('#variant_profit').prop('required', false);
                $('#variant_price_exc_tax').prop('required', false);
            }
        });

        // Get default profit
        var defaultProfit =
            "{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}";

        $(document).on('click', '#add_more_variant_btn', function(e) {
            e.preventDefault();

            // var variant_code = code ? code + '-' + (++variant_code_sequel) : auto_generated_code + '-' + (++variant_code_sequel);

            var product_cost = $('#product_cost').val();
            var product_cost_with_tax = $('#product_cost_with_tax').val();
            var profit = $('#profit').val();
            var product_price = $('#product_price').val();
            var html = '';
            html += '<tr id="more_new_variant">';
            html += '<td>';
            html += '<select class="form-control form-select" name="" id="variants">';
            html += '<option value="">Create Combination</option>';

            $.each(variantsWithChild, function(key, val) {

                html += '<option value="' + val.id + '">' + val.bulk_variant_name + '</option>';
            });

            html += '</select>';
            html += '<input type="text" name="variant_combinations[]" id="variant_combination" class="form-control fw-bold" placeholder="Variant Combination" required>';
            html += '</td>';
            html += '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control fw-bold" placeholder="Variant Code">';
            html += '</td>';
            html += '<td>';
            html += '<input required type="number" step="any" name="variant_costings[]" class="form-control fw-bold" placeholder="Cost" id="variant_costing" value="' + parseFloat(product_cost).toFixed(2) + '">';
            html += '</td>';
            html += '<td>';
            html += '<input required type="number" step="any" name="variant_costings_with_tax[]" class="form-control fw-bold" placeholder="Cost inc.tax" id="variant_costing_with_tax" value="' + parseFloat(product_cost_with_tax).toFixed(2) + '">';
            html += '</td>';
            html += '<td>';
            html += '<input required type="number" step="any" name="variant_profits[]" class="form-control fw-bold" placeholder="Profit" value="' +
                parseFloat(profit).toFixed(2) + '" id="variant_profit">';
            html += '</td>';
            html += '<td>';
            html += '<input required type="number" step="any" name="variant_prices_exc_tax[]" class="form-control fw-bold" placeholder="Price inc.tax" id="variant_price_exc_tax" value="' + parseFloat(product_price).toFixed(2) + '">';
            html += '</td>';
            html += '<td>';
            html += '<input type="file" name="variant_image[]" class="form-control" id="variant_image">';
            html += '</td>';
            html += '<td><a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a></td>';
            html += '</tr>';
            $('.dynamic_variant_body').prepend(html);

            regenerateVariantCode();
        });
        // Variant all functionality end

        // This functionality of Count prackage product all prices
        function CountTotalComboProductPrice(allQuantities, allUnitPrices) {

            var allUnitPriceContainer = [];
            allUnitPrices.forEach(function(price) {

                allUnitPriceContainer.push(price.value);
            });

            var countedPrice = [];
            var i = 0;
            allQuantities.forEach(function(quantity) {

                countedPrice.push(parseFloat(parseFloat(quantity.value) * parseFloat(allUnitPriceContainer[i])));
                i++;
            });

            var totalPrice = 0;
            countedPrice.forEach(function(price) {

                totalPrice += parseFloat(price);
            });

            return parseFloat(parseFloat(totalPrice));
        }

        function get_form_part(type) {

            var url = "{{ route('products.get.form.part', ':type') }}";
            var route = url.replace(':type', type)
            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(html) {

                    $('.form_part').html(html);
                }
            });
        }

        function regenerateVariantCode() {

            var code = $('#code').val();
            var auto_generated_code = $('#auto_generated_code').val();

            var variantCodes = document.querySelectorAll('input[name="variant_codes[]"]');
            var variantCodesArray = Array.from(variantCodes);
            var reversed = variantCodesArray.reverse();

            var length = variantCodesArray.length;
            var i = length;
            for (var index = length - 1; index >= 0; index--) {

                var variant_code = code ? code + '-' + (i) : auto_generated_code + '-' + (i);
                reversed[index].value = variant_code;
                i--;
            }
        }

        // call jquery method
        var action_direction = '';
        $(document).ready(function() {

            // Select product and show spacific product creation fields or area
            $('#type').on('change', function() {

                var value = $(this).val();
                get_form_part(value);
            });

            // Search product for creating combo
            $(document).on('input', '#search_product', function(e) {

                $('.variant_list_area').empty();
                $('.select_area').hide();
                var productCode = $(this).val();

                if ((productCode === "")) {

                    $('.variant_list_area').empty();
                    $('.select_area').hide();
                    return;
                }

                $.ajax({
                    url: "{{ url('product/search/product') }}" + "/" + productCode,
                    dataType: 'json',
                    success: function(product) {

                        if (!$.isEmptyObject(product)) {

                            $('#search_product').addClass('is-valid');
                        }

                        if (!$.isEmptyObject(product.product) || !$.isEmptyObject(product
                                .variant_product)) {

                            $('#search_product').addClass('is-valid');

                            if (!$.isEmptyObject(product.product)) {

                                var product = product.product;
                                if (product.variants.length == 0) {

                                    $('.select_area').hide();
                                    $('#search_product').val('');
                                    product_ids = document.querySelectorAll('#product_id');
                                    var sameProduct = 0;
                                    product_ids.forEach(function(input) {

                                        if (input.value == product.id) {

                                            sameProduct += 1;
                                            var className = input.getAttribute('class');
                                            // get closest table row for increasing qty and re calculate product amount
                                            var closestTr = $('.' + className).closest('tr');
                                            // update same product qty
                                            var presentQty = closestTr.find('#combo_quantity').val();
                                            var updateQty = parseFloat(presentQty) + 1;
                                            closestTr.find('#combo_quantity').val(updateQty);

                                            // update unit cost with discount
                                            var unitPriceIncTax = closestTr.find(
                                                '#unit_price_inc_tax').val();
                                            // update subtotal
                                            var calcSubTotal = parseFloat(unitPriceIncTax) * parseFloat(updateQty);
                                            var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));
                                            calculateTotalAmount();
                                            return;
                                        }
                                    });

                                    if (sameProduct == 0) {

                                        var tax_percent = product.tax_ac_id != null ? product.tax.tax_percent : 0;
                                        var tax_amount = parseFloat(product.tax != null ? product.product_price / 100 * product.tax.tax_percent : 0);
                                        var tr = '';
                                        tr += '<tr class="text-center">';
                                        tr += '<td>';
                                        tr += '<span class="product_name">' + product.name + '</span><br>';
                                        tr += '<span class="product_code">(' + product.product_code + ')</span><br>';
                                        tr += '<span class="product_variant"></span>';
                                        tr += '<input value="' + product.id + '" type="hidden" class="productId-' + product.id + '" id="product_id" name="product_ids[]">';
                                        tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                        tr += '</td>';

                                        tr += '<td>';
                                        tr += '<input type="number" step="any" value="1" required name="combo_quantities[]" class="form-control form-control-sm" id="combo_quantity">';
                                        tr += '</td>';

                                        var unitPriceIncTax = product.product_price + tax_amount;
                                        tr += '<td>';
                                        tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(2) + '" required name="unit_prices_inc_tax[]" type="text" class="form-control form-control-sm" id="unit_price_inc_tax">';
                                        tr += '</td>';

                                        tr += '<td>';
                                        tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(2) + '" required name="subtotals[]" type="text" class="form-control form-control-sm" id="subtotal">';
                                        tr += '</td>';

                                        tr += '<td class="text-right">';
                                        tr += '<a href="#" id="remove_combo_product_btn" class="btn btn-sm btn-danger">-</a>';
                                        tr += '</td>';

                                        tr += '</tr>';
                                        $('#combo_products').append(tr);
                                        calculateTotalAmount();
                                    }
                                } else {

                                    var li = "";
                                    var tax_percent = product.tax_ac_id != null ? product.tax
                                        .tax_percent : 0.00;

                                    $.each(product.variants, function(key, variant) {

                                        var tax_amount = parseFloat(product.tax != null ? variant.variant_price / 100 * product.tax.tax_percent : 0.00);
                                        var variantPriceIncTax = variant.variant_price + tax_amount;
                                        li += '<li>';
                                        li += '<a class="select_variant_product" data-p_id="' + product.id + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_code="' + variant.product_code + '" data-v_price="' + parseFloat(variantPriceIncTax).toFixed(2) + '" data-v_name="' + variant.variant_name + '" href="#">' + product.name + ' [' + variant_code_sequel.variant_name + ']' + '</a>';
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
                                var tax_percent = variant_product.product.tax_ac_id != null ? variant_product.product.tax.percent : 0;
                                var tax_rate = parseFloat(variant_product.product.tax != null ? variant_product.variant_cost / 100 * tax_percent : 0);
                                var variant_ids = document.querySelectorAll('#variant_id');
                                var sameVariant = 0;

                                variant_ids.forEach(function(input) {

                                    if (input.value != 'noid') {

                                        if (input.value == variant_product.id) {

                                            sameVariant += 1;
                                            var className = input.getAttribute('class');
                                            // get closest table row for increasing qty and re calculate product amount
                                            var closestTr = $('.' + className).closest('tr');
                                            // update same product qty
                                            var presentQty = closestTr.find('#combo_quantity').val();
                                            var updateQty = parseFloat(presentQty) + 1;
                                            closestTr.find('#combo_quantity').val(updateQty);

                                            // update unit cost with discount
                                            var unitPriceIncTax = closestTr.find('#unit_price_inc_tax').val();
                                            // update subtotal
                                            var calcSubTotal = parseFloat(unitPriceIncTax) * parseFloat(updateQty);
                                            var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));
                                            calculateTotalAmount();
                                            return;
                                        }
                                    }
                                });

                                if (sameVariant == 0) {

                                    var tax_percent = variant_product.product.tax_ac_id != null ? variant_product.product.tax.tax_percent : 0;
                                    var tax_amount = parseFloat(variant_product.product.tax != null ? variant_product.variant_price / 100 * variant_product.product.tax.tax_percent : 0);
                                    var tr = '';
                                    tr += '<tr class="text-center">';
                                    tr += '<td>';
                                    tr += '<span class="product_name">' + variant_product.product.name + '</span><br>';
                                    tr += '<span class="product_code">(' + variant_product.variant_code + ')</span><br>';
                                    tr += '<span class="product_variant">(' + variant_product.variant_name + ')</span>';
                                    tr += '<input value="' + variant_product.product.id + '" type="hidden" class="productId-' + variant_product.product.id + '" id="product_id" name="product_ids[]">';
                                    tr += '<input value="' + variant_product.id + '" type="hidden" class="variantId-' + variant_product.id + '" id="variant_id" name="variant_ids[]">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="1.00" required name="combo_quantities[]" type="text" class="form-control form-control-sm" id="combo_quantity">';
                                    tr += '</td>';

                                    var unitPriceIncTax = variant_product.variant_price + tax_amount;
                                    tr += '<td>';
                                    tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(2) + '" required name="unit_prices_inc_tax[]" type="text" class="form-control form-control-sm" id="unit_price_inc_tax">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input readonly value="' + parseFloat(unitPriceIncTax).toFixed(2) + '" type="text" name="subtotal[]" id="subtotal" class="form-control form-control-sm">';
                                    tr += '</td>';

                                    tr += '<td class="text-right">';
                                    tr += '<a href="#" id="remove_combo_product_btn" class="btn btn-sm btn-danger">-</a>';
                                    tr += '</td>';

                                    tr += '</tr>';
                                    $('#combo_products').append(tr);
                                    calculateTotalAmount();
                                }
                            }
                        } else {

                            $('#search_product').addClass('is-invalid');
                        }
                    }
                });
            });

            // Select variant product for creating combo
            $(document).on('click', '.select_variant_product', function(e) {
                e.preventDefault();

                $('#selectVairantModal').modal('hide');
                var product_id = $(this).data('p_id');
                var product_name = $(this).data('p_name');
                var variant_id = $(this).data('v_id');
                var variant_name = $(this).data('v_name');
                var variant_code = $(this).data('v_code');
                var variant_price_inc_tax = $(this).data('v_price');
                var variant_ids = document.querySelectorAll('#variant_id');
                var sameVariant = 0;

                variant_ids.forEach(function(input) {

                    if (input.value != 'noid') {

                        if (input.value == variant_id) {

                            sameVariant += 1;
                            var className = input.getAttribute('class');
                            var className = input.getAttribute('class');
                            // get closest table row for increasing qty and re calculate product amount
                            var closestTr = $('.' + className).closest('tr');
                            // update same product qty
                            var presentQty = closestTr.find('#combo_quantity').val();
                            var updateQty = parseFloat(presentQty) + 1;
                            closestTr.find('#combo_quantity').val(updateQty);

                            // update unit cost with discount
                            var unitPriceIncTax = closestTr.find('#unit_price_inc_tax').val();
                            // update subtotal
                            var calcSubTotal = parseFloat(unitPriceIncTax) * parseFloat(updateQty);
                            var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));
                            calculateTotalAmount();
                            return;
                        }
                    }
                });

                if (sameVariant == 0) {

                    var tr = '';
                    tr += '<tr class="text-center">';
                    tr += '<td>';
                    tr += '<span class="product_name">' + product_name + '</span><br>';
                    tr += '<span class="product_code">(' + variant_code + ')</span><br>';
                    tr += '<span class="product_variant">(' + variant_name + ')</span>';
                    tr += '<input value="' + product_id + '" type="hidden" class="productId-' + product_id + '" id="product_id" name="product_ids[]">';
                    tr += '<input value="' + variant_id + '" type="hidden" class="variantId-' + variant_id + '" id="variant_id" name="variant_ids[]">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input type="number" step="any" value="1.00" required name="combo_quantities[]" class="form-control form-control-sm" id="combo_quantity">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input readonly value="' + variant_price_inc_tax + '" required name="unit_prices_inc_tax[]" type="number" step="any" class="form-control form-control-sm" id="unit_price_inc_tax">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input readonly type="number" step="any" value="' + variant_price_inc_tax + '" required name="subtotals[]" class="form-control form-control-sm" id="subtotal">';
                    tr += '</td>';

                    tr += '<td class="text-right">';
                    tr += '<a href="#" id="remove_combo_product_btn" class="btn btn-sm btn-danger mt-1">-</a>';
                    tr += '</td>';

                    tr += '</tr>';
                    $('#combo_products').append(tr);
                    calculateTotalAmount();
                }
            });

            function calculateTotalAmount() {

                var subtotals = document.querySelectorAll('#subtotal');
                var netTotalAmount = 0;

                subtotals.forEach(function(subtotal) {

                    netTotalAmount += parseFloat(subtotal.value);
                });

                $('.span_total_combo_price').html(parseFloat(netTotalAmount).toFixed(2));
                $('#total_combo_price').val(parseFloat(netTotalAmount).toFixed(2));
                var profit = $('#profit').val();
                var combo_price_exc_tax = parseFloat(netTotalAmount) / 100 * parseFloat(profit) + parseFloat(
                    netTotalAmount);
                $('#combo_price').val(parseFloat(combo_price_exc_tax).toFixed(2));
            }

            // Combo product total price increase or dicrease by quantity
            $(document).on('input', '#combo_quantity', function() {

                var qty = $(this).val() ? $(this).val() : 0;

                var tr = $(this).closest('tr');

                //Update subtotal
                var unitPriceIncTax = $(this).closest('tr').find('#unit_price_inc_tax').val();
                var calcSubtotal = parseFloat(unitPriceIncTax) * parseFloat(qty);
                var subtotal = tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();
            });

            $(document).on('click', '#remove_combo_product_btn', function(e) {

                e.preventDefault();
                $(this).closest('tr').remove();
                calculateTotalAmount();
            });

            // Dispose Select area
            $(document).on('click', '.remove_select_area_btn', function(e) {

                e.preventDefault();
                $('.select_area').hide();
            });

            // Romove variant table row
            $(document).on('click', '#variant_remove_btn', function(e) {

                e.preventDefault();
                $(this).closest('tr').remove();
                regenerateVariantCode();

            });

            // Setup ajax for csrf token.
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // set sub category in form field
            $('#category_id').on('change', function() {

                var category_id = $(this).val();

                $.get("{{ url('common/ajax/call/category/subcategories/') }}" + "/" + category_id,
                    function(subCategories) {

                        $('#sub_category_id').empty();
                        $('#sub_category_id').append('<option value="">Select Sub-Category</option>');

                        $.each(subCategories, function(key, val) {

                            $('#sub_category_id').append('<option data-sub_cate_name="' + val.name + '" value="' + val.id + '">' + val.name + '</option>');
                        });
                    });

                generateProductCode();
            });

            $('#sub_category_id').on('change', function() {

                generateProductCode();
            });

            $('#name').on('input', function() {

                generateProductCode();
            });

            $(document).on('click keypress focus blur change', '.form-control', function(event) {

                $('.product_submit_button').prop('type', 'button');
            });

            $(document).on('click', '.product_submit_button', function() {

                action_direction = $(this).val();
            });

            var isAllowSubmit = true;
            $(document).on('click', '.product_submit_button', function() {

                var action_direction = $(this).val();

                if (isAllowSubmit) {

                    $(this).prop('type', 'submit');
                } else {

                    $(this).prop('type', 'button');
                }
            });

            document.onkeyup = function() {
                var e = e || window.event; // for IE to cover IEs window event-object

                if (e.ctrlKey && e.which == 13) {

                    $('#save_and_new').click();
                    return false;
                } else if (e.shiftKey && e.which == 13) {

                    $('#save').click();
                    return false;
                } else if (e.altKey && e.which == 85) {

                    $('#addUnit').click();
                    return false;
                } else if (e.altKey && e.which == 78) {

                    $('#addCategory').click();
                    return false;
                } else if (e.altKey && e.which == 77) {

                    $('#addSubcategory').click();
                    return false;
                } else if (e.altKey && e.which == 66) {

                    $('#addBrand').click();
                    return false;
                } else if (e.altKey && e.which == 87) {

                    $('#addWarranty').click();
                    return false;
                }
            }

            // Add product by ajax
            $('#add_product_form').on('submit', function(e) {
                e.preventDefault();

                $('.loading_button').show();
                var url = $(this).attr('action');

                isAjaxIn = false;
                isAllowSubmit = false;

                $.ajax({
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

                        @php
                            $isEcommerce = "$addons->e_commerce";
                        @endphp

                        if ($.isEmptyObject(data.errorMsg)) {

                            toastr.success('Item create Successfully');

                            variant_code_sequel = 0;
                            if (action_direction == 'save') {

                                window.location = "{{ route('products.all.product') }}";
                            } else {

                                $('#add_product_form')[0].reset();
                                get_form_part(1);
                                $('#profit').val(parseFloat(defaultProfit).toFixed(2));
                                document.getElementById('name').focus();
                                getLastid();
                                table.ajax.reload();

                                $("#unit_id").select2("destroy");
                                $("#unit_id").select2();
                                $("#category_id").select2("destroy");
                                $("#category_id").select2();
                                $("#sub_category_id").select2("destroy");
                                $("#sub_category_id").select2();
                                $("#brand_id").select2("destroy");
                                $("#brand_id").select2();
                                $("#warranty_id").select2("destroy");
                                $("#warranty_id").select2();
                            }
                        } else {

                            toastr.error(data.errorMsg);
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

                        toastr.error('Please check again all form fields.',
                            'Some thing went wrong.');

                        $.each(err.responseJSON.errors, function(key, error) {

                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });

                if (isAjaxIn == false) {

                    isAllowSubmit = true;
                }
            });

            // Automatic remove searching product not found signal
            setInterval(function() {

                $('#search_product').removeClass('is-invalid');
            }, 350);

            // Automatic remove searching product is found signal
            setInterval(function() {

                $('#search_product').removeClass('is-valid');
            }, 1000);
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                e.preventDefault();

                $('#' + nextId).focus().select();
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

                if ($(this).attr('id') == 'is_variant' && $('#is_variant').val() == 0) {

                    $('#type').focus().select();
                }

                $('#' + nextId).focus().select();
            }
        });


        $(document).on('click', '#addCategory', function(e) {
            e.preventDefault();

            var url = "{{ route('product.categories.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#categoryAddOrEditModal').html(data);
                    $('#categoryAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#category_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#addSubcategory', function(e) {
            e.preventDefault();

            var categoryId = $('#category_id').val();
            if (categoryId == '') {

                toastr.error('Please select category first.');
                return;
            }

            var url = "{{ route('product.subcategories.create', ':category_id') }}";
            var route = url.replace(':category_id', categoryId);
            $.ajax({
                url: route,
                type: 'get',
                success: function(data) {

                    $('#subcategoryAddOrEditModal').html(data);
                    $('#subcategoryAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#subcategory_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#addUnit', function(e) {
            e.preventDefault();

            var url = "{{ route('products.units.create', 0) }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#unitAddOrEditModal').html(data);
                    $('#unitAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#unit_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#addBrand', function(e) {
            e.preventDefault();

            var url = "{{ route('product.brands.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#brandAddOrEditModal').html(data);
                    $('#brandAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#brand_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#addWarranty', function(e) {
            e.preventDefault();

            var url = "{{ route('product.warranties.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#warrantyAddOrEditModal').html(data);
                    $('#warrantyAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#warranty_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        function generateProductCode() {

            var product_serial = $('#product_serial').val();
            var code_prefix = $('#code_prefix').val();
            var category_name = $('#category_id').find('option:selected').data('cate_name');
            var subcategory_name = $('#sub_category_id').find('option:selected').data('sub_cate_name');
            var product_name = $('#name').val();

            if (code_prefix) {

                var productCode = product_serial + product_serial;
                $('#auto_generated_code').val(productCode);
            } else {

                var categoryFirstLetter = category_name ? category_name[0] : '';
                var subcategoryFirstLetter = subcategory_name ? subcategory_name[0] : '';
                var productNameFirstLetter = product_name ? product_name[0] : '';
                var productCode = categoryFirstLetter + subcategoryFirstLetter + productNameFirstLetter + product_serial;
                $('#auto_generated_code').val(productCode);
            }
        }

        function getLastid() {

            $.get("{{ route('common.ajax.call.get.last.id', ['products', 4]) }}", function(productSerial) {

                $('#product_serial').val(productSerial);
            });
        }
    </script>
@endpush
