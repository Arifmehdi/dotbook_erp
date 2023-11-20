<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\SubSection\CreateSubSectionRequest;
use Modules\HRM\Http\Requests\SubSection\UpdateSubSectionRequest;
use Modules\HRM\Interface\SubSectionServiceInterface;
use Modules\HRM\Transformers\SubSectionResource;

class SubSectionController extends Controller
{
    private $subSectionService;

    public function __construct(SubSectionServiceInterface $subSectionService)
    {
        $this->subSectionService = $subSectionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $subSections = SubSectionResource::collection($this->subSectionService->all());

        return $subSections;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateSubSectionRequest $request)
    {
        $data = $this->subSectionService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Sub Section Saved successfully!'])->setStatusCode(201);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        $subSection = SubSectionResource::make($this->subSectionService->find($id));

        return $subSection;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateSubSectionRequest $request, $id)
    {
        $data = $this->subSectionService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Sub Section Updated successfully!'])->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $subSection = $this->subSectionService->trash($id);

        return response()->json(['message' => 'Sub Section deleted successfully'])->setStatusCode(202);
    }

    /**
     * Permanent Delete the Sub Section Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $subSections = SubSectionResource::collection($this->subSectionService->getTrashedItem());

        return $subSections;
    }

    /**
     * Permanent Delete the subSection Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $subSection = $this->subSectionService->permanentDelete($id);

        return response()->json(['message' => 'Sub Section is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $subSection = $this->subSectionService->restore($id);

        return response()->json(['message' => 'Sub Section restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->sub_section_id)) {
            if ($request->action_type == 'move_to_trash') {
                $subSection = $this->subSectionService->bulkTrash($request->sub_section_id);

                return response()->json(['message' => 'Sub Sections are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $subSection = $this->subSectionService->bulkRestore($request->sub_section_id);

                return response()->json(['message' => 'Sub Sections are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $subSection = $this->subSectionService->bulkPermanentDelete($request->sub_section_id);

                return response()->json(['message' => 'Sub Sections are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
