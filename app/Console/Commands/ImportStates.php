<?php
//comande bch tmli country lo5erine
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportStates extends Command
{
    protected $signature = 'import:states';
    protected $description = 'Import states for all countries';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtenir les pays depuis la base de données
        $countries = DB::table('countries')->get();

        foreach ($countries as $country) {
            // Obtenir les états/provinces pour chaque pays
            $response = Http::timeout(120)->post('https://countriesnow.space/api/v0.1/countries/states', [
                'country' => $country->name
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $states = $data['data']['states'] ?? [];

                foreach ($states as $state) {
                    DB::table('states')->insert([
                        'country_id' => $country->id,
                        'name' => $state['name'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                $this->error("Failed to fetch data for country: {$country->name}");
            }
        }

        $this->info('States imported successfully');
    }
}
