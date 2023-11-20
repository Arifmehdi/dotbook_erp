<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class NameSearchUtil
{
    public function nameSearching($keyword, $isShowNotForSaleItem = true, $recentPriceTime = null)
    {
        $namedProducts = '';

        $query = DB::table('product_branches')
            ->where('products.status', 1)
            ->where('products.name', 'LIKE', '%'.$keyword.'%');

        if ($isShowNotForSaleItem == false) {

            $query->where('is_for_sale', 1);
        }

        $namedProducts = $query->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('accounts as taxes', 'products.tax_ac_id', 'taxes.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->select(
                'product_branches.product_quantity as p_qty',
                'product_branch_variants.variant_quantity as v_qty',
                'products.id',
                'products.name',
                'products.product_code',
                'products.is_combo',
                'products.is_manage_stock',
                // 'products.is_purchased',
                'products.is_show_emi_on_pos',
                'products.is_variant',
                'products.product_cost',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.profit',
                'products.quantity',
                'products.tax_ac_id',
                'products.tax_type',
                'products.thumbnail_photo',
                'products.type',
                'products.unit_id',
                'taxes.name as tax_name',
                'taxes.tax_percent',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_profit',
                'product_variants.variant_price',
                'product_variants.variant_quantity',
                'units.name as unit_name',
            )
            // ->where('products.name', 'LIKE',  $keyword . '%')->orderBy('id', 'desc')->limit(25)
            ->orderBy('products.name', 'asc')->limit(25)
            ->get();

        if ($recentPriceTime) {

            foreach ($namedProducts as $product) {

                $recentPrice = DB::table('recent_prices')->where('product_id', $product->id)->where('variant_id', $product->variant_id)
                    ->where('start_time', '<=', $recentPriceTime)
                    ->where('end_time', '>=', $recentPriceTime)->orderBy('id', 'desc')->first();

                if ($recentPrice) {

                    if ($product->variant_name) {

                        $product->variant_price = $recentPrice->new_price;
                    } else {

                        $product->product_price = $recentPrice->new_price;
                    }
                }
            }
        }

        if ($namedProducts && count($namedProducts) > 0) {

            return response()->json(['namedProducts' => $namedProducts]);
        } else {

            return response()->json(['NotFoundMsg' => 'Not Found.']);
        }
    }

    public function searchStockToBranch($product, $product_code, $status = null, $is_allowed_discount = false, $price_group_id = null, $isCheckStock = true, $isShowNotForSaleItem = true, $recentPriceTime = null)
    {
        if ($product) {

            $this->checkRecentPrice($product, $recentPriceTime);

            if ($product->is_manage_stock == 0) {

                return response()->json(
                    [
                        'product' => $product,
                        'qty_limit' => PHP_INT_MAX,
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    ]
                );
            }

            $productBranch = DB::table('product_branches')
                ->where('product_id', $product->id)
                ->select('product_quantity')
                ->first();

            if ($productBranch) {

                if ($status == 2 || $status == 3 || $status == 4 || $status == 7) {

                    return response()->json(
                        [
                            'product' => $product,
                            'qty_limit' => $productBranch ? $productBranch->product_quantity : 0,
                            'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        ]
                    );
                }

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo item is not sellable in this demo']);
                } else {

                    if ($productBranch->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productBranch->product_quantity,
                                'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this item in the business Location']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This item is not available in the business Location.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'updateVariantCost', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->select([
                    'id',
                    'product_id',
                    'variant_name',
                    'variant_code',
                    'variant_quantity',
                    'variant_cost',
                    'variant_cost_with_tax',
                    'variant_profit',
                    'variant_price',
                ])->first();

            if ($variant_product) {

                if ($variant_product->product->is_manage_stock == 0) {

                    return response()->json([
                        'variant_product' => $variant_product,
                        'qty_limit' => PHP_INT_MAX,
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                    ]);
                }

                if ($variant_product) {

                    $productBranch = DB::table('product_branches')->where('product_id', $variant_product->product_id)->first();

                    if (is_null($productBranch)) {

                        return response()->json(['errorMsg' => 'This item is not available in the business Location']);
                    }

                    $productBranchVariant = DB::table('product_branch_variants')
                        ->where('product_branch_id', $productBranch->id)
                        ->where('product_id', $variant_product->product_id)
                        ->where('product_variant_id', $variant_product->id)
                        ->select('variant_quantity')
                        ->first();

                    if (is_null($productBranchVariant)) {

                        return response()->json(['errorMsg' => 'This variant is not available in the business Location.']);
                    }

                    if ($productBranch && $productBranchVariant) {

                        if ($status == 2 || $status == 3 || $status == 4 || $status == 7) {

                            return response()->json(
                                [
                                    'variant_product' => $variant_product,
                                    'qty_limit' => $productBranchVariant ? $productBranchVariant->variant_quantity : 0,
                                    'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                                ]
                            );
                        }

                        if ($productBranchVariant->variant_quantity > 0) {

                            return response()->json([
                                'variant_product' => $variant_product,
                                'qty_limit' => $productBranchVariant->variant_quantity,
                                'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                            ]);
                        } else {

                            return response()->json(['errorMsg' => 'Stock is out of this item(variant) from the business Location.']);
                        }
                    } else {

                        return response()->json(['errorMsg' => 'This item is not available in this Location/Shop.']);
                    }
                }
            }
        }

        return $this->nameSearching($product_code, $isShowNotForSaleItem, $recentPriceTime);
    }

    public function addSaleSearchStockToWarehouse($product, $product_code, $warehouse_id, $status = null, $is_allowed_discount = false, $price_group_id = null, $isCheckStock = true, $isShowNotForSaleItem = true, $recentPriceTime = null)
    {
        if ($product) {

            $this->checkRecentPrice($product, $recentPriceTime);

            if ($product->is_manage_stock == 0) {

                return response()->json(
                    [
                        // 'pricePriority' => 'recent_price',
                        'product' => $product,
                        'qty_limit' => PHP_INT_MAX,
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    ]
                );
            }

            $productWarehouse = DB::table('product_warehouses')
                ->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product->id)
                ->select('product_quantity')
                ->first();

            if ($productWarehouse) {

                if ($status == 2 || $status == 3 || $status == 4 || $status == 7) {

                    return response()->json(
                        [
                            'product' => $product,
                            'qty_limit' => $productWarehouse ? $productWarehouse->product_quantity : 0,
                            'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        ]
                    );
                }

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo item is not sellable in this demo']);
                } else {

                    if ($productWarehouse->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productWarehouse->product_quantity,
                                'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this item from the selected warehouse']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This item is not available in the selected warehouse.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'updateVariantCost', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->select([
                    'id',
                    'product_id',
                    'variant_name',
                    'variant_code',
                    'variant_quantity',
                    'variant_cost',
                    'variant_cost_with_tax',
                    'variant_profit',
                    'variant_price',
                ])->first();

            if ($variant_product) {

                if ($variant_product->product->is_manage_stock == 0) {

                    return response()->json([
                        'variant_product' => $variant_product,
                        'qty_limit' => PHP_INT_MAX,
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                    ]);
                }

                if ($variant_product) {

                    $productWarehouse = DB::table('product_warehouses')
                        ->where('warehouse_id', $warehouse_id)
                        ->where('product_id', $variant_product->product_id)
                        ->first();

                    if (is_null($productWarehouse)) {

                        return response()->json(['errorMsg' => 'This item is not available in the selected warehouse']);
                    }

                    $productWarehouseVariant = DB::table('product_warehouse_variants')
                        ->where('product_warehouse_id', $productWarehouse->id)
                        ->where('product_id', $variant_product->product_id)
                        ->where('product_variant_id', $variant_product->id)
                        ->select('variant_quantity')
                        ->first();

                    if (is_null($productWarehouseVariant)) {

                        return response()->json(['errorMsg' => 'Item variant is not available in the selected warehouse']);
                    }

                    if ($productWarehouse && $productWarehouseVariant) {

                        if ($status == 2 || $status == 3 || $status == 4 || $status == 7) {

                            return response()->json(
                                [
                                    'variant_product' => $variant_product,
                                    'qty_limit' => $productWarehouseVariant ? $productWarehouseVariant->variant_quantity : 0,
                                    'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                                ]
                            );
                        }

                        if ($productWarehouseVariant->variant_quantity > 0) {

                            return response()->json([
                                'variant_product' => $variant_product,
                                'qty_limit' => $productWarehouseVariant->variant_quantity,
                                'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                            ]);
                        } else {

                            return response()->json(['errorMsg' => 'Stock is out of this item variant in the selected warehouse']);
                        }
                    } else {

                        return response()->json(['errorMsg' => 'Item is not available in the selected warehouse.']);
                    }
                }
            }
        }

        return $this->nameSearching($product_code, $isShowNotForSaleItem, $recentPriceTime);
    }

    public function checkBranchSingleProductStock($product_id, $status = null, $is_allowed_discount = false, $price_group_id = null)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock', 'brand_id', 'category_id', 'quantity')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json(
                [
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => PHP_INT_MAX,
                ]
            );
        }

        $productBranch = DB::table('product_branches')->where('product_id', $product_id)->first();

        if ($productBranch) {

            if ($status == 2 || $status == 3 || $status == 4 || $status == 7) {

                return response()->json([
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => $productBranch->product_quantity,
                    'all_stock' => $product->quantity,
                ]);
            }

            if ($productBranch->product_quantity > 0) {

                return response()->json([
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => $productBranch->product_quantity,
                    'all_stock' => $product->quantity,
                ]);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this item from the business location.']);
            }
        } else {

            return response()->json(['errorMsg' => 'This item is not available in the business location.']);
        }
    }

    public function checkBranchVariantProductStock($product_id, $variant_id, $status = null, $is_allowed_discount = false, $price_group_id = null)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock', 'brand_id', 'category_id', 'quantity')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json([
                'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                'stock' => PHP_INT_MAX,
            ]);
        }

        $productBranch = DB::table('product_branches')->where('product_id', $product_id)->first();

        if ($productBranch) {

            $productBranchVariant = DB::table('product_branch_variants')
                ->where('product_branch_id', $productBranch->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($productBranchVariant) {

                if ($status == 2 || $status == 3 || $status == 4 || $status == 7) {

                    return response()->json([
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        'stock' => $productBranchVariant->variant_quantity,
                        'all_stock' => $product->quantity,
                    ]);
                }

                if ($productBranchVariant->variant_quantity > 0) {

                    return response()->json([
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        'stock' => $productBranchVariant->variant_quantity,
                        'all_stock' => $product->quantity,
                    ]);
                } else {

                    return response()->json(['errorMsg' => 'Stock is out of this item(variant) from the Business Location']);
                }
            } else {

                return response()->json(['errorMsg' => 'This variant is not available in the Business Location.']);
            }
        } else {

            return response()->json(['errorMsg' => 'This item is not available in the Business Location.']);
        }
    }

    public function checkAddSaleWarehouseSingleProductStock($product_id, $warehouse_id, $status = null, $is_allowed_discount = false, $price_group_id = null)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock', 'brand_id', 'category_id', 'quantity')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json(
                [
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => PHP_INT_MAX,
                ]
            );
        }

        $productWarehouse = DB::table('product_warehouses')
            ->where('product_id', $product_id)
            ->where('warehouse_id', $warehouse_id)->first();

        if ($productWarehouse) {

            if ($status == 2 || $status == 3 || $status == 4 || $status == 7) {

                return response()->json([
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => $productWarehouse->product_quantity,
                    'all_stock' => $product->quantity,
                ]);
            }

            if ($productWarehouse->product_quantity > 0) {

                return response()->json([
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => $productWarehouse->product_quantity,
                    'all_stock' => $product->quantity,
                ]);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this item in the selected warehouse.']);
            }
        } else {

            return response()->json(['errorMsg' => 'This item is not available in the selected warehouse.']);
        }
    }

    public function checkAddSaleWarehouseVariantProductStock($product_id, $variant_id, $warehouse_id, $status = null, $is_allowed_discount = false, $price_group_id = null)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock', 'brand_id', 'category_id', 'quantity')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json([
                'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                'stock' => PHP_INT_MAX,
            ]);
        }

        $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();

        if ($productWarehouse) {

            $productWarehouseVariant = DB::table('product_warehouse_variants')
                ->where('product_warehouse_id', $productWarehouse->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($productWarehouseVariant) {

                if ($status == 2 || $status == 3 || $status == 4 || $status == 7) {

                    return response()->json([
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        'stock' => $productWarehouseVariant->variant_quantity,
                        'all_stock' => $product->quantity,
                    ]);
                }

                if ($productWarehouseVariant->variant_quantity > 0) {

                    return response()->json([
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        'stock' => $productWarehouseVariant->variant_quantity,
                        'all_stock' => $product->quantity,
                    ]);
                } else {

                    return response()->json(['errorMsg' => 'Stock is out of this item(variant) in the selected warehouse.']);
                }
            } else {

                return response()->json(['errorMsg' => 'This variant is not available in the selected warehouse..']);
            }
        } else {

            return response()->json(['errorMsg' => 'This item is not available in the selected warehouse.']);
        }
    }

    public function checkWarehouseSingleProduct($product_id, $warehouse_id)
    {
        $productWarehouse = DB::table('product_warehouses')->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->first();

        if ($productWarehouse) {

            if ($productWarehouse->product_quantity > 0) {

                return response()->json($productWarehouse->product_quantity);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this item from this warehouse']);
            }
        } else {

            return response()->json(['errorMsg' => 'This item is not available in this warehouse.']);
        }
    }

    // Check warehouse product variant qty
    public function checkWarehouseProductVariant($product_id, $variant_id, $warehouse_id)
    {
        $productWarehouse = DB::table('product_warehouses')
            ->where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->first();

        if (is_null($productWarehouse)) {

            return response()->json(['errorMsg' => 'This item is not available in this warehouse.']);
        }

        $productWarehouseVariant = DB::table('product_warehouse_variants')
            ->where('product_warehouse_id', $productWarehouse->id)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)
            ->first();

        if (is_null($productWarehouseVariant)) {

            return response()->json(['errorMsg' => 'This variant is not available in this warehouse.']);
        }

        if ($productWarehouse && $productWarehouseVariant) {

            if ($productWarehouseVariant->variant_quantity > 0) {

                return response()->json($productWarehouseVariant->variant_quantity);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse.']);
            }
        } else {

            return response()->json(['errorMsg' => 'This variant is not available in this shop.']);
        }
    }

    public function searchStockToWarehouse($product, $product_code, $warehouse_id)
    {
        if ($product) {

            $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product->id)
                ->first();

            if ($productWarehouse) {

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo item is not transferable.']);
                } else {

                    if ($productWarehouse->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productWarehouse->product_quantity,
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this item of this warehouse']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This item is not available in this warehouse.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->first();

            if ($variant_product) {

                $productWarehouse = DB::table('product_warehouses')
                    ->where('warehouse_id', $warehouse_id)
                    ->where('product_id', $variant_product->product_id)
                    ->first();

                if (is_null($productWarehouse)) {

                    return response()->json(['errorMsg' => 'This item is not available in the selected warehouse']);
                }

                $productWarehouseVariant = DB::table('product_warehouse_variants')
                    ->where('product_warehouse_id', $productWarehouse->id)
                    ->where('product_id', $variant_product->product_id)
                    ->where('product_variant_id', $variant_product->id)
                    ->first();

                if (is_null($productWarehouseVariant)) {

                    return response()->json(['errorMsg' => 'This variant is not available in this warehouse']);
                }

                if ($productWarehouse && $productWarehouseVariant) {

                    if ($productWarehouseVariant->variant_quantity > 0) {

                        return response()->json(
                            [
                                'variant_product' => $variant_product,
                                'qty_limit' => $productWarehouseVariant->variant_quantity,
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this item(variant) in the selected warehouse']);
                    }
                } else {

                    return response()->json(['errorMsg' => 'This item is not available in this warehouse.']);
                }
            }
        }

        return $this->nameSearching($product_code);
    }

    public function productDiscount($product_id, $price_group_id, $brand_id, $category_id)
    {
        $presentDate = date('Y-m-d');

        $__price_group_id = $price_group_id != 'no_id' ? $price_group_id : null;
        $__category_id = $category_id ? $category_id : null;
        $__brand_id = $brand_id ? $brand_id : null;

        $discountProductWise = DB::table('discount_products')
            ->where('discount_products.product_id', $product_id)
            ->leftJoin('discounts', 'discount_products.discount_id', 'discounts.id')
            ->where('discounts.is_active', 1)
            ->where('discounts.price_group_id', $__price_group_id)
            ->whereRaw('"'.$presentDate.'" between `start_at` and `end_at`')
            ->orderBy('discounts.priority', 'desc')
            ->first();

        if ($discountProductWise) {

            return $this->setDiscount($discountProductWise);
        }

        $discountBrandCategoryWise = '';
        $discountBrandCategoryWiseQ = DB::table('discounts')
            ->where('discounts.is_active', 1)
            //->where('discounts.price_group_id', $__price_group_id)
            ->whereRaw('"'.$presentDate.'" between `start_at` and `end_at`');

        if ($__brand_id && $__category_id) {

            $discountBrandCategoryWiseQ->where('discounts.category_id', '!=', null);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', '!=', null);
            $discountBrandCategoryWiseQ->where('discounts.category_id', $__category_id);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', $__brand_id);
        } elseif ($__brand_id && ! $__category_id) {

            $discountBrandCategoryWiseQ->where('discounts.category_id', null);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', '!=', null);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', $__brand_id);
        } elseif (! $__brand_id && $__category_id) {

            $discountBrandCategoryWiseQ->where('discounts.brand_id', null);
            $discountBrandCategoryWiseQ->where('discounts.category_id', '!=', null);
            $discountBrandCategoryWiseQ->where('discounts.category_id', $__category_id);
        }

        $discountBrandCategoryWise = $discountBrandCategoryWiseQ
            ->select('discounts.discount_type', 'discounts.discount_amount', 'discounts.apply_in_customer_group')
            ->orderBy('discounts.priority', 'desc')
            ->first();

        return $this->setDiscount($discountBrandCategoryWise);

        // if (!$discountBrandCategoryWise) {

        //     return $this->setDiscount(NULL);
        // }

        // if ($discountBrandCategoryWise->brand_id && $discountBrandCategoryWise->category_id) {

        //     if ($discountBrandCategoryWise->brand_id == $__brand_id && $discountBrandCategoryWise->category_id == $__category_id) {

        //         return $this->setDiscount($discountBrandCategoryWise);
        //     }
        // } elseif (!$discountBrandCategoryWise->brand_id && $discountBrandCategoryWise->category_id) {

        //     if ($discountBrandCategoryWise->category_id == $__category_id) {

        //         return $this->setDiscount($discountBrandCategoryWise);
        //     }
        // } elseif ($discountBrandCategoryWise->brand_id && !$discountBrandCategoryWise->category_id) {

        //     if ($discountBrandCategoryWise->brand_id == $__brand_id) {

        //         return $this->setDiscount($discountBrandCategoryWise);
        //     }
        // }
    }

    public function setDiscount($discount)
    {
        $discountDetails = [];
        $discountDetails['discount_type'] = isset($discount->discount_type) ? $discount->discount_type : 1;
        $discountDetails['discount_amount'] = isset($discount->discount_amount) ? $discount->discount_amount : 0;
        //$discountDetails['apply_in_customer_group'] = isset($discount->apply_in_customer_group) ? $discount->apply_in_customer_group : 0;

        return $discountDetails;
    }

    public function checkRecentPrice($product, $recentPriceTime)
    {
        $__recentPriceTime = $recentPriceTim ?? date('Y-m-d H:i:s');
        if (count($product->variants) > 0) {

            foreach ($product->variants as $variant) {

                $recentPrice = DB::table('recent_prices')->where('product_id', $variant->product_id)->where('variant_id', $variant->id)
                    ->where('start_time', '<=', $__recentPriceTime)
                    ->where('end_time', '>=', $__recentPriceTime)->orderBy('id', 'desc')->first();

                if ($recentPrice) {

                    $variant->variant_price = $recentPrice->new_price;
                }
            }
        } else {

            $recentPrice = DB::table('recent_prices')->where('recent_prices.product_id', $product->id)
                ->where('recent_prices.start_time', '<=', $__recentPriceTime)
                ->where('recent_prices.end_time', '>=', $__recentPriceTime)->orderBy('recent_prices.id', 'desc')->first();

            if ($recentPrice) {

                $product->product_price = $recentPrice->new_price;
            }
        }
    }
}
