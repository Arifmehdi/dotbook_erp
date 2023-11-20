<?php

namespace App\Utils;

use App\Models\Category;
use App\Models\ComboProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class ProductUtil
{
    protected $purchaseSaleChainUtil;

    protected $invoiceVoucherRefIdUtil;

    public function __construct(
        PurchaseSaleChainUtil $purchaseSaleChainUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
    ) {
        $this->purchaseSaleChainUtil = $purchaseSaleChainUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function addProduct($request)
    {
        $variantIds = [];

        $addProduct = new Product();
        $addProduct->type = $request->type;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->code ? $request->code : $request->auto_generated_code;
        $addProduct->category_id = $request->category_id;
        $addProduct->parent_category_id = $request->sub_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->alert_quantity = $request->alert_quantity;
        $addProduct->tax_ac_id = $request->tax_ac_id;
        $addProduct->tax_type = $request->tax_type;
        $addProduct->purchase_type = $request->purchase_type;
        $addProduct->product_condition = $request->product_condition;
        $addProduct->is_show_in_ecom = $request->is_show_in_ecom;
        $addProduct->is_for_sale = $request->is_not_for_sale;
        $addProduct->is_show_emi_on_pos = $request->is_show_emi_on_pos;
        $addProduct->is_manage_stock = $request->stock_type;
        $addProduct->product_details = isset($request->product_details) ? $request->product_details : null;
        $addProduct->is_purchased = 0;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->weight = isset($request->weight) ? $request->weight : null;

        if ($request->file('image')) {

            if (count($request->file('image')) > 0) {

                foreach ($request->file('image') as $image) {

                    $productImage = $image;
                    $productImageName = uniqid() . '.' . $productImage->getClientOriginalExtension();
                    Image::make($productImage)->resize(600, 600)->save('uploads/product/' . $productImageName);
                    $addProductImage = new ProductImage();
                    $addProductImage->product_id = $addProduct->id;
                    $addProductImage->image = $productImageName;
                    $addProductImage->save();
                }
            }
        }

        if ($request->type == 1) {

            $addProduct->product_cost = $request->product_cost ? $request->product_cost : 0;
            $addProduct->profit = $request->profit ? $request->profit : 0;
            $addProduct->product_cost_with_tax = $request->product_cost_with_tax ? $request->product_cost_with_tax : 0;
            $addProduct->product_price = $request->product_price ? $request->product_price : 0;

            if ($request->file('photo')) {

                $productThumbnailPhoto = $request->file('photo');
                $productThumbnailName = uniqid() . '.' . $productThumbnailPhoto->getClientOriginalExtension();

                $path = public_path('uploads/product/thumbnail');

                if (!file_exists($path)) {

                    mkdir($path);
                }

                Image::make($productThumbnailPhoto)->resize(600, 600)->save($path . '/' . $productThumbnailName);
                $addProduct->thumbnail_photo = $productThumbnailName;
            }

            if ($request->is_variant == 1) {

                $addProduct->is_variant = $request->is_variant;
                $addProduct->save();

                $index = 0;
                foreach ($request->variant_combinations as $value) {

                    $addVariant = new ProductVariant();
                    $addVariant->product_id = $addProduct->id;
                    $addVariant->variant_name = $value;
                    $addVariant->variant_code = $request->variant_codes[$index];
                    $addVariant->variant_cost = $request->variant_costings[$index];
                    $addVariant->variant_cost_with_tax = $request->variant_costings_with_tax[$index];
                    $addVariant->variant_profit = $request->variant_profits[$index];
                    $addVariant->variant_price = $request->variant_prices_exc_tax[$index];

                    if (isset($request->variant_image[$index])) {

                        $variantImage = $request->variant_image[$index];
                        $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
                        $path = public_path('uploads/product/variant_image');

                        if (!file_exists($path)) {

                            mkdir($path);
                        }

                        Image::make($variantImage)->resize(250, 250)->save($path . '/' . $variantImageName);
                        $addVariant->variant_image = $variantImageName;
                    }

                    $addVariant->save();

                    array_push($variantIds, $addVariant->id);

                    $index++;
                }
            } else {

                $addProduct->save();
            }
        }

        if ($request->type == 2) {

            $addProduct->is_combo = 1;
            $addProduct->profit = $request->profit ? $request->profit : 0.00;
            $addProduct->combo_price = $request->combo_price;
            $addProduct->product_price = $request->combo_price;
            $addProduct->save();

            $productIds = $request->product_ids;
            $combo_quantities = $request->combo_quantities;
            $productVariantIds = $request->variant_ids;
            $index = 0;

            foreach ($productIds as $id) {

                $addComboProducts = new ComboProduct();
                $addComboProducts->product_id = $addProduct->id;
                $addComboProducts->combo_product_id = $id;
                $addComboProducts->quantity = $combo_quantities[$index];
                $addComboProducts->product_variant_id = $productVariantIds[$index] !== 'noid' ? $productVariantIds[$index] : null;
                $index++;
                $addComboProducts->save();
            }
        }

        return ['addProduct' => $addProduct, 'variantIds' => $variantIds];
    }

    public function updateProduct($request, $productId)
    {
        $variantIds = [];
        $updateProduct = Product::with(['variants', 'ComboProducts'])->where('id', $productId)->first();
        $updateProduct->name = $request->name;
        $updateProduct->product_code = $request->code ? $request->code : $request->auto_generated_code;
        $updateProduct->category_id = $request->category_id;
        $updateProduct->parent_category_id = $request->sub_category_id;
        $updateProduct->brand_id = $request->brand_id;
        $updateProduct->unit_id = $request->unit_id;
        $updateProduct->alert_quantity = $request->alert_quantity;
        $updateProduct->tax_ac_id = $request->tax_ac_id;
        $updateProduct->tax_type = $request->tax_type;
        $updateProduct->purchase_type = $request->purchase_type;
        $updateProduct->product_condition = $request->product_condition;
        $updateProduct->is_show_in_ecom = $request->is_show_in_ecom;
        $updateProduct->is_for_sale = $request->is_not_for_sale;
        $updateProduct->is_show_emi_on_pos = $request->is_show_emi_on_pos;
        $updateProduct->is_manage_stock = $request->stock_type;
        $updateProduct->product_details = $request->product_details;
        $updateProduct->barcode_type = $request->barcode_type;
        $updateProduct->warranty_id = $request->warranty_id;
        $updateProduct->weight = $request->weight;

        if ($request->file('image')) {

            if (count($request->file('image')) > 0) {

                foreach ($request->file('image') as $image) {

                    $productImage = $image;
                    $productImageName = uniqid() . '.' . $productImage->getClientOriginalExtension();
                    Image::make($productImage)->resize(250, 250)->save('uploads/product/' . $productImageName);
                    $addProductImage = new ProductImage();
                    $addProductImage->product_id = $updateProduct->id;
                    $addProductImage->image = $productImageName;
                    $addProductImage->save();
                }
            }
        }

        if ($updateProduct->type == 1) {

            $updateProduct->product_cost = $request->product_cost ? $request->product_cost : 0;
            $updateProduct->profit = $request->profit ? $request->profit : 0;
            $updateProduct->product_cost_with_tax = $request->product_cost_with_tax ? $request->product_cost_with_tax : 0;
            $updateProduct->product_price = $request->product_price ? $request->product_price : 0;

            // Upload product thumbnail
            if ($request->file('photo')) {

                $path = public_path('uploads/product/thumbnail');

                if ($updateProduct->thumbnail_photo) {

                    if (file_exists($path . '/' . $updateProduct->thumbnail_photo)) {

                        unlink($path . '/' . $updateProduct->thumbnail_photo);
                    }
                }

                $productThumbnailPhoto = $request->file('photo');
                $productThumbnailName = uniqid() . '.' . $productThumbnailPhoto->getClientOriginalExtension();

                if (!file_exists($path)) {

                    mkdir($path);
                }

                Image::make($productThumbnailPhoto)->resize(250, 250)->save($path . '/' . $productThumbnailName);
                $updateProduct->thumbnail_photo = $productThumbnailName;
            }

            if ($updateProduct->is_variant == 1) {

                foreach ($updateProduct->variants as $variant) {

                    $variant->delete_in_update = 1;
                    $variant->save();
                }

                $updateProduct->save();

                $index = 0;
                foreach ($request->variant_combinations as $value) {

                    $updateVariant = ProductVariant::where('id', $request->variant_ids[$index])->first();

                    if ($updateVariant) {

                        $updateVariant->variant_name = $value;
                        $updateVariant->variant_code = $request->variant_codes[$index];
                        $updateVariant->variant_cost = $request->variant_costings[$index];
                        $updateVariant->variant_cost_with_tax = $request->variant_costings_with_tax[$index];
                        $updateVariant->variant_profit = $request->variant_profits[$index];
                        $updateVariant->variant_price = $request->variant_prices_exc_tax[$index];
                        $updateVariant->delete_in_update = 0;

                        if (isset($request->variant_image[$index])) {

                            $path = public_path('uploads/product/variant_image');

                            if ($updateVariant->variant_image != null) {

                                if (file_exists($path . '/' . $updateVariant->variant_image)) {

                                    unlink($path . '/' . $updateVariant->variant_image);
                                }
                            }

                            $variantImage = $request->variant_image[$index];
                            $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();

                            if (!file_exists($path)) {

                                mkdir($path);
                            }

                            Image::make($variantImage)->resize(250, 250)->save($path . '/' . $variantImageName);
                            $updateVariant->variant_image = $variantImageName;
                        }

                        $updateVariant->save();

                        array_push($variantIds, $updateVariant->id);
                    } else {

                        $addVariant = new ProductVariant();
                        $addVariant->product_id = $updateProduct->id;
                        $addVariant->variant_name = $value;
                        $addVariant->variant_code = $request->variant_codes[$index];
                        $addVariant->variant_cost = $request->variant_costings[$index];
                        $addVariant->variant_cost_with_tax = $request->variant_costings_with_tax[$index];
                        $addVariant->variant_profit = $request->variant_profits[$index];
                        $addVariant->variant_price = $request->variant_prices_exc_tax[$index];

                        if (isset($request->variant_image[$index])) {

                            $variantImage = $request->variant_image[$index];
                            $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();

                            $path = public_path('uploads/product/variant_image');

                            if (!file_exists($path)) {

                                mkdir($path);
                            }

                            Image::make($variantImage)->resize(250, 250)->save($path . '/' . $variantImageName);
                            $addVariant->variant_image = $variantImageName;
                        }

                        $addVariant->save();

                        array_push($variantIds, $addVariant->id);
                    }

                    $index++;
                }

                $deleteNotFoundVariants = ProductVariant::where('delete_in_update', 1)->get();

                foreach ($deleteNotFoundVariants as $deleteNotFoundVariant) {

                    if ($deleteNotFoundVariant->variant_image != null) {

                        if (file_exists(public_path('uploads/product/variant_image/' . $updateVariant->variant_image))) {

                            unlink(public_path('uploads/product/thumbnail/' . $updateVariant->variant_image));
                        }
                    }

                    $deleteNotFoundVariant->delete();
                }
            } else {

                $updateProduct->save();
            }
        }

        if ($updateProduct->type == 2) {

            if ($request->product_ids == null) {

                return response()->json(['errorMsg' => 'You have selected combo item but there is no item at all']);
            }

            foreach ($updateProduct->ComboProducts as $ComboProduct) {

                $ComboProduct->delete_in_update = 1;
                $ComboProduct->save();
            }

            $updateProduct->profit = $request->profit ? $request->profit : 0.00;
            $updateProduct->product_price = $request->combo_price;
            $updateProduct->combo_price = $request->combo_price;
            $updateProduct->save();

            $combo_ids = $request->combo_ids;
            $productIds = $request->product_ids;
            $combo_quantities = $request->combo_quantities;
            $productVariantIds = $request->variant_ids;
            $index = 0;

            foreach ($productIds as $id) {

                $updateComboProduct = ComboProduct::where('id', $combo_ids[$index])->first();
                if ($updateComboProduct) {

                    $updateComboProduct->quantity = $combo_quantities[$index];
                    $updateComboProduct->delete_in_update = 0;
                    $updateComboProduct->save();
                } else {

                    $addComboProducts = new ComboProduct();
                    $addComboProducts->product_id = $updateProduct->id;
                    $addComboProducts->combo_product_id = $id;
                    $addComboProducts->quantity = $combo_quantities[$index];
                    $addComboProducts->product_variant_id = $productVariantIds[$index] !== 'noid' ? $productVariantIds[$index] : null;
                    $addComboProducts->save();
                }
                $index++;
            }
        }

        $deleteNotFoundComboProducts = ComboProduct::where('delete_in_update', 1)->get();

        foreach ($deleteNotFoundComboProducts as $deleteNotFoundComboProduct) {

            $deleteNotFoundComboProduct->delete();
        }

        return ['updateProduct' => $updateProduct, 'variantIds' => $variantIds];
    }

    public function productListTable($request)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $countPriceGroup = DB::table('price_groups')->where('status', 'Active')->count();
        $products = '';

        $query = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.parent_category_id', 'sub_cate.id')
            ->leftJoin('accounts as taxes', 'products.tax_ac_id', 'taxes.id')
            ->leftJoin('brands', 'products.brand_id', 'brands.id');

        if ($request->type == 1) {

            $query->where('products.type', 1)->where('products.is_variant', 0);
        }

        if ($request->type == 2) {

            $query->where('products.is_variant', 1)->where('products.type', 1);
        }

        if ($request->type == 3) {

            $query->where('products.type', 2)->where('products.is_combo', 1);
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->subcategory_id) {

            $query->where('products.parent_category_id', $request->subcategory_id);
        }

        if ($request->unit_id) {

            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->status != '') {

            $query->where('products.status', $request->status);
        }

        // if ($request->is_for_sale) {
        //     $query->where('products.is_for_sale', '0');
        // }

        $products = $query->select(
            [
                'products.id',
                'products.name',
                'products.status',
                'products.is_variant',
                'products.type',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.product_code',
                'products.is_manage_stock',
                'products.thumbnail_photo',
                'products.expire_date',
                'products.is_combo',
                'products.quantity as current_stock',
                //'product_branches.product_quantity',
                'units.name as unit_name',
                'taxes.name as tax_name',
                'categories.name as cate_name',
                'categories.code as cate_code',
                'sub_cate.name as sub_cate_name',
                'brands.name as brand_name',
            ]
        )->orderBy('id', 'desc');

        return DataTables::of($products)
            ->addColumn('multiple_delete', function ($row) {
                return '<input id="' . $row->id . '" class="data_id sorting_disabled" type="checkbox" name="data_ids[]" value="' . $row->id . '"/>';
            })->editColumn('photo', function ($row) {

                $imgSrc = (isset($row->thumbnail_photo) && file_exists(public_path('uploads/product/thumbnail/' . $row->thumbnail_photo))) ? asset('uploads/product/thumbnail/' . $row->thumbnail_photo) : asset('images/default.jpg');

                return '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="' . $imgSrc . '">';

            })->addColumn('action', function ($row) use ($countPriceGroup) {

                $html = '<div class="btn-group" role="group">';

                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action</button>';

                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                $html .= '<a class="dropdown-item details_button" href="' . route('products.view', [$row->id]) . '"> View</a>';

                if (auth()->user()->can('product_edit')) {

                    $html .= '<a class="dropdown-item" href="' . route('products.edit', [$row->id]) . '"> Edit</a>';
                }

                if (auth()->user()->can('product_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="' . route('products.delete', [$row->id]) . '"> Delete</a>';
                }

                if (auth()->user()->can('openingStock_add')) {

                    $html .= '<a class="dropdown-item" id="openingStockBtn" href="' . route('products.opening.stock.create.or.edit', [$row->id]) . '"> Opening Stock Add or Edit</a>';
                }

                if ($countPriceGroup > 0) {

                    $html .= '<a class="dropdown-item" href="' . route('products.add.price.groups', [$row->id, $row->is_variant]) . '"> Price Group Add or Edit</a>';
                }

                $html .= ' </div>';
                $html .= '</div>';

                return $html;
            })->editColumn('name', function ($row) {

                $html = '';
                $html .= $row->name;
                $html .= $row->is_manage_stock == 0 ? ' <span class="badge bg-primary pt-1"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '';

                return $html;
            })->editColumn('type', function ($row) {

                if ($row->type == 1 && $row->is_variant == 1) {

                    return '<span class="text-primary">Variant</span>';
                } elseif ($row->type == 1 && $row->is_variant == 0) {

                    return '<span class="text-success">Single</span>';
                } elseif ($row->type == 2) {

                    return '<span class="text-info">Combo</span>';
                } elseif ($row->type == 3) {

                    return '<span class="text-info">Digital</span>';
                }
            })

            ->editColumn('unit_cost_inc_tax', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->product_cost_with_tax);
            })

            ->editColumn('unit_price_exc_tax', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->product_price);
            })

            ->editColumn('current_stock', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->current_stock);
            })

            ->editColumn('cate_name', function ($row) {

                return ($row->cate_name ? '<p class="p-0 m-0">' . $row->cate_name . ($row->cate_code ? '/' . $row->cate_code : '') . '</p>' : '...') . ($row->sub_cate_name ? '<p class="p-0 m-0"> --- ' . $row->sub_cate_name . '</p>' : '');
            })

            ->editColumn('status', function ($row) {

                if ($row->status == 1) {
                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input change_status" data-url="' . route('products.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked />';
                    $html .= '</div>';

                    return $html;
                } else {
                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input change_status" data-url="' . route('products.change.status', [$row->id]) . '"style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                    $html .= '</div>';

                    return $html;
                }
            })->editColumn('brand_name', function ($row) {

                return $row->brand_name ? $row->brand_name : '...';
            })->editColumn('tax_name', function ($row) {

                return $row->tax_name ? $row->tax_name : '...';
            })->rawColumns([
                'multiple_delete',
                'photo',
                'action',
                'name',
                'type',
                'unit_cost_inc_tax',
                'unit_price_exc_tax',
                'current_stock',
                'cate_name',
                'status',
                'expire_date',
                'tax_name',
                'brand_name',
            ])->smart(true)->make(true);
    }

    public function addQuickCategory($request)
    {
        $request->validate(['name' => 'required']);

        $addQuickCategory = new Category();
        $addQuickCategory->name = $request->name;
        $addQuickCategory->code = $this->invoiceVoucherRefIdUtil->generateCategoryCode();
        $addQuickCategory->save();

        return response()->json($addQuickCategory);
    }

    public function deleteProduct($deleteProduct)
    {
        if (!is_null($deleteProduct)) {

            if ($deleteProduct->thumbnail_photo) {

                if (file_exists(public_path('uploads/product/thumbnail/' . $deleteProduct->thumbnail_photo))) {

                    unlink(public_path('uploads/product/thumbnail/' . $deleteProduct->thumbnail_photo));
                }
            }

            if ($deleteProduct->productImages->count() > 0) {

                foreach ($deleteProduct->productImages as $productImage) {

                    if (file_exists(public_path('uploads/product/' . $productImage->image))) {

                        unlink(public_path('uploads/product/' . $productImage->image));
                    }
                }
            }

            if ($deleteProduct->variants->count() > 0) {

                foreach ($deleteProduct->variants as $variant) {

                    if ($variant->variant_image) {

                        if (file_exists(public_path('uploads/product/variant_image/' . $variant->variant_image))) {

                            unlink(public_path('uploads/product/variant_image/' . $variant->variant_image));
                        }
                    }
                }
            }

            $deleteProduct->delete();

            return $deleteProduct;
        }
    }

    public function updateProductAndVariantPrice(
        $productId,
        $variant_id,
        $unit_cost_with_discount,
        $net_unit_cost,
        $profit,
        $selling_price,
        $isEditProductPrice,
        $isLastEntry,
        $tax_ac_id = null
    ) {
        $updateProduct = Product::where('id', $productId)->first();
        $updateProduct->is_purchased = 1;

        if ($updateProduct->is_variant == 0) {

            if ($isLastEntry == 1) {

                $updateProduct->tax_ac_id = $tax_ac_id;
                $updateProduct->product_cost = $unit_cost_with_discount;
                $updateProduct->product_cost_with_tax = $net_unit_cost;
            }

            if ($isEditProductPrice == '1') {

                $updateProduct->profit = $profit;
                $updateProduct->product_price = $selling_price;
            }
        }

        $updateProduct->save();

        if ($variant_id != null) {

            $updateVariant = ProductVariant::where('id', $variant_id)
                ->where('product_id', $productId)
                ->first();

            if ($isLastEntry == 1) {

                $updateVariant->variant_cost = $unit_cost_with_discount;
                $updateVariant->variant_cost_with_tax = $net_unit_cost;
            }

            if ($isEditProductPrice == '1') {

                $updateVariant->variant_profit = $profit;
                $updateVariant->variant_price = $selling_price;
            }

            $updateVariant->is_purchased = 1;
            $updateVariant->save();
        }
    }
}
