<?php

namespace Modules\CRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessLeadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\CRM\Entities\BusinessLead::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'location' => $this->faker->address(),
            'phone_numbers' => $this->faker->randomNumber(9, false),
            'email_addresses' => $this->faker->safeEmail,
            'total_employees' => \rand(100, 10000),
            'description' => $this->faker->paragraph(),
            'additional_information' => $this->faker->paragraph(),
        ];
    }
}
