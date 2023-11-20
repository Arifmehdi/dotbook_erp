<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interface\FileUploaderServiceInterface;
use App\Models\AccountGroup;
use App\Models\Supplier;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\SupplierContactPersonUtil;
use App\Utils\SupplierDetailsUtil;
use App\Utils\SupplierUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class SupplierController extends Controller
{
    public $supplierUtil;

    public $accountUtil;

    public $invoiceVoucherRefIdUtil;

    public $userActivityLogUtil;

    public $supplierDetailsUtil;

    public $supplierContactPersonUtil;

    public $accountLedgerUtil;

    public function __construct(
        SupplierUtil $supplierUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        UserActivityLogUtil $userActivityLogUtil,
        SupplierDetailsUtil $supplierDetailsUtil,
        SupplierContactPersonUtil $supplierContactPersonUtil,
        AccountLedgerUtil $accountLedgerUtil,
    ) {
        $this->supplierUtil = $supplierUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->supplierDetailsUtil = $supplierDetailsUtil;
        $this->supplierContactPersonUtil = $supplierContactPersonUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (! auth()->user()->can('supplier_all')) {

            abort(403, 'Access Forbidden.');
        }

        $supplierUtil = $this->supplierUtil;

        if ($request->ajax()) {
            return $this->supplierUtil->supplierList();
        }

        $total = [
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return view('contacts::suppliers.index', compact('total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function basicModal()
    {
        if (! auth()->user()->can('supplier_add')) {

            abort(403, 'Access Forbidden.');
        }

        return view('contacts::suppliers.ajax_view.supplier_create_basic_modal');
    }

    public function detailedModal()
    {
        if (! auth()->user()->can('supplier_add')) {

            abort(403, 'Access Forbidden.');
        }

        return view('contacts::suppliers.ajax_view.supplier_create_detailed_modal');
    }

    public function manage(Request $request, $supplierId)
    {
        $supplier = DB::table('suppliers')
            ->leftJoin('accounts', 'suppliers.id', 'accounts.supplier_id')
            ->where('suppliers.id', $supplierId)->select('suppliers.*', 'accounts.id as supplier_account_id')->first();

        return view('contacts::suppliers.manage', compact('supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploaderServiceInterface $fileUploaderService)
    {
        if (! auth()->user()->can('supplier_add')) {

            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $gs = DB::table('general_settings')->select('business', 'prefix')->first();
            $openingBalanceDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));
            $addons = DB::table('addons')->select('branches')->first();

            $addSupplier = $this->supplierUtil->addSupplier($request, $this->invoiceVoucherRefIdUtil, $gs);
            $this->supplierDetailsUtil->addSupplierDetails($request, $addSupplier, $fileUploaderService);
            $this->supplierContactPersonUtil->addSupplierContactPersons($addSupplier, $request);

            $supplierAccountGroup = AccountGroup::where('sub_sub_group_number', 10)->where('is_reserved', 1)->first();
            $request->account_group_id = $supplierAccountGroup->id;
            $addAccount = $this->accountUtil->addAccount($request, supplierId: $addSupplier->id);
            $addSupplier->supplier_account_id = $addAccount->id;

            $this->accountLedgerUtil->addAccountLedger(
                voucher_type_id: 0,
                date: $openingBalanceDate,
                account_id: $addAccount->id,
                trans_id: $addAccount->id,
                amount: $request->opening_balance ? $request->opening_balance : 0.00,
                amount_type: $request->opening_balance_type,
            );

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 2, data_obj: $addSupplier);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addSupplier;
    }

    public function statistics()
    {
        $statistics = [
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return response()->json($statistics);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('contacts::suppliers.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($supplierId)
    {
        if (! auth()->user()->can('supplier_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $supplier = Supplier::with('supplierDetails', 'supplierContactPersonDetails')->where('id', $supplierId)->firstOrFail();

        return view('contacts::suppliers.ajax_view.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, FileUploaderServiceInterface $fileUploaderService)
    {
        if (! auth()->user()->can('supplier_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:suppliers,phone,'.$request->id,
        ]);

        try {

            DB::beginTransaction();

            $gs = DB::table('general_settings')->select('business')->first();
            $openingBalanceDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

            $supplier = Supplier::with('supplierDetails', 'supplierContactPersonDetails', 'account')->where('id', $request->id)->firstOrFail();

            $updateSupplier = $this->supplierUtil->updateSupplier($request, $supplier);
            $this->supplierDetailsUtil->updateSupplierDetails($request, $supplier, $fileUploaderService);
            $this->supplierContactPersonUtil->updateSupplierContactPersons($supplier, $request);

            $account = '';
            $supplierAccountGroup = AccountGroup::where('sub_sub_group_number', 10)->where('is_reserved', 1)->first();
            if ($supplier->account) {

                $request->account_group_id = $supplierAccountGroup->id;
                $account = $this->accountUtil->updateAccount($request, $supplier->account);
            } else {

                $request->account_group_id = $supplierAccountGroup->id;
                $account = $this->accountUtil->addAccount($request, supplierId: $supplier->id);
            }

            $this->accountLedgerUtil->updateAccountLedger(
                voucher_type_id: 0,
                date: $openingBalanceDate,
                account_id: $account->id,
                trans_id: $account->id,
                amount: $request->opening_balance ? $request->opening_balance : 0.00,
                amount_type: $request->opening_balance_type,
            );

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 2, data_obj: $updateSupplier);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Supplier updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request, $supplierId)
    {
        if (! auth()->user()->can('supplier_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $deleteSupplier = Supplier::with(['account', 'account.accountLedgersWithOutOpeningBalances'])->where('id', $supplierId)->first();
            $account = $deleteSupplier?->account;
            $ledgers = $deleteSupplier?->account?->accountLedgersWithOutOpeningBalances;

            if (isset($ledgers) && count($ledgers) > 1) {
                return response()->json(['errorMsg' => 'Supplier can\'t be deleted. One or more entry has been created in the ledger.']);
            }

            if (! is_null($deleteSupplier)) {
                $deleteSupplier->delete();
                $this->userActivityLogUtil->addLog(action: 3, subject_type: 2, data_obj: $deleteSupplier);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        DB::statement('ALTER TABLE suppliers AUTO_INCREMENT = 1');

        return response()->json('supplier deleted successfully');
    }

    public function viewSupplier($supplierId)
    {
        if (! auth()->user()->can('supplier_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $supplier = Supplier::with('supplierDetails', 'supplierContactPersonDetails')->where('id', $supplierId)->firstOrFail();

        return view('contacts::suppliers.show', compact('supplier'));
    }

    public function changeStatus($supplierId)
    {
        $statusChange = Supplier::where('id', $supplierId)->first();

        if ($statusChange->status == 1) {
            $statusChange->status = 0;
            $statusChange->save();

            return response()->json('Supplier deactivated successfully');
        } else {
            $statusChange->status = 1;
            $statusChange->save();

            return response()->json('Supplier activated successfully');
        }
    }

    public function viewSupplierPdf($supplierId)
    {
        $supplier = Supplier::with('supplierDetails', 'supplierContactPersonDetails')->where('id', $supplierId)->firstOrFail();
        $pdf = PDF::loadView('contacts::suppliers.view-supplier-pdf', compact('supplier'));
        $pdf->stream("{$supplier->name}-view.pdf");
    }
}
