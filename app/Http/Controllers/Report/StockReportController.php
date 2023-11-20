<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {

        $this->converter = $converter;
    }

    // Index view of Stock report
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();
            $branch_stock = '';
            $query = DB::table('product_branches')
                ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
                ->leftJoin('products', 'product_branches.product_id', 'products.id')
                ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', 'brands.id')
                ->leftJoin('taxes', 'products.tax_ac_id', 'taxes.id');

            if ($request->category_id) {

                $query->where('products.category_id', $request->category_id);
            }

            if ($request->brand_id) {

                $query->where('products.brand_id', $request->brand_id);
            }

            if ($request->unit_id) {

                $query->where('products.unit_id', $request->unit_id);
            }

            if ($request->tax_ac_id) {

                $query->where('products.tax_ac_id', $request->tax_ac_id);
            }

            $branch_stock = $query->select(
                'units.code_name',
                'products.name',
                'products.product_code',
                'products.product_cost_with_tax',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_branches.product_quantity',
                'product_branches.total_sale',
                'product_branch_variants.variant_quantity',
                'product_branch_variants.total_sale as v_total_sale',
            );

            return DataTables::of($branch_stock)
                ->editColumn('product_code', fn ($row) => $row->variant_code ? $row->variant_code : $row->product_code)
                ->editColumn('name', fn ($row) => $row->name.' '.$row->variant_name)

                ->editColumn('stock', fn ($row) => '<span class="stock" data-value="'.($row->variant_quantity ? $row->variant_quantity : $row->product_quantity).'">'.($row->variant_quantity ? '<strong>'.$row->variant_quantity.'</strong>' : '<strong>'.$row->product_quantity.'</strong>').'/'.$row->code_name.'</span>')

                ->editColumn('price', fn ($row) => $row->variant_price ? $row->variant_price : $row->product_price)
                ->editColumn('stock_value', function ($row) use ($converter) {
                    $price = $row->variant_cost_with_tax ? $row->variant_cost_with_tax : $row->product_cost_with_tax;
                    $stock = $row->variant_quantity ? $row->variant_quantity : $row->product_quantity;
                    $currentStockValue = $price * $stock;

                    return '<span class="stock_value" data-value="'.$currentStockValue.'">'.$converter->format_in_bdt($currentStockValue).'</span>';
                })
                ->editColumn('total_sale', fn ($row) => '<span class="total_sale" data-value="'.($row->v_total_sale ? $row->v_total_sale : $row->total_sale).'">'.($row->v_total_sale ? $row->v_total_sale : $row->total_sale).'('.$row->code_name.')</span>')
                ->rawColumns(['product_code', 'name', 'stock', 'price', 'stock_value', 'total_sale'])
                ->make(true);
        }

        $brands = DB::table('brands')->get(['id', 'name']);
        $categories = DB::table('categories')->where('parent_category_id', null)->get(['id', 'name']);
        $taxes = DB::table('taxes')->get(['id', 'tax_name']);
        $units = DB::table('units')->get(['id', 'name']);

        return view('inventories.reports.stock_report.index', compact('brands', 'taxes', 'units', 'categories'));
    }

    // Get all product stock **requested by ajax**
    public function warehouseStock(Request $request)
    {
        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();
            $warehouse_stock = '';

            $query = DB::table('product_warehouses')
                // ->where('product_warehouses.product_id', 4131)
                ->leftJoin('product_warehouse_variants', 'product_warehouses.id', 'product_warehouse_variants.product_warehouse_id')
                ->leftJoin('products', 'product_warehouses.product_id', 'products.id')
                ->leftJoin('product_variants', 'product_warehouse_variants.product_variant_id', 'product_variants.id')
                ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', 'brands.id')
                ->leftJoin('taxes', 'products.tax_ac_id', 'taxes.id')
                ->leftJoin('purchase_products', 'products.id', 'purchase_products.product_id')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id');

            if ($request->warehouse_id) {

                $query->where('product_warehouses.warehouse_id', $request->warehouse_id);
            }

            if ($request->category_id) {

                $query->where('products.category_id', $request->category_id);
            }

            if ($request->subcategory_id) {

                $query->where('products.parent_category_id', $request->subcategory_id);
            }

            if ($request->brand_id) {

                $query->where('products.brand_id', $request->brand_id);
            }

            if ($request->unit_id) {

                $query->where('products.unit_id', $request->unit_id);
            }

            if ($request->tax_ac_id) {

                $query->where('products.tax_ac_id', $request->tax_ac_id);
            }

            $warehouse_stock = $query->select(
                'units.code_name as unit_code_name',
                'warehouses.id as w_id',
                'warehouses.warehouse_name as w_name',
                'warehouses.warehouse_code as w_code',
                'products.name',
                'products.product_code',
                'products.product_cost_with_tax',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_warehouses.product_quantity',
                'product_warehouse_variants.variant_quantity',
                DB::raw(
                    '
                        SUM(
                            case when
                            product_warehouses.product_id = purchase_products.product_id
                            then
                            purchase_products.quantity end
                        )
                        as product_purchased_qty
                    '
                ),
                DB::raw(
                    '
                        SUM(
                            case when
                            product_warehouses.product_id = purchase_products.product_id
                            then
                            purchase_products.line_total end
                        ) as all_product_purchased_cost
                    '
                ),
                DB::raw(
                    '
                        SUM(
                            case when
                            product_variants.id = purchase_products.product_variant_id
                            then
                            purchase_products.quantity end
                        ) as variant_purchased_qty
                    '
                ),
                DB::raw(
                    '
                        SUM(
                            case when
                            product_variants.id = purchase_products.product_variant_id
                            then
                            purchase_products.line_total end
                        ) as all_variant_purchased_cost
                    '
                )
            )->groupBy([
                'units.id',
                'warehouses.id',
                'products.name',
                'product_variants.id',
                'product_warehouses.id',
                'product_warehouses.product_id',
                'product_warehouse_variants.id',
                'product_warehouse_variants.product_variant_id',
            ])->orderBy('products.name');

            return DataTables::of($warehouse_stock)
                ->editColumn('product_code', fn ($row) => $row->variant_code ? $row->variant_code : $row->product_code)
                ->editColumn('name', fn ($row) => $row->name.' '.$row->variant_name)
                ->editColumn('warehouse', fn ($row) => $row->w_name.'/'.$row->w_code)
                ->editColumn('stock', fn ($row) => '<span class="stock" data-value="'.($row->variant_quantity ? $row->variant_quantity : $row->product_quantity).'">'.$converter->format_in_bdt(($row->variant_quantity ? $row->variant_quantity : $row->product_quantity)).'/'.$row->unit_code_name.'</span>')
                ->editColumn('per_unit_cost', function ($row) use ($converter) {

                    $totalPrice = 0;
                    $totalInStock = 0;
                    if ($row->variant_name) {

                        $totalInStock = $row->variant_purchased_qty;
                        $totalPrice = $row->all_variant_purchased_cost;
                    } else {

                        $totalInStock = $row->product_purchased_qty;
                        $totalPrice = $row->all_product_purchased_cost;
                    }

                    $wtAvgPrice = $totalInStock > 0 ? $totalPrice / $totalInStock : 0;

                    return $converter->format_in_bdt($wtAvgPrice);
                })

                ->editColumn('stock_value', function ($row) use ($converter) {

                    $totalPrice = 0;
                    $currentStock = 0;
                    $totalInStock = 0;
                    if ($row->variant_name) {

                        $currentStock = $row->variant_quantity;
                        $totalInStock = $row->variant_purchased_qty;
                        $totalPrice = $row->all_variant_purchased_cost;
                    } else {

                        $currentStock = $row->product_quantity;
                        $totalInStock = $row->product_purchased_qty;
                        $totalPrice = $row->all_product_purchased_cost;
                    }

                    $wtAvgPrice = $totalInStock > 0 ? $totalPrice / $totalInStock : 0;
                    $currentStockValue = $wtAvgPrice * $currentStock;

                    return '<span class="stock_value" data-value="'.$currentStockValue.'">'.$converter->format_in_bdt($currentStockValue).'</span>';
                })
                ->rawColumns(['product_code', 'name', 'stock', 'per_unit_cost', 'stock_value'])
                ->make(true);
        }
    }

    // Print Branch Stock
    public function printBranchStock(Request $request)
    {
        $branch_stock = '';

        $query = DB::table('product_branches')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('brands', 'products.brand_id', 'brands.id')
            ->leftJoin('taxes', 'products.tax_ac_id', 'taxes.id');

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->unit_id) {

            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->tax_ac_id) {

            $query->where('products.tax_ac_id', $request->tax_ac_id);
        }

        $branch_stock = $query->select(
            'units.code_name',
            'products.name',
            'products.product_code',
            'products.product_cost_with_tax',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_cost_with_tax',
            'product_variants.variant_price',
            'product_branches.product_quantity',
            'product_branches.total_sale',
            'product_branch_variants.variant_quantity',
            'product_branch_variants.total_sale as v_total_sale',
        )->get();

        return view('inventories.reports.stock_report.ajax_view.branch_stock_print', compact('branch_stock'));
    }

    public function printWarehouseStock(Request $request)
    {
        $branch_stock = '';

        $warehouseName = $request->warehouse_name;
        $categoryName = $request->category_name;
        $subcategoryName = $request->subcategory_name;
        $brandName = $request->brand_name;
        $unitName = $request->unit_name;

        $warehouse_stock = '';

        $query = DB::table('product_warehouses')
            ->leftJoin('product_warehouse_variants', 'product_warehouses.id', 'product_warehouse_variants.product_warehouse_id')
            ->leftJoin('products', 'product_warehouses.product_id', 'products.id')
            ->leftJoin('product_variants', 'product_warehouse_variants.product_variant_id', 'product_variants.id')
            ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('brands', 'products.brand_id', 'brands.id')
            ->leftJoin('taxes', 'products.tax_ac_id', 'taxes.id')
            ->leftJoin('purchase_products', 'products.id', 'purchase_products.product_id')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id');

        if ($request->warehouse_id) {

            $query->where('product_warehouses.warehouse_id', $request->warehouse_id);
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->subcategory_id) {

            $query->where('products.parent_category_id', $request->subcategory_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->unit_id) {

            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->tax_ac_id) {

            $query->where('products.tax_ac_id', $request->tax_ac_id);
        }

        $warehouse_stock = $query->select(
            'units.code_name as unit_code_name',
            'warehouses.id as w_id',
            'warehouses.warehouse_name as w_name',
            'warehouses.warehouse_code as w_code',
            'products.name',
            'products.product_code',
            'products.product_cost_with_tax',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_cost_with_tax',
            'product_variants.variant_price',
            'product_warehouses.product_quantity',
            'product_warehouse_variants.variant_quantity',
            DB::raw(
                '
                    SUM(
                        case when
                        product_warehouses.product_id = purchase_products.product_id
                        then
                        purchase_products.quantity end
                    )
                    as product_purchased_qty
                '
            ),
            DB::raw(
                '
                    SUM(
                        case when
                        product_warehouses.product_id = purchase_products.product_id
                        then
                        purchase_products.line_total end
                    ) as all_product_purchased_cost
                '
            ),
            DB::raw(
                '
                    SUM(
                        case when
                        product_variants.id = purchase_products.product_variant_id
                        then
                        purchase_products.quantity end
                    ) as variant_purchased_qty
                '
            ),
            DB::raw(
                '
                    SUM(
                        case when
                        product_variants.id = purchase_products.product_variant_id
                        then
                        purchase_products.line_total end
                    ) as all_variant_purchased_cost
                '
            )
        )->groupBy([
            'units.id',
            'warehouses.id',
            'products.name',
            'product_variants.id',
            'product_warehouses.id',
            'product_warehouses.product_id',
            'product_warehouse_variants.id',
            'product_warehouse_variants.product_variant_id',
        ])->orderBy('warehouses.id')->orderBy('products.name')->get();

        return view('inventories.reports.stock_report.ajax_view.warehouse_stock_print', compact(
            'warehouse_stock',
            'warehouseName',
            'categoryName',
            'subcategoryName',
            'brandName',
            'unitName'
        ));
    }

    public function printWarehouseStockValue(Request $request)
    {
        $branch_stock = '';

        $warehouseName = $request->warehouse_name;
        $categoryName = $request->category_name;
        $subcategoryName = $request->subcategory_name;
        $brandName = $request->brand_name;
        $unitName = $request->unit_name;

        $warehouse_stock = '';

        $query = DB::table('product_warehouses')
            // ->where('product_warehouses.product_id', 4131)
            ->leftJoin('product_warehouse_variants', 'product_warehouses.id', 'product_warehouse_variants.product_warehouse_id')
            ->leftJoin('products', 'product_warehouses.product_id', 'products.id')
            ->leftJoin('product_variants', 'product_warehouse_variants.product_variant_id', 'product_variants.id')
            ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('brands', 'products.brand_id', 'brands.id')
            ->leftJoin('taxes', 'products.tax_ac_id', 'taxes.id')
            ->leftJoin('purchase_products', 'products.id', 'purchase_products.product_id')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id');

        if ($request->warehouse_id) {

            $query->where('product_warehouses.warehouse_id', $request->warehouse_id);
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->subcategory_id) {

            $query->where('products.parent_category_id', $request->subcategory_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->unit_id) {

            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->tax_ac_id) {

            $query->where('products.tax_ac_id', $request->tax_ac_id);
        }

        $warehouse_stock = $query->select(
            'product_variants.variant_name',
            'product_warehouses.product_quantity',
            'product_warehouse_variants.variant_quantity',
            DB::raw(
                '
                    SUM(
                        case when
                        product_warehouses.product_id = purchase_products.product_id
                        then
                        purchase_products.quantity end
                    )
                    as product_purchased_qty
                '
            ),
            DB::raw(
                '
                    SUM(
                        case when
                        product_warehouses.product_id = purchase_products.product_id
                        then
                        purchase_products.line_total end
                    ) as all_product_purchased_cost
                '
            ),
            DB::raw(
                '
                    SUM(
                        case when
                        product_variants.id = purchase_products.product_variant_id
                        then
                        purchase_products.quantity end
                    ) as variant_purchased_qty
                '
            ),
            DB::raw(
                '
                    SUM(
                        case when
                        product_variants.id = purchase_products.product_variant_id
                        then
                        purchase_products.line_total end
                    ) as all_variant_purchased_cost
                '
            )
        )->groupBy([
            'units.id',
            'warehouses.id',
            'products.name',
            'product_variants.id',
            'product_warehouses.id',
            'product_warehouses.product_id',
            'product_warehouse_variants.id',
            'product_warehouse_variants.product_variant_id',
        ])->orderBy('products.name')->get();

        return view('inventories.reports.stock_report.ajax_view.warehouse_stock_value_print', compact(
            'warehouse_stock',
            'warehouseName',
            'categoryName',
            'subcategoryName',
            'brandName',
            'unitName'
        ));
    }
}
