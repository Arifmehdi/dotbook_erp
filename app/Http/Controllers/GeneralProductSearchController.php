<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Utils\GeneralProductSearchUtil;
use Illuminate\Support\Facades\DB;

class GeneralProductSearchController extends Controller
{
    protected $generalProductSearchUtil;

    public function __construct(GeneralProductSearchUtil $generalProductSearchUtil)
    {
        $this->generalProductSearchUtil = $generalProductSearchUtil;
    }

    public function commonSearch($keyWord, $isShowNotForSaleItem = 1, $priceGroupId = null, $type = null, $saleId = null)
    {
        $recentPriceTime = $type == 'sales' || $type == 'sales_order' || $type == 'quotation' ? date('Y-m-d H:i:s') : null;

        if ($saleId) {

            $sale = DB::table('sales')->where('id', $saleId)->select('order_date', 'report_date', 'quotation_date')->first();
            $recentPriceTime = $type == 'sales' ? $sale->report_date : date('Y-m-d H:i:s');
            $recentPriceTime = $type == 'sales_order' ? $sale->order_date : date('Y-m-d H:i:s');
            $recentPriceTime = $type == 'quotation' ? $sale->quotation_date : date('Y-m-d H:i:s');
        }

        $keyWord = (string) $keyWord;
        $__keyWord = str_replace('~', '/', $keyWord);
        $__priceGroupId = ($priceGroupId && $priceGroupId != 'no_id') ? $priceGroupId : null;

        $product = Product::with([
            'variants',
            'variants.updateVariantCost',
            'tax:id,tax_percent',
            'unit:id,name,code_name',
            'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'updateProductCost',
        ])->where('product_code', $__keyWord)->select([
            'id',
            'name',
            'type',
            'product_code',
            'product_price',
            'profit',
            'product_cost',
            'product_cost_with_tax',
            'thumbnail_photo',
            'category_id',
            'brand_id',
            'unit_id',
            'tax_ac_id',
            'tax_type',
            'quantity',
            'is_show_emi_on_pos',
            'is_manage_stock',
            'is_for_sale',
        ])->first();

        return $this->generalProductSearchUtil->getProductByKeyword(product: $product, keyWord: $__keyWord, priceGroupId: $__priceGroupId, isShowNotForSaleItem: $isShowNotForSaleItem, recentPriceTime: $recentPriceTime);
    }

    public function checkProductDiscount($productId, $priceGroupId)
    {
        return $this->generalProductSearchUtil->getProductDiscountById($productId, $priceGroupId);
    }

    public function checkProductDiscountWithStock($productId, $variantId, $priceGroupId)
    {
        return $this->generalProductSearchUtil->getProductDiscountByIdWithAvailableStock($productId, $variantId, $priceGroupId);
    }

    public function singleProductStock($productId, $warehouseId = null)
    {
        if ($warehouseId) {

            return $this->generalProductSearchUtil->singleProductWarehouseStock($productId, $warehouseId);
        } else {

            return $this->generalProductSearchUtil->singleProductCompanyStock($productId);
        }
    }

    public function variantProductStock($productId, $variantId, $warehouseId = null)
    {
        if ($warehouseId) {

            return $this->generalProductSearchUtil->variantProductWarehouseStock($productId, $variantId, $warehouseId);
        } else {

            return $this->generalProductSearchUtil->variantProductCompanyStock($productId, $variantId);
        }
    }

    public function productUnitAndMultiplierUnit($productId)
    {
        return $this->generalProductSearchUtil->getProductUnitAndMultiplierUnit($productId);
    }
}
