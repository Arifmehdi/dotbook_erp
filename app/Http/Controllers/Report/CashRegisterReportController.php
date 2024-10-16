<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CashRegisterReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    // Index view of cash register report
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $settings = DB::table('general_settings')->select(['id', 'business'])->first();

            $cashRegisters = '';

            $query = DB::table('cash_registers')->leftJoin('users', 'cash_registers.admin_id', 'users.id');

            if ($request->user_id) {

                $query->where('cash_registers.admin_id', $request->user_id);
            }

            if ($request->status) {

                if ($request->status == 1) {

                    $query->where('cash_registers.status', 1);
                } elseif ($request->status == 2) {

                    $query->where('cash_registers.status', 0);
                }
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('cash_registers.created_at', $date_range); // Final
            }

            $query->select(
                'cash_registers.*',
                'users.prefix as u_prefix',
                'users.name as u_first_name',
                'users.last_name as u_last_name',
            );

            $cashRegisters = $query->orderBy('cash_registers.created_at', 'desc');

            return DataTables::of($cashRegisters)
                ->addColumn('action', function ($row) {
                    return '<a id="register_details_btn" href="'.route('sales.cash.register.details.for.report', [$row->id]).'" class="btn btn-sm btn-primary">View</a>';
                })
                ->editColumn('created_at', function ($row) {

                    //return Carbon::parse($row->created_at)->toFormattedDateString();
                    return Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('jS M, Y h:i A');
                })
                ->editColumn('closed_time', function ($row) {

                    if ($row->closed_at) {

                        return Carbon::createFromFormat('Y-m-d H:i:s', $row->closed_at)->format('jS M, Y h:i A');
                    }
                })
                ->editColumn('user', function ($row) {

                    return $row->u_prefix.' '.$row->u_first_name.' '.$row->u_last_name;
                })
                ->editColumn('status', function ($row) {

                    return $row->status == 1 ? '<span class="badge bg-success">Open</span>' : '<span class="badge bg-danger">Closed</span>';
                })
                ->editColumn('closed_amount', function ($row) {

                    return '<span class="closed_amount" data-value="'.$row->closed_amount.'">'.$this->converter->format_in_bdt($row->closed_amount).'</span>';
                })
                ->rawColumns(['action', 'created_at', 'closed_time', 'user', 'status', 'closed_amount'])
                ->make(true);
        }

        $branchUsers = DB::table('users')->where('allow_login', 1)->get();

        return view('reports.cash_register_report.index', compact('branchUsers'));
    }

    public function reportPrint(Request $request)
    {
        $fromDate = '';
        $toDate = '';

        $cashRegisters = '';

        $query = DB::table('cash_registers')
            ->leftJoin('users', 'cash_registers.admin_id', 'users.id')
            ->leftJoin('cash_register_transactions', 'cash_registers.id', 'cash_register_transactions.cash_register_id')
            ->leftJoin('sales', 'cash_register_transactions.sale_id', 'sales.id');

        if ($request->user_id) {

            $query->where('cash_registers.admin_id', $request->user_id);
        }

        if ($request->status) {

            if ($request->status == 1) {

                $query->where('cash_registers.status', 1);
            } elseif ($request->status == 2) {

                $query->where('cash_registers.status', 0);
            }
        }

        $fromDate = '';
        $toDate = '';

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('cash_registers.created_at', $date_range); // Final

            $fromDate = $from_date;
            $toDate = $to_date;
        }

        $query->select(
            'cash_registers.id',
            'cash_registers.date',
            'cash_registers.closed_at',
            'cash_registers.closed_amount',
            'cash_registers.status',
            'cash_registers.created_at',
            'cash_registers.closing_note',
            DB::raw('SUM(sales.total_payable_amount) as total_sale'),
            DB::raw('SUM(sales.paid) as total_paid'),
            DB::raw('SUM(sales.due) as total_due'),
            'users.prefix as u_prefix',
            'users.name as u_first_name',
            'users.last_name as u_last_name',
        );

        $cashRegisters = $query->orderBy('cash_registers.created_at', 'desc')->groupBy('cash_registers.id')->get();

        return view(
            'reports.cash_register_report.ajax_view.print_report',
            compact(
                'cashRegisters',
                'fromDate',
                'toDate',
            )
        );
    }
}
