<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Admin\EmailController;
use App\Models\Appointment;
use DateTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use URL;
use Mail;

class SiteAppointmentController extends Controller
{
    /**
     * Esta função retorna a VIEW e compacta junto todos os agendamentos do usuario para lista no calendario e fazer a listagem deles
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){
            $title = [
                'big' => 'Meus <strong>agendamentos</strong>'
            ];

            $request = new Requests\API\HomeAPIRequest;
            $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
            $health_plans = $search_fields['health_plans'];

            if (\Auth::check()) {

                $request = new \App\Http\Requests\API\HomeAPIRequest;
                $appointments = app('App\Http\Controllers\API\AppointmentAPIController')->listAppointmentsSite($request);

                return view('site.pages.appointments', compact('title', 'appointments'));

            } else return view('site.pages.register', compact('title', 'health_plans'));
        }else{
            return redirect(URL::previous());
        }

    }

    /**
     * Esta função é para fazer o cancelamento do agendamento
     * @param $appointment_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cancel($appointment_id, $appointment = true){

        if(Auth::check() && Auth::user()->user_type == 3){

            $appointment = Appointment::find($appointment_id);

            $request = Request::create(
                url('/api/v1/appointments/'.$appointment_id),
                'POST',
                [
                    "appointment" => $appointment_id,
                    "patient_email" => Auth::user()->email,
                ]
            );

            $response = app('App\Http\Controllers\API\AppointmentAPIController')->deleteAppointment($appointment, $request);

            flash('Você fez o cancelamento de sua consulta!', 'teal');

            $options = array();
            $options['type'] = 'cancel_appointment';
            $options['to'] = 'user';

            EmailController::sendEmail(User::find(Auth::user()->id), $options);

        }

        return redirect('agendamentos');

    }

    /**
     * Esta função é para fazer o cadastro de um agendamento
     * @param $place_id
     * @param $place_next
     * @param $place_next_epoch
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function makeMark($place_id, $place_next, $place_next_epoch, $detail_id){

        if (Auth::check() && Auth::user()->user_type == 3) {

            //arrumando a configuração de fuso horario
            $dt = new DateTime("@$place_next_epoch");

            $request = Requests\API\AppointmentAPIRequest::create(
                url('/api/v1/appointments'),
                'POST',
                [
                    "local_id" => $place_id,
                    "time_slot_id" => $place_next,
                    "time_epoch" => $dt->format('U'),
                    "time_slot_detail_id" => $detail_id,
                    "patient_name" => Auth::user()->name,
                    "patient_email" => Auth::user()->email,
                    "patient_phone" => Auth::user()->phone
                ]
            );

            $response = app('App\Http\Controllers\API\AppointmentAPIController')->makeAppointment($request);

            if ($response == 'não salvou a consulta!') {
                flash('Não foi possivel salvar sua consulta!', 'teal');
            }

            if ($response == 'não encontrou o local') {
                flash('Não foi possivel encontrar o local da consulta!', 'teal');
            }

            if (strlen($response) > 112 && $response) {

                $options = array();
                $options['type'] = 'make_appointment';
                $options['to'] = 'user';

                $epoch = $place_next_epoch;
                $dt = new DateTime("@$epoch");
                $options['epoch'] = $dt->format('Y-m-d H:i:s');

//                EmailController::sendEmail(User::find(Auth::user()->id), $options);

                flash('Sua consulta foi marcada com sucesso!', 'teal');
            }else{
                flash('Esse horário não está mais disponivel, tente em outro horário!', 'teal');
            }

        }

        return redirect('agendamentos');

    }

}
