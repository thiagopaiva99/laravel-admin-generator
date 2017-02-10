<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 12/08/16
 * Time: 18:22
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\UserAPIRequest;
use App\Http\Requests\API\UserUpdateAPIRequest;
use App\Http\Requests\API\UserLoginAPIRequest;

use App\Models\HealthPlan;
use App\Models\User;

use Auth;

class UserAPIController extends AppBaseController {

    public function registerUser(UserAPIRequest $request, $response = true) {
        $userEmail = $request->get("email");
        $user = User::where("email", $userEmail)->first();
        if($user == null || ($request->has("facebook_id") && $user->facebook_id == $request->get("facebook_id"))) {
            if ($user == null) {
                $user = new User();
            }

            $user->name = $request->get("name");
            $user->email = $request->get("email");
            $user->password = bcrypt($request->get("password"));

            if ($request->has("facebook_id")) {
                $user->facebook_id = $request->get("facebook_id");
            }

            if ($request->has("image_src")) {
                $user->image_src = $request->get("image_src");
            }

            if ($request->has("phone")) {
                $user->phone = $request->get("phone");
            }

            if ($request->has("address")) {
                $user->address = $request->get("address");
            }

            $user->user_type = User::UserTypePatient;

            $user->private_health_plan = $request->get("private_health_plan");

            if ($user->save()) {

                if($request->has("health_plans")) {
                    $user->healthPlans()->detach();

                    $healthPlans = $request->get("health_plans");
                    foreach ($healthPlans AS $healthPlanId) {
                        $healthPlan = HealthPlan::find($healthPlanId);
                        if ($healthPlan) {
                            $user->healthPlans()->attach($healthPlan);
                        }
                    }
                }

                return $response ? $this->showUser($user) : 'Usuário cadastrado com sucesso.';
            } else {
                return $response ?
                    response()->json("Houve um erro ao salvar o usuário. Por favor tente novamente.", 500) :
                    "Houve um erro ao salvar o usuário. Por favor tente novamente.";
            }            
        } else {
            return $response ?
                response()->json("E-mail já cadastrado no sistema.",409) :
                "E-mail já cadastrado no sistema.";
        } 
    }
    
    public function updateUser(User $user, UserUpdateAPIRequest $request, $response = true) {
        $user->name = $request->get("name");
        $user->email = $request->get("email");
        $user->private_health_plan = $request->get("private_health_plan");

        if ($request->exists("password")) {
            $user->password = bcrypt($request->get("password"));
        }
        
        if ($request->exists("facebook_id")) {
            $user->facebook_id = $request->get("facebook_id");
        }

        if ($request->exists("image_src")) {
            $user->image_src = $request->get("image_src");
        }

        if ($request->exists("phone")) {
            $user->phone = $request->get("phone");
        }

        if ($request->exists("address")) {
            $user->address = $request->get("address");
        }

        if ($user->save()) {

            if($user->private_health_plan == true) {
                $user->healthPlans()->detach();
            } else if($request->has("health_plans")) {
                $user->healthPlans()->detach();

                $healthPlans = $request->get("health_plans");
                foreach ($healthPlans AS $healthPlanId) {
                    $healthPlan = HealthPlan::find($healthPlanId);
                    if ($healthPlan) {
                        $user->healthPlans()->attach($healthPlan);
                    }
                }
            }

            return $response ? $this->showUser($user) : true;
        } else {
            return $response ? response()->json("não salvou o usuário", 500) : false;
        }
    }

    public function loginUser(UserLoginAPIRequest $request, $response = true) {
        $email = $request->get("email");
        $password = $request->get("password");

        if (Auth::attempt(['email' => $email, 'password' => $password], true)) {
            // Authentication passed...
            $user = User::find(Auth::id());
            return $response ? $this->showUser($user) : true;
        } else {
            return $response ? response()->json("invalid credentials",401) : false;
        }
    }

    public function showUser(User $user) {
        $user->load("healthPlans", "appointments");
        return response()->json($user);
    }
}