<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\HRM\Entities\HrmDepartment;

class SectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\Section::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'hrm_department_id' => HrmDepartment::inRandomOrder()->first(),
            'name' => $this->faker->word() . ' - ' . $this->faker->randomNumber(),
        ];
    }
}
