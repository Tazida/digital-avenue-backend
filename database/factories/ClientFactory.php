<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'       => fake()->unique()->company(),
            'type'       => fake()->randomElement(Client::TYPES),
            'logo_url'   => null,
            'show_logo'  => false,
            'website'    => fake()->optional()->url(),
            'sort_order' => 0,
        ];
    }
}
