<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 12/08/16
 * Time: 18:22
 */
namespace App\Http\Controllers\API;

use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\AppointmentAPIRequest;
use App\Http\Requests\API\HomeAPIRequest;
use App\Models\TimeSlot;
use App\Models\TimeSlotDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Local;
use DB;


class AppointmentAPIController extends AppBaseController {

    /*
     * Métodos Privados
     */

    /**
     * Devolve os agendamentos do usuário (pelo $userId)
     *
     * @param integer $userId
     * @return mixed
     */
    private function listAppointmentsMethods($userId, $site = false) {
        $user = User::find($userId);
        return $site ? $user->appointments->load('timeSlot') : $user->appointments;
    }

    /**
     * Função que gera uma consulta
     *
     * @param AppointmentAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeAppointment(AppointmentAPIRequest $request) {
        $localId = $request->get("local_id");
        $local = Local::find($localId);
        if ($local) {
            $patient = User::find(Auth::id());

            $timeSlotId = $request->get("time_slot_id");
            $timeEpoch = $request->get("time_epoch");

            $appointmentStart = Carbon::createFromTimestamp($timeEpoch, 'America/Sao_Paulo');
            $appointmentEnd = Carbon::createFromTimestamp($timeEpoch, 'America/Sao_Paulo')->addMinutes($local->appointment_duration_in_minutes);

            $appointment = new Appointment;
            $appointment->time_slot_id = $timeSlotId;
            $appointment->time_slot_local_id = $localId;
            $appointment->time_slot_user_id = $local->user_id;
            $appointment->time_slot_detail_id = $request->get('time_slot_detail_id');
            $appointment->appointment_start = $appointmentStart;
            $appointment->appointment_end = $appointmentEnd;

            if ($request->has("patient_name")) {
                $appointment->patient_name = $request->get("patient_name");
            } else {
                $appointment->patient_name = $patient->name;
            }

            if ($request->has("patient_phone")) {
                $appointment->patient_phone = $request->get("patient_phone");
            } else {
                $appointment->patient_phone = $patient->phone;
            }

            if ($request->has("patient_email")) {
                $appointment->patient_email = $request->get("patient_email");
            } else {
                $appointment->patient_email = $patient->email;
            }

            if ($patient->appointments()->save($appointment)) {
                $patient = User::with("appointments", "healthPlans")->where("id", $patient->id)->first();
                return response()->json($patient);
            } else {
                return response()->json("não salvou a consulta!", 409);
            }
        } else {
            return response()->json("não encontrou o local", 404);
        }
    }

    /**
     * Função que apaga uma consulta
     *
     * @param Appointment $appointment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAppointment(Appointment $appointment, Request $request) {
        $appointment->delete();
        $userId = \Auth::id();
        $patient = User::with("appointments", "healthPlans")->where("id", $userId)->first();

        //enviando email
//        $options = array();
//        $options['to'] = 'user';
//        $options['type'] = 'cancel_appointment';
//        EmailController::sendEmail($patient, $options);

        return response()->json($patient);
    }

    /**
     * Função que detalhe uma consulta
     *
     * @param Appointment $appointment
     */
    public function detailAppointment(Appointment $appointment) {
        if($appointment->user_id == Auth::id()) {

            $user = Auth::user();

            DB::statement("SET LC_TIME = 'pt_BR.UTF8';");

            $place = Local::find($appointment->time_slot_local_id);
            $place->load(['exams', 'specializations', 'user']);

            $availableAppointmentData = DB::select("SELECT * FROM fn2_format_appointment($appointment->id);");

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

            if($appointment->timeSlotDetail->private) {
            } else {
                $place->load("healthPlans");
                $place->amount = null;
            }

            return response()->json(["place" => $place]);
        } else {
            return response()->json("Não autorizado", 401);
        }
    }

    /**
     * Função que retorna um JSON com as consultas
     *
     * @param HomeAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAppointments(HomeAPIRequest $request) {
        return response()->json($this->listAppointmentsMethods(\Auth::id()));
    }

    /**
     * Função que retorna um Array com as consultas
     *
     * @param HomeAPIRequest $request
     * @return mixed
     */
    public function listAppointmentsSite(HomeAPIRequest $request) {
        return $this->listAppointmentsMethods(\Auth::id(), true);
    }

}