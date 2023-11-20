<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Entities\BdDistrict;
use Modules\Core\Entities\BdDivision;
use Modules\Core\Entities\BdUnion;
use Modules\Core\Entities\BdUpazila;
use Modules\Core\Interface\BdDistrictServiceInterface;
use Modules\Core\Interface\BdDivisionServiceInterface;
use Modules\Core\Interface\BdUnionServiceInterface;
use Modules\Core\Interface\BdUpazilaServiceInterface;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\GradeServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Modules\HRM\Interface\SettingServiceInterface;
use Modules\HRM\Interface\ShiftServiceInterface;
use Modules\HRM\Interface\SubSectionServiceInterface;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class ResignAndLeftEmployeeController extends Controller
{
    private const EMPLOYEE_STATUS_ACTIVE = 1;

    private const EMPLOYEE_STATUS_RESIGNED = 2;

    private const EMPLOYEE_STATUS_LEFT = 3;

    private $commonService;

    private $employeeService;

    private $departmentService;

    private $designationService;

    private $bdUnionService;

    private $bdUpazilaService;

    private $districtService;

    private $divisionService;

    private $sectionService;

    private $subSectionService;

    private $gradeService;

    private $shiftService;

    private $settingService;

    public function __construct(
        CommonServiceInterface $commonService,
        EmployeeServiceInterface $employeeService,
        HrmDepartmentServiceInterface $departmentService,
        DesignationServiceInterface $designationService,
        BdUnionServiceInterface $bdUnionService,
        BdUpazilaServiceInterface $bdUpazilaService,
        BdDistrictServiceInterface $districtService,
        BdDivisionServiceInterface $divisionService,
        SectionServiceInterface $sectionService,
        SubSectionServiceInterface $subSectionService,
        GradeServiceInterface $gradeService,
        ShiftServiceInterface $shiftService,
        SettingServiceInterface $settingService,
    ) {
        $this->commonService = $commonService;
        $this->employeeService = $employeeService;
        $this->departmentService = $departmentService;
        $this->designationService = $designationService;
        $this->bdUnionService = $bdUnionService;
        $this->bdUpazilaService = $bdUpazilaService;
        $this->districtService = $districtService;
        $this->divisionService = $divisionService;
        $this->sectionService = $sectionService;
        $this->subSectionService = $subSectionService;
        $this->gradeService = $gradeService;
        $this->shiftService = $shiftService;
        $this->settingService = $settingService;
    }

    /**
     * Resign And Left Employees.
     *
     * @return Renderable
     */
    public function resignIndex(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_resigned_employee_index'), 403, 'Access Forbidden');
        $resignEmployeeBuilder = $this->employeeService->resignEmployeeBuilder();
        if ($request->ajax()) {
            if ($request->designation_id) {
                $employees = $resignEmployeeBuilder->where('designation_id', $request->designation_id)->get();
            }
            if ($request->hrm_department_id) {
                $employees = $resignEmployeeBuilder->where('hrm_department_id', $request->hrm_department_id)->get();
            }
            $start_date = date('Y-m-d', strtotime('-1month'));
            $end_date = date('Y-m-d');

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $start_date = $form_date;
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $resignEmployeeBuilder->whereBetween('employees.resign_date', [$form_date, $to_date]); // Final
                $end_date = $to_date;
            }
            $rowCount = $resignEmployeeBuilder->count();
            $employees = $resignEmployeeBuilder;

            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="employee_id[]" value="' . $row->id . '" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->editColumn('section', fn ($row) => $row->name)
                ->editColumn('designation', fn ($row) => $row->name)
                ->editColumn('type_status', function ($row) {
                    if ($row->resign_date != null) {
                        $status = '<div class="text-center"><span class="badge bg-danger text-white">Resigned</span><br>(' . $row->resign_date . ')</div>';
                    } elseif ($row->left_date != null) {
                        $status = '<div class="text-center"><span class="badge bg-danger text-white"> Left </span><br>(' . $row->left_date . ')</div>';
                    } else {
                        $status = '<div class="text-center"><span class="badge bg-primary text-white">Active</span></div>';
                    }

                    return $status;
                })
                ->editColumn('photo', function ($row) {
                    return $this->commonService->showAvatarImage('uploads/employees/', $row->photo);
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('hrm_employees_update')) {
                        if (!$row->trashed()) {
                            if ($row->resign_date != null) {
                                $html .= '<a class="dropdown-item" style="border-bottom:1px solid #ddd;" href="' . route('hrm.employee.active', $row->id) . '" id="activeEmp"><i class="fa-solid fa-toggle-on text-success"></i> Activate</a>';
                                $html .= '<a class="dropdown-item resign" href="' . route('hrm.employee.resign', $row->id) . '" id="resign"><i class="fa-solid fa-pencil"></i> Edit Resign</a>';
                            }
                        }
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'type_status', 'photo', 'address'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::resigned_employees.index');
    }

    public function leftIndex(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_left_employee_index'), 403, 'Access Forbidden');
        $leftEmployeeBuilder = $this->employeeService->leftEmployeeBuilder();
        if ($request->ajax()) {
            if ($request->designation_id) {
                $employees = $leftEmployeeBuilder->where('designation_id', $request->designation_id);
            }
            if ($request->hrm_department_id) {
                $employees = $leftEmployeeBuilder->where('hrm_department_id', $request->hrm_department_id);
            }
            $start_date = date('Y-m-d', strtotime('-1month'));
            $end_date = date('Y-m-d');

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $start_date = $form_date;
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $leftEmployeeBuilder->whereBetween('employees.left_date', [$form_date, $to_date]); // Final
                $end_date = $to_date;
            }
            $rowCount = $leftEmployeeBuilder->count();
            $employees = $leftEmployeeBuilder;

            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="employee_id[]" value="' . $row->id . '" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->editColumn('type_status', function ($row) {
                    if ($row->resign_date != null) {
                        $status = '<div class="text-center"><span class="badge bg-danger text-white">Resigned</span><br>(' . $row->resign_date . ')</div>';
                    } elseif ($row->left_date != null) {
                        $status = '<div class="text-center"><span class="badge bg-danger text-white"> Left </span><br>(' . $row->left_date . ')</div>';
                    } else {
                        $status = '<div class="text-center"><span class="badge bg-primary text-white">Active</span></div>';
                    }

                    return $status;
                })
                ->editColumn('photo', function ($row) {
                    return $this->commonService->showAvatarImage('uploads/employees/', $row->photo);
                })
                ->editColumn('section', fn ($row) => $row->name)
                ->editColumn('designation', fn ($row) => $row->name)
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
                        $icon1 = '';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i> ';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $type1 = 'Edit';
                        $type2 = 'Delete';
                        $icon1 = '<i class="far fa-edit text-primary"></i> ';
                        $icon2 = '<i class="far fa-trash-alt text-primary"></i> ';
                    }

                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('hrm_employees_update')) {

                        if (!$row->trashed()) {

                            if ($row->resign_date != null) {
                                $html .= '<a class="dropdown-item" style="border-bottom:1px solid #ddd;" href="' . route('hrm.employee.active', $row->id) . '" id="activeEmp"><i class="fa-solid fa-toggle-on text-success"></i> Activate</a>';
                                $html .= '<a class="dropdown-item resign" href="' . route('hrm.employee.resign', $row->id) . '" id="resign"><i class="fa-solid fa-pencil"></i> Edit Resign</a>';
                            } elseif ($row->left_date != null) {
                                $html .= '<a class="dropdown-item" style="border-bottom:1px solid #ddd;" href="' . route('hrm.employee.active', $row->id) . '" id="activeEmp"><i class="fa-solid fa-toggle-on text-success"></i> Active</a>';
                                $html .= '<a href="' . route('hrm.employee.left-letter.first', $row->id) . '" class="dropdown-item openModal" > <i class="fa-solid fa-pencil"></i> First Letter</a>';

                                $html .= '<a class="dropdown-item secondModal" href="' . route('hrm.employee.left-letter.second', $row->id) . '"><i class="fa-solid fa-pencil"></i> Second Letter</a>';

                                $html .= '<a class="dropdown-item thirdModal" href="' . route('hrm.employee.left-letter.third', $row->id) . '"><i class="fa-solid fa-pencil"></i> Third Letter</a>';
                                // $html .= '<a class="dropdown-item" href="' . route('hrm.employee.left-letter', ['type' => 'third', 'id' => $row->id]) . '"><i class="fa-solid fa-pencil"></i> Third Letter</a>';
                                $html .= '<a class="dropdown-item left" style="border-top:1px solid #ddd;" href="' . route('hrm.employee.left', $row->id) . '" id="left"><i class="fa-solid fa-arrow-right-from-bracket"></i> Edit</a>';
                            }
                        } else {
                            $html .= '<a class="dropdown-item restore" href="' . route('hrm.employees.restore', $row->id) . '" id="restore"><i class="fa-solid fa-recycle"></i> Restore</a>';
                        }
                    }

                    // if (auth()->user()->can('hrm_employees_delete')) {
                    //     $html .= '<a class="dropdown-item ' . $action2 . ' delete" href="' . route('hrm.employees.' . $action2, $row->id) . '" id="' . $action2 . '">' . $icon2 . $type2 . '</a>';
                    // }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'type_status', 'photo', 'address', 'permanent_address'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::left_employees.index');
    }

    public function firstLetter(Request $request, $id)
    {
        abort_if(!auth()->user()->can('hrm_left_employee_index'), 403, 'Access Forbidden');
        $left_employee = Employee::find($id);

        return view('hrm::left_employees.letter.letter_date_modal.first_modal', compact('left_employee'));
    }

    public function secondLetter(Request $request, $id)
    {
        abort_if(!auth()->user()->can('hrm_left_employee_index'), 403, 'Access Forbidden');
        $employee = Employee::find($id);

        return view('hrm::left_employees.letter.letter_date_modal.second_modal', compact('employee'));
    }

    public function thirdLetter(Request $request, $id)
    {
        abort_if(!auth()->user()->can('hrm_left_employee_index'), 403, 'Access Forbidden');
        $employee = Employee::find($id);

        return view('hrm::left_employees.letter.letter_date_modal.third_modal', compact('employee'));
    }

    public function employeeActive($id)
    {
        abort_if(!auth()->user()->can('hrm_resigned_employee_index'), 403, 'Access Forbidden');
        $employee = $this->employeeService->find($id);
        if ($employee->resign_date != null && $employee->employment_status > 0) {
            $employee->resign_date = null;
            $employee->employment_status = EmploymentStatus::Active;
        }
        if ($employee->left_date != null && $employee->employment_status > 0) {
            $employee->left_date = null;
            $employee->employment_status = EmploymentStatus::Active;
        }
        $employee->save();

        return response()->json('Employee activate successfully');
    }

    /**
     * Left Employee
     *
     * @return Renderable
     */
    public function left(Request $request, $id)
    {
        abort_if(!auth()->user()->can('hrm_left_employee_index'), 403, 'Access Forbidden');
        $employee = $this->employeeService->find($id);

        return view('hrm::employees.ajax_views.left', compact('employee'));
    }

    /**
     * Print Left Letter.
     *
     * @return Renderable
     */
    public function printLeftLetter(Request $request, $type, $id)
    {

        // $settings = $this->settingService->getSettingsType('general');
        // $employee = Employee::find($id);
        $employee = $this->employeeService->find($id);
        $BdDistrict = BdDistrict::where('id', $employee->permanent_district_id)->first()->name;
        $BdUpazila = BdUpazila::where('id', $employee->permanent_upazila_id)->first()->name;
        $BdUnion = BdUnion::where('id', $employee->permanent_union_id)->first()->name;
        $BdDivision = BdDivision::where('id', $employee->permanent_division_id)->first()->name;

        $date = date('d-m-Y', strtotime($request->first_date));
        $secondDate = date('d-m-Y', strtotime($request->second_date));
        $thirdDate = date('d-m-Y', strtotime($request->third_date));

        if ($type == 'first') {

            return view('hrm::left_employees.letter.letter_one', compact('employee', 'date', 'BdDistrict', 'BdUpazila', 'BdUnion', 'BdDivision'));

            // $pdf = PDF::loadView('hrm::left_employees.letter.letter_one', compact('employee', 'settings',));
            // $pdf->stream('custom-name-goes-here.pdf');
        } elseif ($type == 'second') {

            return view('hrm::left_employees.letter.letter_two', compact('employee', 'date', 'secondDate', 'BdDistrict', 'BdUpazila', 'BdUnion', 'BdDivision'));

            // $pdf = PDF::loadView('hrm::left_employees.letter.letter_two', compact('employee', 'settings',));
            // $pdf->stream('custom-name-goes-here.pdf');
        } elseif ($type == 'third') {

            return view('hrm::left_employees.letter.letter_third', compact('employee', 'date', 'secondDate', 'thirdDate', 'BdDistrict', 'BdUpazila', 'BdUnion', 'BdDivision'));

            $pdf = PDF::loadView('hrm::left_employees/letters/lefty_letter_three', compact('employee'));
            $pdf->stream('custom-name-goes-here.pdf');
        }

        return view('hrm::left_employees.letter.letter_one', compact('employee', 'date', 'BdDistrict', 'BdUpazila', 'BdUnion', 'BdDivision'));
    }

    /**
     * Resign Employee.
     *
     * @return Renderable
     */
    public function resign(Request $request, $id)
    {
        $employee = $this->employeeService->find($id);

        return view('hrm::employees.ajax_views.resign', compact('employee'));
    }

    /**
     * Manage Employee.
     *
     * @return Renderable
     */
    public function manageEmployee(Request $request, $id)
    {
        if ($request->manageType == 'left') {
            $employee = $this->employeeService->makeLeftEmployee($request, $id);
        } elseif ($request->manageType == 'resign') {
            $employee = $this->employeeService->makeResignedEmployee($request, $id);
        }

        return response()->json('Employee left successfully');
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
}
