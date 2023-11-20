<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Visit\CreateVisitRequest;
use Modules\HRM\Http\Requests\Visit\UpdateVisitRequest;
use Modules\HRM\Interface\VisitServiceInterface;
use Modules\HRM\Transformers\VisitResource;

class VisitController extends Controller
{
    private $visitService;

    public function __construct(VisitServiceInterface $visitService)
    {
        $this->visitService = $visitService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $visit = VisitResource::collection($this->visitService->all());

        return $visit;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('hrm::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateVisitRequest $request)
    {
        $visit = $this->visitService->store($request->validated());
        if ($visit) {
            return response()->json('Visit created successfully!');
        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        $visit = VisitResource::make($this->visitService->find($id));

        return $visit;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('hrm::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateVisitRequest $request, $id)
    {
        $visit = $this->visitService->update($request->validated(), $id);
        if ($visit) {
            return response()->json('Visit updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $visit = $this->visitService->trash($id);

        return response()->json('Visit deleted successfully!');
    }

    public function allTrash()
    {
        $visit = VisitResource::collection($this->visitService->getTrashedItem());

        return $visit;
    }

    /**
     * Permanent Delete the shift Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $visit = $this->visitService->permanentDelete($id);

        return response()->json(['message' => 'Visit is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $visit = $this->visitService->restore($id);

        return response()->json(['message' => 'Visit restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->visit_id)) {
            if ($request->action_type == 'move_to_trash') {
                $visit = $this->visitService->bulkTrash($request->visit_id);

                return response()->json(['message' => 'Visit are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $visit = $this->visitService->bulkRestore($request->visit_id);

                return response()->json(['message' => 'Visit are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $visit = $this->visitService->bulkPermanentDelete($request->visit_id);

                return response()->json(['message' => 'Visit are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
