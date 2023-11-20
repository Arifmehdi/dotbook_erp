<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OvertimeAdjustmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\OvertimeAdjustment::class;

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
            'ot_minutes' => $this->faker->numberBetween(1, 86400),
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(1970, 2023),
            'description' => $this->faker->word(25),
        ];
    }
}
