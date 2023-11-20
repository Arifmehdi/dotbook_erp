<?php

namespace App\Utils;

use App\Models\StockIssue;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockIssueUtil
{
    public function stockIssueListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $stockIssues = '';
        $query = DB::table('stock_issues')
            ->leftJoin('departments', 'stock_issues.department_id', 'departments.id')
            ->leftJoin('users as created_by', 'stock_issues.created_by_id', 'created_by.id');

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

        $query->select(
            'stock_issues.id',
            'stock_issues.department_id',
            'stock_issues.stock_event_id',
            'stock_issues.date',
            'stock_issues.note',
            'stock_issues.voucher_no',
            'stock_issues.total_item',
            'stock_issues.total_qty',
            'stock_issues.net_total_value',
            'departments.name as dep_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        );

        $stockIssues = $query->orderBy('stock_issues.report_date', 'desc');

        return DataTables::of($stockIssues)

            ->addColumn('action', fn ($row) => $this->stockIssueTableAction($row))

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })
            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.\App\Utils\Converter::format_in_bdt($row->total_item).'</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_qty).'</span>')

            ->editColumn('net_total_value', fn ($row) => '<span class="net_total_value" data-value="'.$row->net_total_value.'">'.\App\Utils\Converter::format_in_bdt($row->net_total_value).'</span>')

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_qty', 'net_total_value', 'created_by'])
            ->make(true);
    }

    public function addStockIssue($request, $codeGenerationService)
    {
        $addStockIssue = new StockIssue();
        $voucherNo = $codeGenerationService->generateMonthWise(table: 'stock_issues', column: 'voucher_no', prefix: 'SI', splitter: '-', suffixSeparator: '-');
        $addStockIssue->voucher_no = $voucherNo;
        $addStockIssue->department_id = $request->department_id;
        $addStockIssue->stock_event_id = $request->stock_event_id;
        $addStockIssue->created_by_id = auth()->user()->id;
        $addStockIssue->total_item = $request->total_item;
        $addStockIssue->total_qty = $request->total_qty;
        $addStockIssue->net_total_value = $request->net_total_value;
        $addStockIssue->note = $request->note;
        $addStockIssue->date = $request->date;
        $addStockIssue->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addStockIssue->time = date('h:i:s a');
        $addStockIssue->save();

        return $addStockIssue;
    }

    public function updateStockIssue($updateStockIssue, $request)
    {
        $updateStockIssue->department_id = $request->department_id;
        $updateStockIssue->stock_event_id = $request->stock_event_id;
        $updateStockIssue->created_by_id = auth()->user()->id;
        $updateStockIssue->total_item = $request->total_item;
        $updateStockIssue->total_qty = $request->total_qty;
        $updateStockIssue->net_total_value = $request->net_total_value;
        $updateStockIssue->note = $request->note;
        $updateStockIssue->date = $request->date;
        $time = date(' H:i:s', strtotime($updateStockIssue->report_date));
        $updateStockIssue->report_date = date('Y-m-d H:i:s', strtotime($request->date.$time));
        $updateStockIssue->time = date('h:i:s a');
        $updateStockIssue->save();

        return $updateStockIssue;
    }

    private function stockIssueTableAction($row)
    {
        $html = '<div class="btn-group" role="group">';
        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
        $html .= '<a href="'.route('stock.issue.show', [$row->id]).'" class="dropdown-item" id="details_btn"> View</a>';

        if (auth()->user()->can('stock_issue_update')) {

            $html .= '<a href="'.route('stock.issue.edit', [$row->id]).' " class="dropdown-item"> Edit</a>';
        }

        if (auth()->user()->can('stock_issue_delete')) {

            $html .= '<a href="'.route('stock.issue.delete', $row->id).'" class="dropdown-item" id="delete"> Delete</a>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
