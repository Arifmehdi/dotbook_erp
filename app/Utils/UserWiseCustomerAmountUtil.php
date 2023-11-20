<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserWiseCustomerAmountUtil
{
    public function userWiseCustomerAmountSummery($customerId, $customerAccountId, $user_id = null, $from_date = null, $to_date = null, $openingBlAndCrLimit = true)
    {
        // $customerDetails = DB::table('customers')->where('customers.id', $customerId)
        //     ->select('customers.name', 'customers.phone')
        //     ->first();
        $openingBalanceDetails = '';
        $creditLimit = '';

        if ($openingBlAndCrLimit) {

            $openingBalanceDetails = DB::table('customer_opening_balances')->where('customer_opening_balances.customer_id', $customerId)
                ->where('customer_opening_balances.user_id', $user_id)
                ->select('customer_opening_balances.amount', 'customer_opening_balances.is_show_again')
                ->first();

            $creditLimit = DB::table('customers')
                ->select('customers.credit_limit', 'customers.customer_type')
                ->first();
        }

        $amounts = '';

        $query = DB::table('account_ledgers')->where('account_ledgers.account_id', $customerAccountId);

        if ($user_id) {

            $query->where('account_ledgers.user_id', $user_id);
        }

        if ($from_date) {

            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = $to_date ? date('Y-m-d', strtotime($to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('account_ledgers.report_date', $date_range); // Final
        }

        if (auth()->user()->can('view_own_sale')) {

            $query->where('account_ledgers.user_id', auth()->user()->id)
                ->groupBy('account_ledgers.voucher_type')
                ->groupBy('account_ledgers.amount_type');
        }

        $query->groupBy('account_ledgers.voucher_type')
            ->groupBy('account_ledgers.amount_type');

        $amounts = $query->select(
            'account_ledgers.voucher_type',
            'account_ledgers.amount_type',
            DB::raw('SUM(case when account_ledgers.voucher_type = 0 and account_ledgers.debit > 0 then account_ledgers.debit end) as debit_opening_balance'),
            DB::raw('SUM(case when account_ledgers.voucher_type = 0 and account_ledgers.credit > 0 then account_ledgers.credit end) as credit_opening_balance'),
            DB::raw('SUM(account_ledgers.debit + account_ledgers.credit) as amt'),
        )->get();

        $openingBalance = 0;
        $totalSale = 0;
        $totalReceived = 0;
        $totalReturn = 0;
        $totalLess = 0;
        $totalPaid = 0;

        foreach ($amounts as $amount) {

            if ($amount->voucher_type == 0) {

                $openingBalance += $amount->debit_opening_balance ? $amount->debit_opening_balance : 0;
                $openingBalance -= $amount->credit_opening_balance ? $amount->credit_opening_balance : 0;
                // $openingBalance += $amount->amt;
            } elseif ($amount->voucher_type == 1) {

                $totalSale += $amount->amt;
            } elseif ($amount->voucher_type == 2) {

                $totalReturn += $amount->amt;
            } elseif ($amount->voucher_type == 8) {

                $totalReceived += $amount->amt;
            } elseif ($amount->voucher_type == 9) {

                $totalPaid += $amount->amt;
            } elseif ($amount->voucher_type == 13) {

                if ($amount->amount_type == 'debit') {

                    $totalPaid += $amount->amt;
                } else {

                    $totalReceived += $amount->amt;
                }
            }
        }

        $totalDue = ($totalSale + $openingBalance + $totalPaid) - $totalReceived - $totalReturn - $totalLess;

        $totalReturnDue = $totalReturn - ($totalSale + $openingBalance - $totalReceived) - $totalPaid;

        return [
            'opening_balance' => $openingBalance,
            'total_sale' => $totalSale,
            'total_received' => $totalReceived,
            'total_return' => $totalReturn,
            'total_less' => $totalLess,
            'total_paid' => $totalPaid,
            'total_sale_due' => $totalDue,
            'total_sale_return_due' => $totalReturnDue > 0 ? $totalReturnDue : 0,
            // 'customerDetails' => $customerDetails,
            'openingBalanceDetails' => $openingBalanceDetails ? $openingBalanceDetails : 'N/A',
            'customer_type' => $creditLimit ? $creditLimit->customer_type : 1,
            'credit_limit' => $creditLimit ? $creditLimit->credit_limit : 0,
        ];
    }

    public function customerClosingBalance($customerId, $user_id = null, $from_date = null, $to_date = null)
    {
        $openingBalanceDetails = DB::table('customer_opening_balances')->where('customer_opening_balances.customer_id', $customerId)
            ->where('customer_opening_balances.user_id', $user_id)
            ->select('customer_opening_balances.amount', 'customer_opening_balances.is_show_again')
            ->first();

        $amounts = '';

        $query = DB::table('customer_ledgers')->where('customer_ledgers.customer_id', $customerId);

        if ($user_id) {

            $query->where('customer_ledgers.user_id', $user_id);
        }

        if ($from_date) {

            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = $to_date ? date('Y-m-d', strtotime($to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('customer_ledgers.report_date', $date_range); // Final
        }

        if (auth()->user()->can('view_own_sale')) {

            $query->where('customer_ledgers.user_id', auth()->user()->id)->groupBy('customer_ledgers.customer_id');
        }

        $query->groupBy('customer_ledgers.customer_id');

        $amounts = $query->select(
            DB::raw('SUM(case when customer_ledgers.voucher_type = 0 and customer_ledgers.debit > 0 then amount end) as debit_opening_balance'),
            DB::raw('SUM(case when customer_ledgers.voucher_type = 0 and customer_ledgers.credit > 0 then amount end) as credit_opening_balance'),
            DB::raw('SUM(debit) as debit'),
            DB::raw('SUM(credit) as credit')
        )->get();

        $debitOpeningBalance = $amounts->sum('debit_opening_balance');
        $creditOpeningBalance = $amounts->sum('credit_opening_balance');

        $openingBalance = 0;
        $openingBalanceSide = 'debit';

        $debit = $amounts->sum('debit');
        $credit = $amounts->sum('credit');

        $closingBalance = 0;

        $closingBalanceSide = 'debit';
        $closingBalanceAmount = $debit - $credit;

        if ($debit > $credit) {

            $closingBalance = $debit - $credit;
            $closingBalanceSide = 'debit';
        } elseif ($credit > $debit) {

            $closingBalance = $credit - $debit;
            $closingBalanceSide = 'credit';
        } elseif ($debit == $credit) {

            $closingBalance = $credit - $debit;
        }

        if ($debitOpeningBalance > $creditOpeningBalance) {

            $openingBalance = $debitOpeningBalance - $creditOpeningBalance;
            $openingBalanceSide = 'debit';
        } elseif ($creditOpeningBalance > $debitOpeningBalance) {

            $openingBalance = $creditOpeningBalance - $debitOpeningBalance;
            $openingBalanceSide = 'credit';
        }

        return [
            'debit_opening_balance' => $debitOpeningBalance ? $debitOpeningBalance : 0,
            'credit_opening_balance' => $creditOpeningBalance ? $creditOpeningBalance : 0,
            'opening_balance' => $openingBalance ? $openingBalance : 0,
            'opening_balance_side' => $openingBalanceSide,
            'openingBalanceDetails' => $openingBalanceDetails,
            'debit' => $debit ? $debit : 0,
            'credit' => $credit ? $credit : 0,
            'closing_balance' => $closingBalance ? $closingBalance : 0,
            'closing_balance_side' => $closingBalanceSide,
            'closing_balance_side_st' => $closingBalanceSide == 'debit' ? 'Dr' : 'Cr',
            'closing_balance_amount' => $closingBalanceAmount,
        ];
    }

    public function userWiseCustomerInvoiceAndOrders($customer_id, $user_id = null)
    {
        $allSalesAndOrders = '';
        $invoices = '';
        $orders = '';

        $allSalesAndOrdersQuery = DB::table('sales')->where('sales.customer_id', $customer_id)
            ->whereIn('sales.status', [1, 3, 7])
            ->where('sales.due', '>', 0);

        $invoicesQuery = DB::table('sales')
            ->where('sales.customer_id', $customer_id)
            ->where('sales.status', 1)->where('sales.due', '>', 0);

        $ordersQuery = DB::table('sales')->where('sales.customer_id', $customer_id)
            ->where('sales.order_status', 1)->where('sales.due', '>', 0);

        if ($user_id) {

            $allSalesAndOrdersQuery->where('sales.order_by_id', $user_id)->orWhere('sales.admin_id', $user_id)->where('sales.due', '>', 0);
            $invoicesQuery->where('sales.admin_id', $user_id);
            $ordersQuery->where('sales.order_by_id', $user_id);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $allSalesAndOrdersQuery->select('id', 'date', 'invoice_id', 'order_id', 'order_date', 'total_payable_amount', 'sale_return_amount', 'due', 'status')
                ->orderBy('report_date', 'desc');

            $invoicesQuery->select('id', 'date', 'invoice_id', 'total_payable_amount', 'sale_return_amount', 'due')->orderBy('report_date', 'desc');

            $ordersQuery->select('id', 'date', 'order_id', 'order_date', 'total_payable_amount', 'sale_return_amount', 'due', 'status')
                ->orderBy('report_date', 'desc');
        } else {

            if (auth()->user()->can('view_own_sale')) {

                $allSalesAndOrdersQuery->where('sales.order_by_id', auth()->user()->id)->orWhere('sales.admin_id', auth()->user()->id)->where('sales.due', '>', 0);

                $invoicesQuery->where('sales.admin_id', auth()->user()->id);

                $ordersQuery->where('sales.order_by_id', auth()->user()->id);
            }

            $allSalesAndOrdersQuery->select('id', 'date', 'invoice_id', 'order_id', 'order_date', 'total_payable_amount', 'sale_return_amount', 'due', 'status')
                ->orderBy('report_date', 'desc');

            $invoicesQuery->select('id', 'date', 'invoice_id', 'total_payable_amount', 'sale_return_amount', 'due')->orderBy('report_date', 'desc');

            $ordersQuery->select('id', 'date', 'order_id', 'order_date', 'total_payable_amount', 'sale_return_amount', 'due', 'status')
                ->orderBy('report_date', 'desc');
        }

        $allSalesAndOrders = $allSalesAndOrdersQuery->get();
        $invoices = $invoicesQuery->get();
        $orders = $ordersQuery->get();

        return [
            'allSalesAndOrders' => $allSalesAndOrders,
            'invoices' => $invoices,
            'orders' => $orders,
        ];
    }
}
