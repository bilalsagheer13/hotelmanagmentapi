<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
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
            "name"=>"required",
            "email"=>"required|email",
            "phone_no"=>"required",
            "country"=>"required",
            "state"=>"required",
            "city"=>"required",
            "address"=>"required",
            "new_image"=>"image",
            "zip_code"=>"required",
        ];
    }
}
