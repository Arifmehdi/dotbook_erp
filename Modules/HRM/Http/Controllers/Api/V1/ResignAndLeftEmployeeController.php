<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Interface\BdDistrictServiceInterface;
use Modules\Core\Interface\BdDivisionServiceInterface;
use Modules\Core\Interface\BdUnionServiceInterface;
use Modules\Core\Interface\BdUpazilaServiceInterface;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\GradeServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Modules\HRM\Interface\SettingServiceInterface;
use Modules\HRM\Interface\ShiftServiceInterface;
use Modules\HRM\Interface\SubSectionServiceInterface;
use Modules\HRM\Transformers\LeftEmployeesResource;
use Modules\HRM\Transformers\ResignEmployeesResource;

class ResignAndLeftEmployeeController extends Controller
{
    private const EMPLOYEE_STATUS_ACTIVE = 1;

    private const EMPLOYEE_STATUS_RESIGNED = 2;

    private const EMPLOYEE_STATUS_LEFT = 3;

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
        private SettingServiceInterface $settingService,
    ) {
    }

    /**
     * Resign And Left Employees.
     *
     * @return Renderable
     */
    public function resignIndex(Request $request)
    {
        $employees = $this->employeeService->resignedEmployee();
        $rowCount = $this->employeeService->getRowCountForResigned();
        $employeesResource = ResignEmployeesResource::collection($employees);

        return response()->json([
            'employee_type' => 'resigned',
            'total_employee' => $rowCount,
            'employees' => $employeesResource,
        ]);
    }

    public function leftIndex(Request $request)
    {
        $employees = $this->employeeService->leftEmployee();
        $rowCount = $this->employeeService->getRowCountForLeft();
        $employeesResource = LeftEmployeesResource::collection($employees);

        return response()->json([
            'employee_type' => 'left',
            'total_employee' => $rowCount,
            'employees' => $employeesResource,
        ]);
    }

    public function manageEmployee(Request $request, $id)
    {
        if ($request->manageType == 'left') {
            $employee = $this->employeeService->makeLeftEmployee($request, $id);
        } elseif ($request->manageType == 'resign') {
            $employee = $this->employeeService->makeResignedEmployee($request, $id);
        }

        return response()->json('Employee left successfully');
    }

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
