<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Http\Requests\Visit\CreateVisitRequest;
use Modules\HRM\Http\Requests\Visit\UpdateVisitRequest;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\VisitServiceInterface;
use Yajra\DataTables\DataTables;

class VisitController extends Controller
{
    private $visitService;

    protected $employeeService;

    public function __construct(VisitServiceInterface $visitService, EmployeeServiceInterface $employeeService)
    {
        $this->visitService = $visitService;
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {

        if ($request->showTrashed == 'true') {
            $visits = $this->visitService->getTrashedItem();
        } else {
            $visits = $this->visitService->all();
        }
        $rowCount = $this->visitService->getRowCount();
        $trashedCount = $this->visitService->getTrashedCount();
        $employees = $this->employeeService->employeeActiveListWithId();
        if ($request->ajax()) {
            return DataTables::of($visits)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {

                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="visit_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->editColumn('description', function ($row) {
                    $description = str_replace('&nbsp;', '', strip_tags($row->description));

                    return $description;
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $action3 = '';
                    $type = '';
                    $icon1 = '';
                    $icon2 = '';
                    $icon3 = '';
                    if ($row->trashed()) {
                        $action1 = 'restore';
                        $action2 = 'permanent-delete';
                        $action3 = 'show';
                        $type = 'restore';
                        $icon1 = '<i class="fa-solid fa-recycle"></i>';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i>';
                        $icon3 = '<i class="fa-solid fa-eye"></i>';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $action3 = 'show';
                        $type = 'Edit';
                        $icon1 = '<span class="fas fa-edit"></span></a>';
                        $icon2 = '<span class="fas fa-trash"></span>';
                        $icon3 = '<span class="fas fa-eye"></span>';
                    }

                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->can('hrm_visit_delete')) {
                        $html .= '<a href="'.route('hrm.visit.'.$action3, $row->id).'" class="action-btn c-view" id="view" title="view">'.$icon3.'</a>';
                    }
                    if (auth()->user()->can('hrm_visit_update')) {
                        $html .= '<a href="'.route('hrm.visit.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_visit" title="'.$type.'">'.$icon1.'</a>';
                    }
                    if (auth()->user()->can('hrm_visit_delete')) {
                        $html .= '<a href="'.route('hrm.visit.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_visit" title="Delete">'.$icon2.'</a>';
                    }

                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'photo'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::visits.index', compact('visits', 'employees'));
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
            return response()->json('Visit created successfully');
        }
    }

    /**
     * Show the specified resource.
     *
     * @return Renderable
     */
    public function show(int $id)
    {
        $visit = $this->visitService->find($id);

        return view('hrm::visits.ajax_views.show', compact('visit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $visit = $this->visitService->find($id);

        return view('hrm::visits.ajax_views.edit', compact('visit'));
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
        $this->visitService->trash($id);

        return response()->json('Visit deleted successfully!');
    }

    public function permanentDelete($id)
    {
        $visit = $this->visitService->permanentDelete($id);

        return response()->json('Visit is permanently deleted successfully');
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

        return response()->json('Visit restored successfully');
    }

    // Bulk Action
    public function bulkAction(Request $request)
    {
        if (isset($request->visit_id)) {
            if ($request->action_type == 'move_to_trash') {
                $notice = $this->visitService->bulkTrash($request->visit_id);

                return response()->json('Notice are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $notice = $this->visitService->bulkRestore($request->visit_id);

                return response()->json('Notice are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $notice = $this->visitService->bulkPermanentDelete($request->visit_id);

                return response()->json('Notice are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function visitFileDelete(Request $request, $id)
    {
        $visit = $this->visitService->visitFileDelete($id);
        if ($visit) {
            return response()->json('Visit File Deleted successfully!');
        }
    }
}
