<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HolidayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\Holiday::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $holidayTypeArray = ['Offday', 'Holiday'];
        $holidayUserArray = ['SuperAdmin', 'Admin', 'TimeKeeper'];

        return [
            'name' => $this->faker->dayOfWeek($max = 'now'),
            'type' => $this->faker->randomElement($holidayTypeArray),
            'from' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'to' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'num_of_days' => $this->faker->randomDigit(),
        ];
    }
}
