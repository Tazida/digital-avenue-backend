<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => EquipmentCategory::factory(),
            'name'        => fake()->words(2, true),
            'capacity'    => fake()->optional()->numerify('## Ton'),
            'qty_own'     => fake()->numberBetween(0, 10),
            'qty_hire'    => fake()->numberBetween(0, 5),
            'unit'        => 'Nos',
            'condition'   => 'In Order',
            'sort_order'  => 0,
        ];
    }
}
