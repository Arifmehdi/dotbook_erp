<div class="modal-dialog modal-full-display">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title product_name" id="exampleModalLabel">
                {{ $product->name . ' - ' . $product->product_code }}
            </h5>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    @php
                        $imgSrc = (isset($product->thumbnail_photo) && file_exists(public_path('uploads/product/thumbnail/' . $product->thumbnail_photo))) ? asset('uploads/product/thumbnail/' . $product->thumbnail_photo) : asset('images/default.jpg');
                    @endphp
                    <img  class="rounded" style="height:auto;width:190px;" src="{{ $imgSrc }}" class="d-block w-100">
                </div>

                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.code')(SKU) : </strong> {{ $product->product_code }}</li>
                        <li><strong>@lang('menu.brand') : </strong> {{ $product->brand ? $product->brand->name : 'N/A' }}</li>
                        <li><strong>@lang('menu.unit') : </strong> {{ $product->unit->name }}</li>
                        <li><strong>@lang('menu.barcode_type') : </strong> {{ $product->barcode_type }}</li>
                        <li><strong>@lang('menu.manage_stock') ? : </strong> {!! $product->is_manage_stock == 1 ? '<span class="text-success">YES</span>' : '<span class="text-danger">NO</span>' !!}</li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.category') : </strong> {{$product->category ? $product->category->name : 'N/A' }}</li>
                        <li><strong>@lang('menu.sub_category') : </strong> {{ $product->subCategory ? $product->subCategory->name : 'N/A' }}</li>
                        <li><strong>@lang('menu.is_for_sale') : </strong>{{ $product->is_for_sale == 1 ? 'Yes' : 'No' }}</li>
                        <li><strong>@lang('menu.alert_quantity') : </strong>{{ $product->alert_quantity }}</li>
                        <li><strong>@lang('menu.warranty') : </strong>
                            {{ $product->warranty ? $product->warranty->name : 'N/A' }}
                        </li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.expire_date') : </strong> {{$product->expire_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($product->expire_date)) : 'N/A' }}
                        </li>
                        <li><strong>@lang('menu.tax') : </strong>{{ $product->tax ? $product->tax->name : 'N/A' }}</li>
                        @if ($product->tax)
                            <li><strong>@lang('menu.tax_type'): </strong>{{ $product->tax_type == 1 ? 'Exclusive' : 'Inclusive' }}</li>
                        @endif
                        <li><strong>@lang('menu.item_condition') : </strong> {{ $product->product_condition }}</li>
                        <li>
                            <strong>@lang('menu.item_type') : </strong>
                            @php
                                $product_type = '';
                            @endphp
                            @if ($product->type == 1 && $product->is_variant == 1)
                                @php $product_type = 'Variant'; @endphp
                            @elseif ($product->type == 1 && $product->is_variant == 0)
                                @php $product_type = 'Single'; @endphp
                            @elseif ($product->type == 2)
                                @php  $product_type = 'Combo'; @endphp
                            @elseif ($product->type == 3)
                                @php  $product_type = 'Digital'; @endphp
                            @endif
                            {{ $product_type }}
                        </li>
                        <li>
                            <strong class="text-primary">{{ $product->is_manage_stock == 0 ? '(Service related/Digital Item)' : '' }}</strong>
                        </li>
                    </ul>
                </div>
            </div><br>
            @php $tax = $product->tax ? $product->tax->tax_percent : 0  @endphp
            @if ($product->is_combo == 1)

                <div class="row">
                    <div class="heading">
                        <label class="p-0 m-0"><strong>@lang('menu.combo') :</strong></label>
                    </div>
                    <div class="table-responsive" id="combo_product_details">
                        <!--Warehouse Stock Details-->
                        @include('inventories.products.ajax_view.partials.combo_product_list')
                        <!--Warehouse Stock Details End-->
                    </div>
                </div>
            @else

                @if ($product->is_variant == 0)

                    <div class="row">
                        <div class="heading">
                            <label class="p-0 m-0"><strong>@lang('menu.purchase_and_selling_price') :</strong></label>
                        </div>
                        <div class="table-responsive">
                            <!--single_product_pricing_table-->
                            @include('inventories.products.ajax_view.partials.single_product_pricing_table')
                            <!--single_product_pricing_table End-->
                        </div>
                    </div>
                @elseif($product->is_variant == 1)

                    <div class="row">
                        <div class="heading">
                            <label class="p-0 m-0"><strong>@lang('menu.purchase_and_selling_price_details') :</strong></label>
                        </div>

                        <div class="table-responsive">
                            <!--variant_product_pricing_table-->
                            @include('inventories.products.ajax_view.partials.variant_product_pricing_table')
                            <!--variant_product_pricing_table End-->
                        </div>
                    </div>
                @endif
            @endif

            @if ($product->is_manage_stock == 1)

                @if (count($own_warehouse_stocks) > 0)

                    <hr class="m-0">

                    <div class="row">
                        <div class="heading">
                            <label class="p-0 m-0">@lang('menu.own') <strong>@lang('menu.warehouse'):</strong> @lang('menu.stock_details') </label>
                        </div>
                        <div class="table-responsive" id="warehouse_stock_details">
                            <!--Warehouse Stock Details-->
                            @include('inventories.products.ajax_view.partials.own_warehouse_stock_details')
                            <!--Warehouse Stock Details End-->
                        </div>
                    </div>
                @endif

                <hr class="m-0">

                <div class="row">
                    <div class="heading">
                        <label class="p-0 m-0">@lang('menu.won') <strong>@lang('menu.business_location'):</strong>@lang('menu.stock_details') </label>
                    </div>
                    <div class="table-responsive" id="branch_stock_details">
                        @include('inventories.products.ajax_view.partials.branch_stock_details')
                    </div>
                </div>

                <hr class="m-0">
            @endif

    </div>
    <div class="modal-footer text-end">
        <button type="submit" class="btn btn-sm btn-success print_btn me-2">@lang('menu.print')</button>
        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
    </div>
</div>
