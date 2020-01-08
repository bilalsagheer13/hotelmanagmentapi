<?php

use App\Models\RoomCapacity;
use App\Models\RoomPrices;
use App\Models\Rooms;
use App\Models\RoomTypes;
use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rooms::getQuery()->delete();
        RoomCapacity::getQuery()->delete();
        RoomTypes::getQuery()->delete();
        $hotel_id = \App\Models\Hotels::first()->id;
        $capacities = ["single","double","family"];
        $capacity_ids = [];
        foreach($capacities as $capacity)
        {
            $roomCapacity = new RoomCapacity();
            $roomCapacity->name = $capacity;
            $roomCapacity->hotel_id = $hotel_id;
            $roomCapacity->save();
            $capacity_ids[] = $roomCapacity->id;
        }


        $types = ["standard","deluxe"];
        $type_ids = [];
        foreach($types as $type)
        {
            $roomType = new RoomTypes();
            $roomType->name = $type;
            $roomType->hotel_id = $hotel_id;
            $roomType->save();
            $type_ids[] = $roomType->id;
        }


        $faker = \Faker\Factory::create();
        RoomPrices::truncate();
        foreach ($type_ids as $type_id)
        {
            $roomPrice = new RoomPrices();
            $roomPrice->price = $faker->randomNumber(2);
            $roomPrice->room_type_id = $type_id;
            $roomPrice->hotel_id = $hotel_id;
            $roomPrice->save();
        }


        for ($i =1; $i <= 10; $i++)
        {
            $room = new Rooms();
            $room->name = "A".$i;
            $room->room_capacity_id = $faker->randomElement($capacity_ids);
            $room->room_type_id = $faker->randomElement($type_ids);
            $room->hotel_id = $hotel_id;
            $room->image = "images/blank.jpg";
            $room->save();
        }
    }
}
