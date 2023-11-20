<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\Grade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name' => 'Grade - '.$this->faker->randomNumber(),
            'basic' => $this->faker->numberBetween(6000, 12000),
            'house_rent' => $this->faker->numberBetween(1500, 4000),
            'medical' => $this->faker->numberBetween(600, 1500),
            'food' => $this->faker->numberBetween(500, 1200),
            'transport' => $this->faker->numberBetween(300, 1200),
            'other' => $this->faker->numberBetween(200, 2000),
        ];
    }
}
