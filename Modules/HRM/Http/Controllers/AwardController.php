<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Award\CreateAwardRequest;
use Modules\HRM\Http\Requests\Award\UpdateAwardRequest;
use Modules\HRM\Interface\AwardServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class AwardController extends Controller
{
    private $awardService;

    private $employeeService;

    public function __construct(AwardServiceInterface $awardService, EmployeeServiceInterface $employeeService)
    {
        $this->awardService = $awardService;
        $this->employeeService = $employeeService;
        // $this->holidayEventService = $holidayEventService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $awards = $this->awardService->getTrashedItem();
        } else {
            $awards = $this->awardService->awardEmployeeFilter($request);
        }

        $rowCount = $this->awardService->getRowCount();
        $trashedCount = $this->awardService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($awards)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="award_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->editColumn('employee_id', function ($row) {
                    return $row->employee_id ?? 'Employee ID is not specified';
                })
                ->editColumn('employee', function ($row) {
                    return $row->employee_name ?? 'Employee is not specified';
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type = '';
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

                    if (auth()->user()->can('hrm_awards_update')) {
                        $html .= '<a href="'.route('hrm.awards.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_awards_update" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_awards_delete')) {
                        $html .= '<a href="'.route('hrm.awards.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_awards" title="Delete">'.$icon2.'</a>';
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
        $employees = $this->employeeService->employeeActiveListWithId();

        return view('hrm::awards.index', compact('employees'));
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
    public function store(CreateAwardRequest $request)
    {
        $department = $this->awardService->store($request->validated());

        return response()->json('Award created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $award = $this->awardService->find($id);
        $employees = $this->employeeService->activeEmployee();

        return view('hrm::awards.ajax_views.edit', compact('award', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateAwardRequest $request, $id)
    {
        $award = $this->awardService->update($request->validated(), $id);

        return response()->json('Award updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $award = $this->awardService->trash($id);
        if ($award) {
            return response()->json('Award removed successfully');
        }
    }

    public function permanentDelete($id)
    {
        $award = $this->awardService->permanentDelete($id);
        if ($award) {
            return response()->json('Award is permanently deleted successfully');
        }
    }

    public function restore($id)
    {
        $award = $this->awardService->restore($id);

        return response()->json('Award restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->award_id)) {
            if ($request->action_type == 'move_to_trash') {
                $award = $this->awardService->bulkTrash($request->award_id);

                return response()->json('Award are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $award = $this->awardService->bulkRestore($request->award_id);

                return response()->json('Award are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $award = $this->awardService->bulkPermanentDelete($request->award_id);

                return response()->json('Award are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }
}
