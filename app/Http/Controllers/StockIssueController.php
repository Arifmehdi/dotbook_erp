<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Product;
use App\Models\StockIssue;
use App\Models\StockIssueProduct;
use App\Utils\DayBookUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\NameSearchUtil;
use App\Utils\ProductStockUtil;
use App\Utils\StockIssueProductUtil;
use App\Utils\StockIssueUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockIssueController extends Controller
{
    public function __construct(
        private ProductStockUtil $productStockUtil,
        private InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        private UserActivityLogUtil $userActivityLogUtil,
        private StockIssueUtil $stockIssueUtil,
        private StockIssueProductUtil $stockIssueProductUtil,
        private NameSearchUtil $nameSearchUtil,
        private DayBookUtil $dayBookUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('stock_issue_index')) {

            abort(403, 'Access denied.');
        }

        if ($request->ajax()) {

            return $this->stockIssueUtil->stockIssueListTable($request);
        }

        $departments = DB::table('departments')->select('id', 'name')->orderBy('name', 'asc')->get();
        $events = DB::table('stock_events')->select('id', 'name')->orderBy('name', 'asc')->get();

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->orderBy('warehouses.warehouse_name', 'asc')->get();

        return view('procurement.stock_issue.index', compact('departments', 'warehouses', 'events'));
    }

    public function show($stockIssueId)
    {
        if (!auth()->user()->can('stock_issue_view')) {

            abort(403, 'Access denied.');
        }

        $stockIssue = StockIssue::with([
            'department',
            'event',
            'createdBy:id,prefix,name,last_name',
            'issueProducts',
            'issueProducts.product',
            'issueProducts.variant',
            'issueProducts.warehouse:id,warehouse_name,warehouse_code',
            'issueProducts.issueUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'issueProducts.issueUnit.baseUnit:id,base_unit_id,code_name',
        ])->where('id', $stockIssueId)->first();

        return view('procurement.stock_issue.ajax_view.show', compact('stockIssue'));
    }

    public function create()
    {
        if (!auth()->user()->can('stock_issue_create')) {

            abort(403, 'Access denied.');
        }

        $departments = DB::table('departments')->select('id', 'name')->orderBy('name', 'asc')->get();
        $events = DB::table('stock_events')->select('id', 'name')->orderBy('name', 'asc')->get();

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->get();

        return view('procurement.stock_issue.create', compact('departments', 'warehouses', 'events'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (!auth()->user()->can('stock_issue_create')) {

            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'date' => 'required|date',
            'department_id' => 'required',
        ], [
            'department_id.required' => 'Department field is required.',
        ]);

        if (!isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Item table is empty.']);
        } elseif (count($request->product_ids) > 60) {

            return response()->json(['errorMsg' => 'Stock issue items must be less than 60 or equal.']);
        }

        try {

            DB::beginTransaction();

            $addStockIssue = $this->stockIssueUtil->addStockIssue($request, $codeGenerationService);

            // Add Day Book entry for Stock Issue
            $this->dayBookUtil->addDayBook(voucherTypeId: 15, date: $request->date, accountId: null, transId: $addStockIssue->id, amount: $request->net_total_value, amountType: 'credit', productId: $request->product_ids[0]);

            $this->stockIssueProductUtil->addStockIssueProduct($request, $addStockIssue->id);

            $__index = 0;
            foreach ($request->product_ids as $productId) {

                $warehouse_id = $request->warehouse_ids[$__index] ? $request->warehouse_ids[$__index] : null;
                $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;

                $this->productStockUtil->adjustMainProductAndVariantStock($productId, $variant_id);

                if ($warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($productId, $variant_id, $warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($productId, $variant_id);
                }

                $__index++;
            }

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 31, data_obj: $addStockIssue);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => 'Successfully stock issue is created.']);
        } else {

            $stockIssue = StockIssue::with([
                'department',
                'event',
                'createdBy:id,prefix,name,last_name',
                'issueProducts',
                'issueProducts.product',
                'issueProducts.variant',
                'issueProducts.warehouse:id,warehouse_name,warehouse_code',
                'issueProducts.issueUnit:id,code_name,base_unit_id,base_unit_multiplier',
                'issueProducts.issueUnit.baseUnit:id,base_unit_id,code_name',
            ])->where('id', $addStockIssue->id)->first();

            return view('procurement.save_and_print_template.print_stock_issue', compact('stockIssue'));
        }
    }

    public function edit($stockIssueId)
    {
        if (!auth()->user()->can('stock_issue_update')) {

            abort(403, 'Access denied.');
        }

        $departments = DB::table('departments')->select('id', 'name')->orderBy('name', 'asc')->get();
        $events = DB::table('stock_events')->select('id', 'name')->orderBy('name', 'asc')->get();

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->get();

        $stockIssue = StockIssue::with([
            'issueProducts',
            'issueProducts.warehouse',
            'issueProducts.product',
            'issueProducts.product.unit:id,name,code_name',
            'issueProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'issueProducts.variant',
        ])->where('id', $stockIssueId)->first();

        return view('procurement.stock_issue.edit', compact('departments', 'warehouses', 'events', 'stockIssue'));
    }

    public function update(Request $request, $stockIssueId)
    {
        if (!auth()->user()->can('stock_issue_update')) {

            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'date' => 'required|date',
            'department_id' => 'required',
        ], [
            'department_id.required' => 'Department field is required.',
        ]);

        if (!isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Item table is empty.']);
        } elseif (count($request->product_ids) > 60) {

            return response()->json(['errorMsg' => 'Stock issue items must be less than 60 or equal.']);
        }

        try {

            DB::beginTransaction();
            $stockIssue = StockIssue::with(['issueProducts'])->where('id', $stockIssueId)->first();

            $storeIssuedProducts = $stockIssue->issueProducts;

            foreach ($stockIssue->issueProducts as $issueProduct) {

                $issueProduct->is_delete_in_update = 1;
                $issueProduct->save();
            }

            $updateStockIssue = $this->stockIssueUtil->updateStockIssue($stockIssue, $request);

            // Update Day Book entry for Stock Issue
            $this->dayBookUtil->updateDayBook(voucherTypeId: 15, date: $request->date, accountId: null, transId: $updateStockIssue->id, amount: $request->net_total_value, amountType: 'credit', productId: $request->product_ids[0]);

            $this->stockIssueProductUtil->updateStockIssueProduct($request, $stockIssueId);

            // deleted not getting previous product
            $deletedUnusedStockIssuedProducts = StockIssueProduct::where('stock_issue_id', $stockIssueId)
                ->where('is_delete_in_update', 1)
                ->get();

            if (count($deletedUnusedStockIssuedProducts) > 0) {

                foreach ($deletedUnusedStockIssuedProducts as $deletedUnusedStockIssuedProduct) {

                    $storedProductId = $deletedUnusedStockIssuedProduct->product_id;
                    $storedVariantId = $deletedUnusedStockIssuedProduct->variant_id;
                    $storedWarehouseId = $deletedUnusedStockIssuedProduct->warehouse_id;
                    $deletedUnusedStockIssuedProduct->delete();

                    // Adjust deleted product stock
                    $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

                    if (isset($storedWarehouseId)) {

                        $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $storedWarehouseId);
                    } else {

                        $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId);
                    }
                }
            }

            $stockIssuedProducts = DB::table('stock_issue_products')->where('stock_issue_id', $stockIssueId)->get();

            foreach ($stockIssuedProducts as $stockIssuedProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($stockIssuedProduct->product_id, $stockIssuedProduct->variant_id);

                if (isset($stockIssuedProduct->warehouse_id)) {

                    $this->productStockUtil->adjustWarehouseStock($stockIssuedProduct->product_id, $stockIssuedProduct->variant_id, $stockIssuedProduct->warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($stockIssuedProduct->product_id, $stockIssuedProduct->variant_id);
                }
            }

            foreach ($storeIssuedProducts as $issuedProducts) {

                if ($issuedProducts->warehouse_id) {

                    $check = DB::table('stock_issue_products')->where('id', $issuedProducts->id)
                        ->where('warehouse_id', $issuedProducts->warehouse_id)
                        ->where('product_id', $issuedProducts->product_id)
                        ->where('variant_id', $issuedProducts->variant_id)->first();

                    if (!$check) {

                        $this->productStockUtil->adjustWarehouseStock($issuedProducts->product_id, $issuedProducts->variant_id, $issuedProducts->warehouse_id);
                    }
                }
            }

            // Update user Log
            $this->userActivityLogUtil->addLog(action: 2, subject_type: 31, data_obj: $updateStockIssue);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }
        session()->flash('successMsg', 'Successfully stock issue is updated');

        return response()->json('Successfully stock issue is updated');
    }

    public function delete(Request $request, $stockIssueId)
    {
        if (!auth()->user()->can('stock_issue_delete')) {

            abort(403, 'Access denied.');
        }

        $deleteStockIssue = StockIssue::with(['issueProducts'])->where('id', $stockIssueId)->first();

        $storeIssuedProducts = $deleteStockIssue->issueProducts;

        // Add user Log
        $this->userActivityLogUtil->addLog(action: 3, subject_type: 31, data_obj: $deleteStockIssue);

        $deleteStockIssue->delete();

        foreach ($storeIssuedProducts as $storeIssuedProduct) {

            $variant_id = $storeIssuedProduct->variant_id ? $storeIssuedProduct->variant_id : null;

            $this->productStockUtil->adjustMainProductAndVariantStock($storeIssuedProduct->product_id, $variant_id);

            if ($storeIssuedProduct->warehouse_id) {

                $this->productStockUtil->adjustWarehouseStock($storeIssuedProduct->product_id, $variant_id, $storeIssuedProduct->warehouse_id);
            } else {

                $this->productStockUtil->adjustBranchStock($storeIssuedProduct->product_id, $variant_id);
            }
        }

        return response()->json('Successfully stock issue is deleted');
    }
}
