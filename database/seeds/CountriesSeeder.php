<?php

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
        $countries = array (
            "Argentina",
            "Australia",
            "Austria",
            "Belgium",
            "Bolivia",
            "Brazil",
            "Canada",
            "Chile",
            "China",
            "Colombia",
            "Croatia",
            "Dominican Republic",
            "Ecuador",
            "Estonia",
            "Finland",
            "France",
            "Germany",
            "Guatemala",
            "Hong Kong",
            "Hungary",
            "India",
            "Indonesia",
            "Israel",
            "Italy",
            "Japan",
            "Korea",
            "Latvia",
            "Lithuania",
            "Malaysia",
            "Mexico",
            "Netherlands",
            "New Zealand",
            "Nepal",
            "Norway",
            "Peru",
            "Philippines",
            "Poland",
            "Portugal",
            "Russia",
            "Singapore",
            "Slovakia",
            "South Africa",
            "Spain",
            "Sweden",
            "Switzerland",
            "Taiwan",
            "Thailand",
            "Turkey",
            "Ukraine",
            "United Kingdom (UK)",
            "United States of America (USA)",
            "Venezuela", 
        );

        foreach($countries as $country){
            \DB::table('countries')->insert([
                'name'=>$country
            ]);
        }
    }
}
