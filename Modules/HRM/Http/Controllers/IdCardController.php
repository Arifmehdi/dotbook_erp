<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\GradeServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class IdCardController extends Controller
{
    public function __construct(
        private EmployeeServiceInterface $employeeService,
        private HrmDepartmentServiceInterface $departmentService,
        private SectionServiceInterface $sectionService,
        private DesignationServiceInterface $designationService,
        private GradeServiceInterface $gradeService,
        private CommonServiceInterface $commonService,
    ) {
    }

    public function generateIdCard(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_id_card_print_index'), 403, 'Access Forbidden');
        $employees = $this->employeeService->allEmployeeListWithSelected();
        $departments = $this->departmentService->departmentSelectedAndSortListWithId();
        $sections = $this->sectionService->sectionSelectedAndSortListWithId();
        $designations = $this->designationService->designationSelectedAndSortListWithId();
        $grades = $this->gradeService->gradeSelectedAndSortListWithId();
        $employees_data = $this->employeeService->activeEmployeeListForID($request);
        $rowCount = $employees_data->count();

        if ($request->ajax()) {
            return DataTables::of($employees_data)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="employee_ids[]" value="' . $row->id . '" class="mt-2 check1">
                                </div>';
                    return $html;
                })
                ->editColumn('departmentID', function ($row) {
                    return $row->department_name ?? null;
                })
                ->editColumn('section_id', function ($row) {
                    return $row->section_name ?? null;
                })
                ->editColumn('designationID', function ($row) {
                    return $row->designation_name ?? null;
                })
                ->editColumn('print_count', function ($row) {
                    return $row->print_count == null ? '<span class="badge bg-info text-white">Not Printed</span>' : '<span class="badge bg-primary text-white">Printed<span class="badge bg-danger" style="font-size: 10px; margin-left: 5px">' . $row->print_count . '</span></span>';
                })
                ->editColumn('address', function ($row) {
                    $address = $row?->present_village . ',' . $row?->union_name . ',' . $row?->upazila_name . ',' . $row?->district_name . ',' . $row?->division_name;
                    $verified_length = strlen($address);
                    $address_result = ($verified_length > 30) ? substr($address, 0, 30) . '...' : $address;

                    return '<span title="' . $address . '">' . $address_result . '</span>' ?? 'Address is not Specified';
                })
                ->editColumn('grade_id', function ($row) {
                    return $row->grade_name ?? 'Grade is not Specified';
                })
                ->editColumn('joining', function ($row) {
                    return $row->joining_date ?? 'Joining Date is not Specified';
                })
                ->editColumn('status', function ($row) {
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
                ->rawColumns(['check', 'print_count', 'status', 'photo', 'address', 'designationID', 'departmentID', 'joining'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::employees.idcard.bulk_id_card', compact('employees', 'departments', 'sections', 'sections', 'designations', 'grades'));
    }

    public function printIdCard(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_id_card_print'), 403, 'Access Forbidden');
        if ($request->ajax()) {
            $query = Employee::query();

            if ($request->departmentID) {
                $query->where('hrm_department_id', $request->departmentID);
            }

            if ($request->sectionID) {
                $query->where('section_id', $request->sectionID);
            }

            if ($request->gradeID) {
                $query->where('grade_id', $request->gradeID);
            }
            if ($request->designationID) {
                $query->where('designation_id', $request->designationID);
            }
            if ($request->employeeStatus) {
                $query->where('employment_status', $request->employeeStatus);
            }

            if ($request->employeeID) {
                $query->where('id', $request->employeeID);
            }
            if ($request->joiningDate) {
                $date_range = explode('-', $request->joiningDate);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('joining_date', [$form_date, $to_date]);
            }

            $employees = $query->get();
            $employees->map(function ($employee) {
                $employee->print_count += 1;
                $employee->save();
            });

            return view('hrm::employees.idcard.bulk_id_card_print', compact('employees'));
        }
    }
}
