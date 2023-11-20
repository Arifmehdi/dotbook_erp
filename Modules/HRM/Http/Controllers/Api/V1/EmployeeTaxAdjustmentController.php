<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\EmployeeTaxAdjustment\CreateEmployeeTaxAdjustmentRequest;
use Modules\HRM\Http\Requests\EmployeeTaxAdjustment\UpdateEmployeeTaxAdjustmentRequest;
use Modules\HRM\Interface\EmployeeTaxAdjustmentServiceInterface;
use Modules\HRM\Transformers\EmployeeTaxAdjustmentResource;

class EmployeeTaxAdjustmentController extends Controller
{
    private $employeeTaxAdjustmentService;

    public function __construct(EmployeeTaxAdjustmentServiceInterface $employeeTaxAdjustmentService)
    {
        $this->employeeTaxAdjustmentService = $employeeTaxAdjustmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $employeeTaxAdjustment = EmployeeTaxAdjustmentResource::collection($this->employeeTaxAdjustmentService->all());

        return $employeeTaxAdjustment;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateEmployeeTaxAdjustmentRequest $request)
    {
        $data = $this->employeeTaxAdjustmentService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Employee tax adjustment saved successfully.']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $ids
     * @return Response
     */
    public function show($id)
    {
        $employeeTaxAdjustment = EmployeeTaxAdjustmentResource::make($this->employeeTaxAdjustmentService->find($id));

        return $employeeTaxAdjustment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateEmployeeTaxAdjustmentRequest $request, $id)
    {
        $data = $this->employeeTaxAdjustmentService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Employee tax adjustment Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->trash($id);

        return response()->json(['message' => 'Employee tax adjustment Deleted Successfully.']);

    }

    public function allTrash()
    {
        $employeeTaxAdjustment = EmployeeTaxAdjustmentResource::collection($this->employeeTaxAdjustmentService->getTrashedItem());

        return $employeeTaxAdjustment;
    }

    public function permanentDelete($id)
    {
        $overtimeAdjustment = $this->employeeTaxAdjustmentService->permanentDelete($id);

        return response()->json(['message' => 'Employee tax adjustment is permanent deleted successfully']);
    }

    public function restore($id)
    {
        $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->restore($id);

        return response()->json(['message' => 'Employee tax adjustment restored Successfully']);
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->tax_id)) {
            if ($request->action_type == 'move_to_trash') {
                $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->bulkTrash($request->tax_id);

                return response()->json(['message' => 'Employee tax adjustment are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->bulkRestore($request->tax_id);

                return response()->json(['message' => 'Employee tax adjustment are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $employeeTaxAdjustment = $this->employeeTaxAdjustmentService->bulkPermanentDelete($request->tax_id);

                return response()->json(['message' => 'Employee tax adjustment are permanent deleted successfully'], 401);
            } else {
                return response()->json(['message' => 'Action is not specified']);
            }
        } else {
            return response()->json(['message' => 'No item is selected'], 401);
        }
    }
}
