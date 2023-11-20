<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HRM\Http\Requests\Designation\CreateDesignationRequest;
use Modules\HRM\Http\Requests\Designation\UpdateDesignationRequest;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Transformers\DesignationResource;

class DesignationController extends Controller
{
    private $designationService;

    public function __construct(DesignationServiceInterface $designationService)
    {
        $this->designationService = $designationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $designations = DesignationResource::collection($this->designationService->all());

        return $designations;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateDesignationRequest $request)
    {
        $data = $this->designationService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Designation Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $designation = DesignationResource::make($this->designationService->find($id));

        return $designation;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateDesignationRequest $request, $id)
    {
        $data = $this->designationService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Designation Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $designation = $this->designationService->trash($id);

        return response()->json(['message' => 'Designation Deleted successfully']);
    }

    /**
     * Permanent Delete the designation Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $designations = DesignationResource::collection($this->designationService->getTrashedItem());

        return $designations;
    }

    /**
     * Permanent Delete the designation Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $designation = $this->designationService->permanentDelete($id);

        return response()->json(['message' => 'Designation is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $designation = $this->designationService->restore($id);

        return response()->json(['message' => 'Designation restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->designation_id)) {
            if ($request->action_type == 'move_to_trash') {
                $designation = $this->designationService->bulkTrash($request->designation_id);

                return response()->json(['message' => 'Designations are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $designation = $this->designationService->bulkRestore($request->designation_id);

                return response()->json(['message' => 'Designations are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $designation = $this->designationService->bulkPermanentDelete($request->designation_id);

                return response()->json(['message' => 'Designations are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
