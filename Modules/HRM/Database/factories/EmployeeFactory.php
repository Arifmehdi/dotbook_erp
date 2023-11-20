<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\BdDistrict;
use Modules\Core\Entities\BdDivision;
use Modules\Core\Entities\BdUnion;
use Modules\Core\Entities\BdUpazila;
use Modules\HRM\Entities\HrmDepartment;
use Modules\HRM\Entities\Designation;
use Modules\HRM\Entities\Grade;
use Modules\HRM\Entities\Section;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Entities\SubSection;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'name' => $this->faker->name,
            'email' => $this->faker->name,
            'login_access' => 1,
            'phone' => $this->faker->phoneNumber,
            'alternative_phone' => $this->faker->phoneNumber,
            'photo' => $this->faker->imageUrl(),
            'dob' => $this->faker->date(),
            'nid' => $this->faker->randomNumber(),
            'birth_certificate' => $this->faker->randomNumber(),
            'marital_status' => $this->faker->randomElement(['Married', 'Single', 'Other', 'Divorced']),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'blood' => $this->faker->randomElement(['A', 'B', 'AB', 'O']) . '+',
            'country' => 'Bangladesh',
            'father_name' => $this->faker->name,
            'mother_name' => $this->faker->name,
            'religion' => $this->faker->randomElement(['Islam', 'Hindu', 'Buddha', 'Christian']),
            'home_phone' => $this->faker->phoneNumber,
            'emergency_contact_person_name' => $this->faker->name(),
            'emergency_contact_person_phone' => $this->faker->phoneNumber,
            'emergency_contact_person_relation' => $this->faker->randomElement(['Father', 'Brother', 'Mama', 'Uncle']),
            'present_division_id' => BdDivision::inRandomOrder()->first(),
            'present_district_id' => BdDistrict::inRandomOrder()->first(),
            'present_upazila_id' => BdUpazila::inRandomOrder()->first(),
            'present_union_id' => BdUnion::inRandomOrder()->first(),
            'present_village' => $this->faker->address,
            'permanent_division_id' => BdDivision::inRandomOrder()->first(),
            'permanent_district_id' => BdDistrict::inRandomOrder()->first(),
            'permanent_upazila_id' => BdUpazila::inRandomOrder()->first(),
            'permanent_union_id' => BdUnion::inRandomOrder()->first(),
            'permanent_village' => $this->faker->address,
            'employee_id' => 'ID-10022' . $this->faker->randomNumber(),
            'shift_id' => Shift::inRandomOrder()->first(),
            'hrm_department_id' => HrmDepartment::inRandomOrder()->first(),
            'section_id' => Section::inRandomOrder()->first(),
            'sub_section_id' => SubSection::inRandomOrder()->first(),
            'designation_id' => Designation::inRandomOrder()->first(),
            'grade_id' => Grade::inRandomOrder()->first(),
            'duty_type_id' => $this->faker->randomDigit(),
            'joining_date' => $this->faker->date(),
            'employee_type' => $this->faker->numberBetween(1, 4),
            'salary' => $this->faker->randomElement([2000, 25000, 32102, 201230, 51412, 52140]),
            'starting_shift_id' => Shift::inRandomOrder()->first(),
            'starting_salary' => Grade::inRandomOrder()?->first()?->starting_salary ?? 10_000,
            'employment_status' => 1,
            // 'resign_date' => $this->faker->,
            // 'left_date' => $this->faker->date(),
            // 'termination_date' => $this->faker->date(),
            'bank_name' => $this->faker->randomElement(['Standard Bank', 'Jamuna Bank', 'EBL Bank']),
            'bank_branch_name' => $this->faker->randomElement(['Uttara', 'Basundhara', 'GajiPur']),
            'bank_account_name' => $this->faker->randomElement(['A-S-D', 'F-H-R']),
            'bank_account_number' => $this->faker->randomNumber(),
            'mobile_banking_provider' => $this->faker->randomElement(['Bkash', 'Nagad', 'Amar Pay']),
            'mobile_banking_account_number' => $this->faker->randomNumber(),
        ];
    }
}
