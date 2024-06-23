<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produits>
 */
class ProduitsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => $this->faker->word,
            'ref' => $this->faker->unique()->randomNumber,
            'prixCharge' => $this->faker->randomFloat(2, 0, 1000),
            'prixVente' => $this->faker->randomFloat(2, 0, 1000),
            'qte' => $this->faker->randomNumber,
            'qteMinGros' => $this->faker->randomNumber,
            'prixGros' => $this->faker->randomFloat(2, 0, 1000),
            'promo' => $this->faker->boolean,
            'longueur' => $this->faker->randomFloat(2, 0, 100),
            'largeur' => $this->faker->randomFloat(2, 0, 100),
            'hauteur' => $this->faker->randomFloat(2, 0, 100),
            'profondeur' => $this->faker->randomFloat(2, 0, 100),
            'tempsProduction' => 20,
            'matiers' => $this->faker->word,
            'description' => $this->faker->sentence,
            'descriptionTechnique' => $this->faker->paragraph,
            'ficheTechnique' => $this->faker->word,
            'publicationSocial' => $this->faker->boolean,
            'fraisTransport' => $this->faker->randomFloat(2, 0, 1000),
            'idCategorie' => 1,
            'imagePrincipale' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean
        ];
    }
}
