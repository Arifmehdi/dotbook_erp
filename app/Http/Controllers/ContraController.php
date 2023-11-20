<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Contra;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\ContraDescriptionUtil;
use App\Utils\ContraUtil;
use App\Utils\Converter;
use App\Utils\DayBookUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContraController extends Controller
{
    protected $accountUtil;

    protected $converter;

    protected $contraUtil;

    protected $contraDescriptionUtil;

    protected $accountLedgerUtil;

    protected $dayBookUtil;

    public function __construct(
        AccountUtil $accountUtil,
        AccountLedgerUtil $accountLedgerUtil,
        Converter $converter,
        ContraUtil $contraUtil,
        ContraDescriptionUtil $contraDescriptionUtil,
        DayBookUtil $dayBookUtil,
    ) {
        $this->accountUtil = $accountUtil;
        $this->converter = $converter;
        $this->contraUtil = $contraUtil;
        $this->contraDescriptionUtil = $contraDescriptionUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
        $this->dayBookUtil = $dayBookUtil;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('contras_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->contraUtil->list(request: $request, converter: $this->converter);
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('finance.vouchers.contra.index', compact('paymentMethods'));
    }

    public function create($mode)
    {
        if (! auth()->user()->can('contras_add')) {

            abort(403, 'Access Forbidden.');
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();
        if ($mode == 1) {

            return view('finance.vouchers.contra.create_single_entry', compact('paymentMethods'));
        } else {

            return view('finance.vouchers.contra.create_double_entry', compact('paymentMethods'));
        }
    }

    public function show($id)
    {
        if (! auth()->user()->can('contras_index')) {

            abort(403, 'Access Forbidden.');
        }

        $contra = Contra::with([
            'descriptions',
            'user:id,prefix,name,last_name',
            'descriptions.paymentMethod:id,name',
            'descriptions.account:id,name,phone,account_number,account_group_id',
            'descriptions.account.group:id,name',
            'descriptions.paymentMethod:id,name',
        ])->where('id', $id)->first();

        return view('finance.vouchers.contra.ajax_view.show', compact('contra'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $voucherGenerator)
    {
        if (! auth()->user()->can('contras_add')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required|date',
        ], ['date.date' => 'Date Format is invalid.']);

        if ($request->debit_total == 0 || $request->credit_total == 0) {

            return response()->json(['errorMsg' => 'Total Debit or Total Credit must not be 0']);
        } elseif ($request->debit_total != $request->credit_total) {

            return response()->json(['errorMsg' => 'Total Debit and Total Credit must be equal.']);
        }

        try {

            DB::beginTransaction();

            $addContra = $this->contraUtil->addContra(date: $request->date, remarks: $request->remarks, voucherGenerator: $voucherGenerator, voucherPrefix: 'CO', debitTotal: $request->debit_total, creditTotal: $request->credit_total, isTransactionDetails: $request->is_transaction_details, mode: $request->mode);

            $index = 0;
            $creditAccountId = '';
            $creditAmount = '';
            foreach ($request->account_ids as $account_id) {

                $amountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'dr' : 'cr';
                $ledgerAmountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'debit' : 'credit';
                $amount = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? $request->debit_amounts[$index] : $request->credit_amounts[$index];

                if ($creditAccountId == '' && ($request->amount_types[$index] == 'Cr' || $request->amount_types[$index] == 'cr')) {

                    $creditAccountId = $account_id;
                    $creditAmount = $request->credit_amounts[$index];
                }

                // Add Contra Description Entry
                $addContraDescription = $this->contraDescriptionUtil->addContraDescription(contraId: $addContra->id, accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index]);

                //Add Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 12, date: $request->date, account_id: $account_id, trans_id: $addContraDescription->id, amount: $amount, amount_type: $ledgerAmountType);

                $index++;
            }

            // Add Day Book entry for Contra Voucher
            $this->dayBookUtil->addDayBook(voucherTypeId: 11, date: $request->date, accountId: $creditAccountId, transId: $addContra->id, amount: $creditAmount, amountType: 'credit');

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Contra created successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('contras_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $accountUtil = $this->accountUtil;
        $contra = Contra::with([
            'singleModeDebitDescription',
            'singleModeDebitDescription.account:id,name,account_number',
            'singleModeDebitDescription.paymentMethod:id,name',
            'singleModeCreditDescriptions',
            'singleModeCreditDescriptions.account:id,name,account_number',
            'singleModeCreditDescriptions.paymentMethod:id,name',
            'descriptions',
            'descriptions.account:id,name,account_number',
            'descriptions.paymentMethod:id,name',
        ])->where('id', $id)->first();

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();
        if ($contra->mode == 1) {

            return view('finance.vouchers.contra.edit_single_entry', compact('contra', 'accountUtil', 'paymentMethods'));
        } else {

            return view('finance.vouchers.contra.edit_double_entry', compact('contra', 'accountUtil', 'paymentMethods'));
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('contras_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required|date',
        ], ['date.date' => 'Date Format is invalid.']);

        if ($request->debit_total == 0 || $request->credit_total == 0) {

            return response()->json(['errorMsg' => 'Total Debit or Total Credit must not be 0']);
        } elseif ($request->debit_total != $request->credit_total) {

            return response()->json(['errorMsg' => 'Total Debit and Total Credit must be equal.']);
        }

        try {

            DB::beginTransaction();

            $updateContra = $this->contraUtil->updateContra(id: $id, date: $request->date, remarks: $request->remarks, debitTotal: $request->debit_total, creditTotal: $request->credit_total, isTransactionDetails: $request->is_transaction_details);

            // Prepare unused deletable contraDescriptions
            $this->contraDescriptionUtil->prepareUnusedDeletableContraDescriptions($updateContra->descriptions);

            $index = 0;
            $creditAccountId = '';
            $creditAmount = '';
            foreach ($request->account_ids as $account_id) {

                $amountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'dr' : 'cr';
                $ledgerAmountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'debit' : 'credit';
                $amount = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? $request->debit_amounts[$index] : $request->credit_amounts[$index];

                if ($creditAccountId == '' && ($request->amount_types[$index] == 'Cr' || $request->amount_types[$index] == 'cr')) {

                    $creditAccountId = $account_id;
                    $creditAmount = $request->credit_amounts[$index];
                }

                // Update Contra Description Entry
                $updateContraDescription = $this->contraDescriptionUtil->updateContraDescription(contraId: $updateContra->id, contraDescriptionId: $request->contra_description_ids[$index], accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index]);

                //Update Ledger Entry
                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 12, date: $request->date, account_id: $account_id, trans_id: $updateContraDescription->id, amount: $amount, amount_type: $ledgerAmountType);

                $index++;
            }

            // Delete unused contraDescriptions
            $this->contraDescriptionUtil->deleteUnusedContraDescriptions($updateContra->id);

            // Update Day Book entry for Contra Voucher
            $this->dayBookUtil->updateDayBook(voucherTypeId: 11, date: $request->date, accountId: $creditAccountId, transId: $updateContra->id, amount: $creditAmount, amountType: 'credit');

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Contra updated successfully');
    }

    public function delete(Request $receipt, $id)
    {
        if (! auth()->user()->can('contras_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $this->contraUtil->deleteContra($id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Contra deleted successfully');
    }
}
