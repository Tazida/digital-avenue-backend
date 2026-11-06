<?php

namespace Database\Factories;

use App\Models\CompanyInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanyInfo>
 */
class CompanyInfoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'            => 'Digital Avenue',
            'tagline'         => fake()->sentence(),
            'intro'           => fake()->paragraph(),
            'vision'          => fake()->sentence(),
            'mission'         => implode("\n", fake()->sentences(3)),
            'founder_message' => fake()->paragraph(),
            'email'           => fake()->safeEmail(),
            'phone'           => '+8801000000000',
        ];
    }
}
