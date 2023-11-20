<table id="single_product_pricing_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-primary">
            <th class="text-white text-start">@lang('menu.default_unit_cost_exc_tax')</th>
            <th class="text-white text-start">@lang('menu.default_unit_cost_inc_tax')</th>
            <th class="text-white text-start">@lang('menu.profit_margin')(%)</th>
            <th class="text-white text-start">@lang('menu.default_unit_price_exc_tax')</th>
            <th class="text-white text-start">@lang('menu.default_unit_price_inc_tax')</th>
            @if (count($price_groups) > 0)
                <th class="text-white text-start">@lang('menu.price_group')</th>
            @endif
            @php
                $priceIncTax = ($product->product_price / 100) * $tax + $product->product_price;
                if ($product->tax_type == 2) {
                    $inclusiveTax = 100 + $tax;
                    $calc = ($product->product_price / $inclusiveTax) * 100;
                    $__tax_amount = $product->product_price - $calc;
                    $priceIncTax = $product->product_price + $__tax_amount;
                }
            @endphp
        </tr>
    </thead>
    <tbody class="single_product_pricing_table_body">
        <tr>
            <td class="text-start fw-bold">
                {{ App\Utils\Converter::format_in_bdt($product->product_cost) }}
            </td>
            <td class="text-start fw-bold">{{ $product->product_cost_with_tax }}</td>
            <td class="text-start fw-bold">{{ $product->profit }}</td>
            <td class="text-start fw-bold">{{ App\Utils\Converter::format_in_bdt($product->product_price) }}</td>
            <td class="text-start fw-bold">{{ App\Utils\Converter::format_in_bdt($priceIncTax) }}</td>
            @if (count($price_groups) > 0)
                <td class="text-start">
                    @foreach ($price_groups as $pg)
                        @php
                            $price_group_product = DB::table('price_group_products')
                            ->where('price_group_id', $pg->id)->where('product_id', $product->id)->first();
                            $groupPrice = 0;
                            if ($price_group_product) {
                                $groupPrice = $price_group_product->price;
                            }
                        @endphp
                        <p class="p-0 m-0"><strong>{{ $pg->name }}</strong> - {{ App\Utils\Converter::format_in_bdt($groupPrice)}}</p>
                    @endforeach
                </td>
            @endif
        </tr>
    </tbody>
</table>
