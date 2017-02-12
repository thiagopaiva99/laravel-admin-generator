<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 12/08/16
 * Time: 15:49
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\HomeAPIRequest;
use Illuminate\Http\Request;
use Response;
use DB;
use Auth;

use App\Models\Local;

class PlaceAPIController extends AppBaseController {

    public function showPlace(Local $place, $response = true) {
        $user = Auth::user();

        if($user) {
            $place->load(['exams', 'specializations', 'user']);

            DB::statement("SET LC_TIME = 'pt_BR.UTF8';");
            DB::statement("SET LC_MONETARY = 'pt_BR.UTF8';");

            $availableAppointmentData = DB::select("SELECT * FROM fn2_next_available_appointment_for_user($user->id, $place->id) LIMIT 1");

            if($availableAppointmentData && is_array($availableAppointmentData) && sizeof($availableAppointmentData) > 0) {
                $nextAvailableAppointment = $availableAppointmentData[0];
                $place->next_available_appointment_id = $nextAvailableAppointment->time_slot_id;
                $place->next_available_appointment_detail_id = $nextAvailableAppointment->time_slot_detail_id;
                $place->next_available_appointment_str = $nextAvailableAppointment->time_label_formated;
                $place->next_available_appointment_substr = $nextAvailableAppointment->time_sublabel_formated;
                $place->next_available_appointment_epoch = $nextAvailableAppointment->time_epoch;
                $place->next_available_appointment_queue = $nextAvailableAppointment->time_slot_for_queue;
            } else {
                $place->next_available_appointment_id = null;
                $place->next_available_appointment_detail_id = null;
                $place->next_available_appointment_str = null;
                $place->next_available_appointment_substr = null;
                $place->next_available_appointment_epoch = null;
                $place->next_available_appointment_queue = null;
            }

            $doctor = $place->user;
            $otherPlaces = $doctor->locals()->where('id', '!=', $place->id)->get();

            $places = [];

            foreach($otherPlaces AS $otherPlace) {
                $availableAppointmentData = DB::select("SELECT * FROM fn2_next_available_appointment_for_user($user->id, $otherPlace->id) LIMIT 1");
                if($availableAppointmentData && is_array($availableAppointmentData) && sizeof($availableAppointmentData) > 0) {
                    $places[] = $otherPlace;
                }
            }

            if($user->private_health_plan) {
            } else {
                $place->load("healthPlans");
                $place->amount = null;
            }

            $return = [
                "place" => $place,
                "other_places" => $places
            ];

            return $response ? response()->json($return) : $return;
        } else {
            return response()->json("sem usuário", 401);
        }
    }

    public function showAvailableAppointments(Local $place, HomeAPIRequest $request, $response = true) {

        $user = Auth::user();

        if($user) {
            $appointments = DB::select("SELECT * FROM fn2_next_availables_appointments_for_user($user->id, $place->id);");

            return $response ? response()->json($appointments) : $appointments;
        } else {
            return response()->json("sem usuário", 401);
        }

    }
}