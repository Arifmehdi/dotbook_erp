<?php

namespace Modules\LCManagement\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExportTableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\LCManagement\Entities\ExportTable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phone,
            'exporter_id' => 2,
            'business' => $this->faker->business,
            'alternative_number' => $this->faker->alternative_number,
            'land_line' => $this->faker->land_line,
            'email' => $this->faker->email,
            'date_of_birth' => $this->faker->date_of_birth,
            'id_proof_name' => $this->faker->text,
            'id_proof_number' => $this->faker->number,
            'tex_number' => $this->faker->text,
            'total_export' => $this->faker->text,
            'total_paid' => $this->faker->text,
            'total_due' => $this->faker->text,
            'address' => $this->faker->text,
            'city' => $this->faker->text,
            'state' => $this->faker->state,
            'zip' => $this->faker->postcode,
            'country' => $this->faker->country,

        ];
    }
}
