<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 16/08/16
 * Time: 18:31
 */

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;
use App\Models\User;

class AppointmentAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *AppointmentAPIRequest
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
            "local_id" => "required|integer",
            "time_slot_id" => "required|integer",
            "time_epoch" => "required|numeric",
            "time_slot_detail_id" => "required|integer",
            "patient_name" => "string|max:60",
            "patient_phone" => "string|max:20",
            "patient_email" => "string|max:255",
        ];
    }
}
