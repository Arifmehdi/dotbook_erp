<?php

namespace Modules\HRM\Http\Controllers\Recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Enums\BloodGroups;
use Modules\Core\Enums\Countries;
use Modules\Core\Enums\MaritalStatus;
use Modules\Core\Interface\BdDistrictServiceInterface;
use Modules\Core\Interface\BdDivisionServiceInterface;
use Modules\Core\Interface\BdUnionServiceInterface;
use Modules\Core\Interface\BdUpazilaServiceInterface;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\GradeServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Modules\HRM\Interface\ShiftServiceInterface;
use Modules\HRM\Interface\SubSectionServiceInterface;
use Modules\Website\Entities\JobApply;

class ConvertToEmployeeController extends Controller
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

    public function applicantConvert(Request $request, $id)
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
        $job_candidate_info = JobApply::findOrFail($id);

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
                'job_candidate_info',
            )
        );
    }
}
