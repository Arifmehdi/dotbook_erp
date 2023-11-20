<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\AccountLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerCashFlowController extends Controller
{
    public function index($accountId, $cashFlowSide, $fromDate = null, $toDate = null)
    {
        $account = DB::table('accounts')->where('id', $accountId)->select('id', 'name', 'account_number', 'phone')->first();

        return view('finance.reports.cash_flow.ledger_cash_flow.index', compact('account', 'cashFlowSide', 'fromDate', 'toDate'));
    }

    public function ledgerCashFlowView(Request $request, $accountId, $cashFlowSide)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $ledgerCashflow = $this->ledgerCashQuery($request, $accountId, $cashFlowSide);
        $by = 'accountId';

        return view('finance.reports.cash_flow.ledger_cash_flow.ajax_view.ledger_cash_flow_blade_view', compact('ledgerCashflow', 'request', 'fromDate', 'toDate', 'by'));
    }

    public function ledgerCashFlowBladePrint(Request $request, $accountId, $cashFlowSide)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $ledgerCashflow = $this->ledgerCashQuery($request, $accountId, $cashFlowSide);
        $by = 'accountId';

        $account = DB::table('accounts')->where('id', $accountId)->select('id', 'name', 'account_number', 'phone')->first();

        return view('finance.reports.cash_flow.ledger_cash_flow.ajax_view.ledger_cash_flow_blade_view_print', compact('ledgerCashflow', 'request', 'fromDate', 'toDate', 'by', 'account', 'fromDate', 'toDate'));
    }

    private function ledgerCashQuery($request, $accountId, $cashFlowSide)
    {
        $query = AccountLedger::query()
            ->whereRaw('concat(account_ledgers.debit,account_ledgers.credit) > 0')
            ->where('account_ledgers.is_cash_flow', 1);

        $query->where('account_ledgers.account_id', $accountId);

        if ($request->user_id) {

            $query->where('account_ledgers.user_id', $request->user_id);
        }

        if ($cashFlowSide == 'in') {

            $query->where('account_ledgers.amount_type', 'credit');
        } elseif ($cashFlowSide == 'out') {

            $query->where('account_ledgers.amount_type', 'debit');
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('account_ledgers.date', $date_range);
        }

        $query->leftJoin('sales', 'account_ledgers.sale_id', 'sales.id')
            ->leftJoin('sale_returns', 'account_ledgers.sale_return_id', 'sale_returns.id')
            ->leftJoin('sale_products', 'account_ledgers.sale_product_id', 'sale_products.id')
            ->leftJoin('sales as productSale', 'sale_products.sale_id', 'productSale.id')
            ->leftJoin('sale_return_products', 'account_ledgers.sale_return_product_id', 'sale_return_products.id')
            ->leftJoin('sale_returns as productSaleReturn', 'sale_return_products.sale_return_id', 'productSaleReturn.id')
            ->leftJoin('purchases', 'account_ledgers.purchase_id', 'purchases.id')
            ->leftJoin('purchase_products', 'account_ledgers.purchase_product_id', 'purchase_products.id')
            ->leftJoin('purchases as productPurchase', 'purchase_products.purchase_id', 'productPurchase.id')
            ->leftJoin('purchase_returns', 'account_ledgers.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('purchase_return_products', 'account_ledgers.purchase_return_product_id', 'purchase_return_products.id')
            ->leftJoin('purchase_returns as productPurchaseReturn', 'purchase_return_products.purchase_return_id', 'productPurchaseReturn.id')
            ->leftJoin('stock_adjustments', 'account_ledgers.adjustment_id', 'stock_adjustments.id')
            ->leftJoin('contra_descriptions', 'account_ledgers.contra_description_id', 'contra_descriptions.id')
            ->leftJoin('contras', 'contra_descriptions.contra_id', 'contras.id')
            ->leftJoin('journal_entries', 'account_ledgers.journal_entry_id', 'journal_entries.id')
            ->leftJoin('journals', 'journal_entries.journal_id', 'journals.id')
            ->leftJoin('payment_descriptions', 'account_ledgers.payment_description_id', 'payment_descriptions.id')
            ->leftJoin('payments', 'payment_descriptions.payment_id', 'payments.id')
            ->leftJoin('expense_descriptions', 'account_ledgers.expense_description_id', 'expense_descriptions.id')
            ->leftJoin('expanses', 'expense_descriptions.expense_id', 'expanses.id')
            ->with(
                [
                    'account:id,name,account_number,account_group_id',
                    'account.group:id,name,sub_group_number,sub_sub_group_number',
                    'user:id,prefix,name,last_name',
                    'journalEntry',
                    'journalEntry.journal',
                    'journalEntry.journal.entries',
                    'journalEntry.journal.entries.assignedUser:id,prefix,name,last_name',
                    'journalEntry.journal.entries.account:id,name,phone,account_number,account_group_id',
                    'journalEntry.journal.entries.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'journalEntry.journal.entries.paymentMethod:id,name',
                    'contraDescription',
                    'contraDescription.contra',
                    'contraDescription.contra.descriptions',
                    'contraDescription.contra.descriptions.account:id,name,account_number',
                    'contraDescription.contra.descriptions.paymentMethod:id,name',
                    'paymentDescription.user:id,prefix,name,last_name',
                    'paymentDescription.payment',
                    'paymentDescription.payment.descriptions',
                    'paymentDescription.payment.descriptions.account:id,name,account_number,account_group_id',
                    'paymentDescription.payment.descriptions.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'paymentDescription.payment.descriptions.user:id,prefix,name,last_name',
                    'paymentDescription.payment.descriptions.paymentMethod:id,name',
                    'paymentDescription.payment.descriptions.references:id,payment_description_id,sale_id,purchase_id,stock_adjustment_id,amount',
                    'paymentDescription.payment.descriptions.references.sale:id,invoice_id,order_id,order_status',
                    'paymentDescription.payment.descriptions.references.purchase:id,invoice_id,purchase_status',
                    'sale:id,customer_account_id,total_payable_amount,sale_note,payment_note,sale_account_id,total_sold_qty,order_discount_amount,order_tax_amount',
                    'sale.salesAccount:id,name',
                    'sale.customer:id,name,phone,address',
                    'sale.saleProducts:id,sale_id,product_id,product_variant_id,quantity,unit,unit_price_inc_tax,subtotal',
                    'sale.saleProducts.product:id,name',
                    'sale.saleProducts.variant:id,variant_name',
                    'salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount,return_note',
                    'salesReturn.salesAccount:id,name',
                    'salesReturn.customer:id,name,phone,address',
                    'salesReturn.returnProducts:id,sale_return_id,product_id,unit,unit_price_inc_tax,return_qty,return_subtotal',
                    'salesReturn.returnProducts.product:id,name',
                    'salesReturn.returnProducts.variant:id,variant_name',
                    'purchase:id,supplier_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount,total_purchase_amount,purchase_note,payment_note,purchase_account_id',
                    'purchase.purchaseAccount:id,name',
                    'purchase.purchaseProducts:id,purchase_id,product_id,product_variant_id,unit,quantity,net_unit_cost,line_total',
                    'purchase.purchaseProducts.product:id,name',
                    'purchaseProduct:id,purchase_id,tax_ac_id',
                    'purchaseProduct.purchase:id,supplier_account_id,total_purchase_amount,purchase_note,payment_note,purchase_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount',
                    'purchaseProduct.purchase.purchaseAccount:id,name',
                    'purchaseProduct.purchase.supplier:id,name',
                    'purchaseProduct.purchase.purchaseProducts:id,purchase_id,product_id,product_variant_id,quantity,unit,tax_ac_id,unit_tax_percent,unit_tax_amount,net_unit_cost,line_total',
                    'purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturn.returnProducts:id,purchase_return_id,product_id,unit,unit_cost_inc_tax,return_qty,return_subtotal',
                    'purchaseReturn.returnProducts.product:id,name',
                    'purchaseReturn.returnProducts.variant:id,variant_name',
                    'stockAdjustment:id,expense_account_id,total_qty,net_total_amount,recovered_amount,type,reason',
                    'stockAdjustment:account:id,name',
                    'stockAdjustment:adjustmentProducts:id,adjustmentProducts,product_id,product_variant_id,quantity,unit,unit_cost_inc_tax,subtotal',
                    'stockAdjustment:adjustmentProducts.product:id,name',
                    'stockAdjustment:adjustmentProducts.variant:id,variant_name',
                    'saleProduct:id,sale_id,tax_ac_id',
                    'saleProduct.sale:id,customer_account_id,total_payable_amount,sale_note,payment_note,sale_account_id,total_sold_qty,order_discount_amount,order_tax_amount',
                    'saleProduct.sale.salesAccount:id,name',
                    'saleProduct.sale.customer:id,name',
                    'saleProduct.sale.saleProducts:id,sale_id,product_id,product_variant_id,quantity,unit,tax_ac_id,unit_tax_percent,unit_tax_amount,unit_price_inc_tax,subtotal',

                    'purchaseReturnProduct:id,purchase_return_id,tax_ac_id',
                    'purchaseReturnProduct:purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturnProduct.purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturnProduct.purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturnProduct.purchaseReturn.returnProducts:id,purchase_return_id,product_id,product_variant_id,unit,unit_cost_inc_tax,return_qty,return_subtotal',
                    'purchaseReturnProduct.purchaseReturn.returnProducts.product:id,name',
                    'purchaseReturnProduct.purchaseReturn.returnProducts.variant:id,variant_name',

                    'salesReturnProduct:id,sale_return_id,tax_ac_id',
                    'salesReturnProduct:salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'salesReturnProduct.salesReturn.salesAccount:id,name',
                    'salesReturnProduct.salesReturn.customer:id,name,phone,address',
                    'salesReturnProduct.salesReturn.returnProducts:id,sale_return_id,product_id,product_variant_id,unit,unit_price_inc_tax,unit_tax_percent,unit_tax_amount,return_qty,return_subtotal',
                    'salesReturnProduct.salesReturn.returnProducts.product:id,name',
                    'salesReturnProduct.salesReturn.returnProducts.variant:id,variant_name',

                    'expenseDescription:id,expense_id',
                    'expenseDescription.expense:id,note,purchase_ref_id',
                    'expenseDescription.expense.expenseDescriptions:id,expense_id,account_id,amount_type,amount',
                    'expenseDescription.expense.expenseDescriptions.account:id,name,account_group_id',
                    'expenseDescription.expense.expenseDescriptions.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'expenseDescription.expense.purchase:id,invoice_id',
                ]
            )
            ->select(
                'account_ledgers.user_id',
                'account_ledgers.date',
                'account_ledgers.voucher_type',
                'account_ledgers.account_id',
                'account_ledgers.sale_id',
                'account_ledgers.sale_product_id',
                'account_ledgers.sale_return_id',
                'account_ledgers.sale_return_product_id',
                'account_ledgers.purchase_id',
                'account_ledgers.purchase_product_id',
                'account_ledgers.purchase_return_id',
                'account_ledgers.purchase_return_product_id',
                'account_ledgers.adjustment_id',
                'account_ledgers.payment_description_id',
                'account_ledgers.journal_entry_id',
                'account_ledgers.expense_description_id',
                'account_ledgers.debit',
                'account_ledgers.credit',
                'account_ledgers.running_balance',
                'account_ledgers.amount_type',
                'account_ledgers.contra_description_id',
                'sales.id as sale_id',
                'sales.invoice_id as sales_voucher',
                'sale_returns.id as sale_return_id',
                'sale_returns.voucher_no as sale_return_voucher',
                'productSaleReturn.id as product_sale_return_id',
                'productSaleReturn.voucher_no as product_sale_return_voucher',
                'productSale.id as product_sale_id',
                'productSale.invoice_id as product_sale_voucher',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_voucher',
                'productPurchase.id as product_purchase_id',
                'productPurchase.invoice_id as product_purchase_voucher',
                'purchase_returns.id as purchase_return_id',
                'purchase_returns.voucher_no as purchase_return_voucher',
                'productPurchaseReturn.id as product_purchase_return_id',
                'productPurchaseReturn.voucher_no as product_purchase_return_voucher',
                'stock_adjustments.id as adjustment_id',
                'stock_adjustments.voucher_no as stock_adjustment_voucher',
                'contras.id as contra_id',
                'contras.voucher_no as contra_voucher',
                'journals.id as journal_id',
                'journals.voucher_no as journal_voucher',
                'payments.id as payment_id',
                'payments.voucher_no as payment_voucher',
                'expanses.id as expense_id',
                'expanses.voucher_no as expense_voucher',
            );

        return $query->orderBy('account_ledgers.date', 'asc')->get();
    }
}
