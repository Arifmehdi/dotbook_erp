<?php

namespace App\Utils\Manufacturing;

use App\Models\Manufacturing\Production;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\ProductStockUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductionUtil
{
    protected $converter;

    protected $productStockUtil;

    protected $accountUtil;

    public function __construct(
        Converter $converter,
        ProductStockUtil $productStockUtil,
        AccountUtil $accountUtil,
    ) {
        $this->converter = $converter;
        $this->productStockUtil = $productStockUtil;
        $this->accountUtil = $accountUtil;
    }

    public function productionList($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $productions = '';
        $query = DB::table('productions')
            ->leftJoin('warehouses', 'productions.warehouse_id', 'warehouses.id')
            ->leftJoin('products', 'productions.product_id', 'products.id')
            ->leftJoin('product_variants', 'productions.variant_id', 'product_variants.id')
            ->leftJoin('units', 'productions.unit_id', 'units.id');

        $query->select(
            'productions.*',
            'products.name as p_name',
            'product_variants.variant_name as v_name',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'units.code_name as u_name',
        );

        $productions = $this->filteredQuery($request, $query)->orderBy('productions.report_date', 'desc');

        return DataTables::of($productions)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="'.route('manufacturing.productions.show', [$row->id]).'"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                if (auth()->user()->can('production_edit')) {
                    $html .= '<a class="dropdown-item" href="'.route('manufacturing.productions.edit', [$row->id]).'"><i class="far fa-edit text-primary"></i> Edit</a>';
                }

                if (auth()->user()->can('production_delete')) {
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('manufacturing.productions.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                }

                $html .= '<a class="dropdown-item" id="send_notification" href="#"><i class="fas fa-envelope text-primary"></i> Send Notification</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {
                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('product', fn ($row) => $row->p_name.' '.$row->v_name)
            ->editColumn('unit_cost_inc_tax', fn ($row) => $this->converter->format_in_bdt($row->unit_cost_inc_tax))
            ->editColumn('price_exc_tax', fn ($row) => $this->converter->format_in_bdt($row->price_exc_tax))
            ->editColumn('total_final_quantity', fn ($row) => '<span class="total_final_quantity" data-value="'.$row->total_final_quantity.'">'.$row->total_final_quantity.'/'.$row->u_name.'</span>')
            ->editColumn('total_ingredient_cost', fn ($row) => '<span class="total_ingredient_cost" data-value="'.$row->total_ingredient_cost.'">'.$this->converter->format_in_bdt($row->total_ingredient_cost).'</span>')
            ->editColumn('production_cost', fn ($row) => '<span class="production_cost" data-value="'.$row->production_cost.'">'.$this->converter->format_in_bdt($row->production_cost).'</span>')
            ->editColumn('total_cost', fn ($row) => '<span class="total_cost" data-value="'.$row->total_cost.'">'.$this->converter->format_in_bdt($row->total_cost).'</span>')
            ->editColumn('status', function ($row) {
                if ($row->is_final == 1) {
                    return '<span class="text-success"><b>Final</b></span>';
                } else {
                    return '<span class="text-danger"><b>Hold</b></span>';
                }
            })
            ->rawColumns(['action', 'date', 'product', 'unit_cost_inc_tax', 'price_exc_tax', 'total_final_quantity', 'total_ingredient_cost', 'production_cost', 'total_cost', 'status'])
            ->make(true);
    }

    public function deleteProduction($productionId)
    {
        $production = Production::with(['ingredients'])->where('id', $productionId)->first();
        $storedStatus = $production->is_final;
        $storedProductId = $production->product_id;
        $storedVariantId = $production->variant_id;
        $storedWarehouseId = $production->warehouse_id;
        $storedStockWarehouseId = $production->stock_warehouse_id;
        $storeIngredients = $production->ingredients;

        $storedProductionAccountId = $production->production_account_id;

        if (! is_null($production)) {
            $production->delete();
        }

        if ($storedStatus == 1) {

            $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);
            if ($storedWarehouseId) {

                $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $storedWarehouseId);
            } else {

                $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, $storedBranchId);
            }

            if (count($storeIngredients) > 0) {

                foreach ($storeIngredients as $ingredient) {

                    $this->productStockUtil->adjustMainProductAndVariantStock($ingredient->product_id, $ingredient->variant_id);
                    if ($storedStockWarehouseId) {

                        $this->productStockUtil->adjustWarehouseStock($ingredient->product_id, $ingredient->variant_id, $storedStockWarehouseId);
                    } else {

                        $this->productStockUtil->adjustBranchStock($ingredient->product_id, $ingredient->variant_id);
                    }
                }
            }
        }
    }

    public function updateProductAndVariantPriceByProduction(
        $productId,
        $variant_id,
        $unit_cost_exc_tax,
        $unit_cost_inc_tax,
        $x_margin,
        $selling_price,
        $tax_ac_id,
        $tax_type
    ) {

        $updateProduct = Product::where('id', $productId)->first();
        $updateProduct->is_purchased = 1;
        $updateProduct->tax_ac_id = $tax_ac_id;
        $updateProduct->tax_type = $tax_type;

        if ($updateProduct->is_variant == 0) {

            $updateProduct->product_cost = $unit_cost_exc_tax;
            $updateProduct->product_cost_with_tax = $unit_cost_inc_tax;
            $updateProduct->profit = $x_margin;
            $updateProduct->product_price = $selling_price;
        }
        $updateProduct->save();

        if ($variant_id != null) {

            $updateVariant = ProductVariant::where('id', $variant_id)
                ->where('product_id', $productId)
                ->first();
            $updateVariant->variant_cost = $unit_cost_exc_tax;
            $updateVariant->variant_cost_with_tax = $unit_cost_inc_tax;
            $updateVariant->variant_profit = $x_margin;
            $updateVariant->variant_price = $selling_price;
            $updateVariant->is_purchased = 1;
            $updateVariant->save();
        }
    }

    private function filteredQuery($request, $query)
    {
        if ($request->warehouse_id) {

            $query->where('productions.warehouse_id', $request->warehouse_id);
        }

        if ($request->status != '') {

            $query->where('productions.is_final', $request->status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date, $to_date];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('productions.report_date', $date_range); // Final
        }

        return $query;
    }
}
