<?php

try {

    DB::beginTransaction();

    $expenseDescriptions = ExpenseDescription::with(['expense'])->get();

    foreach ($expenseDescriptions as $description) {

        $expenseAccount = Account::where('name', $expenseCategory->name)->where('account_type', 7)->first();

        $expenseAccountId = null;

        if (! $expenseAccount) {

            $addExpenseAccountGetId = Account::insertGetId([
                'name' => $expenseCategory->name,
                'account_type' => 7,
                'opening_balance_type' => 'debit',
            ]);

            $expenseAccountId = $addExpenseAccountGetId;
        } else {

            $expenseAccountId = $expenseAccount->id;
        }

        $description->expense_account_id = $expenseAccountId;
        $description->save();

        $description->expense->voucher_no = $description->expense->invoice_id;
        $description->expense->created_by_id = $description->expense->admin_id;
        $description->expense->save();

        $add = new AccountLedger();
        $add->date = $description->expense->report_date;
        $add->account_id = $expenseAccountId;
        $add->voucher_type = 5;
        $add->expense_description_id = $description->id;
        $add->debit = $description->amount;
        $add->amount_type = 'debit';
        $add->save();
    }

    DB::commit();
} catch (Exception $e) {

    DB::rollBack();
}
