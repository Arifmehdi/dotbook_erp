<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Grade\CreateGradeRequest;
use Modules\HRM\Http\Requests\Grade\UpdateGradeRequest;
use Modules\HRM\Interface\GradeServiceInterface;
use Modules\HRM\Transformers\GradeResource;

class GradeController extends Controller
{
    private $gradeService;

    public function __construct(GradeServiceInterface $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {

        $grades = GradeResource::collection($this->gradeService->all());

        return $grades;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateGradeRequest $request)
    {
        $data = $this->gradeService->store($request->validated());

        return response()->json(['data' => $data, 'message' => 'Grade Saved successfully!'])->setStatusCode(201);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {

        $grade = GradeResource::make($this->gradeService->find($id));

        return $grade;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateGradeRequest $request, $id)
    {
        $data = $this->gradeService->update($request->validated(), $id);

        return response()->json(['data' => $data, 'message' => 'Grade Updated successfully!'])->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $grade = $this->gradeService->trash($id);

        return response()->json(['message' => 'Grade deleted successfully'])->setStatusCode(202);
    }

    /**
     * Permanent Delete the grade Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function allTrash()
    {
        $grades = GradeResource::collection($this->gradeService->getTrashedItem());

        return $grades;
    }

    /**
     * Permanent Delete the grade Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $grade = $this->gradeService->permanentDelete($id);

        return response()->json(['message' => 'Grade is permanently deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $grade = $this->gradeService->restore($id);

        return response()->json(['message' => 'Grade restored successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {

        if (isset($request->grade_id)) {
            if ($request->action_type == 'move_to_trash') {
                $grade = $this->gradeService->bulkTrash($request->grade_id);

                return response()->json(['message' => 'Grades are deleted successfully']);
            } elseif ($request->action_type == 'restore_from_trash') {
                $grade = $this->gradeService->bulkRestore($request->grade_id);

                return response()->json(['message' => 'Grades are restored successfully']);
            } elseif ($request->action_type == 'delete_permanently') {
                $grade = $this->gradeService->bulkPermanentDelete($request->grade_id);

                return response()->json(['message' => 'Grades are permanently deleted successfully']);
            } else {
                return response()->json(['message' => 'Action is not specified.']);
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
