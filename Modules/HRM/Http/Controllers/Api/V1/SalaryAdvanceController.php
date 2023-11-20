<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\SalaryAdvance\CreateSalaryAdvanceRequest;
use Modules\HRM\Http\Requests\SalaryAdvance\UpdateSalaryAdvanceRequest;
use Modules\HRM\Interface\SalaryAdvanceServiceInterface;
use Modules\HRM\Transformers\SalaryAdvanceResource;

class SalaryAdvanceController extends Controller
{
    private $salaryAdvanceService;

    public function __construct(SalaryAdvanceServiceInterface $salaryAdvanceService)
    {
        $this->salaryAdvanceService = $salaryAdvanceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $salaryAdvance = SalaryAdvanceResource::collection($this->salaryAdvanceService->all());

        return $salaryAdvance;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateSalaryAdvanceRequest $request)
    {
        $data = $this->salaryAdvanceService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Salary advance saved successfully.']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $salaryAdvance = SalaryAdvanceResource::make($this->salaryAdvanceService->find($id));

        return $salaryAdvance;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateSalaryAdvanceRequest $request, $id)
    {
        $data = $this->salaryAdvanceService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Salary advance Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $salaryAdvance = $this->salaryAdvanceService->trash($id);

        return response()->json(['message' => 'Salary advance Deleted Successfully.']);

    }

    public function allTrash()
    {
        $salaryAdvance = SalaryAdvanceResource::collection($this->salaryAdvanceService->getTrashedItem());

        return $salaryAdvance;
    }

    public function permanentDelete($id)
    {
        $salaryAdvance = $this->salaryAdvanceService->permanentDelete($id);

        return response()->json(['message' => 'Salary advance is permanent deleted successfully']);
    }

    public function restore($id)
    {
        $salaryAdvance = $this->salaryAdvanceService->restore($id);

        return response()->json(['message' => 'Salary adjustment restored Successfully']);
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->salary_advance_id)) {
            if ($request->action_type == 'move_to_trash') {
                $salaryAdvance = $this->salaryAdvanceService->bulkTrash($request->salary_advance_id);

                return response()->json(['message' => 'Salary adjustment are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $salaryAdvance = $this->salaryAdvanceService->bulkRestore($request->salary_advance_id);

                return response()->json(['message' => 'Salary adjustment are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $holiday = $this->salaryAdvanceService->bulkPermanentDelete($request->salary_advance_id);

                return response()->json(['message' => 'Salary adjustment are permanent deleted successfully'], 401);
            } else {
                return response()->json(['message' => 'Action is not specified']);
            }
        } else {
            return response()->json(['message' => 'No item is selected'], 401);
        }
    }
}
