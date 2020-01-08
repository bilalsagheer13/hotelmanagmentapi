<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomPriceRequest;
use App\Models\RoomPrices;
use App\Models\Rooms;
use Illuminate\Http\Request;

class RoomPriceController extends Controller
{
    public function getAllRoomPrices($hotel_id)
    {
        if(empty($hotel_id))
        {
            return response()->json(["data"=>null,"message"=>"Hotel id required", "status"=>false],400);
        }

        $types = RoomPrices::select("room_prices.*", "room_types.name as room_type_name")
            ->join("room_types","room_prices.room_type_id", "=", "room_types.id")
            ->where("room_prices.hotel_id",$hotel_id)->get();
        return response()->json(["data"=>$types,"message"=>"Retrieve Success", "status"=>true],200);
    }

    public function createRoomPrice(RoomPriceRequest $request)
    {
        $price = $request->get("price");
        $hotel_id = $request->get("hotel_id");
        $room_type_id = $request->get("room_type_id");
        $already_added = RoomPrices::where("room_type_id",$room_type_id)->first();
        if($already_added)
        {
            return response()->json(["data"=>null,"message"=>"Room Price for this type already added.", "status"=>false],400);
        }
        $roomPrice = new RoomPrices();
        $roomPrice->price = $price;
        $roomPrice->room_type_id = $room_type_id;
        $roomPrice->hotel_id = $hotel_id;
        $roomPrice->save();
        return response()->json(["data"=>$roomPrice,"message"=>"Room Price created successfully", "status"=>true],200);
    }

    public function updateRoomPrice(RoomPriceRequest $request,RoomPrices $roomPrice)
    {
        $price = $request->get("price");
        $hotel_id = $request->get("hotel_id");
        $room_type_id = $request->get("room_type_id");
        $existing_count = RoomPrices::where("room_type_id",$room_type_id)->where("id","!=",$roomPrice->id)->count();
        if($existing_count > 0)
        {
            return response()->json(["data"=>null,"message"=>"Room Price for this type already added.", "status"=>false],400);
        }
        $roomPrice->price = $price;
        $roomPrice->room_type_id = $room_type_id;

        $roomPrice->save();
        return response()->json(["data"=>$roomPrice,"message"=>"Room Price updated successfully", "status"=>true],200);
    }

    public function deleteRoomPrice(RoomPrices $roomPrice)
    {
        $roomPrice->delete();
        return response()->json(["data"=>null,"message"=>"Room Price deleted successfully", "status"=>true],200);
    }
}
