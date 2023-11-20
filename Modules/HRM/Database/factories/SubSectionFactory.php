<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\HRM\Entities\Section;

class SubSectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\SubSection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'section_id' => Section::inRandomOrder()->first(),
            'name' => $this->faker->word().' - '.$this->faker->randomNumber(),
        ];
    }
}
