<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\IncomeReceipt;
use App\Models\PaymentMethod;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\IncomeUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\UserActivityLogUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeReceiptController extends Controller
{
    protected $accountUtil;

    protected $util;

    protected $converter;

    protected $incomeUtil;

    protected $productStockUtil;

    protected $invoiceVoucherRefIdUtil;

    protected $userActivityLogUtil;

    public function __construct(
        AccountUtil $accountUtil,
        Util $util,
        Converter $converter,
        IncomeUtil $incomeUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        UserActivityLogUtil $userActivityLogUtil,
    ) {
        $this->accountUtil = $accountUtil;
        $this->util = $util;
        $this->converter = $converter;
        $this->incomeUtil = $incomeUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
    }

    public function index($id)
    {
        $income = Income::with(
            [
                'incomeDescriptions:id,income_id,income_account_id,amount',
                'incomeDescriptions.account:id,name,account_number,account_type',
                'createdBy:id,prefix,name,last_name',
                'incomeReceipts',
                'incomeReceipts.account:id,name,account_number,account_type',
                'incomeReceipts.method:id,name',
            ]
        )->where('id', $id)->first();

        return view('income.ajax_view.receipt_list', compact('income'));
    }

    public function show($receiptId)
    {
        $receipt = IncomeReceipt::with(
            [
                'income',
            ]
        )->where('id', $receiptId)->first();

        return view('income.ajax_view.receipt_details', compact('receipt'));
    }

    public function create($id)
    {
        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $accounts = DB::table('accounts')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2, 17])
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        foreach ($accounts as $account) {

            $amounts = $this->accountUtil->accountClosingBalance($account->id);
            $balance = $this->converter->format_in_bdt($amounts['closing_balance']).' '.$amounts['closing_balance_side_st_name'];
            $account->balance = $balance;
        }

        $income = Income::where('id', $id)->first();

        return view('income.ajax_view.add_income_receipt', compact('income', 'accounts', 'methods'));
    }

    public function store(Request $request, $incomeId)
    {
        $this->validate($request, [
            'date' => 'required',
            'received_amount' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $income = Income::where('id', $incomeId)->first();

            if ($request->received_amount > 0) {

                $addReceiptGetId = $this->incomeUtil->addReceiptGetId(
                    income_id: $income->id,
                    request: $request
                );

                // Add bank account Ledger
                $this->accountUtil->addAccountLedger(
                    voucher_type_id: 29,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $addReceiptGetId,
                    amount: $request->received_amount,
                    balance_type: 'debit'
                );

                $this->incomeUtil->adjustIncomeAmount($income);

                $incomeDetails = DB::table('income_receipts')
                    ->where('income_receipts.id', $addReceiptGetId)
                    ->leftJoin('incomes', 'income_receipts.income_id', 'incomes.id')
                    ->select(
                        'income_receipts.voucher_no as receipt_voucher',
                        'income_receipts.report_date',
                        'income_receipts.amount as received_amount',
                        'incomes.voucher_no as income_voucher',
                    )->first();

                $this->userActivityLogUtil->addLog(action: 1, subject_type: 33, data_obj: $incomeDetails);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Income Received successfully!');
    }

    public function edit($receiptId)
    {
        $receipt = IncomeReceipt::with(
            [
                'income',
            ]
        )->where('id', $receiptId)->first();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $accounts = DB::table('accounts')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2, 17])
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        foreach ($accounts as $account) {

            $amounts = $this->accountUtil->accountClosingBalance($account->id);
            $balance = $this->converter->format_in_bdt($amounts['closing_balance']).' '.$amounts['closing_balance_side_st_name'];
            $account->balance = $balance;
        }

        return view('income.ajax_view.edit_income_receipt', compact('receipt', 'accounts', 'methods'));
    }

    public function update(Request $request, $receiptId)
    {
        $this->validate($request, [
            'date' => 'required',
            'received_amount' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $receipt = IncomeReceipt::with(['income'])->where('id', $receiptId)->first();

            if ($request->received_amount > 0) {

                $this->incomeUtil->updateReceipt(
                    receipt: $receipt,
                    request: $request
                );

                // Add bank account Ledger
                $this->accountUtil->updateAccountLedger(
                    voucher_type_id: 29,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $receipt->id,
                    amount: $request->received_amount,
                    balance_type: 'debit'
                );

                $this->incomeUtil->adjustIncomeAmount($receipt->income);

                $incomeDetails = DB::table('income_receipts')
                    ->where('income_receipts.id', $receipt->id)
                    ->leftJoin('incomes', 'income_receipts.income_id', 'incomes.id')
                    ->select(
                        'income_receipts.voucher_no as receipt_voucher',
                        'income_receipts.report_date',
                        'income_receipts.amount as received_amount',
                        'incomes.voucher_no as income_voucher',
                    )->first();

                $this->userActivityLogUtil->addLog(action: 2, subject_type: 33, data_obj: $incomeDetails);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Income Receipt updated successfully!');
    }

    public function delete($receiptId)
    {
        try {
            DB::beginTransaction();

            $deleteReceipt = IncomeReceipt::with(['income'])->where('id', $receiptId)->first();

            if (! is_null($deleteReceipt)) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 33, data_obj: $deleteReceipt);
                $deleteReceipt->delete();
            }

            $this->incomeUtil->adjustIncomeAmount($deleteReceipt->income);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        DB::statement('ALTER TABLE income_receipts AUTO_INCREMENT = 1');

        return response()->json('Income Receipt deleted successfully!');
    }
}
