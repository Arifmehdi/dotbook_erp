<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Utils\AccountLedgerUtil;
use App\Utils\DayBookUtil;
use App\Utils\PaymentDescriptionReferenceUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use App\Utils\ProductStockUtil;
use App\Utils\StockAdjustmentProductUtil;
use App\Utils\StockAdjustmentUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    protected $stockAdjustmentUtil;

    protected $stockAdjustmentProductUtil;

    protected $productStockUtil;

    protected $accountLedgerUtil;

    protected $paymentUtil;

    protected $paymentDescriptionUtil;

    protected $paymentDescriptionReferenceUtil;

    protected $userActivityLogUtil;

    protected $dayBookUtil;

    public function __construct(
        StockAdjustmentUtil $stockAdjustmentUtil,
        StockAdjustmentProductUtil $stockAdjustmentProductUtil,
        ProductStockUtil $productStockUtil,
        AccountLedgerUtil $accountLedgerUtil,
        PaymentUtil $paymentUtil,
        PaymentDescriptionUtil $paymentDescriptionUtil,
        PaymentDescriptionReferenceUtil $paymentDescriptionReferenceUtil,
        UserActivityLogUtil $userActivityLogUtil,
        DayBookUtil $dayBookUtil,
    ) {

        $this->stockAdjustmentUtil = $stockAdjustmentUtil;
        $this->stockAdjustmentProductUtil = $stockAdjustmentProductUtil;
        $this->productStockUtil = $productStockUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
        $this->paymentUtil = $paymentUtil;
        $this->paymentDescriptionUtil = $paymentDescriptionUtil;
        $this->paymentDescriptionReferenceUtil = $paymentDescriptionReferenceUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->dayBookUtil = $dayBookUtil;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('stock_adjustments_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->stockAdjustmentUtil->stockAdjustmentList($request);
        }

        return view('inventories.stock_adjustment.index');
    }

    public function show($adjustmentId)
    {
        $adjustment = StockAdjustment::with(
            'warehouse',
            'adjustmentProducts',
            'adjustmentProducts.product',
            'adjustmentProducts.variant',
            'adjustmentProducts.warehouse:id,warehouse_name,warehouse_code',
            'adjustmentProducts.stockAdjustmentUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'adjustmentProducts.stockAdjustmentUnit.baseUnit:id,code_name',
            'createdBy:id,prefix,name,last_name',
            'references:id,stock_adjustment_id,amount,payment_description_id',
            'references.paymentDescription:id,payment_id,payment_id,account_id,payment_method_id',
            'references.paymentDescription.account:id,name',
            'references.paymentDescription.paymentMethod:id,name',
            'references.paymentDescription.payment:id,voucher_no,date,payment_type',
        )->where('id', $adjustmentId)->first();

        return view('inventories.stock_adjustment.ajax_view.show', compact('adjustment'));
    }

    public function create()
    {
        if (! auth()->user()->can('stock_adjustments_add')) {

            abort(403, 'Access Forbidden.');
        }

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $expenseAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name']);

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

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('inventories.stock_adjustment.create', compact('warehouses', 'expenseAccounts', 'accounts', 'methods'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (! auth()->user()->can('stock_adjustments_add')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required',
            'type' => 'required',
            'expense_account_id' => 'required',
        ], [
            'expense_account_id.required' => 'Expense Ledger is required.',
        ]);

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required'], ['warehouse_id.required' => 'Warehouse is required.']);
        }

        if (isset($request->recovered_amount) && $request->recovered_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Debit A/c is required.']);
        }

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'item table is empty.']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['prefix'])->first();
            $StockAdjustmentVoucherPrefix = json_decode($settings->prefix, true)['stock_djustment'];
            $receiptVoucherPrefix = json_decode($settings->prefix, true)['sale_payment'];
            $__receiptVoucherPrefix = $receiptVoucherPrefix != null ? $receiptVoucherPrefix : 'RV';

            $addStockAdjustment = $this->stockAdjustmentUtil->addStockAdjustment($request, $codeGenerationService, $StockAdjustmentVoucherPrefix);

            // Add Day Book entry for sales
            $this->dayBookUtil->addDayBook(voucherTypeId: 8, date: $request->date, accountId: $request->expense_account_id, transId: $addStockAdjustment->id, amount: $request->net_total_amount, amountType: 'debit');

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 13, data_obj: $addStockAdjustment);

            // Add Expense A/c Ledger
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 7, date: $request->date, account_id: $request->expense_account_id, trans_id: $addStockAdjustment->id, amount: $request->net_total_amount, amount_type: 'debit');

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

                $addStockAdjustmentProduct = $this->stockAdjustmentProductUtil->addStockAdjustmentProduct($addStockAdjustment->id, $request, $index);

                $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);

                if ($request->warehouse_ids[$index]) {

                    $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $request->warehouse_ids[$index]);
                } else {

                    $this->productStockUtil->adjustBranchStock($product_id, $variant_id);
                }

                $index++;
            }

            if (isset($request->recovered_amount) && $request->recovered_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: null, paymentType: 1, voucherGenerator: $codeGenerationService, voucherPrefix: $__receiptVoucherPrefix, debitTotal: $request->recovered_amount, creditTotal: $request->recovered_amount, stockAdjustmentRefId: $addStockAdjustment->id);

                // Add Payment Description Debit Entry
                $addDebitPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->recovered_amount);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->account_id, trans_id: $addDebitPaymentDescription->id, amount: $request->recovered_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addCreditPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->expense_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->recovered_amount);

                // Add Payment Description Reference
                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addCreditPaymentDescription->id, refIdColNames: ['stock_adjustment_id'], refIds: [$addStockAdjustment->id], amounts: [$request->recovered_amount]);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->expense_account_id, trans_id: $addCreditPaymentDescription->id, amount: $request->recovered_amount, amount_type: 'credit');
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', 'Stock adjustment created successfully');

        return response()->json('Stock adjustment created successfully');
    }

    public function delete($adjustmentId)
    {
        if (! auth()->user()->can('stock_adjustments_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $deleteAdjustment = StockAdjustment::with([
                'adjustmentProducts',
                'adjustmentProducts.product',
                'adjustmentProducts.variant',
            ])->where('id', $adjustmentId)->first();

            if (! is_null($deleteAdjustment)) {

                $storedAdjustmentProducts = $deleteAdjustment->adjustmentProducts;

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 13, data_obj: $deleteAdjustment);

                $deleteAdjustment->delete();

                foreach ($storedAdjustmentProducts as $adjustmentProduct) {

                    // Update product qty for adjustment
                    $this->productStockUtil->adjustMainProductAndVariantStock($adjustmentProduct->product_id, $adjustmentProduct->product_variant_id);

                    if ($adjustmentProduct->warehouse_id) {

                        $this->productStockUtil->adjustWarehouseStock($adjustmentProduct->product_id, $adjustmentProduct->product_variant_id, $adjustmentProduct->warehouse_id);
                    } else {

                        $this->productStockUtil->adjustBranchStock($adjustmentProduct->product_id, $adjustmentProduct->product_variant_id);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Stock adjustment deleted successfully.');
    }
}
