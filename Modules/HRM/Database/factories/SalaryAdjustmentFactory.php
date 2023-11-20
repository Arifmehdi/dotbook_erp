<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryAdjustmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\SalaryAdjustment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => $this->faker->randomElement(\Modules\HRM\Entities\Employee::pluck('id')->toArray()),
            'type' => $this->faker->numberBetween(1, 2),
            'amount' => $this->faker->numberBetween(5000, 50000),
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(2000, 2023),
            'description' => $this->faker->word(50),
        ];
    }
}
