<?php

namespace Modules\HRM\Service;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\CalculationCheckerServiceInterface;
use Modules\HRM\Interface\JobCardServiceInterface;
use Modules\HRM\Interface\JobCardSummaryServiceInterface;
use Modules\HRM\Interface\LeaveApplicationServiceInterface;
use Modules\HRM\Interface\OffDaysServiceInterface;
use Modules\HRM\Interface\SalaryServiceInterface;

class CalculationCheckerService implements CalculationCheckerServiceInterface
{
    public function __construct(private LeaveApplicationServiceInterface $leaveApplicationService, private OffDaysServiceInterface $offDaysService, private SalaryServiceInterface $salaryService, private JobCardServiceInterface $jobCardService, private JobCardSummaryServiceInterface $jobCardSummaryService)
    {
    }

    public function checkJobCardAndSalary($request)
    {
        abort_if(! auth()->user()->can('hrm_calculation_checker_jobVsSalary'), 403, 'Access forbidden');
        $year = $request->year;
        $month = $request->month;
        $section_id = $request->section_id;

        $month_number = date('m', strtotime("$month $year"));

        $_first_day_in_month = "01-$month_number-$year";
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
        $_last_day_in_month = "$days_in_month-$month_number-$year";

        $query = DB::connection('hrm')->table('employees')
            ->where('employees.employment_status', EmploymentStatus::Active)
            ->where('employees.deleted_at', null)
            ->where('joining_date', '<', date('Y-m-d', strtotime($_last_day_in_month)))
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->leftJoin('grades', 'employees.grade_id', 'grades.id')
            ->leftJoin('leave_applications', 'employees.id', 'leave_applications.employee_id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id');

        if ($request->employee_id) {
            $query = $query->where('employees.id', $request->employee_id);
        }
        if ($request->section_id) {
            $query = $query->where('employees.section_id', $request->section_id);
        }
        $employee_collection = $query->select([
            'sections.name as section_name',
            'grades.name as grade_name',
            'grades.medical',
            'grades.food',
            'grades.transport',
            'designations.name as designation_name',
            'employees.id',
            'employees.employee_id',
            'employees.name',
            'employees.joining_date',
            'employees.salary',
            'employees.overtime_allowed',
            'employees.mobile_banking_account_number',
            'employees.employment_status',
            'employees.left_date',
            'employees.resign_date',
            'shifts.name as shift_name',
            'shifts.start_time',
            'shifts.end_time',
        ])->distinct()->orderBy('employees.employee_id')->get();

        $salaryResult = $this->salaryService->calculateSalary($employee_collection, $section_id, $year, $month_number);

        echo "<h5>Equality Checker for 'Job-card' VS 'SalarySheet/Payslip/Excel' Calculation</h5>";
        echo "<h5>Year: $year, Month: $month</h5>";
        echo '<h5> Employee Calculation: '.count($salaryResult['employees']).'</h5>';
        echo '<p><button onclick="window.print()" style="float: right">Printed Result</button></p>';

        for ($i = 0; $i < count($salaryResult['employees']); $i++) {
            $testNo = $i + 1;
            $employee = $salaryResult['employees'][$i];
            $jcr = $this->jobCardService->calculateJobCard($employee->id, $month, $year, $this->leaveApplicationService, $this->offDaysService);

            $y1 = $jcr['total_overtime'] == $employee->over_time;
            $y2 = $jcr['total_present'] == $employee->present;
            $y3 = $jcr['total_leave'] == $employee->leaves;
            $y4 = $jcr['total_absent'] == $employee->absent;
            $y5 = $jcr['total_weekend'] == $employee->off_days;
            $isEqual = $y1 && $y2 && $y3 && $y4 && $y5;

            if ($isEqual) {
                echo "<span style=\"color: green;\">✔ Test Passed&nbsp;  $testNo &nbsp; &nbsp;</span>";

                echo <<<EOL
                    ID = $employee->employee_id,
                    Overtime {$jcr['total_overtime']} == $employee->over_time,
                    Present {$jcr['total_present']} == $employee->present,
                    Leaves {$jcr['total_leave']} == $employee->leaves,
                    Absents {$jcr['total_absent']} == $employee->absent,
                    Off Days {$jcr['total_weekend']} == $employee->off_days
                    <hr>
                    EOL;
            } else {
                echo "<span style=\"color: red;\">❌ Test Failed &nbsp;  $testNo &nbsp; &nbsp;</span>";

                echo <<<EOL
                    ID = $employee->employee_id,
                    Overtime {$jcr['total_overtime']} == $employee->over_time,
                    Present {$jcr['total_present']} == $employee->present,
                    Leaves {$jcr['total_leave']} == $employee->leaves,
                    Absents {$jcr['total_absent']} == $employee->absent,
                    Off Days {$jcr['total_weekend']} == $employee->off_days
                    <hr>
                    EOL;
            }
        }
    }

