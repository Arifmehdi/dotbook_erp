<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

class ExpenseByPurchaseUtil
{
    public function addExpenseByPurchase($purchase, $request, $codeGenerationService, $expenseVoucherPrefix)
    {
        $expenseUtil = new \App\Utils\ExpenseUtil();

        $addExpense = $expenseUtil->addExpense(date: $request->date, remarks: $request->expense_note, mode: 1, debitTotal: $request->total_additional_expense, creditTotal: $request->total_additional_expense, isTransactionDetails: 0, maintainCostCentre: 0, voucherGenerator: $codeGenerationService, expenseVoucherPrefix: $expenseVoucherPrefix, purchaseRefId: $purchase->id);

        $expenseAccountsArr = [
            [
                'account_name' => 'expense_credit_account',
                'account_id' => $request->expense_credit_account_id,
                'amount_type' => 'cr',
                'ledger_amount_type' => 'credit',
                'amount' => $request->total_additional_expense ? $request->total_additional_expense : 0,
                'payment_method_id' => $request->expense_payment_method_id,
                'transaction_no' => $request->expense_transaction_no,
                'cheque_no' => $request->expense_cheque_no,
            ],
            [
                'account_name' => 'labour_cost',
                'account_id' => DB::table('accounts')->where('fixed_name', 'labour_cost')->first('id')->id,
                'amount_type' => 'dr',
                'ledger_amount_type' => 'debit',
                'amount' => $request->labour_cost ? $request->labour_cost : 0,
                'payment_method_id' => null,
                'transaction_no' => null,
                'cheque_no' => null,
            ],
            [
                'account_name' => 'transport_cost',
                'account_id' => DB::table('accounts')->where('fixed_name', 'transport_cost')->first('id')->id,
                'amount_type' => 'dr',
                'ledger_amount_type' => 'debit',
                'amount' => $request->transport_cost ? $request->transport_cost : 0,
                'payment_method_id' => null,
                'transaction_no' => null,
                'cheque_no' => null,
            ],
            [
                'account_name' => 'scale_charge',
                'account_id' => DB::table('accounts')->where('fixed_name', 'scale_charge')->first('id')->id,
                'amount_type' => 'dr',
                'ledger_amount_type' => 'debit',
                'amount' => $request->scale_charge ? $request->scale_charge : 0,
                'payment_method_id' => null,
                'transaction_no' => null,
                'cheque_no' => null,
            ],
            [
                'account_name' => 'others',
                'account_id' => DB::table('accounts')->where('fixed_name', 'others')->first('id')->id,
                'amount_type' => 'dr',
                'ledger_amount_type' => 'debit',
                'amount' => $request->others ? $request->others : 0,
                'payment_method_id' => null,
                'transaction_no' => null,
                'cheque_no' => null,
            ],
        ];

        $expenseDescriptionUtil = new \App\Utils\ExpenseDescriptionUtil();
        $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();

        foreach ($expenseAccountsArr as $expenseAccount) {

            if ($expenseAccount['amount'] > 0) {

                $addExpenseDescription = $expenseDescriptionUtil->addExpenseDescription(expenseId: $addExpense->id, accountId: $expenseAccount['account_id'], paymentMethodId: $expenseAccount['payment_method_id'], amountType: $expenseAccount['amount_type'], amount: $expenseAccount['amount'], transactionNo: $expenseAccount['transaction_no'], chequeNo: $expenseAccount['cheque_no'], chequeSerialNo: null, chequeIssueDate: null);

                $accountLedgerUtil->addAccountLedger(voucher_type_id: 5, date: $request->date, account_id: $expenseAccount['account_id'], trans_id: $addExpenseDescription->id, amount: $expenseAccount['amount'], amount_type: $expenseAccount['ledger_amount_type']);
            }
        }
    }

