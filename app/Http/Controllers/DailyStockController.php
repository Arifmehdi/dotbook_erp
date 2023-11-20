<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\DailyStock;
use App\Models\DailyStockProduct;
use App\Utils\AccountLedgerUtil;
use App\Utils\DailyStockProductUtil;
use App\Utils\DailyStockUtil;
use App\Utils\DayBookUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\ProductStockUtil;
use App\Utils\PurchaseSaleChainUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyStockController extends Controller
{
    protected $dailyStockUtil;

    protected $dailyStockProductUtil;

    protected $invoiceVoucherRefIdUtil;

    protected $productStockUtil;

    protected $purchaseSaleChainUtil;

    protected $accountLedgerUtil;

    protected $dayBookUtil;

    public function __construct(
        DailyStockUtil $dailyStockUtil,
        DailyStockProductUtil $dailyStockProductUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        ProductStockUtil $productStockUtil,
        PurchaseSaleChainUtil $purchaseSaleChainUtil,
        AccountLedgerUtil $accountLedgerUtil,
        DayBookUtil $dayBookUtil,
    ) {

        $this->dailyStockUtil = $dailyStockUtil;
        $this->dailyStockProductUtil = $dailyStockProductUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->productStockUtil = $productStockUtil;
        $this->purchaseSaleChainUtil = $purchaseSaleChainUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
        $this->dayBookUtil = $dayBookUtil;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('daily_stock_index')) {

            abort(403, 'Access denied.');
        }

        if ($request->ajax()) {

            return $this->dailyStockUtil->dailyStockTable($request);
        }

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->get();

        $users = DB::table('users')->where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name')->get();

        return view('inventories.daily_stock.index', compact('warehouses', 'users'));
    }

    public function create()
    {
        if (! auth()->user()->can('daily_stock_create')) {

            abort(403, 'Access denied.');
        }

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $units = DB::table('units')->select('id', 'name', 'code_name')->get();

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        return view('inventories.daily_stock.create', compact('taxAccounts', 'units', 'warehouses'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (! auth()->user()->can('daily_stock_create')) {

            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'date' => 'required|date',
        ]);

        if ($request->warehouse_count) {

            $this->validate($request, [
                'warehouse_id' => 'required',
            ]);
        }

        try {

            DB::beginTransaction();

            $addDailyStock = $this->dailyStockUtil->addDailyStock($request, $codeGenerationService);

            // Add Day Book entry for Daily Stock
            $this->dayBookUtil->addDayBook(voucherTypeId: 13, date: $request->date, accountId: null, transId: $addDailyStock->id, amount: $request->total_stock_value, amountType: 'debit', productId: $request->product_ids[0]);

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $addDailyStockProduct = $this->dailyStockProductUtil->addDailyStockProduct($request, $addDailyStock->id, $index);

                $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'daily_stock_product_id',
                    transId: $addDailyStockProduct->id,
                    productId: $productId,
                    quantity: $request->quantities[$index],
                    variantId: $variantId,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    sellingPrice: 0,
                    subTotal: $request->subtotals[$index],
                    createdAt: date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s'))),
                );

                if ($request->tax_ac_ids[$index]) {

                    // Add Tax A/c ledger Entry For Daily Stock Product
                    $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 20, account_id: $request->tax_ac_ids[$index], date: $request->date, trans_id: $addDailyStockProduct->id, amount: ($request->tax_amounts[$index] * $request->quantity), amount_type: 'debit');
                }

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

            $dailyStock = DailyStock::with([
                'dailyStockProducts',
                'dailyStockProducts.product',
                'dailyStockProducts.variant',
                'dailyStockProducts.dailyStockUnit:id,code_name,base_unit_id,base_unit_multiplier',
                'dailyStockProducts.dailyStockUnit.baseUnit:id,code_name',
                'createdBy:id,prefix,name,last_name',
            ])->where('id', $addDailyStock->id)->first();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 1) {

            return view('inventories.daily_stock.save_and_print_template.print_daily_stock', compact('dailyStock'));
        } else {

            return response()->json(['finalMsg' => 'Daily stock added successfully']);
        }
    }

    public function edit($dailyStockId)
    {
        if (! auth()->user()->can('daily_stock_update')) {

            abort(403, 'Access denied.');
        }

        $dailyStock = DailyStock::with([
            'dailyStockProducts',
            'dailyStockProducts.product',
            'dailyStockProducts.variant',
            'dailyStockProducts.product.unit:id,name,code_name',
            'dailyStockProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'dailyStockProducts.dailyStockUnit:id,name,code_name,base_unit_multiplier',
        ])->where('id', $dailyStockId)->first();

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

        return view('inventories.daily_stock.edit', compact('dailyStock', 'taxAccounts', 'warehouses'));
    }

    public function update(Request $request, $dailyStockId)
    {
        if (! auth()->user()->can('daily_stock_update')) {

            abort(403, 'Access denied.');
        }

        $this->validate($request, ['date' => 'required|date']);

        if ($request->warehouse_count) {

            $this->validate($request, ['warehouse_id' => 'required'], ['warehouse_id.required' => 'Warehouse is required']);
        }

        try {

            DB::beginTransaction();

            $dailyStock = DailyStock::with(['dailyStockProducts'])->where('id', $dailyStockId)->first();

            $storedWarehouseId = $dailyStock->warehouse_id;
            $storeDailyStockProducts = $dailyStock->dailyStockProducts;

            $updateDailyStock = $this->dailyStockUtil->updateDailyStock($dailyStock, $request);

            // Update Day Book entry for Daily Stock
            $this->dayBookUtil->updateDayBook(voucherTypeId: 13, date: $request->date, accountId: null, transId: $updateDailyStock->id, amount: $request->total_stock_value, amountType: 'debit', productId: $request->product_ids[0]);

            foreach ($updateDailyStock->dailyStockProducts as $dailyStockProduct) {

                $dailyStockProduct->is_delete_in_update = 1;
                $dailyStockProduct->save();
            }

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

                $updateDailyStockProduct = $this->dailyStockProductUtil->updateDailyStockProduct($updateDailyStock->id, $request, $index);

                if ($updateDailyStockProduct['addOrEditDailyStockProduct']->tax_ac_id) {

                    // Add Tax A/c ledger
                    $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 20, date: $request->date, account_id: $updateDailyStockProduct['addOrEditDailyStockProduct']->tax_ac_id, trans_id: $updateDailyStockProduct['addOrEditDailyStockProduct']->id, amount: ($updateDailyStockProduct['addOrEditDailyStockProduct']->tax_amount * $updateDailyStockProduct['addOrEditDailyStockProduct']->quantity), amount_type: 'debit', current_account_id: $updateDailyStockProduct['currTaxAcId']);
                } else {

                    $this->accountLedgerUtil->deleteUnusedLedgerEntry(voucherType: 20, transId: $updateDailyStockProduct['addOrEditDailyStockProduct']->id, accountId: $updateDailyStockProduct['currTaxAcId']);
                }

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'daily_stock_product_id',
                    transId: $updateDailyStockProduct['addOrEditDailyStockProduct']->id,
                    productId: $productId,
                    quantity: $request->quantities[$index],
                    variantId: $variantId,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    sellingPrice: 0,
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

            // deleted not getting previous product
            $deletedUnusedDailyStockProducts = DailyStockProduct::where('daily_stock_id', $updateDailyStock->id)
                ->where('is_delete_in_update', 1)
                ->get();

            if (count($deletedUnusedDailyStockProducts) > 0) {

                foreach ($deletedUnusedDailyStockProducts as $deletedDailyStockProduct) {

                    $storedProductId = $deletedDailyStockProduct->product_id;
                    $storedVariantId = $deletedDailyStockProduct->variant_id;
                    $deletedDailyStockProduct->delete();
                    // Adjust deleted product stock
                    $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

                    if (isset($request->warehouse_count)) {

                        $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $request->warehouse_id);
                    } else {

                        $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId);
                    }
                }
            }

            $dailyStockProducts = DB::table('daily_stock_products')->where('daily_stock_id', $updateDailyStock->id)->get();

            foreach ($dailyStockProducts as $dailyStockProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($dailyStockProduct->product_id, $dailyStockProduct->variant_id);

                if (isset($request->warehouse_count)) {

                    $this->productStockUtil->addWarehouseProduct($dailyStockProduct->product_id, $dailyStockProduct->variant_id, $request->warehouse_id);
                    $this->productStockUtil->adjustWarehouseStock($dailyStockProduct->product_id, $dailyStockProduct->variant_id, $request->warehouse_id);
                } else {

                    $this->productStockUtil->addBranchProduct($dailyStockProduct->product_id, $dailyStockProduct->variant_id);
                    $this->productStockUtil->adjustBranchStock($dailyStockProduct->product_id, $dailyStockProduct->variant_id);
                }
            }

            if (isset($request->warehouse_count) && $request->warehouse_id != $storedWarehouseId) {

                foreach ($storeDailyStockProducts as $storeDailyStockProduct) {

                    $this->productStockUtil->adjustWarehouseStock($storeDailyStockProduct->product_id, $storeDailyStockProduct->variant_id, $storedWarehouseId);
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(['successMsg' => 'Daily stock updated successfully']);
    }

    public function show($dailyStockId)
    {
        if (! auth()->user()->can('daily_stock_view')) {

            abort(403, 'Access denied.');
        }

        $dailyStock = DailyStock::with([
            'dailyStockProducts',
            'dailyStockProducts.product',
            'dailyStockProducts.variant',
            'dailyStockProducts.dailyStockUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'dailyStockProducts.dailyStockUnit.baseUnit:id,code_name',
            'createdBy:id,prefix,name,last_name',
        ])->where('id', $dailyStockId)->first();

        return view('inventories.daily_stock.ajax_view.daily_stock_details', compact('dailyStock'));
    }

    public function delete($dailyStockId)
    {
        if (! auth()->user()->can('daily_stock_delete')) {

            abort(403, 'Access denied.');
        }

        $deleteDailyStock = DailyStock::with([
            'dailyStockProducts',
            'dailyStockProducts.product',
            'dailyStockProducts.variant',
            'dailyStockProducts.purchaseSaleChains',
        ])->where('id', $dailyStockId)->first();

        $storedWarehouseId = $deleteDailyStock->warehouse_id;

        $storeDailyStockProducts = $deleteDailyStock->dailyStockProducts;

        foreach ($deleteDailyStock->dailyStockProducts as $dailyStockProduct) {

            if (count($dailyStockProduct->purchaseSaleChains) > 0) {

                $variant = $dailyStockProduct->variant ? ' - '.$dailyStockProduct->variant->name : '';
                $product = $dailyStockProduct->product->name.$variant;

                return response()->json("Can not delete is purchase. Mismatch between sold and purchase/daily-stock/production/opening stock account method. Product: ${product}");
            }
        }

        // Add user Log
        // $this->userActivityLogUtil->addLog(
        //     action: 3,
        //     subject_type: $deletePurchase->purchase_status == 3 ? 5 : 4,
        //     data_obj: $deletePurchase
        // );

        $deleteDailyStock->delete();

        foreach ($storeDailyStockProducts as $dailyStockProduct) {

            $variant_id = $dailyStockProduct->variant_id ? $dailyStockProduct->variant_id : null;

            $this->productStockUtil->adjustMainProductAndVariantStock($dailyStockProduct->product_id, $variant_id);

            if ($storedWarehouseId) {

                $this->productStockUtil->adjustWarehouseStock($dailyStockProduct->product_id, $variant_id, $storedWarehouseId);
            } else {

                $this->productStockUtil->adjustBranchStock($dailyStockProduct->product_id, $variant_id);
            }
        }

        return response()->json('Successfully daily stock is deleted');
    }
}
