<?php

namespace App\Utils;

use App\Models\StockAdjustment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentUtil
{
    public function stockAdjustmentList($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $adjustments = '';
        $query = DB::table('stock_adjustments')
            ->leftJoin('users', 'stock_adjustments.created_by_id', 'users.id');

        if ($request->type) {

            $query->where('stock_adjustments.type', $request->type);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('stock_adjustments.date_ts', $date_range); // Final
        }

        $query->select(
            'stock_adjustments.*',
            'users.prefix',
            'users.name',
            'users.last_name',
        );

        $adjustments = $query->orderBy('stock_adjustments.date_ts', 'desc');

        return DataTables::of($adjustments)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="details_btn" href="'.route('stock.adjustments.show', [$row->id]).'"><i class="far fa-eye text-primary"></i> View</a>';

                if (auth()->user()->can('stock_adjustments_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('stock.adjustments.delete', $row->id).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
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

            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'adjustment_location', 'type', 'net_total_amount', 'recovered_amount', 'created_by'])
            ->make(true);
    }

    public function addStockAdjustment($request, $codeGenerationService, $voucherPrefix)
    {
        $__voucherPrefix = $voucherPrefix != null ? $voucherPrefix : auth()->user()->user_id;
        $voucherNo = $codeGenerationService->generateMonthWise('stock_adjustments', 'voucher_no', $__voucherPrefix, 4, 13, '-', '-');

        $addStockAdjustment = new StockAdjustment();
        $addStockAdjustment->voucher_no = $voucherNo;
        $addStockAdjustment->expense_account_id = $request->expense_account_id;
        $addStockAdjustment->type = $request->type;
        $addStockAdjustment->total_item = $request->total_item;
        $addStockAdjustment->total_qty = $request->total_qty;
        $addStockAdjustment->net_total_amount = $request->net_total_amount;
        $addStockAdjustment->recovered_amount = $request->recovered_amount ? $request->recovered_amount : 0;
        $addStockAdjustment->date = $request->date;
        $addStockAdjustment->date_ts = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addStockAdjustment->created_by_id = auth()->user()->id;
        $addStockAdjustment->reason = $request->reason;
        $addStockAdjustment->save();

        return $addStockAdjustment;
    }
}
