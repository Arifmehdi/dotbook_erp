<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\LeaveApplicationReportServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class LeaveApplicationReportController extends Controller
{
    private $LeaveApplicationReportService;

    protected $employeeService;

    public function __construct(LeaveApplicationReportServiceInterface $LeaveApplicationReportService, EmployeeServiceInterface $employeeService)
    {
        $this->LeaveApplicationReportService = $LeaveApplicationReportService;
        $this->employeeService = $employeeService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $employees = $this->employeeService->employeeActiveListWithId();
        $leaveApplications = $this->LeaveApplicationReportService->leaveApplicationFilter($request);
        $rowCount = $this->LeaveApplicationReportService->getRowCount();
        if ($request->ajax()) {
            return DataTables::of($leaveApplications)
                ->addIndexColumn()
                ->addColumn('employeeName', function ($row) {
                    return $row->employee->name ?? 'No Employee';
                })
                ->addColumn('employee_id', function ($row) {
                    return $row->employee->employee_id ?? 'No Employee ID';
                })
                ->addColumn('leave_type_name', function ($row) {
                    return $row->leaveType->name ?? 'No Leave Type';
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="leave_application_id[]" value="'.$row->id.'" class="mt-2 check1">
                            </div>';

                    return $html;
                })
                ->addColumn('leave_type_name', function ($row) {
                    return "<span class='d-block text-center'>". $row->leaveType->name ."</span>" ?? '';
                })
                ->addColumn('approve_day', function ($row) {
                    return "<span class='d-block text-center'>".$row->approve_day."</span>";

                })
                ->addColumn('isPaid', function ($row) {
                    return ($row->is_paid == 0)
                    ? "<span  class='d-block text-center' title='Unpaid'><i class='fa-sharp fa-regular fa-circle-xmark fa-lg text-danger'></i></span>"
                    : "<span  class='d-block text-center' title='Paid'><i class='fa-sharp fa-regular fa-circle-check fa-lg text-success'></i></span>";
                })
                ->addColumn('attachment', function ($row) {
                    return ($row->attachment == null)
                        ? "<span' class='d-block text-center'><a href='#'><i class='fa-sharp fa-regular fa-circle-xmark fa-lg text-danger'></i></a></span>"
                        : "<a href='#' class='d-block text-center'><i class='fa-sharp fa-regular fa-circle-check fa-lg text-success'></i></a>";
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge bg-primary text-white">Allowed</span>' : '<span class="badge bg-info text-white">Not-Allowed</span>';
                })

                ->rawColumns(['action', 'check', 'status','isPaid', 'attachment','leave_type_name',
                'approve_day'])
                ->with([
                    'allRow' => $rowCount,
                    // 'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::reports.leave-application.index', compact('leaveApplications', 'employees'));
    }
}
