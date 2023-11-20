<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Interface\BdDistrictServiceInterface;
use Modules\Core\Interface\BdDivisionServiceInterface;
use Modules\Core\Interface\BdUnionServiceInterface;
use Modules\Core\Interface\BdUpazilaServiceInterface;
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
use Modules\HRM\Transformers\EmployeeResource;
use Modules\HRM\Transformers\TrashEmployeesResource;

class EmployeeController extends Controller
{
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
    }

    public function index(Request $request)
    {
        $employees = $this->employeeService->getItemByFilter($request)->paginate();
        $employeeCount = $this->employeeService->getRowCount();
        $employeesResource = EmployeeResource::collection($employees);

        return response()->json([
            'employee_type' => 'Active',
            'total_employee' => $employeeCount,
            'employees' => $employeesResource,
        ]);
    }

    public function trashIndex(Request $request)
    {
        $trashedCount = $this->employeeService->getTrashedCount();
        $getTrashedItem = $this->employeeService->getTrashedItem();
        $employeesResource = TrashEmployeesResource::collection($getTrashedItem);

        return response()->json([
            'employee_type' => 'TRASH',
            'total_employee' => $trashedCount,
            'employees' => $employeesResource,
        ]);
    }

    public function store(CreateEmployeeRequest $request)
    {
        $employee = $this->employeeService->store($request->validated());

        return response()->json('Employee Created Successfully!');
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = $this->employeeService->update($request->validated(), $id);

        return response()->json('Employee updated successfully');
    }

    public function destroy($id)
    {
        $employee = $this->employeeService->trash($id);
        $employee->save();

        return response()->json('Employee deleted successfully');
    }

    public function permanentDelete($id)
    {
        $employee = $this->employeeService->permanentDelete($id);

        return response()->json('Employee is permanently deleted successfully');
    }

    public function restore($id)
    {
        $employee = $this->employeeService->restore($id);

        return response()->json('Employee restored successfully');
    }

    public function show($id)
    {
        $employee = $this->employeeService->find($id);
        $employeesResource = EmployeeResource::make($employee);

        return response()->json([
            'employees' => $employeesResource,
        ]);
    }

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
