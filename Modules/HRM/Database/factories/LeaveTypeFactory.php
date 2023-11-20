<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\LeaveType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => strtoupper($this->faker->word(2)),
            'for_months' => $this->faker->numberBetween(1, 12),
            'days' => $this->faker->numberBetween(1, 30),
            'is_active' => $this->faker->boolean(50),
        ];
    }
}
