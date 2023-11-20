<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionProduct;
use App\Utils\Converter;
use App\Utils\RequisitionProductUtil;
use App\Utils\RequisitionUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseRequisitionController extends Controller
{
    public function __construct(
        private Converter $converter,
        private UserActivityLogUtil $userActivityLogUtil,
        private RequisitionUtil $requisitionUtil,
        private RequisitionProductUtil $requisitionProductUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('all_requisition')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->requisitionUtil->requisitionListTable($request);
        }

        $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name')->get();
        $requesters = DB::table('requesters')->select('id', 'name', 'phone_number')->orderBy('name', 'asc')->get();

        return view('procurement.requisitions.index', compact('users', 'requesters'));
    }

    public function show($requisitionId)
    {
        $requisition = PurchaseRequisition::with([
            'department:id,name',
            'requester:id,name',
            'createdBy:id,prefix,name,last_name',
            'requisitionProducts',
            'requisitionProducts.product',
            'requisitionProducts.product.unit:id,name,code_name',
            'requisitionProducts.variant',
            'requisitionProducts.requisitionUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'requisitionProducts.requisitionUnit.baseUnit:id,base_unit_id,code_name',
        ])->where('id', $requisitionId)->first();

        return view('procurement.requisitions.ajax_view.show', compact('requisition'));
    }

    public function create()
    {
        if (! auth()->user()->can('create_requisition')) {

            abort(403, 'Access Forbidden.');
        }

        $departments = DB::table('departments')->select('id', 'name')->orderBy('name', 'asc')->get();
        $requesters = DB::table('requesters')->select('id', 'name', 'phone_number')->orderBy('name', 'asc')->get();

        return view('procurement.requisitions.create', compact('departments', 'requesters'));
    }

    // Store requisition method
    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (! auth()->user()->can('create_requisition')) {

            return response()->json(['errorMsg' => 'Access Forbidden.']);
        }

        $this->validate($request, [
            'date' => 'required|date',
            'department_id' => 'required',
            'requester_id' => 'required',
        ], [
            'department_id.required' => 'Department field is required.',
            'requester_id.required' => 'Requested by field is required.',
        ]);

        if (! isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Product table is empty.']);
        } elseif (count($request->product_ids) > 60) {

            return response()->json(['errorMsg' => 'Purchase invoice items must be less than 60 or equal.']);
        }

        // add purchase total information
        $addRequisition = $this->requisitionUtil->addRequisition($request, $codeGenerationService);

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $this->requisitionProductUtil->addRequisitionProduct($request, $addRequisition->id, $index);

            $index++;
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => 'Successfully purchase requisition is created.']);
        } else {

            $requisition = PurchaseRequisition::with([
                'department:id,name',
                'requester:id,name',
                'createdBy:id,prefix,name,last_name',
                'requisitionProducts',
                'requisitionProducts.product',
                'requisitionProducts.product.unit:id,code_name',
                'requisitionProducts.variant',
                'requisitionProducts.requisitionUnit:id,code_name,base_unit_id,base_unit_multiplier',
                'requisitionProducts.requisitionUnit.baseUnit:id,base_unit_id,code_name',
            ])->where('id', $addRequisition->id)->first();

            return view('procurement.save_and_print_template.print_requisition', compact('requisition'));
        }
    }

    public function edit($requisitionId)
    {
        if (! auth()->user()->can('edit_requisition')) {
            abort(403, 'Access Forbidden.');
        }

        $requisition = PurchaseRequisition::with(
            'requisitionProducts',
            'requisitionProducts.product',
            'requisitionProducts.product.unit:id,name,code_name',
            'requisitionProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'requisitionProducts.variant',
            'requisitionProducts.requisitionUnit:id,name,base_unit_id,base_unit_multiplier',
            'requisitionProducts.requisitionUnit.baseUnit:id,name,base_unit_id',
        )->where('id', $requisitionId)->first();

        $departments = DB::table('departments')->select('id', 'name')->orderBy('name', 'asc')->get();
        $requesters = DB::table('requesters')->select('id', 'name', 'phone_number')->orderBy('name', 'asc')->get();

        return view('procurement.requisitions.edit', compact('requisition', 'departments', 'requesters'));
    }

    // Update requisition method
    public function update(Request $request, $requisitionId)
    {
        if (! auth()->user()->can('edit_requisition')) {

            return response()->json(['errorMsg' => 'Access Forbidden.']);
        }

        $this->validate($request, [
            'date' => 'required|date',
        ]);

        if (! isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Product table is empty.']);
        } elseif (count($request->product_ids) > 60) {

            return response()->json(['errorMsg' => 'Purchase invoice items must be less than 60 or equal.']);
        }

        // add purchase total information
        $requisition = PurchaseRequisition::with('requisitionProducts')->where('id', $requisitionId)->first();

        $updateRequisition = $this->requisitionUtil->updateRequisition($requisition, $request);

        foreach ($updateRequisition->requisitionProducts as $rq_product) {

            $rq_product->is_delete_in_update = 1;
            $rq_product->save();
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $this->requisitionProductUtil->updateRequisitionProduct($updateRequisition->id, $request, $index);

            $index++;
        }

        $this->requisitionUtil->updateRequisitionLeftQty($updateRequisition->id);

        $deleteRequisitionProduct = PurchaseRequisitionProduct::where('requisition_id', $updateRequisition->id)
            ->where('is_delete_in_update', 1)->get();

        foreach ($deleteRequisitionProduct as $rq_product) {

            $rq_product->delete();
        }

        return response()->json(['successMsg' => 'Successfully purchase requisition is updated.']);
    }

    public function delete(Request $request, $requisitionId)
    {
        // get deleting purchase row
        $deleteRequisition = PurchaseRequisition::with(['purchases', 'purchaseOrders'])->where('id', $requisitionId)->first();

        // // Add user Log
        // $this->userActivityLogUtil->addLog(
        //     action: 3,
        //     subject_type: $deletePurchase->purchase_status == 3 ? 5 : 4,
        //     data_obj: $deletePurchase
        // );

        if (count($deleteRequisition->purchases) > 0) {

            return response()->json('The requisition can\'t be deleted, Associated with purchase');
        }

        if (count($deleteRequisition->purchaseOrders) > 0) {

            return response()->json('The requisition can\'t be deleted, Associated with purchase order');
        }

        $deleteRequisition->delete();

        return response()->json('Successfully purchase requisition is deleted');
    }

    public function requisitionApproval($requisitionId)
    {
        if (! auth()->user()->can('approve_requisition')) {

            return response()->json(['errorMsg' => 'Access Forbidden.']);
        }

        $requisition = DB::table('purchase_requisitions')->where('id', $requisitionId)->select('id', 'is_approved')->first();

        return view('procurement.requisitions.ajax_view.requisition_approval_modal', compact('requisition'));
    }

    public function requisitionApprovalUpdate(Request $request, $requisitionId)
    {
        if (! auth()->user()->can('approve_requisition')) {

            return response()->json(['errorMsg' => 'Access Forbidden.']);
        }

        $requisition = PurchaseRequisition::where('id', $requisitionId)->first();
        $requisition->is_approved = $request->is_approved;
        $requisition->approved_by_id = auth()->user()->id;
        $requisition->save();

        return response()->json('Successfully Purchase Requisition approval status has been changed');
    }
}
