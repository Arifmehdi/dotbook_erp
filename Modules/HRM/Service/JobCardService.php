<?php

namespace Modules\HRM\Service;

use Carbon\Carbon;
use DB;
use Modules\Core\Utils\DateTimeUtils;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Interface\JobCardServiceInterface;
use Modules\HRM\Interface\LeaveApplicationServiceInterface;
use Modules\HRM\Interface\OffDaysServiceInterface;

class JobCardService implements JobCardServiceInterface
{
    public function __construct(
        private OffDaysServiceInterface $offDaysService,
        private LeaveApplicationServiceInterface $leaveApplicationService,
    ) {
    }

    public function jobCardPrint($request)
    {
        // $holidays_array = $this->offDaysService->getByMonth($request->month, $request->year);
        abort_if(! auth()->user()->can('hrm_attendance_job_card_print'), 403, 'Access forbidden');
        $employee_id = $request->employee_id;
        $month = $request->month;
        $year = $request->year;
        [
            'month' => $month,
            'year' => $year,
            'employee' => $employee,
            'results' => $results,
            'total_present' => $total_present,
            'total_leave' => $total_leave,
            'total_absent' => $total_absent,
            'total_late' => $total_late,
            'total_overtime' => $total_overtime,
            'total_weekend' => $total_weekend,
            'employee_type' => $employee_type,
            'employee_type_date' => $employee_type_date,
        ] = $this->calculateJobCard($employee_id, $month, $year, $this->leaveApplicationService, $this->offDaysService);

        return compact(
            'month',
            'year',
            'employee',
            'results',
            'total_present',
            'total_leave',
            'total_absent',
            'total_late',
            'total_overtime',
            'total_weekend',
            'employee_type',
            'employee_type_date'
        );
    }

