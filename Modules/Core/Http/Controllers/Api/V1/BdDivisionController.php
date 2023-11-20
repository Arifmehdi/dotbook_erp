<?php

namespace Modules\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Http\Requests\BdDivision\CreateBdDivisionRequest;
use Modules\Core\Http\Requests\BdDivision\UpdateBdDivisionRequest;
use Modules\Core\Interface\BdDivisionServiceInterface;
use Modules\Core\Transformers\BdDivisionResource;

class BdDivisionController extends Controller
{
    private $divisionService;

    public function __construct(BdDivisionServiceInterface $divisionService)
    {
        $this->divisionService = $divisionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $divisions = BdDivisionResource::collection($this->divisionService->all());

        return $divisions;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateBdDivisionRequest $request)
    {
        $data = $this->divisionService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Division Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $division = BdDivisionResource::make($this->divisionService->find($id));

        return $division;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateBdDivisionRequest $request, $id)
    {
        $data = $this->divisionService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Division Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $division = $this->divisionService->trash($id);

        return response()->json(['message' => 'Division Deleted successfully']);
    }

    public function allTrash()
    {
        $bdDivision = BdDivisionResource::collection($this->divisionService->getTrashedItem());

        return $bdDivision;
    }

    /**
     * Permanent Delete the department Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $department = $this->divisionService->permanentDelete($id);

        return response()->json(['message' => 'Division is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $department = $this->divisionService->restore($id);

        return response()->json(['message' => 'Division restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->division_id)) {
            if ($request->action_type == 'move_to_trash') {
                $department = $this->divisionService->bulkTrash($request->division_id);

                return response()->json(['message' => 'Divisions are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $department = $this->divisionService->bulkRestore($request->division_id);

                return response()->json(['message' => 'Divisions are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $department = $this->divisionService->bulkPermanentDelete($request->division_id);

                return response()->json(['message' => 'Divisions are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
