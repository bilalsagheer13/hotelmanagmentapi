<?php

use App\Models\Hotels;
use Illuminate\Database\Seeder;

class HotelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let->s clear the users table first
        Hotels::getQuery()->delete();

        $faker = \Faker\Factory::create();

        $hotel = new Hotels();
        $hotel->name = $faker->name." Hotel";
        $hotel->email = $faker->email;
        $hotel->phone_no = $faker->e164PhoneNumber;
        $hotel->address = $faker->address;
        $hotel->country = $faker->country;
        $hotel->city = $faker->city;
        $hotel->state = $faker->state;
        $hotel->zip_code = $faker->postcode;
        $hotel->image = str_replace("public/","",$faker->image('public/images',400,300));
        $hotel->user_id = 1;
        $hotel->save();
    }
}
