<?php

namespace Modules\Contacts\Services;

use App\Models\MoneyReceipt;

class MoneyReceiptService
{
    public function addMoneyReceipt($customerId, $request, $codeGenerationService)
    {
        $invoiceId = $codeGenerationService->generateMonthWise('sales', 'invoice_id', 'MR', 4, 13, '-', '-');

        $addReceipt = new MoneyReceipt();
        $addReceipt->invoice_id = $invoiceId;
        $addReceipt->customer_id = $customerId;
        $addReceipt->amount = $request->amount;
        $addReceipt->note = $request->note;
        $addReceipt->receiver = $request->receiver;
        $addReceipt->ac_details = $request->ac_details;
        $addReceipt->is_date = isset($request->is_date) ? 1 : 0;
        $addReceipt->is_customer_name = isset($request->is_customer_name) ? 1 : 0;
        $addReceipt->is_header_less = isset($request->is_header_less) ? 1 : 0;
        $addReceipt->gap_from_top = isset($request->is_header_less) ? $request->gap_from_top : null;
        $addReceipt->date = date('d-m-Y');
        $addReceipt->save();

        return $addReceipt;
    }

    public function updateMoneyReceipt($request, $receiptId)
    {
        $updateReceipt = MoneyReceipt::where('id', $receiptId)->first();
        $updateReceipt->amount = $request->amount;
        $updateReceipt->note = $request->note;
        $updateReceipt->receiver = $request->receiver;
        $updateReceipt->ac_details = $request->ac_details;
        $updateReceipt->is_date = isset($request->is_date) ? 1 : 0;
        $updateReceipt->is_header_less = isset($request->is_header_less) ? 1 : 0;
        $updateReceipt->gap_from_top = isset($request->is_header_less) ? $request->gap_from_top : null;
        $updateReceipt->is_customer_name = isset($request->is_customer_name) ? 1 : 0;
        $updateReceipt->date = date('d-m-Y');
        $updateReceipt->save();

        return $updateReceipt;
    }
}
