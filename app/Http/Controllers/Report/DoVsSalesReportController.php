<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DoVsSalesReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    // Index view of expense report
    public function index(Request $request)
    {
        if (! auth()->user()->can('do_vs_sales_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();
            $sales = '';

            $query = Sale::where('status', 1)->where('delivery_order_id', '!=', null);

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('report_date', $date_range); // Final
            }

            $sales = $query->with(
                [
                    'do:id,do_id,do_date,total_do_qty,net_total_amount',
                    'customer:id,name',
                    'weight:id,sale_id,do_car_number,first_weight,second_weight',
                ]
            )->select(
                'id',
                'invoice_id',
                'customer_account_id',
                'total_sold_qty',
                'total_delivered_qty',
                'do_total_left_qty',
                'report_date',
                'net_total_amount',
                'delivery_order_id'
            )->orderBy('report_date', 'desc')->get();

            return DataTables::of($sales)
                ->editColumn('do_id', function ($row) {

                    return $row->do->do_id;
                })
                ->editColumn('do_date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->do->do_date));
                })
                ->editColumn('do_total_left_qty', fn ($row) => '<span class="do_total_left_qty" data-value="'.$row->do_total_left_qty.'">'.$this->converter->format_in_bdt($row->do_total_left_qty).'</span>')
                ->editColumn('invoice_date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->report_date));
                })
                ->editColumn('do_car_number', fn ($row) => $row?->weight?->do_car_number)
                ->editColumn('total_sold_qty', function ($row) use ($converter) {

                    return '<span class="total_sold_qty" data-value="'.$row->total_sold_qty.'">'.$converter->format_in_bdt($row->total_sold_qty).'</span>';
                })
                ->editColumn('sold_net_total', function ($row) use ($converter) {

                    return '<span class="sold_net_total" data-value="'.$row->net_total_amount.'">'.$converter->format_in_bdt($row->net_total_amount).'</span>';
                })
                ->editColumn('weight', function ($row) use ($converter) {

                    if ($row->weight) {

                        $html = '<p class="p-0 m-0"><strong>1st</strong> Weight : '.$converter->format_in_bdt($row->weight->first_weight).'</p>';
                        $html .= '<p class="p-0 m-0"><strong>2nd</strong> Weight : '.$converter->format_in_bdt($row->weight->second_weight).'</p>';

                        return $html;
                    }
                })
                ->editColumn('net_weight', function ($row) use ($converter) {

                    if ($row->weight) {

                        $calc1 = $row->weight->second_weight - $row->weight->first_weight;

                        return '<span class="net_weight" data-value="'.$calc1.'">'.$converter->format_in_bdt($calc1).'</span>';
                    }
                })
                ->rawColumns(['order_date', 'total_sold_qty', 'do_total_left_qty', 'net_total', 'invoice_date', 'total_delivered_qty', 'weight', 'sold_net_total', 'net_weight'])
                ->make(true);
        }

        return view('sales_app.reports.do_vs_sales.index');
    }

    public function print(Request $request)
    {
        $fromDate = '';
        $toDate = '';

        $query = Sale::where('status', 1)->where('delivery_order_id', '!=', null);

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('report_date', $date_range); // Final
        }

        $query->select('id', 'invoice_id', 'customer_account_id', 'total_sold_qty', 'total_delivered_qty', 'do_total_left_qty', 'report_date', 'net_total_amount', 'delivery_order_id');

        $sales = $query->with(
            [
                'do:id,do_id,do_date,total_do_qty,net_total_amount',
                'weight:sale_id,do_car_number,first_weight,second_weight',
            ]
        )->orderBy('report_date', 'desc')->get();

        return view('sales_app.reports.do_vs_sales.ajax_view.print', compact('sales', 'fromDate', 'toDate'));
    }
}
