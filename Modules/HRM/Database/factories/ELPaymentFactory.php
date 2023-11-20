<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ELPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\ELPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => $this->faker->randomElement(\Modules\HRM\Entities\Employee::pluck('id')->toArray()),
            'year' => $this->faker->numberBetween(1970, 2023),
            'el_days' => $this->faker->numberBetween(1970, 2023),
            'payment_date' => $this->faker->date(),
            'payment_amount' => $this->faker->numberBetween(5000, 50000),
            'payment_type_id' => $this->faker->randomElement(\Modules\HRM\Entities\PaymentType::pluck('id')->toArray()),
            'remarks' => $this->faker->paragraph(5),
            'status' => $this->faker->boolean(95),
        ];
    }
}
