<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customers;
use App\Models\RoomBookings;
use App\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getAllCustomers($hotel_id)
    {
        if(empty($hotel_id))
        {
            return response()->json(["data"=>null,"message"=>"Hotel id required", "status"=>false],400);
        }

        $types = Customers::where("hotel_id",$hotel_id)->get();
        return response()->json(["data"=>$types,"message"=>"Retrieve Success", "status"=>true],200);
    }

    public function updateCustomer(CustomerRequest $request)
    {
        $customer = Customers::where("user_id",$request->user_id)->first();
        if(!$customer)
        {
            $customer = new Customers();
        }
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone_no = $request->phone_no;
        $customer->address = $request->address;
        $customer->country = $request->country;
        $customer->fax = $request->fax;
        $customer->city = $request->city;
        $customer->user_id = $request->user_id;
        $customer->hotel_id = $request->hotel_id;
        $customer->save();
        $user = User::find($request->user_id);
        $user->name = $customer->first_name . " " . $customer->last_name;
        $user->save();

        return response()->json(["data"=>$customer,"message"=>"Customer Profile updated successfully", "status"=>true],200);
    }

    public function deleteCustomer(Customers $customer)
    {
        $customerUse = RoomBookings::where("customer_id",$customer->id)->where("end_date",">=",date("Y-m-d H:i:s"))->count();
        if($customerUse)
        {
            return response()->json(["data"=>null,"message"=>"This customer have  ".$customerUse." active bookings. Please remove these first.", "status"=>false],400);
        }
        $user = User::find($customer->user_id);
        $user->delete();
        $customer->delete();

        return response()->json(["data"=>null,"message"=>"Customer deleted successfully", "status"=>true],200);
    }
}
