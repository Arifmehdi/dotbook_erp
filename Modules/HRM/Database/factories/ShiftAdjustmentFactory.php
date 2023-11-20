<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\HRM\Entities\ShiftAdjustment;

class ShiftAdjustmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShiftAdjustment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shift_id' => $this->faker->randomElement(\Modules\HRM\Entities\Shift::pluck('id')->toArray()),
            'start_time' => $this->faker->time('H:i:s', 'now'),
            'end_time' => $this->faker->time('H:i:s', 'now'),
            'late_count' => $this->faker->time('H:i:s', 'now'),
            'applied_date_from' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'applied_date_to' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'with_break' => $this->faker->boolean(50),
            'break_start' => $this->faker->time('H:i:s', 'now'),
            'break_end' => $this->faker->time('H:i:s', 'now'),
        ];
    }
}
