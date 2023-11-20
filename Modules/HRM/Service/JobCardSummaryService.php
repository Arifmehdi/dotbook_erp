<?php

namespace Modules\HRM\Service;

use Carbon\Carbon;
use DB;
use DebugBar\DataCollector\Renderable;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\JobCardSummaryServiceInterface;
use Modules\HRM\Interface\LeaveApplicationServiceInterface;
use Modules\HRM\Interface\OffDaysServiceInterface;

class JobCardSummaryService implements JobCardSummaryServiceInterface
{
    public function __construct(private OffDaysServiceInterface $offDaysService, private LeaveApplicationServiceInterface $leaveApplicationService)
    {
    }

    public function jobSummaryPrint($request) //Renderable
    {
        abort_if(! auth()->user()->can('hrm_attendance_job_summary_print'), 403, 'Access forbidden');
        [
            'employees' => $employees,
            'section_id' => $section_id,
            'month' => $month,
            'year' => $year,
        ] = $this->calculateSummaryFilter($request); //array

        [
            'month' => $month,
            'year' => $year,
            'employees' => $employees,
            'section_name' => $section_name,
            'attendances_dates' => $attendances_dates,
            'overtime_sum' => $overtime_sum,
        ] = $this->calculateSummary($employees, $section_id, $month, $year, $this->offDaysService, $this->leaveApplicationService);

        return compact(
            'month',
            'year',
            'employees',
            'section_name',
            'attendances_dates',
            'overtime_sum',
        );
    }

    public function calculateSummaryFilter($request)
    {
        abort_if(! auth()->user()->can('hrm_attendance_job_summary_view'), 403, 'Access forbidden');
        // Request data
        $employee_id = $request->employee_id;
        $section_id = $request->section_id;
        $sub_section_id = $request->sub_section_id;
        $shift_id = $request->shift_id;
        $month = $request->month;
        $year = $request->year;
        $month_number = date('m', strtotime("$month $year"));
        $_first_day_in_month = "01-$month_number-$year";
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
        $_last_day_in_month = "$days_in_month-$month_number-$year";

        $employees = new \Illuminate\Support\Collection();
        // Query #1
        $query = DB::connection('hrm')->table('employees')
            ->where('employment_status', EmploymentStatus::Active) // active employee
            ->where('joining_date', '<', date('Y-m-d', strtotime($_last_day_in_month)))
            ->leftJoin('designations', 'employees.designation_id', 'designations.id')
            ->leftJoin('sections', 'employees.section_id', '=', 'sections.id')
        // ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id');

        if ($request->employee_id) {
            $query->where('employees.id', $request->employee_id);
        }
        if ($request->section_id) {
            $query->where('employees.section_id', $request->section_id);
        }
        if ($request->sub_section_id) {
            $query->where('employees.sub_section_id', $request->sub_section_id);
        }
        if ($request->shift_id) {
            $query->where('employees.shift_id', $request->shift_id);
        }

        $employees = $query->select(
            'employees.id',
            'employees.name',
            'employees.employee_id',
            'employees.overtime_allowed',
            'employees.joining_date',
            // 'employees.type_status',
            'employees.employment_status',
            'employees.resign_date',
            'employees.left_date',
            'designations.name as designation_name',
            'sections.name as section_name',
            'shifts.name as shift_name',
            'shifts.late_count',
            'shifts.start_time',
            'shifts.end_time'
        )->orderBy('employee_id', 'asc')->get();

        return compact('employees', 'section_id', 'month', 'year');
    }

