<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SaleReturnProduct;
use App\Utils\AccountLedgerUtil;
use App\Utils\DayBookUtil;
use App\Utils\ProductStockUtil;
use App\Utils\PurchaseSaleChainUtil;
use App\Utils\SaleReturnProductUtil;
use App\Utils\SaleReturnUtil;
use App\Utils\SaleUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RandomSaleReturnController extends Controller
{
    protected $saleReturnUtil;

    protected $saleReturnProductUtil;

    protected $productStockUtil;

    protected $saleUtil;

    protected $accountLedgerUtil;

    protected $userActivityLogUtil;

    protected $purchaseSaleChainUtil;

    protected $dayBookUtil;

    public function __construct(
        SaleReturnUtil $saleReturnUtil,
        SaleReturnProductUtil $saleReturnProductUtil,
        ProductStockUtil $productStockUtil,
        SaleUtil $saleUtil,
        AccountLedgerUtil $accountLedgerUtil,
        UserActivityLogUtil $userActivityLogUtil,
        PurchaseSaleChainUtil $purchaseSaleChainUtil,
        DayBookUtil $dayBookUtil,
    ) {

        $this->saleReturnUtil = $saleReturnUtil;
        $this->saleReturnProductUtil = $saleReturnProductUtil;
        $this->productStockUtil = $productStockUtil;
        $this->saleUtil = $saleUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->purchaseSaleChainUtil = $purchaseSaleChainUtil;
        $this->dayBookUtil = $dayBookUtil;
    }

    public function create()
    {
        if (! auth()->user()->can('add_sales_return')) {

            abort(403, 'Access Forbidden.');
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $users = '';
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('sales_app.sale_return.random_return.create', compact('customerAccounts', 'saleAccounts', 'taxAccounts', 'price_groups', 'warehouses', 'users'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        $this->validate($request, [
            'customer_account_id' => 'required',
            'date' => 'required',
            'sale_account_id' => 'required',
        ], [
            'customer_account_id.required' => 'Customer field is required',
            'sale_account_id.required' => 'Sale Return A/c is required',
        ]);

        if (isset($request->user_count)) {

            $this->validate($request, ['user_id' => 'required'], ['user_id.required' => 'Sr is required']);
        }

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['id', 'prefix'])->first();
            $returnVoucherPrefix = json_decode($settings->prefix, true)['sale_return'];

            $sale = Sale::where('id', $request->sale_id)->first();

            $srUserId = $sale ? ($sale->sr_user_id ? $sale->sr_user_id : auth()->user()->id) : $request->user_id;

            $addSaleReturn = $this->saleReturnUtil->addSaleReturn($sale, $request, $srUserId, $codeGenerationService, $returnVoucherPrefix);

            // Add Day Book entry for sales return
            $this->dayBookUtil->addDayBook(voucherTypeId: 3, date: $request->date, accountId: $request->customer_account_id, transId: $addSaleReturn->id, amount: $request->total_return_amount, amountType: 'credit');

            // Add Sales Return ledger
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 2, date: $request->date, account_id: $request->sale_account_id, trans_id: $addSaleReturn->id, amount: $request->total_return_amount, amount_type: 'debit');

            // Add customer ledger Entry
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 2, date: $request->date, account_id: $request->customer_account_id, trans_id: $addSaleReturn->id, amount: $request->total_return_amount, amount_type: 'credit', user_id: $srUserId);

            if ($request->return_tax_ac_id) {

                // Add Tax ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 2, date: $request->date, account_id: $request->return_tax_ac_id, trans_id: $addSaleReturn->id, amount: $request->return_tax_amount, amount_type: 'debit');
            }

            // Add sale return products
            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $addReturnProduct = $this->saleReturnProductUtil->addReturnProduct(saleReturnId: $addSaleReturn->id, request: $request, index: $index);

                if ($addReturnProduct->tax_ac_id) {

                    // Add Tax A/c ledger
                    $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 18, date: $request->date, account_id: $addReturnProduct->tax_ac_id, trans_id: $addReturnProduct->id, amount: ($addReturnProduct->unit_tax_amount * $addReturnProduct->return_qty), amount_type: 'debit');
                }

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'sale_return_product_id',
                    transId: $addReturnProduct->id,
                    productId: $product_id,
                    quantity: $request->return_quantities[$index],
                    variantId: $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    sellingPrice: $request->unit_prices_exc_tax[$index],
                    subTotal: $request->subtotals[$index],
                    createdAt: date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s'))),
                );

                $index++;
            }

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

            if ($sale) {

                $sale->is_return_available = 1;

                $this->saleUtil->adjustSaleInvoiceAmounts($sale);
            }

            $saleReturn = SaleReturn::with([
                'customer',
                'sr:id,prefix,name,last_name',
                'createdBy:id,prefix,name,last_name',
                'warehouse',
                'returnProducts',
                'returnProducts.product',
                'returnProducts.variant',
                'returnProducts.returnUnit:id,code_name,base_unit_id,base_unit_multiplier',
                'returnProducts.returnUnit.baseUnit:id,code_name',
            ])->where('id', $addSaleReturn->id)->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 9, data_obj: $saleReturn);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == '1') {

            return view('sales_app.sale_return.save_and_print_template.sale_return_print_view', compact('saleReturn'));
        } else {

            return response()->json(['successMsg' => 'Sale Return is created successfully.']);
        }
    }

    public function edit($returnId)
    {
        if (! auth()->user()->can('edit_sales_return')) {

            abort(403, 'Access Forbidden.');
        }

        $return = SaleReturn::with(
            [
                'returnProducts',
                'returnProducts.product',
                'returnProducts.product.unit:id,name,code_name',
                'returnProducts.product.unit.childUnits:id,name,base_unit_id,code_name,base_unit_multiplier',
                'returnProducts.returnUnit:id,name,base_unit_multiplier',
                'returnProducts.saleProduct:id,quantity,unit_id',
                'returnProducts.saleProduct.saleUnit:id,name,code_name',
                'returnProducts.variant',
            ]
        )->where('id', $returnId)->first();

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $users = '';
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('sales_app.sale_return.random_return.edit', compact('return', 'customerAccounts', 'saleAccounts', 'price_groups', 'taxAccounts', 'warehouses', 'users'));
    }

    public function update(Request $request, $returnId)
    {
        if (! auth()->user()->can('edit_sales_return')) {

            return response()->json('Access Denied.', 403);
        }

        $this->validate($request, [
            'customer_account_id' => 'required',
            'date' => 'required',
            'sale_account_id' => 'required',
        ], [
            'customer_account_id.required' => 'Customer field is required',
            'sale_account_id.required' => 'Sale A/c is required',
        ]);

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required']);
        }

        if (isset($request->user_count)) {

            $this->validate($request, ['user_id' => 'required'], ['user_id.required' => 'Sr field is required']);
        }

        try {

            DB::beginTransaction();

            $sale = Sale::where('id', $request->sale_id)->first();

            $srUserId = $sale ? ($sale->sr_user ? $sale->sr_user : auth()->user()->id) : $request->user_id;

            $saleReturn = SaleReturn::with(['sale', 'returnProducts'])->where('id', $returnId)->first();

            $storedCurrSalesAccountId = $saleReturn->sale_account_id;
            $storedCurrSrUserId = $saleReturn->sr_user_id;
            $storedCurrCustomerAccountId = $saleReturn->customer_account_id;
            $storedCurrReturnTaxAccountId = $saleReturn->tax_ac_id;
            $storedWarehouseId = $saleReturn->warehouse_id;
            $storeReturnedProducts = $saleReturn->returnProducts;

            foreach ($saleReturn->returnProducts as $returnProduct) {

                $returnProduct->is_delete_in_update = 1;
                $returnProduct->save();
            }

            $updateSaleReturn = $this->saleReturnUtil->updateSaleReturn($saleReturn, $request, $srUserId);

            // Update Day Book entry for sales return
            $this->dayBookUtil->updateDayBook(voucherTypeId: 3, date: $request->date, accountId: $request->customer_account_id, transId: $updateSaleReturn->id, amount: $request->total_return_amount, amountType: 'credit');

            // Update Sale Return A/c ledger
            $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 2, date: $request->date, account_id: $request->sale_account_id, trans_id: $updateSaleReturn->id, amount: $request->total_return_amount, amount_type: 'debit', current_account_id: $storedCurrSalesAccountId);

            // Add customer ledger Entry
            $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 2, date: $request->date, account_id: $request->customer_account_id, trans_id: $updateSaleReturn->id, amount: $request->total_return_amount, amount_type: 'credit', user_id: $storedCurrSrUserId, new_user_id: $srUserId, current_account_id: $storedCurrCustomerAccountId);

            if ($request->return_tax_ac_id) {

                // Add Tax ledger Entry
                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 2, date: $request->date, account_id: $request->return_tax_ac_id, trans_id: $updateSaleReturn->id, amount: $request->return_tax_amount, amount_type: 'debit', current_account_id: $storedCurrReturnTaxAccountId);
            } else {

                $this->accountLedgerUtil->deleteUnusedLedgerEntry(voucherType: 2, transId: $updateSaleReturn->id, accountId: $storedCurrReturnTaxAccountId);
            }

            // update sale return products
            $index = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
                $updateReturnProduct = $this->saleReturnProductUtil->updateReturnProduct($updateSaleReturn->id, $request, $index);

                if ($updateReturnProduct['addOrEditReturnProduct']->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 18, date: $request->date, account_id: $updateReturnProduct['addOrEditReturnProduct']->tax_ac_id, trans_id: $updateReturnProduct['addOrEditReturnProduct']->id, amount: ($updateReturnProduct['addOrEditReturnProduct']->unit_tax_amount * $updateReturnProduct['addOrEditReturnProduct']->return_qty), amount_type: 'debit', current_account_id: $updateReturnProduct['currentTaxAcId']);
                } else {

                    $this->accountLedgerUtil->deleteUnusedLedgerEntry(voucherType: 18, transId: $updateReturnProduct['addOrEditReturnProduct']->id, accountId: $updateReturnProduct['currentTaxAcId']);
                }

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'sale_return_product_id',
                    transId: $updateReturnProduct['addOrEditReturnProduct']->id,
                    productId: $productId,
                    quantity: $request->return_quantities[$index],
                    variantId: $variantId,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    sellingPrice: $request->unit_prices_exc_tax[$index],
                    subTotal: $request->subtotals[$index],
                    createdAt: date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s'))),
                );

                $this->productStockUtil->adjustMainProductAndVariantStock($productId, $variantId);

                if (isset($request->warehouse_count)) {

                    $this->productStockUtil->addWarehouseProduct($productId, $variantId, $request->warehouse_id);
                    $this->productStockUtil->adjustWarehouseStock($productId, $variantId, $request->warehouse_id);
                } else {

                    $this->productStockUtil->addBranchProduct($productId, $variantId);
                    $this->productStockUtil->adjustBranchStock($productId, $variantId);
                }

                $index++;
            }

            $deleteUnusedReturnProducts = SaleReturnProduct::where('sale_return_id', $returnId)->where('is_delete_in_update', 1)->get();

            foreach ($deleteUnusedReturnProducts as $deleteUnusedReturnProduct) {

                $storedProductId = $deleteUnusedReturnProduct->product_id;
                $storedVariantId = $deleteUnusedReturnProduct->product_variant_id;
                $deleteUnusedReturnProduct->delete();

                // Adjust deleted product stock
                $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

                if (isset($request->warehouse_count)) {

                    $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $request->warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId);
                }
            }

            if (isset($request->warehouse_count) && $request->warehouse_id != $storedWarehouseId) {

                foreach ($storeReturnedProducts as $storeReturnedProduct) {

                    $this->productStockUtil->adjustWarehouseStock($storeReturnedProduct->product_id, $storeReturnedProduct->product_variant_id, $storedWarehouseId);
                }
            }

            if ($updateSaleReturn->sale) {

                $this->saleUtil->adjustSaleInvoiceAmounts($updateSaleReturn->sale);
            }

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 9, data_obj: $updateSaleReturn);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Sale Return is updated successfully.');
    }
}
