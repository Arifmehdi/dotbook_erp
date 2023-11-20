<?php

namespace App\Utils;

use App\Models\SaleReturn;

class SaleReturnUtil
{
    public function addSaleReturn($sale, $request, $srUserId, $codeGenerationService, $returnVoucherPrefix)
    {
        $voucherPrefix = $returnVoucherPrefix != null ? $returnVoucherPrefix : auth()->user()->user_id;
        $voucherNo = $codeGenerationService->generateMonthWise(table: 'sale_returns', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-');

        $addSaleReturn = new SaleReturn();
        $addSaleReturn->total_item = $request->total_item;
        $addSaleReturn->total_qty = $request->total_qty;
        $addSaleReturn->sale_id = $request->sale_id;
        $addSaleReturn->voucher_no = $voucherNo;
        $addSaleReturn->customer_account_id = $request->customer_account_id;
        $addSaleReturn->warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : null;
        $addSaleReturn->sale_account_id = $request->sale_account_id;
        $addSaleReturn->sr_user_id = $srUserId;
        $addSaleReturn->created_by_id = auth()->user()->id;
        $addSaleReturn->all_price_type = $request->all_price_type;
        $addSaleReturn->return_discount_type = $request->return_discount_type;
        $addSaleReturn->return_discount = $request->return_discount;
        $addSaleReturn->return_discount_amount = $request->return_discount_amount;
        $addSaleReturn->tax_ac_id = $request->return_tax_ac_id;
        $addSaleReturn->return_tax_percent = $request->return_tax_percent;
        $addSaleReturn->return_tax_amount = $request->return_tax_amount;
        $addSaleReturn->net_total_amount = $request->net_total_amount;
        $addSaleReturn->total_return_amount = $request->total_return_amount;
        $addSaleReturn->date = $request->date;
        $addSaleReturn->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addSaleReturn->save();

        return $addSaleReturn;
    }

    public function updateSaleReturn($updateSaleReturn, $request, $srUserId)
    {
        $updateSaleReturn->total_item = $request->total_item;
        $updateSaleReturn->total_qty = $request->total_qty;
        $updateSaleReturn->sale_id = $request->sale_id;
        $updateSaleReturn->customer_account_id = $request->customer_account_id;
        $updateSaleReturn->sale_account_id = $request->sale_account_id;
        $updateSaleReturn->sr_user_id = $srUserId;
        $updateSaleReturn->return_discount_type = $request->return_discount_type;
        $updateSaleReturn->return_discount = $request->return_discount;
        $updateSaleReturn->return_discount_amount = $request->return_discount_amount;
        $updateSaleReturn->tax_ac_id = $request->return_tax_ac_id;
        $updateSaleReturn->return_tax_percent = $request->return_tax_percent;
        $updateSaleReturn->return_tax_amount = $request->return_tax_amount;
        $updateSaleReturn->net_total_amount = $request->net_total_amount;
        $updateSaleReturn->total_return_amount = $request->total_return_amount;
        $updateSaleReturn->all_price_type = $request->all_price_type;
        $updateSaleReturn->date = $request->date;
        $time = date(' H:i:s', strtotime($updateSaleReturn->report_date));
        $updateSaleReturn->report_date = date('Y-m-d H:i:s', strtotime($request->date.$time));
        $updateSaleReturn->save();

        return $updateSaleReturn;
    }
}
