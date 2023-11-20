<?php

namespace App\Utils;

use App\Models\AccountLedger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountLedgerUtil
{
    public function voucherType($voucher_type_id)
    {
        $data = [
            0 => ['name' => 'Opening Balance', 'id' => 'account_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'account_id', 'link' => ''],
            1 => ['name' => 'Sales', 'id' => 'sale_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'sale_id', 'link' => 'sales.show'],
            2 => ['name' => 'Sales Return', 'id' => 'sale_return_id', 'voucher_no' => 'sale_return_voucher', 'details_id' => 'sale_return_id', 'link' => 'sales.returns.show'],
            3 => ['name' => 'Purchase', 'id' => 'purchase_id', 'voucher_no' => 'purchase_voucher', 'details_id' => 'purchase_id', 'link' => 'purchases.show'],
            4 => ['name' => 'Purchase Return', 'id' => 'purchase_return_id', 'voucher_no' => 'purchase_return_voucher', 'details_id' => 'purchase_return_id', 'link' => 'purchases.returns.show'],
            5 => ['name' => 'Expenses', 'id' => 'expense_description_id', 'voucher_no' => 'expense_voucher', 'details_id' => 'expense_id', 'link' => 'vouchers.expenses.show'],
            7 => ['name' => 'Stock Adjustment', 'id' => 'adjustment_id', 'voucher_no' => 'stock_adjustment_voucher', 'details_id' => 'adjustment_id', 'link' => 'stock.adjustments.show'],
            8 => ['name' => 'Receipt', 'id' => 'payment_description_id', 'voucher_no' => 'payment_voucher', 'details_id' => 'payment_id', 'link' => 'vouchers.receipts.show'],
            9 => ['name' => 'Payment', 'id' => 'payment_description_id', 'voucher_no' => 'payment_voucher', 'details_id' => 'payment_id', 'link' => 'vouchers.payments.show'],
            12 => ['name' => 'Contra', 'id' => 'contra_description_id', 'voucher_no' => 'contra_voucher', 'details_id' => 'contra_id', 'link' => 'vouchers.contras.show'],
            13 => ['name' => 'Journal', 'id' => 'journal_entry_id', 'voucher_no' => 'journal_voucher', 'details_id' => 'journal_id', 'link' => 'vouchers.journals.show'],
            15 => ['name' => 'Income Receipt', 'id' => 'income_receipt_id', 'voucher_no' => 'income_receipt_voucher', 'details_id' => 'income_receipt_id', 'link' => ''],
            16 => ['name' => 'Sales', 'id' => 'sale_product_id', 'voucher_no' => 'product_sale_voucher', 'details_id' => 'product_sale_id', 'link' => 'sales.show'],
            17 => ['name' => 'Purchase', 'id' => 'purchase_product_id', 'voucher_no' => 'product_purchase_voucher', 'details_id' => 'product_purchase_id', 'link' => 'purchases.show'],
            18 => ['name' => 'Sales Return', 'id' => 'sale_return_product_id', 'voucher_no' => 'product_sale_return_voucher', 'details_id' => 'product_sale_return_id', 'link' => 'sales.returns.show'],
            19 => ['name' => 'Purchase Return', 'id' => 'purchase_return_product_id', 'voucher_no' => 'product_purchase_return_voucher', 'details_id' => 'product_purchase_return_id', 'link' => 'purchases.returns.show'],
            20 => ['name' => 'Daily Stock', 'id' => 'daily_stock_product_id', 'voucher_no' => 'daily_stock_voucher', 'details_id' => 'product_daily_stock_id', 'link' => ''],
        ];

        return $data[$voucher_type_id];
    }

    public function addAccountLedger(
        $voucher_type_id,
        $date,
        $account_id,
        $trans_id,
        $amount,
        $amount_type,
        $user_id = null,
        $cash_bank_account_id = null
    ) {
        $voucherType = $this->voucherType($voucher_type_id);
        $add = new AccountLedger();
        $add->user_id = $user_id;
        $time = $voucher_type_id == 0 ? ' 01:00:00' : date(' H:i:s');
        $add->date = date('Y-m-d H:i:s', strtotime($date.$time));
        $add->account_id = $account_id;
        $add->voucher_type = $voucher_type_id;
        $add->{$voucherType['id']} = $trans_id;
        $add->{$amount_type} = $amount;
        $add->amount_type = $amount_type;
        $add->is_cash_flow = isset($cash_bank_account_id) ? 1 : 0;
        $add->save();
    }

    public function updateAccountLedger(
        $voucher_type_id,
        $date,
        $account_id,
        $trans_id,
        $amount,
        $amount_type,
        $user_id = null,
        $new_user_id = null,
        $current_account_id = null,
        $cash_bank_account_id = null
    ) {
        $voucherType = $this->voucherType($voucher_type_id);

        $update = '';
        $query = AccountLedger::where($voucherType['id'], $trans_id)->where('voucher_type', $voucher_type_id);

        if ($user_id) {

            $query->where('user_id', $user_id);
        }

        if ($current_account_id) {

            $query->where('account_id', $current_account_id);
        }

        $update = $query->first();

        // dd($update);

        if ($update) {

            $update->debit = 0;
            $update->credit = 0;
            $previousAccountId = $update->account_id;
            $previousTime = date(' H:i:s', strtotime($update->date));
            $update->date = date('Y-m-d H:i:s', strtotime($date.$previousTime));
            $update->user_id = $new_user_id ? $new_user_id : $user_id;
            $update->account_id = $account_id;
            $update->{$amount_type} = $amount;
            $update->amount_type = $amount_type;
            $update->is_cash_flow = isset($cash_bank_account_id) ? 1 : 0;
            $update->save();
        } else {

            $this->addAccountLedger(
                $voucher_type_id,
                $date,
                $account_id,
                $trans_id,
                $amount,
                $amount_type,
                $user_id,
                $cash_bank_account_id
            );
        }
    }

    public function deleteUnusedLedgerEntry($voucherType, $transId, $accountId)
    {
        $voucherType = $this->voucherType($voucherType);
        $deleteAccountLedger = AccountLedger::where('voucher_type', $voucherType)
            ->where($voucherType['id'], $transId)->where('account_id', $accountId)->first();

        if (! is_null($deleteAccountLedger)) {

            $deleteAccountLedger->delete();
        }
    }

    public function ledgerEntries($request, $id, $by = 'accountId')
    {
        $ledgers = '';
        $settings = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($settings->business, true)['start_date']));

        $ledgers = $this->ledgerEntriesQuery($request, $id, $by);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $userId = $request->user_id ? $request->user_id : null;
            $accountOpeningBalance = '';

            if ($by == 'accountId') {

                $accountOpeningBalanceQ = DB::table('account_ledgers')->where('account_ledgers.account_id', $id);
            } else {

                $accountOpeningBalanceQ = DB::table('account_ledgers')->where('account_ledgers.user_id', $id);
            }

            if ($request->user_id) {

                $accountOpeningBalanceQ->where('account_ledgers.user_id', $request->user_id);
            }

            $accountOpeningBalance = $accountOpeningBalanceQ->select(
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
            )->groupBy('account_ledgers.account_id')->get();

            $openingBalanceDebit = $accountOpeningBalance->sum('opening_total_debit');
            $openingBalanceCredit = $accountOpeningBalance->sum('opening_total_credit');

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';
            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                $currOpeningBalanceSide = 'dr';
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $arr = [
                'id' => 0,
                'user_id' => $request->user_id ? $request->user_id : null,
                'user' => $request->user_id ? (object) ['id' => $request->user_id, 'prefix' => null, 'name' => $request->user_name, 'last_name' => null] : null,
                'voucher_type' => 0,
                'sales_voucher' => null,
                'date' => null,
                'account_id' => $id,
                'amount_type' => $currOpeningBalanceSide == 'dr' ? 'debit' : 'credit',
                'debit' => $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0.00,
                'credit' => $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0.00,
                'running_balance' => 0,
                'balance_type' => ' Dr',
            ];

            $stdArr = (object) $arr;

            $ledgers->prepend($stdArr);
        }

        // return $ledgers;

        $runningDebit = 0;
        $runningCredit = 0;
        foreach ($ledgers as $ledger) {

            $runningDebit += $ledger->debit;
            $runningCredit += $ledger->credit;

            if ($runningDebit > $runningCredit) {

                $ledger->running_balance = $runningDebit - $runningCredit;
                $ledger->balance_type = ' Dr.';
            } elseif ($runningCredit > $runningDebit) {

                $ledger->running_balance = $runningCredit - $runningDebit;
                $ledger->balance_type = ' Cr.';
            }
        }

        return DataTables::of($ledgers)
            ->editColumn('date', function ($row) use ($settings) {

                $dateFormat = json_decode($settings->business, true)['date_format'];
                $__date_format = str_replace('-', '/', $dateFormat);

                return $row->date ? date($__date_format, strtotime($row->date)) : '';
            })

            ->editColumn('particulars', function ($row) use ($request, $by) {

                $voucherType = $row->voucher_type;
                $ledgerParticularsUtil = new \App\Utils\LedgerParticularsUtil();

                return $ledgerParticularsUtil->particulars($request, $row->voucher_type, $row, $by);
            })

            ->editColumn('voucher_type', function ($row) {

                //return $row->voucher_type;
                $type = $this->voucherType($row->voucher_type);

                return $row->voucher_type != 0 ? '<strong>'.$type['name'].'</strong>' : '';
            })

            ->editColumn('voucher_no', function ($row) {

                //return $row->voucher_type;
                $type = $this->voucherType($row->voucher_type);

                return '<a href="'.(! empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#').'" id="details_btn" class="fw-bold">'.$row->{$type['voucher_no']}.'</a>';
            })
            ->editColumn('debit', fn ($row) => '<span class="debit fw-bold" data-value="'.$row->debit.'">'.($row->debit > 0 ? \App\Utils\Converter::format_in_bdt($row->debit) : '').'</span>')
            ->editColumn('credit', fn ($row) => '<span class="credit fw-bold" data-value="'.$row->credit.'">'.($row->credit > 0 ? \App\Utils\Converter::format_in_bdt($row->credit) : '').'</span>')
            ->editColumn('running_balance', function ($row) {

                return $row->running_balance > 0 ? '<span class="running_balance fw-bold">'.\App\Utils\Converter::format_in_bdt(abs($row->running_balance)).$row->balance_type.'</span>' : '';
            })
            ->rawColumns(['date', 'particulars', 'voucher_type', 'voucher_no', 'debit', 'credit', 'running_balance'])
            ->make(true);
    }

    public function ledgerEntriesPrint($request, $id, $by = 'accountId')
    {
        $ledgers = '';
        $settings = DB::table('general_settings')->select('business')->first();
        $accountStartDate = date('Y-m-d', strtotime(json_decode($settings->business, true)['start_date']));

        $ledgers = $this->ledgerEntriesQuery($request, $id, $by);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $userId = $request->user_id ? $request->user_id : null;
            $accountOpeningBalance = '';

            if ($by == 'accountId') {

                $accountOpeningBalanceQ = DB::table('account_ledgers')->where('account_ledgers.account_id', $id);
            } else {

                $accountOpeningBalanceQ = DB::table('account_ledgers')->where('account_ledgers.user_id', $id);
            }

            if ($request->user_id) {

                $accountOpeningBalanceQ->where('account_ledgers.user_id', $request->user_id);
            }

            $accountOpeningBalance = $accountOpeningBalanceQ->select(
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
            )->groupBy('account_ledgers.account_id')->get();

            $openingBalanceDebit = $accountOpeningBalance->sum('opening_total_debit');
            $openingBalanceCredit = $accountOpeningBalance->sum('opening_total_credit');

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = 'dr';
            if ($openingBalanceDebit > $openingBalanceCredit) {

                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                $currOpeningBalanceSide = 'dr';
            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                $currOpeningBalanceSide = 'cr';
            }

            $arr = [
                'id' => 0,
                'user_id' => $request->user_id ? $request->user_id : null,
                'user' => $request->user_id ? (object) ['id' => $request->user_id, 'prefix' => null, 'name' => $request->user_name, 'last_name' => null] : null,
                'voucher_type' => 0,
                'sales_voucher' => null,
                'date' => null,
                'account_id' => $id,
                'amount_type' => $currOpeningBalanceSide == 'dr' ? 'debit' : 'credit',
                'debit' => $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0.00,
                'credit' => $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0.00,
                'running_balance' => 0,
                'balance_type' => ' Dr',
            ];

            $stdArr = (object) $arr;

            $ledgers->prepend($stdArr);
        }

        $runningDebit = 0;
        $runningCredit = 0;
        foreach ($ledgers as $ledger) {

            $runningDebit += $ledger->debit;
            $runningCredit += $ledger->credit;

            if ($runningDebit > $runningCredit) {

                $ledger->running_balance = $runningDebit - $runningCredit;
                $ledger->balance_type = ' Dr.';
            } elseif ($runningCredit > $runningDebit) {

                $ledger->running_balance = $runningCredit - $runningDebit;
                $ledger->balance_type = ' Cr.';
            }
        }

        return $ledgers;
    }

    public function ledgerEntriesQuery($request, $id, $by)
    {
        $query = AccountLedger::query()
            ->whereRaw('concat(account_ledgers.debit,account_ledgers.credit) > 0');

        if ($by == 'accountId') {

            $query->where('account_ledgers.account_id', $id);
        } elseif ($by == 'userId') {

            $query->where('account_ledgers.user_id', $id);
        }

        if ($request->customer_account_id) {

            $query->where('account_ledgers.account_id', $request->customer_account_id);
        }

        if ($request->user_id) {

            $query->where('account_ledgers.user_id', $request->user_id);
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
                    'journalEntry.journal:id,remarks',
                    'journalEntry.journal.entries',
                    'journalEntry.journal.entries.assignedUser:id,prefix,name,last_name',
                    'journalEntry.journal.entries.account:id,name,phone,account_number,account_group_id',
                    'journalEntry.journal.entries.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'journalEntry.journal.entries.paymentMethod:id,name',

                    'contraDescription',
                    'contraDescription.contra:id,remarks',
                    'contraDescription.contra.descriptions',
                    'contraDescription.contra.descriptions.account:id,name,account_number',
                    'contraDescription.contra.descriptions.paymentMethod:id,name',

                    'paymentDescription',
                    'paymentDescription.user:id,prefix,name,last_name',
                    'paymentDescription.payment:id,remarks',
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
                    'sale.saleProducts:id,sale_id,product_id,product_variant_id,quantity,unit_id,unit_price_inc_tax,subtotal',
                    'sale.saleProducts.product:id,name',
                    'sale.saleProducts.variant:id,variant_name',
                    'sale.saleProducts.saleUnit:id,code_name,base_unit_multiplier',

                    'salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount,return_note',
                    'salesReturn.salesAccount:id,name',
                    'salesReturn.customer:id,name,phone,address',
                    'salesReturn.returnProducts:id,sale_return_id,product_id,product_variant_id,unit_id,unit_price_inc_tax,return_qty,return_subtotal',
                    'salesReturn.returnProducts.product:id,name',
                    'salesReturn.returnProducts.variant:id,variant_name',
                    'salesReturn.returnProducts.returnUnit:id,code_name,base_unit_multiplier',

                    'purchase:id,supplier_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount,total_purchase_amount,purchase_note,payment_note,purchase_account_id',
                    'purchase.purchaseAccount:id,name',
                    'purchase.purchaseProducts:id,purchase_id,product_id,product_variant_id,unit_id,quantity,net_unit_cost,line_total',
                    'purchase.purchaseProducts.product:id,name',
                    'purchase.purchaseProducts.variant:id,variant_name',
                    'purchase.purchaseProducts.purchaseUnit:id,code_name,base_unit_multiplier',

                    'purchaseProduct:id,purchase_id,tax_ac_id',
                    'purchaseProduct.purchase:id,supplier_account_id,total_purchase_amount,purchase_note,payment_note,purchase_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount',
                    'purchaseProduct.purchase.purchaseAccount:id,name',
                    'purchaseProduct.purchase.supplier:id,name',
                    'purchaseProduct.purchase.purchaseProducts:id,purchase_id,product_id,product_variant_id,quantity,unit_id,tax_ac_id,unit_tax_percent,unit_tax_amount,net_unit_cost,line_total',
                    'purchaseProduct.purchase.purchaseProducts.product:id,name',
                    'purchaseProduct.purchase.purchaseProducts.variant:id,variant_name',
                    'purchaseProduct.purchase.purchaseProducts.purchaseUnit:id,code_name,base_unit_multiplier',

                    'purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturn.returnProducts:id,purchase_return_id,product_id,product_variant_id,unit_cost_inc_tax,return_qty,unit_id,return_subtotal',
                    'purchaseReturn.returnProducts.product:id,name',
                    'purchaseReturn.returnProducts.variant:id,variant_name',
                    'purchaseReturn.returnProducts.returnUnit:id,code_name,base_unit_multiplier',

                    'stockAdjustment:id,expense_account_id,total_qty,net_total_amount,recovered_amount,type,reason',
                    'stockAdjustment:account:id,name',
                    'stockAdjustment:adjustmentProducts:id,adjustmentProducts,product_id,product_variant_id,quantity,unit,unit_cost_inc_tax,subtotal',
                    'stockAdjustment:adjustmentProducts.product:id,name',
                    'stockAdjustment:adjustmentProducts.variant:id,variant_name',
                    'stockAdjustment:adjustmentProducts.stockAdjustmentUnit:id,code_name,base_unit_multiplier',

                    'saleProduct:id,sale_id,tax_ac_id',
                    'saleProduct.sale:id,customer_account_id,total_payable_amount,sale_note,payment_note,sale_account_id,total_sold_qty,order_discount_amount,order_tax_amount',
                    'saleProduct.sale.salesAccount:id,name',
                    'saleProduct.sale.customer:id,name',
                    'saleProduct.sale.saleProducts:id,sale_id,product_id,product_variant_id,quantity,unit_id,tax_ac_id,unit_tax_percent,unit_tax_amount,unit_price_inc_tax,subtotal',

                    'purchaseReturnProduct:id,purchase_return_id,tax_ac_id',
                    'purchaseReturnProduct:purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturnProduct.purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturnProduct.purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturnProduct.purchaseReturn.returnProducts:id,purchase_return_id,product_id,product_variant_id,unit_id,unit_tax_percent,unit_tax_amount,unit_cost_inc_tax,return_qty,return_subtotal',
                    'purchaseReturnProduct.purchaseReturn.returnProducts.product:id,name',
                    'purchaseReturnProduct.purchaseReturn.returnProducts.variant:id,variant_name',
                    'purchaseReturnProduct.purchaseReturn.returnProducts.returnUnit:id,code_name,base_unit_multiplier',

                    'salesReturnProduct:id,sale_return_id,tax_ac_id',
                    'salesReturnProduct:salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'salesReturnProduct.salesReturn.salesAccount:id,name',
                    'salesReturnProduct.salesReturn.customer:id,name,phone,address',
                    'salesReturnProduct.salesReturn.returnProducts:id,sale_return_id,product_id,product_variant_id,unit_id,unit_price_inc_tax,unit_tax_percent,unit_tax_amount,return_qty,return_subtotal',
                    'salesReturnProduct.salesReturn.returnProducts.product:id,name',
                    'salesReturnProduct.salesReturn.returnProducts.variant:id,variant_name',
                    'salesReturnProduct.salesReturn.returnProducts.returnUnit:id,code_name,base_unit_multiplier',

                    'expenseDescription',
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

        return $query->orderBy('account_ledgers.date', 'asc')->orderBy('account_ledgers.id', 'asc')->get();
    }

    public function accountVoucherList($request, $id, $by)
    {
        $ledgers = '';

        $settings = DB::table('general_settings')->select('business')->first();

        $query = AccountLedger::query();

        if ($by == 'accountId') {

            $query->where('account_ledgers.account_id', $id);
        } else {

            $query->where('account_ledgers.user_id', $id);
        }

        if ($request->voucher_type) {

            $query->where('account_ledgers.voucher_type', $request->voucher_type);
        }

        if ($request->user_id) {

            $query->where('account_ledgers.user_id', $request->user_id);
        }

        if ($request->customer_account_id) {

            $query->where('account_ledgers.account_id', $request->customer_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('account_ledgers.date', $date_range);
        }

        $query->whereIn('account_ledgers.voucher_type', [8, 9, 13])
            ->leftJoin('journal_entries', 'account_ledgers.journal_entry_id', 'journal_entries.id')
            ->leftJoin('journals', 'journal_entries.journal_id', 'journals.id')
            ->leftJoin('payment_descriptions', 'account_ledgers.payment_description_id', 'payment_descriptions.id')
            ->leftJoin('payments', 'payment_descriptions.payment_id', 'payments.id')
            ->with(
                [
                    'account',
                    'user:id,prefix,name,last_name',
                    'journalEntry',
                    'journalEntry.journal',
                    'journalEntry.journal.entries',
                    'journalEntry.journal.entries.assignedUser:id,prefix,name,last_name',
                    'journalEntry.journal.entries.account:id,name,phone,account_number,account_group_id',
                    'journalEntry.journal.entries.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'journalEntry.journal.entries.paymentMethod:id,name',
                    'paymentDescription.user:id,prefix,name,last_name',
                    'paymentDescription.payment',
                    'paymentDescription.payment.descriptions',
                    'paymentDescription.payment.descriptions.account',
                    'paymentDescription.payment.descriptions.user:id,prefix,name,last_name',
                    'paymentDescription.payment.descriptions.paymentMethod:id,name',
                    'paymentDescription.payment.descriptions.references',
                    'paymentDescription.payment.descriptions.references.sale:id,invoice_id,total_payable_amount',
                    'paymentDescription.payment.descriptions.references.purchase:id,invoice_id,total_purchase_amount',
                ]
            )
            ->select(
                'account_ledgers.user_id',
                'account_ledgers.date',
                'account_ledgers.voucher_type',
                'account_ledgers.account_id',
                'account_ledgers.payment_description_id',
                'account_ledgers.journal_entry_id',
                'account_ledgers.debit',
                'account_ledgers.credit',
                'account_ledgers.amount_type',
                'journals.id as journal_id',
                'journals.voucher_no as journal_voucher',
                'payments.id as payment_id',
                'payments.voucher_no as payment_voucher',
            );

        $ledgers = $query->orderBy('account_ledgers.date', 'desc')->get();

        return DataTables::of($ledgers)
            ->editColumn('date', function ($row) use ($settings) {

                $dateFormat = json_decode($settings->business, true)['date_format'];
                $__date_format = str_replace('-', '/', $dateFormat);

                return date($__date_format, strtotime($row->date));
            })

            ->editColumn('descriptions', function ($row) use ($request, $by) {

                $voucherType = $row->voucher_type;
                $ledgerParticularsUtil = new \App\Utils\LedgerParticularsUtil();

                return $ledgerParticularsUtil->particulars($request, $row->voucher_type, $row, $by);
            })

            ->editColumn('voucher_type', function ($row) {

                $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();
                $type = $accountLedgerUtil->voucherType($row->voucher_type);

                return '<strong>'.$type['name'].'</strong>';
            })

            ->editColumn('voucher_no', function ($row) {

                $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();
                $type = $accountLedgerUtil->voucherType($row->voucher_type);

                return '<a href="#" class="fw-bold">'.$row->{$type['voucher_no']}.'</a>';
            })
            ->editColumn('debit', fn ($row) => '<span class="voucher_debit fw-bold" data-value="'.$row->debit.'">'.($row->debit > 0 ? \App\Utils\Converter::format_in_bdt($row->debit) : '').'</span>')
            ->editColumn('credit', fn ($row) => '<span class="voucher_credit fw-bold" data-value="'.$row->credit.'">'.($row->credit > 0 ? \App\Utils\Converter::format_in_bdt($row->credit) : '').'</span>')
            ->rawColumns(['date', 'descriptions', 'voucher_type', 'voucher_no', 'debit', 'credit'])
            ->make(true);
    }
}
