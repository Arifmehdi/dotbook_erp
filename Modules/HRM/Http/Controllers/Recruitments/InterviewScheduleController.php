<?php

namespace Modules\HRM\Http\Controllers\Recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Communication\Entities\EmailTemplate;
use Modules\HRM\Entities\Interview;
use Modules\HRM\Enums\JobAppliedStatus;
use Modules\HRM\Http\Requests\Recruitment\CreateScheduleRequest;
use Modules\HRM\Http\Requests\Recruitment\UpdateScheduleRequest;
use Modules\HRM\Interface\InterviewScheduleServiceInterface;
use Modules\HRM\Interface\RecruitmentServiceInterface;
use Modules\Website\Entities\JobApply;
use Yajra\DataTables\Facades\DataTables;

class InterviewScheduleController extends Controller
{
    private $interviewScheduleService;

    public function __construct(InterviewScheduleServiceInterface $interviewScheduleService, private RecruitmentServiceInterface $recruitmentService)
    {
        $this->interviewScheduleService = $interviewScheduleService;
    }

    public function scheduleList(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $interviewSchedule = $this->interviewScheduleService->getTrashedItem();
        } else {
            $interviewSchedule = $this->interviewScheduleService->all();
        }
        $rowCount = $this->interviewScheduleService->getRowCount();
        $trashedCount = $this->interviewScheduleService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($interviewSchedule)
                ->addIndexColumn()
                ->addColumn('interview_title', function ($row) {
                    return $row->interview->title;
                })
                ->editColumn('interviewers', function ($row) {
                    return $row->interviewers;
                })
                ->addColumn('applicant', function ($row) {
                    return $row->applicant->full_name;
                })
                ->editColumn('descriptions', function ($row) {
                    return $row->descriptions;
                })
                ->editColumn('date_time', function ($row) {
                    return $row->date_time;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="schedule_id[]" value="'.$row->id.'" class="mt-2 check1">
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
                        $html .= '<a href="'.route('hrm.schedule_edit', $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'update_leave_type" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_leave_types_delete')) {
                        $html .= '<a href="'.route('hrm.schedule_destroy', $row->id).'" class="action-btn c-delete delete" id="delete_leave_type" title="Delete">'.$icon2.'</a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'interview_title', 'applicant'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }
        $selectedForInterviees = JobApply::where('status', JobAppliedStatus::SelectedInterview->value)->select('id', 'first_name', 'last_name')->get();
        $interviewTitles = Interview::all();
        $email_templates = EmailTemplate::pluck('format_name', 'id');

        return view('hrm::recruitments.interview-schedule.index', compact('interviewSchedule', 'interviewTitles', 'selectedForInterviees', 'email_templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function scheduleStore(CreateScheduleRequest $request)
    {
        $attributes = $request->validated();
        $interviewSchedule = $this->interviewScheduleService->store($attributes);

        return response()->json('Interview schedule created successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function scheduleEdit($id)
    {
        $interviewSchedule = $this->interviewScheduleService->find($id);
        $selectedForInterviees = JobApply::where('status', JobAppliedStatus::SelectedInterview->value)->select('id', 'first_name', 'last_name')->get();
        $interviewTitles = Interview::all();

        return view('hrm::recruitments.interview-schedule.ajax_views.edit', compact('interviewSchedule', 'selectedForInterviees', 'interviewTitles'));
    }

    public function scheduleUpdate(UpdateScheduleRequest $request, $id)
    {
        $attributes = $request->validated();
        $this->interviewScheduleService->update($attributes, $id);

        return response()->json('Interview schedule updated successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function scheduleDestroy($id)
    {
        $interviewSchedule = $this->interviewScheduleService->trash($id);

        return response()->json('Interview schedule deleted successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function schedulePermanentDelete($id)
    {
        $interviewSchedule = $this->interviewScheduleService->permanentDelete($id);

        return response()->json('Interview schedule is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $interviewSchedule = $this->interviewScheduleService->restore($id);

        return response()->json('Interview schedule restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function scheduleBulkAction(Request $request)
    {
        if (isset($request->schedule_id)) {
            if ($request->action_type == 'move_to_trash') {
                $interviewSchedule = $this->interviewScheduleService->bulkTrash($request->schedule_id);

                return response()->json('Interview schedule are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $interviewSchedule = $this->interviewScheduleService->bulkRestore($request->schedule_id);

                return response()->json('Interview schedule are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $interviewSchedule = $this->interviewScheduleService->bulkPermanentDelete($request->schedule_id);

                return response()->json('Interview schedule are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
