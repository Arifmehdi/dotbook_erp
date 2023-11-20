<?php

namespace Modules\LCManagement\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LcmanagementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\LCManagement\Entities\Lcmanagement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'asiaBank',
        ];
    }
}
