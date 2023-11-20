<?php

namespace Modules\Asset\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssetUnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Asset\Entities\AssetUnit::class;

    public function definition()
    {
        return [
            'name' => $this->faker->text(),
            'created_by' => $this->faker->text(),
        ];
    }
}
