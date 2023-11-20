<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\IncomeDescription;
use App\Models\PaymentMethod;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\IncomeUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\UserActivityLogUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class IncomeController extends Controller
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

    public function index(Request $request)
    {
        if (! auth()->user()->can('incomes_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('business')->first();
            $incomes = '';
            $converter = $this->converter;

            $query = Income::query();

            if ($request->user_id) {

                $query->where('incomes.created_by_id', $request->user_id);
            }

            if ($request->income_account_id) {

                $query->where('incomes.income_account_ids', 'LIKE', '%'.$request->income_account_id.'%');
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('incomes.report_date', $date_range); // Final
            }

            $incomes = $query
                ->leftJoin('users', 'incomes.created_by_id', 'users.id')
                ->with(
                    'incomeDescriptions:id,income_id,income_account_id,amount',
                    'incomeDescriptions.account:id,name,account_number,account_type',
                )->select(
                    'incomes.*',
                    'users.prefix as cr_prefix',
                    'users.name as cr_name',
                    'users.last_name as cr_last_name',
                )->orderBy('incomes.report_date', 'desc');

            return DataTables::of($incomes)
                ->addColumn('action', function ($row) {

                    $html = <<<'START_HTML'
                    <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                START_HTML;

                    // $viewPaymentRoute = route('income.payment.view', [$row->id]);
                    $showRoute = route('income.show', [$row->id]);
                    $html .= <<<VIEW_LINK
                    <a class="dropdown-item" id="details_button" href="$showRoute"> View</a>
                VIEW_LINK;

                    $indexRoute = route('income.receipts.index', [$row->id]);
                    $html .= <<<VIEW_PAYMENT_LINK
                    <a class="dropdown-item" id="view_receipts" href="$indexRoute"> View Receipts</a>
                VIEW_PAYMENT_LINK;

                    if ($row->due > 0) {

                        $addReceiptRoute = route('income.receipts.create', [$row->id]);
                        $html .= <<<ADD_PAYMENT_LINK
                            <a class="dropdown-item" id="add_payment" href="$addReceiptRoute">Add Receipt</a>
                        ADD_PAYMENT_LINK;
                    }

                    if (auth()->user()->can('edit_expense')) {

                        $editRoute = route('income.edit', [$row->id]);
                        $html .= <<<EDIT_LINK
                            <a class="dropdown-item" href="$editRoute"> Edit</a>
                        EDIT_LINK;
                    }

                    if (auth()->user()->can('delete_expense')) {

                        $deleteRoute = route('income.delete', [$row->id]);
                        $html .= <<<DELETE_LINK
                        <a class="dropdown-item" id="delete" href="$deleteRoute"> Delete</a>
                    DELETE_LINK;
                    }

                    $html .= <<<'END_HTML'
                    </div>
                    </div>
                END_HTML;

                    return $html;
                })->editColumn('descriptions', function ($row) use ($converter) {

                    $html = '';

                    foreach ($row->incomeDescriptions as $description) {

                        $accountType = '';
                        if ($description->account->account_type == 24) {

                            $accountType = 'Direct Income : ';
                        } elseif ($description->account->account_type == 25) {

                            $accountType = 'Indirect Income : ';
                        } else {

                            $accountType = 'Misc. Income A/c : ';
                        }

                        $html .= '<p class="m-0 p-0">-'.$accountType.$description->account->name.': <strong> '.$converter->format_in_bdt($description->amount).'</strong></p>';
                    }

                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->report_date));
                })
                ->editColumn('createdBy', function ($row) {

                    return $row->cr_prefix.' '.$row->cr_name.' '.$row->cr_last_name;
                })
                ->editColumn('payment_status', function ($row) {

                    $html = '';
                    $receivable = $row->total_amount;

                    if ($row->due <= 0) {

                        $html .= <<<'RECEIVED_TEXT'
                        <span class="badge bg-success">Received</span>
                        RECEIVED_TEXT;
                    } elseif ($row->due > 0 && $row->due < $receivable) {

                        $html .= <<<'PARTIAL_TEXT'
                        <span class="badge bg-primary text-white">Partial</span>
                    PARTIAL_TEXT;
                    } elseif ($receivable == $row->due) {

                        $html .= <<<'DUE_TEXT'
                        <span class="badge bg-danger text-white">Due</span>
                    DUE_TEXT;
                    }

                    return $html;
                })

                ->editColumn('received', fn ($row) => '<span class="received" data-value="'.$row->received.'">'.$this->converter->format_in_bdt($row->received).'</span>')

                ->editColumn('total_amount', fn ($row) => '<span class="total_amount" data-value="'.$row->total_amount.'">'.$this->converter->format_in_bdt($row->total_amount).'</span>')

                ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="'.$row->due.'">'.$this->converter->format_in_bdt($row->due).'</span>')

                ->rawColumns(['action', 'date', 'createdBy', 'payment_status', 'received', 'due', 'total_amount', 'descriptions'])
                ->make(true);
        }

        $users = DB::table('users')->where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('finance.income.index', compact('users'));
    }

    public function show($id)
    {
        if (! auth()->user()->can('incomes_show')) {

            abort(403, 'Access Forbidden.');
        }

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

        return view('finance.income.ajax_view.show', compact('income'));
    }

    public function create()
    {
        if (! auth()->user()->can('incomes_create')) {

            abort(403, 'Access Forbidden.');
        }

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

        return view('finance.income.create', compact('methods', 'accounts'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('incomes_create')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required',
            'total_amount' => 'required',
            'received_amount' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $addIncome = new Income();
            $addIncome->voucher_no = $request->voucher_no ? $request->voucher_no : 'INC'.str_pad($this->invoiceVoucherRefIdUtil->getLastId('incomes'), 5, '0', STR_PAD_LEFT);
            $addIncome->created_by_id = auth()->user()->id;
            $addIncome->total_amount = $request->total_amount;
            $addIncome->received = $request->received_amount;
            $addIncome->due = $request->due;
            $addIncome->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));

            $income_account_ids = '';
            foreach ($request->income_account_ids as $income_account_id) {

                $income_account_ids .= $income_account_id.', ';
            }

            $addIncome->income_account_ids = $income_account_ids;
            $addIncome->note = $request->note;
            $addIncome->save();

            $index = 0;
            foreach ($request->income_account_ids as $income_account_id) {

                $addIncomeDescription = new IncomeDescription();
                $addIncomeDescription->income_id = $addIncome->id;
                $addIncomeDescription->income_account_id = $income_account_id;
                $addIncomeDescription->amount = $request->amounts[$index];
                $addIncomeDescription->save();

                $this->accountUtil->addAccountLedger(
                    voucher_type_id: 28,
                    date: $request->date,
                    account_id: $income_account_id,
                    trans_id: $addIncomeDescription->id,
                    amount: $request->amounts[$index],
                    balance_type: 'credit'
                );

                $index++;
            }

            if ($request->received_amount > 0) {

                $addReceiptGetId = $this->incomeUtil->addReceiptGetId(
                    income_id: $addIncome->id,
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
            }

            $income = Income::with(
                [
                    'incomeDescriptions:id,income_id,income_account_id,amount',
                    'incomeDescriptions.account:id,name,account_number,account_type',
                    'createdBy:id,prefix,name,last_name',
                ]
            )->where('id', $addIncome->id)->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 32, data_obj: $income);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('finance.income.ajax_view.income_print', compact('income'));
        }

        return response()->json('Income created successfully!');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('incomes_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $income = Income::with(['incomeDescriptions'])->where('id', $id)->firstOrFail();

        $incomeAccounts = DB::table('accounts')
            ->whereIn('accounts.account_type', [12, 24, 25])
            ->orderBy('accounts.account_type', 'desc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
            )->get();

        foreach ($incomeAccounts as $incomeAccount) {

            $amounts = $this->accountUtil->accountClosingBalance($incomeAccount->id);
            $balance = $this->converter->format_in_bdt($amounts['closing_balance']).' '.$amounts['closing_balance_side_st_name'];
            $incomeAccount->balance = $balance;
        }

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

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        return view('finance.income.edit', compact('income', 'incomeAccounts', 'accounts', 'methods'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('incomes_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required',
            'total_amount' => 'required',
            'received_amount' => 'required',
        ]);

        // Update Income
        try {

            DB::beginTransaction();
            //
            $updateIncome = Income::with(['incomeDescriptions'])->where('id', $id)->first();

            foreach ($updateIncome->incomeDescriptions as $description) {

                $description->is_delete_in_update = 1;
                $description->save();
            }

            $updateIncome->voucher_no = $request->voucher_no;
            $updateIncome->total_amount = $request->total_amount;
            $time = date(' H:i:s', strtotime($updateIncome->report_date));
            $updateIncome->report_date = date('Y-m-d H:i:s', strtotime($request->date.$time));
            $updateIncome->save();

            $index = 0;
            foreach ($request->income_account_ids as $income_account_id) {

                $updateDescription = IncomeDescription::where('income_id', $updateIncome->id)
                    ->where('id', $income_account_id)->first();

                $trans_id = '';
                if ($updateDescription) {

                    $updateDescription->amount = $request->amounts[$index];
                    $updateDescription->is_delete_in_update = 0;
                    $updateDescription->save();
                    $trans_id = $updateDescription->id;
                } else {

                    $addDescription = new IncomeDescription();
                    $addDescription->income_id = $updateIncome->id;
                    $addDescription->income_account_id = $income_account_id;
                    $addDescription->amount = $request->amounts[$index];
                    $addDescription->save();
                    $trans_id = $addDescription->id;
                }

                $this->accountUtil->updateAccountLedger(
                    voucher_type_id: 28,
                    date: $request->date,
                    account_id: $income_account_id,
                    trans_id: $trans_id,
                    amount: $request->amounts[$index],
                    balance_type: 'credit'
                );

                $index++;
            }

            // Delete Unused income description
            $deleteAbleIncomeDescriptions = IncomeDescription::where('income_id', $updateIncome->id)
                ->where('is_delete_in_update', 1)->get();

            foreach ($deleteAbleIncomeDescriptions as $description) {

                $description->delete();
            }

            if ($request->received_amount > 0) {

                $addReceiptGetId = $this->incomeUtil->addReceiptGetId(
                    income_id: $updateIncome->id,
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
            }

            $this->incomeUtil->adjustIncomeAmount($updateIncome);

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 32, data_obj: $updateIncome);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Income updated successfully!');
    }

    public function delete($id)
    {
        if (! auth()->user()->can('incomes_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {
            DB::beginTransaction();

            $deleteIncome = Income::where('id', $id)->first();

            if (! is_null($deleteIncome)) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 32, data_obj: $deleteIncome);
                $deleteIncome->delete();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        DB::statement('ALTER TABLE incomes AUTO_INCREMENT = 1');

        return response()->json('Income deleted successfully!');
    }

    public function getIncomeAccountsByAjax()
    {
        $incomeAccounts = DB::table('accounts')
            ->whereIn('accounts.account_type', [12, 24, 25])
            ->orderBy('accounts.account_type', 'desc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
            )->get();

        foreach ($incomeAccounts as $incomeAccount) {

            $amounts = $this->accountUtil->accountClosingBalance($incomeAccount->id);
            $balance = $this->converter->format_in_bdt($amounts['closing_balance']).' '.$amounts['closing_balance_side_st_name'];
            $incomeAccount->balance = $balance;
        }

        return $incomeAccounts;
    }

    public function incomeAccountQuickAddModal()
    {
        return view('finance.income.ajax_view.add_quick_income_account');
    }
}