    public function checkSummaryAndSalary($request)
    {
        abort_if(! auth()->user()->can('hrm_calculation_checker_summaryVsSalary'), 403, 'Access forbidden');
        // $jcResult = $jobCardController->calculateJobCard($user_id, $month, $year);
        $month = $request->month;
        $month_name = $month;
        $year = $request->year;
        $section_id = $request->section_id;

        $month_number = date('m', strtotime("$month $year"));
        $_first_day_in_month = "01-$month_number-$year";
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
        $_last_day_in_month = "$days_in_month-$month_number-$year";

        $employees = new Collection();
        $query = DB::connection('hrm')->table('employees')
            ->where('employees.employment_status', EmploymentStatus::Active)
            ->where('employees.deleted_at', null)
            ->where('joining_date', '<', date('Y-m-d', strtotime($_last_day_in_month)))
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->leftJoin('grades', 'employees.grade_id', 'grades.id')
            ->leftJoin('leave_applications', 'employees.id', 'leave_applications.employee_id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id');

        if ($request->employee_id) {
            $query = $query->where('employees.id', $request->employee_id);
        }
        if ($request->section_id) {
            $query = $query->where('employees.section_id', $request->section_id);
        }

        $employees = $query->select([
            'sections.name as section_name',
            'grades.name as grade_name',
            'grades.medical',
            'grades.food',
            'grades.transport',
            'designations.name as designation_name',
            'employees.id',
            'employees.employee_id',
            'employees.name',
            'employees.joining_date',
            'employees.salary',
            'employees.overtime_allowed',
            'employees.mobile_banking_account_number',
            'employees.employment_status',
            'employees.left_date',
            'employees.resign_date',
            'shifts.name as shifts_name',
            'shifts.start_time',
            'shifts.end_time',
        ])
            // ->where('users.overtime_allowed', 1)
            ->distinct()->orderBy('employees.employee_id')->get();

        $salaryResult = $this->salaryService->calculateSalary($employees, $section_id, $year, $month_number);
        $summaryResult = $this->jobCardSummaryService->calculateSummary($employees, $section_id, $month, $year, $this->offDaysService, $this->leaveApplicationService);

        echo "<p>Equality Checker for 'Job-card Summary' && 'SalarySheet/Payslip/Excel' Calculation</p>";
        echo "<p>Year: $year, Month: $month</p>";
        echo '<p> Employee Calculation: '.count($summaryResult['employees']).'</p>';
        echo '<p><button onclick="window.print()" style="float: right">Printed Result</button></p>';

        $counter = count($salaryResult['employees']);
        for ($i = 0; $i < $counter; $i++) {
            $testNo = $i + 1;
            $employeeSalary = $employee = $salaryResult['employees'][$i];
            $employeeSummary = $summaryResult['employees'][$i];

            $isEqual = ($employeeSalary->off_days == $employeeSummary->off_days)
                && ($employeeSalary->total_present == $employeeSummary->present)
                && ($employeeSalary->total_overtime == $employeeSummary->over_time)
                && ($employeeSalary->absent == $employeeSummary->absent);

            if ($isEqual) {
                echo "<span style=\"color: green;\">✔ Test Passed &nbsp;  $testNo &nbsp; &nbsp; </span>";

                echo <<<EOL
                ID = $employeeSalary->employee_id,
                Present $employeeSalary->present == $employeeSummary->total_present,
                Off Days $employeeSalary->off_days == $employeeSummary->total_weekend
                Overtime $employeeSalary->over_time == $employeeSummary->total_overtime,
                <hr>
                EOL;
            } else {
                echo "<span style=\"color: red;\">❌ Test Failed &nbsp;  $testNo &nbsp; &nbsp; </span>";

                echo <<<EOL
                ID = $employeeSalary->employee_id,
                Present $employeeSalary->present == $employeeSummary->total_present,
                Off Days $employeeSalary->off_days == $employeeSummary->total_weekend
                Overtime $employeeSalary->over_time == $employeeSummary->total_overtime,
                <hr>
                EOL;
            }
        }
    }

