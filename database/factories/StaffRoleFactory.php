<?php

namespace Database\Factories;

use App\Models\StaffRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffRole>
 */
class StaffRoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'      => fake()->jobTitle(),
            'count_own'  => fake()->numberBetween(0, 20),
            'count_hire' => fake()->numberBetween(0, 50),
            'sort_order' => 0,
        ];
    }
}
