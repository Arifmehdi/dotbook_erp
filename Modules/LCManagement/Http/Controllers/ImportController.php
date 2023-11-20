<?php

namespace Modules\LCManagement\Http\Controllers;

use App\Utils\AccountUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\LC\ImportUtil;
use App\Utils\UserActivityLogUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\LCManagement\Entities\Import;

class ImportController extends Controller
{
    protected $util;

    protected $accountUtil;

    protected $invoiceVoucherRefIdUtil;

    protected $userActivityLogUtil;

    protected $importUtil;

    public function __construct(
        Util $util,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        UserActivityLogUtil $userActivityLogUtil,
        ImportUtil $importUtil,
    ) {
        $this->util = $util;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->importUtil = $importUtil;
    }

    public function create()
    {

        if (! auth()->user()->can('import_purchase_order_create')) {

            abort(403, 'Access denied.');
        }

        $lcs = DB::table('lcs')->select('id', 'lc_no', 'opening_date', 'last_date', 'expire_date')->get();
        $units = DB::table('units')->get();

        $accounts = DB::table('accounts')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $purchaseAccounts = DB::table('accounts')
            ->where('accounts.account_type', 3)
            ->get(['accounts.id', 'accounts.name']);

        $currencies = DB::table('currencies')->select('id', 'code', 'country')->get();
        $banks = DB::table('banks')->select('id', 'name')->get();
        $exporters = DB::table('exporters')->select('id', 'name', 'phone', 'exporter_id')->get();
        $advisingBanks = DB::table('advising_banks')->select('id', 'name')->get();
        $insuranceCompanies = DB::table('insurance_companies')->select('id', 'name', 'company_id')->get();
        $cnfAgents = DB::table('cnf_agents')->select('id', 'name', 'agent_id')->get();

        return view('lcmanagement::imports.create', compact('lcs', 'purchaseAccounts', 'units', 'currencies', 'banks', 'exporters', 'advisingBanks', 'accounts', 'insuranceCompanies', 'cnfAgents'));
    }

    public function store(Request $request)
    {

        if (! auth()->user()->can('import_purchase_order_create')) {
            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'exporter_id' => 'required|date',
            'lc_id' => 'required',
            'order_date' => 'required|date',
            'ledger_account_id' => 'required',
            'goods_country_id' => 'required',
            'destination_country_id' => 'required',
        ], [
            'exporter_id.required' => 'Exporter field is required.',
            'lc_id.required' => 'LC field is required.',
            'ledger_account_id.required' => 'Ledger A/c field is required.',
            'destination_country_id.required' => 'Destination field is required.',
        ]);

        try {

            DB::beginTransaction();

            $addImport = new Import();
            $addImport->import_po_no = $request->invoice_id ? $request->invoice_id : 'IMPO'.str_pad($this->invoiceVoucherRefIdUtil->getLastId('imports'), 5, '0', STR_PAD_LEFT);
            $addImport->exporter_id = $request->exporter_id;
            $addImport->lc_id = $request->lc_id;
            $addImport->order_date = date('Y-m-d H:i:s', strtotime($request->order_date.date(' H:i:s')));
            $addImport->ledger_account_id = $request->ledger_account_id;
            $addImport->receive_date = $request->receive_date ? date('Y-m-d H:i:s', strtotime($request->receive_date.date(' H:i:s'))) : '';
            $addImport->proforma_no = $request->proforma_no;
            $addImport->goods_country_id = $request->goods_country_id;
            $addImport->destination_country_id = $request->destination_country_id;
            $addImport->terms_of_delivery = $request->terms_of_delivery;
            $addImport->terms_of_payment = $request->terms_of_payment;
            $addImport->lc_amount = $request->lc_amount;
            $addImport->currency_id = $request->currency_id;
            $addImport->currency_rate = $request->currency_rate;
            $addImport->total_amount = $request->total_amount;
            $addImport->lc_margin_amount = $request->lc_margin_amount;
            $addImport->insurance_company_id = $request->insurance_company_id;
            $addImport->insurance_payable_amt = $request->insurance_payable_amt;
            $addImport->shipment_mode = $request->shipment_mode;
            $addImport->cnf_agent_id = $request->cnf_agent_id;
            $addImport->mode_of_amount = $request->mode_of_amount;
            $addImport->advising_bank_id = $request->advising_bank_id;
            $addImport->issuing_bank_id = $request->issuing_bank_id;
            $addImport->opening_bank_id = $request->opening_bank_id;
            $addImport->account_id = $request->account_id;
            $addImport->save();

            $this->importUtil->addImportProduct($request, $addImport->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => 'Successfully import purchase order is created.']);
        } else {

            $import = Import::with([
                'lc:id,lc_no',
                'importFrom:id,country',
                'destinationTo:id,country',
                'exporter',
                'createdBy:id,prefix,name,last_name',
                'insuranceCompany:id,name,company_id',
                'cnf:id,name,agent_id',
                'advisingBank:id,name',
                'issuingBank:id,name',
                'openingBank:id,name',
                'account:id,name,account_number',
                'importProducts',
                'importProducts.product',
                'importProducts.variant',
            ])->where('id', $addImport->id)->first();

            return view('lc.imports.save_and_print_template.print_import_purchase_order', compact('import'));
        }
    }
}
