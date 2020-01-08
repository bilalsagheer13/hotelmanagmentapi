<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooms extends BaseModel
{
    public function capacity()
    {
        return $this->belongsTo('App\Models\RoomCapacity',"room_capacity_id");
    }
    public function type()
    {
        return $this->belongsTo('App\Models\RoomTypes',"room_type_id");
    }
}
