<?php

namespace Modules\CRM\Database\factories;

use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\CRM\Entities\Appointments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => $this->faker->randomElement(Customer::pluck('id')),
            'appointor_id' => $this->faker->randomElement(User::pluck('id')),
            'schedule_date' => Carbon::parse(now())->format('Y-m-d'),
            'schedule_time' => Carbon::parse(now())->format('H:i:s'),
            'description' => fake()->paragraph(),
        ];
    }
}
