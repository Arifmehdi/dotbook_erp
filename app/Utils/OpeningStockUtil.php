<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductOpeningStock;
use App\Models\ProductVariant;

class OpeningStockUtil
{
    public function saveAddOrEditOpeningStock($productId, $variantId, $unitCostIncTax, $quantity, $subtotal, $warehouseId = null)
    {
        $openingStock = ProductOpeningStock::with([
            'product',
            'product.tax',
            'product.purchaseProducts',
            'product.dailyStockProducts',
            'product.productions',
            'variant',
            'variant.purchaseVariants',
            'variant.dailyStockVariants',
            'variant.productionVariants',
        ])->where('product_id', $productId)->where('product_variant_id', $variantId)->first();

        $storePreviousWarehouseId = '';

        $addOrEditOpeningStock = '';
        $product = '';
        $variant = '';

        if ($openingStock) {

            $addOrEditOpeningStock = $openingStock;
            $storePreviousWarehouseId = $openingStock->warehouse_id;

            if ($variantId) {

                $product = $openingStock->product;
                $variant = $openingStock->variant;
            } else {

                $product = $openingStock->product;
            }
        } else {

            $addOrEditOpeningStock = new ProductOpeningStock();

            if ($variantId) {

                $product = Product::with(['tax'])->where('id', $productId)->first();
                $variant = ProductVariant::with(['tax'])->where('id', $variantId)->where('product_id', $productId)->first();
            } else {

                $product = $product = Product::with(['tax'])->where('id', $productId)->first();
            }
        }

        $addOrEditOpeningStock->warehouse_id = $warehouseId;
        $addOrEditOpeningStock->product_id = $productId;
        $addOrEditOpeningStock->product_variant_id = $variantId;
        $addOrEditOpeningStock->unit_cost_inc_tax = $unitCostIncTax;
        $addOrEditOpeningStock->quantity = $quantity;
        $addOrEditOpeningStock->subtotal = $subtotal;
        $addOrEditOpeningStock->save();
        $addOrEditOpeningStock->previous_warehouse_id = $storePreviousWarehouseId;

        if ($variantId) {

            $this->updateVariantCost($product, $variant, $unitCostIncTax);
        } else {

            $this->updateProductCost($product, $unitCostIncTax);
        }

        return $addOrEditOpeningStock;
    }

    private function updateProductCost($product, $unitCostIncTax)
    {
        $deductedTaxAmount = 0;
        if ($product->tax) {

            $taxPercent = $product?->tax?->tax_percent ? $product?->tax?->tax_percent : 0;
            $deductedTaxAmount = ($unitCostIncTax / (100 + $taxPercent)) * $taxPercent;
        }

        if ($product->product_cost == 0 || $product->product_cost == null) {

            $product->product_cost = $unitCostIncTax - $deductedTaxAmount;
            $product->product_cost_with_tax = $unitCostIncTax;
            $product->save();
        } else {

            if (count($product->purchaseProducts) == 0 && count($product->dailyStockProducts) == 0 && count($product->productions) == 0) {

                $product->product_cost = $unitCostIncTax - $deductedTaxAmount;
                $product->product_cost_with_tax = $unitCostIncTax;
                $product->save();
            }
        }
    }

    private function updateVariantCost($product, $variant, $unitCostIncTax)
    {
        $deductedTaxAmount = 0;
        if ($product->tax) {

            $taxPercent = $product?->tax?->tax_percent ? $product?->tax?->tax_percent : 0;
            $deductedTaxAmount = ($unitCostIncTax / (100 + $taxPercent)) * $taxPercent;
        }

        if ($variant->variant_cost == 0 || $variant->variant_cost == null) {

            $variant->variant_cost = $unitCostIncTax - $deductedTaxAmount;
            $variant->variant_cost_with_tax = $unitCostIncTax;
            $variant->save();
        } else {

            if (count($variant->purchaseVariants) != 0 || count($variant->dailyStockVariants) != 0 || count($variant->productionVariants) != 0) {

                $variant->variant_cost = $unitCostIncTax - $deductedTaxAmount;
                $variant->variant_cost_with_tax = $unitCostIncTax;
                $variant->save();
            }
        }
    }
}
