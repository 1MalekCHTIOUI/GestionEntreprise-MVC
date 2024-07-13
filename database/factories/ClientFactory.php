<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'nom_societe' => $this->faker->company,
            'tel1' => $this->faker->phoneNumber,
            'tel2' => $this->faker->phoneNumber,
            'whatsapp' => $this->faker->phoneNumber,
            'facebook_page' => $this->faker->url,
            'instagram_account' => $this->faker->userName,
            'linkedin_page' => $this->faker->url,
            'site_web' => $this->faker->url,
            'email' => $this->faker->safeEmail,
            'pays_id' => \App\Models\Country::factory(),
            'gouvernerat_id' => \App\Models\State::factory(),
            'adresse' => $this->faker->address,
            'matricul_fiscal' => $this->faker->randomNumber(8, true),
            'secteur' => $this->faker->word,
            'notes' => $this->faker->sentence,
            'label_id' => \App\Models\Label::factory(),
            'logo' => $this->faker->imageUrl(640, 480, 'business', true),
        ];
    }
}
