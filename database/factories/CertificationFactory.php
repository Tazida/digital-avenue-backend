<?php

namespace Database\Factories;

use App\Models\Certification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certification>
 */
class CertificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'      => fake()->words(3, true),
            'issuer'     => fake()->company(),
            'number'     => fake()->bothify('CERT-####-??'),
            'issued_on'  => fake()->dateTimeBetween('-3 years', '-1 month'),
            'expires_on' => fake()->dateTimeBetween('+1 month', '+2 years'),
            'is_public'  => false,
            'sort_order' => 0,
        ];
    }

    public function public(): static
    {
        return $this->state(['is_public' => true]);
    }
}
