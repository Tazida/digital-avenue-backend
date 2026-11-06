<?php

namespace Database\Factories;

use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'service_id' => null,
            'name'       => fake()->name(),
            'email'      => fake()->safeEmail(),
            'phone'      => fake()->optional()->numerify('+8801#########'),
            'company'    => fake()->optional()->company(),
            'message'    => fake()->paragraph(),
            'status'     => 'new',
        ];
    }
}
