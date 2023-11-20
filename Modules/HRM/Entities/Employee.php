<?php

namespace Modules\HRM\Entities;

use Modules\Core\Entities\BdDistrict;
use Modules\Core\Entities\BdDivision;
use Modules\Core\Entities\BdUnion;
use Modules\Core\Entities\BdUpazila;
use Modules\HRM\Enums\EmploymentStatus;

class Employee extends BaseModel
{
    // protected $appends = ['permanent_address', 'present_address', 'mobile_banking_number'];

    protected $fillable = [
        'name',
        'phone',
        'alternative_phone',
        'photo',
        'dob',
        'nid',
        'birth_certificate',
        'marital_status',
        'gender',
        'blood',
        'country',
        'father_name',
        'mother_name',
        'religion',
        'email',
        'login_access',
        'home_phone',
        'emergency_contact_person_name',
        'emergency_contact_person_phone',
        'emergency_contact_person_relation',
        'present_division_id',
        'present_district_id',
        'present_upazila_id',
        'present_union_id',
        'present_village',
        'permanent_division_id',
        'permanent_district_id',
        'permanent_upazila_id',
        'permanent_union_id',
        'permanent_village',
        'employee_id',
        'shift_id',
        'hrm_department_id',
        'section_id',
        'sub_section_id',
        'designation_id',
        'grade_id',
        'duty_type_id',
        'joining_date',
        'employee_type',
        'salary',
        'starting_salary',
        'employment_status',
        'resign_date',
        'left_date',
        'termination_date',
        'bank_name',
        'bank_branch_name',
        'bank_account_name',
        'bank_account_number',
        'mobile_banking_provider',
        'mobile_banking_account_number',
    ];

    public function scopeActive($query)
    {
        return $query->where('employment_status', EmploymentStatus::Active);
    }

    public function getGrossSalaryAttribute()
    {
        $grade = $this->grade;

        return $this->salary +
            $grade->house_rent +
            $grade->medical +
            $grade->food +
            $grade->transport +
            $grade->other;
    }

    public function getBeneficialSalaryAttribute()
    {
        $grade = $this->grade;

        return
            $grade->house_rent +
            $grade->medical +
            $grade->food +
            $grade->transport +
            $grade->other;
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function increments()
    {
        return $this->hasMany(Increment::class);
    }

    public function section()
    {
        return $this->hasOne(Section::class, 'id', 'section_id');
    }

    public function hrmDepartment()
    {
        return $this->hasOne(HrmDepartment::class, 'id', 'hrm_department_id');
    }

    public function designation()
    {
        return $this->hasOne(Designation::class, 'id', 'designation_id');
    }

    public function permanentUnion()
    {
        return $this->belongsTo(BdUnion::class, 'permanent_union_id');
    }

    public function permanentUpazila()
    {
        return $this->belongsTo(BdUpazila::class, 'permanent_upazila_id');
    }

    public function permanentDistrict()
    {
        return $this->belongsTo(BdDistrict::class, 'permanent_district_id');
    }

    public function permanentDivision()
    {
        return $this->belongsTo(BdDivision::class, 'permanent_division_id');
    }

    public function getPermanentAddressAttribute()
    {
        return (!empty($this?->permanent_village) ? ($this?->permanent_village . ', ') : null) .
            (!empty($this?->permanentUnion?->name) ? ($this?->permanentUnion?->name . ', ') : null) .
            (!empty($this?->permanentUpazila->name) ? ($this?->permanentUpazila->name . ', ') : null) .
            (!empty($this?->permanentDistrict->name) ? ($this?->permanentDistrict->name . ', ') : null) .
            (!empty($this?->permanentDivision->name) ? ($this?->permanentDivision->name) : null);
    }

    public function presentUnion()
    {
        return $this->belongsTo(BdUnion::class, 'present_union_id');
    }

    public function presentUpazila()
    {
        return $this->belongsTo(BdUpazila::class, 'present_upazila_id');
    }

    public function presentDistrict()
    {
        return $this->belongsTo(BdDistrict::class, 'present_district_id');
    }

    public function presentDivision()
    {
        return $this->belongsTo(BdDivision::class, 'present_division_id');
    }

    public function getPresentAddressAttribute()
    {
        return $this?->present_village . ', ' .
            $this?->presentUnion?->name . ', ' .
            $this?->presentUpazila?->name . ', ' .
            $this?->presentDistrict?->name . ', ' .
            $this?->presentDivision?->name;
    }

    public function getMobileBankingNumberAttribute()
    {
        return '(' . $this->mobile_banking_provider . ') ' . $this->mobile_banking_account_number;
    }

    public function grade()
    {
        return $this->hasOne(Grade::class, 'id', 'grade_id');
    }

    public function shift()
    {
        return $this->hasOne(Shift::class, 'id', 'shift_id');
    }

    protected static function newFactory()
    {
        return \Modules\HRM\Database\factories\EmployeeFactory::new();
    }

    public function leaveapp()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function elPayment()
    {
        return $this->hasMany(ELPayment::class);
    }

    public function awards()
    {
        return $this->hasMany(Award::class);
    }

    public function salaryAdjustment()
    {
        return $this->hasMany(SalaryAdjustment::class);
    }
}
