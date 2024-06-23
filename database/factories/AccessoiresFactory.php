<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accessoires>
 */
class AccessoiresFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'prixAchat' => $this->faker->randomFloat(2, 0, 1000),
            'prixVente' => $this->faker->randomFloat(2, 0, 1000),
            'qte' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean,
        ];
    }
}
