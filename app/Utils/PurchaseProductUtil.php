<?php

namespace App\Utils;

use App\Models\PurchaseProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseProductUtil
{
    public function addPurchaseProduct($request, $purchaseId, $isEditProductPrice, $index)
    {
        $addPurchaseProduct = new PurchaseProduct();
        $addPurchaseProduct->purchase_id = $purchaseId;
        $addPurchaseProduct->product_id = $request->product_ids[$index];
        $addPurchaseProduct->product_variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addPurchaseProduct->description = $request->descriptions[$index];
        $addPurchaseProduct->quantity = $request->quantities[$index];
        $addPurchaseProduct->left_qty = $request->quantities[$index];
        $addPurchaseProduct->unit_id = $request->unit_ids[$index];
        $addPurchaseProduct->unit_cost = $request->unit_costs_exc_tax[$index];
        $addPurchaseProduct->unit_discount = $request->unit_discounts[$index];
        $addPurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addPurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
        $addPurchaseProduct->subtotal = $request->subtotals[$index];
        $addPurchaseProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addPurchaseProduct->tax_type = $request->tax_types[$index];
        $addPurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addPurchaseProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
        $addPurchaseProduct->line_total = $request->linetotals[$index];

        if ($isEditProductPrice == '1') {

            $addPurchaseProduct->profit_margin = $request->profits[$index];
            $addPurchaseProduct->selling_price = $request->selling_prices[$index];
        }

        if (isset($request->lot_number)) {

            $addPurchaseProduct->lot_no = $request->lot_numbers[$index];
        }

        $addPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addPurchaseProduct->save();

        return $addPurchaseProduct;
    }

    public function updatePurchaseProduct($request, $purchaseId, $isEditProductPrice, $index, $purchaseUtil = null)
    {
        $filterVariantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $updateOrAddPurchaseProduct = '';
        $purchaseProduct = PurchaseProduct::where('purchase_id', $purchaseId)->where('id', $request->purchase_product_ids[$index])->first();

        $currentUnitTaxAcId = $purchaseProduct ? $purchaseProduct->tax_ac_id : null;

        if ($purchaseProduct) {

            $updateOrAddPurchaseProduct = $purchaseProduct;
        } else {

            $updateOrAddPurchaseProduct = new PurchaseProduct();
        }

        $updateOrAddPurchaseProduct->purchase_id = $purchaseId;
        $updateOrAddPurchaseProduct->product_id = $request->product_ids[$index];
        $updateOrAddPurchaseProduct->product_variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $updateOrAddPurchaseProduct->description = $request->descriptions[$index];
        $updateOrAddPurchaseProduct->quantity = $request->quantities[$index];
        $updateOrAddPurchaseProduct->left_qty = $request->quantities[$index];
        $updateOrAddPurchaseProduct->unit_id = $request->unit_ids[$index];
        $updateOrAddPurchaseProduct->unit_cost = $request->unit_costs_exc_tax[$index];
        $updateOrAddPurchaseProduct->unit_discount = $request->unit_discounts[$index];
        $updateOrAddPurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $updateOrAddPurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
        $updateOrAddPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
        $updateOrAddPurchaseProduct->subtotal = $request->subtotals[$index];
        $updateOrAddPurchaseProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $updateOrAddPurchaseProduct->tax_type = $request->tax_types[$index];
        $updateOrAddPurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $updateOrAddPurchaseProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $updateOrAddPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
        $updateOrAddPurchaseProduct->line_total = $request->linetotals[$index];

        if ($isEditProductPrice == '1') {

            $updateOrAddPurchaseProduct->profit_margin = $request->profits[$index];
            $updateOrAddPurchaseProduct->selling_price = $request->selling_prices[$index];
        }

        if (isset($request->lot_numbers)) {

            $updateOrAddPurchaseProduct->lot_no = $request->lot_numbers[$index];
        }

        $updateOrAddPurchaseProduct->delete_in_update = 0;
        $updateOrAddPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $updateOrAddPurchaseProduct->save();

        $purchaseUtil->adjustPurchaseLeftQty($updateOrAddPurchaseProduct);

        return ['updateOrAddPurchaseProduct' => $updateOrAddPurchaseProduct, 'currentUnitTaxAcId' => $currentUnitTaxAcId];
    }

    public function purchaseProductListTable($request)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();

        $purchaseProducts = '';
        $query = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.parent_category_id', 'sub_cate.id');

        if ($request->product_id) {

            $query->where('purchase_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('purchase_products.product_variant_id', $request->variant_id);
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->sub_category_id) {

            $query->where('products.parent_category_id', $request->sub_category_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $query->select(
            'purchase_products.purchase_id',
            'purchase_products.product_id',
            'purchase_products.product_variant_id',
            'purchase_products.net_unit_cost',
            'purchase_products.quantity',
            'units.code_name as unit_code',
            'purchase_products.line_total',
            'purchase_products.selling_price',
            'purchases.id',
            'purchases.supplier_id',
            'purchases.date',
            'purchases.invoice_id',
            'products.name',
            'products.product_code',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_price',
            'suppliers.name as supplier_name'
        );

        $purchaseProducts = $query->where('purchases.is_purchased', 1)
            ->orderBy('purchases.report_date', 'desc');

        return DataTables::of($purchaseProducts)
            ->editColumn('product', function ($row) {

                $variant = $row->variant_name ? ' - '.$row->variant_name : '';

                return $row->name.$variant;
            })->editColumn('product_code', function ($row) {

                return $row->variant_code ? $row->variant_code : $row->product_code;
            })->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('quantity', function ($row) {

                return $row->quantity.' (<span class="qty" data-value="'.$row->quantity.'">'.$row->unit_code.'</span>)';
            })
            ->editColumn('invoice_id', fn ($row) => '<a href="'.route('purchases.show', [$row->purchase_id]).'" class="details_button text-danger text-hover" title="view" >'.$row->invoice_id.'</a>')

            ->editColumn('net_unit_cost', fn ($row) => \App\Utils\Converter::format_in_bdt($row->net_unit_cost))
            ->editColumn('price', function ($row) {

                if ($row->selling_price > 0) {

                    return \App\Utils\Converter::format_in_bdt($row->selling_price);
                } else {

                    if ($row->variant_name) {

                        return \App\Utils\Converter::format_in_bdt($row->variant_price);
                    } else {

                        return \App\Utils\Converter::format_in_bdt($row->product_price);
                    }
                }

                return $converter->format_in_bdt($row->net_unit_cost);
            })->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="'.$row->line_total.'">'.\App\Utils\Converter::format_in_bdt($row->line_total).'</span>')

            ->rawColumns(['product', 'product_code', 'date', 'quantity', 'invoice_id', 'net_unit_cost', 'price', 'subtotal'])
            ->make(true);
    }

    public function update(Type $var = null)
    {
        // code...
    }
}
