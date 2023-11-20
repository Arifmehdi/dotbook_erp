<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    // Index view of Stock report
    public function index(Request $request)
    {
        if (! auth()->user()->can('stock_adjustment_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $query = DB::table('stock_adjustments');

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('date_ts', $date_range);
            }

            return $query->select(
                DB::raw('sum(net_total_amount) as t_amount'),
                DB::raw('sum(recovered_amount) as t_recovered_amount'),
                DB::raw("SUM(IF(type = '1', net_total_amount, 0)) as total_normal"),
                DB::raw("SUM(IF(type = '2', net_total_amount, 0)) as total_abnormal"),
            )->get();
        }

        return view('inventories.reports.stock_adjustment_report.index');
    }

    // All Stock Adjustment **requested by ajax**
    public function allAdjustments(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $adjustments = '';

            $query = DB::table('stock_adjustments')
                ->leftJoin('users', 'stock_adjustments.created_by_id', 'users.id');

            if ($request->type) {

                $query->where('stock_adjustments.type', $request->type);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('stock_adjustments.date_ts', $date_range); // Final
            }

            $adjustments = $query->select(
                'stock_adjustments.*',
                'users.prefix',
                'users.name',
                'users.last_name',
            )->orderBy('stock_adjustments.date_ts', 'desc');

            return DataTables::of($adjustments)
                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('type', function ($row) {

                    return $row->type == 1 ? '<span class="badge bg-primary">Normal</span>' : '<span class="badge bg-danger">Abnormal</span>';
                })
                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.\App\Utils\Converter::format_in_bdt($row->total_item).'</span>')
                ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_qty).'</span>')
                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="'.$row->net_total_amount.'">'.\App\Utils\Converter::format_in_bdt($row->net_total_amount).'</span>')
                ->editColumn('recovered_amount', fn ($row) => '<span class="recovered_amount" data-value="'.$row->recovered_amount.'">'.\App\Utils\Converter::format_in_bdt($row->recovered_amount).'</span>')
                ->editColumn('created_by', fn ($row) => $row->prefix.' '.$row->name.' '.$row->last_name)
                ->rawColumns(['date', 'type', 'total_item',  'total_qty', 'net_total_amount', 'recovered_amount', 'created_by'])
                ->make(true);
        }
    }

    public function print(Request $request)
    {
        $fromDate = '';
        $toDate = '';
        $adjustments = '';
        $query = DB::table('stock_adjustments')
            ->leftJoin('users', 'stock_adjustments.created_by_id', 'users.id');

        if ($request->type) {

            $query->where('stock_adjustments.type', $request->type);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('stock_adjustments.date_ts', $date_range); // Final
        }

        $adjustments = $query->select(
            'stock_adjustments.*',
            'users.prefix',
            'users.name',
            'users.last_name',
        )->orderBy('id', 'desc')->get();

        return view('inventories.reports.stock_adjustment_report.ajax_view.print', compact('adjustments', 'fromDate', 'toDate'));
    }
}
