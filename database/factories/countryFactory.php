<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\country>
 */
class countryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->country,
            'short_name' => $this->faker->countryCode,
            'flag_img' => $this->faker->imageUrl(),
            'country_code' => $this->faker->unique()->randomNumber(3)

        ];
    }
}
