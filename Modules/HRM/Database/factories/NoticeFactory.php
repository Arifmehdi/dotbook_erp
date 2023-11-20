<?php

namespace Modules\HRM\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NoticeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\HRM\Entities\Notice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->paragraph(1),
            'description' => $this->faker->paragraph(5),
            'attachment' => $this->faker->imageUrl(),
            'notice_by' => $this->faker->firstName(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
