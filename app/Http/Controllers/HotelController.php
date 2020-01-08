<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelRequest;
use App\Models\Hotels;
use App\Models\RoomBookings;
use App\Models\RoomPrices;
use App\Models\Rooms;
use App\Models\RoomTypes;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function getHotelDetails(Hotels $hotel)
    {
        return response()->json(["data"=>$hotel,"message"=>"Retrieve Success", "status"=>true],200);
    }

    public function updateHotel(HotelRequest $request, Hotels $hotel)
    {
        $hotel->name = $request->name;
        $hotel->email = $request->email;
        $hotel->phone_no = $request->phone_no;
        $hotel->address = $request->address;
        $hotel->country = $request->country;
        $hotel->state = $request->state;
        $hotel->city = $request->city;
        $hotel->zip_code = $request->zip_code;
        if($request->hasFile("new_image"))
        {
            if(file_exists(public_path($hotel->image)))
            {
                unlink(public_path($hotel->image));
            }
            $filename = $hotel->id."_".time().".jpg";
            $dir = "images/hotels/".$hotel->id."/";
            $path = public_path("images/hotels/".$hotel->id."/");
            $request->new_image->move($path,$filename);
            $hotel->image = $dir.$filename;
        }
        $hotel->save();
        return response()->json(["data"=>$hotel,"message"=>"Update Success", "status"=>true],200);
    }

    public function getHotelStats($hotel_id)
    {
        $room_counts = Rooms::where("hotel_id",$hotel_id)->count();
        $type_counts = RoomTypes::where("hotel_id",$hotel_id)->count();
        $price_counts = RoomPrices::where("hotel_id",$hotel_id)->count();
        $booking_counts = RoomBookings::where("hotel_id",$hotel_id)->count();
        $data = ["room_counts"=>$room_counts,"type_counts"=>$type_counts,"price_counts"=>$price_counts,"booking_counts"=>$booking_counts];
        return response()->json(["data"=>$data,"message"=>"Retrieve Success", "status"=>true],200);
    }
}
