<?php

namespace Modules\HRM\Database\Seeders;

use DB;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Modules\HRM\Entities\Grade;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Entities\Holiday;
use Modules\HRM\Entities\Section;
use Modules\Core\Entities\BdUnion;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\LeaveType;
use Modules\HRM\Enums\EmployeeType;
use Modules\HRM\Entities\SubSection;
use Modules\Core\Entities\BdDistrict;
use Modules\HRM\Entities\Designation;
use Illuminate\Support\Facades\Schema;
use Modules\HRM\Entities\HrmDepartment;
use Illuminate\Database\Schema\Blueprint;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Entities\ShiftAdjustment;
use Modules\HRM\Entities\LeaveApplication;
use Modules\HRM\Entities\SalaryAdjustment;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->seedHrmDepartments();
        // $this->seedSections();
        // $this->seedSubSections();
        // $this->seedDesignation();
        // $this->seedGrades();
        // $this->seedShifts();
        // $this->seedShiftAdjustments();
        // $this->seedHolidays();
        // $this->seedLeaveTypes();
        // $this->seedEmployeesRaw();
        // $this->seedLeaveApplications();
        // $this->seedSalaryAdjustments();
        // $this->call(SettingTableSeeder::class);


        $this->seedAttendances();
    }

    public function seedLeaveTypes(): void
    {
        $leaveTypes = DB::connection('old')->table('leavetype')->get();
        foreach ($leaveTypes as $leaveType) {
            LeaveType::create([
                'id' => $leaveType->id,
                'name' => $leaveType->type_name,
                'for_months' => 12,
                'days' => $leaveType->days,
                'is_active' => 1,
                'deleted_at' => $leaveType?->deleted_at ?? null,
                'created_at' => $leaveType->created_at,
                'updated_at' => $leaveType->updated_at,
            ]);
        }
    }

    public function seedLeaveApplications(): void
    {
        $leaveApplications = DB::connection('old')->table('leaveapplications')->leftJoin('users', 'leaveapplications.user_id', 'users.id')->where('users.is_admin', '!=', 1)->where('users.user_type', 2)->get();
        foreach ($leaveApplications as $leaveApplication) {
            if (LeaveApplication::where('id', $leaveApplication->id)->doesntExist() && Employee::whereId($leaveApplication->user_id)->exists()) {
                LeaveApplication::create([
                    'id' => $leaveApplication->id,
                    'employee_id' => $leaveApplication->user_id,
                    'leave_type_id' => $leaveApplication->type_id,
                    'from_date' => $leaveApplication->approve_start_date,
                    'to_date' => $leaveApplication->approve_end_date,
                    'approve_day' => $leaveApplication->approve_day,
                    'reason' => $leaveApplication->reason,
                    'attachment' => $leaveApplication?->hard_copy ?? null,
                    'status' => $leaveApplication->status,
                    'created_at' => $leaveApplication->created_at,
                    'updated_at' => $leaveApplication->updated_at,
                    'deleted_at' => $leaveApplication?->deleted_at ?? null,
                ]);
            }
        }
    }

    public function seedHolidays(): void
    {
        $old_holidays = DB::connection('old')->table('holidays')->get();
        foreach ($old_holidays as $oldHoliday) {
            Holiday::create([
                'id' => $oldHoliday->id,
                'name' => $oldHoliday->holiday_name,
                'type' => $oldHoliday->type,
                'from' => $oldHoliday->from,
                'to' => $oldHoliday->to,
                'num_of_days' => $oldHoliday->num_of_days,
                'created_at' => $oldHoliday->created_at,
                'updated_at' => $oldHoliday->updated_at,
            ]);
        }
    }

    public function seedAttendances(): void
    {
        $attendances = DB::connection('old')
            ->table('attendances')
            ->where('year', '2023')
            ->where('month', 'January')
            // ->where('month', 'February')
            // ->where('month', 'March')
            ->whereNotNull('shift_id')
            ->whereNotIn('id', Attendance::pluck('id')->toArray())
            ->select(
                "attendances.id",
                "attendances.user_id as employee_id",
                "attendances.clock_in",
                "attendances.clock_out",
                "attendances.at_date",
                "attendances.at_date_ts",
                "attendances.clock_in_ts",
                "attendances.clock_out_ts",
                "attendances.month",
                "attendances.year",
                "attendances.bm_clock_in",
                "attendances.bm_clock_in_ts",
                "attendances.bm_clock_out",
                "attendances.bm_clock_out_ts",
                "attendances.shift_id",
                "attendances.holiday_id",
                "attendances.shift",
                "attendances.leave_type",
                "attendances.status",
                "attendances.manual_entry",
            )
            ->limit(5)
            ->get()
            ->toArray();

        // if (Schema::hasColumns('hrm', ['user_id', 'holiday_id', 'leave_type', 'shift'])) {
        //     Schema::connection('hrm')->dropColumns('attendances', 'holiday_id');
        // }
        DB::connection('hrm')->table('attendances')->insert($attendances);
    }

    public function optimizeAttendanceTable(): void
    {
        Schema::connection('hrm')->table('attendances', function (Blueprint $table) {

            if (Schema::hasColumn('attendances', 'user_id')) {
                Schema::rename('user_id', 'employee_id');
            }
            $table->string('clock_in', 5)->nullable()->change();
            $table->string('clock_out', 5)->nullable()->change();
            $table->string('at_date', 10)->nullable()->change();
            $table->timestamp('at_date_ts')->nullable()->change();
            $table->timestamp('clock_in_ts')->nullable()->change();
            $table->timestamp('clock_out_ts')->nullable()->change();
            $table->string('month', 9)->nullable()->change();
            $table->year('year')->nullable()->change();
            $table->string('bm_clock_in', 5)->nullable()->change();
            $table->timestamp('bm_clock_in_ts')->nullable()->change();
            $table->string('bm_clock_out', 5)->nullable()->change();
            $table->timestamp('bm_clock_out_ts')->nullable()->change();
            $table->foreignIdFor(Shift::class)->constrained()->onDelete('cascade');
            // $table->integer('holiday_id')->nullable()->change();
            // $table->string('shift', 191)->nullable()->change();
            // $table->string('leave_type', 191)->nullable()->change();
            $table->string('status', 10)->nullable()->change();
            $table->boolean('manual_entry')->nullable()->change()->default(false);
            $table->unique(['employee_id', 'at_date'], 'UserId_AttendanceDate_Unique');
        });
    }

    public function seedEmployeesRaw(): void
    {
        $employees = DB::connection('old')->table('users')->where('is_admin', '!=', 1)->where('user_type', 2)->get();
        foreach ($employees as $employee) {
            try {
                if (isset($employee->employee_id) && Employee::where('employee_id', $employee->employee_id)->doesntExist()) {
                    Schema::disableForeignKeyConstraints();
                    $employee = Employee::firstOrCreate([
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'phone' => $employee->phone,
                        'alternative_phone' => $employee->alternative_phone,
                        'photo' => $employee->photo,
                        'dob' => Carbon::parse($employee->dob)?->format('Y-m-d'),
                        'nid' => $employee->nid,
                        'birth_certificate' => $employee?->birth_certificate ?? null,
                        'marital_status' => $employee->marital_status,
                        'gender' => $employee->gender,
                        'blood' => $employee->blood,
                        'country' => $employee->country,
                        'father_name' => $employee->father_name,
                        'mother_name' => $employee->mother_name,
                        'religion' => $employee->religion,
                        'email' => $employee->email,
                        'login_access' => $employee?->login_access ?? null,
                        'home_phone' => $employee->home_phone,

                        'emergency_contact_person_name' => $employee->emergency_contact_person,
                        'emergency_contact_person_phone' => $employee->emergency_contact,
                        'emergency_contact_person_relation' => $employee->emergency_contact_relation,

                        'present_division_id' => BdDistrict::where('id', $employee->present_district_id)?->first()?->division_id,
                        'present_district_id' => $employee->present_district_id,
                        'present_upazila_id' => $employee->present_thana_id,
                        'present_union_id' => BdUnion::where('name', $employee->present_post_office)->first()?->id,
                        'present_village' => $employee->present_address,

                        'permanent_division_id' => BdDistrict::where('id', $employee->district_id)?->first()?->division_id,
                        'permanent_district_id' => $employee->district_id,
                        'permanent_upazila_id' => $employee->thana_id,
                        'permanent_union_id' => $employee->postoffice_id,
                        'permanent_village' => $employee->permanent_address,

                        'employee_id' => $employee->employee_id,
                        'shift_id' => $employee->shift_id,
                        'hrm_department_id' => $employee->department_id,
                        'section_id' => $employee->division_id,
                        'sub_section_id' => $employee->subsection_id,
                        'designation_id' => $employee->position_id,
                        'grade_id' => $employee->grade_id,
                        'duty_type_id' => $employee->duty_type === 'Full Time' ? 1 : null,
                        'joining_date' => $employee?->joining_date ?? today()->subYears(2),
                        'employee_type' => $employee?->employee_type ?? EmployeeType::Employee,
                        'salary' => $employee?->rate ?? 0,
                        'starting_shift_id' => $employee->starting_shift,
                        'starting_salary' => $employee->starting_salary,
                        'employment_status' => $employee->type_status,
                        'resign_date' => $employee->resign_date,
                        'left_date' => $employee->left_date,
                        'termination_date' => $employee->termination_date,
                        'bank_name' => $employee->bank_name,
                        'bank_branch_name' => $employee->branch,
                        'bank_account_name' => $employee->account_holder,
                        'bank_account_number' => $employee->account_number,
                        'mobile_banking_provider' => isset($employee->nagad) ? 'Nagad' : (isset($employee->rocket) ? 'Rocket' : 'Bkash'),
                        'mobile_banking_account_number' => isset($employee->nagad) ? $employee->nagad : (isset($employee->rocket) ? $employee->rocket : $employee->bkash),
                        'created_at' => $employee->created_at,
                        'updated_at' => $employee->updated_at,
                        'deleted_at' => $employee?->deleted_at ?? null,
                    ]);
                    Schema::disableForeignKeyConstraints();
                }
            } catch (Exception $exception) {
                // dd($exception);
            }
        }
    }
    public function seedEmployees(): void
    {
        $employees = DB::connection('old')->table('users')->where('is_admin', '!=', 1)->where('user_type', 2)->get();
        foreach ($employees as $employee) {
            try {
                Schema::disableForeignKeyConstraints();
                if (isset($employee->employee_id)) {
                    $param1 = $present_division_id =  BdDistrict::where('id', $employee->present_district_id)?->first()?->division_id;
                    // if (!$present_division_id) {
                    //     dd('present_district_id ==> ' . $employee->present_district_id, $present_division_id);
                    // }
                    $param2 = $present_union_id =  BdUnion::where('name', $employee->present_post_office)->first()?->id;
                    // if (!$present_union_id) {
                    //     dd('present_post_office ==> ' . $employee->present_post_office);
                    // }
                    $param3 = $permanent_division_id =  BdDistrict::where('id', $employee->district_id)?->first()?->division_id;
                    // if (!$permanent_division_id) {
                    //     dd('district_id ==> ' . $employee->district_id);
                    // }
                    $param4 = $shift_id =  Shift::where('id', $employee->shift_id)->first()?->id;
                    // if (!$shift_id) {
                    //     dd('shift_id ==> ' . $employee->shift_id);
                    // }
                    $param5 = $hrm_department_id =  HrmDepartment::where('id', $employee->department_id)->first()?->id;
                    // if (!$hrm_department_id) {
                    //     dd('department_id ==> ' . $employee->department_id);
                    // }
                    $param6 = $section_id =  Section::where('id', $employee->division_id)->first()?->id;
                    // if (!$section_id) {
                    //     dd('division_id ==> ' . $employee->division_id);
                    // }
                    $param7 = $sub_section_id =  SubSection::where('id', $employee->sub_section_id)->first()?->id;
                    // if (!$sub_section_id) {
                    //     dd('sub_section_id ==> ' . $employee->sub_section_id);
                    // }
                    $param8 = $designation_id =  Designation::where('id', $employee->position_id)->first()?->id ?? Designation::first()->id;
                    // if (!$designation_id) {
                    //     dd('position_id ==> ' . $employee->position_id);
                    // }
                    $param9 = $grade_id =  Grade::where('id', $employee->grade_id)->first()?->id ?? Grade::first()->id;
                    // if (!$grade_id) {
                    //     dd('grade_id ==> ' . $employee->grade_id);
                    // }

                    $user =
                        isset($param1) &&
                        isset($param2) &&
                        isset($param3) &&
                        isset($param4) &&
                        isset($param5) &&
                        isset($param6) &&
                        isset($param7) &&
                        isset($param8) &&
                        isset($param9);

                    if ($user) {
                        $employee = Employee::firstOrCreate([
                            'id' => $employee->id,
                            'name' => $employee->name,
                            'phone' => $employee->phone,
                            'alternative_phone' => $employee->alternative_phone,
                            'photo' => $employee->photo,
                            'dob' => Carbon::parse($employee->dob)?->format('Y-m-d'),
                            'nid' => $employee->nid,
                            'birth_certificate' => $employee?->birth_certificate ?? null,
                            'marital_status' => $employee->marital_status,
                            'gender' => $employee->gender,
                            'blood' => $employee->blood,
                            'country' => $employee->country,
                            'father_name' => $employee->father_name,
                            'mother_name' => $employee->mother_name,
                            'religion' => $employee->religion,
                            'email' => $employee->email,
                            'login_access' => $employee?->login_access ?? null,
                            'home_phone' => $employee->home_phone,

                            'emergency_contact_person_name' => $employee->emergency_contact_person,
                            'emergency_contact_person_phone' => $employee->emergency_contact,
                            'emergency_contact_person_relation' => $employee->emergency_contact_relation,

                            'present_division_id' => BdDistrict::where('id', $employee->present_district_id)?->first()?->division_id,
                            'present_district_id' => $employee->present_district_id,
                            'present_upazila_id' => $employee->present_thana_id,
                            'present_union_id' => BdUnion::where('name', $employee->present_post_office)->first()?->id,
                            'present_village' => $employee->present_address,

                            'permanent_division_id' => BdDistrict::where('id', $employee->district_id)?->first()?->division_id,
                            'permanent_district_id' => $employee->district_id,
                            'permanent_upazila_id' => $employee->thana_id,
                            'permanent_union_id' => $employee->postoffice_id,
                            'permanent_village' => $employee->permanent_address,

                            'employee_id' => $employee->employee_id,
                            'shift_id' => Shift::find($employee->shift_id)?->id,
                            'hrm_department_id' => HrmDepartment::find($employee->department_id)?->id,
                            'section_id' => Section::find($employee->division_id)?->id,
                            'sub_section_id' => SubSection::find($employee->sub_section_id)?->id,
                            'designation_id' => Designation::find($employee->position_id)?->id,
                            'grade_id' => Grade::find($employee->grade_id)?->id,
                            'duty_type_id' => $employee->duty_type === 'Full Time' ? 1 : null,
                            'joining_date' => $employee?->joining_date ?? today()->subYears(2),
                            'employee_type' => $employee?->employee_type ?? EmployeeType::Employee,
                            'salary' => $employee?->rate ?? 0,
                            'starting_shift_id' => $employee->starting_shift,
                            'starting_salary' => $employee->starting_salary,
                            'employment_status' => $employee->type_status,
                            'resign_date' => $employee->resign_date,
                            'left_date' => $employee->left_date,
                            'termination_date' => $employee->termination_date,
                            'bank_name' => $employee->bank_name,
                            'bank_branch_name' => $employee->branch,
                            'bank_account_name' => $employee->account_holder,
                            'bank_account_number' => $employee->account_number,
                            'mobile_banking_provider' => isset($employee->nagad) ? 'Nagad' : (isset($employee->rocket) ? 'Rocket' : 'Bkash'),
                            'mobile_banking_account_number' => isset($employee->nagad) ? $employee->nagad : (isset($employee->rocket) ? $employee->rocket : $employee->bkash),
                            'created_at' => $employee->created_at,
                            'updated_at' => $employee->updated_at,
                            'deleted_at' => $employee?->deleted_at ?? null,
                        ]);
                    } else {
                        dd($employee);
                    }
                }
            } catch (Exception $exception) {
                dd($exception);
            }
        }
    }

    public function seedShiftAdjustments(): void
    {
        $shift_adjustments = DB::connection('old')->table('shift_adjustments')->get();
        foreach ($shift_adjustments as $shift_adjustment) {
            ShiftAdjustment::firstOrCreate([
                'id' => $shift_adjustment->id,
                'shift_id' => $shift_adjustment->shift_id,
                'start_time' => $shift_adjustment->start_time,
                'end_time' => $shift_adjustment->end_time,
                'late_count' => $shift_adjustment->late_count,
                'applied_date_from' => $shift_adjustment->applied_date_from,
                'applied_date_to' => $shift_adjustment->applied_date_to,
                'with_break' => $shift_adjustment?->with_break ?? 0,
                'break_start' => $shift_adjustment->break_start,
                'break_end' => $shift_adjustment->break_end,
            ]);
        }
    }

    public function seedShifts(): void
    {
        $shifts = DB::connection('old')->table('shifts')->get();
        foreach ($shifts as $shift) {
            Shift::firstOrCreate([
                'id' => $shift->id,
                'name' => $shift->shift_name,
                'start_time' => $shift->start_time,
                'late_count' => $shift->late_count,
                'end_time' => $shift->end_time,
                'is_allowed_overtime' => $shift->is_allowed_overtime,
            ]);
        }
    }

    public function seedDesignation(): void
    {
        $designations = DB::connection('old')->table('positions')->get();
        foreach ($designations as $designation) {
            $section = Section::where('id', $designation->section_id)->first();
            if (!isset($section)) {
                \Log::info("Not found with {$designation->section_id}");
            } else {
                Designation::firstOrCreate([
                    'id' => $designation->id,
                    'name' => $designation->position_name,
                    'details' => $designation->position_details,
                    'section_id' => $designation->section_id,
                ]);
            }
        }
    }

    public function seedSections(): void
    {
        $sections = DB::connection('old')->table('divisions')->get();
        foreach ($sections as $section) {
            Section::firstOrCreate([
                'id' => $section->id,
                'hrm_department_id' => $section->department_id,
                'name' => $section->division_name,
            ]);
        }
    }

    public function seedSubSections(): void
    {
        $sub_sections = DB::connection('old')->table('subsections')->get();
        foreach ($sub_sections as $sub_section) {
            SubSection::firstOrCreate([
                'id' => $sub_section->id,
                // 'hrm_department_id' => $sub_section->department_id,
                'section_id' => $sub_section->division_id,
                'name' => $sub_section->sub_section_name,
            ]);
        }
    }

    public function seedHrmDepartments(): void
    {
        $departments = DB::connection('old')->table('departments')->get();
        foreach ($departments as $department) {
            try {
                HrmDepartment::firstOrCreate([
                    'id' => $department->id,
                    'name' => $department->department_name,
                ]);
            } catch (Exception $exception) {
            }
        }
    }

    public function seedGrades(): void
    {
        $grades = DB::connection('old')->table('grades')->get();
        foreach ($grades as $grade) {
            \Modules\HRM\Entities\Grade::firstOrCreate([
                'id' => $grade->id,
                'name' => $grade->grades,
                'basic' => $grade->basic,
                'house_rent' => $grade->house_rent,
                'food' => $grade->food,
                'medical' => $grade->medical,
                'transport' => $grade->transport,
                'other' => $grade->other,
            ]);
        }
    }

    public function seedSalaryAdjustments(): void
    {
        // // id`, `employee_id`, `type`, `amount`, `month`, `year`, `description`, `created_at`, `updated_at
        $salary_adjustments = DB::connection('old')->table('otherearns')->get();
        foreach ($salary_adjustments as $s_adjustment) {
            $proceed = Employee::where('id', $s_adjustment->user_id)->exists() && (!SalaryAdjustment::where('id', $s_adjustment->id)->exists());
            if ($proceed) {
                \Modules\HRM\Entities\SalaryAdjustment::firstOrCreate([
                    'id' => $s_adjustment->id,
                    'employee_id' => $s_adjustment->user_id,
                    'type' => ($s_adjustment->amount == null) ? 2 : 1,
                    'amount' => $s_adjustment?->deduction ?? 0,
                    'month' => date('n', strtotime($s_adjustment->month)),
                    'year' => $s_adjustment->year,
                    'description' => $s_adjustment->title,
                    'created_at' => $s_adjustment->created_at,
                    'updated_at' => $s_adjustment->updated_at,
                ]);
            }
        }
    }
}
