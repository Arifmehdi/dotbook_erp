<?php

namespace App\Utils;

use App\Models\CustomerOpeningBalance;

class CustomerOpeningBalanceUtil
{
    public function addCustomerOpeningBalance($customer_id, $account_id, $opening_balance, $opening_balance_type, $user_id, $never_show_again = null)
    {
        $addCustomerOpeningBalance = CustomerOpeningBalance::insert([
            'customer_id' => $customer_id,
            'account_id' => $account_id,
            'user_id' => $user_id,
            'amount' => $opening_balance ? $opening_balance : 0.00,
            'balance_type' => $opening_balance_type,
            'is_show_again' => isset($never_show_again) ? 0 : 1,
        ]);
    }

    public function updateCustomerOpeningBalance($customer_id, $account_id, $opening_balance, $opening_balance_type, $user_id, $never_show_again = null)
    {
        $userOpeningBalance = CustomerOpeningBalance::where('customer_id', $customer_id)->where('user_id', $user_id)->first();

        if ($userOpeningBalance) {

            $userOpeningBalance->account_id = $account_id;
            $userOpeningBalance->amount = $opening_balance ? $opening_balance : 0.00;
            $userOpeningBalance->balance_type = $opening_balance_type;
            $userOpeningBalance->is_show_again = isset($never_show_again) ? 0 : 1;
            $userOpeningBalance->save();
        } else {

            $addCustomerOpeningBalance = new CustomerOpeningBalance();
            $addCustomerOpeningBalance->customer_id = $customer_id;
            $addCustomerOpeningBalance->account_id = $account_id;
            $addCustomerOpeningBalance->user_id = $user_id;
            $addCustomerOpeningBalance->amount = $opening_balance ? $opening_balance : 0.00;
            $addCustomerOpeningBalance->balance_type = $opening_balance_type;
            $addCustomerOpeningBalance->is_show_again = isset($never_show_again) ? 0 : 1;
            $addCustomerOpeningBalance->save();
        }
    }
}
