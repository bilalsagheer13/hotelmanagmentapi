<?php

namespace App\Http\Controllers;

use App\Models\RoomCapacity;
use App\Models\Rooms;
use Illuminate\Http\Request;

class RoomCapacityController extends Controller
{
    public function getAllRoomCapacities($hotel_id)
    {
        if(empty($hotel_id))
        {
            return response()->json(["data"=>null,"message"=>"Hotel id required", "status"=>false],400);
        }

        $capacities = RoomCapacity::where("hotel_id",$hotel_id)->get();
        return response()->json(["data"=>$capacities,"message"=>"Retrieve Success", "status"=>true],200);
    }

    public function createRoomCapacity(Request $request)
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
        $roomCapacity = new RoomCapacity();
        $roomCapacity->name = $name;
        $roomCapacity->hotel_id = $hotel_id;
        $roomCapacity->save();
        return response()->json(["data"=>$roomCapacity,"message"=>"Room Capacity created successfully", "status"=>true],200);
    }

    public function updateRoomCapacity(Request $request,RoomCapacity $roomCapacity)
    {
        $name = $request->get("name");
        if(empty($name))
        {
            return response()->json(["data"=>null,"message"=>"Name is required", "status"=>false],400);

        }
        $roomCapacity->name = $name;
        $roomCapacity->save();
        return response()->json(["data"=>$roomCapacity,"message"=>"Room Capacity updated successfully", "status"=>true],200);
    }

    public function deleteRoomCapacity(RoomCapacity $roomCapacity)
    {
        $roomCapacityUse = Rooms::where("room_capacity_id",$roomCapacity->id)->pluck("name")->toArray();
        if($roomCapacityUse)
        {
            return response()->json(["data"=>null,"message"=>"Room Capacity is being used in these rooms ".implode(', ',$roomCapacityUse).". Please remove that first.", "status"=>false],400);
        }
        $roomCapacity->delete();
        return response()->json(["data"=>null,"message"=>"Room Capacity deleted successfully", "status"=>true],200);
    }
}
