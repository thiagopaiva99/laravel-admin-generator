<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 16/08/16
 * Time: 17:06
 */

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;
use App\Models\User;
use Hash;

class UserAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->has("email")) {
            $userEmail = $this->get("email");
            $user = User::where("email", $userEmail)->first();
            if ($user) {
                // usuário existe no db.

                // validar senha
                if($this->has("password")) {
                    // verificar se a senha bate com a senha do usuário
                    if(!Hash::check($this->get("password"), $user->password)){
                        return response()->json("Ocorreu um erro e você acabou gerando um erro 409", 409);
                    }else{
                        return Hash::check($this->get("password"), $user->password);
                    }
                }

                /*

                // validar facebook
                else if ($this->has("facebook_id")) {
                    // verificar se o facebook id bate
                    return $this->get("facebook_id") == $user->facebook_id;
                }
                */
            } else {
                // não existe usuário com este e-mail!
                return true;
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
            "password" => "required|string|max:255",
            "health_plans" => "array",
            "private_health_plan" => "required|boolean"
        ];
    }
}
