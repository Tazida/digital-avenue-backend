<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'slug'         => Str::slug($title),
            'title'        => Str::ucfirst($title),
            'category'     => fake()->randomElement(array_keys(Service::CATEGORIES)),
            'summary'      => fake()->sentence(),
            'description'  => fake()->paragraph(),
            'sort_order'   => 0,
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(['is_published' => false]);
    }
}
