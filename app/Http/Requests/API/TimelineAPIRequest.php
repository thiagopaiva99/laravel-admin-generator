<?php

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;

class TimelineAPIRequest extends APIRequest
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
            "lat" => "required|numeric|max:90|min:-90",
            "lng" => "required|numeric|max:180|min:-180",

            "id" => "integer",

            "offset" => "integer",
            "limit" => "integer",

            "term" => "string|max:255",

            "max_date" => "numeric",
            "min_date" => "numeric",

            "distance" => "numeric",
            "max_amount" => "numeric",

            "specializations" => "array",
            "exams" => "array",
            "health_plans" => "array",

            "for_date" => 'numeric'
        ];
    }
}
