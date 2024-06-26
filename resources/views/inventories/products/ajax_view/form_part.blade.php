@if ($type == 1)
    <div class="row mt-1">
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
                        <select class="form-control form-select" name="tax_ac_id" id="tax_ac_id" data-next="tax_type">
                            <option data-tax_percent="0" value="">@lang('menu.no_tax')</option>
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
                        <select name="tax_type" class="form-control form-select" id="tax_type" data-next="profit">
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
                    <select name="is_variant" class="form-control form-select" id="is_variant" data-next="variants">
                        <option value="0">@lang('menu.no')</option>
                        <option value="1">@lang('menu.yes')</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-1">
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
                                    <th class="text-white text-start">@lang('menu.variant_code') <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Variant code(SKU) must be unique." class="fas fa-info-circle tp"></i>
                                    </th>
                                    <th colspan="2" class="text-white text-start">@lang('menu.default_cost')</th>
                                    <th class="text-white text-start">@lang('menu.profit')(%)</th>
                                    <th class="text-white text-start">@lang('menu.default_price') (@lang('menu.exc_tax'))</th>
                                    <th class="text-white text-start">@lang('menu.variant_image')</th>
                                    <th><i class="fas fa-trash-alt text-white"></i></th>
                                </tr>
                            </thead>

                            <tbody class="dynamic_variant_body">
                                <tr>
                                    <td class="text-start">
                                        <select class="form-control form-control form-select" name="" id="variants">
                                            <option value="">@lang('menu.create_variation')</option>
                                            @foreach ($variants as $variant)
                                                <option value="{{ $variant->id }}">{{ $variant->bulk_variant_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input type="text" name="variant_combinations[]" id="variant_combination" class="form-control" placeholder="Variant Combination">
                                    </td>

                                    <td class="text-start">
                                        <input type="text" step="any" name="variant_codes[]" id="variant_code" class="form-control" placeholder="Variant Code">
                                    </td>

                                    <td class="text-start">
                                        <input type="number" step="any" name="variant_costings[]" class="form-control" placeholder="Cost" id="variant_costing">
                                    </td>

                                    <td class="text-start">
                                        <input type="number" step="any" name="variant_costings_with_tax[]"class="form-control" placeholder="Cost inc.tax" id="variant_costing_with_tax">
                                    </td>

                                    <td class="text-start">
                                        <input type="number" step="any" name="variant_profits[]" class="form-control" placeholder="Profit" value="0.00" id="variant_profit">
                                    </td>

                                    <td class="text-start">
                                        <input type="text" step="any" name="variant_prices_exc_tax[]" class="form-control" placeholder="@lang('menu.price_include_tax')" id="variant_price_exc_tax">
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
@else
    <div class="row mt-1">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8 offset-2">
                    <div class="add_combo_product_input">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            </div>
                            <input type="text" name="search_product" class="form-control form-control-sm" autocomplete="off" id="search_product" placeholder="Item search/scan by Item code">
                        </div>

                        <div class="select_area">
                            <ul class="variant_list_area">

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-10 offset-1 mt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form_table_heading">
                                <p class="m-0 pb-1"><strong>@lang('menu.create_combo_item')</strong></p>
                            </div>
                            <div class="table-responsive">
                                <table class="table modal-table table-sm">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.item')</th>
                                            <th>@lang('menu.quantity')</th>
                                            <th>@lang('menu.unit_price')</th>
                                            <th>@lang('menu.sub_total')</th>
                                            <th><i class="fas fa-trash-alt"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="combo_products"></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-center">@lang('menu.net_total_amount') :</th>
                                            <th>
                                                {{ json_decode($generalSettings->business, true)['currency'] }} <span class="span_total_combo_price">0.00</span>

                                                <input type="hidden" name="total_combo_price" id="total_combo_price" />
                                            </th>
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

    <div class="row">
        <div class="col-md-3 offset-3">
            <label><b>@lang('menu.x_margin') </b></label>
            <input type="text" name="profit" class="form-control form-control-sm" id="profit" value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}">
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.default') @lang('menu.price_exc_tax') </b></label>
            <input type="text" name="combo_price" class="form-control form-control-sm" id="combo_price">
        </div>
    </div>
@endif
