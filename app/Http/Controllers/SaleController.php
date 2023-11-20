<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Jobs\SaleMailJob;
use App\Models\GatePass;
use App\Models\PaymentMethod;
use App\Models\ProductBranch;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\DayBookUtil;
use App\Utils\DeliveryOrderUtil;
use App\Utils\PaymentDescriptionReferenceUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use App\Utils\ProductStockUtil;
use App\Utils\PurchaseUtil;
use App\Utils\SaleProductUtil;
use App\Utils\SaleUtil;
use App\Utils\SmsUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct(
        private SaleUtil $saleUtil,
        private SaleProductUtil $saleProductUtil,
        private SmsUtil $smsUtil,
        private ProductStockUtil $productStockUtil,
        private AccountUtil $accountUtil,
        private PurchaseUtil $purchaseUtil,
        private UserActivityLogUtil $userActivityLogUtil,
        private DeliveryOrderUtil $deliveryOrderUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private PaymentUtil $paymentUtil,
        private PaymentDescriptionUtil $paymentDescriptionUtil,
        private PaymentDescriptionReferenceUtil $paymentDescriptionReferenceUtil,
        private DayBookUtil $dayBookUtil,
    ) {
    }

    public function index(Request $request, $customerAccountId = null, $srUserId = null)
    {
        if (!auth()->user()->can('view_sales')) {

            abort(403, 'Access Forbidden.');
        }

        $customerAccountId = $customerAccountId == 'null' ? null : $customerAccountId;

        if ($request->ajax()) {

            return $this->saleUtil->addSaleTable($request, $customerAccountId, $srUserId);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        return view('sales_app.sales.index', compact('customerAccounts', 'saleAccounts', 'users'));
    }

    public function show($saleId)
    {
        $sale = Sale::with([
            'customer:id,name,phone,address',
            'saleBy:id,prefix,name,last_name',
            'weight',
            'saleProducts',
            'saleProducts.product:id,name,product_code',
            'saleProducts.product.warranty',
            'saleProducts.variant:id,variant_name,variant_code',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.saleUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.saleUnit.baseUnit:id,code_name',
            'do:id,do_id,do_date,shipping_address,order_by_id,all_price_type',
            'salesAccount:id,name',
            'sr:id,prefix,name,last_name',

            'references:id,payment_description_id,sale_id,amount',
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
        ])->where('id', $saleId)->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($saleId, true);

        return view('sales_app.sales.ajax_view.show', compact('sale', 'customerCopySaleProducts'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $invoice_schemas = DB::table('invoice_schemas')->get(['format', 'prefix', 'start_from']);

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $accounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->get();

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        $users = [];
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('sales_app.sales.create', compact(
            'customerAccounts',
            'methods',
            'accounts',
            'saleAccounts',
            'price_groups',
            'invoice_schemas',
            'warehouses',
            'users',
            'taxAccounts',
        ));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        $this->validate($request, [
            'customer_account_id' => 'required',
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
        ], [
            'customer_account_id.required' => 'Customer is required',
            'sale_account_id.required' => 'Sale A/c is required',
        ]);

        if (isset($request->user_count)) {

            $this->validate($request, ['user_id' => 'required'], ['user_id.required' => 'User is required']);
        }

        if (isset($request->receive_amount) && $request->receive_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Debit A/c is required']);
        }

        $srUserId = isset($request->user_count) ? $request->user_id : auth()->user()->id;

        $settings = DB::table('general_settings')
            ->select(['id', 'business', 'prefix', 'send_es_settings'])
            ->first();

        $receiptVoucherPrefix = json_decode($settings->prefix, true)['sale_payment'];
        $__receiptVoucherPrefix = $receiptVoucherPrefix != null ? $receiptVoucherPrefix : 'RV';

        $checkCreditLimit = false;
        if ($checkCreditLimit == true) {

            $creditLimitRestriction = $this->saleUtil->checkCreditLimit($request);
            if ($creditLimitRestriction['pass'] == false) {

                return response()->json(['errorMsg' => $creditLimitRestriction['msg']]);
            }
        }

        $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];
        $paymentVoucherPrefix = json_decode($settings->prefix, true)['sale_payment'];

        $branchInvoiceSchema = DB::table('invoice_schemas')
            ->select(
                'invoice_schemas.id as schema_id',
                'invoice_schemas.prefix',
                'invoice_schemas.format',
                'invoice_schemas.start_from',
            )->first();

        // $invoicePrefix = '';
        // if ($request->invoice_schema) {

        //     $invoicePrefix = $request->invoice_schema;
        // } else {

        //     if ($branchInvoiceSchema && $branchInvoiceSchema->prefix !== null) {

        //         $invoicePrefix = $branchInvoiceSchema->format == 2 ? date('Y') . $branchInvoiceSchema->start_from : $branchInvoiceSchema->prefix . $branchInvoiceSchema->start_from;
        //     } else {

        //         $defaultSchemas = DB::table('invoice_schemas')->where('is_default', 1)->first();
        //         $invoicePrefix = $defaultSchemas->format == 2 ? date('Y') . $defaultSchemas->start_from : $defaultSchemas->prefix . $defaultSchemas->start_from;
        //     }
        // }

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'Item table is empty']);
        }

        try {

            DB::beginTransaction();

            $addSale = $this->saleUtil->addSale($request, $srUserId, $codeGenerationService);

            // Add sales A/c ledger
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 1, date: $request->date, account_id: $request->sale_account_id, trans_id: $addSale->id, amount: $request->sales_ledger_amount, amount_type: 'credit');

            // Add Day Book entry for sales
            $this->dayBookUtil->addDayBook(voucherTypeId: 1, date: $request->date, accountId: $request->customer_account_id, transId: $addSale->id, amount: $request->total_invoice_amount, amountType: 'debit');

            // Add Customer Ledger entry for sales
            if ($request->customer_account_id) {

                // Add customer ledger
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 1, date: $request->date, account_id: $request->customer_account_id, trans_id: $addSale->id, amount: $request->total_invoice_amount, amount_type: 'debit', user_id: $srUserId);
            }

            if ($request->order_tax_ac_id) {

                // Add customer ledger
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 1, date: $request->date, account_id: $request->order_tax_ac_id, trans_id: $addSale->id, amount: $request->order_tax_amount, amount_type: 'credit');
            }

            if (isset($request->receive_amount) && $request->receive_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: null, paymentType: 1, voucherGenerator: $codeGenerationService, voucherPrefix: $__receiptVoucherPrefix, debitTotal: $request->receive_amount, creditTotal: $request->receive_amount, saleRefId: $addSale->id);

                // Add Payment Description Debit Entry
                $addDebitPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, chequeNo: $request->cheque_no, transactionNo: $request->transaction_no, amountType: 'dr', amount: $request->receive_amount);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->account_id, trans_id: $addDebitPaymentDescription->id, amount: $request->receive_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addCreditPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->customer_account_id, paymentMethodId: $request->payment_method_id, chequeNo: $request->cheque_no, transactionNo: $request->transaction_no, amountType: 'cr', amount: $request->receive_amount, userId: $srUserId);

                // Add Payment Description Reference
                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addCreditPaymentDescription->id, refIdColNames: ['sale_id'], refIds: [$addSale->id], amounts: [$request->receive_amount]);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->customer_account_id, trans_id: $addCreditPaymentDescription->id, amount: $request->receive_amount, amount_type: 'credit', user_id: $srUserId, cash_bank_account_id: $request->account_id);
            }

            // update product quantity and add sale product
            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $addSaleProduct = $this->saleProductUtil->addSaleProduct(saleId: $addSale->id, request: $request, index: $index);

                if ($addSaleProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 16, date: $request->date, account_id: $addSaleProduct->tax_ac_id, trans_id: $addSaleProduct->id, amount: ($addSaleProduct->unit_tax_amount * $addSaleProduct->quantity), amount_type: 'credit');
                }

                $index++;
            }

            // Add sale payment
            $sale = Sale::with([
                'customer',
                'saleProducts',
                'saleProducts.product:id,name,product_code,is_manage_stock',
                'saleProducts.variant:id,variant_name,variant_code',
                'saleProducts.warehouse',
                'saleBy:id,prefix,name,last_name',
                'sr:id,prefix,name,last_name',
                'weight',
                'do:id,do_id,do_date,order_by_id,order_id,all_price_type',
            ])->where('id', $addSale->id)->first();

            $adjustedSale = $this->saleUtil->adjustSaleInvoiceAmounts($sale);

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 7, data_obj: $sale);

            $__index = 0;
            foreach ($request->product_ids as $product_id) {

                $warehouse_id = $request->warehouse_ids[$__index] ? $request->warehouse_ids[$__index] : null;

                $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;

                $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);

                if ($warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($product_id, $variant_id);
                }

                $__index++;
            }

            $this->saleUtil->addPurchaseSaleProductChain($sale, $stockAccountingMethod);

            // if (
            //     \App\Models\GeneralSetting::isEmailActive() &&
            //     json_decode($settings->send_es_settings, true)['send_inv_via_email'] == '1'
            // ) {

            //     if ($sale->customer && $sale->customer->email) {

            //         SaleMailJob::dispatch($sale->customer->email, $sale)
            //             ->delay(now()->addSeconds(5));
            //     }
            // }

            // if (
            //     \App\Models\GeneralSetting::isSmsActive() &&
            //     json_decode($settings->send_es_settings, true)['send_notice_via_sms'] == '1'
            // ) {

            //     if ($sale->customer && $sale->customer->phone) {

            //         $this->smsUtil->sendSaleSms($sale);
            //     }
            // }

            $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($sale->id, true);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('sales_app.save_and_print_template.sale_print', compact('sale', 'customerCopySaleProducts'));
        } else {

            return response()->json(['finalMsg' => 'Sale created successfully']);
        }
    }

    public function edit($saleId)
    {
        if (!auth()->user()->can('edit_sale')) {

            abort(403, 'Access Forbidden.');
        }

        $priceGroups = DB::table('price_groups')->where('status', 'Active')->get();

        $sale = Sale::with([
            'saleProducts',
            'saleProducts.warehouse',
            'saleProducts.product',
            'saleProducts.product.unit:id,name,code_name',
            'saleProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.variant',
            'saleProducts.saleUnit:id,name,base_unit_multiplier',
            'saleProducts.product.comboProducts',
            'saleProducts.product.comboProducts.parentProduct',
            'saleProducts.product.comboProducts.product_variant',
            'do:id,order_by_id',
        ])->where('id', $saleId)->first();

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

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

        $warehouses = DB::table('warehouses')->select('warehouses.id', 'warehouses.warehouse_name as name', 'warehouses.warehouse_code as code')->get();

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = [];
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('sales_app.sales.edit', compact('sale', 'priceGroups', 'saleAccounts', 'taxAccounts', 'methods', 'accounts', 'warehouses', 'users', 'customerAccounts'));
    }

    public function update(Request $request, $saleId, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (!auth()->user()->can('edit_sale')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
            'customer_account_id' => 'required',
            'sale_account_id' => 'required',
        ], [
            'customer_account_id.required' => 'Customer is required',
            'sale_account_id.required' => 'Sale A/c is required',
        ]);

        if (isset($request->user_count)) {

            $this->validate($request, ['user_id' => 'required'], ['user_id.required' => 'User is required']);
        }

        if (isset($request->receive_amount) && $request->receive_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Debit A/c is required']);
        }

        $settings = DB::table('general_settings')->select(['id', 'business', 'prefix'])->first();

        $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'Product table is empty']);
        }

        $sale = Sale::with([
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.product.comboProducts',
            'do',
            'payments',
            'references',
        ])->where('id', $saleId)->first();

        if (count($sale->payments) > 0 || count($sale->references) > 0) {

            if ($sale->customer_account_id != $request->customer_account_id) {

                return response()->json(['errorMsg' => 'Customer can\'t be changed. There is one or more payments which is referring this sales invoice.']);
            }
        }

        $settings = DB::table('general_settings')->select(['prefix'])->first();
        $receiptVoucherPrefix = json_decode($settings->prefix, true)['sale_payment'];
        $__receiptVoucherPrefix = $receiptVoucherPrefix != null ? $receiptVoucherPrefix : 'RV';

        $storedCurrentSrUserId = $sale->sr_user_id;
        $storedCurrentTaxAcId = $sale->tax_ac_id;
        $storedCurrCustomerAccountId = $sale->customer_account_id;
        $storedCurrSalesAccountId = $sale->sale_account_id;
        $storedSaleProducts = $sale->saleProducts;

        $srUserId = isset($request->user_count) ? $request->user_id : $storedCurrentSrUserId;

        $checkCreditLimit = false;
        if ($checkCreditLimit == true) {

            $creditLimitRestriction = $this->saleUtil->checkCreditLimit($request);
            if ($creditLimitRestriction['pass'] == false) {

                return response()->json(['errorMsg' => $creditLimitRestriction['msg']]);
            }
        }

        try {

            DB::beginTransaction();

            foreach ($sale->saleProducts as $saleProduct) {

                $saleProduct->delete_in_update = 1;
                $saleProduct->save();
            }

            $updateSale = $this->saleUtil->updateSale($sale, $request, $srUserId);

            // Update Day Book entry for sales
            $this->dayBookUtil->updateDayBook(voucherTypeId: 1, date: $request->date, accountId: $request->customer_account_id, transId: $updateSale->id, amount: $request->total_invoice_amount, amountType: 'debit');

            // Update Sales A/c Ledger
            $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 1, date: $request->date, account_id: $request->sale_account_id, trans_id: $updateSale->id, amount: ($request->sales_ledger_amount - $request->order_tax_amount), amount_type: 'credit', current_account_id: $storedCurrSalesAccountId);

            if ($updateSale->customer_account_id) {

                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 1, date: $request->date, account_id: $updateSale->customer_account_id, trans_id: $updateSale->id, amount: $request->total_invoice_amount, amount_type: 'debit', user_id: $storedCurrentSrUserId, new_user_id: $srUserId, current_account_id: $storedCurrCustomerAccountId);
            }

            if ($request->order_tax_ac_id) {

                // Add Tax ledger Entry
                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 1, date: $request->date, account_id: $updateSale->order_tax_ac_id, trans_id: $updateSale->id, amount: $request->order_tax_amount, amount_type: 'credit', current_account_id: $storedCurrentTaxAcId);
            } else {

                $this->accountLedgerUtil->deleteUnusedLedgerEntry(voucherType: 1, transId: $updateSale->id, accountId: $storedCurrentTaxAcId);
            }

            // Update/Add sale product rows
            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $updateSaleProduct = $this->saleProductUtil->updateSaleProduct(saleId: $updateSale->id, request: $request, index: $index);

                if ($updateSaleProduct['addOrUpdateSaleProduct']->tax_ac_id) {

                    // Add Tax A/c ledger
                    $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 16, date: $request->date, account_id: $updateSaleProduct['addOrUpdateSaleProduct']->tax_ac_id, trans_id: $updateSaleProduct['addOrUpdateSaleProduct']->id, amount: ($updateSaleProduct['addOrUpdateSaleProduct']->unit_tax_amount * $updateSaleProduct['addOrUpdateSaleProduct']->quantity), amount_type: 'credit', current_account_id: $updateSaleProduct['currentTaxAcId']);
                } else {

                    $this->accountLedgerUtil->deleteUnusedLedgerEntry(voucherType: 16, transId: $updateSaleProduct['addOrUpdateSaleProduct']->id, accountId: $updateSaleProduct['currentTaxAcId']);
                }

                $index++;
            }

            if (isset($request->receive_amount) && $request->receive_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: null, paymentType: 1, voucherGenerator: $codeGenerationService, voucherPrefix: $__receiptVoucherPrefix, debitTotal: $request->receive_amount, creditTotal: $request->receive_amount, saleRefId: $updateSale->id);

                // Add Payment Description Debit Entry
                $addDebitPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, chequeNo: $request->cheque_no, transactionNo: $request->transaction_no, amountType: 'dr', amount: $request->receive_amount, userId: $srUserId);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->account_id, trans_id: $addDebitPaymentDescription->id, amount: $request->receive_amount, amount_type: 'debit', user_id: $srUserId);

                // Add Payment Description Credit Entry
                $addCreditPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->customer_account_id, paymentMethodId: $request->payment_method_id, chequeNo: $request->cheque_no, transactionNo: $request->transaction_no, amountType: 'cr', amount: $request->receive_amount, userId: $srUserId);

                // Add Payment Description Reference
                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addCreditPaymentDescription->id, refIdColNames: ['sale_id'], refIds: [$updateSale->id], amounts: [$request->receive_amount]);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->customer_account_id, trans_id: $addCreditPaymentDescription->id, amount: $request->receive_amount, amount_type: 'credit', user_id: $srUserId, cash_bank_account_id: $request->account_id);
            }

            $deleteNotFoundSaleProducts = SaleProduct::with(
                'purchaseSaleProductChains',
                'purchaseSaleProductChains.purchaseProduct'
            )->where('sale_id', $updateSale->id)->where('delete_in_update', 1)->get();

            foreach ($deleteNotFoundSaleProducts as $deleteNotFoundSaleProduct) {

                $storedProductId = $deleteNotFoundSaleProduct->product_id;
                $storedVariantId = $deleteNotFoundSaleProduct->product_variant_id ? $deleteNotFoundSaleProduct->product_variant_id : null;
                $storedStockBranchId = $deleteNotFoundSaleProduct->stock_branch_id;
                $storedStockWarehouseId = $deleteNotFoundSaleProduct->stock_warehouse_id;
                $purchaseSaleProductChains = $deleteNotFoundSaleProduct->purchaseSaleProductChains;

                $deleteNotFoundSaleProduct->delete();

                $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

                if ($storedStockWarehouseId) {

                    $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $storedStockWarehouseId);
                } else {

                    $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, $storedStockBranchId);
                }

                foreach ($purchaseSaleProductChains as $purchaseSaleProductChain) {

                    $this->purchaseUtil->adjustPurchaseLeftQty($purchaseSaleProductChain->purchaseProduct);
                }
            }

            $saleProducts = DB::table('sale_products')->where('sale_id', $updateSale->id)->get();

            foreach ($saleProducts as $saleProduct) {

                $variant_id = $saleProduct->product_variant_id ? $saleProduct->product_variant_id : null;

                $this->productStockUtil->adjustMainProductAndVariantStock($saleProduct->product_id, $variant_id);

                if ($saleProduct->stock_warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($saleProduct->product_id, $variant_id, $saleProduct->stock_warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($saleProduct->product_id, $variant_id, $saleProduct->stock_branch_id);
                }
            }

            foreach ($storedSaleProducts as $saleProduct) {

                if ($saleProduct->stock_warehouse_id) {

                    $check = DB::table('sale_products')->where('id', $saleProduct->id)
                        ->where('stock_warehouse_id', $saleProduct->stock_warehouse_id)
                        ->where('product_id', $saleProduct->product_id)
                        ->where('product_variant_id', $saleProduct->product_variant_id)->first();

                    if (!$check) {

                        $this->productStockUtil->adjustWarehouseStock($saleProduct->product_id, $saleProduct->product_variant_id, $saleProduct->stock_warehouse_id);
                    }
                }
            }

            $sale = Sale::with([
                'saleProducts',
                'saleProducts.product',
                'saleProducts.purchaseSaleProductChains',
                'saleProducts.purchaseSaleProductChains.purchaseProduct',
            ])->where('id', $updateSale->id)->first();

            $this->saleUtil->updatePurchaseSaleProductChain($sale, $stockAccountingMethod);

            if ($updateSale->do) {

                $this->deliveryOrderUtil->calculateDoLeftQty($updateSale->do);
            }

            $adjustedSale = $this->saleUtil->adjustSaleInvoiceAmounts($updateSale);
            $this->userActivityLogUtil->addLog(action: 2, subject_type: $request->status == 1 ? 7 : 8, data_obj: $adjustedSale);
            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', 'Sale updated successfully');

        return response()->json(['successMsg' => 'Sale updated successfully']);
    }

    public function delete(Request $request, $saleId)
    {
        $this->saleUtil->deleteSale($request, $saleId);

        DB::statement('ALTER TABLE sales AUTO_INCREMENT = 1');

        return response()->json('Sale deleted successfully');
    }

    public function getRecentProduct($product_id)
    {
        $product = ProductBranch::with(['product', 'product.tax', 'product.unit'])
            ->where('product_id', $product_id)
            ->first();

        if ($product->product_quantity > 0) {

            return view('sales_app.ajax_view.recent_product_view', compact('product'));
        } else {

            return response()->json([
                'errorMsg' => 'Product is not added in the sale table, cause you did not add any number of opening stock in this Company.',
            ]);
        }
    }

    public function print($saleId)
    {
        $sale = Sale::with([
            'customer',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.product.warranty',
            'saleProducts.variant',
            'saleBy:id,prefix,name,last_name',
            'quotationBy:id,prefix,name,last_name',
            'sr:id,prefix,name,last_name',
        ])->where('id', $saleId)->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($saleId, true);

        if ($sale->status == 1) {

            if ($sale->created_by == 1) {

                return view('sales_app.save_and_print_template.sale_print', compact('sale', 'customerCopySaleProducts'));
            } else {

                return view('sales_app.save_and_print_template.pos_sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount'
                ));
            }
        } elseif ($sale->status == 4) {

            $quotation = $sale;

            return view('sales_app.sales.save_and_print_template.quotation_print', compact('quotation', 'customerCopySaleProducts'));
        }
    }

    public function getProductPriceGroup()
    {
        return DB::table('price_group_products')->get(['id', 'price_group_id', 'product_id', 'variant_id', 'price']);
    }

    public function printSaleGatePass(Request $request, $saleId, CodeGenerationServiceInterface $codeGenerationService)
    {
        $gatePass = DB::table('gate_passes')->where('sale_id', $saleId)->first();

        if (!$gatePass) {

            $addGatePass = new GatePass();
            $addGatePass->voucher_no = $codeGenerationService->generateMonthWise(table: 'gate_passes', column: 'voucher_no', prefix: 'SGP', splitter: '-', suffixSeparator: '-');
            $addGatePass->gp_for = 1;
            $addGatePass->sale_id = $saleId;
            $addGatePass->created_by_id = auth()->user()->id;
            $addGatePass->save();
        }

        $sale = Sale::with([
            'customer',
            'gatePass',
            'gatePass.createdBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product:id,name,product_code,is_manage_stock',
            'saleProducts.variant:id,variant_name,variant_code',
        ])->where('id', $saleId)->orderBy('id', 'desc')->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($sale->id, true);

        return view('sales_app.save_and_print_template.gate_pass_print', compact('sale', 'customerCopySaleProducts'));
    }

    public function printSaleWeight($saleId)
    {
        $sale = Sale::with([
            'do:id,do_id,do_date',
            'weight',
            'weight.firstWeightedBy:id,prefix,name,last_name',
            'weight.secondWeightedBy:id,prefix,name,last_name',
        ])->where('id', $saleId)->orderBy('id', 'desc')->first();

        return view('sales_app.save_and_print_template.weight_print', compact('sale'));
    }
}