    public function checkAllCalculation($request)
    {
        abort_if(! auth()->user()->can('hrm_calculation_checker_allCalculation'), 403, 'Access forbidden');
        $month = $request->month;
        $month_name = $month;
        $year = $request->year;
        $section_id = $request->section_id;

        $month_number = date('m', strtotime("$month $year"));
        $_first_day_in_month = "01-$month_number-$year";
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
        $_last_day_in_month = "$days_in_month-$month_number-$year";

        $employees = new Collection();
        $query = DB::connection('hrm')->table('employees')
            ->where('employees.employment_status', EmploymentStatus::Active)
            ->where('employees.deleted_at', null)
            ->where('joining_date', '<', date('Y-m-d', strtotime($_last_day_in_month)))
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->leftJoin('grades', 'employees.grade_id', 'grades.id')
            ->leftJoin('leave_applications', 'employees.id', 'leave_applications.employee_id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id');

        if ($request->employee_id) {
            $query = $query->where('employees.id', $request->employee_id);
        }
        if ($request->section_id) {
            $query = $query->where('employees.section_id', $request->section_id);
        }

        $employees = $query->select([
            'sections.name as section_name',
            'grades.name as grade_name',
            'grades.medical',
            'grades.food',
            'grades.transport',
            'designations.name as designation_name',
            'employees.id',
            'employees.employee_id',
            'employees.name',
            'employees.joining_date',
            'employees.salary',
            'employees.overtime_allowed',
            'employees.mobile_banking_account_number',
            'employees.employment_status',
            'employees.left_date',
            'employees.resign_date',
            'shifts.name as shifts_name',
            'shifts.start_time',
            'shifts.end_time',
        ])
            // ->where('users.overtime_allowed', 1)
            ->distinct()->orderBy('employees.employee_id')->get();

        $salaryResult = $this->salaryService->calculateSalary($employees, $section_id, $year, $month_number);

        $summaryResult = $this->jobCardSummaryService->calculateSummary($employees, $section_id, $month, $year, $this->offDaysService, $this->leaveApplicationService);

        echo "<p>Equality Checker for 'Job Card', 'Job Card Summary' && 'SalarySheet/Payslip/Excel' Calculations</p>";
        echo "<p>Year: $year, Month: $month</p>";
        echo '<p> Employee Calculation: '.count($summaryResult['employees']).'</p>';
        echo '<p><button onclick="window.print()" style="float: right">Printed Result</button></p>';

        $counter = count($salaryResult['employees']);
        for ($i = 0; $i < $counter; $i++) {
            $testNo = $i + 1;
            $employeeSalary = $employee = $salaryResult['employees'][$i];
            $employeeSummary = $summaryResult['employees'][$i];
            $jcr = $this->jobCardService->calculateJobCard($employee->id, $month, $year, $this->leaveApplicationService, $this->offDaysService);

            $y1 = count(\array_unique([$jcr['total_overtime'], $employeeSummary->total_overtime, $employeeSalary->over_time])) == 1;
            $y2 = count(\array_unique([$jcr['total_present'], $employeeSummary->total_present, $employeeSalary->total_present])) == 1;
            $y3 = count(\array_unique([$jcr['total_absent'], $employeeSummary->absent, $employeeSalary->absent])) == 1;
            $y4 = count(\array_unique([$jcr['total_weekend'], $employeeSummary->total_weekend, $employeeSalary->off_days])) == 1;
            // $y5 = count(\array_unique([$jcr['total_leave'], $employeeSummary->leaves, $employeeSalary->over_time])) == 1;
            $y5 = 1;

            $isEqual = $y1 && $y2 && $y3 && $y4 && $y5;

            if ($isEqual) {
                echo "<span style=\"color: green;\">✔ Test Passed &nbsp;  $testNo &nbsp; &nbsp; </span>";

                echo <<<EOL
                ID = $employeeSalary->employee_id,
                Overtime {$jcr['total_overtime']} = $employeeSummary->total_overtime = $employeeSalary->over_time,
                Present {$jcr['total_present']} = $employeeSummary->total_present = $employeeSalary->present,
                Absent {$jcr['total_absent']} = $employeeSummary->absent = $employeeSalary->absent,
                Off Days {$jcr['total_weekend']} = $employeeSummary->total_weekend = $employeeSalary->off_days
                <hr>
                EOL;
            } else {
                echo "<span style=\"color: red;\">❌ Test Failed &nbsp;  $testNo &nbsp; &nbsp; </span>";

                echo <<<EOL
                ID = $employeeSalary->employee_id,
                Overtime {$jcr['total_overtime']} = $employeeSummary->total_overtime = $employeeSalary->over_time,
                Present {$jcr['total_present']} = $employeeSummary->total_present = $employeeSalary->present,
                Absent {$jcr['total_absent']} = $employeeSummary->absent = $employeeSalary->absent,
                Off Days {$jcr['total_weekend']} = $employeeSummary->total_weekend = $employeeSalary->off_days
                <hr>
                EOL;
            }
        }
    }
}
