<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\HrmDepartmentServiceInterface;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\GradeServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use PDF;
use Yajra\DataTables\DataTables;

class AppointmentLetterController extends Controller
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

    public function bulkAppointment(EmployeeServiceInterface $employeeService)
    {
        abort_if(!auth()->user()->can('hrm_appointment_with_select_letter_index'), 403, 'Access Forbidden');
        $employees = $this->employeeService->employeeActiveListWithId();
        return view('hrm::employees.appointment-letter.appointment_letter', compact('employees'));
    }

    public function createPersonWiseRow(Request $request, $user_id)
    {
        $employee = Employee::where('id', $user_id)->first();

        return view('hrm::employees.appointment-letter.partial', compact('employee'));
    }

    public function bulkLetterPrint(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_appointment_with_select_letter_print'), 403, 'Access Forbidden');
        if ($request->user_ids == null) {
            return redirect()->back()->with('message', [
                'type' => 'error',
                'text' => 'Select a employee first to generate appointment letter.',
            ]);
        }
        $employees_ids = $request->user_ids;
        $pdf = PDF::loadView('hrm::employees.appointment-letter.bulk_print', compact('employees_ids'));
        $pdf->stream('letter.pdf');
    }

    public function appointmentLetter2(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_appointment_letter_index'), 403, 'Access Forbidden');

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
                                <input type="checkbox" name="appointment_ids[]" value="' . $row->id . '" class="mt-2 check1">
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

                ->rawColumns(['check', 'status', 'photo', 'address', 'designationID', 'departmentID', 'joining'])
                ->with([
                    'allRow' => $rowCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::employees.appointmentLetter2.appointment_letter', compact('employees', 'departments', 'sections', 'sections', 'designations', 'grades'));
    }

    public function printAppointmentLetter2(Request $request)
    {
        abort_if(!auth()->user()->can('hrm_appointment_letter_print'), 403, 'Access Forbidden');

        if ($request->appointment_ids) {
            $employees_ids = $request->appointment_ids;
            // if ($employees_ids == null) {
            //     return redirect()->back()->with('message', [
            //         'type' => 'error',
            //         'text' => 'Select a employee first to generate appointment letter.',
            //     ]);
            // }
            $pdf = PDF::loadView('hrm::employees.appointmentLetter2.appointment-letter-print', compact('employees_ids'));
            $pdf->stream('letter.pdf');

            return $pdf;
        }

        $query = Employee::orderBy('id', 'desc')->where('employment_status', EmploymentStatus::Active);
        if ($request->ajax()) {
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
        }
        $employees = $query->get();
        $employees_ids = $employees->pluck('id');

        return view('hrm::employees.appointmentLetter2.appointment-letter-print', compact('employees_ids'));
    }

    public function printAppointmentLetter(Request $request)
    {
        $employees_ids = $request->employee_id;
        if ($employees_ids == null) {
            return redirect()->back()->with('message', [
                'type' => 'error',
                'text' => 'Select a employee first to generate appointment letter.',
            ]);
        }

        $pdf = PDF::loadView('hrm::employees.appointmentLetter2.appointment-letter-print', compact('employees_ids'));
        $pdf->stream('letter.pdf');

        return $pdf;
    }
}
