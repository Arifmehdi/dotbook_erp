<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseOrderProduct;
use App\Utils\AccountLedgerUtil;
use App\Utils\DayBookUtil;
use App\Utils\PaymentDescriptionReferenceUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use App\Utils\ProductStockUtil;
use App\Utils\ProductUtil;
use App\Utils\PurchaseOrderProductUtil;
use App\Utils\PurchaseOrderUtil;
use App\Utils\PurchaseUtil;
use App\Utils\RequisitionUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private ProductUtil $productUtil,
        private RequisitionUtil $requisitionUtil,
        private PurchaseOrderUtil $purchaseOrderUtil,
        private PurchaseOrderProductUtil $purchaseOrderProductUtil,
        private PurchaseUtil $purchaseUtil,
        private ProductStockUtil $productStockUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private PaymentUtil $paymentUtil,
        private PaymentDescriptionUtil $paymentDescriptionUtil,
        private PaymentDescriptionReferenceUtil $paymentDescriptionReferenceUtil,
        private UserActivityLogUtil $userActivityLogUtil,
        private DayBookUtil $dayBookUtil,
    ) {
    }

    public function index(Request $request, $supplierAccountId = null)
    {
        if (! auth()->user()->can('all_po')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseOrderUtil->poListTable($request, $supplierAccountId);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('procurement.orders.index', compact('supplierAccounts'));
    }

    public function show($orderId)
    {
        $order = Purchase::with([
            'requisition:id,requisition_no',
            'warehouse',
            'supplier',
            'admin',
            'purchaseAccount:id,name',
            'orderedProducts',
            'orderedProducts.receivedProducts',
            'orderedProducts.receivedProducts.receiveStock',
            'orderedProducts.product',
            'orderedProducts.product.warranty',
            'orderedProducts.variant',
            'orderedProducts.orderUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'orderedProducts.orderUnit.baseUnit:id,base_unit_id,code_name',

            'references:id,payment_description_id,journal_entry_id,purchase_id,amount',
            'references.paymentDescription:id,payment_id',
            'references.paymentDescription.payment:id,voucher_no,date,payment_type',
            'references.paymentDescription.payment.descriptions:id,payment_id,account_id,payment_method_id',
            'references.paymentDescription.payment.descriptions.paymentMethod:id,name',
            'references.paymentDescription.payment.descriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.paymentDescription.payment.descriptions.account.bank:id,name',
            'references.paymentDescription.payment.descriptions.account.group:id,sub_sub_group_number',

            'references.journalEntry:id,journal_id',
            'references.journalEntry.journal:id,voucher_no,date',
            'references.journalEntry.journal.entries:id,journal_id,account_id,payment_method_id',
            'references.journalEntry.journal.entries.paymentMethod:id,name',
            'references.journalEntry.journal.entries.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.journalEntry.journal.entries.account.bank:id,name',
            'references.journalEntry.journal.entries.account.group:id,sub_sub_group_number',
        ])->where('id', $orderId)->first();

        return view('procurement.orders.ajax_view.show', compact('order'));
    }

    public function printSupplierCopy($orderId)
    {
        $order = Purchase::with([
            'supplier',
            'admin',
            'orderedProducts',
            'orderedProducts.product',
            'orderedProducts.variant',
        ])->where('id', $orderId)->first();

        return view('procurement.orders.ajax_view.print_supplier_copy', compact('order'));
    }

    public function create()
    {
        if (! auth()->user()->can('create_po')) {

            abort(403, 'Access Forbidden.');
        }

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $accounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance', 'banks.name as bank')
            ->get();

        $purchaseAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->whereIn('account_groups.sub_group_number', [12])
            ->select('accounts.id', 'accounts.name')->get();

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('procurement.orders.create', compact('warehouses', 'methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (! auth()->user()->can('create_po')) {

            return response()->json(['errorMsg' => 'Access Forbidden.'], 403);
        }

        $this->validate($request, [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/c is required.',
            'account_id.required' => 'Credit field must not be is empty.',
            'payment_method_id.required' => 'Payment method field is required.',
            'supplier_account_id.required' => 'Supplier is required.',
        ]);

        if (isset($request->paying_amount) && $request->paying_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Credit A/c is required.']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
            $paymentVoucherPrefix = json_decode($settings->prefix, true)['purchase_payment'];
            $isEditProductPrice = json_decode($settings->purchase, true)['is_edit_pro_price'];

            if (! isset($request->product_ids)) {

                return response()->json(['errorMsg' => 'Product table is empty.']);
            } elseif (count($request->product_ids) > 60) {

                return response()->json(['errorMsg' => 'Purchase invoice items must be less than 60 or equal.']);
            }

            $updateLastCreated = Purchase::where('is_last_created', 1)->select('id', 'is_last_created')->first();

            if ($updateLastCreated) {

                $updateLastCreated->is_last_created = 0;
                $updateLastCreated->save();
            }

            // Add purchase Order
            $addOrder = $this->purchaseOrderUtil->addPurchaseOrder($request, $codeGenerationService);

            // Add Day Book entry for sales
            $this->dayBookUtil->addDayBook(voucherTypeId: 5, date: $request->date, accountId: $request->supplier_account_id, transId: $addOrder->id, amount: $request->total_invoice_amount, amountType: 'credit');

            // add purchase order product
            $this->purchaseOrderProductUtil->addPurchaseOrderProduct($request, $isEditProductPrice, $addOrder->id);

            if (isset($request->paying_amount) && $request->paying_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: $request->payment_note, paymentType: 2, voucherGenerator: $codeGenerationService, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, purchaseRefId: $addOrder->id);

                // Add Payment Description Debit Entry
                $addPaymentDebitDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->supplier_account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->paying_amount, chequeNo: $request->cheque_no);

                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addPaymentDebitDescription->id, refIdColNames: ['purchase_id'], refIds: [$addOrder->id], amounts: [$request->paying_amount]);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addPaymentDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addPaymentCreditDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $request->account_id, trans_id: $addPaymentCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');
            }

            if ($request->requisition_id) {

                // Update Requisition
                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($request->requisition_id);
            }

            $adjustedPurchase = $this->purchaseUtil->adjustPurchaseInvoiceAmounts($addOrder);

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 5, data_obj: $adjustedPurchase);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => 'Successfully purchase Order is created.']);
        } else {

            $po = Purchase::with([
                'requisition:id,requisition_no',
                'supplier',
                'admin:id,prefix,name,last_name',
                'orderedProducts',
                'orderedProducts.product',
                'orderedProducts.product.warranty',
                'orderedProducts.variant',
                'orderedProducts.orderUnit:id,code_name,base_unit_id,base_unit_multiplier',
                'orderedProducts.orderUnit.baseUnit:id,base_unit_id,code_name',
            ])->where('id', $addOrder->id)->first();

            return view('procurement.save_and_print_template.print_order', compact('po'));
        }
    }

    // Purchase edit view
    public function edit($orderId)
    {
        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $order = Purchase::with(
            [
                'requisition:id,requisition_no',
                'orderedProducts',
                'orderedProducts.product',
                'orderedProducts.product.unit:id,name,code_name',
                'orderedProducts.variant',
                'orderedProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
                'orderedProducts.orderUnit:id,name,code_name,base_unit_multiplier',
            ]
        )->where('id', $orderId)->firstOrFail();

        $accounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance', 'banks.name as bank')->get();

        $purchaseAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->whereIn('account_groups.sub_group_number', [12])
            ->select('accounts.id', 'accounts.name')->get();

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        return view('procurement.orders.edit', compact('warehouses', 'order', 'methods', 'purchaseAccounts', 'taxAccounts', 'accounts', 'supplierAccounts'));
    }

    // update purchase method
    public function update(Request $request, $orderId, CodeGenerationServiceInterface $codeGenerationService)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'purchase_account_id' => 'required',
        ], ['purchase_account_id.required' => 'Purchase A/c is required.']);

        if (isset($request->paying_amount) && $request->paying_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Credit A/c is required.']);
        }

        $purchaseOrder = purchase::with(['orderedProducts', 'payments', 'references'])->where('id', $orderId)->first();

        if (count($purchaseOrder->payments) > 0 || count($purchaseOrder->references) > 0) {

            if ($purchaseOrder->supplier_account_id != $request->supplier_account_id) {

                return response()->json(['errorMsg' => 'Supplier can\'t be changed. There is one or more payments which is referring this purchase order.']);
            }
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
            $paymentVoucherPrefix = json_decode($settings->prefix, true)['purchase_payment'];
            $isEditProductPrice = json_decode($settings->purchase, true)['is_edit_pro_price'];

            if (isset($request->warehouse_count)) {

                $this->validate($request, ['warehouse_id' => 'required']);
            }

            if (! isset($request->product_ids)) {

                return response()->json(['errorMsg' => 'Product table is empty.']);
            }

            foreach ($purchaseOrder->orderedProducts as $orderProduct) {

                $orderProduct->delete_in_update = 1;
                $orderProduct->save();
            }

            // update purchase total information
            $updatePurchaseOrder = $this->purchaseOrderUtil->updatePurchaseOrder($purchaseOrder, $request);

            $this->dayBookUtil->updateDayBook(voucherTypeId: 5, date: $request->date, accountId: $request->supplier_account_id, transId: $updatePurchaseOrder->id, amount: $request->total_invoice_amount, amountType: 'credit');

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $updateOrderProduct = $this->purchaseOrderProductUtil->updatePurchaseOrderProduct($request, $updatePurchaseOrder->id, $isEditProductPrice, $index);

                $index++;
            }

            if (isset($request->paying_amount) && $request->paying_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: $request->payment_note, paymentType: 2, voucherGenerator: $codeGenerationService, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, purchaseRefId: $updatePurchaseOrder->id);

                // Add Payment Description Debit Entry
                $addPaymentDebitDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->supplier_account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->paying_amount, chequeNo: $request->cheque_no);

                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addPaymentDebitDescription->id, refIdColNames: ['purchase_id'], refIds: [$updatePurchaseOrder->id], amounts: [$request->paying_amount]);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addPaymentDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addPaymentCreditDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $request->account_id, trans_id: $addPaymentCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');
            }

            // deleted not getting previous product
            $deletedUnusedPurchaseOrPoProducts = PurchaseOrderProduct::where('purchase_id', $updatePurchaseOrder->id)
                ->where('delete_in_update', 1)
                ->get();

            if (count($deletedUnusedPurchaseOrPoProducts) > 0) {

                foreach ($deletedUnusedPurchaseOrPoProducts as $deletedPurchaseProduct) {

                    $deletedPurchaseProduct->delete();
                }
            }

            $this->purchaseOrderProductUtil->adjustPurchaseOrderProductPendingQty($updatePurchaseOrder->id);

            $this->purchaseOrderUtil->updatePoQtyAndStatusPortion($updatePurchaseOrder);

            if ($request->requisition_id) {

                // Update Requisition
                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($request->requisition_id);
            }

            $adjustedPurchase = $this->purchaseUtil->adjustPurchaseInvoiceAmounts($updatePurchaseOrder);

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 2, subject_type: 5, data_obj: $adjustedPurchase);

            DB::commit();

            session()->flash('successMsg', 'Successfully purchase Order is updated');

            return response()->json('Successfully purchase Order is updated');
        } catch (Exception $e) {

            DB::rollBack();
        }
    }

    // delete purchase method
    public function delete(Request $request, $orderId)
    {
        // get deleting purchase row
        $deleteOrder = purchase::with(['receiveStocks'])->where('id', $orderId)->first();

        if (count($deleteOrder->receiveStocks) > 0) {

            return response()->json('Purchase order can not be deleted. This order associated with receive stock voucher.');
        }

        $storedRequisitionId = $deleteOrder->requisition_id;

        // Add user Log
        $this->userActivityLogUtil->addLog(action: 3, subject_type: 5, data_obj: $deleteOrder);

        $deleteOrder->delete();

        if ($storedRequisitionId) {

            $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($storedRequisitionId);
        }

        DB::statement('ALTER TABLE purchases AUTO_INCREMENT = 1');

        return response()->json('Successfully purchase order is deleted');
    }
}
