<?php

namespace Modules\HRM\Http\Controllers\Recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Recruitment\CreateInterviewQuestionRequest;
use Modules\HRM\Http\Requests\Recruitment\UpdateInterviewQuestionRequest;
use Modules\HRM\Interface\InterviewQuestionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class InterviewQuestionController extends Controller
{
    private $interviewQuestionService;

    public function __construct(InterviewQuestionServiceInterface $interviewQuestionService)
    {
        $this->interviewQuestionService = $interviewQuestionService;
    }

    public function interviewQuestionList(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $interview = $this->interviewQuestionService->getTrashedItem();
        } else {
            $interview = $this->interviewQuestionService->all();
        }
        $rowCount = $this->interviewQuestionService->getRowCount();
        $trashedCount = $this->interviewQuestionService->getTrashedCount();
        if ($request->ajax()) {
            return DataTables::of($interview)
                ->addIndexColumn()
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('description', function ($row) {
                    return $row->description;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="interview_id[]" value="'.$row->id.'" class="mt-2 check1">
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
                    if (auth()->user()->can('hrm_interview_update')) {
                        $html .= '<a href="'.route('hrm.interview_question_edit', $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'update_leave_type" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_leave_types_delete')) {
                        $html .= '<a href="'.route('hrm.interview_question_destroy', $row->id).'" class="action-btn c-delete delete" id="delete_leave_type" title="Delete">'.$icon2.'</a>';
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

        return view('hrm::recruitments.interview-question.index', compact('interview'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function interviewQuestionStore(CreateInterviewQuestionRequest $request)
    {
        $attributes = $request->validated();
        $interview = $this->interviewQuestionService->store($attributes);

        return response()->json('Interview question created successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function interviewQuestionEdit($id)
    {
        $interview = $this->interviewQuestionService->find($id);

        return view('hrm::recruitments.interview-question.ajax_views.edit', compact('interview'));
    }

    public function interviewQuestionUpdate(UpdateInterviewQuestionRequest $request, $id)
    {
        $attributes = $request->validated();
        $this->interviewQuestionService->update($attributes, $id);

        return response()->json('Interview question updated successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function interviewQuestionDestroy($id)
    {
        $interview = $this->interviewQuestionService->trash($id);

        return response()->json('Interview question deleted successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function interviewQuestionPermanentDelete($id)
    {
        $interview = $this->interviewQuestionService->permanentDelete($id);

        return response()->json('Interview question is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $interview = $this->interviewQuestionService->restore($id);

        return response()->json('Interview question restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function interviewQuestionBulkAction(Request $request)
    {
        if (isset($request->interview_id)) {
            if ($request->action_type == 'move_to_trash') {
                $interview = $this->interviewQuestionService->bulkTrash($request->interview_id);

                return response()->json('Interview question are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $interview = $this->interviewQuestionService->bulkRestore($request->interview_id);

                return response()->json('Interview question are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $interview = $this->interviewQuestionService->bulkPermanentDelete($request->interview_id);

                return response()->json('Interview question are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
