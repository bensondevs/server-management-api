<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Http;
use Illuminate\Database\Seeder;

use App\Models\Country;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! file_exists(resource_path('json/countries.json'))) {
            if (! file_exists(resource_path('json'))) {
                shell_exec('mkdir ' . resource_path('json'));
            }

            $countriesArray = Http::get('https://restcountries.com/v3.1/all')->json();
            file_put_contents(resource_path('json/countries.json'), json_encode($countriesArray));
        }

        // Get lists of raw countries from the json file
        $fileContent = file_get_contents(resource_path('json/countries.json'));
        $rawCountries = json_decode($fileContent, true);

        $countries = [];
        foreach ($rawCountries as $rawCountry) {
            $country = [
    			'country_name' => $rawCountry['name']['official'],
    			'country_json' => json_encode($rawCountry),
                'created_at' => now(),
                'updated_at' => now(),
    		];
        	array_push($countries, $country);
        }

        Country::insert($countries);
    }
}
