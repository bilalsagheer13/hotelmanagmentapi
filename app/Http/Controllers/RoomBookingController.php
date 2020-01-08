<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomBookingRequest;
use App\Models\RoomBookings;
use App\Models\RoomPrices;
use App\Models\Rooms;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomBookingController extends Controller
{
    public function getAllRoomBookings($hotel_id)
    {
        if(empty($hotel_id))
        {
            return response()->json(["data"=>null,"message"=>"Hotel id required", "status"=>false],400);
        }

        $bookings = RoomBookings::select("bookings.*","rooms.name as room_name","room_types.name as room_type_name","room_prices.price")
            ->join("rooms","rooms.id","=","bookings.room_id")
            ->join("room_types","rooms.room_type_id","=","room_types.id")
            ->leftJoin("room_prices","rooms.room_type_id","=","room_prices.room_type_id")
            ->where("bookings.hotel_id",$hotel_id)->orderBy("bookings.id")->get();
        foreach ($bookings as $booking)
        {
            $date1 = new \DateTime($booking->start_date);
            $date2 = new \DateTime($booking->end_date);
            $numberOfNights= $date2->diff($date1)->format("%a");
            $booking->total_nights = $numberOfNights;
            $booking->start_date = date("Y-m-d",strtotime($booking->start_date));
            $booking->end_date = date("Y-m-d",strtotime($booking->end_date));
        }
        return response()->json(["data"=>$bookings,"message"=>"Retrieve Success", "status"=>true],200);
    }

    public function createRoomBooking(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "start_date" => "required|date",
            "end_date" => "required|after_or_equal:start_date",
            "customer_name" => "required",
            "customer_email" => "required|email",
            "hotel_id" => "required|exists:hotels,id",
            "room_id" => "required|exists:rooms,id",
        ]);
        if($validator->fails())
        {
            return response()->json(["data"=>null,"message"=>$validator->messages()->first(),"status"=>false],400);
        }
        $start_date = $request->get("start_date");
        $end_date = $request->get("end_date");
        $customer_name = $request->get("customer_name");
        $customer_email = $request->get("customer_email");
        $hotel_id = $request->get("hotel_id");
        $room_id = $request->get("room_id");

        $roomBooking = new RoomBookings();
        $roomBooking->start_date = date("Y-m-d H:i:s",(strtotime($start_date)+ (24*60*60)));
        $roomBooking->end_date = date("Y-m-d H:i:s",(strtotime($end_date)+ (24*60*60)));
        $roomBooking->hotel_id = $hotel_id;
        $roomBooking->room_id = $room_id;
        $roomBooking->customer_name = $customer_name;
        $roomBooking->customer_email = $customer_email;
        $total_price = 0;
        $date1 = new \DateTime($start_date);
        $date2 = new \DateTime($end_date);

        $numberOfNights= $date2->diff($date1)->format("%a");
        $price = RoomPrices::join("room_types","room_prices.room_type_id","=","room_types.id")
            ->join("rooms","room_types.id","=","rooms.room_type_id")
            ->where("rooms.id",$room_id)->first();
        if($price)
        {
            $total_price = $price->price * $numberOfNights;
        }
        $roomBooking->total_price = $total_price;
        $roomBooking->save();
        return response()->json(["data"=>$roomBooking,"message"=>"Room Booking created successfully", "status"=>true],200);
    }

    public function updateRoomBooking(RoomBookingRequest $request,RoomBookings $roomBooking)
    {
        $start_date = $request->get("start_date");
        $end_date = $request->get("end_date");
        $room_id = $request->get("room_id");
        $customer_name = $request->get("customer_name");
        $customer_email = $request->get("customer_email");
        $roomBooking->start_date = date("Y-m-d H:i:s", (strtotime($start_date)+ (24*60*60)));
        $roomBooking->end_date = date("Y-m-d H:i:s", (strtotime($end_date)+ (24*60*60)));
        $roomBooking->room_id = $room_id;
        $roomBooking->customer_name = $customer_name;
        $roomBooking->customer_email = $customer_email;
        $total_price = 0;
        $date1 = new \DateTime($start_date);
        $date2 = new \DateTime($end_date);

        $numberOfNights= $date2->diff($date1)->format("%a");
        $price = RoomPrices::join("room_types","room_prices.room_type_id","=","room_types.id")
            ->join("rooms","room_types.id","=","rooms.room_type_id")
            ->where("rooms.id",$room_id)->first();        if($price)
        {
            $total_price = $price->price * $numberOfNights;
        }
        $roomBooking->total_price = $total_price;
        $roomBooking->save();
        return response()->json(["data"=>$roomBooking,"message"=>"Room Booking updated successfully", "status"=>true],200);
    }

    public function deleteRoomBooking(RoomBookings $roomBooking)
    {
        $roomBooking->delete();
        return response()->json(["data"=>null,"message"=>"Room Booking deleted successfully", "status"=>true],200);
    }
}
