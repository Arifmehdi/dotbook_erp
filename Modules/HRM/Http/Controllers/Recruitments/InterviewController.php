<?php

namespace Modules\HRM\Http\Controllers\Recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Recruitment\CreateInterviewRequest;
use Modules\HRM\Http\Requests\Recruitment\UpdateInterviewRequest;
use Modules\HRM\Interface\InterviewServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class InterviewController extends Controller
{
    private $interviewService;

    public function __construct(InterviewServiceInterface $interviewService)
    {
        $this->interviewService = $interviewService;
    }

    public function interviewList(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_interview_index'), 403, 'Access Forbidden');
        if ($request->showTrashed == 'true') {
            $interview = $this->interviewService->getTrashedItem();
        } else {
            $interview = $this->interviewService->all();
        }
        $rowCount = $this->interviewService->getRowCount();
        $trashedCount = $this->interviewService->getTrashedCount();
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
                        $html .= '<a href="'.route('hrm.interview_edit', $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'update_leave_type" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_leave_types_delete')) {
                        $html .= '<a href="'.route('hrm.interview_destroy', $row->id).'" class="action-btn c-delete delete" id="delete_leave_type" title="Delete">'.$icon2.'</a>';
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

        return view('hrm::recruitments.interview.index', compact('interview'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function interviewStore(CreateInterviewRequest $request)
    {
        abort_if(! auth()->user()->can('hrm_interview_create'), 403, 'Access Forbidden');
        $attributes = $request->validated();
        $interview = $this->interviewService->store($attributes);

        return response()->json('Interview created successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function interviewEdit($id)
    {
        abort_if(! auth()->user()->can('hrm_interview_update'), 403, 'Access Forbidden');
        $interview = $this->interviewService->find($id);

        return view('hrm::recruitments.interview.ajax_views.edit', compact('interview'));
    }

    public function interviewUpdate(UpdateInterviewRequest $request, $id)
    {
        $attributes = $request->validated();
        $this->interviewService->update($attributes, $id);

        return response()->json('Interview updated successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function interviewDestroy($id)
    {
        abort_if(! auth()->user()->can('hrm_interview_delete'), 403, 'Access Forbidden');
        $interview = $this->interviewService->trash($id);

        return response()->json('Interview deleted successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function interviewPermanentDelete($id)
    {
        abort_if(! auth()->user()->can('hrm_interview_delete'), 403, 'Access Forbidden');
        $interview = $this->interviewService->permanentDelete($id);

        return response()->json('Interview is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        abort_if(! auth()->user()->can('hrm_interview_delete'), 403, 'Access Forbidden');
        $interview = $this->interviewService->restore($id);

        return response()->json('Interview restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function interviewBulkAction(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_interview_delete'), 403, 'Access Forbidden');
        if (isset($request->interview_id)) {
            if ($request->action_type == 'move_to_trash') {
                $interview = $this->interviewService->bulkTrash($request->interview_id);

                return response()->json('Interview are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $interview = $this->interviewService->bulkRestore($request->interview_id);

                return response()->json('Interview are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $interview = $this->interviewService->bulkPermanentDelete($request->interview_id);

                return response()->json('Interview are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
