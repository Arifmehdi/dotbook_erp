<?php

namespace App\Http\Controllers;

use App\Models\BarcodeSetting;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarcodeController extends Controller
{
    public function __construct()
    {

    }

    // Generate barcode index view
    public function index()
    {
        if (! auth()->user()->can('generate_barcode')) {
            abort(403, 'Access Forbidden.');
        }

        $bc_settings = DB::table('barcode_settings')->orderBy('is_continuous', 'desc')->get(['id', 'name', 'is_default']);

        return view('inventories.barcode.index_v2', compact('bc_settings'));
    }

    public function preview(Request $request)
    {
        $req = $request;
        if (! isset($req->product_ids)) {
            session()->flash('errorMsg', 'Product list is empty.');

            return redirect()->back();
        }

        $br_setting = BarcodeSetting::where('id', $request->br_setting_id)->first();

        return view('inventories.barcode.preview', compact('br_setting', 'req'));
    }

    // Get all supplier products
    public function supplierProduct()
    {
        $supplier_products = SupplierProduct::with(['supplier', 'product', 'product.tax', 'variant'])
            ->where('label_qty', '>', 0)
            ->get();

        return view('inventories.barcode.ajax_view.purchase_product_list', compact('supplier_products'));
    }

    public function multipleGenerateCompleted(Request $request)
    {
        //return $request->all();
        $index = 0;
        foreach ($request->product_ids as $product_id) {
            $variant_id = $request->product_variant_ids[$index] != 'null' ? $request->product_variant_ids[$index] : null;
            $supplierProduct = SupplierProduct::where('supplier_id', $request->supplier_ids[$index])
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->first();
            if ($supplierProduct) {
                $supplierProduct->label_qty = 0;
                $supplierProduct->save();
            }
            $index++;
        }

        return response()->json(['Successfully completed barcode row is deleted.']);
    }

    // Search product
    public function searchProduct($searchKeyword)
    {
        $products = Product::with(['productPurchasedVariants'])
            ->where('name', 'like', $searchKeyword.'%')
            ->where('is_purchased', 1)->select(
                'id',
                'name',
                'product_code',
                'is_combo',
                'is_featured',
                'is_for_sale',
                'is_manage_stock',
                'is_purchased',
                'is_variant',
                'offer_price',
                'product_cost',
                'product_cost_with_tax',
                'product_price',
                'profit',
                'quantity',
                'tax_ac_id',
                'tax_type',
                'type',
                'unit_id',
            )->limit(25)
            ->get();
        if ($products->count() > 0) {
            return response()->json($products);
        } else {
            $products = Product::with(['productPurchasedVariants'])
                ->where('product_code', $searchKeyword)
                ->where('is_purchased', 1)
                ->get();

            return response()->json($products);
        }
    }

    // Get selected product
    public function getSelectedProduct($productId)
    {
        $supplierProducts = SupplierProduct::with('supplier', 'product', 'product.tax', 'variant')->where('product_id', $productId)->get();

        return response()->json($supplierProducts);
    }

    // Get selected product variant
    public function getSelectedProductVariant($productId, $variantId)
    {
        $supplierProducts = SupplierProduct::with(
            'supplier',
            'product',
            'product.tax',
            'variant'
        )->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->get();

        return response()->json($supplierProducts);
    }

    // Generate specific product barcode view
    public function genrateProductBarcode($productId)
    {
        $productId = $productId;
        $bc_settings = DB::table('barcode_settings')->orderBy('is_continuous', 'desc')->get(['id', 'name', 'is_default']);

        return view('inventories.barcode.specific_product_barcode', compact('productId', 'bc_settings'));
    }

    // Get specific product's supplier product
    public function getSpacificSupplierProduct($productId)
    {
        $supplierProducts = SupplierProduct::with('supplier', 'product', 'product.tax', 'variant')->where('product_id', $productId)->get();

        return response()->json($supplierProducts);
    }

    // Generate barcode on purchase view
    public function onPurchaseBarcode($purchaseId)
    {
        $purchaseId = $purchaseId;
        $bc_settings = DB::table('barcode_settings')->orderBy('is_continuous', 'desc')->get(['id', 'name', 'is_default']);

        return view('inventories.barcode.purchase_product_barcode_v2', compact('purchaseId', 'bc_settings'));
    }

    // Get purchase products for generating barcode
    public function getPurchaseProduct($purchaseId)
    {
        $purchaseProducts = PurchaseProduct::with(['purchase', 'purchase.supplier', 'product', 'variant'])->where('purchase_id', $purchaseId)->get();

        return response()->json($purchaseProducts);
    }
}
