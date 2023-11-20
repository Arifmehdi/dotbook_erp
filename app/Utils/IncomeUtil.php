<?php

namespace App\Utils;

use App\Models\IncomeReceipt;
use Illuminate\Support\Facades\DB;

class IncomeUtil
{
    protected $invoiceVoucherRefIdUtil;

    protected $converter;

    protected $accountUtil;

    public function __construct(
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        Converter $converter,
        AccountUtil $accountUtil
    ) {
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->accountUtil = $accountUtil;
    }

    public function adjustIncomeAmount($income)
    {
        $totalIncomeReceived = DB::table('income_receipts')
            ->where('income_receipts.income_id', $income->id)
            ->select(DB::raw('sum(amount) as total_received'))
            ->groupBy('income_receipts.income_id')
            ->get();

        $due = $income->total_amount - $totalIncomeReceived->sum('total_received');
        $income->received = $totalIncomeReceived->sum('total_received');
        $income->due = $due;
        $income->save();

        return $income;
    }

    public function addReceiptGetId($income_id, $request, $another_amount = 0, $custom_date = null)
    {
        $date = $custom_date ? $custom_date : $request->date;

        $addIncomeReceipt = new IncomeReceipt();
        $addIncomeReceipt->voucher_no = 'IRV'.str_pad($this->invoiceVoucherRefIdUtil->getLastId('income_receipts'), 4, '0', STR_PAD_LEFT);
        $addIncomeReceipt->income_id = $income_id;
        $addIncomeReceipt->account_id = $request->account_id;
        $addIncomeReceipt->payment_method_id = $request->payment_method_id;
        $addIncomeReceipt->amount = $another_amount > 0 ? $another_amount : (isset($request->received_amount) ? $request->received_amount : 0);
        $addIncomeReceipt->date = $date;
        $addIncomeReceipt->report_date = date('Y-m-d H:i:s', strtotime($date.date(' H:i:s')));
        $addIncomeReceipt->note = $request->received_note;
        $addIncomeReceipt->created_by_id = auth()->user()->id;
        $addIncomeReceipt->save();

        return $addIncomeReceipt->id;
    }

    public function updateReceipt($receipt, $request, $another_amount = 0)
    {
        $receipt->account_id = $request->account_id;
        $receipt->payment_method_id = $request->payment_method_id;
        $receipt->amount = isset($request->received_amount) ? $request->received_amount : $another_amount;
        $receipt->date = $request->date;
        $receipt->report_date = date('Y-m-d', strtotime($request->date));
        $receipt->note = $request->received_note;
        $receipt->save();
    }
}
