<?php

namespace Modules\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Http\Requests\BdDistrict\CreateBdDistrictRequest;
use Modules\Core\Http\Requests\BdDistrict\UpdateBdDistrictRequest;
use Modules\Core\Interface\BdDistrictServiceInterface;
use Modules\Core\Transformers\BdDistrictResource;

class BdDistrictController extends Controller
{
    private $districtService;

    public function __construct(BdDistrictServiceInterface $districtService)
    {
        $this->districtService = $districtService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $param = $request->validate([
            'division_id' => 'nullable|numeric',
        ]);

        $districts = BdDistrictResource::collection($this->districtService->all($param));

        return $districts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateBdDistrictRequest $request)
    {
        $attributes = $request->validated();
        $data = $this->districtService->store($attributes);

        return response()->json(['data' => $data, 'message' => 'District Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $district = BdDistrictResource::make($this->districtService->find($id));

        return $district;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateBdDistrictRequest $request, $id)
    {
        $data = $this->districtService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'District Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $district = $this->districtService->trash($id);

        return response()->json(['message' => 'District Deleted successfully']);
    }

    public function districtFilterByDivision()
    {
        return 'pk';
    }

    public function allTrash()
    {
        $bdUnions = BdDistrictResource::collection($this->districtService->getTrashedItem());

        return $bdUnions;
    }

    /**
     * Permanent Delete the department Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $department = $this->districtService->permanentDelete($id);

        return response()->json(['message' => 'District is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $department = $this->districtService->restore($id);

        return response()->json(['message' => 'District restored successfully']);
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
        if (isset($request->district_id)) {
            if ($request->action_type == 'move_to_trash') {
                $department = $this->districtService->bulkTrash($request->district_id);

                return response()->json(['message' => 'District are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $department = $this->districtService->bulkRestore($request->district_id);

                return response()->json(['message' => 'District are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $department = $this->districtService->bulkPermanentDelete($request->district_id);

                return response()->json(['message' => 'District are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
