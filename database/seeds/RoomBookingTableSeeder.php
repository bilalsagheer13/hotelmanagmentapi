<?php

use App\Models\Customers;
use App\Models\RoomBookings;
use Illuminate\Database\Seeder;

class RoomBookingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        /*Customers::truncate();
        for($i = 0; $i < 10; $i++)
        {
            $customer = new Customers();
            $customer->first_name = $faker->firstName;
            $customer->last_name = $faker->lastName;
            $customer->email = $faker->email;
            $customer->phone_no = $faker->e164PhoneNumber;
            $customer->address = $faker->address;
            $customer->country = $faker->country;
            $customer->city = $faker->city;
            $customer->fax = null;
            $customer->user_id = random_int(1,10);
            $customer->hotel_id = 1;
            $customer->save();
        }*/
        $room_ids = \App\Models\Rooms::pluck("id")->toArray();
        $hotel_id = \App\Models\Hotels::first()->id;
        RoomBookings::truncate();
        for($i = 0; $i < 10; $i++ )
        {
            $booking = new RoomBookings();
            $booking->hotel_id = $hotel_id;
            $booking->room_id = $faker->randomElement($room_ids);
            $booking->customer_name = $faker->name;
            $booking->customer_email = $faker->email;
            $booking->start_date = $faker->dateTimeBetween('next Monday','next Monday +7 days')->format("Y-m-d H:i:s");
            $booking->end_date = date("Y-m-d H:i:s", (strtotime($booking->start_date) + (random_int(1,10) * 24*60*60)));
            $booking->total_price = $faker->randomNumber(2);
            $booking->save();
        }
    }
}
