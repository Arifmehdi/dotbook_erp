<?php

namespace App\Http\Controllers\Manufacturing;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;

    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('manuf_report')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
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
                ->editColumn('date', function ($row) use ($generalSettings) {
                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('from', function ($row) use ($generalSettings) {
                    return json_decode($generalSettings->business, true)['shop_name'].'  ';
                })->editColumn('product', fn ($row) => $row->p_name.' '.$row->v_name)
                ->editColumn('unit_cost_inc_tax', fn ($row) => $this->converter->format_in_bdt($row->unit_cost_inc_tax))
                ->editColumn('price_exc_tax', fn ($row) => $this->converter->format_in_bdt($row->price_exc_tax))
                ->editColumn('quantity', fn ($row) => '<span class="quantity" data-value="'.$row->quantity.'">'.$row->quantity.'/'.$row->u_name.'</span>')
                ->editColumn('wasted_quantity', fn ($row) => '<span class="wasted_quantity" data-value="'.$row->wasted_quantity.'">'.$row->wasted_quantity.'/'.$row->u_name.'</span>')
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
                ->rawColumns(['date', 'from', 'product', 'unit_cost_inc_tax', 'price_exc_tax', 'quantity', 'wasted_quantity',  'total_final_quantity', 'total_ingredient_cost', 'production_cost', 'total_cost', 'status'])
                ->make(true);
        }

        $categories = DB::table('categories')->select('id', 'name')->get();

        return view('manufacturing.report.index', compact('branches'));
    }

    private function filteredQuery($request, $query)
    {
        if ($request->product_id) {
            $query->where('productions.product_id', $request->product_id);
        }

        if ($request->variant_id) {
            $query->where('productions.variant_id', $request->variant_id);
        }

        if ($request->warehouse_id) {
            $query->where('productions.warehouse_id', $request->warehouse_id);
        }

        if ($request->category_id) {
            $query->where('products.category_id', $request->category_id);
        }

        if ($request->sub_category_id) {
            $query->where('products.parent_category_id', $request->sub_category_id);
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
