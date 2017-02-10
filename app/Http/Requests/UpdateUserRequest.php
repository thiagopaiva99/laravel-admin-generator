<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\User;

class UpdateUserRequest extends Request
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
            'name' => 'required|max:60',
            'email' => 'required|email',
            "facebook_id" => "string|max:40",
            "phone" => "string",
            "image_src" => "string",
            "preferred_user" => "boolean",
            "approval_status" => "integer",
            "address" => "string",
            "user_type" => "integer"
        ];
    }
}
