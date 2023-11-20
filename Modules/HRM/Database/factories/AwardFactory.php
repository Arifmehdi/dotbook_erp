<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\HRM\Entities\Employee;

class AwardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\Award::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $monthArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        return [
            'employee_id' => $this->faker->randomElement(Employee::pluck('id')->toArray()),
            'award_name' => strtoupper($this->faker->word(4)),
            'award_description' => $this->faker->paragraph(5),
            'gift_item' => $this->faker->word(4),
            'award_by' => $this->faker->word(2),
            'date' => $this->faker->date(),
            'month' => $this->faker->randomElement($monthArray),
            'year' => $this->faker->numberBetween(2000, 2023),
        ];
    }
}