    public function updateExpenseByPurchase($purchase, $request, $codeGenerationService, $expenseVoucherPrefix)
    {
        $expenseUtil = new \App\Utils\ExpenseUtil();

        $addOrUpdateExpense = '';
        if ($purchase->expense) {

            $addOrUpdateExpense = $expenseUtil->updateExpense(id: $purchase->expense->id, date: $request->date, remarks: $request->expense_note, debitTotal: $request->total_additional_expense, creditTotal: $request->total_additional_expense, isTransactionDetails: 0, maintainCostCentre: 0);

            $purchase?->expense?->expenseDescriptions()->delete();
        } else {

            $addOrUpdateExpense = $expenseUtil->addExpense(date: $request->date, remarks: $request->expense_note, mode: 1, debitTotal: $request->total_additional_expense, creditTotal: $request->total_additional_expense, isTransactionDetails: 0, maintainCostCentre: 0, voucherGenerator: $codeGenerationService, expenseVoucherPrefix: $expenseVoucherPrefix, purchaseRefId: $purchase->id);
        }

        $expenseAccountsArr = [
            [
                'account_name' => 'expense_credit_account',
                'account_id' => $request->expense_credit_account_id,
                'amount_type' => 'cr',
                'ledger_amount_type' => 'credit',
                'amount' => $request->total_additional_expense ? $request->total_additional_expense : 0,
                'payment_method_id' => $request->expense_payment_method_id,
                'transaction_no' => $request->expense_transaction_no,
                'cheque_no' => $request->expense_cheque_no,
            ],
            [
                'account_name' => 'labour_cost',
                'account_id' => DB::table('accounts')->where('fixed_name', 'labour_cost')->first('id')->id,
                'amount_type' => 'dr',
                'ledger_amount_type' => 'debit',
                'amount' => $request->labour_cost ? $request->labour_cost : 0,
                'payment_method_id' => null,
                'transaction_no' => null,
                'cheque_no' => null,
            ],
            [
                'account_name' => 'transport_cost',
                'account_id' => DB::table('accounts')->where('fixed_name', 'transport_cost')->first('id')->id,
                'amount_type' => 'dr',
                'ledger_amount_type' => 'debit',
                'amount' => $request->transport_cost ? $request->transport_cost : 0,
                'payment_method_id' => null,
                'transaction_no' => null,
                'cheque_no' => null,
            ],
            [
                'account_name' => 'scale_charge',
                'account_id' => DB::table('accounts')->where('fixed_name', 'scale_charge')->first('id')->id,
                'amount_type' => 'dr',
                'ledger_amount_type' => 'debit',
                'amount' => $request->scale_charge ? $request->scale_charge : 0,
                'payment_method_id' => null,
                'transaction_no' => null,
                'cheque_no' => null,
            ],
            [
                'account_name' => 'others',
                'account_id' => DB::table('accounts')->where('fixed_name', 'others')->first('id')->id,
                'amount_type' => 'dr',
                'ledger_amount_type' => 'debit',
                'amount' => $request->others ? $request->others : 0,
                'payment_method_id' => null,
                'transaction_no' => null,
                'cheque_no' => null,
            ],
        ];

        $expenseDescriptionUtil = new \App\Utils\ExpenseDescriptionUtil();
        $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();

        foreach ($expenseAccountsArr as $expenseAccount) {

            if ($expenseAccount['amount'] > 0) {

                $addExpenseDescription = $expenseDescriptionUtil->addExpenseDescription(expenseId: $addOrUpdateExpense->id, accountId: $expenseAccount['account_id'], paymentMethodId: $expenseAccount['payment_method_id'], amountType: $expenseAccount['amount_type'], amount: $expenseAccount['amount'], transactionNo: $expenseAccount['transaction_no'], chequeNo: $expenseAccount['cheque_no'], chequeSerialNo: null, chequeIssueDate: null);

                $accountLedgerUtil->addAccountLedger(voucher_type_id: 5, date: $request->date, account_id: $expenseAccount['account_id'], trans_id: $addExpenseDescription->id, amount: $expenseAccount['amount'], amount_type: $expenseAccount['ledger_amount_type']);
            }
        }
    }
}
