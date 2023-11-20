<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryAdvanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\Advance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $monthArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        return [
            'employee_id' => $this->faker->randomElement(\Modules\HRM\Entities\Employee::pluck('id')->toArray()),
            'permitted_by' => $this->faker->randomElement(\App\Models\User::pluck('id')->toArray()),
            'date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'amount' => $this->faker->numberBetween(200, 20000),
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(2000, 2025),
            'detail' => $this->faker->word(50),
        ];
    }
}
