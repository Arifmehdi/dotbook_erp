<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockIssueReportController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('stock_issue_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $stockIssues = '';
            $query = DB::table('stock_issues');

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

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('stock_issues.report_date', $date_range); // Final
            }

            $stockIssues = $query->leftJoin('warehouses', 'stock_issues.warehouse_id', 'warehouses.id')
                ->leftJoin('departments', 'stock_issues.department_id', 'departments.id')
                ->leftJoin('stock_events', 'stock_issues.stock_event_id', 'stock_events.id')
                ->leftJoin('users as created_by', 'stock_issues.created_by_id', 'created_by.id')
                ->select(
                    'stock_issues.id',
                    'stock_issues.warehouse_id',
                    'stock_issues.department_id',
                    'stock_issues.stock_event_id',
                    'stock_issues.date',
                    'stock_issues.voucher_no',
                    'stock_issues.total_item',
                    'stock_issues.total_qty',
                    'stock_issues.net_total_value',
                    'departments.name as dep_name',
                    'stock_events.name as event_name',
                    'created_by.prefix as created_prefix',
                    'created_by.name as created_name',
                    'created_by.last_name as created_last_name',
                )->orderBy('stock_issues.report_date', 'desc');

            return DataTables::of($stockIssues)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })

                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.\App\Utils\Converter::format_in_bdt($row->total_item).'</span>')

                ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_qty).'</span>')

                ->editColumn('net_total_value', fn ($row) => '<span class="net_total_value" data-value="'.$row->net_total_value.'">'.\App\Utils\Converter::format_in_bdt($row->net_total_value).'</span>')
                ->editColumn('created_by', function ($row) {

                    return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
                })
                ->rawColumns(['date', 'send_from', 'total_item', 'total_qty', 'total_qty', 'net_total_value', 'created_by'])
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

        return view('procurement.reports.stock_issue_report.index', compact('departments', 'warehouses', 'events'));
    }

    public function print(Request $request)
    {
        if (! auth()->user()->can('stock_issue_report')) {

            abort(403, 'Access Forbidden.');
        }

        $stockIssues = '';
        $fromDate = '';
        $toDate = '';
        $warehouseName = $request->warehouse_name;
        $departmentName = $request->department_name;
        $eventName = $request->event_name;

        $query = DB::table('stock_issues')
            ->leftJoin('warehouses', 'stock_issues.warehouse_id', 'warehouses.id')
            ->leftJoin('departments', 'stock_issues.department_id', 'departments.id')
            ->leftJoin('stock_events', 'stock_issues.stock_event_id', 'stock_events.id')
            ->leftJoin('users as created_by', 'stock_issues.created_by_id', 'created_by.id');

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

        $stockIssues = $query->select(
            'stock_issues.id',
            'stock_issues.warehouse_id',
            'stock_issues.department_id',
            'stock_issues.stock_event_id',
            'stock_issues.date',
            'stock_issues.voucher_no',
            'stock_issues.total_item',
            'stock_issues.total_qty',
            'stock_issues.net_total_value',
            'warehouses.warehouse_name as w_name',
            'warehouses.warehouse_code as w_code',
            'departments.name as dep_name',
            'stock_events.name as event_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('stock_issues.report_date', 'desc')->get();

        return view(
            'procurement.reports.stock_issue_report.ajax_view.print',
            compact(
                'stockIssues',
                'fromDate',
                'toDate',
                'warehouseName',
                'departmentName',
                'eventName',
            )
        );
    }
}
