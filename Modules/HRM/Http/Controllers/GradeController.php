<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Grade\CreateGradeRequest;
use Modules\HRM\Http\Requests\Grade\UpdateGradeRequest;
use Modules\HRM\Interface\GradeServiceInterface;
use Yajra\DataTables\Facades\DataTables;

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
    public function index(Request $request)
    {

        if ($request->showTrashed == 'true') {
            $grades = $this->gradeService->getTrashedItem();
        } else {
            $grades = $this->gradeService->all();
        }

        $rowCount = $this->gradeService->getRowCount();
        $trashedCount = $this->gradeService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($grades)
                ->addIndexColumn()
                ->addColumn('gross_salary', fn($row) => $row->gross_salary)
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="grade_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type = '';
                    $icon1 = '';
                    $icon2 = '';
                    if ($row->trashed()) {
                        $action1 = 'restore';
                        $action2 = 'permanent-delete';
                        $type = 'restore';
                        $icon1 = '<i class="fa-solid fa-recycle"></i>';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i>';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $type = 'Edit';
                        $icon1 = '<span class="fas fa-edit"></span></a>';
                        $icon2 = '<span class="fas fa-trash "></span>';
                    }

                    $html = '<div class="dropdown table-dropdown">';

                    if (auth()->user()->can('hrm_grades_update')) {
                        $html .= '<a href="'.route('hrm.grades.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_grade" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_grades_delete')) {
                        $html .= '<a href="'.route('hrm.grades.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_grade" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::grades.index', compact('grades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateGradeRequest $request)
    {
        $service = $this->gradeService->store($request->validated());

        return response()->json('Grade created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $grade = $this->gradeService->find($id);

        return view('hrm::grades.ajax_views.edit', compact('grade'));
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
        $grade = $this->gradeService->update($request->validated(), $id);

        return response()->json('Grade updated successfully');
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

        return response()->json('Grade deleted successfully');
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

        return response()->json('Grade is permanently deleted successfully');
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

        return response()->json('Grade restored successfully');
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

                return response()->json('Grades are Deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $grade = $this->gradeService->bulkRestore($request->grade_id);

                return response()->json('Grades are Restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $grade = $this->gradeService->bulkPermanentDelete($request->grade_id);

                return response()->json('Grades are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
