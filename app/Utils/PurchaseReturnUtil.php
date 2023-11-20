<?php

namespace App\Utils;

use App\Models\PurchaseReturn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnUtil
{
    public function returnList($request)
    {
        $returns = '';
        $generalSettings = DB::table('general_settings')->first();
        $query = DB::table('purchase_returns')
            ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
            ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id');

        if ($request->supplier_account_id) {

            $query->where('purchase_returns.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchase_returns.report_date', $date_range); // Final
        }

        $returns = $query->select(
            'purchase_returns.*',
            'purchases.invoice_id as p_invoice_id',
            'suppliers.name as sup_name',
        )->orderBy('purchase_returns.report_date', 'desc');

        return DataTables::of($returns)

            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="details_btn" href="'.route('purchases.returns.show', $row->id).'"> View</a>';

                $html .= '<a class="dropdown-item" href="'.route('purchases.returns.edit', $row->id).'"> Edit</a>';

                $html .= '<a class="dropdown-item" id="delete" href="'.route('purchases.returns.delete', $row->id).'"> Delete</a>';

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })

            ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount fw-bold" data-value="'.$row->total_return_amount.'">'.\App\Utils\Converter::format_in_bdt($row->total_return_amount).'</span>')

            ->rawColumns(['action', 'date', 'supplier', 'total_return_amount'])
            ->make(true);
    }

    public function addPurchaseReturn($request, $codeGenerationService, $voucherPrefix)
    {
        // generate invoice ID
        $__voucherPrefix = $voucherPrefix != null ? $voucherPrefix : auth()->user()->user_id;

        $voucherNo = $codeGenerationService->generateMonthWise(table: 'purchase_returns', column: 'voucher_no', prefix: $__voucherPrefix, splitter: '-', suffixSeparator: '-');

        $addPurchaseReturn = new PurchaseReturn();
        $addPurchaseReturn->voucher_no = $voucherNo;
        $addPurchaseReturn->purchase_id = $request->purchase_id;
        $addPurchaseReturn->supplier_account_id = $request->supplier_account_id;
        $addPurchaseReturn->purchase_account_id = $request->purchase_account_id;
        $addPurchaseReturn->total_item = $request->total_item;
        $addPurchaseReturn->total_qty = $request->total_qty;
        $addPurchaseReturn->net_total_amount = $request->net_total_amount;
        $addPurchaseReturn->return_discount_type = $request->return_discount_type;
        $addPurchaseReturn->return_discount_amount = $request->return_discount_amount ? $request->return_discount_amount : 0;
        $addPurchaseReturn->total_return_amount = $request->total_return_amount;
        $addPurchaseReturn->return_tax_percent = $request->return_tax_percent ? $request->return_tax_percent : 0;
        $addPurchaseReturn->return_tax_amount = $request->return_tax_amount ? $request->return_tax_amount : 0;
        $addPurchaseReturn->return_discount = $request->return_discount ? $request->return_discount : 0;
        $addPurchaseReturn->total_return_amount = $request->total_return_amount ? $request->total_return_amount : 0;
        $addPurchaseReturn->date = $request->date;
        $addPurchaseReturn->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addPurchaseReturn->created_by_id = auth()->user()->id;
        $addPurchaseReturn->save();

        return $addPurchaseReturn;
    }

    public function updatePurchaseReturn($updatePurchaseReturn, $request)
    {
        $updatePurchaseReturn->purchase_id = $request->purchase_id;
        $updatePurchaseReturn->supplier_account_id = $request->supplier_account_id;
        $updatePurchaseReturn->purchase_account_id = $request->purchase_account_id;
        $updatePurchaseReturn->total_item = $request->total_item;
        $updatePurchaseReturn->total_qty = $request->total_qty;
        $updatePurchaseReturn->net_total_amount = $request->net_total_amount;
        $updatePurchaseReturn->return_discount_type = $request->return_discount_type;
        $updatePurchaseReturn->return_discount_amount = $request->return_discount_amount ? $request->return_discount_amount : 0;
        $updatePurchaseReturn->total_return_amount = $request->total_return_amount ? $request->total_return_amount : 0;
        $updatePurchaseReturn->return_tax_percent = $request->return_tax_percent ? $request->return_tax_percent : 0;
        $updatePurchaseReturn->return_tax_amount = $request->return_tax_amount ? $request->return_tax_amount : 0;
        $updatePurchaseReturn->return_discount = $request->return_discount ? $request->return_discount : 0;
        $updatePurchaseReturn->total_return_amount = $request->total_return_amount ? $request->total_return_amount : 0;
        $updatePurchaseReturn->date = $request->date;
        $time = date(' H:i:s', strtotime($updatePurchaseReturn->report_date));
        $updatePurchaseReturn->report_date = date('Y-m-d H:i:s', strtotime($updatePurchaseReturn->date.$time));
        $updatePurchaseReturn->save();

        return $updatePurchaseReturn;
    }

    public function adjustPurchaseReturnAmounts($purchaseReturn)
    {
        $totalReturnPaid = DB::table('purchase_payments')
            ->where('purchase_payments.purchase_id', $purchaseReturn->purchase_id)
            ->where('purchase_payments.payment_type', 2)
            ->select(DB::raw('sum(paid_amount) as total_paid'))
            ->groupBy('purchase_payments.purchase_id')
            ->get();

        $due = $purchaseReturn->total_return_amount - $totalReturnPaid->sum('total_paid');
        $purchaseReturn->total_return_due_received = $totalReturnPaid->sum('total_paid');
        $purchaseReturn->total_return_due = $due;
        $purchaseReturn->save();
    }
}
