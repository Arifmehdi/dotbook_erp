<?php

namespace Modules\HRM\Service;

use App\Interface\FileUploaderServiceInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Enums\EmployeeType;
use Modules\HRM\Enums\EmploymentStatus;
use Modules\HRM\Enums\JobAppliedStatus;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Modules\Website\Entities\JobApply;

class EmployeeService implements EmployeeServiceInterface
{
    public function __construct(
        private FileUploaderServiceInterface $fileUploader,
    ) {
    }

    public function all()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $employee = Employee::with(['section', 'designation', 'grade'])->orderBy('id', 'desc');

        return $employee;
    }

    public function store(array $attribute)
    {
        abort_if(!auth()->user()->can('hrm_master_list_index'), 403, 'Access Forbidden');
        DB::beginTransaction();
        try {
            if (isset($attribute['login_access']) && $attribute['login_access'] == 1) {
                User::create([
                    'name' => $attribute['name'],
                    'username' => $attribute['username'],
                    'email' => $attribute['email'],
                    'password' => bcrypt($attribute['password']),
                ]);
            } else {
                $attribute['login_access'] = 0;
                unset($attribute['username']);
                unset($attribute['password']);
                unset($attribute['password_confirmation']);
            }

            if (isset($attribute['p_same']) && $attribute['p_same'] == 1) {
                $attribute['present_division_id'] = $attribute['permanent_division_id'];
                $attribute['present_district_id'] = $attribute['permanent_district_id'];
                $attribute['present_upazila_id'] = $attribute['permanent_upazila_id'];
                $attribute['present_union_id'] = $attribute['permanent_union_id'];
                $attribute['present_village'] = $attribute['permanent_village'];
            }

            if (isset($attribute['photo'])) {
                $attribute['photo'] = $tmpImagePath = $this->fileUploader->upload($attribute['photo'], 'uploads/employees');
            }

            $attribute['salary'] = $attribute['starting_salary'];
            $employee = Employee::create($attribute);
            if (!isset($employee) or empty($employee)) {
                File::delete('uploads/employees/' . $tmpImagePath);
            }
            DB::commit();

            if (isset($attribute['job_candidate_id']) && !empty($attribute['job_candidate_id'])) {
                $jobApplicant = JobApply::find($attribute['job_candidate_id']);
                if (isset($jobApplicant)) {
                    $jobApplicant->status = JobAppliedStatus::ConvertToEmployee;
                    $jobApplicant->save();
                }
            }

            return $employee;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getLastInsertedEmployee()
    {
        abort_if(!auth()->user()->can('hrm_employees_view'), 403, 'Access Forbidden');
        $employee = Employee::orderBy('id', 'desc')->first();

        return $employee;
    }

    public function find($id)
    {
        abort_if(!auth()->user()->can('hrm_employees_view'), 403, 'Access Forbidden');
        $employee = Employee::find($id);

        return $employee;
    }

    public function update(array $attribute, $id)
    {
        abort_if(!auth()->user()->can('hrm_employees_update'), 403, 'Access Forbidden');
        DB::beginTransaction();
        try {
            $employee = Employee::find($id);
            if (isset($attribute['allowed_login']) && $attribute['allowed_login'] == 1) {
                $employee->update([
                    'name' => $attribute['name'],
                    'username' => $attribute['username'],
                    'email' => $attribute['email'],
                    'password' => bcrypt($attribute['password']),
                ]);
            } else {
                $attribute['allowed_login'] = 0;
                unset($attribute['username']);
                unset($attribute['password']);
                unset($attribute['password_confirmation']);
            }

            if (isset($attribute['p_same']) && $attribute['p_same'] == 1) {
                $attribute['present_division_id'] = $attribute['permanent_hrm_department_id'];
                $attribute['present_district_id'] = $attribute['permanent_district_id'];
                $attribute['present_upazila_id'] = $attribute['permanent_upazila_id'];
                $attribute['present_union_id'] = $attribute['permanent_union_id'];
                $attribute['present_village'] = $attribute['permanent_village'];
            }

            if (isset($attribute['photo'])) {
                $attribute['photo'] = $tmpImagePath = $this->fileUploader->upload($attribute['photo'], 'uploads/employees');
            }

            $attribute['salary'] = $attribute['starting_salary'];
            $employee = $employee->update($attribute);
            if (!isset($employee) or empty($employee)) {
                File::delete('uploads/employees/' . $tmpImagePath);
            }

            DB::commit();

            return $employee;
        } catch (Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }

    //Get Trashed Item list
    public function getTrashedItem()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $item = Employee::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Get Trashed Item list
    public function trashedEmployeeBuilder()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $item = Employee::onlyTrashed()->orderBy('id', 'desc');

        return $item;
    }

    //Move To Trash
    public function trash($id)
    {
        abort_if(!auth()->user()->can('hrm_employees_delete'), 403, 'Access Forbidden');
        $item = Employee::find($id);
        if ($item->employment_status > 0) {
            $item->employment_status = EmploymentStatus::Delete;
        }
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash($ids)
    {
        abort_if(!auth()->user()->can('hrm_employees_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Employee::find($id);
            if ($item->employment_status > 0) {
                $item->employment_status = EmploymentStatus::Delete;
            }
            if ($item->resign_date != null) {
                $item->resign_date = null;
            }
            if ($item->left_date != null) {
                $item->left_date = null;
            }
            if ($item->termination_date != null) {
                $item->left_date = null;
            }
            $item->delete($item);
            $item->save();
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete($id)
    {
        abort_if(!auth()->user()->can('hrm_employees_delete'), 403, 'Access Forbidden');
        $item = Employee::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete($ids)
    {
        abort_if(!auth()->user()->can('hrm_employees_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Employee::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore($id)
    {
        abort_if(!auth()->user()->can('hrm_employees_delete'), 403, 'Access Forbidden');
        $item = Employee::withTrashed()->find($id);
        if ($item->employment_status > 0) {
            $item->employment_status = EmploymentStatus::Active;
        }
        $item->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore($ids)
    {
        abort_if(!auth()->user()->can('hrm_employees_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = Employee::withTrashed()->find($id);
            if ($item->employment_status > 0) {
                $item->employment_status = EmploymentStatus::Active;
            }
            $item->restore($item);
            $item->save();
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $employee = Employee::count();

        return $employee;
        // $count = Employee::where('employment_status', EmploymentStatus::Active)->count();
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $count = Employee::onlyTrashed()->count();

        return $count;
    }

    //Get Row Count
    public function getRowCountForLeft()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $count = Employee::where('left_date', '!=', null, 'or')->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getRowCountForResigned()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $count = Employee::where('resign_date', '!=', null)->count();

        return $count;
    }

    // Make Left Employee
    public function makeLeftEmployee($request, $id)
    {
        abort_if(!auth()->user()->can('hrm_employees_update'), 403, 'Access Forbidden');
        $request->validate(['left_date' => 'required']);
        $employee = Employee::find($id);
        $employee->left_date = $request->left_date;
        if ($employee->employment_status > 0) {
            $employee->employment_status = EmploymentStatus::Left;
        }
        $employee->update();

        return $employee;
    }

    // Resigned Employee List
    public function resignedEmployee()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $employees = Employee::where('resign_date', '!=', null)->where('employment_status', EmploymentStatus::Resign)->get();

        return $employees;
    }

    // Resigned Employee List
    public function resignEmployeeBuilder()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $resignedEmployeeBuilder = Employee::where('employment_status', EmploymentStatus::Resign)
            ->with([
                'grade',
                'shift',
                'section',
                'department',
                'designation',
                'permanentUnion',
                'permanentUpazila',
                'permanentDistrict',
                'permanentDivision',
                'presentUnion',
                'presentUpazila',
                'presentDistrict',
                'presentDivision',
            ]);

        return $resignedEmployeeBuilder;
    }

    // Left Employee List
    public function activeEmployee()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        // $employees = Employee::where('employment_status', EmploymentStatus::Active)->get();
        $employees = Employee::where('employment_status', EmploymentStatus::Active);

        return $employees;
    }

    // Left Employee List
    public function leftEmployee()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        // $employees = Employee::where('left_date', '!=', null)->where('employment_status', EmploymentStatus::Left)->get();
        $employees = Employee::where('employment_status', EmploymentStatus::Left)->get();

        return $employees;
    }

    public function leftEmployeeBuilder()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $leftEmployeeBuilder = Employee::where('employment_status', EmploymentStatus::Left)
            ->with([
                'grade',
                'shift',
                'section',
                'department',
                'designation',
                'permanentUnion',
                'permanentUpazila',
                'permanentDistrict',
                'permanentDivision',
                'presentUnion',
                'presentUpazila',
                'presentDistrict',
                'presentDivision',
            ]);

        return $leftEmployeeBuilder;
    }

    // Make Resign Employee
    public function makeResignedEmployee($request, $id)
    {
        abort_if(!auth()->user()->can('hrm_employees_update'), 403, 'Access Forbidden');
        $request->validate([
            'resign_date' => 'required',
        ]);

        $employee = Employee::find($id);
        $employee->resign_date = $request->resign_date;
        $employee->employment_status = EmploymentStatus::Resign;
        $employee->save();

        return $employee;
    }

    public function getItemByFilter($request)
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');

        if ($request->showTrashed == 'true') {
            $query = $item = Employee::onlyTrashed()->orderBy('id', 'desc');
        } else {
            $query = Employee::with(['section', 'designation', 'grade'])->orderBy('id', 'desc');
        }
        if ($request->hrm_department_id) {
            $query->where('hrm_department_id', $request->hrm_department_id);
        }
        if ($request->shift_id) {
            $query->where('shift_id', $request->shift_id);
        }
        if ($request->designation_id) {
            $query->where('designation_id', $request->designation_id);
        }
        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->grade_id) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->employee_id) {
            $query->where('id', $request->employee_id)->where('employment_status', EmploymentStatus::Active);
        }

        if ($request->employment_status && null != $request->employment_status) {
            $query->where('employment_status', $request->employment_status);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('joining_date', [$form_date, $to_date]); // Final
        }

        return $query;
    }

    // Resigned and left employee index
    public function resignedAndLeftEmployee($request)
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        if ($request->showTrashed == 'true') {
            $query1 = Employee::onlyTrashed()->where('resign_date', '!=', null);
            $query2 = Employee::onlyTrashed()->where('left_date', '!=', null);
            $query = $query1->union($query2);
        } else {
            $query = Employee::with(['section', 'designation', 'grade'])
                ->where('resign_date', '!=', null, 'or')
                ->where('left_date', '!=', null, 'or')
                ->orderBy('id', 'desc');
        }
        if ($request->hrm_department_id) {
            $query->where('hrm_department_id', $request->hrm_department_id);
        }
        if ($request->shift_id) {
            $query->where('shift_id', $request->shift_id);
        }
        if ($request->designation_id) {
            $query->where('designation_id', $request->designation_id);
        }
        if ($request->grade_id) {
            $query->where('grade_id', $request->grade_id);
        }
        if ($request->employment_status == 'left') {
            $query->where('left_date', '!=', null);
        }
        if ($request->employment_status == 'resign') {
            $query->where('resign_date', '!=', null);
        }
        if ($request->employment_status == 0) {
            $query->where('left_date', '=', null);
            $query->where('resign_date', '=', null);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('joining_date', [$form_date, $to_date]); // Final
        }

        return $query->get();
    }

    public function getEmployee_DailyRemuneration(int $id): float
    {
        abort_if(!auth()->user()->can('hrm_employees_update'), 403, 'Access Forbidden');
        $employee = Employee::find($id);
        $gross = $employee->salary;

        return round($gross / 30);
    }

    public function activeEmployeesWithOtherInfo(): iterable
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $employee = DB::connection('hrm')->table('employees')
            ->where('employees.employment_status', EmploymentStatus::Active->value)
            ->leftJoin('shifts', 'shifts.id', 'employees.shift_id')
            ->leftJoin('sections', 'sections.id', 'employees.section_id')
            ->select(
                'employees.id',
                'employees.employee_id',
                'employees.name',
                'employees.present_district_id',
                'employees.present_upazila_id',
                'employees.present_union_id',
                'employees.present_village',
                'employees.phone',
                'employees.salary',
                'employees.joining_date',
                'shifts.name as shift_name',
                'sections.name as section_name'
            )
            ->orderBy('employee_id')
            ->get();

        return $employee;
    }

    public function getById(int $id): ?Employee
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');

        return Employee::query()
            ->where('employees.id', $id)
            ->where('employees.employee_type', EmployeeType::Employee)
            ->leftJoin('shifts', 'shifts.id', 'employees.shift_id')
            ->leftJoin('grades', 'grades.id', 'employees.grade_id')
            ->leftJoin('hrm_departments', 'hrm_departments.id', 'employees.hrm_department_id')
            ->leftJoin('sub_sections', 'sub_sections.id', 'employees.sub_section_id')
            ->leftJoin('designations', 'designations.id', 'employees.designation_id')
            ->select(
                'employees.id',
                'employees.employee_id',
                'employees.name as employee_nme',
                // 'employees.present_address',
                'employees.phone',
                'employees.joining_date',
                'employees.salary',
                'shifts.name as shift_name',
                'hrm_departments.name as department_name',
                'designations.name as designation_name',
                'sub_sections.name as subsection_name',
                'grades.basic',
                'grades.house_rent',
                'grades.medical',
                'grades.food',
                'grades.transport',
            )
            ->first();
    }

    // Active Employee List
    public function employeeActiveList()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $employees = Employee::active()->pluck('employee_id', 'name');

        return $employees;
    }

    public function allEmployeeListWithSelected()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $allEmployee = Employee::orderBy('employee_id', 'asc')->get(['id', 'employee_id', 'name']);

        return $allEmployee;
    }

    public function employeeActiveListWithId()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access Forbidden');
        $employees = Employee::active()
            // ->where('employee_type', EmployeeType::Employee)
            ->select('employee_id', 'name', 'id')
            ->get();

        return $employees;
    }

    public function employeeList($request)
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access forbidden');
        $query = DB::connection('hrm')->table('employees')
            ->leftJoin('hrm_departments', 'employees.hrm_department_id', 'hrm_departments.id')
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('sub_sections', 'employees.sub_section_id', 'sub_sections.id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id')
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->orderBy('employees.id', 'desc');
        if ($request->hrm_department_id) {
            $query->where('employees.hrm_department_id', $request->hrm_department_id);
        }
        if ($request->section_id) {
            $query->where('employees.section_id', $request->section_id);
        }
        if ($request->sub_section_id) {
            $query->where('employees.sub_section_id', $request->sub_section_id);
        }
        if ($request->designation_id) {
            $query->where('employees.designation_id', $request->designation_id);
        }
        if ($request->shift_id) {
            $query->where('employees.shift_id', $request->shift_id);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $query->where('clock_in_ts', '>=', $from_date);
            $query->where('clock_out_ts', '<=', $to_date);
        }

        $employees = $query->select(
            'employees.*',
            'employees.name as employee_name',
            'employees.employee_id as employeeId',
            'sections.name as section_name',
            'shifts.name as shift_name'
        );

        return $employees;
    }

    // Active Employee List
    // public function activeEmployeeList()
    // {
    //     $employees = $this->employeeService->employeeActiveList();
    //     return response()->json($employees);
    // }

    // public function activeEmployeeListWithId()
    // {
    //     $employees = $this->employeeService->employeeActiveListWithId();
    //     return response()->json($employees);
    // }

    public function activeEmployeeListForID($request)
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access forbidden');
        $query = DB::connection('hrm')->table('employees')
            // ->where('employment_status', EmploymentStatus::Active)
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('grades', 'employees.grade_id', 'grades.id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id')
            ->leftJoin('hrm_departments', 'employees.hrm_department_id', 'hrm_departments.id')
            ->orderBy('id', 'desc')
            ->leftJoin(config('database.connections.mysql.database') . '.bd_districts', 'employees.present_district_id', 'bd_districts.id')
            ->leftJoin(config('database.connections.mysql.database') . '.bd_divisions', 'employees.present_division_id', 'bd_divisions.id')
            ->leftJoin(config('database.connections.mysql.database') . '.bd_unions', 'employees.present_union_id', 'bd_unions.id')
            ->leftJoin(config('database.connections.mysql.database') . '.bd_upazilas', 'employees.present_upazila_id', 'bd_unions.id')
            ->orderBy('employees.id', 'desc')
            ->select(
                'employees.id',
                'employees.name as employee_name',
                'employees.employee_id',
                'employees.photo',
                'employees.phone',
                'employees.joining_date',
                'employees.employment_status',
                'employees.present_village',
                'employees.present_union_id',
                'employees.present_upazila_id',
                'employees.present_district_id',
                'employees.present_division_id',
                'employees.resign_date',
                'employees.left_date',
                'employees.termination_date',
                'employees.print_count',
                'shifts.name as shift_name',
                'designations.name as designation_name',
                'hrm_departments.name as department_name',
                'sections.name as section_name',
                'grades.name as grade_name',
                config('database.connections.mysql.database') . '.bd_districts.name as district_name',
                config('database.connections.mysql.database') . '.bd_divisions.name as division_name',
                config('database.connections.mysql.database') . '.bd_unions.name as union_name',
                config('database.connections.mysql.database') . '.bd_upazilas.name as upazila_name'
            );
        if ($request->hrm_department_id) {
            $query->where('employees.hrm_department_id', $request->hrm_department_id);
        }

        if ($request->section_id) {
            $query->where('employees.section_id', $request->section_id);
        }

        if ($request->grade_id) {
            $query->where('employees.grade_id', $request->grade_id);
        }
        if ($request->designation_id) {
            $query->where('employees.designation_id', $request->designation_id);
        }
        if ($request->employment_status) {
            $query->where('employees.employment_status', $request->employment_status);
        }

        if ($request->employee_id) {
            $query->where('employees.id', $request->employee_id);
        }
        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('employees.joining_date', [$form_date, $to_date]);
        }

        return $query;
    }

    public function adminTypeEmployee()
    {
        abort_if(!auth()->user()->can('hrm_employees_index'), 403, 'Access forbidden');
        $admin_type_employee = DB::connection('hrm')->table('employees')
            // ->where('employment_status', EmploymentStatus::Active)
            ->leftJoin('shifts', 'employees.shift_id', 'shifts.id')
            ->leftJoin('sections', 'employees.section_id', 'sections.id')
            ->leftJoin('grades', 'employees.grade_id', 'grades.id')
            ->leftJoin('designations', 'employees.designation_id', 'designations.id')
            ->leftJoin('hrm_departments', 'employees.hrm_department_id', 'hrm_departments.id')
            ->orderBy('id', 'desc')
            ->where('employees.employee_type', EmployeeType::Admin)
            ->get([
                'employees.id',
                'employees.name as employee_name',
                'employees.employee_id',
                'employees.photo',
                'employees.phone',
                'employees.joining_date',
                'employees.employment_status',
                'employees.present_village',
                'employees.present_union_id',
                'employees.present_upazila_id',
                'employees.present_district_id',
                'employees.present_division_id',
                'employees.resign_date',
                'employees.left_date',
                'employees.termination_date',
                'shifts.name as shift_name',
                'designations.name as designation_name',
                'hrm_departments.name as department_name',
                'sections.name as section_name',
                'grades.name as grade_name',
            ]);

        return $admin_type_employee;
    }
}
