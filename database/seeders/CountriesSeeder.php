<?php

namespace Database\Seeders;

use \Illuminate\Support\Facades\Http;

use App\Models\Country;

use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawCountries = Http::get('https://restcountries.eu/rest/v2/all')->json();
        $countries = [];
        foreach ($rawCountries as $rawCountry) {
        	$country = [
    			'country_name' => $rawCountry['name'],
    			'country_json' => json_encode($rawCountry),
    		];
        	array_push($countries, $country);
        }

        Country::insert($countries);
    }
}
