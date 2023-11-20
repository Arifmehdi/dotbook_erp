<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\Shift::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $shiftNamesArray = ['Shift 1', 'Shift 2', 'Shift 3', 'Shift 4'];

        return [
            'name' => $this->faker->randomElement($shiftNamesArray),
            'start_time' => $this->faker->time('H:i:s', 'now'),
            'late_count' => $this->faker->time('H:i:s', 'now'),
            'end_time' => $this->faker->time('H:i:s', 'now'),
            'is_allowed_overtime' => $this->faker->boolean(50),
        ];
    }
}
