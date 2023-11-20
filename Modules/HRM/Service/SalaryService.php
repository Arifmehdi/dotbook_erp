<?php

namespace Modules\HRM\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\HRM\Enums\AdjustmentType;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\LeaveApplicationServiceInterface;
use Modules\HRM\Interface\OffDaysServiceInterface;
use Modules\HRM\Interface\SalaryServiceInterface;

/* class SalaryService
 * Salary List Generator For All Employees
 * @author Sunwarul Islam, SpeedDigit, 2021
 */

class SalaryService implements SalaryServiceInterface
{
    public function __construct(
        private LeaveApplicationServiceInterface $leaveApplicationService,
        private OffDaysServiceInterface $offDaysService,
    ) {
    }

    public function calculateSalary(iterable $employees_collection, ?int $_section_id, ?int $year, ?int $_month_number, string $startDate = null, string $endDate = null): iterable
    {
        $customDateSalary = (isset($startDate) && isset($endDate));
        $_first_day_in_month = $customDateSalary ? $startDate : date('d-m-Y', strtotime("01-$_month_number-$year"));

        $_first_day_in_month_object = new \DateTime($_first_day_in_month);
        $days_in_month = (int) $_first_day_in_month_object->format('t');

        $_last_day_in_month = $customDateSalary ? $endDate : date('t-m-Y', strtotime($_first_day_in_month));

        $month_name = date('F', strtotime($_first_day_in_month));
        $month = $month_name;

        // $isBuyerMode = isset(auth()->user()->buyer_mode) && (auth()->user()->buyer_mode == 1);
        $isBuyerMode = config('app.is_buyer_mode');
        // QUERY #2 (Leave Applications of requested month)
        $employeesUserIds = $employees_collection->pluck('id')->toArray();
        $leaveApplications = $this->leaveApplicationService->getEmployeesLeaves($employeesUserIds, $month, $year);

        // QUERY #3 (All attendances of requested month)
        $attendances_object = DB::connection('hrm')->table('attendances')
            ->where('year', $year)
            ->where('month', $month_name)
            ->get(['employee_id', 'clock_in', 'clock_out', 'at_date', 'clock_in_ts', 'clock_out_ts', 'status', 'manual_entry', 'shift', 'bm_clock_in', 'bm_clock_in_ts', 'bm_clock_out', 'bm_clock_out_ts']);

        // QUERY #4 (Holidays of requested month)
        $holidays_array = $this->offDaysService->getByMonth($month, $year);
        $holidays_date_array = $holidays_array['dates_array'];

        // QUERY # 5
        $shifts = DB::connection('hrm')->table('shifts')->get(['name', 'start_time', 'end_time']);
        // // QUERY # 6
        // $other_earns = DB::table('otherearns')->where('month', $month_name)->where('year', $year)->get(['user_id', 'amount', 'deduction'])->toArray();

        $salary_adjustments_collection = DB::connection('hrm')->table('salary_adjustments')
            ->where('year', $year)
            ->where('month', date('n', strtotime($month)))
            ->get()->toArray();

        // $salary_adjustments__additions = $salary_adjustments_collection->where('type', AdjustmentType::Addition);
        // $salary_adjustments__deductions = $salary_adjustments_collection->where('type', AdjustmentType::Deduction);

        // QUERY # 7
        // $deduction_taxes = DB::table('deduction_tax')->where('month', $month_name)->where('year', $year)->get(['user_id', 'tax', 'month', 'year'])->toArray();

        $taxes_adjustments_collection = DB::connection('hrm')->table('tax_adjustments')
            ->where('year', $year)
            ->where('month', date('n', strtotime($month)))
            ->get()->toArray();

        // // QUERY # 8
        // $manual_overtimes = DB::table('addition_ot')->where('month', $month_name)->where('year', $year)->get()->toArray();
        $overtime_adjustments_collection = DB::connection('hrm')->table('overtime_adjustments')
            ->where('year', $year)
            ->where('month', date('n', strtotime($month)))
            ->get()->toArray();

        // $overtime_adjustments__additions = $salary_adjustments_collection->where('type', AdjustmentType::Addition);
        // $overtime_adjustments__deductions = $salary_adjustments_collection->where('type', AdjustmentType::Deduction);

        //QUERY #9
        $section = DB::connection('hrm')->table('sections')->where('id', $_section_id)->first();
        $section_name = $section->name ?? null;

        // QUERY 10
        $shift_adjustments_collection = DB::connection('hrm')
            ->table('shift_adjustments')
            ->leftJoin('shifts', 'shift_adjustments.shift_id', 'shifts.id')
            ->select('shift_adjustments.*', 'shifts.name as shift_name')
            ->get();
        /**
         * Employee wise salary list generation
         */
        $employees = $employees_collection
            ->reject(function ($employee) use ($_last_day_in_month) {
                return strtotime($_last_day_in_month) < strtotime($employee->joining_date);
            })
            ->reject(function ($employee) use ($_first_day_in_month) {
                $isLeft = isset($employee->left_date) && isset($employee->employment_status) && ($employee->employment_status == EmploymentStatus::Left);
                if ($isLeft) {
                    $left_date = date('Y-m-d', strtotime($employee->left_date));
                    $first_day_of_month = date('Y-m-d', strtotime($_first_day_in_month));
                    $isEmployeeLeftBeforeFirstDateOfRequestedMonth = strtotime($left_date) <= strtotime($first_day_of_month);

                    return $isEmployeeLeftBeforeFirstDateOfRequestedMonth;
                }
            })
            ->reject(function ($employee) use ($_first_day_in_month) {
                $isResigned = isset($employee->resign_date) && isset($employee->employment_status) && ($employee->employment_status == EmploymentStatus::Resign);
                if ($isResigned) {
                    $resign_date = date('Y-m-d', strtotime($employee->resign_date));
                    $first_day_of_month = date('Y-m-d', strtotime($_first_day_in_month));
                    $isEmployeeResignedBeforeFirstDayOfRequestedMonth = strtotime($resign_date) <= strtotime($first_day_of_month);

                    return $isEmployeeResignedBeforeFirstDayOfRequestedMonth;
                }
            })
            // $other_earns,
            // $deduction_taxes,

            // $manual_overtimes,
            // toDO::both are and last one add in use blank space

            ->map(function ($employee, $key) use (
                $_first_day_in_month,
                $_last_day_in_month,
                $holidays_date_array,
                $leaveApplications,
                $attendances_object,

                $days_in_month,

                $isBuyerMode,
                $month,
                $year,
                $shift_adjustments_collection,
                $salary_adjustments_collection,
                $overtime_adjustments_collection,
                $taxes_adjustments_collection,
            ) {
                $basic = ($employee->salary - 1850) / 1.5;
                $employee->basic = round($basic);
                $employee->house_rent = round(($basic * 50) / 100);
                $gross_salary = $employee->salary;

                // Working day count
                $joining_date = date('d-m-Y', strtotime($employee->joining_date));
                $start_date = (\strtotime($joining_date) < strtotime($_first_day_in_month)) ? $_first_day_in_month : $joining_date;
                $end_date = (strtotime(date('d-m-Y')) < strtotime($_last_day_in_month)) ? date('d-m-Y') : $_last_day_in_month;

                // If employee resigned
                if ((isset($employee->employment_status) && $employee->employment_status == EmploymentStatus::Resign) && isset($employee->resign_date)) {
                    $resign_date = date('d-m-Y', strtotime($employee->resign_date.' -1day'));
                    $end_date = strtotime($resign_date) < strtotime($end_date) ? $resign_date : $end_date;
                }

                // If employee left
                if ((isset($employee->employment_status) && $employee->employment_status == EmploymentStatus::Left) && isset($employee->left_date)) {
                    $left_date = date('d-m-Y', strtotime($employee->left_date.' -1day'));
                    $end_date = strtotime($left_date) < strtotime($end_date) ? $left_date : $end_date;
                }

                $d1 = Carbon::createFromFormat('d-m-Y', date('d-m-Y', strtotime($start_date)));
                $d2 = Carbon::createFromFormat('d-m-Y', date('d-m-Y', strtotime($end_date)));

                $employee->working_days = $working_days = (int) $d1->diffInDays($d2) + 1;

                // Leaves
                $employee_current_month_leaves = $leaveApplications->where('employee_id', $employee->id);
                $approved_leaves = $this->leaveApplicationService->getUniqueLeaves($employee->id, $employee_current_month_leaves);
                $approved_leaves_date_array = $approved_leaves['dates_array'];

                // Present Count
                // Applicable off day to this employee (Considering his joining date, current printing date, etc)
                $_applied_off_days_array = array_filter($holidays_date_array, function ($off_day) use ($start_date, $end_date) {
                    return (strtotime($off_day) >= strtotime(date('Y-m-d', strtotime($start_date)))) &&
                        (strtotime($off_day) <= strtotime(date('Y-m-d', strtotime($end_date))));
                });

                $applied_off_day_count = count(array_diff($_applied_off_days_array, $approved_leaves_date_array));
                $employee->off_days = $off_days = $applied_off_day_count;

                // Attendances count of the employee in current requested month and year
                $attendances_of_this_user = $attendances_object->where('employee_id', $employee->id)
                    ->where('at_date', '>=', date('d-m-Y', strtotime($start_date)))
                    ->where('at_date', '<=', date('d-m-Y', strtotime($end_date)))
                    ->sortByDesc('manual_entry')
                    ->unique('at_date')
                    ->sortBy('at_date')
                    ->toArray();

                // \Log::info($attendances_of_this_user);

                $attendances_count_including_off_days = array_reduce($attendances_of_this_user, function ($sum, $item) {
                    // \Log::info($item->at_date);
                    return $sum + ($item->status == 'Present' || $item->status == 'Late');
                }, 0);

                $approved_leaves_count = $approved_leaves['total'];
                $employee->leaves = $approved_leaves_count;

                // Worked in weekend count
                $worked_in_weekends_count = array_reduce($attendances_of_this_user, function ($sum, $item) use ($holidays_date_array, $approved_leaves_date_array) {
                    $attendance_date = date('Y-m-d', strtotime($item->at_date));
                    $isWorkedInLeaveDay = in_array($attendance_date, $approved_leaves_date_array);
                    $isWorkedInHoliday = in_array($attendance_date, $holidays_date_array);

                    return $sum + (($isWorkedInHoliday || $isWorkedInLeaveDay) && ! ($item->status == 'Leave'));
                });

                $employee->present = $present = $attendances_count_including_off_days - $worked_in_weekends_count;
                $absent = $working_days - ($present + $approved_leaves_count + $applied_off_day_count);

                $employee->absent = ($absent >= 0) ? $absent : 0;

                // Attendance bonus
                $attendance_bonus = 0;
                if (isset($employee->grade_name) && ($employee->grade_name != 'N/A')) {
                    if ($present + $off_days == $days_in_month) {
                        $attendance_bonus = 500;
                    }
                }

                $employee->attendance_bonus = $attendance_bonus;

                // Tiffin days
                $tiffin_days = 0;
                $night_bill_days = 0;

                // if ($employee->shift_name == 'A' || $employee->shift_name == 'G') {
                $tiffin_and_night_days = array_reduce(
                    $attendances_of_this_user,
                    function ($sum, $attendance) {

                        if (! is_null($attendance->clock_in_ts) && ! is_null($attendance->clock_out_ts)) {
                            $t1 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', \strtotime($attendance->clock_in_ts)));
                            $t2 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', \strtotime($attendance->clock_out_ts)));
                            // Handle Sept. and adjustment condition
                            if ($t1->gt($t2)) {
                                $t2 = $t2->addDay();
                            }
                            $d = $t1->diffInMinutes($t2);
                        } else {
                            $d = 0;
                        }

                        if (($d >= 13 * 60) && ($d < 15 * 60)) {
                            $sum['tiffin_days'] += 1;
                        }

                        if (($d >= 15 * 60)) {
                            $sum['night_bill_days'] += 1;
                        }

                        // return $d >= 14;
                        return $sum;
                    },
                    [
                        'tiffin_days' => 0,
                        'night_bill_days' => 0,
                    ]
                );

                $tiffin_days = $tiffin_and_night_days['tiffin_days'];
                $night_bill_days = $tiffin_and_night_days['night_bill_days'];

                $employee->tiffin_days = $tiffin_days = $isBuyerMode ? 0 : $tiffin_days;
                $employee->tiffin_bill = $tiffin_bill = $tiffin_days * 20;

                $employee->night_bill_days = $night_bill_days = $isBuyerMode ? 0 : $night_bill_days;
                $employee->night_bill = $night_bill = ($employee->grade_name == 'N/A') ? ($night_bill_days * 100) : $night_bill_days * 40;

                // \Debugbar::info($tiffin_and_night_days);

                // Overtime O.T.
                $over_time_minutes = array_reduce($attendances_of_this_user, function ($sum, $attendance) use ($employee, $holidays_date_array, $isBuyerMode, $month, $year, $shift_adjustments_collection) {

                    $attendance_date = date('d-m-Y', strtotime($attendance->at_date));
                    if (isset($attendance->shift)) {

                        $shift = $shift_adjustments_collection
                            ->where('shift_name', $attendance->shift)
                            ->where('applied_date_from', '<=', date('Y-m-d', strtotime($attendance->at_date)))
                            ->where('applied_date_to', '>=', date('Y-m-d', strtotime($attendance->at_date)))
                            ->first();
                        if (isset($shift)) {
                            $shift_start = $shift->start_time;
                            $shift_start_ts = "$attendance_date $shift_start";
                            $shift_end = $shift->end_time;

                            if ($shift_end <= 12) {
                                $actualDate = date('d-m-Y', strtotime($attendance_date.'+1 day'));
                                $shift_end_ts = "$actualDate $shift_end";

                            } else {
                                $shift_end_ts = "$attendance_date $shift_end";
                            }

                            $shift_late_count_ts = "$attendance_date $shift->late_count";
                            // OT Break Code Start

                            if (isset($shift->with_break) && ($shift->with_break == 1)) {
                                // This shift have a break. calculate the break time.
                                $break_start = new Carbon(date('d-m-Y H:i', strtotime($attendance->at_date.' '.$shift->break_start)));
                                $break_end = new Carbon(date('d-m-Y H:i', strtotime($attendance->at_date.' '.$shift->break_end)));
                                $breakDiff = $break_start->diff($break_end);
                                $break_minutes = $breakDiff->h * 60 + $breakDiff->i;
                                // \Debugbar::info("Have break: Date = $attendance->at_date, Minutes =  $break_minutes");
                            }
                            // OT Break Code Ends
                        }
                    }

                    if ($employee->overtime_allowed == 1) {
                        if (isset($attendance->clock_out_ts)) {

                            $attendance_date = date('Y-m-d', strtotime($attendance->at_date));

                            if ($isBuyerMode) {
                                if (isset($attendance->clock_out_ts) && isset($attendance->clock_in_ts)) {
                                    if (strtotime($attendance->clock_out_ts) < strtotime($attendance->clock_in_ts)) {
                                        $attendance->clock_out_ts = date('Y-m-d H:i:s', strtotime($attendance->clock_out_ts.' +1day'));
                                    }
                                } elseif (isset($attendance->clock_out_ts) && isset($shift_start_ts)) {
                                    if (strtotime($attendance->clock_out_ts) < strtotime($shift_start_ts)) {
                                        $attendance->clock_out_ts = date('Y-m-d H:i:s', strtotime($attendance->clock_out_ts.' +1day'));
                                    }
                                }
                                $earlyExit = isset($attendance->clock_out_ts) && (strtotime($attendance->clock_out_ts) < strtotime($shift_end_ts));

                                if (! $earlyExit && isset($attendance->clock_out_ts)) {

                                    $bmClockOut_TS_Exists = isset($attendance->bm_clock_out_ts) && ! empty($attendance->bm_clock_out_ts);
                                    $regularClockOut_GT_shift_end = $bmClockOut_TS_Exists && (strtotime($attendance->clock_out_ts) > strtotime($shift_end_ts));
                                    $regularClockOut_GT_bm_clockOut = $bmClockOut_TS_Exists && (strtotime($attendance->clock_out_ts) > strtotime($attendance->bm_clock_out_ts));

                                    if ($bmClockOut_TS_Exists && $regularClockOut_GT_shift_end && $regularClockOut_GT_bm_clockOut) {
                                        $attendance->clock_out_ts = $attendance->bm_clock_out_ts;
                                        $attendance->clock_out = date('H:i', strtotime($attendance->bm_clock_out_ts));
                                    }

                                    $__tempT1 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($attendance->clock_out_ts)));
                                    $__tempT2 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($shift_end_ts)));
                                    $__Diff1 = $__tempT1->diffInMinutes($__tempT2);

                                    if ($__Diff1 >= 134) {
                                        $__tempHi = $__tempT2->addMinutes(120 + mt_rand(1, 14));
                                        $attendance->clock_out = $__tempHi->format('H:i');
                                        $attendance->clock_out_ts = $__tempHi->format('Y-m-d H:i:s');

                                        DB::connection('hrm')->table('attendances')
                                            ->where('month', $month)
                                            ->where('year', $year)
                                            ->where('employee_id', $attendance->employee_id)
                                            ->where('at_date', $attendance->at_date)
                                            ->update([
                                                'bm_clock_in' => $attendance->clock_in,
                                                'bm_clock_in_ts' => $attendance->clock_in_ts,
                                                'bm_clock_out' => $attendance->clock_out,
                                                'bm_clock_out_ts' => $attendance->clock_out_ts,
                                            ]);
                                    }
                                }
                            } // buyer mode ends
                            // If weekend, whole day is over-time
                            $isWeekend = in_array($attendance_date, $holidays_date_array);

                            if ($isWeekend && ! $isBuyerMode) {
                                $clockOut_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($attendance->clock_out_ts)));
                                $clockIn_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($attendance->clock_in_ts)));

