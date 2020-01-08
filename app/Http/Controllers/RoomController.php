<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Models\RoomBookings;
use App\Models\Rooms;
use App\Models\RoomTypes;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function getAllRooms(Request $request)
    {
        $hotel_id = $request->get("hotel_id");
        if(empty($hotel_id))
        {
            return response()->json(["data"=>null,"message"=>"Hotel id required", "status"=>false],400);
        }
        $query = Rooms::select("rooms.*","room_types.name as room_type_name")
            ->join("room_types","rooms.room_type_id","=","room_types.id")
            ->where("rooms.hotel_id",$hotel_id);
        if($request->has("room_capacity_id"))
        {
            $room_capacity_id = $request->get("room_capacity_id");
            $query->where("rooms.room_capacity_id",$room_capacity_id);
        }
        if($request->has("room_type_id"))
        {
            $room_type_id = $request->get("room_type_id");
            $query->where("rooms.room_type_id",$room_type_id);
        }
        $rooms = $query->get();
        $room_types = RoomTypes::where("hotel_id",$hotel_id)->get();
        return response()->json(["data"=>$rooms,"room_types"=>$room_types,"message"=>"Retrieve Success", "status"=>true],200);
    }

    public function getAvailableRooms(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $hotel_id = $request->hotel_id;
        $room_type_id = $request->room_type_id;
        if(empty($start_date) || empty($end_date))
        {
            return response()->json(["data"=>null,"message"=>"Please select correct date range", "status"=>false],400);
        }
        if(empty($hotel_id))
        {
            return response()->json(["data"=>null,"message"=>"Hotel id is required", "status"=>false],400);
        }
        $skip_rooms = RoomBookings::whereBetween("start_date",[$start_date,$end_date])
            ->orWhereBetween("end_date",[$start_date,$end_date])
            ->orWhere(function ($query) use ($start_date,$end_date){
                $query->where("start_date","<=",$start_date)
                    ->where("end_date",">=",$start_date);
            })
            ->orWhere(function ($query) use ($start_date,$end_date){
                $query->where("start_date","<=",$end_date)
                    ->where("end_date",">=",$end_date);
            })
            ->pluck("room_id")->toArray();
        $query = Rooms::select("rooms.*","room_prices.price","room_types.name as room_type_name")
            ->join("room_types","rooms.room_type_id","=","room_types.id")
            ->leftJoin("room_prices","rooms.room_type_id","=","room_prices.room_type_id")
            ->whereNotIn("rooms.id",$skip_rooms);
        if($room_type_id)
        {
            $query->where("rooms.room_type_id",$room_type_id);
        }
        $query->where("rooms.hotel_id",$hotel_id);
        $rooms = $query->get();
        $date1 = new \DateTime($start_date);
        $date2 = new \DateTime($end_date);

        $numberOfNights= $date2->diff($date1)->format("%a");
        foreach ($rooms as $room)
        {
            $room->total_nights = $numberOfNights;
        }
        return response()->json(["data"=>$rooms,"message"=>"Retrieve Success", "status"=>true],200);
    }

    public function getRoomsList($hotel_id)
    {
        if(empty($hotel_id))
        {
            return response()->json(["data"=>null,"message"=>"Hotel id is required", "status"=>false],400);
        }
        $rooms = Rooms::select("id","name")->where("hotel_id",$hotel_id)->get();

        return response()->json(["data"=>$rooms,"message"=>"Retrieve Success", "status"=>true],200);
    }

    public function createRoom(RoomRequest $request)
    {
        $room = new Rooms();
        $room->name = $request->name;
        $room->hotel_id = $request->hotel_id;
        $room->room_capacity_id = $request->room_capacity_id;
        $room->room_type_id = $request->room_type_id;
        if($request->hasFile("new_image"))
        {
            $filename = time().".jpg";
            $dir = "images/hotels/".$room->hotel_id."/rooms/";
            $path = public_path($dir);
            $request->new_image->move($path,$filename);
            $room->image = $dir.$filename;
        }
        $room->save();
        return response()->json(["data"=>$room,"message"=>"Room created successfully", "status"=>true],200);
    }

    public function updateRoom(RoomRequest $request,Rooms $room)
    {
        $room->name = $request->name;
        $room->room_capacity_id = $request->room_capacity_id;
        $room->room_type_id = $request->room_type_id;
        if($request->hasFile("new_image"))
        {
            if(file_exists(public_path($room->image)))
            {
                unlink(public_path($room->image));
            }
            $filename = $room->id."_".time().".jpg";
            $dir = "images/hotels/".$room->hotel_id."/rooms/";
            $path = public_path($dir);
            $request->new_image->move($path,$filename);
            $room->image = $dir.$filename;
        }
        $room->save();
        return response()->json(["data"=>$room,"message"=>"Room updated successfully", "status"=>true],200);
    }

    public function deleteRoom(Rooms $room)
    {
        $roomUse = RoomBookings::where("room_id",$room->id)->where("end_date",">=",date("Y-m-d H:i:s"))->count();
        if($roomUse)
        {
            return response()->json(["data"=>null,"message"=>"This room is being used in $roomUse active bookings", "status"=>true],200);
        }
        $room->delete();
        return response()->json(["data"=>null,"message"=>"Room deleted successfully", "status"=>true],200);
    }
}
