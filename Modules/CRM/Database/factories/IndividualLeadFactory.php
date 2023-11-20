<?php

namespace Modules\CRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IndividualLeadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\CRM\Entities\IndividualLead::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone_numbers' => $this->faker->randomNumber(6, false),
            'email_addresses' => $this->faker->safeEmail,
            'companies' => $this->faker->address(),
            'description' => $this->faker->address(),
            'additional_information' => $this->faker->address(),
        ];
    }
}
