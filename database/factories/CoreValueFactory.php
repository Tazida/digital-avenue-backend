<?php

namespace Database\Factories;

use App\Models\CoreValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CoreValue>
 */
class CoreValueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'sort_order'  => 0,
        ];
    }
}
