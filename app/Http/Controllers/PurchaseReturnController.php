<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnProduct;
use App\Utils\AccountLedgerUtil;
use App\Utils\DayBookUtil;
use App\Utils\ProductStockUtil;
use App\Utils\PurchaseReturnProductUtil;
use App\Utils\PurchaseReturnUtil;
use App\Utils\PurchaseUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    protected $purchaseReturnUtil;

    protected $purchaseReturnProductUtil;

    protected $productStockUtil;

    protected $purchaseUtil;

    protected $accountLedgerUtil;

    protected $userActivityLogUtil;

    protected $dayBookUtil;

    public function __construct(
        PurchaseReturnUtil $purchaseReturnUtil,
        PurchaseReturnProductUtil $purchaseReturnProductUtil,
        ProductStockUtil $productStockUtil,
        PurchaseUtil $purchaseUtil,
        AccountLedgerUtil $accountLedgerUtil,
        UserActivityLogUtil $userActivityLogUtil,
        DayBookUtil $dayBookUtil,
    ) {
        $this->purchaseReturnUtil = $purchaseReturnUtil;
        $this->purchaseReturnProductUtil = $purchaseReturnProductUtil;
        $this->productStockUtil = $productStockUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->dayBookUtil = $dayBookUtil;
    }

    // Sale return index view
    public function index(Request $request)
    {
        if (! auth()->user()->can('view_purchase_return')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseReturnUtil->returnList($request);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('procurement.purchase_return.index', compact('supplierAccounts'));
    }

    // Show purchase return details
    public function show($returnId)
    {
        $return = PurchaseReturn::with([
            'purchase',
            'supplier:id,name,phone,address',
            'returnProducts',
            'returnProducts.product:id,name,product_code',
            'returnProducts.variant:id,variant_name,variant_code',
            'returnProducts.warehouse:id,warehouse_name,warehouse_code',
            'returnProducts.returnUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'returnProducts.returnUnit.baseUnit:id,base_unit_id,code_name',
        ])->where('id', $returnId)->first();

        return view('procurement.purchase_return.ajax_view.show', compact('return'));
    }

    public function create()
    {
        if (! auth()->user()->can('add_purchase_return')) {

            abort(403, 'Access Forbidden.');
        }

        $units = DB::table('units')->select('id', 'name', 'code_name')->get();

        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')->get();

        $purchaseAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->whereIn('account_groups.sub_group_number', [12])
            ->select('accounts.id', 'accounts.name')->get();

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        return view('procurement.purchase_return.create', compact('warehouses', 'supplierAccounts', 'purchaseAccounts', 'taxAccounts', 'units'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'date' => 'required',
            'purchase_account_id' => 'required',
        ], [
            'supplier_account_id.required' => 'Supplier field is required',
            'purchase_account_id.required' => 'Purchase Ledger is required',
        ]);

        if (! isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Product return table is empty']);
        }

        if ($request->total_qty == 0) {

            return response()->json(['errorMsg' => 'Total Returned Quantity must not be 0']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['id', 'prefix'])->first();
            $voucherPrefix = json_decode($settings->prefix, true)['purchase_return'];

            $addPurchaseReturn = $this->purchaseReturnUtil->addPurchaseReturn($request, $codeGenerationService, $voucherPrefix);

            // Add Day Book entry for Purchase Return
            $this->dayBookUtil->addDayBook(voucherTypeId: 6, date: $request->date, accountId: $request->supplier_account_id, transId: $addPurchaseReturn->id, amount: $request->total_return_amount, amountType: 'debit');

            // Add supplier account ledger entry
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 4, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addPurchaseReturn->id, amount: $request->total_return_amount, amount_type: 'debit');

            // Add purchase account ledger entry
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 4, date: $request->date, account_id: $request->purchase_account_id, trans_id: $addPurchaseReturn->id, amount: $request->purchase_ledger_amount, amount_type: 'credit');

            if ($request->return_tax_ac_id) {

                // Add tax A/c ledger entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 4, date: $request->date, account_id: $request->return_tax_ac_id, trans_id: $addPurchaseReturn->id, amount: $request->return_tax_amount, amount_type: 'credit');
            }

            // Add purchase return product
            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $addPurchaseReturnProduct = $this->purchaseReturnProductUtil->addPurchaseReturnProduct(purchaseReturnId: $addPurchaseReturn->id, request: $request, index: $index);

                if ($addPurchaseReturnProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 19, date: $request->date, account_id: $addPurchaseReturnProduct->tax_ac_id, trans_id: $addPurchaseReturnProduct->id, amount: ($addPurchaseReturnProduct->unit_tax_amount * $addPurchaseReturnProduct->return_qty), amount_type: 'credit');
                }

                $index++;
            }

            $__index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;

                $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);

                if ($request->warehouse_ids[$__index]) {

                    $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $request->warehouse_ids[$__index]);
                } else {

                    $this->productStockUtil->adjustBranchStock($product_id, $variant_id);
                }

                $__index++;
            }

            $return = PurchaseReturn::with([
                'supplier',
                'purchase',
                'returnProducts',
                'returnProducts.product:id,name,product_code',
                'returnProducts.variant:id,variant_name,variant_code',
                'returnProducts.returnUnit:id,code_name,base_unit_id,base_unit_multiplier',
                'returnProducts.returnUnit.baseUnit:id,base_unit_id,code_name',
            ])->where('id', $addPurchaseReturn->id)->first();

            if ($return->purchase) {

                $this->purchaseUtil->adjustPurchaseInvoiceAmounts($return->purchase);
            }

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 6, data_obj: $return);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 1) {

            return view('procurement.save_and_print_template.print_purchase_return', compact('return'));
        } else {

            return response()->json(['successMsg' => 'Successfully purchase return is added.']);
        }
    }

    // Edit supplier return view
    public function edit($purchaseReturnId)
    {
        if (! auth()->user()->can('edit_purchase_return')) {

            abort(403, 'Access Forbidden.');
        }

        $return = PurchaseReturn::with([
            'purchase',
            'returnProducts',
            'returnProducts.product:id,name,product_code,unit_id',
            'returnProducts.variant:id,variant_name,variant_code',
            'returnProducts.warehouse:id,warehouse_name,warehouse_code',
            'returnProducts.returnUnit:id,name,code_name,base_unit_multiplier',
            'returnProducts.product.unit:id,name,code_name',
            'returnProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'returnProducts.purchaseProduct:id,quantity,unit_id',
            'returnProducts.purchaseProduct.purchaseUnit:id,name,base_unit_multiplier',
        ])->where('id', $purchaseReturnId)->first();

        $purchaseAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->whereIn('account_groups.sub_group_number', [12])
            ->select('accounts.id', 'accounts.name')->get();

        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')->get();

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view(
            'procurement.purchase_return.edit',
            compact(
                'return',
                'supplierAccounts',
                'warehouses',
                'purchaseAccounts',
                'taxAccounts',
            )
        );
    }

    public function update(Request $request, $purchaseReturnId)
    {
        $this->validate($request, [
            'date' => 'required',
            'purchase_account_id' => 'required',
        ], [
            'supplier_id.required' => 'Supplier is required',
            'purchase_account_id.required' => 'Purchase A/c is required',
        ]);

        if (! isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Product return table is empty']);
        }

        if ($request->total_qty == 0) {

            return response()->json(['errorMsg' => 'Total Returned Quantity must not be 0']);
        }

        try {

            DB::beginTransaction();

            $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();

            $purchaseReturn = PurchaseReturn::with('purchase', 'returnProducts')->where('id', $purchaseReturnId)->first();

            $storedCurrPurchase = $purchaseReturn->purchase;
            $storedCurrPurchaseAccountId = $purchaseReturn->purchase_account_id;
            $storedCurrSupplierAccountId = $purchaseReturn->supplier_account_id;
            $storedCurrPurchaseReturnTaxAccountId = $purchaseReturn->tax_ac_id;
            $storedReturnProducts = $purchaseReturn->returnProducts;

            foreach ($purchaseReturn->returnProducts as $returnProduct) {

                $returnProduct->is_delete_in_update = 1;
                $returnProduct->save();
            }

            $updatePurchaseReturn = $this->purchaseReturnUtil->updatePurchaseReturn($purchaseReturn, $request);

            // Add Day Book entry for Purchase Return
            $this->dayBookUtil->updateDayBook(voucherTypeId: 6, date: $request->date, accountId: $request->supplier_account_id, transId: $updatePurchaseReturn->id, amount: $request->total_return_amount, amountType: 'debit');

            // Update Supplier A/c Ledger Entry
            $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 4, date: $request->date, account_id: $request->supplier_account_id, trans_id: $updatePurchaseReturn->id, amount: $request->total_return_amount, amount_type: 'debit', current_account_id: $storedCurrSupplierAccountId);

            // Update Purchase A/c Ledger Entry
            $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 4, date: $request->date, account_id: $request->purchase_account_id, trans_id: $updatePurchaseReturn->id, amount: $request->purchase_ledger_amount, amount_type: 'credit', current_account_id: $storedCurrPurchaseAccountId);

            if ($request->purchase_tax_ac_id) {

                // Update Tax A/c ledger Entry
                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 4, date: $request->date, account_id: $updatePurchaseReturn->tax_ac_id, trans_id: $updatePurchase->id, amount: $request->return_tax_amount, amount_type: 'credit', current_account_id: $storedCurrPurchaseReturnTaxAccountId);
            } else {

                $this->accountLedgerUtil->deleteUnusedLedgerEntry(voucherType: 4, transId: $updatePurchaseReturn->id, accountId: $storedCurrPurchaseReturnTaxAccountId);
            }

            // Update Purchase Return Product
            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $updatePurchaseReturnProduct = $this->purchaseReturnProductUtil->updatePurchaseReturnProduct(purchaseReturnId: $updatePurchaseReturn->id, request: $request, index: $index);

                if ($updatePurchaseReturnProduct['addOrUpdatePurchaseReturnProduct']->tax_ac_id) {

                    // Add Tax A/c ledger
                    $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 19, date: $request->date, account_id: $updatePurchaseReturnProduct['addOrUpdatePurchaseReturnProduct']->tax_ac_id, trans_id: $updatePurchaseReturnProduct['addOrUpdatePurchaseReturnProduct']->id, amount: ($updatePurchaseReturnProduct['addOrUpdatePurchaseReturnProduct']->unit_tax_amount * $updatePurchaseReturnProduct['addOrUpdatePurchaseReturnProduct']->return_qty), amount_type: 'debit', current_account_id: $updatePurchaseReturnProduct['currentUnitTaxAcId']);
                } else {

                    $this->accountLedgerUtil->deleteUnusedLedgerEntry(voucherType: 19, transId: $updatePurchaseReturnProduct['addOrUpdatePurchaseReturnProduct']->id, accountId: $updatePurchaseReturnProduct['currentUnitTaxAcId']);
                }
                $index++;
            }

            // delete not found previous products
            $purchaseReturnProducts = PurchaseReturnProduct::where('is_delete_in_update', 1)->get();

            if (count($purchaseReturnProducts) > 0) {

                foreach ($purchaseReturnProducts as $returnProduct) {

                    $storedProductId = $returnProduct->product_id;
                    $storedVariantId = $returnProduct->product_variant_id;
                    $returnProduct->delete();

                    $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

                    if ($returnProduct->warehouse_id) {

                        $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $returnProduct->warehouse_id);
                    } else {

                        $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId);
                    }
                }
            }

            $returnProducts = DB::table('purchase_return_products')->where('purchase_return_id', $updatePurchaseReturn->id)->get();

            foreach ($returnProducts as $returnProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($returnProduct->product_id, $returnProduct->product_variant_id);

                if ($returnProduct->warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($returnProduct->product_id, $returnProduct->product_variant_id, $returnProduct->warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($returnProduct->product_id, $returnProduct->product_variant_id);
                }
            }

            foreach ($storedReturnProducts as $returnProduct) {

                if ($returnProduct->warehouse_id) {

                    $check = DB::table('purchase_return_products')
                        ->where('id', $returnProduct->id)
                        ->where('warehouse_id', $returnProduct->warehouse_id)
                        ->where('product_id', $returnProduct->product_id)
                        ->where('product_variant_id', $returnProduct->product_variant_id)->first();

                    if (! $check) {

                        $this->productStockUtil->adjustWarehouseStock($returnProduct->product_id, $returnProduct->product_variant_id, $returnProduct->warehouse_id);
                    }
                }
            }

            if ($request->purchase_id) {

                $purchase = Purchase::where('id', $request->purchase_id)->first();
                $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
            }

            if (isset($storedCurrPurchase) && $storedCurrPurchase->id != $request->purchase_id) {

                $this->purchaseUtil->adjustPurchaseInvoiceAmounts($storedCurrPurchase);
            }

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 6, data_obj: $purchaseReturn);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', 'Purchase return updated successfully.');

        return response()->json('Purchase return updated successfully.');
    }

    //Deleted purchase return
    public function delete($purchaseReturnId)
    {
        if (! auth()->user()->can('delete_purchase_return')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();
            $purchaseReturn = PurchaseReturn::with(['purchase', 'returnProducts'])->where('id', $purchaseReturnId)->first();
            $storeReturnProducts = $purchaseReturn->returnProducts;
            $storePurchase = $purchaseReturn->purchase;

            $purchaseReturn->delete();

            foreach ($storeReturnProducts as $returnProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($returnProduct->product_id, $returnProduct->product_variant_id);

                if ($returnProduct->warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($returnProduct->product_id, $returnProduct->product_variant_id, $returnProduct->warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($returnProduct->product_id, $returnProduct->product_variant_id);
                }
            }

            if ($storePurchase) {

                $this->purchaseUtil->adjustPurchaseInvoiceAmounts($storePurchase);
            }

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 6, data_obj: $purchaseReturn);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Successfully purchase return is deleted');
    }
}
