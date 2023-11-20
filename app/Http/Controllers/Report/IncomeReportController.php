<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Utils\Converter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class IncomeReportController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    // Index view of expense report
    public function index(Request $request)
    {
        if (! auth()->user()->can('income_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $converter = $this->converter;

            $generalSettings = DB::table('general_settings')->first();
            $expenses = '';

            $query = DB::table('incomes')
                ->leftJoin('users', 'incomes.created_by_id', 'users.id');

            if ($request->user_id) {

                $query->where('incomes.created_by_id', $request->user_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('incomes.report_date', $date_range); // Final
            }

            $expenses = $query->select(
                'incomes.*',
                'users.prefix as cr_prefix',
                'users.name as cr_name',
                'users.last_name as cr_last_name',
            )->orderBy('incomes.report_date', 'desc');

            return DataTables::of($expenses)
                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->report_date));
                })
                ->editColumn('user_name', function ($row) {
                    return $row->cr_prefix.' '.$row->cr_name.' '.$row->cr_last_name;
                })
                ->editColumn('receive_status', function ($row) {
                    $html = '';
                    $receivable = $row->total_amount;
                    if ($row->due <= 0) {

                        $html .= '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $receivable) {

                        $html .= '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($receivable == $row->due) {

                        $html .= '<span class="badge bg-danger text-white">Due</span>';
                    }

                    return $html;
                })
                ->editColumn('total_amount', fn ($row) => '<span class="total_amount" data-value="'.$row->total_amount.'">'.$this->converter->format_in_bdt($row->total_amount).'</span>')
                ->editColumn('received', fn ($row) => '<span class="received" data-value="'.$row->received.'">'.$this->converter->format_in_bdt($row->received).'</span>')
                ->editColumn('due', fn ($row) => '<span class="due" data-value="'.$row->due.'" class="text-danger">'.$this->converter->format_in_bdt($row->due).'</span>')
                ->rawColumns(['action', 'date', 'user_name', 'receive_status', 'received', 'due', 'total_amount'])
                ->make(true);
        }

        $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name')->get();

        return view('reports.income_report.index', compact('users'));
    }

    public function print(Request $request)
    {
        $incomes = '';
        $fromDate = '';
        $toDate = '';
        $query = Income::query()->leftJoin('users', 'incomes.created_by_id', 'users.id');

        if ($request->user_id) {

            $query->where('incomes.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('incomes.report_date', $date_range); // Final
        }

        $incomes = $query
            ->with(
                'incomeDescriptions:id,income_id,income_account_id,amount',
                'incomeDescriptions.account:id,name,account_number,account_type',
            )
            ->select(
                'incomes.*',
                'users.prefix as cr_prefix',
                'users.name as cr_name',
                'users.last_name as cr_last_name',
            )->orderBy('incomes.report_date', 'desc')->get();

        return view('reports.income_report.ajax_view.print', compact('incomes', 'fromDate', 'toDate'));
    }
}
