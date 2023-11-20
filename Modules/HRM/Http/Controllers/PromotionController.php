<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Section;
use Modules\HRM\Entities\SubSection;
use Modules\HRM\Http\Requests\Promotion\CreatePromotionRequest;
use Modules\HRM\Http\Requests\Promotion\UpdatePromotionRequest;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\PromotionServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Modules\HRM\Interface\SubSectionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class PromotionController extends Controller
{
    public function __construct(
        private EmployeeServiceInterface $employeeService,
        private PromotionServiceInterface $promotionService,
        private HrmDepartmentServiceInterface $departmentService,
        private SectionServiceInterface $sectionService,
        private DesignationServiceInterface $designationService,
        private SubSectionServiceInterface $subSectionService
    ) {
    }

    public function index(Request $request)
    {
        $employees = $this->employeeService->employeeActiveListWithId();
        $sections = $this->sectionService->sectionSelectedAndSortListWithId();
        $departments = $this->departmentService->departmentSelectedAndSortListWithId();
        $designations = $this->designationService->designationSelectedAndSortListWithId();
        $admin_users = DB::connection('hrm')->table('employees')->where('employee_type', 1)->get();

        if ($request->showTrashed == 'true') {
            $employee = $this->promotionService->getTrashedItem();
        } else {
            $employee = $this->promotionService->promoteEmployeeListAfterSort($request);
        }
        $trashedCount = $this->promotionService->getTrashedCount();

        if ($request->ajax()) {
            $rowCount = $employee->count();
            return DataTables::of($employee)
                ->addIndexColumn()
                ->editColumn('promoted_date', function ($row) {
                    return date(config('hrm.date_format'), strtotime($row->promoted_date)) ?? ' ';
                })
                ->editColumn('promoted_by', function ($row) {
                    return $row?->approver?->name;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="promotion_id[]" value="' . $row->promotion_id . '" class="mt-2 check1">
                            </div>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    if ($row->trashed()) {
                        if (auth()->user()->can('hrm_promotion_update')) {
                            $html .= '<a href="' . route('hrm.promotion.restore', $row->promotion_id) . '" class="action-btn c-edit restore" id="restore" title="restore"><i class="fa-solid fa-recycle"></i></a>';
                            $html .= '<a href="' . route('hrm.promotion.permanent.delete', $row->promotion_id) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                        }
                    } else {
                        if (auth()->user()->can('hrm_promotion_delete')) {
                            $html .= '<a href="' . route('hrm.promotions.edit', $row->promotion_id) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                            $html .= '<a href="' . route('hrm.promotions.destroy', $row->promotion_id) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                        }
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['check', 'action'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::promotion.index', compact('employees', 'departments', 'designations', 'sections', 'admin_users'));
    }

    public function create()
    {
        $employee = $this->employeeService->employeeActiveListWithId();
        $departments = $this->departmentService->departmentSelectedAndSortListWithId();
        $designations = $this->designationService->designationSelectedAndSortListWithId();
        $sections = $this->sectionService->sectionSelectedAndSortListWithId();
        // $admin_type_employee = $this->employeeService->adminTypeEmployee();
        $mainDb = config('database.connections.mysql.database');
        $hrmDb = config('database.connections.hrm.database');
        $admin_type_employee = DB::connection('hrm')->table('employees')->where('employee_type', 1)->get();

        return view('hrm::promotion.ajax_view.create', compact('employee', 'designations', 'departments', 'admin_type_employee', 'sections'));
    }

    public function getHrmDepartment(Request $request)
    {
        $sections = Section::where('hrm_department_id', $request->id)->get();
        if ($sections->count() == 0) {
            $str_to_send = "<option value=''>>--No Section at database--<</option>";
        } else {
            $str_to_send = "<option value=''>>--Choose One--<</option>";
        }
        foreach ($sections as $section) {
            $str_to_send .= "<option value='$section->id'>$section->name</option>";
        }
        echo $str_to_send;
    }

    public function getSection(Request $request)
    {
        $subSections = SubSection::where('section_id', $request->id)->get();
        if ($subSections->count() == 0) {
            $str_to_send = "<option value=''>>--No Section at database--<</option>";
        } else {
            $str_to_send = "<option value=''>>--Choose One--<</option>";
        }
        foreach ($subSections as $subSection) {
            $str_to_send .= "<option value='$subSection->id'>$subSection->name</option>";
        }
        echo $str_to_send;
    }

    public function store(CreatePromotionRequest $request)
    {
        $this->promotionService->store($request->validated());
        return response()->json('Promotion created successfully');
    }

    public function show($id)
    {
        return view('hrm::show');
    }

    public function edit($id)
    {
        $employees = $this->employeeService->employeeActiveListWithId();
        $promotional_employee = $this->promotionService->find($id);
        $designation = $this->designationService->designationSelectedAndSortListWithId();
        $departments = $this->departmentService->departmentSelectedAndSortListWithId();
        $sections = $this->sectionService->sectionSelectedAndSortListWithId();
        $subSections = $this->subsectionService->subsectionSelectedAndSortListWithId();

        $mainDb = config('database.connections.mysql.database');
        $hrmDb = config('database.connections.hrm.database');
        $admin_type_employee = DB::connection('hrm')->table('employees')->where('employee_type', 1)->get();
        return view('hrm::promotion.ajax_view.edit', compact('employees', 'promotional_employee', 'designation', 'departments', 'sections', 'subSections', 'admin_type_employee'));
    }

    public function update(UpdatePromotionRequest $request, $id)
    {
        $this->promotionService->update($request->validated(), $id);
        return response()->json('Promotion updated successfully');
    }

    public function destroy($id)
    {
        $paymentType = $this->promotionService->trash($id);
        return response()->json('Promotion Type deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->promotion_id)) {
            if ($request->action_type == 'move_to_trash') {
                $promotion = $this->promotionService->bulkTrash($request->promotion_id);
                return response()->json('Promotion are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $promotion = $this->promotionService->bulkRestore($request->promotion_id);
                return response()->json('Promotion are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $promotion = $this->promotionService->bulkPermanentDelete($request->promotion_id);

                return response()->json('Promotion are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function permanentDelete($id)
    {
        $promotion = $this->promotionService->permanentDelete($id);
        return response()->json('promotion is permanently deleted successfully');
    }

    public function restore($id)
    {
        $promotion = $this->promotionService->restore($id);
        return response()->json('Promotion restored successfully');
    }
}
