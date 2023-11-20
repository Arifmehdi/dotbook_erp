<?php

namespace Modules\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Http\Requests\BdUnion\CreateBdUnionRequest;
use Modules\Core\Http\Requests\BdUnion\UpdateBdUnionRequest;
use Modules\Core\Interface\BdUnionServiceInterface;
use Modules\Core\Transformers\BdUnionResource;

class BdUnionController extends Controller
{
    private $unionService;

    public function __construct(BdUnionServiceInterface $unionService)
    {
        $this->unionService = $unionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $params = $request->validate([
            'upazila_id' => 'nullable|numeric',
        ]);
        $unions = $this->unionService->all($params)->paginate();
        $unionsResource = BdUnionResource::collection($unions);

        return $unionsResource;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateBdUnionRequest $request)
    {
        $data = $this->unionService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Union Saved successfully!']);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $union = BdUnionResource::make($this->unionService->find($id));

        return $union;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateBdUnionRequest $request, $id)
    {
        $data = $this->unionService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Union Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $union = $this->unionService->trash($id);

        return response()->json(['message' => 'Union Deleted successfully']);
    }

    public function allTrash()
    {
        $bdUnions = BdUnionResource::collection($this->unionService->getTrashedItem());

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
        $department = $this->unionService->permanentDelete($id);

        return response()->json(['message' => 'Union is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $department = $this->unionService->restore($id);

        return response()->json(['message' => 'Union restored successfully']);
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
        if (isset($request->union_id)) {
            if ($request->action_type == 'move_to_trash') {
                $department = $this->unionService->bulkTrash($request->union_id);

                return response()->json(['message' => 'Unions are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $department = $this->unionService->bulkRestore($request->union_id);

                return response()->json(['message' => 'Unions are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $department = $this->unionService->bulkPermanentDelete($request->union_id);

                return response()->json(['message' => 'Unions are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
