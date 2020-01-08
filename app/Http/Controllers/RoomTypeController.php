<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\RoomTypes;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function getAllRoomTypes($hotel_id)
    {
        if(empty($hotel_id))
        {
            return response()->json(["data"=>null,"message"=>"Hotel id required", "status"=>false],400);
        }

        $types = RoomTypes::where("hotel_id",$hotel_id)->get();
        return response()->json(["data"=>$types,"message"=>"Retrieve Success", "status"=>true],200);
    }

    public function createRoomType(Request $request)
    {
        $name = $request->get("name");
        if(empty($name))
        {
            return response()->json(["data"=>null,"message"=>"Name is required", "status"=>false],400);
        }
        $hotel_id = $request->get("hotel_id");
        if(empty($hotel_id))
        {
            return response()->json(["data"=>null,"message"=>"Hotel id required", "status"=>false],400);
        }
        $roomType = new RoomTypes();
        $roomType->name = $name;
        $roomType->hotel_id = $hotel_id;
        $roomType->save();
        return response()->json(["data"=>$roomType,"message"=>"Room Type created successfully", "status"=>true],200);
    }

    public function updateRoomType(Request $request,RoomTypes $roomType)
    {
        $name = $request->get("name");
        if(empty($name))
        {
            return response()->json(["data"=>null,"message"=>"Name is required", "status"=>false],400);

        }
        $roomType->name = $name;
        $roomType->save();
        return response()->json(["data"=>$roomType,"message"=>"Room Type updated successfully", "status"=>true],200);
    }

    public function deleteRoomType(RoomTypes $roomType)
    {
        $roomTypeUse = Rooms::where("room_type_id",$roomType->id)->pluck("name")->toArray();
        if($roomTypeUse)
        {
            return response()->json(["data"=>null,"message"=>"Room Type is being used in these rooms ".implode(', ',$roomTypeUse).". Please remove that first.", "status"=>false],400);
        }
        $roomType->delete();
        return response()->json(["data"=>null,"message"=>"Room Type deleted successfully", "status"=>true],200);
    }
}
