<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Purchase;
use App\Models\ReceiveStock;
use App\Utils\Converter;
use App\Utils\DayBookUtil;
use App\Utils\ProductStockUtil;
use App\Utils\PurchaseOrderProductUtil;
use App\Utils\PurchaseOrderUtil;
use App\Utils\ReceiveStockProductUtil;
use App\Utils\ReceiveStockUtil;
use App\Utils\RequisitionUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiveStockController extends Controller
{
    public function __construct(
        private UserActivityLogUtil $userActivityLogUtil,
        private RequisitionUtil $requisitionUtil,
        private ReceiveStockUtil $receiveStockUtil,
        private ReceiveStockProductUtil $receiveStockProductUtil,
        private ProductStockUtil $productStockUtil,
        private PurchaseOrderUtil $purchaseOrderUtil,
        private PurchaseOrderProductUtil $purchaseOrderProductUtil,
        private DayBookUtil $dayBookUtil,
    ) {
    }

    public function index(Request $request, Converter $converter)
    {
        if (! auth()->user()->can('receive_stocks_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->receiveStockUtil->receiveListTable($request, $converter);
        }

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('procurement.receive_stock.index', compact('supplierAccounts'));
    }

    public function show($id)
    {
        if (! auth()->user()->can('receive_stocks_view')) {

            abort(403, 'Access Forbidden.');
        }

        $receiveStock = ReceiveStock::with([
            'requisition:id,requisition_no,department_id',
            'requisition.department:id,name',
            'purchaseOrder:id,invoice_id',
            'warehouse:id,warehouse_name,warehouse_code',
            'supplier',
            'createdBy:id,prefix,name,last_name',
            'receiveStockProducts',
            'receiveStockProducts.product',
            'receiveStockProducts.variant',
            'receiveStockProducts.receiveUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'receiveStockProducts.receiveUnit.baseUnit:id,base_unit_id,code_name',
        ])->where('id', $id)->first();

        return view('procurement.receive_stock.ajax_view.show', compact('receiveStock'));
    }

    public function create()
    {
        if (! auth()->user()->can('receive_stocks_create')) {

            abort(403, 'Access Forbidden.');
        }

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        return view('procurement.receive_stock.create', compact('warehouses', 'supplierAccounts'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (! auth()->user()->can('receive_stocks_create')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate(
            $request,
            [
                'supplier_account_id' => 'required',
                'date' => 'required|date',
            ],
            ['supplier_account_id.required' => 'Supplier is required']
        );

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required'], ['warehouse_id.required' => 'Warehouse field is required.']);
        }

        if (! isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Item table is empty.']);
        } elseif (count($request->product_ids) > 60) {

            return response()->json(['errorMsg' => 'Receive Stock items must be less than 60 or equal.']);
        }

        try {

            DB::beginTransaction();

            $addReceiveStock = $this->receiveStockUtil->receiveStockStore($request, $codeGenerationService);

            // Add Day Book entry for Receive Stock
            $this->dayBookUtil->addDayBook(voucherTypeId: 14, date: $request->date, accountId: $request->supplier_account_id, transId: $addReceiveStock->id, amount: $request->total_qty, amountType: 'credit');

            $this->receiveStockProductUtil->addReceiveStockProducts($request, $addReceiveStock->id);

            if ($request->requisition_id) {

                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($request->requisition_id);
                $this->requisitionUtil->updateRequisitionLeftQty($request->requisition_id);
            }

            if ($request->purchase_order_id) {

                $purchaseOrder = Purchase::where('id', $request->purchase_order_id)->first();
                $this->purchaseOrderProductUtil->adjustPurchaseOrderProductPendingQty($purchaseOrder->id);
                $this->purchaseOrderUtil->updatePoQtyAndStatusPortion($purchaseOrder);
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

            $receiveStock = ReceiveStock::with([
                'requisition:id,requisition_no,department_id',
                'requisition.department:id,name',
                'warehouse:id,warehouse_name,warehouse_code',
                'purchaseOrder:id,invoice_id',
                'supplier:id,name,phone,address',
                'createdBy:id,prefix,name,last_name',
                'receiveStockProducts',
                'receiveStockProducts.product',
                'receiveStockProducts.product.unit:id,code_name',
                'receiveStockProducts.variant',
                'receiveStockProducts.receiveUnit:id,code_name,base_unit_id,base_unit_multiplier',
                'receiveStockProducts.receiveUnit.baseUnit:id,base_unit_id,code_name',
            ])->where('id', $addReceiveStock->id)->first();

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 36, data_obj: $receiveStock);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => 'Successfully stock is Received.']);
        } else {

            return view('procurement.save_and_print_template.print_receive_stock', compact('receiveStock'));
        }
    }

    public function edit($id)
    {
        if (! auth()->user()->can('receive_stocks_update')) {

            abort(403, 'Access Forbidden.');
        }

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $supplierAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $receiveStock = ReceiveStock::with([
            'purchase',
            'purchaseOrder:id,invoice_id',
            'requisition:id,requisition_no',
            'receiveStockProducts',
            'receiveStockProducts.product',
            'receiveStockProducts.variant',
            'receiveStockProducts.receiveUnit:id,name,base_unit_id,base_unit_multiplier',
            'receiveStockProducts.receiveUnit.baseUnit:id,base_unit_id,name',
        ])->where('id', $id)->first();

        if ($receiveStock->purchase) {

            session()->flash('errorMsg', 'Receive stock voucher can\'t be edited, Associated with purchase');

            return redirect()->back();
        }

        return view('procurement.receive_stock.edit', compact('warehouses', 'supplierAccounts', 'receiveStock'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('receive_stocks_update')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate(
            $request,
            [
                'supplier_account_id' => 'required',
                'date' => 'required|date',
            ],
            ['supplier_account_id.required' => 'Supplier is required']
        );

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required'], ['warehouse_id.required' => 'Warehouse field is required.']);
        }

        if (! isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Item table is empty.']);
        } elseif (count($request->product_ids) > 60) {

            return response()->json(['errorMsg' => 'Receive Stock items must be less than 60 or equal.']);
        }

        try {

            DB::beginTransaction();

            $receiveStock = ReceiveStock::with(['receiveStockProducts'])->where('id', $id)->first();

            $storedCurrRequisitionId = $receiveStock->requisition_id;
            $storedCurrPurchaseOrderId = $receiveStock->purchase_order_id;
            $storedWarehouseId = $receiveStock->warehouse_id;
            $storeReceiveStockProducts = $receiveStock->receiveStockProducts;

            $updateReceiveStock = $this->receiveStockUtil->receiveStockUpdate($request, $receiveStock);

            // Add Day Book entry for Receive Stock
            $this->dayBookUtil->updateDayBook(voucherTypeId: 14, date: $request->date, accountId: $request->supplier_account_id, transId: $updateReceiveStock->id, amount: $request->total_qty, amountType: 'credit');

            $this->receiveStockProductUtil->updateReceiveStockProducts($request, $receiveStock, $this->productStockUtil);

            if ($request->requisition_id) {

                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($request->requisition_id);
                $this->requisitionUtil->updateRequisitionLeftQty($request->requisition_id);
            }

            if ($storedCurrRequisitionId && ($storedCurrRequisitionId != $request->requisition_id)) {

                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($storedCurrRequisitionId);
                $this->requisitionUtil->updateRequisitionLeftQty($storedCurrRequisitionId);
            }

            if ($request->purchase_order_id) {

                $purchaseOrder = Purchase::where('id', $request->purchase_order_id)->first();
                $this->purchaseOrderProductUtil->adjustPurchaseOrderProductPendingQty($purchaseOrder->id);
                $this->purchaseOrderUtil->updatePoQtyAndStatusPortion($purchaseOrder);
            }

            if ($storedCurrPurchaseOrderId && ($storedCurrPurchaseOrderId != $request->purchase_order_id)) {

                $currentPurchaseOrder = Purchase::where('id', $storedCurrPurchaseOrderId)->first();
                $this->purchaseOrderProductUtil->adjustPurchaseOrderProductPendingQty($currentPurchaseOrder->id);
                $this->purchaseOrderUtil->updatePoQtyAndStatusPortion($currentPurchaseOrder);
            }

            $receiveStockProducts = DB::table('receive_stock_products')->where('receive_stock_id', $receiveStock->id)->get();

            foreach ($receiveStockProducts as $receiveStockProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($receiveStockProduct->product_id, $receiveStockProduct->variant_id);

                if (isset($request->warehouse_count)) {

                    $this->productStockUtil->addWarehouseProduct($receiveStockProduct->product_id, $receiveStockProduct->variant_id, $request->warehouse_id);
                    $this->productStockUtil->adjustWarehouseStock($receiveStockProduct->product_id, $receiveStockProduct->variant_id, $request->warehouse_id);
                } else {

                    $this->productStockUtil->addBranchProduct($receiveStockProduct->product_id, $receiveStockProduct->variant_id);
                    $this->productStockUtil->adjustBranchStock($receiveStockProduct->product_id, $receiveStockProduct->variant_id);
                }
            }

            if (isset($request->warehouse_count) && $request->warehouse_id != $storedWarehouseId) {

                foreach ($storeReceiveStockProducts as $storeReceiveStockProduct) {

                    $this->productStockUtil->adjustWarehouseStock($storeReceiveStockProduct->product_id, $storeReceiveStockProduct->variant_id, $storedWarehouseId);
                }
            }

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 2, subject_type: 36, data_obj: $receiveStock);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Successfully Received stock is updated.');
    }

    public function delete(Request $request, $id)
    {
        if (! auth()->user()->can('receive_stocks_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $delete = $this->receiveStockUtil->deleteReceiveStock($id, $this->requisitionUtil, $this->productStockUtil, $this->userActivityLogUtil);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        DB::statement('ALTER TABLE receive_stocks AUTO_INCREMENT = 1');

        // return response()->json('Successfully received stock is deleted');
        return $delete;
    }
}
