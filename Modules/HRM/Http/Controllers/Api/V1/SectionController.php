<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Section\CreateSectionRequest;
use Modules\HRM\Http\Requests\Section\UpdateSectionRequest;
use Modules\HRM\Interface\SectionServiceInterface;
use Modules\HRM\Transformers\SectionResource;

class SectionController extends Controller
{
    private $sectionService;

    public function __construct(SectionServiceInterface $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $sections = SectionResource::collection($this->sectionService->all());

        return $sections;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateSectionRequest $request)
    {
        $data = $this->sectionService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Section Saved successfully!'])->setStatusCode(201);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {

        $section = SectionResource::make($this->sectionService->find($id));

        return $section;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateSectionRequest $request, $id)
    {
        $data = $this->sectionService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Section Updated successfully!'])->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $section = $this->sectionService->trash($id);

        return response()->json(['message' => 'Section deleted successfully'])->setStatusCode(202);
    }

    /**
     * Permanent Delete the section Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $sections = SectionResource::collection($this->sectionService->getTrashedItem());

        return $sections;
    }

    /**
     * Permanent Delete the section Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $section = $this->sectionService->permanentDelete($id);

        return response()->json(['message' => 'Section is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $section = $this->sectionService->restore($id);

        return response()->json(['message' => 'Section restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->section_id)) {
            if ($request->action_type == 'move_to_trash') {
                $section = $this->sectionService->bulkTrash($request->section_id);

                return response()->json(['message' => 'Sections are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $section = $this->sectionService->bulkRestore($request->section_id);

                return response()->json(['message' => 'Sections are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $section = $this->sectionService->bulkPermanentDelete($request->section_id);

                return response()->json(['message' => 'Sections are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
