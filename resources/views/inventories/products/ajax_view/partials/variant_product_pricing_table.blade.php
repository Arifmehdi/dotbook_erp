<table id="variant_product_pricing_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-primary">
            <th class="text-white text-start">@lang('menu.variations')</th>
            <th class="text-white text-start">@lang('menu.variant_code')(SKU)</th>
            <th class="text-white text-start">@lang('menu.stock')</th>
            <th class="text-white text-start">@lang('menu.default_unit_cost_exc_tax')</th>
            <th class="text-white text-start">@lang('menu.default_unit_cost_inc_tax')</th>
            <th class="text-white text-start">@lang('menu.profit_margin')(%)</th>
            <th class="text-white text-start">@lang('menu.default_unit_price_exc_tax')</th>
            <th class="text-white text-start">@lang('menu.default_unit_price_inc_tax')</th>
            @if (count($price_groups) > 0)
                <th class="text-white text-start">@lang('menu.price_group')</th>
            @endif
            <th class="text-white text-start">@lang('menu.variation_images')</th>
        </tr>
    </thead>
    <tbody class="variant_product_pricing_table_body">
        @foreach ($product->variants as $variant)
            @php
                $priceIncTax = ($variant->variant_price / 100 * $tax) + $variant->variant_price;
                if ($product->tax_type == 2) {
                    $inclusiveTax = 100 + $tax;
                    $calc = ($variant->variant_price / $inclusiveTax) * 100;
                    $__tax_amount = $variant->variant_price - $calc;
                    $priceIncTax = $variant->variant_price + $__tax_amount;
                }
            @endphp
            <tr>
                <td class="text-start">{{ $variant->variant_name }}</td>
                <td class="text-start">{{ $variant->variant_code }}</td>
                <td class="text-start fw-bold">{{ App\Utils\Converter::format_in_bdt($variant->variant_quantity) }}</td>
                <td class="text-start fw-bold">{{ App\Utils\Converter::format_in_bdt($variant->variant_cost) }}</td>
                <td class="text-start fw-bold">{{ App\Utils\Converter::format_in_bdt($variant->variant_cost_with_tax) }}</td>
                <td class="text-start fw-bold">{{ App\Utils\Converter::format_in_bdt($variant->variant_profit) }}</td>

                <td class="text-start fw-bold">
                    {{ App\Utils\Converter::format_in_bdt($variant->variant_price) }}
                </td>

                <td class="text-start fw-bold">
                    {{ App\Utils\Converter::format_in_bdt($priceIncTax) }}
                </td>

                @if (count($price_groups) > 0)
                    <td class="text-start">
                        @foreach ($price_groups as $pg)
                            @php
                                $price_group_product = DB::table('price_group_products')
                                ->where('price_group_id', $pg->id)
                                ->where('product_id', $product->id)
                                ->where('variant_id', $variant->id)
                                ->first();
                                $groupPrice = 0;
                                if ($price_group_product) {
                                    $groupPrice = $price_group_product->price;
                                }
                            @endphp
                            <p class="m-0 p-0"><strong>{{ $pg->name }}</strong> - {{ App\Utils\Converter::format_in_bdt($groupPrice) }}</p>
                        @endforeach
                    </td>
                @endif

                <td class="text-start">
                    @if ($variant->variant_image)
                        <img style="width: 40px;height:40px;" src="{{ asset('uploads/product/variant_image/'. $variant->variant_image) }}">
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
