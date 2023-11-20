<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\Enums\BloodGroups;
use Modules\Core\Enums\Countries;
use Modules\Core\Enums\MaritalStatus;
use Modules\Core\Interface\BdDistrictServiceInterface;
use Modules\Core\Interface\BdDivisionServiceInterface;
use Modules\Core\Interface\BdUnionServiceInterface;
use Modules\Core\Interface\BdUpazilaServiceInterface;
use Modules\HRM\Entities\Designation;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Exports\EmployeeList;
use Modules\HRM\Http\Requests\Employee\CreateEmployeeRequest;
use Modules\HRM\Http\Requests\Employee\UpdateEmployeeRequest;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\GradeServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Modules\HRM\Interface\ShiftServiceInterface;
use Modules\HRM\Interface\SubSectionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function __construct(
        private CommonServiceInterface $commonService,
        private EmployeeServiceInterface $employeeService,
        private HrmDepartmentServiceInterface $departmentService,
        private DesignationServiceInterface $designationService,
        private BdUnionServiceInterface $bdUnionService,
        private BdUpazilaServiceInterface $bdUpazilaService,
        private BdDistrictServiceInterface $districtService,
        private BdDivisionServiceInterface $divisionService,
        private SectionServiceInterface $sectionService,
        private SubSectionServiceInterface $subSectionService,
        private GradeServiceInterface $gradeService,
        private ShiftServiceInterface $shiftService,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = $this->employeeService->getItemByFilter($request);
            $rowCount = $this->employeeService->getRowCount();

            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="employee_id[]" value="' . $row->id . '" class="mt-2 check1">
                    </div>';

                    return $html;
                })
                ->editColumn('departmentID', function ($row) {
                    return $row->hrmDepartment->name ?? 'Designation is not Specified';
                })
                ->editColumn('section_id', function ($row) {
                    return $row->section->name ?? 'Section is not Specified';
                })
                ->editColumn('designationID', function ($row) {
                    return $row->designation->name ?? 'Designation is not Specified';
                })
                ->editColumn('address', function ($row) {
                    $address = Str::limit($row?->present_address, 30, '...');
                    $full_address = $row?->present_address;

                    return '<span title="' . $full_address . '">' . $address;
                })
                ->editColumn('grade_id', function ($row) {
                    return $row->grade->name ?? 'Grade is not Specified';
                })
                ->editColumn('joining', function ($row) {
                    return $row->joining_date ?? 'Joining Date is not Specified';
                })
                ->editColumn('employment_status', function ($row) {
                    if ($row->employment_status == 1 || $row->employment_status == '' || $row->employment_status == null) {
                        return '✅ Active';
                    } elseif ($row->employment_status == 2) {
                        $date = date('d-m-Y', strtotime($row->resign_date));

                        return "🛑 Resigned ({$date})";
                    } elseif ($row->employment_status == 3) {
                        $date = date('d-m-Y', strtotime($row->left_date));

                        return "⏹ Left ({$date})";
                    } elseif ($row->employment_status == 4) {
                        $date = date('d-m-Y', strtotime($row->termination_date));

                        return "⏏️ Terminated ({$date})";
                    }
                })
                ->editColumn('photo', function ($row) {
                    return $this->commonService->showAvatarImage('uploads/employees/', $row->photo);
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type1 = '';
                    $type2 = '';
                    $icon1 = '';
                    $icon2 = '';
                    if ($row->trashed()) {
                        $action1 = 'restore';
                        $action2 = 'permanent-delete';
                        $type1 = 'Restore';
                        $type2 = 'Permanent Delete';
                        $icon1 = '<i class="fa-solid fa-recycle"></i> ';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i> ';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $type1 = 'Edit';
                        $type2 = 'Delete';
                        $icon1 = '<i class="far fa-edit"></i> ';
                        $icon2 = '<i class="far fa-trash-alt"></i> ';
                    }
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('hrm_employees_update')) {

                        if (!$row->trashed()) {

                            if ($row->resign_date != null) {
                                $html .= '<a class="dropdown-item" style="border-bottom: 1px solid #665f5f;" href="' . route('hrm.employee.view', $row->id) . '" id="view"><i class="fa-light fa-eye"></i> View</a>';
                                $html .= '<a class="dropdown-item resign" href="' . route('hrm.employee.resign', $row->id) . '" id="resign"><i class="fa-light fa-pencil"></i> Edit Resign</a>';
                                $html .= '<a class="dropdown-item" href="' . route('hrm.employee.active', $row->id) . '" id="activeEmp"><i class="fa-solid fa-toggle-on"></i> Active</a>';
                            } elseif ($row->left_date != null) {
                                $html .= '<a class="dropdown-item" style="border-bottom: 1px solid #665f5f;" href="' . route('hrm.employee.view', $row->id) . '" id="view"><i class="fa-light fa-eye"></i> View</a>';
                                $html .= '<a class="dropdown-item" style="border-bottom:1px solid #ddd;" href="' . route('hrm.employee.active', $row->id) . '" id="activeEmp"><i class="fa-solid fa-toggle-on"></i> Active</a>';
                                $html .= '<a class="dropdown-item" target="_blank" href="' . route('hrm.employee.left-letter', ['type' => 'first', 'id' => $row->id]) . '"><i class="fa-light fa-pencil"></i> First Letter</a>';
                                $html .= '<a class="dropdown-item" target="_blank" href="' . route('hrm.employee.left-letter', ['type' => 'second', 'id' => $row->id]) . '"><i class="fa-light fa-pencil"></i> Second Letter</a>';
                                $html .= '<a class="dropdown-item" target="_blank" href="' . route('hrm.employee.left-letter', ['type' => 'third', 'id' => $row->id]) . '"resign"><i class="fa-light fa-pencil"></i> Third Letter</a>';
                                $html .= '<a class="dropdown-item left" href="' . route('hrm.employee.left', $row->id) . '" id="left"><i class="fa-regular fa-arrow-right-from-bracket"></i> Edit Left</a>';
                            } else {
                                $html .= '<a class="dropdown-item" style="border-bottom: 1px solid #665f5f;" href="' . route('hrm.employee.view', $row->id) . '" id="view"><i class="fa-light fa-eye"></i> View</a>';
                                $html .= '<a class="dropdown-item ' . $action1 . '" href="' . route('hrm.employees.' . $action1, $row->id) . '" id="' . $action1 . '">' . $icon1 . $type1 . '</a>';
                                $html .= '<a class="dropdown-item" target="_blank" href="' . route('hrm.employee.id.card', $row->id) . '" id="id_card"><i class="fa-light fa-id-card"></i> ID CARD</a>';
                                $html .= '<a class="dropdown-item resign" href="' . route('hrm.employee.resign', $row->id) . '" id="resign"><i class="fa-light fa-pen-nib"></i> Resign</a>';
                                $html .= '<a class="dropdown-item left" href="' . route('hrm.employee.left', $row->id) . '" id="left"><i class="fa-light fa-arrow-right-from-bracket"></i> Left</a>';
                            }
                        }
                    }

                    if (auth()->user()->can('hrm_employees_delete')) {
                        if ($row->trashed()) {
                            $html .= '<a class="dropdown-item ' . $action1 . ' " href="' . route('hrm.employees.' . $action1, $row->id) . '" id="' . $action1 . '">' . $icon1 . $type1 . '</a>';
                        }
                        $html .= '<a class="dropdown-item ' . $action2 . ' delete" href="' . route('hrm.employees.' . $action2, $row->id) . '" id="' . $action2 . '">' . $icon2 . $type2 . '</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'employment_status', 'photo', 'address', 'designationID', 'departmentID', 'joining'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::employees.index');
    }

    public function trashIndex(Request $request)
    {
        // $trashedItem = $this->employeeService->getTrashedItem();
        // $rowCount = $this->employeeService->getRowCount();
        $trashedCount = $this->employeeService->getTrashedCount();
        $trashedItem = $this->employeeService->trashedEmployeeBuilder();

        if ($request->ajax()) {

            if ($request->section_id) {
                $employees = $trashedItem->where('section_id', $request->section_id)->get();
            }
            if ($request->hrm_department_id) {
                $employees = $trashedItem->where('hrm_department_id', $request->hrm_department_id)->get();
            }
            if ($request->shift_id) {
                $employees = $trashedItem->where('shift_id', $request->shift_id)->get();
            }
            if ($request->grade_id) {
                $employees = $trashedItem->where('grade_id', $request->grade_id)->get();
            }
            $start_date = date('Y-m-d', strtotime('-1month'));
            $end_date = date('Y-m-d');

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $start_date = $form_date;
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $trashedItem->whereBetween('employees.deleted_at', [$form_date, $to_date]); // Final
                $end_date = $to_date;
            }
            $rowCount = $trashedItem->count();
            $employees = $trashedItem->get();

            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="employee_id[]" value="' . $row->id . '" class="mt-2 check1">
                    </div>';

                    return $html;
                })
                ->editColumn('section_id', function ($row) {
                    return $row->section->name ?? 'Section is not Specified';
                })
                ->editColumn('address', function ($row) {
                    // $address = Str::limit($row?->present_address, 30, '...');
                    $address = substr($row->present_address, 0, 40) . ' ...' ?? '';
                    $full_address = $row?->present_address;

                    return '<span title="' . $full_address . '">' . $address;
                })
                ->editColumn('designation_id', function ($row) {
                    return $row->designation->name ?? 'Designation is not Specified';
                })
                ->editColumn('grade_id', function ($row) {
                    return $row->grade->name ?? 'Grade is not Specified';
                })
                ->editColumn('photo', function ($row) {
                    return $this->commonService->showAvatarImage('uploads/employees/', $row->photo);
                })
                ->editColumn('hrm_department_id', function ($row) {
                    return $row->hrmDepartment->name ?? 'HrmDepartment is not Specified';
                })
                ->editColumn('section_id', function ($row) {
                    return $row->section->name ?? 'Section is not Specified';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    if ($row->trashed()) {
                        $html .= '<a href="' . route('hrm.employees.restore', $row->id) . '" class="action-btn c-edit restore" id="restore" title="restore"><i class="fa-solid fa-recycle"></i></a>';
                        $html .= '<a href="' . route('hrm.employees.permanent-delete', $row->id) . '" class="action-btn c-delete delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'photo', 'address'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::trashed_employees.index');
    }

    public function employeeView($id)
    {
        $employee = Employee::find($id);

        return view('hrm::employees.ajax_views.view', compact('employee'));
    }

    public function idCard($id)
    {
        $generalSettings = GeneralSetting::first();
        $employee = Employee::find($id);

        return view('hrm::employees.ajax_views.id-card-view', compact('employee', 'generalSettings'));
    }

    /** Create's a new employee */
    public function create(Request $request)
    {
        $departments = $this->departmentService->all();
        $designations = $this->designationService->all();
        $unions = $this->bdUnionService->all([]);
        $bdUpazila = $this->bdUpazilaService->all([]);
        $districts = $this->districtService->all([]);
        $divisions = $this->divisionService->all();
        $sections = $this->sectionService->all();
        $subsections = $this->subSectionService->all();
        $grades = $this->gradeService->all();
        $shifts = $this->shiftService->all();
        $countries = Countries::cases();
        $marital_status = MaritalStatus::cases();
        $blood_groups = BloodGroups::cases();
        $last_inserted_employee = $this->employeeService->getLastInsertedEmployee();

        return view(
            'hrm::employees.create',
            compact(
                'designations',
                'departments',
                'unions',
                'sections',
                'subsections',
                'grades',
                'bdUpazila',
                'divisions',
                'districts',
                'shifts',
                'countries',
                'marital_status',
                'blood_groups',
                'last_inserted_employee',
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(CreateEmployeeRequest $request)
    {
        $employee = $this->employeeService->store($request->validated());

        return response()->json('Employee Created Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $employee = $this->employeeService->find($id);
        $departments = $this->departmentService->all();
        $designations = $this->designationService->all();

        $unions = $this->bdUnionService->all(['upazila_id' => $employee->present_upazila_id])->get(['id', 'upazilla_id', 'name']);
        $bdUpazila = $this->bdUpazilaService->all([]);
        $districts = $this->districtService->all([]);
        $divisions = $this->divisionService->all();
        $sections = $this->sectionService->all();
        $subsections = $this->subSectionService->all();
        $grades = $this->gradeService->all();
        $shifts = $this->shiftService->all();
        $countries = Countries::cases();
        $marital_status = MaritalStatus::cases();
        $blood_groups = BloodGroups::cases();

        return view(
            'hrm::employees.edit',
            compact(
                'employee',
                'designations',
                'departments',
                'unions',
                'sections',
                'subsections',
                'grades',
                'bdUpazila',
                'divisions',
                'districts',
                'shifts',
                'countries',
                'marital_status',
                'blood_groups',
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = $this->employeeService->update($request->validated(), $id);

        return response()->json('Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $employee = $this->employeeService->trash($id);
        $employee->save();

        return response()->json('Employee deleted successfully');
    }

    /**
     * Permanent Delete the employee Items
     *
     * @param  int  $id
     * @return Renderable
     */
    public function permanentDelete($id)
    {
        $employee = $this->employeeService->permanentDelete($id);

        return response()->json('Employee is permanently deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function restore($id)
    {
        $employee = $this->employeeService->restore($id);

        return response()->json('Employee restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function bulkAction(Request $request)
    {
        if (isset($request->employee_id)) {
            if ($request->action_type == 'move_to_trash') {
                $employee = $this->employeeService->bulkTrash($request->employee_id);

                return response()->json('Employee are deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $employee = $this->employeeService->bulkRestore($request->employee_id);

                return response()->json('Employee are restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $employee = $this->employeeService->bulkPermanentDelete($request->employee_id);

                return response()->json('Employee are permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function masterList(request $request)
    {
        abort_if(!auth()->user()->can('hrm_master_list_index'), 403, 'Access Forbidden');
        $designations = Designation::get();
        if ($request->ajax()) {
            return DataTables::of($designations)
                ->addIndexColumn()
                ->addColumn('designation', function ($row) {
                    return $row->name ?? 'No Designation';
                })
                ->addColumn('totalPerson', function ($row) {
                    $total = Employee::where('section_id', $row->id)->get();
                    $total_result = count($total);

                    return $total_result ?? 'No Person No';
                })
                ->rawColumns(['designation', 'totalPerson'])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::master_list_by_designation.index');
    }

    public function employeeExport(Request $request)
    {
        $title = 'My Page Title';
        $employees = $this->employeeService->employeeList($request);

        return Excel::download(new EmployeeList($employees, $title), 'Employee-List.xlsx');
    }

    public function printCount(Request $request)
    {
        $employee = Employee::find($request->id);
        $employee->print_count = $employee->print_count + 1;
        $a = $employee->save();
    }
}