                                if ($clockIn_TS->gt($clockOut_TS)) {
                                    $clockOut_TS = $clockOut_TS->addDay();
                                }

                                $diff = $clockOut_TS->diff($clockIn_TS);

                                $adjuster = $diff->h * 60 + $diff->i;

                                // If clock_out > break_end then reduce (break_end - break_start minutes from OT count)
                                // OTB
                                if (isset($break_minutes) && is_numeric($break_minutes) && isset($attendance->clock_out_ts) && isset($break_end)) {
                                    $breakEnd_Time = new Carbon($break_end);
                                    if ($clockOut_TS->gt($breakEnd_Time)) {
                                        if ($adjuster > $break_minutes) {
                                            $adjuster -= $break_minutes;
                                        }
                                    }
                                }

                                $_hour = intval($adjuster / 60);
                                $_minutes = intval($adjuster % 60);

                                $ot_minutes = 0;
                                if ($_minutes > 14 && $_minutes < 40) {
                                    $ot_minutes = 30;
                                } elseif ($_minutes >= 40 && $_minutes <= 60) {
                                    $_hour += 1;
                                    $_minutes = 0;
                                } elseif ($_minutes >= 0 && $_minutes < 15) {
                                    $ot_minutes = 0;
                                }

                                $_hour = ($_hour >= 7) ? $_hour - 1 : $_hour;

