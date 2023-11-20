<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HRM\Exports\SalaryExport;
use Modules\HRM\Interface\DesignationServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\HRM\Interface\SalaryListServiceInterface;
use Modules\HRM\Interface\SalaryServiceInterface;
use Modules\HRM\Interface\SectionServiceInterface;
use Modules\HRM\Interface\ShiftServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class SalaryListController extends Controller
{
    /**
     * Payroll list without proper header
     *
     * @return Renderable
     */
    public function __construct(
        private EmployeeServiceInterface $employeeService,
        private SectionServiceInterface $sectionService,
        private ShiftServiceInterface $shiftService,
        private DesignationServiceInterface $designationService,
        private SalaryListServiceInterface $salaryListService,
        private SalaryServiceInterface $salaryService
    ) {
    }

    public function salaryList(Request $request)
    {

        // abort_if(! auth()->user()->can('hrm_attendance_job_card'), 403, 'Access forbidden');
        $employees = $this->employeeService->employeeActiveListWithId();
        $departments = $this->sectionService->sectionWithHrmDepartmentAndSelection();
        $designations = $this->designationService->all();
        $shifts = $this->shiftService->shiftOptimized();

        $employees_data = $this->salaryListService->payrollList($request);
        $rowCount = $this->salaryListService->payrollList($request)->count();
        if ($request->ajax()) {
            return DataTables::of($employees_data)
                ->addIndexColumn()
                ->addColumn('employment_status', function ($row) {
                    if ($row->employment_status == 1 || $row->employment_status == '' || $row->employment_status == null) {
                        return '✅ Active';
                        // return '<span class="badge w-50 badge-primary">Active</span>';
                    } elseif ($row->employment_status == 2) {
                        $date = date(config('hrm.date_format'), strtotime($row->resign_date));

                        return "🛑 Resigned ({$date})";
                        // return '<span class="badge w-50 badge-danger">Resigned</span>';
                    } elseif ($row->employment_status == 3) {
                        $date = date(config('hrm.date_format'), strtotime($row->left_date));

                        return "⏹ Left ({$date})";
                        // return '<span class="badge w-50 badge-warning">Lefty</span>';
                    }
                })
                ->editColumn('joining_date_format', function ($row) {
                    $date = date(Config('hrm.date_format'), strtotime($row->joining_date));

                    return $date;
                })
                ->editColumn('salary_format', function ($row) {
                    $salary = $row->salary . ' ' . ' .00 BDT';

                    return $salary;
                })
                ->editColumn('payment_&_number', function ($row) {
                    $payment_details = $row->mobile_banking_provider . ' (' . $row->mobile_banking_account_number . ')';

                    return $payment_details;
                })
                ->rawColumns(['employment_status'])
                ->with([
                    'allRow' => $rowCount,
                    // 'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::payroll.salary_list', compact('employees', 'departments', 'shifts', 'designations'));
    }

    public function salaryListPrint(Request $request)
    {
        // $employees_collection = $this->salaryListRequestFilter($request);
        $employees_collection = $this->salaryListService->salaryListRequestFilter($request);
        $year = $request->year;
        $section_id = $request->section_id;
        $_month_number = $request->month;

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $_month_number, $year);
        $_last_day_in_month = date('d-m-Y', strtotime("$days_in_month-$_month_number-$year"));
        $_first_day_in_month = date('d-m-Y', strtotime("01-$_month_number-$year"));
        $month_name = date('F', strtotime($_first_day_in_month));

        $startDate = isset($request->startDate) ? date('d-m-Y', strtotime($request->startDate)) : $_first_day_in_month;
        $endDate = isset($request->endDate) ? date('d-m-Y', strtotime($request->endDate)) : $_last_day_in_month;

        $res = [
            'employees' => $employees,
            'days_in_month' => $days_in_month,
            'month_name' => $month_name,
            'section_name' => $section_name,
            'year' => $year,
            'total_attendance_bonus' => $total_attendance_bonus,
            'total_tiffin_bill' => $total_tiffin_bill,
            'total_night_bill' => $total_night_bill,
            'total_over_time_amount' => $total_over_time_amount,
            'total_gross_pay' => $total_gross_pay,
            'total_payable_salary' => $total_payable_salary,
            'isBuyerMode' => $isBuyerMode,
        ] = $this->salaryService->calculateSalary($employees_collection, $section_id, $year, $_month_number, $startDate, $endDate);

        $printDate = isset($request->printDate) ? $request->printDate : date('d-m-Y');

        return view('hrm::payroll.salary_list_print', compact(
            'employees',
            'days_in_month',
            'month_name',
            'section_name',
            'year',
            'total_attendance_bonus',
            'total_tiffin_bill',
            'total_night_bill',
            'total_over_time_amount',
            'total_gross_pay',
            'total_payable_salary',
            'isBuyerMode',
            'printDate',
        ));
    }

    public function printPayslip(Request $request, SalaryServiceInterface $salaryService)
    {
        $employees_collection = $this->salaryListService->salaryListRequestFilter($request);
        $year = $request->year;
        $section_id = $request->section_id;
        $_month_number = $request->month;

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $_month_number, $year);
        $_first_day_in_month = date('d-m-Y', strtotime("01-$_month_number-$year"));
        $_last_day_in_month = date('d-m-Y', strtotime("$days_in_month-$_month_number-$year"));
        $month_name = date('F', strtotime($_first_day_in_month));

        $startDate = isset($request->startDate) ? date('d-m-Y', strtotime($request->startDate)) : $_first_day_in_month;
        $endDate = isset($request->endDate) ? date('d-m-Y', strtotime($request->endDate)) : $_last_day_in_month;

        [
            'employees' => $employees,
            'days_in_month' => $days_in_month,
            'month_name' => $month_name,
            'section_name' => $section_name,
            'year' => $year,
            'total_attendance_bonus' => $total_attendance_bonus,
            'total_tiffin_bill' => $total_tiffin_bill,
            'total_night_bill' => $total_night_bill,
            'total_over_time_amount' => $total_over_time_amount,
            'total_gross_pay' => $total_gross_pay,
            'total_payable_salary' => $total_payable_salary,
            'isBuyerMode' => $isBuyerMode,
        ] = $this->salaryService->calculateSalary($employees_collection, $section_id, $year, $_month_number, $startDate, $endDate);

        $printDate = isset($request->printDate) ? $request->printDate : date('d-m-Y');

        return view('hrm::payroll.payslip_print', compact(
            'employees',
            'days_in_month',
            'month_name',
            'section_name',
            'year',
            'total_attendance_bonus',
            'total_tiffin_bill',
            'total_night_bill',
            'total_over_time_amount',
            'total_gross_pay',
            'total_payable_salary',
            'isBuyerMode',
            'printDate',
        ));
    }

    public function salaryListExcelExport(Request $request, SalaryServiceInterface $salaryService)
    {
        $employees_collection = $this->salaryListService->salaryListRequestFilter($request);
        $year = $request->year;
        $section_id = $request->section_id;
        $_month_number = $request->month;

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $_month_number, $year);
        $_last_day_in_month = date('d-m-Y', strtotime("$days_in_month-$_month_number-$year"));
        $_first_day_in_month = date('d-m-Y', strtotime("01-$_month_number-$year"));
        $month_name = date('F', strtotime($_first_day_in_month));

        $startDate = isset($request->startDate) ? date('d-m-Y', strtotime($request->startDate)) : $_first_day_in_month;
        $endDate = isset($request->endDate) ? date('d-m-Y', strtotime($request->endDate)) : $_last_day_in_month;

        [
            'employees' => $employees,
            'days_in_month' => $days_in_month,
            'month_name' => $month_name,
            'section_name' => $section_name,
            'year' => $year,
            'total_attendance_bonus' => $total_attendance_bonus,
            'total_tiffin_bill' => $total_tiffin_bill,
            'total_night_bill' => $total_night_bill,
            'total_over_time_amount' => $total_over_time_amount,
            'total_gross_pay' => $total_gross_pay,
            'total_payable_salary' => $total_payable_salary,
            'isBuyerMode' => $isBuyerMode,
        ] = $this->salaryService->calculateSalary($employees_collection, $section_id, $year, $_month_number, $startDate, $endDate);

        $printDate = isset($request->printDate) ? $request->printDate : date('d-m-Y');

        // return Excel::download(new SalaryExport(compact('employees', 'days_in_month')), 'salaries.pdf');
        return Excel::download(new SalaryExport(compact('employees', 'days_in_month')), 'salaries.xlsx');
    }
}
