<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DailyStockItemReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();
            $dailyStockProducts = '';

            $query = DB::table('daily_stock_products')
                ->leftJoin('daily_stocks', 'daily_stock_products.daily_stock_id', 'daily_stocks.id')
                ->leftJoin('products', 'daily_stock_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'daily_stock_products.variant_id', 'product_variants.id')
                ->leftJoin('warehouses', 'daily_stocks.warehouse_id', 'warehouses.id')
                ->leftJoin('units', 'daily_stock_products.unit_id', 'units.id');

            if ($request->product_id) {

                $query->where('daily_stock_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('daily_stock_products.variant_id', $request->variant_id);
            }

            if ($request->warehouse_id) {

                $query->where('daily_stocks.warehouse_id', $request->warehouse_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('daily_stocks.report_date', $date_range);
            }

            $query->select(
                'daily_stock_products.daily_stock_id',
                'daily_stock_products.product_id',
                'daily_stock_products.variant_id',
                'daily_stock_products.unit_cost_inc_tax',
                'daily_stock_products.quantity',
                'daily_stock_products.unit',
                'daily_stock_products.subtotal',
                'daily_stocks.date',
                'daily_stocks.voucher_no',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'warehouses.warehouse_name as w_name',
                'warehouses.warehouse_code as w_code',
            );

            $dailyStockProducts = $query->orderBy('daily_stocks.report_date', 'desc');

            return DataTables::of($dailyStockProducts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - '.$row->variant_name : '';

                    return $row->name.$variant;
                })
                ->editColumn('product_code', function ($row) {

                    return $row->variant_code ? $row->variant_code : $row->product_code;
                })
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('stored_location', function ($row) use ($generalSettings) {

                    if ($row->w_name) {

                        return $row->w_name.'/'.$row->w_code.'<strong>(WH)</strong>';
                    } elseif ($row->b_name) {

                        return $row->b_name.'<strong>(B.L)</strong>';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'];
                    }
                })
                ->editColumn('quantity', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->quantity / $baseUnitMultiplier).'/<span class="quantity" data-value="'.($row->quantity / $baseUnitMultiplier).'">'.$row->unit_code.'</span>';
                })
                ->editColumn('unit_cost_inc_tax', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return \App\Utils\Converter::format_in_bdt($row->unit_cost_inc_tax * $baseUnitMultiplier);
                })
                ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="'.$row->subtotal.'">'.$this->converter->format_in_bdt($row->subtotal).'</span>')
                ->rawColumns(['product', 'product_code', 'date', 'quantity', 'unit_cost_inc_tax', 'stored_location', 'subtotal'])
                ->make(true);
        }

        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')->get();

        return view('inventories.reports.daily_stock_item_report.index', compact('warehouses'));
    }

    public function print(Request $request)
    {
        $dailyStockProducts = '';
        $fromDate = '';
        $toDate = '';

        $query = DB::table('daily_stock_products')
            ->leftJoin('daily_stocks', 'daily_stock_products.daily_stock_id', 'daily_stocks.id')
            ->leftJoin('products', 'daily_stock_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'daily_stock_products.variant_id', 'product_variants.id')
            ->leftJoin('warehouses', 'daily_stocks.warehouse_id', 'warehouses.id')
            ->leftJoin('units', 'daily_stock_products.unit_id', 'units.id');

        if ($request->product_id) {

            $query->where('daily_stock_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('daily_stock_products.variant_id', $request->variant_id);
        }

        if ($request->warehouse_id) {

            $query->where('daily_stocks.warehouse_id', $request->warehouse_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('daily_stocks.report_date', $date_range);
        }

        $query->select(
            'daily_stock_products.daily_stock_id',
            'daily_stock_products.product_id',
            'daily_stock_products.variant_id',
            'daily_stock_products.unit_cost_inc_tax',
            'daily_stock_products.quantity',
            'daily_stock_products.unit',
            'daily_stock_products.subtotal',
            'daily_stocks.date',
            'daily_stocks.voucher_no',
            'units.code_name as unit_code',
            'units.base_unit_multiplier',
            'products.name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'warehouses.warehouse_name as w_name',
            'warehouses.warehouse_code as w_code',
        );

        $dailyStockProducts = $query->orderBy('daily_stocks.report_date', 'desc')->get();

        return view('inventories.reports.daily_stock_item_report.ajax_view.print', compact('dailyStockProducts', 'fromDate', 'toDate'));
    }
}
