<?php

namespace App\Http\Requests\API;

use App\Http\Requests\Request;
use App\Models\User;
class UserUpdateAPIRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->has("email")) {
            if(\Auth::check()) {
                $userRoute = $this->route("user");
                $userAuth = User::find(\Auth::id());

                $email = $this->get("email");

                $userEmail = User::where("email", $email)->first();

                return $userRoute->id == $userAuth->id && ($userEmail == null || $userRoute->email == $email);
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required|max:60",
            'email' => 'required|email|max:255',
            "facebook_id" => "string|max:40",
            "image_src" => "string|max:255",
            "phone" => "string|max:20",
            "address" => "string|max:255",
            "password" => "string|max:255",
            "health_plans" => "array",
            "private_health_plan" => "required|boolean"
        ];
    }
}
