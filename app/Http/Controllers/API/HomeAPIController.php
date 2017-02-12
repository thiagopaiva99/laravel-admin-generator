<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 12/08/16
 * Time: 18:21
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\HomeAPIRequest;
use Illuminate\Http\Request;
use Response;
use DB;

use App\Models\Local;
use App\Models\Specialization;
use App\Models\HealthPlan;
use App\Models\Exam;

class HomeAPIController extends AppBaseController {
    public function showHome(HomeAPIRequest $request, $response = true, $home = false) {

        $plans = HealthPlan::whereNull("health_plan_id")->with("healthPlans")->orderBy("name");

        $specializations = $response ? Specialization::orderBy("name")->get() : Specialization::orderBy("name")->pluck('name', 'id');
        $healthPlans = $home ? HealthPlan::orderBy("name")->pluck('name', 'id') : $plans->get();
        $exams = $response ? Exam::orderBy("name")->get() : Exam::orderBy("name")->pluck('name', 'id');

        $json = [
            "specializations" => $specializations,
            "health_plans" => $healthPlans,
            "exams" => $exams
        ];
        
        return $response ? response()->json($json) : $json;
    }
}