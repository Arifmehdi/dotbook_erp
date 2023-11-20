<?php

namespace App\Http\Controllers;

use App\Models\BulkVariant;
use App\Models\ComboProduct;
use App\Models\PriceGroupProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SupplierProduct;
use App\Utils\OpeningStockUtil;
use App\Utils\ProductStockUtil;
use App\Utils\ProductUtil;
use App\Utils\PurchaseSaleChainUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct(
        private ProductUtil $productUtil,
        private OpeningStockUtil $openingStockUtil,
        private ProductStockUtil $productStockUtil,
        private PurchaseSaleChainUtil $purchaseSaleChainUtil,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function allProduct(Request $request)
    {
        if (!auth()->user()->can('product_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->productUtil->productListTable($request);
        }

        $categories = DB::table('categories')->where('parent_category_id', null)->get(['id', 'name']);
        $brands = DB::table('brands')->get(['id', 'name']);
        $units = DB::table('units')->get(['id', 'name', 'code_name']);

        $total = [
            'item' => Db::table('products')->count(),
            'active' => Db::table('products')->where('status', 1)->count(),
            'inactive' => Db::table('products')->where('status', 0)->count(),
        ];

        return view('inventories.products.index', compact('categories', 'brands', 'units', 'total'));
    }

    public function productstatus()
    {
        $productstatus = [
            'item' => Db::table('products')->count(),
            'active' => Db::table('products')->where('status', 1)->count(),
            'inactive' => Db::table('products')->where('status', 0)->count(),
        ];

        return response()->json($productstatus);
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('product_add')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $products = DB::table('product_branches')
                ->leftJoin('products', 'product_branches.product_id', 'products.id')
                ->select('products.id', 'products.name', 'products.product_cost', 'products.product_price')
                ->orderBy('products.id', 'desc');

            return DataTables::of($products)
                ->addColumn('action', function ($row) {

                    return '<a href="' . route('products.edit', [$row->id]) . '" class="action-btn c-edit" title="Edit"><span class="fas fa-edit"></span></a>';
                })->editColumn('name', function ($row) {

                    return '<span title="' . $row->name . '">' . Str::limit($row->name, 21, '..') . '</span>';
                })->rawColumns(['name', 'action'])->make(true);
        }

        $units = DB::table('units')->where('base_unit_id', null)->orderBy('name', 'asc')->get(['id', 'name', 'code_name']);
        $categories = DB::table('categories')->where('parent_category_id', null)->orderBy('name', 'desc')->get(['id', 'name', 'code']);
        $brands = DB::table('brands')->orderBy('name', 'desc')->get(['id', 'name']);
        $warranties = DB::table('warranties')->orderBy('id', 'desc')->get(['id', 'name']);

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        return view('inventories.products.create', compact('units', 'categories', 'brands', 'warranties', 'taxAccounts'));
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'code' => 'sometimes|unique:products,product_code',
                'unit_id' => 'required',
                'photo' => 'sometimes|image|max:2048',
                'image.*' => 'sometimes|image|max:2048',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        if ($request->is_variant == 1) {

            if ($request->variant_combinations == null) {

                return response()->json(['errorMsg' => 'You have selected Has variant? = Yes but there is no variant at all.']);
            }

            $this->validate(
                $request,
                [
                    'variant_image.*' => 'sometimes|image|max:2048',
                ],
            );
        }

        if ($request->type == 2) {

            if ($request->product_ids == null) {

                return response()->json(['errorMsg' => 'You have selected combo item but there is no item at all']);
            }
        }

        try {

            DB::beginTransaction();

            $addProduct = $this->productUtil->addProduct($request);

            $addedProduct = $addProduct['addProduct'];
            $variantIds = $addProduct['variantIds'];

            if ($addedProduct) {

                $this->userActivityLogUtil->addLog(action: 1, subject_type: 26, data_obj: $addedProduct);
            }

            if ($request->is_variant == 1) {

                foreach ($request->variant_combinations as $key => $value) {

                    $this->productStockUtil->addBranchProduct(
                        product_id: $addedProduct->id,
                        variant_id: $variantIds[$key],
                    );
                }
            } else {

                $this->productStockUtil->addBranchProduct(
                    product_id: $addedProduct->id,
                    variant_id: null,
                    force_add: 1
                );
            }

            if (isset($request->has_opening_stock)) {

                $addOrUpdateOpeningStock = $this->openingStockUtil->saveAddOrEditOpeningStock(
                    productId: $addedProduct->id,
                    variantId: null,
                    unitCostIncTax: $request->unit_cost_inc_tax,
                    quantity: $request->quantity,
                    subtotal: $request->subtotal,
                    warehouseId: isset($request->warehouse_count) ? $request->warehouse_id : null
                );

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'opening_stock_id',
                    transId: $addOrUpdateOpeningStock->id,
                    productId: $addOrUpdateOpeningStock->product_id,
                    quantity: $addOrUpdateOpeningStock->quantity,
                    variantId: $addOrUpdateOpeningStock->product_variant_id,
                    unitCostIncTax: $addOrUpdateOpeningStock->unit_cost_inc_tax,
                    sellingPrice: 0,
                    subTotal: $addOrUpdateOpeningStock->subtotal,
                    createdAt: date('Y-m-d H:i:s'),
                );

                $this->productStockUtil->adjustMainProductAndVariantStock($addedProduct->id, null);

                if ($request->warehouse_count) {

                    $this->productStockUtil->addWarehouseProduct($addedProduct->id, null, $request->warehouse_id);
                    $this->productStockUtil->adjustWarehouseStock($addedProduct->id, null, $request->warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($product_id, null);
                }
            }

            $product = Product::with(['tax', 'unit:id,name,code_name', 'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier'])
                ->where('id', $addedProduct->id)->first();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', 'Item create Successfully');

        return response()->json([
            'item' => $product,
            'warehouse_id' => isset($request->warehouse_count) ? $request->warehouse_id : null,
        ]);
    }

    public function view($productId)
    {
        $product = Product::with([
            'category',
            'subCategory',
            'tax',
            'unit:id,name,code_name',
            'brand',
            'ComboProducts',
            'ComboProducts.parentProduct',
            'ComboProducts.parentProduct.tax',
            'ComboProducts.variants',
            'variants',
        ])->where('id', $productId)->first();

        $own_branch_stocks = DB::table('product_branches')
            ->where('product_branches.product_id', $productId)
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')

            ->select(
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_branches.product_quantity',
                'product_branches.total_sale',
                'product_branches.total_purchased',
                'product_branches.total_opening_stock',
                'product_branches.total_adjusted',
                'product_branches.total_transferred',
                'product_branches.total_received',
                'product_branches.total_sale_return',
                'product_branches.total_purchase_return',
                'product_branch_variants.variant_quantity',
                'product_branch_variants.total_sale as v_total_sale',
                'product_branch_variants.total_purchased as v_total_purchased',
                'product_branch_variants.total_opening_stock as v_total_opening_stock',
                'product_branch_variants.total_adjusted as v_total_adjusted',
                'product_branch_variants.total_transferred as v_total_transferred',
                'product_branch_variants.total_received as v_total_received',
                'product_branch_variants.total_sale_return as v_total_sale_return',
                'product_branch_variants.total_purchase_return as v_total_purchase_return',
            )->get();

        $own_warehouse_stocks = DB::table('product_warehouses')
            ->where('product_warehouses.product_id', $productId)
            ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
            ->leftJoin('product_warehouse_variants', 'product_warehouses.id', 'product_warehouse_variants.product_warehouse_id')
            ->leftJoin('product_variants', 'product_warehouse_variants.product_variant_id', 'product_variants.id')
            ->select(
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_warehouses.product_quantity',
                'product_warehouses.total_purchased',
                'product_warehouses.total_adjusted',
                'product_warehouses.total_transferred',
                'product_warehouses.total_received',
                'product_warehouses.total_sale_return',
                'product_warehouses.total_purchase_return',
                'product_warehouse_variants.variant_quantity',
                'product_warehouse_variants.total_purchased as v_total_purchased',
                'product_warehouse_variants.total_adjusted as v_total_adjusted',
                'product_warehouse_variants.total_transferred as v_total_transferred',
                'product_warehouse_variants.total_received as v_total_received',
                'product_warehouse_variants.total_sale_return as v_',
                'product_warehouse_variants.total_purchase_return as v_total_purchase_return',
            )->get();

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        return view('inventories.products.ajax_view.product_details_view', compact(
            'product',
            'price_groups',
            'own_branch_stocks',
            'own_warehouse_stocks',
        ));
    }

    public function addPriceGroup($productId, $type)
    {
        $priceGroups = DB::table('price_groups')->where('status', 'Active')->get();
        $product_name = DB::table('products')->where('id', $productId)->first(['name', 'product_code']);
        $products = DB::table('products')
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('taxes', 'products.tax_ac_id', 'taxes.id')
            ->where('products.id', $productId)
            ->select(
                'products.id as p_id',
                'products.is_variant',
                'products.name',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_price',
                'product_variants.id as v_id',
                'taxes.tax_percent'
            )->get();

        return view('inventories.products.add_price_group', compact('products', 'type', 'priceGroups', 'product_name'));
    }

    public function savePriceGroup(Request $request)
    {
        $variant_ids = $request->variant_ids;
        $index = 0;
        foreach ($request->product_ids as $product_id) {

            foreach ($request->group_prices as $key => $group_price) {

                (float) $__group_price = $group_price[$product_id][$variant_ids[$index]];
                $__variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : null;
                $updatePriceGroup = PriceGroupProduct::where('price_group_id', $key)->where('product_id', $product_id)->where('variant_id', $__variant_id)->first();

                if ($updatePriceGroup) {

                    $updatePriceGroup->price = $__group_price != null ? $__group_price : null;
                    $updatePriceGroup->save();
                } else {

                    $addPriceGroup = new PriceGroupProduct();
                    $addPriceGroup->price_group_id = $key;
                    $addPriceGroup->product_id = $product_id;
                    $addPriceGroup->variant_id = $__variant_id;
                    $addPriceGroup->price = $__group_price != null ? $__group_price : null;
                    $addPriceGroup->save();
                }
            }

            $index++;
        }

        if ($request->action_type == 'save') {

            return response()->json(['saveMessage' => 'Product price group updated Successfully']);
        } else {

            return response()->json(['saveAndAnotherMsg' => 'Product price group updated Successfully']);
        }
    }

    public function edit($productId)
    {
        $product = DB::table('products')->where('products.id', $productId)
            ->leftJoin('accounts as taxes', 'products.tax_ac_id', 'taxes.id')
            ->select('products.*', 'taxes.tax_percent')
            ->first();

        $categories = DB::table('categories')->where('parent_category_id', null)->orderBy('id', 'desc')->get(['id', 'name', 'code']);
        $units = DB::table('units')->get();
        $brands = DB::table('brands')->get();
        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $warranties = DB::table('warranties')->get();

        return view('inventories.products.edit', compact('product', 'categories', 'units', 'brands', 'taxAccounts', 'warranties'));
    }

    // product update method
    public function update(Request $request, $productId)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'unit_id' => 'required',
                'photo' => 'sometimes|image|max:2048',
                'image.*' => 'sometimes|image|max:2048',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        $updateProduct = $this->productUtil->updateProduct($request, $productId);
        $updatedProduct = $updateProduct['updateProduct'];
        $variantIds = $updateProduct['variantIds'];

        if ($updatedProduct->is_variant == 1) {

            if ($request->variant_combinations == null) {

                return response()->json(['errorMsg' => 'You have selected variant option but there is no variant at all.']);
            }

            $this->validate(
                $request,
                [
                    'variant_image.*' => 'sometimes|image|max:2048',
                ],
            );
        }

        if ($updatedProduct->is_variant == 1) {

            foreach ($request->variant_combinations as $key => $value) {

                $this->productStockUtil->addBranchProduct(
                    product_id: $updatedProduct->id,
                    variant_id: $variantIds[$key]
                );
            }
        } else {

            $this->productStockUtil->addBranchProduct(
                product_id: $updatedProduct->id,
                variant_id: null
            );
        }

        if ($updatedProduct) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 26, data_obj: $updatedProduct);
        }

        session()->flash('successMsg', 'Successfully product is updated');

        return response()->json('Successfully item is updated');
    }

    // delete product
    public function delete(Request $request, $productId)
    {
        $deleteProduct = Product::with(
            [
                'productImages',
                'variants',
                'purchaseProducts',
                'saleProducts',
                'orderedProducts',
                'productions',
                'processes',
                'processIngredients',
                'transfer_to_branch_products',
                'transfer_to_warehouse_products',
                'weights',
            ]
        )->where('id', $productId)->first();

        if (count($deleteProduct->purchaseProducts) > 0) {

            return response()->json(['errorMsg' => 'Item can\'t be deleted. This item associated with purchase.']);
        }

        if (count($deleteProduct->saleProducts) > 0) {

            return response()->json(['errorMsg' => 'Item can\'t be deleted. This item associated with sales.']);
        }

        if (count($deleteProduct->orderedProducts) > 0) {

            return response()->json(['errorMsg' => 'Item can\'t be deleted. This item associated with purchase order.']);
        }

        if (count($deleteProduct->productions) > 0) {

            return response()->json(['errorMsg' => 'Item can\'t be deleted. This item associated with manufacturing production.']);
        }

        if (count($deleteProduct->processes) > 0) {

            return response()->json(['errorMsg' => 'Item can\'t be deleted. This item associated with manufacturing process.']);
        }

        if (count($deleteProduct->processIngredients) > 0) {

            return response()->json(['errorMsg' => 'Item can\'t be deleted. This item associated with manufacturing process ingredients.']);
        }

        if (count($deleteProduct->transfer_to_branch_products) > 0) {

            return response()->json(['errorMsg' => 'Item can\'t be deleted. This item associated with Transfer Stock Warehouse To Business Location.']);
        }

        if (count($deleteProduct->transfer_to_warehouse_products) > 0) {

            return response()->json(['errorMsg' => 'Item can\'t be deleted. This item associated with Transfer Stock Business Location To warehouse.']);
        }

        if (count($deleteProduct->weights) > 0) {

            return response()->json(['errorMsg' => 'Item can\'t be deleted. This item associated with weight scale.']);
        }

        try {

            DB::beginTransaction();

            $deleteProduct = $this->productUtil->deleteProduct($deleteProduct);

            if ($deleteProduct) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 26, data_obj: $deleteProduct);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        DB::statement('ALTER TABLE products AUTO_INCREMENT = 1');

        return response()->json('Item deleted successfully');
    }

    // multiple delete method
    public function multipleDelete(Request $request)
    {
        if ($request->data_ids == null) {

            return response()->json(['errorMsg' => 'You did not select any item.']);
        }

        if ($request->action == 'multiple_delete') {

            //     foreach($request->data_ids as $data_id){
            //         $deleteProduct = Product::with(['product_images', 'variants'])->where('id', $data_id)->get();
            //         if (!is_null($deleteProduct)) {
            //             if ($deleteProduct->thumbnail_photo) {
            //                 if (file_exists(public_path('uploads/product/thumbnail/'.$deleteProduct->thumbnail_photo))) {
            //                     unlink(public_path('uploads/product/thumbnail/'.$deleteProduct->thumbnail_photo));
            //                 }
            //             }

            //             if($deleteProduct->product_images->count() > 0){
            //                 foreach($deleteProduct->product_images as $product_image){
            //                     if (file_exists(public_path('uploads/product/'.$product_image->image))) {
            //                         unlink(public_path('uploads/product/'.$product_image->image));
            //                     }
            //                 }
            //             }

            //             if($deleteProduct->variants->count() > 0){
            //                 foreach($deleteProduct->variants as $variant){
            //                     if($variant->variant_image){
            //                         if (file_exists(public_path('uploads/product/variant_image/'.$variant->variant_image))) {
            //                             unlink(public_path('uploads/product/variant_image/'.$variant->variant_image));
            //                         }
            //                     }
            //                 }
            //             }
            //             $deleteProduct->delete();
            //         }
            //     }

            return response()->json('Multiple delete feature is disabled in this demo');
        } elseif ($request->action == 'multipla_deactive') {

            foreach ($request->data_ids as $data_id) {

                $product = Product::where('id', $data_id)->first();
                $product->status = 0;
                $product->save();
            }

            return response()->json('Successfully all selected item status deactivated');
        }
    }

    // Change product status method
    public function changeStatus($productId)
    {
        $statusChange = Product::where('id', $productId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json('Successfully item is deactivated');
        } else {

            $statusChange->status = 1;
            $statusChange->save();

            return response()->json('Successfully item is activated');
        }
    }

    //Get all form variant by ajax request
    public function getAllFormVariants()
    {
        $variants = BulkVariant::with(['bulk_variant_child'])->get();

        return response()->json($variants);
    }

    public function searchProduct($productCode)
    {
        $product = Product::with(['variant', 'tax', 'unit'])->where('product_code', $productCode)->first();
        if ($product) {

            return response()->json(['product' => $product]);
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $productCode)->first();

            return response()->json(['variant_product' => $variant_product]);
        }
    }

    public function chackPurchaseAndGenerateBarcode($productId)
    {
        $supplierProducts = SupplierProduct::where('product_id', $productId)->get();

        if ($supplierProducts->count() > 0) {

            return response()->json(route('products.generate.product.barcode', $productId));
        } else {

            return response()->json(['errorMsg' => 'This item yet to be purchased.']);
        }
    }

    // Add Category from add product
    public function addCategory(Request $request)
    {
        return $this->productUtil->addQuickCategory($request);
    }

    public function getFormPart($type)
    {
        $type = $type;
        $variants = BulkVariant::with(['bulk_variant_child'])->get();
        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        return view('inventories.products.ajax_view.form_part', compact('type', 'variants', 'taxAccounts'));
    }

    // Get product variants
    public function getProductVariants($productId)
    {
        return $variants = DB::table('product_variants')->where('product_id', $productId)->orderBy('variant_code', 'asc')->get();

        return response()->json($variants);
    }

    public function getComboProducts($productId)
    {
        $comboProducts = ComboProduct::with(['parentProduct', 'parentProduct.tax', 'product_variant'])->where('product_id', $productId)->get();

        return response()->json($comboProducts);
    }
}
