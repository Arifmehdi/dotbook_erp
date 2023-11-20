<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\LeaveApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => $this->faker->randomElement(\Modules\HRM\Entities\Employee::pluck('id')->toArray()),
            'leave_type_id' => $this->faker->randomElement(\Modules\HRM\Entities\LeaveType::pluck('id')->toArray()),
            'from_date' => $this->faker->dateTimeBetween('-2 month', 'now'),
            'to_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'approve_day' => $this->faker->numberBetween(1, 30),
            'reason' => $this->faker->paragraph(5),
            'status' => $this->faker->boolean(95),
        ];
    }
}
