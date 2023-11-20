<?php

namespace Modules\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Http\Requests\BdUpazila\CreateBdUpazilaRequest;
use Modules\Core\Http\Requests\BdUpazila\UpdateBdUpazilaRequest;
use Modules\Core\Interface\BdUpazilaServiceInterface;
use Modules\Core\Transformers\BdUpazilaResource;

class BdUpazilaController extends Controller
{
    private $bdUpazilaService;

    public function __construct(BdUpazilaServiceInterface $bdUpazilaService)
    {
        $this->bdUpazilaService = $bdUpazilaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $params = $request->validate([
            'district_id' => 'nullable|numeric',
        ]);

        $upazilas = $this->bdUpazilaService->all($params);
        $upazilasResource = BdUpazilaResource::collection($upazilas);

        return $upazilasResource;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateBdUpazilaRequest $request)
    {
        $data = $this->bdUpazilaService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Upazlila Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $thana = BdUpazilaResource::make($this->bdUpazilaService->find($id));

        return $thana;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateBdUpazilaRequest $request, $id)
    {
        $data = $this->bdUpazilaService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Upazlila Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $thana = $this->bdUpazilaService->trash($id);

        return response()->json(['message' => 'Upazlila Deleted successfully']);
    }

    public function allTrash()
    {

        $bdUpazila = BdUpazilaResource::collection($this->bdUpazilaService->getTrashedItem());

        return $bdUpazila;
    }

    /**
     * Permanent Delete the department Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $bdUpazila = $this->bdUpazilaService->permanentDelete($id);

        return response()->json(['message' => 'Upazila is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $bdUpazila = $this->bdUpazilaService->restore($id);

        return response()->json(['message' => 'Upazila restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        // return $request;
        if (isset($request->thana_id)) {
            if ($request->action_type == 'move_to_trash') {
                $department = $this->bdUpazilaService->bulkTrash($request->thana_id);

                return response()->json(['message' => 'Upazila are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $department = $this->bdUpazilaService->bulkRestore($request->thana_id);

                return response()->json(['message' => 'Upazila are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $department = $this->bdUpazilaService->bulkPermanentDelete($request->thana_id);

                return response()->json(['message' => 'Upazila are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
