<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashRegisterController extends Controller
{
    public function __construct()
    {

    }

    // Create cash register
    public function create()
    {
        $cashCounters = DB::table('cash_counters')->get(['id', 'counter_name', 'short_name']);

        $saleAccounts = DB::table('accounts')
            ->where('accounts.account_type', 5)
            ->get(['accounts.id', 'accounts.name']);

        $openedCashRegister = CashRegister::with('admin', 'admin.role')
            ->where('admin_id', auth()->user()->id)->where('status', 1)
            ->first();

        if (! $openedCashRegister) {

            return view('sales_app.cash_register.create', compact('cashCounters', 'saleAccounts'));
        } else {

            return redirect()->route('sales.pos.create');
        }
    }

    // Store cash register
    public function store(Request $request)
    {
        $settings = DB::table('general_settings')->select(['business'])->first();

        $this->validate($request, [
            'counter_id' => 'required',
            'cash_in_hand' => 'required',
            'sale_account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sale A/c is required',
        ]);

        $dateFormat = json_decode($settings->business, true)['date_format'];
        $timeFormat = json_decode($settings->business, true)['time_format'];

        $__timeFormat = '';
        if ($timeFormat == '12') {
            $__timeFormat = ' h:i:s';
        } elseif ($timeFormat == '24') {
            $__timeFormat = ' H:i:s';
        }

        $addCashRegister = new CashRegister();
        $addCashRegister->admin_id = auth()->user()->id;
        $addCashRegister->date = date($dateFormat.$__timeFormat);
        $addCashRegister->cash_counter_id = $request->counter_id;
        $addCashRegister->sale_account_id = $request->sale_account_id;
        $addCashRegister->cash_in_hand = $request->cash_in_hand;
        $addCashRegister->save();

        return redirect()->route('sales.pos.create');
    }

    // cash register Details
    public function cashRegisterDetails()
    {
        if (! auth()->user()->can('register_view')) {

            return 'Access Forbidden';
        }

        $queries = $this->detailsRegisterQuery();

        $activeCashRegister = $queries['activeCashRegister'];
        $paymentMethodPayments = $queries['paymentMethodPayments'];
        $accountPayments = $queries['accountPayments'];
        $totalCredit = $queries['totalCredit'];

        return view(

            'sales.cash_register.ajax_view.cash_register_details',
            compact(

                'activeCashRegister',
                'paymentMethodPayments',
                'accountPayments',
                'totalCredit'
            )
        );
    }

    // Cash Register Details For Report
    public function cashRegisterDetailsForReport($crId)
    {
        if (! auth()->user()->can('register_view')) {

            return 'Access Forbidden';
        }

        $queries = $this->detailsRegisterQuery($crId);

        $activeCashRegister = $queries['activeCashRegister'];
        $paymentMethodPayments = $queries['paymentMethodPayments'];
        $accountPayments = $queries['accountPayments'];
        $totalCredit = $queries['totalCredit'];

        return view(

            'sales.cash_register.ajax_view.cash_register_details',
            compact(

                'activeCashRegister',
                'paymentMethodPayments',
                'accountPayments',
                'totalCredit'
            )
        );
    }

    // get closing cash register details
    public function closeCashRegisterModalView()
    {
        $queries = $this->detailsRegisterQuery();

        $activeCashRegister = $queries['activeCashRegister'];
        $paymentMethodPayments = $queries['paymentMethodPayments'];
        $accountPayments = $queries['accountPayments'];
        $totalCredit = $queries['totalCredit'];

        return view(

            'sales.cash_register.ajax_view.close_cash_register_view',
            compact(
                'activeCashRegister',
                'paymentMethodPayments',
                'accountPayments',
                'totalCredit'
            )
        );
    }

    // Close cash register
    public function close(Request $request)
    {
        $this->validate($request, [
            'closed_amount' => 'required',
        ]);

        $closeCashRegister = CashRegister::where('admin_id', auth()->user()->id)->where('status', 1)->first();
        $closeCashRegister->closed_amount = $request->closed_amount;
        $closeCashRegister->closing_note = $request->closing_note;
        $closeCashRegister->closed_at = Carbon::now()->format('Y-m-d H:i:s');
        $closeCashRegister->status = 0;
        $closeCashRegister->save();

        return redirect()->back();
    }

    private function detailsRegisterQuery($crId = null)
    {
        $activeCashRegister = '';
        $activeCashRegisterQuery = DB::table('cash_registers')
            ->leftJoin('users', 'cash_registers.admin_id', 'users.id')
            ->leftJoin('cash_counters', 'cash_registers.cash_counter_id', 'cash_counters.id')
            ->select(
                'cash_registers.id',
                'cash_registers.created_at',
                'cash_registers.closed_at',
                'cash_registers.cash_in_hand',
                'users.prefix as u_prefix',
                'users.name as u_first_name',
                'users.last_name as u_last_name',
                'users.username',
                'users.email as u_email',
                'cash_counters.counter_name',
                'cash_counters.short_name as cc_s_name',
            );

        if (! $crId) {

            $activeCashRegister = $activeCashRegisterQuery
                ->where('users.id', auth()->user()->id)
                ->where('cash_registers.status', 1)->first();
        } else {

            $activeCashRegister = $activeCashRegisterQuery
                ->where('cash_registers.id', $crId)->first();
        }

        $paymentMethodPayments = DB::table('sale_payments')
            ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
            ->leftJoin('payment_methods', 'sale_payments.payment_method_id', 'payment_methods.id')
            ->leftJoin('cash_register_transactions', 'sales.id', 'cash_register_transactions.sale_id')
            ->where('cash_register_transactions.cash_register_id', $activeCashRegister->id)
            ->select('payment_methods.name', DB::raw('SUM(paid_amount) as total_paid'))
            ->groupBy('sale_payments.payment_method_id')->groupBy('payment_methods.name')->get();

        $accountPayments = DB::table('sale_payments')
            ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
            ->leftJoin('accounts', 'sale_payments.account_id', 'accounts.id')
            ->leftJoin('cash_register_transactions', 'sales.id', 'cash_register_transactions.sale_id')
            ->where('cash_register_transactions.cash_register_id', $activeCashRegister->id)
            ->select('accounts.account_type', DB::raw('SUM(paid_amount) as total_paid'))
            ->groupBy('accounts.account_type')->groupBy('accounts.account_type')->get();

        $totalCredit = DB::table('sales')
            ->leftJoin('cash_register_transactions', 'sales.id', 'cash_register_transactions.sale_id')
            ->where('cash_register_transactions.cash_register_id', $activeCashRegister->id)
            ->select(DB::raw('SUM(sales.due) as total_due'))
            ->groupBy('cash_register_transactions.cash_register_id')
            ->get();

        return [
            'activeCashRegister' => $activeCashRegister,
            'paymentMethodPayments' => $paymentMethodPayments,
            'accountPayments' => $accountPayments,
            'totalCredit' => $totalCredit,
        ];
    }
}