    public function calculateJobCard($employee_id, $month, $year, $leaveApplicationService, $offDaysService)
    {
        abort_if(! auth()->user()->can('hrm_attendance_job_card_calculate'), 403, 'Access forbidden');
        $month_number = date('m', strtotime("$month $year"));
        $_first_day_in_month = "01-$month_number-$year";
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
        $_last_day_in_month = "$days_in_month-$month_number-$year";

        $isBuyerMode = config('app.is_buyer_mode');

        $employee = DB::connection('hrm')->table('employees')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id')
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->where('employees.id', $employee_id)
            ->select('employees.*', 'employees.name as employee_name', 'designations.name as designation_name', 'sections.name as section_name', 'shifts.name', 'shifts.late_count', 'shifts.start_time', 'shifts.end_time')
            ->first();

        $employeeTypeStatus = EmploymentStatus::from($employee->employment_status) ?? EmploymentStatus::Trash;
        $employee_type = '';
        $employee_type_date = '';
        switch ($employeeTypeStatus) {
            case EmploymentStatus::Active:
                $employee_type = 'Active';
                break;

            case EmploymentStatus::Resign:
                $employee_type = 'Resigned';
                $employee_type_date = '('.date('d-m-Y', strtotime($employee->resign_date)).')' ?? '';
                break;

            case EmploymentStatus::Left:
                $employee_type = 'Left';
                $employee_type_date = '('.date('d-m-Y', strtotime($employee->left_date)).')' ?? '';
                break;
            default:
                $employee_type = 'N/A';
                break;
        }
        $shifts_collection = DB::connection('hrm')->table('shifts')->get();
        // Query #6
        $shift_adjustments_collection = DB::connection('hrm')->table('shift_adjustments')->get();

        // Working day count
        $joining_date = date('d-m-Y', strtotime($employee->joining_date));
        $start_date = (\strtotime($joining_date) < strtotime($_first_day_in_month)) ? $_first_day_in_month : $joining_date;
        $end_date = (strtotime(date('d-m-Y')) < strtotime($_last_day_in_month)) ? date('d-m-Y') : $_last_day_in_month;

        $resignedOrLeftOnSameMonth = false;
        $jobCardNotAvailableForThisEmployee = false;

        // If employee resigned
        if ((isset($employee->employment_status) && $employee->employment_status == EmploymentStatus::Resign) && isset($employee->resign_date)) {
            $resign_date = date('d-m-Y', strtotime($employee->resign_date));
            $end_date = strtotime($resign_date) < strtotime($end_date) ? $resign_date : $end_date;
            $resignedOrLeftOnSameMonth = date('m-Y', strtotime($resign_date)) == date('m-Y', strtotime("$month $year"));
            if ($resignedOrLeftOnSameMonth) {
                if (intval(date('d', strtotime($resign_date))) == 1) {
                    $jobCardNotAvailableForThisEmployee = true;
                }
                $end_date = date('d-m-Y', strtotime($end_date.' -1 day'));
            }

            if ((strtotime($resign_date) < strtotime($start_date)) || (strtotime($employee->joining_date) > strtotime($end_date))) {
                $jobCardNotAvailableForThisEmployee = true;
            }
        }

        // If employee left
        if ((isset($employee->employment_status) && $employee->employment_status == EmploymentStatus::Left) && isset($employee->left_date)) {
            $left_date = date('d-m-Y', strtotime($employee->left_date));
            $end_date = strtotime($left_date) < strtotime($end_date) ? $left_date : $end_date;
            $resignedOrLeftOnSameMonth = date('m-Y', strtotime($left_date)) == date('m-Y', strtotime("$month $year"));
            if ($resignedOrLeftOnSameMonth) {
                if (intval(date('d', strtotime($left_date))) == 1) {
                    $jobCardNotAvailableForThisEmployee = true;
                }
                $end_date = date('d-m-Y', strtotime($end_date.' -1 day'));
            }

            if ((strtotime($left_date) < strtotime($start_date)) || (strtotime($employee->joining_date) > strtotime($end_date))) {
                $jobCardNotAvailableForThisEmployee = true;
            }
        }

        if ($jobCardNotAvailableForThisEmployee) {
            $attendance_dates = [];
        } else {
            $attendance_dates = DateTimeUtils::dateRange($start_date, $end_date, 'd-m-Y');
        }

        $employee_attendances = DB::connection('hrm')->table('attendances')
            ->where('employee_id', $employee_id)
            ->where('month', $month)
            ->where('year', $year)
            ->where('at_date', '>=', $start_date)
            ->where('at_date', '<=', $end_date)
            ->select('attendances.*')
            // ->orderBy('at_date', 'ASC')
            // ->distinct('at_date'
            ->get()
            ->sortByDesc('manual_entry')
            ->unique('at_date')
            ->sortBy('at_date');

        $holidays_array = $offDaysService->getByMonth($month, $year);
        $holidays_date_array = $holidays_array['dates_array'];
        // Leave
        $leaveapplicationsArray = $leaveApplicationService->getEmployeeLeaves($employee_id, $month, $year);
        $approved_leaves_date_array = $leaveapplicationsArray['dates_array'];
        $approved_leaves_date_type_array = $leaveapplicationsArray['type_wise'];
        // \Log::info($approved_leaves_date_type_array);

        // Total report calculation initialization
        $total_present = 0;
        $total_leave = 0;
        $total_absent = 0;
        $total_late = 0;
        $total_weekend = 0;
        $total_overtime_minutes = 0;

        $results = [];

        foreach ($attendance_dates as $key => $attendance_date) {
            $attendance_date_Ymd = date('Y-m-d', \strtotime($attendance_date));

            $att = $employee_attendances
                ->where('employee_id', $employee->id)
                // ->whereIn('at_date', [$attendance_date, "$attendance_date ", " $attendance_date", " $attendance_date "])
                ->where('at_date', $attendance_date)
                ->first();
            // For weekend attendance with/without overtime, there is no shift for attendance entry. handle that.
            if (isset($att->shift)) {
                // $shift = $shifts_collection->where('shift_name', $att->shift)->first();
                $shift = $shift_adjustments_collection
                    ->where('shift_id', $att->shift_id) // marif : here before set shift name
                    ->where('applied_date_from', '<=', date('Y-m-d', strtotime($att->at_date)))
                    ->where('applied_date_to', '>=', date('Y-m-d', strtotime($att->at_date)))
                    ->first();

                if (isset($shift)) {
                    // Shift start and end date-time ts
                    $shift_start = $shift->start_time;
                    $shift_start_ts = "$attendance_date $shift_start";
                    $shift_end = $shift->end_time;
                    if (intval($shift_end) <= 12) {
                        $actualDate = date('d-m-Y', strtotime($attendance_date.'+1 day'));
                        $shift_end_ts = "$actualDate $shift_end";
                    } else {
                        $shift_end_ts = "$attendance_date $shift_end";
                    }

                    $shift_late_count_ts = "$attendance_date $shift->late_count";
                    $break_start = null;
                    $break_end = null;
                    $breakDiff = null;
                    $break_minutes = 0;
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

            $data['id'] = $employee->id;
            $data['name'] = $employee->name;
            $data['date'] = $attendance_date;
            $data['shift'] = $att->shift ?? '...';

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

                // marif isset
                // if (isset($shift_end_ts)) { //its also added by myself
                $earlyExit = isset($att->clock_out_ts) && (strtotime($att->clock_out_ts) < strtotime($shift_end_ts));

                if (! $earlyExit && isset($att->clock_out_ts)) {
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
                // } //comment after check its set by myself
            } // buyer mode ends

            $data['clock_in'] = isset($att->clock_in) ? date('h:ia', strtotime($att->clock_in)) : '...';
            $data['clock_out'] = isset($att->clock_out) ? date('h:ia', strtotime($att->clock_out)) : '...';
            $data['clock_in_ts'] = $att->clock_in_ts ?? '...';
            $data['clock_out_ts'] = $att->clock_out_ts ?? '...';

            // OverTime Count
            if ($employee->overtime_allowed == 1) {
                $data['break_remark'] = '';
                // Weekend
                $isWeekend = in_array($attendance_date_Ymd, $holidays_date_array);
                if ($isWeekend) {
                    if (isset($att->clock_in_ts) && isset($att->clock_out_ts) && ! $isBuyerMode) {
                        // Whole day is overtime
                        $clockOut_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($att->clock_out_ts)));
                        $clockIn_TS = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($att->clock_in_ts)));

                        if ($clockIn_TS->gt($clockOut_TS)) {
                            $clockOut_TS = $clockOut_TS->addDay();
                        }
                        $diff = $clockOut_TS->diff($clockIn_TS);

                        $adjuster = $diff->h * 60 + $diff->i;

                        // If clock_out > break_end then reduce (break_end - break_start minutes from OT count)
                        // OTB
                        if (isset($break_minutes) && is_numeric($break_minutes) && isset($att->clock_out_ts) && isset($break_end)) {
                            $breakEnd_Time = new Carbon($break_end);
                            if ($clockOut_TS->gt($breakEnd_Time)) {
                                if ($adjuster > $break_minutes) {
                                    $adjuster -= $break_minutes;
                                }
                                $data['break_remark'] = 'OTB: '.DateTimeUtils::minutesToHourMinutes($break_minutes);
                            }
                        }

                        $hour = intval($adjuster / 60);
                        $__minute = intval($adjuster % 60);
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
                        $ot_object = \DateTime::createFromFormat('H:i', "$hour:$minutes");
                        $data['overtime'] = $ot_object->format('H:i');
                    } else {
                        $data['overtime'] = '00:00';
                    }

                    // Weekend logic end
                } elseif (! $isWeekend) {
                    // Regular Day Overtime
                    //marif add isset
                    if (isset($att->clock_out_ts)) {
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

                            // If clock_out > break_end then reduce (break_end - break_start minutes from OT count)
                            // OTB
                            if (isset($break_minutes) && is_numeric($break_minutes) && isset($att->clock_out_ts) && isset($break_end)) {
                                $breakEnd_Time = new Carbon($break_end);
                                if ($clockOut_TS->gt($breakEnd_Time)) {
                                    if ($adjuster > $break_minutes) {
                                        $adjuster -= $break_minutes;
                                    }
                                    $data['break_remark'] = 'OTB: '.DateTimeUtils::minutesToHourMinutes($break_minutes);
                                }
                            }

                            $hour = intval($adjuster / 60);
                            $__minute = intval($adjuster % 60);
                            // \Debugbar::info("H:i = $hour : $__minute", $shiftStart_TS->format('H:i'), $shiftEnd_TS->format('H:i'), $clockOut_TS->format('H:i'));

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
                            \Log::info($data['overtime']);
                        } else {
                            $data['overtime'] = '00:00';
                        }
                    } else {
                        $data['overtime'] = '00:00';
                    }
                }
            } else {
                $data['overtime'] = '00:00';
                $data['is_overtime_allowed'] = 'N/A';
            }
            // Detect the attendance status P/A/L/W etc.

            $status = $att->status ?? 'A';

            $isHoliday = in_array($attendance_date_Ymd, $holidays_date_array);
            $isLeave = in_array($attendance_date_Ymd, $approved_leaves_date_array);
            $isHolidayAndLeaveBoth = false;

            $holidayName = 'Offday';
            $leaveName = 'Leave';

            if ($isHoliday) {
                $status = 'Offday';
                $holidayName = $holidays_array['type_wise']["$attendance_date_Ymd"];
            }

            if ($isLeave) {
                $status = 'Leave';
                $leaveName = $approved_leaves_date_type_array[$attendance_date_Ymd];
            }

            if ($isHoliday && $isLeave) {
                $status = 'OffdayAndLeave';
                $isHolidayAndLeaveBoth = true;
                $holidayName = $holidays_array['type_wise']["$attendance_date_Ymd"];
                $leaveName = $approved_leaves_date_type_array[$attendance_date_Ymd];
            }

            switch ($status) {
                case 'Present':
                    $data['status'] = 'P';
                    break;

                case 'OffdayAndLeave':
                    $data['status'] = $holidayName.'+'.$leaveName;
                    break;

                case 'Late':
                    $data['status'] = 'L';
                    break;

                case 'Absent':
                    $data['status'] = 'A';
                    break;

                case 'A':
                    $data['status'] = 'A';
                    break;

                case 'Offday':
                    $data['status'] = $holidayName;
                    break;

                case 'W':
                    $data['status'] = 'W';
                    break;

                case 'Leave':
                    $data['status'] = $leaveName;
                    break;

                default:
                    $data['status'] = 'A';
                    break;
            }

            // Present with 'P' || 'L' so handle 'L' for late count individually. BugFix
            if ($data['status'] == 'P' || $data['status'] == 'L' || $data['status'] == 'Present' || $data['status'] == 'Late') {
                $total_present += 1;
            } elseif ($isHolidayAndLeaveBoth) {
                $total_leave += 1;
                // $total_weekend += 1;
            } elseif ($isLeave || $data['status'] == 'SL' || $data['status'] == 'CL' || $data['status'] == 'EL' || $data['status'] == 'ML' || $data['status'] == 'Leave') {
                $total_leave += 1;
            } elseif ($isHoliday || $data['status'] == 'Offday' || $data['status'] == 'W') {
                $total_weekend += 1;
            } elseif ($data['status'] == 'A' || $data['status'] == 'Absent') {
                $total_absent += 1;
            }

            if ($data['status'] == 'L' || $data['status'] == 'Late') {
                $total_late += 1;
            }
            // Late Count
            if (isset($att->clock_in_ts) && isset($shift_late_count_ts) && (strtotime($att->clock_in_ts) > strtotime($shift_late_count_ts))) {
                $t1 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($att->clock_in_ts)));
                $t2 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($shift_start_ts)));
                $diff = $t1->diff($t2);

                $data['late'] = $diff->format('%H:%i');
            } else {
                $data['late'] = '00:00';
            }

            // Early Exit
            if (isset($att->clock_out_ts)) {
                $t1 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($att->clock_out_ts)));
                $t2 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($shift_end_ts)));
                $t4 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($shift_start_ts)));

                if ($t4->gt($t1)) {
                    $t1 = $t1->addDay();
                }

                if ($t1->lt($t2)) {
                    $diff = $t1->diff($t2);
                    $data['early_exit'] = $diff->format('%H:%I');
                } else {
                    $data['early_exit'] = '00:00';
                }
            } else {
                $data['early_exit'] = '00:00';
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
            // $data['break_remark'] ?? \Debugbar::info("Break remark exists");

            array_push($results, $data);
        }
        // Iteration (Foreach) Ends
        if ($employee->overtime_allowed != 1) {
            $total_overtime = '0:0';
        } else {
            $total_overtime = (intval($total_overtime_minutes / 60).':'.intval($total_overtime_minutes % 60));
        }

        return compact(
            'month',
            'year',
            'employee',
            'results',
            'total_present',
            'total_leave',
            'total_absent',
            'total_late',
            'total_overtime',
            'total_weekend',
            'employee_type',
            'employee_type_date'
        );
    }
}