    public function calculateSummary($employees, $section_id, $month, $year, $offDaysService, $leaveApplicationService): array
    {
        abort_if(! auth()->user()->can('hrm_attendance_job_summary_view'), 403, 'Access forbidden');
        $month_number = date('m', strtotime("$month $year"));
        $_first_day_in_month = "01-$month_number-$year";
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
        $_last_day_in_month = "$days_in_month-$month_number-$year";

        // $isBuyerMode = isset(auth()->user()->buyer_mode) && (auth()->user()->buyer_mode == 1);
        $isBuyerMode = config('app.is_buyer_mode');
        // Query #2
        $shifts_collection = DB::connection('hrm')->table('shifts')->get();

        $filterDateLD = (strtotime(date('d-m-Y')) < strtotime($_last_day_in_month)) ? date('d-m-Y') : $_last_day_in_month;
        // Query #3
        $attendances = DB::connection('hrm')->table('attendances')
            ->where('year', $year)
            ->where('month', $month)
            ->where('at_date', '<=', $filterDateLD)
            ->orderByDesc('manual_entry')
            ->distinct('at_date');

        $attendances_collection = $attendances->get();
        $attendances_array = $attendances_collection->toArray();
        $attendances_dates = DateTimeUtils::dateRangeBetween($_first_day_in_month, $filterDateLD);

        // Query #4
        $holidays_array = $offDaysService->getByMonth($month, $year);
        $holidays_count = $holidays_array['total'];
        $holidays_date_array = $holidays_array['dates_array'];
        $holidays_date_type_array = $holidays_array['type_wise'];

        $leavesCollection = $leaveApplicationService->getEmployeesLeaves($employees->pluck('id')->toArray(), $month, $year);

        // Query #5
        $section = DB::connection('hrm')->table('sections')->where('id', $section_id)->first();

        // Query #6
        $shift_adjustments_collection = DB::connection('hrm')->table('shift_adjustments')->get();
        $GLOBALS['overtime_sum'] = 0;
        $employees = $employees->map(function ($employee) use (
            $attendances_dates,
            $holidays_date_array,
            $holidays_date_type_array,
            $leaveApplicationService,
            $leavesCollection,
            $attendances_collection,
            $_first_day_in_month,
            $_last_day_in_month,
            $isBuyerMode,
            $month,
            $year,
            $shift_adjustments_collection,
        ) {
            $joining_date = date('d-m-Y', strtotime($employee->joining_date));
            // Working day count
            $start_date = (\strtotime($joining_date) < strtotime($_first_day_in_month)) ? $_first_day_in_month : $joining_date;
            $end_date = (strtotime(date('d-m-Y')) < strtotime($_last_day_in_month)) ? date('d-m-Y') : $_last_day_in_month;

            // If employee resigned
            if ((isset($employee->employment_status) && $employee->employment_status == 2) && isset($employee->resign_date)) {
                $resign_date = date('d-m-Y', strtotime($employee->resign_date));
                $end_date = strtotime($resign_date) < strtotime($end_date) ? $resign_date : $end_date;
            }

            // If employee left
            if ((isset($employee->employment_status) && $employee->employment_status == 3) && isset($employee->left_date)) {
                $left_date = date('d-m-Y', strtotime($employee->left_date));
                $end_date = strtotime($left_date) < strtotime($end_date) ? $left_date : $end_date;
            }
            $employee_attendances = $attendances_collection
                ->where('employee_id', $employee->id)
                ->where('at_date', '>=', $start_date)
                ->where('at_date', '<=', $end_date);

            $holidays_date_array = array_filter($holidays_date_array, function ($date) use ($start_date, $end_date) {
                $_date = strtotime($date);
                $_start_date = strtotime($start_date);
                $_end_date = strtotime($end_date);

                return $_date >= $_start_date && $_date <= $_end_date;
            });

            $employeeLeavesCollection = $leavesCollection->where('employee_id', $employee->id);
            $employeeLeaves = $leaveApplicationService->getUniqueLeaves($employee->id, $employeeLeavesCollection);
            $leaves_date_array = $employeeLeaves['dates_array'];
            $leaves_date_type_array = $employeeLeaves['type_wise'];
            $employeeLeavesTotalCount = $employeeLeaves['total'];

            // Total report calculation initialization
            $total_present = 0;
            $total_weekend = 0;
            $total_leave = 0;
            $total_overtime_minutes = 0;

            $results = [];
            foreach ($attendances_dates as $attendance_date) {

                $attendance_date_Ymd = date('Y-m-d', \strtotime($attendance_date));
                $attendance_date = date('d-m-Y', strtotime($attendance_date));

                $break_minutes = null;

                $att = $employee_attendances
                    ->whereIn('at_date', [$attendance_date, "$attendance_date "])
                    ->first();
                // For weekend attendance with/without overtime, there is no shift for attendance entry. handle that.
                if (isset($att->shift_id)) {
                    // $shift = $shifts_collection->where('shift_name', $att->shift)->first();
                    $shift = $shift_adjustments_collection
                        ->where('shift_id', $att->shift_id)
                        ->where('applied_date_from', '<=', date('Y-m-d', strtotime($attendance_date)))
                        ->where('applied_date_to', '>=', date('Y-m-d', strtotime($attendance_date)))
                        ->first();

                    if (isset($shift)) {
                        // Shift start and end date-time ts
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
                            $break_start = new Carbon(date('d-m-Y H:i', strtotime($att->at_date.' '.$shift->break_start)));
                            $break_end = new Carbon(date('d-m-Y H:i', strtotime($att->at_date.' '.$shift->break_end)));
                            $breakDiff = $break_start->diff($break_end);
                            $break_minutes = $breakDiff->h * 60 + $breakDiff->i;
                            // \Debugbar::info("Have break: Date = $att->at_date, Minutes =  $break_minutes");
                        }
                        // OT Break Code Ends
                    }
                }
                $data = [];

                if ($isBuyerMode) {
                    if (isset($att->clock_out_ts) && isset($att->clock_in_ts)) {
                        if (strtotime($att->clock_out_ts) < strtotime($att->clock_in_ts)) {
                            $att->clock_out_ts = date('Y-m-d H:i:s', strtotime($att->clock_out_ts.' +1day'));
                        }
                    } elseif (isset($att->clock_out_ts) && isset($shift_start_ts)) {
                        if (strtotime($att->clock_out_ts) < strtotime($shift_start_ts)) {
                            $att->clock_out_ts = date('Y-m-d H:i:s', strtotime($att->clock_out_ts.' +1day'));
                        }
                    }

                    if (isset($shift_end_ts)) {
                        $earlyExit = isset($att->clock_out_ts) && ((strtotime($att->clock_out_ts) < strtotime($shift_end_ts)));
                    } else {
                        $earlyExit = false;
                    }

                    if (! $earlyExit && isset($att->clock_out_ts) && isset($shift_end_ts)) {

                        $bmClockOut_TS_Exists = isset($att->bm_clock_out_ts) && ! empty($att->bm_clock_out_ts);
                        $regularClockOut_GT_shift_end = $bmClockOut_TS_Exists && (strtotime($att->clock_out_ts) > strtotime($shift_end_ts));
                        $regularClockOut_GT_bm_clockOut = $bmClockOut_TS_Exists && (strtotime($att->clock_out_ts) > strtotime($att->bm_clock_out_ts));

                        if ($bmClockOut_TS_Exists && $regularClockOut_GT_shift_end && $regularClockOut_GT_bm_clockOut) {
                            $att->clock_out_ts = $att->bm_clock_out_ts;
                            $att->clock_out = date('H:i', strtotime($att->bm_clock_out_ts));
                        }

                        $__tempT1 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($att->clock_out_ts)));
                        $__tempT2 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($shift_end_ts)));
                        $__Diff1 = $__tempT1->diffInMinutes($__tempT2);

                        if ($__Diff1 >= 134) {
                            $__tempHi = $__tempT2->addMinutes(120 + mt_rand(1, 14));
                            $att->clock_out = $__tempHi->format('H:i');
                            $att->clock_out_ts = $__tempHi->format('Y-m-d H:i:s');

                            DB::connection('hrm')->table('attendances')
                                ->where('month', $month)
                                ->where('year', $year)
                                ->where('employee_id', $att->employee_id)
                                ->where('at_date', $att->at_date)
                                ->update([
                                    'bm_clock_in' => $att->clock_in,
                                    'bm_clock_in_ts' => $att->clock_in_ts,
                                    'bm_clock_out' => $att->clock_out,
                                    'bm_clock_out_ts' => $att->clock_out_ts,
                                ]);
                        }
                    }
                } // buyer mode ends

                $data['date'] = $attendance_date;
                $data['shift'] = $att->shift ?? '...';
                $data['clock_in'] = isset($att->clock_in) ? date('h:ia', strtotime($att->clock_in)) : '...';
                $data['clock_out'] = isset($att->clock_out) ? date('h:ia', strtotime($att->clock_out)) : '...';
                $data['overtime'] = '00:00';

                // OverTime Count
                if ($employee->overtime_allowed == 1) {
                    // Weekend
                    if (! $isBuyerMode && in_array($attendance_date_Ymd, $holidays_date_array)) {

                        if (isset($att->clock_in_ts) && isset($att->clock_out_ts)) {
                            // Whole day is overtime
                            $clockOut_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($att->clock_out_ts)));
                            $clockIn_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($att->clock_in_ts)));

                            if ($clockIn_TS->gt($clockOut_TS)) {
                                $clockOut_TS = $clockOut_TS->addDay();
                            }

                            $diff = $clockOut_TS->diff($clockIn_TS);

                            // \Debugbar::info($shiftEnd_TS->format('Y-m-d H:i:s') . '  '. $clockOut_TS->format('Y-m-d H:i:s') .' '. $diff->h.':'.$diff->i );

                            $adjuster = $diff->h * 60 + $diff->i;
                            if (isset($break_minutes) && is_numeric($break_minutes) && isset($att->clock_out_ts) && isset($break_end)) {
                                $breakEnd_Time = new Carbon($break_end);

                                if ($clockOut_TS->gt($breakEnd_Time)) {
                                    if ($adjuster > $break_minutes) {
                                        $adjuster -= $break_minutes;
                                    }
                                }
                            }

                            $hour = intval($adjuster / 60);
                            $__minute = intval($adjuster % 60);

                            // \Debugbar::info("$hour : $__minute");

                            $minutes = 0;
                            if ($__minute > 14 && $__minute < 40) {
                                $minutes = 30;
                            } elseif ($__minute >= 40 && $__minute <= 60) {
                                $hour += 1;
                                $minutes = 0;
                            } elseif ($__minute >= 0 && $__minute < 15) {
                                $minutes = 0;
                            }

                            $hour = ($hour >= 7) ? $hour - 1 : $hour;
                            $hour = ($hour < 9) ? '0'.$hour : $hour;
                            $minutes = $minutes < 10 ? '0'.$minutes : $minutes;

                            if (! is_numeric($hour)) {
                                // \Debugbar::info("Wrong with  H at $att->at_date =  $hour");
                            }
                            if (! is_numeric($minutes)) {
                                // \Debugbar::info("Wrong with i $minutes");
                            }

                            $ot_object = \DateTime::createFromFormat('H:i', "$hour:$minutes");
                            $data['overtime'] = $ot_object->format('H:i');
                        } else {
                            $data['overtime'] = '00:00';
                        }
                        // Weekend logic end
                    } else {

                        // Regular Day Overtime
                        if (isset($att->clock_out_ts) && isset($shift_end_ts)) {

                            $clockOut_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($att->clock_out_ts)));
                            $shiftEnd_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', \strtotime($shift_end_ts)));
                            $t3 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', \strtotime($att->clock_in_ts)));
                            $shiftStart_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', \strtotime($shift_start_ts)));

                            // If clock_out < shift_start
                            if ($shiftStart_TS->gt($clockOut_TS)) {
                                $clockOut_TS = $clockOut_TS->addDay();
                            }

                            if ($clockOut_TS->gt($shiftEnd_TS)) {

                                $diff = $clockOut_TS->diff($shiftEnd_TS);

                                $adjuster = $diff->h * 60 + $diff->i;
                                if (isset($break_minutes) && is_numeric($break_minutes) && isset($att->clock_out_ts) && isset($break_end)) {
                                    $breakEnd_Time = new Carbon($break_end);

                                    if ($clockOut_TS->gt($breakEnd_Time)) {
                                        if ($adjuster > $break_minutes) {
                                            $adjuster -= $break_minutes;
                                        }
                                    }
                                }
                                // \Debugbar::info($shiftEnd_TS->format('Y-m-d H:i:s') . '  '. $clockOut_TS->format('Y-m-d H:i:s') .' '. $diff->h.':'.$diff->i );

                                $hour = intval($adjuster / 60);
                                $__minute = intval($adjuster % 60);

                                // \Debugbar::info($hour . ':' . $__minute . ' clock out: ' . $clockOut_TS->format('Y-m-d H:i:s') . ' Shift end:' . $shiftEnd_TS->format('Y-m-d H:i:s'));

                                $minutes = 0;
                                if ($__minute > 14 && $__minute < 40) {
                                    $minutes = 30;
                                } elseif ($__minute >= 40 && $__minute <= 60) {
                                    $hour += 1;
                                    $minutes = 0;
                                } elseif ($__minute >= 0 && $__minute < 15) {
                                    $minutes = 0;
                                }
                                $hour = ($hour >= 10) ? $hour - 1 : $hour;
                                $hour = ($hour < 9) ? '0'.$hour : $hour;
                                $minutes = $minutes < 10 ? '0'.$minutes : $minutes;

                                $ot_object = \DateTime::createFromFormat('H:i', "$hour:$minutes");
                                $data['overtime'] = $ot_object->format('H:i');
                            } else {
                                $data['overtime'] = '00:00';
                            }
                        } else {
                            $data['overtime'] = '00:00';
                        }
                    }
                }

                $holidayName = '';
                $leaveName = '';

                $status = isset($att->status) ? $att->status : 'NA';
                $isValid = (strtotime($attendance_date_Ymd) >= strtotime($start_date)) && (strtotime($attendance_date_Ymd) <= strtotime($end_date));
                $isHoliday = in_array($attendance_date_Ymd, $holidays_date_array);
                $isLeave = in_array($attendance_date_Ymd, $leaves_date_array);

                if ($isValid && $isHoliday) {
                    $status = 'Holiday';
                    $hdbName = $holidays_date_type_array["$attendance_date_Ymd"] ?? 'Friday';
                    $holidayName = in_array($hdbName, ['Friday', 'friday', 'Fri', 'Fri Day', 'fri day', ' Friday', 'FriDay', 'Weekend', 'Wekend', 'w', 'weekend', 'weeken', 'WEEKEND', 'Weekday', 'Week day']) ? 'W' : 'H';
                }

                if ($isValid && $isLeave) {
                    $status = 'Leave';
                    $leaveName = $leaves_date_type_array[$attendance_date_Ymd];
                }

                switch ($status) {
                    case 'Present':
                        $data['status'] = 'P';
                        break;

                    case 'Late':
                        $data['status'] = 'L';
                        break;

                    case 'Absent':
                        $data['status'] = 'A';
                        break;

                    case 'Leave':
                        $data['status'] = $leaveName;
                        break;

                    case 'Holiday':
                        $data['status'] = $holidayName;
                        break;

                    case 'Offday':
                        $data['status'] = 'W';
                        break;

                    default:
                        $res = (strtotime($attendance_date_Ymd) >= strtotime($start_date)) && (strtotime($attendance_date_Ymd) <= strtotime($end_date));
                        if ($res) {
                            $data['status'] = 'A';
                        } else {
                            $data['status'] = 'N/A';
                        }
                        break;
                }

                if ($data['status'] == 'P' || $data['status'] == 'L' || $data['status'] == 'Present' || $data['status'] == 'Late') {
                    $total_present += 1;
                } elseif ($isLeave && $isValid) {
                    $total_leave += 1;
                } elseif ($isHoliday && $isValid) {
                    $total_weekend += 1;
                }

                $__HiString = explode(':', $data['overtime']);

                $_total_overtime_minutes = $__HiString[0] * 60 + $__HiString[1];

                if ($isBuyerMode) {

                    if (in_array($attendance_date_Ymd, $holidays_date_array)) {
                        $data['clock_in'] = '...';
                        $data['clock_out'] = '...';
                        $data['early_exit'] = '...';
                        $data['late'] = '...';
                        $data['overtime'] = '...';
                        $data['shift'] = '...';
                        $_total_overtime_minutes = 0;
                    }
                }

                $total_overtime_minutes += $_total_overtime_minutes;
                array_push($results, $data);
            }
            // Iteration (Foreach) Ends
            if ($employee->overtime_allowed != 1) {
                $total_overtime = '0:0';
            } else {
                $GLOBALS['overtime_sum'] += $total_overtime_minutes;
                $total_overtime = (intval($total_overtime_minutes / 60).':'.intval($total_overtime_minutes % 60));
            }

            $employee->results = $results;
            $employee->total_present = $total_present;
            $employee->total_overtime = $total_overtime;
            $employee->total_weekend = $total_weekend;
            $employee->total_leave = $total_leave;

            return $employee;
        });
        $overtime_sum = (intval($GLOBALS['overtime_sum'] / 60).':'.intval($GLOBALS['overtime_sum'] % 60));
        $section_name = isset($section) ? $section->name : 'All Employee';

        return compact(
            'month',
            'year',
            'employees',
            'section_name',
            'attendances_dates',
            'overtime_sum',
        );
    }
}
