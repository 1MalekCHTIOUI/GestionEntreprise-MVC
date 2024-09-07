<?php

namespace Database\Factories;

use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ParameterFactory extends Factory
{
    protected $model = Parameter::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'timbre_fiscale' => '1',
            'tva' => '19',
            'fodec' => '1',
            'cachet' => 'cachet.png',
            'logo' => 'logo.png',
            'titre' => 'Entreprise X',
            'tel' => '123456789',
            'email' => 'example@example.com',
            'adresse' => '123 Main St, City, Country',
            'numero_fiscal' => '1234567890'
        ];
    }
}
