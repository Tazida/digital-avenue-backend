<?php

namespace Database\Factories;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Office>
 */
class OfficeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'label'          => fake()->city(),
            'address'        => fake()->address(),
            'phone'          => fake()->optional()->numerify('+8801#########'),
            'map_url'        => null,
            'is_head_office' => false,
            'sort_order'     => 0,
        ];
    }
}
