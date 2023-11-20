<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\SalaryAdjustment\CreateSalaryAdjustmentRequest;
use Modules\HRM\Http\Requests\SalaryAdjustment\UpdateSalaryAdjustmentRequest;
use Modules\HRM\Interface\SalaryAdjustmentServiceInterface;
use Modules\HRM\Transformers\SalaryAdjustmentResource;

class SalaryAdjustmentController extends Controller
{
    private $salaryAdjustmentService;

    public function __construct(SalaryAdjustmentServiceInterface $salaryAdjustmentService)
    {
        $this->salaryAdjustmentService = $salaryAdjustmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $salaryAdjustment = SalaryAdjustmentResource::collection($this->salaryAdjustmentService->all());

        return $salaryAdjustment;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateSalaryAdjustmentRequest $request)
    {
        $data = $this->salaryAdjustmentService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Salary adjustment saved successfully.']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $salaryAdjustment = SalaryAdjustmentResource::make($this->salaryAdjustmentService->find($id));

        return $salaryAdjustment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateSalaryAdjustmentRequest $request, $id)
    {
        $data = $this->salaryAdjustmentService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Salary adjustment Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $salaryAdjustment = $this->salaryAdjustmentService->trash($id);

        return response()->json(['message' => 'Salary adjustment Deleted Successfully.']);

    }

    public function allTrash()
    {
        $salaryAdjustment = SalaryAdjustmentResource::collection($this->salaryAdjustmentService->getTrashedItem());

        return $salaryAdjustment;
    }

    public function permanentDelete($id)
    {
        $salaryAdjustment = $this->salaryAdjustmentService->permanentDelete($id);

        return response()->json(['message' => 'Salary adjustment is permanent deleted successfully']);
    }

    public function restore($id)
    {
        $salaryAdjustment = $this->salaryAdjustmentService->restore($id);

        return response()->json(['message' => 'Salary adjustment restored Successfully']);
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->employee_id)) {
            if ($request->action_type == 'move_to_trash') {
                $salaryAdjustment = $this->salaryAdjustmentService->bulkTrash($request->employee_id);

                return response()->json(['message' => 'Salary adjustment are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $salaryAdjustment = $this->salaryAdjustmentService->bulkRestore($request->employee_id);

                return response()->json(['message' => 'Salary adjustment are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $holiday = $this->salaryAdjustmentService->bulkPermanentDelete($request->employee_id);

                return response()->json(['message' => 'Salary adjustment are permanent deleted successfully'], 401);
            } else {
                return response()->json(['message' => 'Action is not specified']);
            }
        } else {
            return response()->json(['message' => 'No item is selected'], 401);
        }
    }
}
