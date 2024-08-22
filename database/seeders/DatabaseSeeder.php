<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'nom' => 'chtioui',
        //     'prenom' => 'malek',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\Categories::factory(20)->create();

        \App\Models\Accessoires::factory(5)->create();
        \App\Models\Produits::factory(5)->create();

        \App\Models\Label::factory(5)->create();
        \App\Models\Country::factory(5)->create();
        \App\Models\State::factory(5)->create();
        \App\Models\Client::factory(5)->create();
        \App\Models\Tax::factory(5)->create();
        \App\Models\Parameter::factory()->count(1)->create();




        // $this->call(CreateAdminUserSeeder::class);
    }
}
