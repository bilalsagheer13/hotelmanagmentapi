<?php

namespace App\Http\Requests;

use App\Models\RoomPrices;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "price" => "required|numeric",
            "hotel_id" => "required|integer|exists:hotels,id",
            "room_type_id" => "required|integer|exists:room_types,id"
        ];
    }
}
