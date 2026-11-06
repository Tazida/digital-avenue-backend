<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);
        $start = fake()->dateTimeBetween('-8 years', '-1 year');

        return [
            'client_id'      => Client::factory(),
            'slug'           => Str::slug($title),
            'title'          => rtrim($title, '.'),
            'location'       => fake()->city(),
            'start_date'     => $start,
            'end_date'       => fake()->dateTimeBetween($start, 'now'),
            'status'         => 'completed',
            'summary'        => fake()->sentence(12),
            'scope'          => fake()->paragraph(),
            'contract_value' => null,
            'show_value'     => false,
            'is_featured'    => false,
            'is_published'   => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(['is_published' => false]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }
}
