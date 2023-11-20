<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\GeneralSetting;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Utils\AccountLedgerUtil;
use App\Utils\DayBookUtil;
use App\Utils\ExpenseByPurchaseUtil;
use App\Utils\PaymentDescriptionReferenceUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use App\Utils\ProductStockUtil;
use App\Utils\ProductUtil;
use App\Utils\PurchaseProductUtil;
use App\Utils\PurchaseUtil;
use App\Utils\ReceiveStockUtil;
use App\Utils\RequisitionUtil;
use App\Utils\UserActivityLogUtil;
use DB;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function __construct(
        private PurchaseUtil $purchaseUtil,
        private PaymentUtil $paymentUtil,
        private PaymentDescriptionUtil $paymentDescriptionUtil,
        private PaymentDescriptionReferenceUtil $paymentDescriptionReferenceUtil,
        private PurchaseProductUtil $purchaseProductUtil,
        private ProductStockUtil $productStockUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private ProductUtil $productUtil,
        private UserActivityLogUtil $userActivityLogUtil,
        private RequisitionUtil $requisitionUtil,
        private ReceiveStockUtil $receiveStockUtil,
        private ExpenseByPurchaseUtil $expenseByPurchaseUtil,
        private DayBookUtil $dayBookUtil,
    ) {
    }

    public function index(Request $request, $supplierAccountId = null)
    {
        if (! auth()->user()->can('purchase_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseUtil->purchaseListTable($request, $supplierAccountId);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $purchaseAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('procurement.purchases.index', compact('supplierAccounts', 'purchaseAccounts', 'users'));
    }

    public function purchaseProductList(Request $request)
    {
        if (! auth()->user()->can('purchase_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseProductUtil->purchaseProductListTable($request);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $categories = DB::table('categories')->where('parent_category_id', null)->get(['id', 'name']);

        return view('procurement.purchases.purchase_product_list', compact('supplierAccounts', 'categories'));
    }

    public function show($purchaseId)
    {
        $purchase = Purchase::with([
            'requisition:id,requisition_no,department_id',
            'requisition.department:id,name',
            'receiveStock:id,voucher_no,warehouse_id,requisition_id',
            'receiveStock.warehouse:id,warehouse_name,warehouse_code',
            'receiveStock.requisition:id,requisition_no,department_id',
            'receiveStock.requisition.department:id,name',
            'warehouse:id,warehouse_name,warehouse_code',
            'supplier:id,name,phone,address',
            'admin:id,prefix,name,last_name,user_id',
            'purchaseAccount:id,name',
            'purchaseProducts',
            'purchaseProducts.product',
            'purchaseProducts.product.warranty',
            'purchaseProducts.variant',
            'purchaseProducts.purchaseUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseProducts.purchaseUnit.baseUnit:id,base_unit_id,code_name',

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

            'purchaseByScale:id,voucher_no',
            'expense:id,purchase_ref_id,voucher_no',
        ])->where('id', $purchaseId)->first();

        return view('procurement.purchases.ajax_view.show', compact('purchase'));
    }

    public function create()
    {
        if (! auth()->user()->can('purchase_add')) {

            abort(403, 'Access Forbidden.');
        }

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $accounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $purchaseAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->whereIn('account_groups.sub_group_number', [12])
            ->select('accounts.id', 'accounts.name')->get();

        $warehouses = DB::table('warehouses')
            ->select('warehouses.id', 'warehouses.warehouse_name', 'warehouses.warehouse_code')->get();

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('procurement.purchases.create', compact('warehouses', 'methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'invoice_id' => 'sometimes|unique:purchases,invoice_id',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/c is required.',
            'payment_method_id.required' => 'Payment method field is required.',
            'supplier_account_id.required' => 'Supplier is required.',
        ]);

        if (isset($request->warehouse_count) && ! $request->receive_stock_id) {

            $this->validate($request, ['warehouse_id' => 'required'], ['warehouse_id.required' => 'Warehouse field is required.']);
        }

        if (isset($request->total_additional_expense) && $request->total_additional_expense > 0) {

            $this->validate($request, ['expense_credit_account_id' => 'required'], ['expense_credit_account_id.required' => 'Expense credit A/c field is required.']);
        }

        if (isset($request->paying_amount) && $request->paying_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Credit A/c is required.']);
        }

        if (! isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Product table is empty.']);
        } elseif (count($request->product_ids) > 60) {

            return response()->json(['errorMsg' => 'Purchase invoice items must be less than 60 or equal.']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
            $invoicePrefix = json_decode($settings->prefix, true)['purchase_invoice'];
            $paymentVoucherPrefix = json_decode($settings->prefix, true)['purchase_payment'];
            $expenseVoucherPrefix = json_decode($settings->prefix, true)['expenses'];
            $isEditProductPrice = json_decode($settings->purchase, true)['is_edit_pro_price'];

            $updateLastCreated = Purchase::where('is_last_created', 1)
                ->select('id', 'is_last_created')
                ->first();

            if ($updateLastCreated) {

                $updateLastCreated->is_last_created = 0;
                $updateLastCreated->save();
            }

            // add purchase total information
            $addPurchase = $this->purchaseUtil->addPurchase($request, $codeGenerationService, $invoicePrefix);

            if (isset($request->total_additional_expense) && $request->total_additional_expense > 0) {

                $this->expenseByPurchaseUtil->addExpenseByPurchase($addPurchase, $request, $codeGenerationService, $expenseVoucherPrefix);
            }

            // Add Day Book entry for Purchase
            $this->dayBookUtil->addDayBook(voucherTypeId: 4, date: $request->date, accountId: $request->supplier_account_id, transId: $addPurchase->id, amount: $request->total_invoice_amount, amountType: 'credit');

            // Add Purchase A/c Ledger
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 3, date: $request->date, account_id: $request->purchase_account_id, trans_id: $addPurchase->id, amount: $request->total_invoice_amount, amount_type: 'debit');

            // Add supplier A/c ledger Entry For Purchase
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 3, account_id: $request->supplier_account_id, date: $request->date, trans_id: $addPurchase->id, amount: $request->total_invoice_amount, amount_type: 'credit');

            if ($request->purchase_tax_id) {

                // Add Tax A/c ledger Entry For Purchase
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 3, account_id: $request->purchase_tax_id, date: $request->date, trans_id: $addPurchase->id, amount: $request->purchase_tax_amount, amount_type: 'debit');
            }

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $addPurchaseProduct = $this->purchaseProductUtil->addPurchaseProduct(request: $request, purchaseId: $addPurchase->id, isEditProductPrice: $isEditProductPrice, index: $index);

                if ($addPurchaseProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 17, date: $request->date, account_id: $addPurchaseProduct->tax_ac_id, trans_id: $addPurchaseProduct->id, amount: ($addPurchaseProduct->unit_tax_amount * $addPurchaseProduct->quantity), amount_type: 'debit');
                }

                $index++;
            }

            if ($request->requisition_id) {

                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($request->requisition_id);
                $this->requisitionUtil->updateRequisitionLeftQty($request->requisition_id);
            }

            if (isset($request->paying_amount) && $request->paying_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: $request->payment_note, paymentType: 2, voucherGenerator: $codeGenerationService, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, purchaseRefId: $addPurchase->id);

                // Add Payment Description Debit Entry
                $addPaymentDebitDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->supplier_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount, chequeNo: null);

                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addPaymentDebitDescription->id, refIdColNames: ['purchase_id'], refIds: [$addPurchase->id], amounts: [$request->paying_amount]);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addPaymentDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_id);

                // Add Payment Description Credit Entry
                $addPaymentCreditDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $request->account_id, trans_id: $addPaymentCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');
            }

            // update main product and variant price
            $loop = 0;
            foreach ($request->product_ids as $productId) {

                $variant_id = $request->variant_ids[$loop] != 'noid' ? $request->variant_ids[$loop] : null;
                $__xMargin = isset($request->profits) ? $request->profits[$loop] : 0;
                $__sale_price = isset($request->selling_prices) ? $request->selling_prices[$loop] : 0;
                $__tax_ac_id = $request->tax_ac_ids[$loop] ? $request->tax_ac_ids[$loop] : null;

                $this->productUtil->updateProductAndVariantPrice(
                    $productId,
                    $variant_id,
                    $request->unit_costs_with_discount[$loop],
                    $request->net_unit_costs[$loop],
                    $__xMargin,
                    $__sale_price,
                    $isEditProductPrice,
                    $addPurchase->is_last_created,
                    $__tax_ac_id
                );

                $loop++;
            }

            if (! $request->receive_stock_id) {

                $__index = 0;
                foreach ($request->product_ids as $productId) {

                    $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                    $this->productStockUtil->adjustMainProductAndVariantStock($productId, $variant_id);

                    if (isset($request->warehouse_count)) {

                        $this->productStockUtil->addWarehouseProduct($productId, $variant_id, $request->warehouse_id);
                        $this->productStockUtil->adjustWarehouseStock($productId, $variant_id, $request->warehouse_id);
                    } else {

                        $this->productStockUtil->addBranchProduct($productId, $variant_id);
                        $this->productStockUtil->adjustBranchStock($productId, $variant_id);
                    }

                    $__index++;
                }
            }

            $adjustedPurchase = $this->purchaseUtil->adjustPurchaseInvoiceAmounts($addPurchase);

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 4, data_obj: $adjustedPurchase);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => 'Successfully purchase is created.']);
        } else {

            $purchase = Purchase::with([
                'requisition:id,requisition_no,department_id',
                'requisition.department:id,name',
                'receiveStock:id,voucher_no,warehouse_id,requisition_id',
                'receiveStock.warehouse:id,warehouse_name,warehouse_code',
                'receiveStock.requisition:id,requisition_no,department_id',
                'receiveStock.requisition.department:id,name',
                'warehouse:id,warehouse_name,warehouse_code',
                'supplier',
                'admin:id,prefix,name,last_name,user_id',
                'purchaseProducts',
                'purchaseProducts.product',
                'purchaseProducts.product.warranty',
                'purchaseProducts.variant',
                'purchaseProducts.purchaseUnit:id,code_name,base_unit_id,base_unit_multiplier',
                'purchaseProducts.purchaseUnit.baseUnit:id,base_unit_id,code_name',
                'purchaseByScale:id,voucher_no',
                'expense:id,purchase_ref_id,voucher_no',
            ])->where('id', $addPurchase->id)->first();

            return view('procurement.save_and_print_template.print_purchase', compact('purchase'));
        }
    }

    // Purchase edit view
    public function edit($purchaseId)
    {
        $warehouses = DB::table('warehouses')->select('warehouses.id', 'warehouses.warehouse_name', 'warehouses.warehouse_code')->get();

        $purchase = Purchase::with(
            [
                'supplier:id,name',
                'requisition:id,requisition_no',
                'receiveStock:id,voucher_no,warehouse_id',
                'expense',
                'expense.singleModeCreditDescription',
                'purchaseProducts',
                'purchaseProducts.product',
                'purchaseProducts.variant',
                'purchaseProducts.product.unit:id,name,code_name',
                'purchaseProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
                'purchaseProducts.purchaseUnit:id,name,code_name,base_unit_multiplier',
                'purchaseByScale:id,voucher_no',
            ]
        )->where('id', $purchaseId)->firstOrFail();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $accounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.balance',
                'banks.name as bank'
            )->get();

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

        return view('procurement.purchases.edit', compact('warehouses', 'purchase', 'purchaseAccounts', 'taxAccounts', 'accounts', 'methods', 'supplierAccounts'));
    }

    public function update(Request $request, $purchaseId, CodeGenerationServiceInterface $codeGenerationService)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'purchase_account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/c is required.',
            'supplier_account_id.required' => 'Supplier is required.',
        ]);

        if (isset($request->warehouse_count) && ! $request->receive_stock_id) {

            $this->validate($request, ['warehouse_id' => 'required'], ['warehouse_id.required' => 'Warehouse field is required.']);
        }

        if (isset($request->total_additional_expense) && $request->total_additional_expense > 0) {

            $this->validate($request, ['expense_credit_account_id' => 'required'], ['expense_credit_account_id.required' => 'Expense credit A/c field is required.']);
        }

        if (! isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Product table is empty.']);
        }

        $purchase = Purchase::with(['purchaseProducts', 'expense', 'expense.expenseDescriptions', 'payments', 'references'])
            ->where('id', $purchaseId)->first();

        if (count($purchase->payments) > 0 || count($purchase->references) > 0) {

            if ($purchase->supplier_account_id != $request->supplier_account_id) {

                return response()->json(['errorMsg' => 'Supplier can\'t be changed. There is one or more payments which is referring this purchase invoice.']);
            }
        }

        if (isset($request->paying_amount) && $request->paying_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Credit A/c is required.']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
            $paymentVoucherPrefix = json_decode($settings->prefix, true)['purchase_payment'];
            $expenseVoucherPrefix = json_decode($settings->prefix, true)['expenses'];
            $isEditProductPrice = json_decode($settings->purchase, true)['is_edit_pro_price'];

            // get updatable purchase row
            $storedCurrRequisitionId = $purchase->requisition_id;
            $storedCurrPurchaseAccountId = $purchase->purchase_account_id;
            $storedCurrSupplierAccountId = $purchase->supplier_account_id;
            $storedCurrPurchaseTaxAccountId = $purchase->tax_ac_id;
            $storedCurrReceiveStockId = $purchase->receive_stock_id;
            $storedWarehouseId = $purchase->warehouse_id;
            $storePurchaseProducts = $purchase->purchaseProducts;

            $updatePurchase = $this->purchaseUtil->updatePurchase($purchase, $request);

            if (isset($request->total_additional_expense) && $request->total_additional_expense > 0) {

                $this->expenseByPurchaseUtil->updateExpenseByPurchase($updatePurchase, $request, $codeGenerationService, $expenseVoucherPrefix);
            } else {

                $updatePurchase?->expense?->delete();
            }

            // Update Day Book entry for sales
            $this->dayBookUtil->updateDayBook(voucherTypeId: 4, date: $request->date, accountId: $request->supplier_account_id, transId: $updatePurchase->id, amount: $request->total_invoice_amount, amountType: 'credit');

            // Update Purchase A/c Ledger Entry
            $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 3, date: $request->date, account_id: $request->purchase_account_id, trans_id: $updatePurchase->id, amount: $request->total_invoice_amount, amount_type: 'debit', current_account_id: $storedCurrPurchaseAccountId);

            // Update supplier A/c ledger Entry
            $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 3, date: $request->date, account_id: $request->supplier_account_id, trans_id: $updatePurchase->id, amount: $request->total_invoice_amount, amount_type: 'credit', current_account_id: $storedCurrSupplierAccountId);

            if ($request->purchase_tax_ac_id) {

                // Add Tax ledger Entry
                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 3, date: $request->date, account_id: $updatePurchase->tax_ac_id, trans_id: $updatePurchase->id, amount: $request->purchase_tax_amount, amount_type: 'debit', current_account_id: $storedCurrPurchaseTaxAccountId);
            } else {

                $this->accountLedgerUtil->deleteUnusedLedgerEntry(voucherType: 1, transId: $updatePurchase->id, accountId: $storedCurrPurchaseTaxAccountId);
            }

            // update product and variant Price & quantity
            $loop = 0;
            foreach ($request->product_ids as $productId) {

                $variant_id = $request->variant_ids[$loop] != 'noid' ? $request->variant_ids[$loop] : null;

                $__xMargin = isset($request->profits) ? $request->profits[$loop] : 0;
                $__sale_price = isset($request->selling_prices) ? $request->selling_prices[$loop] : 0;
                $__tax_ac_id = $request->tax_ac_ids[$loop] ? $request->tax_ac_ids[$loop] : null;

                $this->productUtil->updateProductAndVariantPrice(
                    $productId,
                    $variant_id,
                    $request->unit_costs_with_discount[$loop],
                    $request->net_unit_costs[$loop],
                    $__xMargin,
                    $__sale_price,
                    $isEditProductPrice,
                    $updatePurchase->is_last_created,
                    $__tax_ac_id
                );

                $loop++;
            }

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $updatePurchaseProduct = $this->purchaseProductUtil->updatePurchaseProduct(request: $request, purchaseId: $updatePurchase->id, isEditProductPrice: $isEditProductPrice, index: $index, purchaseUtil: $this->purchaseUtil);

                if ($updatePurchaseProduct['updateOrAddPurchaseProduct']->tax_ac_id) {

                    // Add Tax A/c ledger
                    $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 17, date: $request->date, account_id: $updatePurchaseProduct['updateOrAddPurchaseProduct']->tax_ac_id, trans_id: $updatePurchaseProduct['updateOrAddPurchaseProduct']->id, amount: ($updatePurchaseProduct['updateOrAddPurchaseProduct']->unit_tax_amount * $updatePurchaseProduct['updateOrAddPurchaseProduct']->quantity), amount_type: 'debit', current_account_id: $updatePurchaseProduct['currentUnitTaxAcId']);
                } else {

                    $this->accountLedgerUtil->deleteUnusedLedgerEntry(voucherType: 17, transId: $updatePurchaseProduct['updateOrAddPurchaseProduct']->id, accountId: $updatePurchaseProduct['currentUnitTaxAcId']);
                }

                $index++;
            }

            // deleted not getting previous product
            $deletedUnusedPurchaseOrPoProducts = PurchaseProduct::where('purchase_id', $updatePurchase->id)->where('delete_in_update', 1)->get();

            if (count($deletedUnusedPurchaseOrPoProducts) > 0) {

                foreach ($deletedUnusedPurchaseOrPoProducts as $deletedPurchaseProduct) {

                    $storedProductId = $deletedPurchaseProduct->product_id;
                    $storedVariantId = $deletedPurchaseProduct->product_variant_id;
                    $deletedPurchaseProduct->delete();
                    // Adjust deleted product stock

                    $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

                    if (isset($request->warehouse_count)) {

                        $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $request->warehouse_id);
                    } else {

                        $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId);
                    }
                }
            }

            if ($request->requisition_id) {

                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($request->requisition_id);
                $this->requisitionUtil->updateRequisitionLeftQty($request->requisition_id);
            }

            if ($storedCurrRequisitionId && ($storedCurrRequisitionId != $request->requisition_id)) {

                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($storedCurrRequisitionId);
                $this->requisitionUtil->updateRequisitionLeftQty($storedCurrRequisitionId);
            }

            $purchaseProducts = DB::table('purchase_products')->where('purchase_id', $updatePurchase->id)->get();

            foreach ($purchaseProducts as $purchaseProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id);

                if (isset($request->warehouse_count)) {

                    $this->productStockUtil->addWarehouseProduct($purchaseProduct->product_id, $purchaseProduct->product_variant_id, $request->warehouse_id);
                    $this->productStockUtil->adjustWarehouseStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id, $request->warehouse_id);
                } else {

                    $this->productStockUtil->addBranchProduct($purchaseProduct->product_id, $purchaseProduct->product_variant_id);
                    $this->productStockUtil->adjustBranchStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id);
                }
            }

            if (isset($request->warehouse_count) && $storedWarehouseId && $request->warehouse_id != $storedWarehouseId) {

                foreach ($storePurchaseProducts as $purchaseProduct) {

                    $this->productStockUtil->adjustWarehouseStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id, $storedWarehouseId);
                }
            }

            if ($request->paying_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: date('d-m-Y'), remarks: $request->payment_note, paymentType: 2, voucherGenerator: $codeGenerationService, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, purchaseRefId: $updatePurchase->id);

                // Add Payment Description Debit Entry
                $addPaymentDebitDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->supplier_account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no);

                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addPaymentDebitDescription->id, refIdColNames: ['purchase_id'], refIds: [$updatePurchase->id], amounts: [$request->paying_amount]);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: date('d-m-Y'), account_id: $request->supplier_account_id, trans_id: $addPaymentDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_idP);

                // Add Payment Description Credit Entry
                $addPaymentCreditDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: date('d-m-Y'), account_id: $request->account_id, trans_id: $addPaymentCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');
            }

            $adjustedPurchase = $this->purchaseUtil->adjustPurchaseInvoiceAmounts($updatePurchase);

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 2, subject_type: 4, data_obj: $adjustedPurchase);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', 'Successfully purchase is updated');

        return response()->json('Successfully purchase is updated');
    }

    public function getAllUnit()
    {
        return Unit::select('id', 'name')->get();
    }

    public function delete(Request $request, $purchaseId)
    {
        try {

            DB::beginTransaction();

            $deletePurchase = purchase::with([
                'purchaseProducts',
                'purchaseProducts.product',
                'purchaseProducts.variant',
                'purchaseProducts.purchaseSaleChains',
                'payments',
            ])->where('id', $purchaseId)->first();

            //purchase payments
            $storedWarehouseId = $deletePurchase->warehouse_id;
            $storedRequisitionId = $deletePurchase->requisition_id;
            $storedPayments = $deletePurchase->purchase_payments;
            $storedPurchaseAccountId = $deletePurchase->purchase_account_id;
            $storePurchaseProducts = $deletePurchase->purchaseProducts;

            foreach ($deletePurchase->purchaseProducts as $purchaseProduct) {

                if (count($purchaseProduct->purchaseSaleChains) > 0) {

                    $variant = $purchaseProduct->variant ? ' - '.$purchaseProduct->variant->name : '';
                    $product = $purchaseProduct->product->name.$variant;

                    return response()->json("Can not delete is purchase. Mismatch between sold and purchase stock account method. Product: ${product}");
                }
            }

            if (count($deletePurchase->payments) > 0) {

                return response()->json('Can not delete is purchase. Purchase has one or more payments');
            }

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 3, subject_type: $deletePurchase->purchase_status == 3 ? 5 : 4, data_obj: $deletePurchase);

            $deletePurchase->delete();

            foreach ($storePurchaseProducts as $purchaseProduct) {

                $variant_id = $purchaseProduct->product_variant_id ? $purchaseProduct->product_variant_id : null;

                $this->productStockUtil->adjustMainProductAndVariantStock($purchaseProduct->product_id, $variant_id);

                if ($storedWarehouseId) {

                    $this->productStockUtil->adjustWarehouseStock($purchaseProduct->product_id, $variant_id, $storedWarehouseId);
                } else {

                    $this->productStockUtil->adjustBranchStock($purchaseProduct->product_id, $variant_id);
                }
            }

            if ($storedRequisitionId) {

                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($storedRequisitionId);
                $this->requisitionUtil->updateRequisitionLeftQty($storedRequisitionId);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Successfully purchase is deleted');
    }

    // Change purchase status
    public function changeStatus(Request $request, $purchaseId)
    {
        $purchase = Purchase::where('id', $purchaseId)->first();
        $purchase->purchase_status = $request->purchase_status;
        $purchase->save();

        return response()->json('Successfully purchase status is changed.');
    }

    //Show Change status modal
    public function settings()
    {
        return view('procurement.settings.index');
    }

    //Show Change status modal
    public function settingsStore(Request $request)
    {
        $updatePurchaseSettings = GeneralSetting::first();
        $purchaseSettings = [
            'is_edit_pro_price' => isset($request->is_edit_pro_price) ? 1 : 0,
            'is_enable_status' => isset($request->is_enable_status) ? 1 : 0,
            'is_enable_lot_no' => isset($request->is_enable_lot_no) ? 1 : 0,
        ];

        $updatePurchaseSettings->purchase = json_encode($purchaseSettings);
        $updatePurchaseSettings->save();

        return response()->json('Purchase settings updated successfully.');
    }
}
