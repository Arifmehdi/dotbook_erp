<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockIssueItemReportController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('stock_issued_items_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $stockIssueItems = '';
            $generalSettings = DB::table('general_settings')->select('business')->first();

            $query = DB::table('stock_issue_products')->leftJoin('stock_issues', 'stock_issue_products.stock_issue_id', 'stock_issues.id')
                ->leftJoin('warehouses', 'stock_issue_products.warehouse_id', 'warehouses.id')
                ->leftJoin('departments', 'stock_issues.department_id', 'departments.id')
                ->leftJoin('products', 'stock_issue_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'stock_issue_products.variant_id', 'product_variants.id')
                ->leftJoin('users as created_by', 'stock_issues.created_by_id', 'created_by.id')
                ->leftJoin('units', 'stock_issue_products.unit_id', 'units.id');

            if (! empty($request->warehouse_id)) {

                $query->where('stock_issue_products.warehouse_id', $request->warehouse_id);
            }

            if ($request->department_id) {

                $query->where('stock_issues.department_id', $request->department_id);
            }

            if ($request->stock_event_id) {

                $query->where('stock_issues.stock_event_id', $request->stock_event_id);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('stock_issues.report_date', $date_range); // Final
            }

            $stockIssueItems = $query->select(
                'stock_issues.date',
                'stock_issues.voucher_no',
                'stock_issues.note',
                'stock_issue_products.quantity',
                'stock_issue_products.unit',
                'stock_issue_products.unit_cost_inc_tax',
                'stock_issue_products.subtotal',
                'products.name as p_name',
                'product_variants.variant_name as v_name',
                'departments.id',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
                'departments.name as dep_name',
                'warehouses.warehouse_name as w_name',
                'warehouses.warehouse_code as w_code',
                'created_by.prefix as created_prefix',
                'created_by.name as created_name',
                'created_by.last_name as created_last_name',
            )->orderBy('stock_issues.report_date', 'desc');

            return DataTables::of($stockIssueItems)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })

                ->editColumn('send_from', function ($row) use ($generalSettings) {

                    if ($row->w_name) {

                        return $row->w_name.'/'.$row->w_code.'<b>(WH)</b>';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'];
                    }
                })

                ->editColumn('product', function ($row) {

                    if ($row->v_name) {

                        return $row->p_name.'-'.$row->v_name;
                    } else {

                        return $row->p_name;
                    }
                })

                ->editColumn('quantity', function ($row) {

                    $baseUnitMultiplier = $row->base_unit_multiplier ? $row->base_unit_multiplier : 1;

                    return '<span class="quantity" data-value="'.($row->quantity / $baseUnitMultiplier).'">'.\App\Utils\Converter::format_in_bdt($row->quantity / $baseUnitMultiplier).'/'.$row->unit_code.'</span>';
                })

                ->editColumn('unit_cost_inc_tax', fn ($row) => '<span class="unit_cost_inc_tax" data-value="'.$row->unit_cost_inc_tax.'">'.\App\Utils\Converter::format_in_bdt($row->unit_cost_inc_tax).'</span>')

                ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="'.$row->subtotal.'">'.\App\Utils\Converter::format_in_bdt($row->subtotal).'</span>')

                ->editColumn('created_by', function ($row) {

                    return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
                })
                ->rawColumns(['product', 'date', 'send_from', 'total_item', 'total_qty', 'quantity', 'unit_cost_inc_tax', 'subtotal', 'created_by'])
                ->make(true);
        }

        $departments = DB::table('departments')->select('id', 'name')->orderBy('name', 'asc')->get();

        $events = DB::table('stock_events')->select('id', 'name')->orderBy('name', 'asc')->get();

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->orderBy('warehouses.warehouse_name', 'asc')->get();

        return view('procurement.reports.stock_issued_items_reports.index', compact('departments', 'events', 'warehouses'));
    }

    public function print(Request $request)
    {
        if (! auth()->user()->can('stock_issued_items_report')) {

            abort(403, 'Access Forbidden.');
        }

        $fromDate = '';
        $toDate = '';
        $stockIssueItems = '';

        $warehouseName = $request->warehouse_name;
        $departmentName = $request->department_name;
        $eventName = $request->event_name;

        $query = DB::table('stock_issue_products');

        if (! empty($request->warehouse_id)) {

            $query->where('stock_issues.warehouse_id', $request->warehouse_id);
        }

        if ($request->department_id) {

            $query->where('stock_issues.department_id', $request->department_id);
        }

        if ($request->stock_event_id) {

            $query->where('stock_issues.stock_event_id', $request->stock_event_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('stock_issues.report_date', $date_range); // Final
        }

        $stockIssueItems = $query->leftJoin('stock_issues', 'stock_issue_products.stock_issue_id', 'stock_issues.id')
            ->leftJoin('warehouses', 'stock_issue_products.warehouse_id', 'warehouses.id')
            ->leftJoin('departments', 'stock_issues.department_id', 'departments.id')
            ->leftJoin('products', 'stock_issue_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'stock_issue_products.variant_id', 'product_variants.id')
            ->leftJoin('users as created_by', 'stock_issues.created_by_id', 'created_by.id')
            ->leftJoin('units', 'stock_issue_products.unit_id', 'units.id')
            ->select(
                'stock_issues.date',
                'stock_issues.voucher_no',
                'stock_issues.note',
                'stock_issue_products.quantity',
                'stock_issue_products.unit',
                'stock_issue_products.unit_cost_inc_tax',
                'stock_issue_products.subtotal',
                'products.name as p_name',
                'product_variants.variant_name as v_name',
                'departments.id',
                'departments.name as dep_name',
                'warehouses.warehouse_name as w_name',
                'warehouses.warehouse_code as w_code',
                'created_by.prefix as created_prefix',
                'created_by.name as created_name',
                'created_by.last_name as created_last_name',
                'units.code_name as unit_code',
                'units.base_unit_multiplier',
            )->orderBy('departments.name', 'asc')->orderBy('stock_issues.report_date', 'desc')->get();

        return view(
            'procurement.reports.stock_issued_items_reports.ajax_view.print',
            compact(
                'stockIssueItems',
                'fromDate',
                'toDate',
                'warehouseName',
                'departmentName',
                'eventName',
            )
        );
    }
}
