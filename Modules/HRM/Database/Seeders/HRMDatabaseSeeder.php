<?php

namespace Modules\HRM\Database\Seeders;

use Illuminate\Database\Seeder;

class HRMDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DefaultSeeder::class);
        // $this->call(GradeTableSeeder::class);
        // $this->call(HrmDepartmentTableSeeder::class);
        // $this->call(LeaveTypeTableSeeder::class);
        // $this->call(SectionTableSeeder::class);
        // $this->call(SubSectionTableSeeder::class);
        // $this->call(DesignationTableSeeder::class);
        // $this->call(ShiftTableSeeder::class);
        // $this->call(HolidayTableSeeder::class);
        // $this->call(ShiftAdjustmentTableSeeder::class);
        // $this->call(EmployeeTableSeeder::class);
        // $this->call(LeaveApplicationTableSeeder::class);
        // $this->call(PaymentTypesSeeder::class);
        // $this->call(ELPaymentTableSeeder::class);
        // $this->call(NoticeTableSeeder::class);
        // $this->call(AwardTableSeeder::class);
        // $this->call(SalaryAdjustmentSeederTableSeeder::class);
        // $this->call(OvertimeAdjustmentTableSeeder::class);
        // $this->call(EmployeeTaxAdjustmentTableSeeder::class);
        // $this->call(SalaryAdvanceTableSeeder::class);
        //  $this->call(VisitTableSeeder::class);
    }
}
