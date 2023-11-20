<?php

namespace Modules\HRM\Imports;

use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\HRM\Entities\Attendance;

class BulkAttendanceImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        $collection->map(function ($row) {
            if (preg_match('/[a-zA-Z]/', $row[0])) {
                exit('
                <h1 style="color: red;">Data format Error!</h1><br>
                <span>Proper format is:- EmployeeID [SPACE] Month/Date/Year [SPACE] Hour(24-format):Minute <br> Separate them [SPACE] with one or multiple spaces. Use single line for single employee record.</span><br><br><br> Error was at this line bellow: ⬇<br><br>
                <span style="color: red">'.$row[0].'</span>
                ');
            }
        });

        $shift_adjustments_collection = DB::connection('hrm')->table('shift_adjustments')->get();

        $collection->map(function ($row) use ($shift_adjustments_collection) {

            [$employeeId, $dateAsFileFormat, $timeAsFileFormat] = \preg_split('/\s+/', trim($row[0]));

            $currentRowTime = date('H:i', strtotime($timeAsFileFormat));

            $str_rep = str_replace('\\', '', $dateAsFileFormat);

            $date = date('d-m-Y', strtotime($str_rep));
            $dateYmd = date('Y-m-d', strtotime($date));

            $currentRowDateTime = "$date $currentRowTime";

            // USER: Detect user from User Id (If the user exists, then it != null)
            $employee = DB::connection('hrm')->table('employees')
                ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
                ->select('employees.id', 'shifts.name as shift_name', 'shifts.id as shift_id')
                ->where('employees.employee_id', $employeeId)
                ->first();
            // If User Found
            if ($employee) {

                $applied_shift = $shift_adjustments_collection
                    ->where('shift_id', $employee->shift_id)
                    ->where('applied_date_from', '<=', date('Y-m-d', strtotime($date)))
                    ->where('applied_date_to', '>=', date('Y-m-d', strtotime($date)))
                    ->first();
                // Updated start_time and late_count
                $applied_shift_id = $applied_shift->shift_id;
                $applied_shift_start_time = $applied_shift->start_time;
                $applied_shift_late_count = $applied_shift->late_count;
                $applied_shift_end_time = $applied_shift->end_time;

                $startClockInTimeString = $applied_shift_start_time;
                $endClockInTimeString = $applied_shift_late_count;

                $startClockInTime = date('H:i', strtotime($startClockInTimeString.'-2 hour'));
                $endClockInTime = date('H:i', strtotime($endClockInTimeString));

                $endClockInDateTime = "$date $endClockInTime";

                // ATTENDANCE OF CURRENT USER AS CURRENT ROW DATA (DATE & TIME): Check if the user Present in $date
                $attendanceToday = DB::connection('hrm')->table('attendances')
                    ->where('employee_id', $employee->id)
                    ->where('at_date', $date)
                    ->first();

                $startClockInDateTime = "$date $startClockInTime";

                $startClockInDateTimeObject = Carbon::createFromFormat('d-m-Y H:i', $startClockInDateTime);
                $currentRowDateTimeObject = Carbon::createFromFormat('d-m-Y H:i', "$currentRowDateTime");

                // $startNewAttendanceForTheShift = && ($currentRowTime > $startClockInTime)
                $startNewAttendanceForTheShift = $currentRowDateTimeObject->gt($startClockInDateTimeObject);

                // If the user not present at current row date, create new row with clock_in time entry in `attendances` table
                if (! isset($attendanceToday) && $startNewAttendanceForTheShift) {

                    // User enters after the startClockInTime (Like >6.00AM for 8.00AM shift)
                    if (strtotime($currentRowTime) >= strtotime($startClockInTime)) {
                        $data = new Attendance();
                        $data->employee_id = $employee->id;
                        // $data->shift = $employee->shift_name;
                        $data->shift_id = $applied_shift_id;
                        $data->at_date = date('d-m-Y', strtotime($date));
                        $data->at_date_ts = date('Y-m-d', strtotime($date));
                        $data->month = date('F', strtotime($date));
                        $data->year = date('Y', strtotime($date));

                        $data->clock_in = date('H:i', strtotime($timeAsFileFormat));
                        $data->clock_in_ts = date('Y-m-d ', strtotime($date)).$timeAsFileFormat; // 2021-09-01 17:12

                        $data->bm_clock_in = $data->clock_in;
                        $data->bm_clock_in_ts = $data->clock_in_ts; // 2021-09-01 17:12

                        if ($applied_shift_start_time) {
                            $office_late_time = strtotime($applied_shift_late_count);
                            $ci_time = strtotime($timeAsFileFormat);
                            if ($ci_time > $office_late_time) {
                                $data->status = 'Late';
                            } else {
                                $data->status = 'Present';
                            }
                        }
                        $data->save();
                    }
                } else {

                    // If not edited manually by Admin/Authority then proceed
                    // Already in table `attendances`, as `clock_in` value
                    if (isset($attendanceToday->at_date)) {
                        $clockInAttendanceDate = date('d-m-Y', strtotime($attendanceToday->at_date));
                    } else {
                        $clockInAttendanceDate = date('d-m-Y', strtotime($date.'-1 day'));
                    }

                    $currentRowDateTimeObject = Carbon::createFromFormat('d-m-Y H:i', $currentRowDateTime);
                    $endClockInDateTimeObject = Carbon::createFromFormat('d-m-Y H:i', $endClockInDateTime);

                    // SAME DATE: If current row $date is same as `clock_in` date
                    if ($clockInAttendanceDate == $date) {

                        if ($attendanceToday->manual_entry != 1) {
                            // Check if the data same as previously entered `clock_in`. If it's same value again, then ignore. Because that already in database as `clock_in` value.
                            if (strtotime($currentRowTime) == strtotime(date('H:i', strtotime($attendanceToday->clock_in)))) {
                                // If Same date, Same time exactly as 8:01 == 8.01 then just ignore the operation
                            } else {

                                // Detect same date clock_out. ( if time > 8:10, the $endClockInTime, then it's regular clock_out happening here)
                                // If the entry surpass the 8:10 the end clock in time then make it as clock_out, ignore otherwise

                                $employeeClockOutAction = $currentRowDateTimeObject->gt($endClockInDateTimeObject);

                                $data = [];
                                $data['clock_out'] = $currentRowTime;
                                $data['clock_out_ts'] = date('Y-m-d ', strtotime($date)).$currentRowTime;

                                $__tempT1 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($data['clock_out_ts'])));
                                $__tempT2 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($dateYmd.' '.$applied_shift_end_time)));
                                $__Diff1 = $__tempT1->diffInMinutes($__tempT2);
                                if ($__Diff1 >= 134) {
                                    $__tempHi = $__tempT2->addMinutes(120 + mt_rand(1, 14));
                                    $data['bm_clock_out'] = $__tempHi->format('H:i');
                                    $data['bm_clock_out_ts'] = $__tempHi->format('Y-m-d H:i:s');
                                } else {
                                    $data['bm_clock_out'] = $data['clock_out'];
                                    $data['bm_clock_out_ts'] = $data['clock_out_ts'];
                                }

                                if ($employeeClockOutAction) {
                                    DB::connection('hrm')->table('attendances')
                                        ->where('employee_id', $employee->id)
                                        ->where('at_date', $date)
                                        ->update($data);
                                }
                            }
                        }
                    } else {

                        // DIFFERENT DATE: If current row time $date is different from clock_in date
                        $attendanceYesterday = DB::connection('hrm')->table('attendances')
                            ->where('employee_id', $employee->id)
                            ->where('at_date', date('d-m-Y', strtotime($date.'-1 day')))
                            ->first();

                        if (isset($attendanceYesterday->manual_entry) && $attendanceYesterday->manual_entry != 1) {
                            // Detect clock_out in different date
                            // $employeeClockOutAction = ($currentRowTime < $startClockInTime);

                            $employeeClockOutAction = $currentRowDateTimeObject->lte($startClockInDateTimeObject);

                            $data = [];
                            $data['clock_out'] = date('H:i', strtotime($currentRowTime));
                            $data['clock_out_ts'] = date('Y-m-d ', strtotime($date)).$currentRowTime;

                            $__tempT1 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($data['clock_out_ts'])));
                            $__tempT2 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($dateYmd.' '.$applied_shift_end_time)));
                            $__Diff1 = $__tempT1->diffInMinutes($__tempT2);
                            if ($__Diff1 >= 134) {
                                $__tempHi = $__tempT2->addMinutes(120 + mt_rand(1, 14));
                                $data['bm_clock_out'] = $__tempHi->format('H:i');
                                $data['bm_clock_out_ts'] = $__tempHi->format('Y-m-d H:i:s');
                            } else {
                                $data['bm_clock_out'] = $data['clock_out'];
                                $data['bm_clock_out_ts'] = $data['clock_out_ts'];
                            }

                            if ($employeeClockOutAction) {
                                DB::connection('hrm')->table('attendances')
                                    ->where('employee_id', $employee->id)
                                    ->where('at_date', date('d-m-Y', strtotime($date.'-1 day')))
                                    ->update($data);
                            }
                        }
                    }
                }
            }
        });
    }
}
