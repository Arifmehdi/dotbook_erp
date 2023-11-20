<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Jobs\SaleMailJob;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Utils\AccountLedgerUtil;
use App\Utils\CustomerUtil;
use App\Utils\DayBookUtil;
use App\Utils\DeliveryOrderUtil;
use App\Utils\PaymentDescriptionReferenceUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use App\Utils\ProductStockUtil;
use App\Utils\SalesOrderProductUtil;
use App\Utils\SalesOrderUtil;
use App\Utils\SaleUtil;
use App\Utils\SmsUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleOrderController extends Controller
{
    public function __construct(
        private SaleUtil $saleUtil,
        private SalesOrderUtil $salesOrderUtil,
        private SalesOrderProductUtil $salesOrderProductUtil,
        private DeliveryOrderUtil $deliveryOrderUtil,
        private SmsUtil $smsUtil,
        private CustomerUtil $customerUtil,
        private ProductStockUtil $productStockUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private PaymentUtil $paymentUtil,
        private PaymentDescriptionUtil $paymentDescriptionUtil,
        private PaymentDescriptionReferenceUtil $paymentDescriptionReferenceUtil,
        private DayBookUtil $dayBookUtil,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request, $customerAccountId = null, $srUserId = null)
    {
        if (! auth()->user()->can('sale_order_all')) {

            abort(403, 'Access Forbidden.');
        }

        $customerAccountId = $customerAccountId == 'null' ? null : $customerAccountId;

        if ($request->ajax()) {

            return $this->salesOrderUtil->salesOrderTable($request, $customerAccountId, $srUserId);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('sales_app.sales_order.index', compact('customerAccounts', 'saleAccounts', 'users'));
    }

    public function show($orderId)
    {
        $order = Sale::with([
            'customer:id,name,phone',
            'orderBy:id,prefix,name,last_name',
            'sr:id,prefix,name,last_name',
            'salesAccount:id,name',
            'saleProducts',
            'saleProducts.saleUnit:id,code_name,base_unit_id,base_unit_id,base_unit_multiplier',
            'saleProducts.saleUnit.baseUnit:id,code_name',
            'saleProducts.product:id,name,product_code',
            'saleProducts.product.warranty',
            'saleProducts.variant:id,variant_name,variant_code',

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
        ])->where('id', $orderId)->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($orderId);

        return view('sales_app.sales_order.ajax_view.show', compact('order', 'customerCopySaleProducts'));
    }

    public function create()
    {
        if (! auth()->user()->can('sale_order_add')) {

            abort(403, 'Access Forbidden.');
        }

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

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

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $users = '';
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('sales_app.sales_order.create', compact(
            'customerAccounts',
            'methods',
            'accounts',
            'saleAccounts',
            'price_groups',
            'taxAccounts',
            'users'
        ));
    }

    // Add Sale method
    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (! auth()->user()->can('sale_order_all')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'customer_account_id' => 'required',
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
        ], [
            'customer_account_id.required' => 'Customer is required',
            'sale_account_id.required' => 'Sale A/c is required',
        ]);

        if (auth()->user()->is_marketing_user == 0) {

            $this->validate($request, ['user_id' => 'required'], ['user_id.required' => 'User is required']);
        }

        if (isset($request->receive_amount) && $request->receive_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Debit A/c is required']);
        }

        if ($request->expire_date) {

            $this->validate($request, ['expire_time' => 'required']);
        }

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'item table is empty']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['id', 'business', 'prefix', 'send_es_settings'])->first();

            $receiptVoucherPrefix = json_decode($settings->prefix, true)['sale_payment'];

            $srUserId = isset($request->user_count) ? $request->user_id : auth()->user()->id;

            $addOrder = $this->salesOrderUtil->addOrder($request, $srUserId, $codeGenerationService);

            // Add Day Book entry for sales order
            $this->dayBookUtil->addDayBook(voucherTypeId: 2, date: $request->date, accountId: $request->customer_account_id, transId: $addOrder->id, amount: $request->total_invoice_amount, amountType: 'debit');

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $this->salesOrderProductUtil->addSaleOrderProduct(orderId: $addOrder->id, request: $request, index: $index);
                $index++;
            }

            if (isset($request->receive_amount) && $request->receive_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: null, paymentType: 1, voucherGenerator: $codeGenerationService, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->receive_amount, creditTotal: $request->receive_amount, saleRefId: $addOrder->id);

                // Add Payment Description Debit Entry
                $addDebitPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, chequeNo: $request->cheque_no, transactionNo: $request->transaction_no, amountType: 'dr', amount: $request->receive_amount);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->account_id, trans_id: $addDebitPaymentDescription->id, amount: $request->receive_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addCreditPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->customer_account_id, paymentMethodId: $request->payment_method_id, chequeNo: $request->cheque_no, transactionNo: $request->transaction_no, amountType: 'cr', amount: $request->receive_amount, userId: $srUserId);

                // Add Payment Description Reference
                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addCreditPaymentDescription->id, refIdColNames: ['sale_id'], refIds: [$addOrder->id], amounts: [$request->receive_amount]);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->customer_account_id, trans_id: $addCreditPaymentDescription->id, amount: $request->receive_amount, amount_type: 'credit', user_id: $srUserId, cash_bank_account_id: $request->account_id);
            }

            $order = Sale::with([
                'customer',
                'saleProducts',
                'saleProducts.product:id,name,product_code,is_manage_stock',
                'saleProducts.variant:id,variant_name,variant_code',
                'saleProducts.warehouse',
                'orderBy:id,prefix,name,last_name',
                'sr:id,prefix,name,last_name',
            ])->where('id', $addOrder->id)->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: $request->status == 1 ? 7 : 8, data_obj: $order);

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

            $adjustedSale = $this->saleUtil->adjustSaleInvoiceAmounts($order);
            $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($order->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('sales_app.save_and_print_template.print_order', compact('order', 'customerCopySaleProducts'));
        } else {

            return response()->json(['salesOrderMsg' => 'Sales order created successfully']);
        }
    }

    // Sale edit view
    public function edit($saleId)
    {
        if (! auth()->user()->can('sale_order_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get();

        $order = Sale::with([
            'saleProducts',
            'customer',
            'saleProducts.warehouse',
            'saleProducts.product',
            'saleProducts.product.unit:id,name,code_name',
            'saleProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.variant',
            'saleProducts.saleUnit:id,name,base_unit_multiplier',
            'saleProducts.product.comboProducts',
            'saleProducts.product.comboProducts.parentProduct',
            'saleProducts.product.comboProducts.product_variant',
        ])->where('id', $saleId)->first();

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

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
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->get();

        $users = '';
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('sales_app.sales_order.edit', compact('order', 'price_groups', 'saleAccounts', 'taxAccounts', 'methods', 'accounts', 'warehouses', 'users', 'customerAccounts'));
    }

    // Update Sale
    public function update(Request $request, $saleId, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (! auth()->user()->can('sale_order_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
            'customer_account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sale A/c is required',
            'customer_account_id.required' => 'Customer is required',
        ]);

        if (isset($request->user_count)) {

            $this->validate($request, ['user_id' => 'required'], ['user_id.required' => 'User is required']);
        }

        if (isset($request->receive_amount) && $request->receive_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Debit A/c is required']);
        }

        if ($request->expire_date) {

            $this->validate($request, ['expire_time' => 'required']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['business', 'prefix'])->first();
            $receiptVoucherPrefix = json_decode($settings->prefix, true)['sale_payment'];

            if ($request->product_ids == null) {

                return response()->json(['errorMsg' => 'product table is empty']);
            }

            $order = Sale::with(['saleProducts', 'saleProducts.product', 'saleProducts.variant', 'saleProducts.product.comboProducts'])
                ->where('id', $saleId)->first();

            $previousSrUserId = $order->sr_user_id;

            $srUserId = isset($request->user_count) ? $request->user_id : $previousSrUserId;

            if ($request->status == 7 && $order->do_approval == 0) {

                return response()->json(['errorMsg' => 'DO approval is pending. Order can not be change in Delivery Order. ']);
            }

            if ($order->do_status == 1 && $request->status == 3) {

                if ($order->total_delivered_qty > 0) {

                    return response()->json(['errorMsg' => 'Can not change status to ordered. Invoice has been created against the order.']);
                }
            }

            if ($order->status == 3 && $request->status == 7) {

                if ($request->expire_date) {

                    $__date = date('Y-m-d H:i:s', strtotime($request->expire_date.$request->expire_time));

                    if (strtotime(date('Y-m-d H:i:s')) > strtotime($__date)) {

                        return response()->json(['errorMsg' => 'Date expired. Sales order can not create delivery order!']);
                    }
                }
            }

            foreach ($order->saleProducts as $saleProduct) {

                $saleProduct->delete_in_update = 1;
                $saleProduct->save();
            }

            $updateOrder = $this->salesOrderUtil->updateSalesOrder($order, $request, $srUserId);

            // Update Day Book entry for sales order
            $this->dayBookUtil->updateDayBook(voucherTypeId: 2, date: $request->date, accountId: $request->customer_account_id, transId: $updateOrder->id, amount: $request->total_invoice_amount, amountType: 'debit');

            // Update/Add sale product rows
            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $this->salesOrderProductUtil->updateSaleOrderProduct(orderId: $updateOrder->id, request: $request, index: $index);
                $index++;
            }

            if (isset($request->receive_amount) && $request->receive_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: null, paymentType: 1, voucherGenerator: $codeGenerationService, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->receive_amount, creditTotal: $request->receive_amount, saleRefId: $updateOrder->id);

                // Add Payment Description Debit Entry
                $addDebitPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, chequeNo: $request->cheque_no, transactionNo: $request->transaction_no, amountType: 'dr', amount: $request->receive_amount);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->account_id, trans_id: $addDebitPaymentDescription->id, amount: $request->receive_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addCreditPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->customer_account_id, paymentMethodId: $request->payment_method_id, chequeNo: $request->cheque_no, transactionNo: $request->transaction_no, amountType: 'cr', amount: $request->receive_amount, userId: $srUserId);

                // Add Payment Description Reference
                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addCreditPaymentDescription->id, refIdColNames: ['sale_id'], refIds: [$updateOrder->id], amounts: [$request->receive_amount]);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->customer_account_id, trans_id: $addCreditPaymentDescription->id, amount: $request->receive_amount, amount_type: 'credit', user_id: $srUserId, cash_bank_account_id: $request->account_id);
            }

            $deleteNotFoundSaleProducts = SaleProduct::with('purchaseSaleProductChains', 'purchaseSaleProductChains.purchaseProduct')
                ->where('sale_id', $updateOrder->id)
                ->where('delete_in_update', 1)->get();

            foreach ($deleteNotFoundSaleProducts as $deleteNotFoundSaleProduct) {

                $deleteNotFoundSaleProduct->delete();
            }

            if ($request->status == 7) {

                $this->deliveryOrderUtil->calculateDoLeftQty($updateOrder);
            }

            $this->userActivityLogUtil->addLog(action: 2, subject_type: $request->status == 1 ? 7 : 8, data_obj: $updateOrder);

            $adjustedSale = $this->saleUtil->adjustSaleInvoiceAmounts($updateOrder);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', 'Sales order updated successfully');

        return response()->json(['successMsg' => 'Sales order updated successfully']);
    }

    public function doApproval($saleId)
    {
        $sale = DB::table('sales')->where('id', $saleId)->select('id', 'do_approval')->first();

        return view('sales_app.sales_order.ajax_view.do_approval_modal', compact('sale'));
    }

    public function doApprovalUpdate(Request $request, $saleId)
    {
        $sale = Sale::where('id', $saleId)->first();
        $sale->do_approval = $request->do_approval;
        $sale->save();

        return response()->json('Successfully DO approval status has been changed');
    }

    public function orderStatusChangeModal($saleId)
    {
        $order = DB::table('sales')->where('id', $saleId)->select('id', 'status', 'do_status')->first();

        return view('sales_app.sales_order.ajax_view.change_order_status_modal', compact('order'));
    }

    public function orderStatusChange(Request $request, $saleId)
    {
        $updateOrder = Sale::with('saleProducts')->where('id', $saleId)->first();

        if ($updateOrder->do_status == 1 && $request->status == 3) {

            if ($updateOrder->total_delivered_qty > 0) {

                return response()->json(['errorMsg' => 'Can not change status to ordered. Invoice has been created against the order.']);
            }
        }

        try {

            DB::beginTransaction();

            if ($request->status == 7) {

                $updateOrder->do_id = $updateOrder->order_id;
                $updateOrder->do_status = 1;
                $updateOrder->do_by_id = $updateOrder->do_by_id ? $updateOrder->do_by_id : auth()->user()->id;
                $updateOrder->do_date = $updateOrder->order_date;
                $updateOrder->total_do_qty = $updateOrder->total_ordered_qty;
                $updateOrder->save();

                $this->deliveryOrderUtil->calculateDoLeftQty($updateOrder);
            } else {

                $updateOrder->do_id = null;
                $updateOrder->do_status = 0;
                $updateOrder->do_by_id = null;
                $updateOrder->do_date = null;
                $updateOrder->total_do_qty = 0;
                $updateOrder->save();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Successfully order status is updated.');
    }
}
