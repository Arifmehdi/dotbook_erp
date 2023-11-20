<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\Visit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $categoryArray = ['Official', 'Unofficial'];

        return [
            'title' => strtoupper($this->faker->word(4)),
            'from_date' => $this->faker->dateTimeBetween('-2 month', 'now'),
            'to_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'category' => $this->faker->randomElement($categoryArray),
            'attachments' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph(5),
        ];
    }
}