                                $total_minutes = ($_hour * 60) + $ot_minutes; // Main value

                                $_temp_hour = intval($total_minutes / 60);
                                $_temp_minutes = intval($total_minutes % 60);

                                // \Debugbar::info("$_temp_hour : $_temp_minutes");
                                // \Debugbar::info("H:i = $_hour : $_minutes");

                                return $sum + $total_minutes;
                            } elseif (! $isWeekend) {

                                // Regular Day Overtime
                                if (isset($attendance->clock_out_ts)) {

                                    $clockOut_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($attendance->clock_out_ts)));
                                    $shiftEnd_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', \strtotime($shift_end_ts)));
                                    $t3 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', \strtotime($attendance->clock_in_ts)));
                                    $shiftStart_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', \strtotime($shift_start_ts)));

                                    // If clock_out < shift_start
                                    if ($shiftStart_TS->gt($clockOut_TS)) {
                                        $clockOut_TS = $clockOut_TS->addDay();
                                    }

                                    if ($clockOut_TS->gt($shiftEnd_TS)) {
                                        // $_minutes_diff = $t1->diffInMinutes($t2); [BUG if diff > 2 days]
                                        $diff = $clockOut_TS->diff($shiftEnd_TS);

                                        $adjuster = $diff->h * 60 + $diff->i;

                                        // If clock_out > break_end then reduce (break_end - break_start minutes from OT count)
                                        // OTB
                                        if (isset($break_minutes) && is_numeric($break_minutes) && isset($attendance->clock_out_ts) && isset($break_end)) {
                                            $breakEnd_Time = new Carbon($break_end);
                                            if ($clockOut_TS->gt($breakEnd_Time)) {
                                                if ($adjuster > $break_minutes) {
                                                    $adjuster -= $break_minutes;
                                                }
                                            }
                                        }

                                        $_hour = intval($adjuster / 60);
                                        $_minutes = intval($adjuster % 60);

                                        $ot_minutes = 0;
                                        if ($_minutes > 14 && $_minutes < 40) {
                                            $ot_minutes = 30;
                                        } elseif ($_minutes >= 40 && $_minutes <= 60) {
                                            $_hour += 1;
                                            $_minutes = 0;
                                        } elseif ($_minutes >= 0 && $_minutes < 15) {
                                            $ot_minutes = 0;
                                        }

                                        $_hour = ($_hour >= 10) ? $_hour - 1 : $_hour;
                                        $total_minutes = ($_hour * 60) + $ot_minutes;

                                        $_temp_hour = intval($total_minutes / 60);
                                        $_temp_minutes = intval($total_minutes % 60);

                                        // \Debugbar::info("$_temp_hour : $_temp_minutes");
                                        // \Debugbar::info("H:i = $_hour : $_minutes");
                                        return $sum + $total_minutes;
                                    }
                                }
                            }
                        }
                    }

                    return $sum;
                });
                // other_earns salary_adjustments_collection
                // deduction_taxes taxes_adjustments_collection
                // manual_overtimes overtime_adjustments_collection

                // Adjust Manual O.T. (Regular OT - (Added OT - Deducted OT))
                // addedOtInMinutes
                $otMinuteAdjustment = array_reduce($overtime_adjustments_collection, function ($sum, $item) use ($employee) {
                    if ($item->employee_id == $employee->id) {
                        if (isset($item->ot_minutes)) {
                            // $__otArray = explode(':', trim($item->addition_ot));
                            // return $sum + (intval($__otArray[0]) * 60 + intval($__otArray[1]));

                            return ($item->type == 2) ? $sum - $item->ot_minutes : $sum + $item->ot_minutes;
                        }
                    }

                    return $sum;
                }) ?? 0;

                // $deductedOtInMinutes = array_reduce($manual_overtimes, function ($sum, $item) use ($employee) {

                //     if ($item->user_id == $employee->id) {
                //         if (isset($item->deduction_ot)) {
                //             $__otArray = explode(':', trim($item->deduction_ot));
                //             return $sum + (intval($__otArray[0]) * 60 + intval($__otArray[1]));
                //         }
                //     }
                //     return $sum;
                // }) ?? 0;

                // Adjustment calculation (in minutes)
                // $over_time_minutes -= $deductedOtInMinutes;
                $over_time_minutes = $otMinuteAdjustment;

                $__hour = intval($over_time_minutes / 60);
                $__minutes = intval($over_time_minutes % 60);

                // Final O.T count
                $employee->over_time = $__hour.':'.($__minutes > 0 ? $__minutes : '0');

                // Overtime Rate/Hour =  (basic/104) with 2 precision
                $employee->over_time_rate = $over_time_rate = number_format(($basic / 104), 2, '.', '');

                // Overtime Amount
                $over_time_amount = round(($__hour * $over_time_rate) + (($__minutes == 30) ? (0.5 * $over_time_rate) : 0));
                // $over_time_amount = (int) round(($__hour * $over_time_rate) + (($__minutes == 30) ? (0.5 * $over_time_rate) : 0));

                $employee->over_time_amount = $over_time_amount;

                // Other Earning
                // salary_adjustments_collection other_earns
                $salaryAdjustmentAfterCount = \array_reduce($salary_adjustments_collection, function ($sum, $earn) {
                    if (isset($item->amount)) {
                        // $__otArray = explode(':', trim($item->addition_ot));
                        // return $sum + (intval($__otArray[0]) * 60 + intval($__otArray[1]));

                        return ($item->type == 2) ? $sum - $earn->amount : $sum + $earn->amount;
                    }
                });
                $employee->salaryAdjustmentAfterCount = $salaryAdjustmentAfterCount;

                // GrossPay = (Gross Salary + Attendance Bonus + Tiffin bill + Overtime amount + Other Earn)
                $working_salary = round(($gross_salary / $days_in_month) * $working_days);

                $gross_pay = $working_salary + $attendance_bonus + $tiffin_bill + $night_bill + $over_time_amount + $salaryAdjustmentAfterCount;

                // \Debugbar::info("$working_salary + $attendance_bonus + $tiffin_bill + $night_bill + $over_time_amount + $other_earning");

                $employee->gross_pay = intval($gross_pay);

                // Deduction

                // Absent amount = (Basic / Days In Month) * Total absent days count
                if ($isBuyerMode) {
                    $per_day_amount = ($basic) / 30;
                } else {
                    // $per_day_amount = ($basic) / $days_in_month;
                    $per_day_amount = ($basic) / 30;
                }
                // $per_day_amount = number_format((float) $gross_salary / $days_in_month, 2, '.', '');

                // $absent_amount = round($per_day_amount * $absent, 2);
                $absent_amount = intval(round($per_day_amount * $absent));
                $employee->absent_amount = $absent_amount;

                // Advance = `otherearns.deduction` of this month & year
                // salary_adjustments_collection other_earns
                $advance = array_reduce($salary_adjustments_collection, function ($sum, $earn) use ($employee) {
                    if ($earn->description == 'Advance') {

                        return ($earn->employee_id == $employee->id) ? $sum + $earn->amount : $sum;
                    }

                });
                $advance = intval($advance);
                $employee->advance = $advance;

                // Tax
                // taxes_adjustments_collection deduction_taxes
                $tax = array_reduce($taxes_adjustments_collection, function ($sum, $tax) {
                    if (isset($tax->amount)) {
                        // $__otArray = explode(':', trim($item->addition_ot));
                        // return $sum + (intval($__otArray[0]) * 60 + intval($__otArray[1]));

                        return ($tax->type == 2) ? $sum - $tax->amount : $sum + $tax->amount;
                    }
                });
                $tax = intval($tax);
                $employee->tax = $tax;

                // If (gross_pay - (absent_amount + advance + tax)) >= 1000 Add 10 Tak to deductions
                $__stamp_fee = ($gross_pay - ($absent_amount + $advance + $tax)) >= 1000 ? 10 : 0;
                $__stamp_fee = intval($__stamp_fee);
                $__stamp_fee = $isBuyerMode ? $__stamp_fee : 0;
                $employee->stamp = $__stamp_fee;

                $total_deductions = $absent_amount + $advance + $tax + $__stamp_fee;
                $total_deductions = intval($total_deductions);
                $employee->total_deductions = $total_deductions;

                $__payable = $gross_pay - $total_deductions;

                // $net_payable = ($present >= 1 && $present <= 31) ? $__payable : 0;
                $net_payable = $__payable;

                $employee->payable_salary = $net_payable;

                $employee->joining_date = date('d-m-Y', strtotime($employee->joining_date));

                return $employee;
            });

        // Totals of each fields:
        $total_attendance_bonus = $employees->sum('attendance_bonus');
        $total_tiffin_bill = $employees->sum('tiffin_bill');
        $total_night_bill = $employees->sum('night_bill');
        $total_over_time_amount = $employees->sum('over_time_amount');
        $total_gross_pay = $employees->sum('gross_pay');
        $total_payable_salary = $employees->sum('payable_salary');

        return compact(
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
        );
    }
}
