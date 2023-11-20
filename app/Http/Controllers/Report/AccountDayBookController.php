<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\DayBook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountDayBookController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $settings = DB::table('general_settings')->select('business')->first();

            $daybook = $this->daybookQuery($request);

            return DataTables::of($daybook)
                ->editColumn('date', function ($row) use ($settings) {

                    $dateFormat = json_decode($settings->business, true)['date_format'];
                    $__date_format = str_replace('-', '/', $dateFormat);

                    return date($__date_format.' h:i:s', strtotime($row->date_ts));
                })

                ->editColumn('particulars', function ($row) use ($request) {

                    $voucherType = $row->voucher_type;
                    $dayBookUtil = new \App\Utils\DayBookUtil();

                    return $dayBookUtil->particulars($request, $row->voucher_type, $row);
                })

                ->editColumn('voucher_type', function ($row) {

                    $daybookUtil = new \App\Utils\DayBookUtil();
                    $type = $daybookUtil->voucherType($row->voucher_type);

                    return '<strong>'.$type['name'].'</strong>';
                })

                ->editColumn('voucher_no', function ($row) {

                    $daybookUtil = new \App\Utils\DayBookUtil();
                    $type = $daybookUtil->voucherType($row->voucher_type);

                    return '<a href="'.(! empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#').'" id="details_btn" class="fw-bold">'.$row->{$type['voucher_no']}.'</a>';
                })
                ->editColumn('debit', fn ($row) => ($row->amount_type == 'debit' ? \App\Utils\Converter::format_in_bdt($row->amount) : ''))
                ->editColumn('credit', fn ($row) => ($row->amount_type == 'credit' ? \App\Utils\Converter::format_in_bdt($row->amount) : ''))
                ->rawColumns(['date', 'particulars', 'voucher_type', 'voucher_no', 'debit', 'credit'])
                ->make(true);
        }

        return view('finance.reports.day_book.index');
    }

    public function print(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $daybook = $this->daybookQuery($request)->get();

        return view(
            'finance.reports.day_book.ajax_view.print_day_book',
            compact(
                'daybook',
                'fromDate',
                'toDate',
                'request'
            )
        );
    }

    public function daybookQuery($request)
    {
        $query = DayBook::query();

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('day_books.date_ts', $date_range);
        }

        if ($request->voucher_type) {

            $query->where('day_books.voucher_type', $request->voucher_type);
        }

        $query->leftJoin('sales', 'day_books.sale_id', 'sales.id')
            ->leftJoin('sale_returns', 'day_books.sale_return_id', 'sale_returns.id')
            ->leftJoin('purchases', 'day_books.purchase_id', 'purchases.id')
            ->leftJoin('purchase_returns', 'day_books.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('stock_adjustments', 'day_books.stock_adjustment_id', 'stock_adjustments.id')
            ->leftJoin('contras', 'day_books.contra_id', 'contras.id')
            ->leftJoin('journals', 'day_books.journal_id', 'journals.id')
            ->leftJoin('payments', 'day_books.payment_id', 'payments.id')
            ->leftJoin('expanses', 'day_books.expense_id', 'expanses.id')
            ->leftJoin('daily_stocks', 'day_books.daily_stock_id', 'daily_stocks.id')
            ->leftJoin('receive_stocks', 'day_books.receive_stock_id', 'receive_stocks.id')
            ->leftJoin('stock_issues', 'day_books.stock_issue_id', 'stock_issues.id')
            ->with(
                [
                    'account:id,name,account_number,address',
                    'product:id,name,product_code',
                    'journal:id,created_by_id,remarks',
                    'journal.entries:id,journal_id,account_id,user_id,payment_method_id,transaction_no,cheque_no,cheque_serial_no,cheque_issue_date,remarkable_note,amount_type,amount',
                    'journal.entries.assignedUser:id,prefix,name,last_name',
                    'journal.entries.account:id,name,phone,account_number',
                    'journal.entries.paymentMethod:id,name',

                    'contra:id,voucher_no,remarks,user_id',
                    'contra.descriptions:id,contra_id,account_id,payment_method_id,transaction_no,cheque_no,cheque_serial_no,cheque_issue_date,amount_type,amount',
                    'contra.descriptions.account:id,name,account_number',
                    'contra.descriptions.paymentMethod:id,name',

                    'payment:id,created_by_id,remarks',
                    'payment.descriptions',
                    'payment.descriptions.account:id,name,account_number',
                    'payment.descriptions.user:id,prefix,name,last_name',
                    'payment.descriptions.paymentMethod:id,name',
                    'payment.descriptions.references:id,payment_description_id,sale_id,purchase_id,stock_adjustment_id,amount',
                    'payment.descriptions.references.sale:id,invoice_id,total_payable_amount',
                    'payment.descriptions.references.purchase:id,invoice_id,total_purchase_amount',

                    'sale:id,customer_account_id,total_payable_amount,sale_note,payment_note,sale_account_id,total_sold_qty,total_ordered_qty,order_discount_amount,order_tax_amount',
                    'sale.salesAccount:id,name',
                    'sale.customer:id,name,phone,address',
                    'sale.saleProducts:id,sale_id,product_id,product_variant_id,quantity,ordered_quantity,unit_id,unit_price_inc_tax,subtotal',
                    'sale.saleProducts.product:id,name',
                    'sale.saleProducts.saleUnit:id,code_name,base_unit_multiplier',
                    'sale.saleProducts.variant:id,variant_name',

                    'salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount,return_note',
                    'salesReturn.salesAccount:id,name',
                    'salesReturn.customer:id,name,phone,address',
                    'salesReturn.returnProducts:id,sale_return_id,product_id,unit_id,unit_price_inc_tax,return_qty,return_subtotal',
                    'salesReturn.returnProducts.product:id,name',
                    'salesReturn.returnProducts.variant:id,variant_name',
                    'salesReturn.returnProducts.returnUnit:id,code_name,base_unit_multiplier',

                    'purchase:id,supplier_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount,total_purchase_amount,purchase_note,payment_note,purchase_account_id',
                    'purchase.purchaseAccount:id,name',
                    'purchase.purchaseProducts:id,purchase_id,product_id,product_variant_id,unit_id,quantity,net_unit_cost,line_total',
                    'purchase.purchaseProducts.product:id,name',
                    'purchase.purchaseProducts.variant:id,variant_name',
                    'purchase.purchaseProducts.purchaseUnit:id,code_name,base_unit_multiplier',
                    'purchase.orderedProducts:id,purchase_id,product_id,product_variant_id,unit_id,order_quantity,net_unit_cost,line_total',
                    'purchase.orderedProducts.product:id,name',
                    'purchase.orderedProducts.variant:id,variant_name',
                    'purchase.orderedProducts.orderUnit:id,code_name,base_unit_multiplier',

                    'purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturn.returnProducts:id,purchase_return_id,product_id,unit_id,unit_cost_inc_tax,return_qty,return_subtotal',
                    'purchaseReturn.returnProducts.product:id,name',
                    'purchaseReturn.returnProducts.variant:id,variant_name',
                    'purchaseReturn.returnProducts.returnUnit:id,code_name,base_unit_multiplier',

                    'stockAdjustment:id,expense_account_id,total_qty,net_total_amount,recovered_amount,type,reason',
                    'stockAdjustment:account:id,name',
                    'stockAdjustment:adjustmentProducts:id,product_id,product_variant_id,quantity,unit,unit_cost_inc_tax,subtotal',
                    'stockAdjustment:adjustmentProducts.product:id,name',
                    'stockAdjustment:adjustmentProducts.variant:id,variant_name',

                    'expense:id,total_amount,tax_amount,net_total_amount,paid,due,note',
                    'expense.expenseDescriptions:id,expense_id,account_id,amount,payment_method_id,transaction_no,cheque_no,cheque_no,amount_type,cheque_issue_date',
                    'expense.expenseDescriptions.account:id,name',
                    'expense.expenseDescriptions.paymentMethod:id,name',
                    'expense.purchase:id,invoice_id,expense_id',

                    'dailyStock',
                    'dailyStock.dailyStockProducts',
                    'dailyStock.dailyStockProducts.product:id,name',

                    'receiveStock',
                    'receiveStock.receiveStockProducts',
                    'receiveStock.receiveStockProducts.product:id,name',
                    'receiveStock.receiveStockProducts.variant:id,variant_name',
                    'receiveStock.receiveStockProducts.receiveUnit:id,code_name,base_unit_multiplier',

                    'StockIssue',
                    'StockIssue.issueProducts',
                    'StockIssue.issueProducts.product:id,name',
                    'StockIssue.issueProducts.variant:id,variant_name',
                    'StockIssue.issueProducts.issueUnit:id,code_name,base_unit_multiplier',
                ]
            )
            ->select(
                'day_books.id',
                'day_books.date_ts',
                'day_books.voucher_type',
                'day_books.account_id',
                'day_books.product_id',
                'day_books.sale_id as daybook_sale_id',
                'day_books.sale_return_id as daybook_sale_return_id',
                'day_books.purchase_id as daybook_purchase_id',
                'day_books.purchase_return_id as daybook_purchase_return_id',
                'day_books.stock_adjustment_id as daybook_stock_adjustment_id',
                'day_books.payment_id as daybook_payment_id',
                'day_books.journal_id as daybook_journal_id',
                'day_books.expense_id as daybook_expense_id',
                'day_books.contra_id as daybook_contra_id',
                'day_books.daily_stock_id as daybook_daily_id',
                'day_books.receive_stock_id as daybook_receive_stock_id',
                'day_books.stock_issue_id as daybook_stock_issue_id',
                'day_books.amount',
                'day_books.amount_type',
                'sales.id as sale_id',
                'sales.invoice_id as sales_voucher',
                'sales.order_id as sales_order_voucher',
                'sale_returns.id as sale_return_id',
                'sale_returns.voucher_no as sale_return_voucher',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_voucher',
                'purchase_returns.id as purchase_return_id',
                'purchase_returns.voucher_no as purchase_return_voucher',
                'stock_adjustments.id as stock_adjustment_id',
                'stock_adjustments.voucher_no as stock_adjustment_voucher',
                'contras.id as contra_id',
                'contras.voucher_no as contra_voucher',
                'journals.id as journal_id',
                'journals.voucher_no as journal_voucher',
                'payments.id as payment_id',
                'payments.voucher_no as payment_voucher',
                'expanses.id as expense_id',
                'expanses.voucher_no as expense_voucher',
                'daily_stocks.id as daily_stock_id',
                'daily_stocks.voucher_no as daily_stock_voucher',
                'receive_stocks.id as receive_stock_id',
                'receive_stocks.voucher_no as receive_stock_voucher',
                'stock_issues.id as stock_issue_id',
                'stock_issues.voucher_no as stock_issue_voucher',
            );

        return $query->orderBy('day_books.date_ts', 'asc');
    }
}
