<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 19/08/16
 * Time: 18:03
 */

namespace App\Http\Controllers\Admin;

use App\Criteria\LocalAppointmentsCriteria;
use App\Http\Controllers\AppBaseController;
use App\Models\Appointment;
use App\Models\HealthPlan;
use App\Models\TimeSlot;
use App\Models\TimeSlotDetail;
use App\Repositories\AppointmentRepository;
use App\Repositories\LocalRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Models\Local;
use Illuminate\Support\Facades\DB;
use URL;

use Illuminate\Cookie\CookieJar;

class CalendarController extends AppBaseController {

    /**
     * Repositório de Consultas
     * @var  AppointmentRepository
     */
    private $appointmentRepository;

    /**
     * Repositório de Locais de Atendimento
     * @var LocalRepository
     */
    private $localRepository;

    /**
     * CalendarController constructor.
     * @param AppointmentRepository $appointmentRepository
     * @param LocalRepository $localRepository
     */
    public function __construct(AppointmentRepository $appointmentRepository, LocalRepository $localRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->localRepository = $localRepository;
    }

    /**
     *
     * @param CookieJar $cookieJar
     * @param Request $request
     * @return mixed
     */
    public function index(CookieJar $cookieJar, Request $request)
    {

        //extras
        $slotTypes   = [ TimeSlot::TimeSlotDefault => "Semanal", TimeSlot::TimeSlotCustom => "Dia Específico" ];
        $days = [
            Carbon::SUNDAY => "Domingo",
            Carbon::MONDAY => "Segunda-feira",
            Carbon::TUESDAY => "Terça-feira",
            Carbon::WEDNESDAY => "Quarta-feira",
            Carbon::THURSDAY => "Quinta-feira",
            Carbon::FRIDAY => "Sexta-feira",
            Carbon::SATURDAY => "Sábado",
        ];

        $allLocals = $this->localRepository->all();

        if (sizeof($allLocals) == 0) {
            $locals = [];
            $local = null;
            $slotDuration = null;
        } else {
            $locals = $allLocals->pluck("name", "id");

            if ($request->has('local')) {
                $local = $request->get("local");
                $cookieJar->queue(cookie('local', $local, 60 * 60 * 24));
            } else if($request->hasCookie("local")) {
                $local = $request->cookie("local");
            } else {
                $local = $allLocals->first()->id;
            }

            $this->appointmentRepository->pushCriteria(new LocalAppointmentsCriteria($local));
            $currentLocal = $this->localRepository->findWithoutFail($local);
            $slotDuration = Carbon::createFromTime(0, 0, 0);
            if($currentLocal) {
                $duration = $currentLocal->appointment_duration_in_minutes;
                $slotDuration->addMinutes($duration);
            } else {
                $slotDuration->addMinutes(60);
            }
        }

        //$events = $this->appointmentRepository->all();

        $calendar = \Calendar::/*addEvents($events)
                    ->*/setOptions(["lang" => "pt-br"])
                    ->setOptions(["eventColor" => "green"])
                    ->setOptions([
//                        "buttonText" => [
//                            "today" => "Hoje",
//                            "month" => "Mês",
//                            "week" => "Semana",
//                            "day" => "Dia"
//                        ]
                    ])
            ->setCallbacks([
                'eventClick' => "function(evt) {
                        window.location.href = '". url('/admin/appointments') . "/' + evt.id;
                }"
            ])
        ;



        return view("admin.calendar.index")
            ->with("calendar", $calendar)
            ->with("locals", $locals)
            ->with('slotTypes', $slotTypes)
            ->with('days', $days)
//            ->with("calendar_last_tab", $calendar_last_tab)
            ->with("local", $local)
            //->with("weekEvents", $weekEvents)
//            ->with("minTime", $minTime)
//            ->with("maxTime", $maxTime)
            ->with("slotDuration", $slotDuration ? $slotDuration->format("H:i:s") : null);
    }

    public function getClinicAppointments(Request $request){
        $start    = $request->get("start");
        $end      = $request->get("end");
        $local_id = $request->get("local");

        $weekDays = [];

        if(isset($local_id) && $request->get("local") != 0){
            $locals = Local::select('locals.id')->where('locals.id', $local_id)->get();
        }else{
            $locals = Local::select('locals.id')->where('clinic_id', Auth::user()->id)->get();
        }

        $weekEvents = [];

        if(isset($locals)){
            $weekStart = Carbon::createFromFormat('Y-m-d', $start);
            $weekEnd   = Carbon::createFromFormat('Y-m-d', $end);

            $appointments = array();



            foreach ($locals as $local){
                $appointments[] = Appointment::where('time_slot_local_id', $local->id)->get();
            }

            foreach($appointments[0] as $appointment){
                $weekEvents[] = [
                    "title"   => nl2br($appointment->patient_name.' '.$appointment->appointment_start->format('H:i').' - '.$appointment->appointment_end->format('H:i')),
                    "allDay"  => true,
                    "start"   => $appointment->appointment_start->format('Y-m-d H:i:s'),
                    "end"     =>  $appointment->appointment_end->format('Y-m-d H:i:s'),
                    "id"      => $appointment->id
                ];
            }

            if(isset($appointments[1])){
                foreach($appointments[1] as $appointment){
                    $weekEvents[] = [
                        "title"   => nl2br($appointment->patient_name.' '.$appointment->appointment_start->format('H:i').' - '.$appointment->appointment_end->format('H:i')),
                        "allDay"  => true,
                        "start"   => $appointment->appointment_start->format('Y-m-d H:i:s'),
                        "end"     =>  $appointment->appointment_end->format('Y-m-d H:i:s'),
                        "id"      => $appointment->id
                    ];
                }
            }
        }

        return response()->json($weekEvents);
    }

    public function feedCalendar(Request $request){
        $start = $request->get("start");
        $end = $request->get("end");

        $weekDays = [];

        if(isset($_GET['local'])){
            $local = $_GET['local'];
        }else{
            $local = Local::where('user_id', Auth::user()->id)->first()->id;
        }

        $weekEvents = [];

        if(isset($local)) {

            $weekStart = Carbon::createFromFormat('Y-m-d', $start);
            $weekEnd = Carbon::createFromFormat('Y-m-d', $end);

            $user_local = Local::find($local);

            if(!is_null($user_local)){
                $user_local = Local::find($local);
            }else{
                $user_local = Local::where('user_id', Auth::user()->id)->first();
            }

            $closedDates  = $this->appointmentRepository->findWhere([['appointment_start', '>=', $start], ['appointment_end', '<=', $end], ['time_slot_local_id', '=', $local]/*, ['user_id' => Auth::user()->id]*/]);

            foreach($closedDates as $timeSlot){
                $weekEvents[] = [
                    "title" => nl2br($timeSlot->patient_name.' '.$timeSlot->appointment_start->format('H:i').' - '.$timeSlot->appointment_end->format('H:i')),
                    "allDay" => true,
                    "start" => $timeSlot->appointment_start->format('Y-m-d H:i:s'),
                    "end" =>  $timeSlot->appointment_end->format('Y-m-d H:i:s'),
                    "id" => $timeSlot->id
                ];
            }
        }
        return response()->json($weekEvents);
    }

    public function getClinicTimeSlots(Request $request){
        $user = $request->get("doctor");

        if($request->has('local')){
            $locall = Local::find($request->get('local'));
            $local = $request->get('local');
        }else{
            $locall = Local::where('user_id', $user)->first();
            $local = $locall->id;
        }

        $start = $request->get("start");
        $end   = $request->get("end");

        $weekDays = [];

        if(isset($local)) {
            $weekStart = Carbon::createFromFormat('Y-m-d', $start);
            $weekEnd = Carbon::createFromFormat('Y-m-d', $end);

            while ($weekStart->lte($weekEnd)) {
                $format = $weekStart->format('Y-m-d');

                $weekDays[] = [
                    "day" => $format,
                    "timeSlots" => DB::select("select * from fn2_available_appointments_slots_for_date(cast('$weekStart' as date), $local, true, 0) where slot_type = ".$request->get("tipo"))
                ];

                $weekStart= $weekStart->addDay();
            }
        }

        if(!empty($weekDays)) {

            $weekEvents = [];

            $lastDate = "";

            $ids = array();

            foreach ($weekDays AS $weekDay) {
                $day = $weekDay["day"];
                $timeSlots = $weekDay["timeSlots"];
                foreach($timeSlots AS $timeSlot) {

                    $timeStart = Carbon::createFromFormat('H:i:s O', $timeSlot->slot_time_start);
                    $timeEnd = Carbon::createFromFormat('H:i:s O',$timeSlot->slot_time_end);

                    $plan = HealthPlan::find($timeSlot->health_id);

                    //foi mudado aqui por enquanto
                    if(!in_array($timeSlot->id, $ids)){
                        $weekEvents[] = [
                            "title"  => "Horário",
                            "allDay" => false,
                            "start"  => $day . ' ' . $timeSlot->slot_time_start,
                            "end"    => $day . ' ' . $timeSlot->slot_time_end,
                            "id"     => $timeSlot->id
                        ];

                        $ids[] = $timeSlot->id;
                    }
                }
            }
        } else {
            $weekEvents = null;
        }

        return response()->json($weekEvents);
    }

    public function getDetailsTimeSlot(Request $request){
        return response()->json(TimeSlotDetail::select('time_slot_details.*', 'health_plans.name')->where('time_slot_id', $request->get("time_slot_id"))->join('health_plans', function($join){
            $join->on('time_slot_details.health_plan_id', '=', 'health_plans.id');
        })->get());
    }

    public function feedClosedDates(Request $request){
        if($request->has('local')){
            $local = Local::find($request->get('local'));
        }else{
            $local = Local::where('user_id', Auth::user()->id)->first();
        }


        if((isset($local)) && ($local->user_id == Auth::user()->id)){
            $start = $request->get("start");
            $end = $request->get("end");
            $weekDays = [];
            $weekEvents = [];

            if(isset($local)) {

                $weekStart = Carbon::createFromFormat('Y-m-d', $start);
                $weekEnd = Carbon::createFromFormat('Y-m-d', $end);



                $closedDates = DB::select(DB::raw("select id, cast(start_datetime as timestamp) as start_datetime, cast(end_datetime as timestamp) as end_datetime from closed_dates where start_datetime >= '".$start."' and end_datetime <= '".$end."' and user_id = ".Auth::user()->id." and local_id = ".$local->id." and deleted_at is null"));

                foreach($closedDates as $timeSlot){
                    $weekEvents[] = [
                        "title" => "",
                        "allDay" => false,
                        "start" => $timeSlot->start_datetime,
                        "end" =>  $timeSlot->end_datetime,
                        "id" => $timeSlot->id
                    ];
                }
            }
            return response()->json($weekEvents);
        }else{
            return "Local não existe ou o médico não pertence a esse local!";
        }
    }

    public function feedClosedDatesClinic(Request $request){
        $user = $request->get("doctor");

        if($request->has('local')){
            $local = Local::find($request->get('local'));
        }else{
            $local = Local::where('user_id', $user)->first();
        }

        $start = $request->get("start");
        $end = $request->get("end");
        $weekDays = [];
        $weekEvents = [];

        if(isset($local)) {

            $weekStart = Carbon::createFromFormat('Y-m-d', $start);
            $weekEnd = Carbon::createFromFormat('Y-m-d', $end);

            $closedDates = DB::select(DB::raw("select id, cast(start_datetime as timestamp) as start_datetime, cast(end_datetime as timestamp) as end_datetime from closed_dates where start_datetime >= '".$start."' and end_datetime <= '".$end."' and user_id = ".$user." and local_id = ".$local->id." and deleted_at is null"));

            foreach($closedDates as $timeSlot){
                $weekEvents[] = [
                    "title" => "",
                    "allDay" => false,
                    "start" => $timeSlot->start_datetime,
                    "end" =>  $timeSlot->end_datetime,
                    "id" => $timeSlot->id
                ];
            }
        }
        return response()->json($weekEvents);
    }

    public function feed(Request $request) {
        if($request->has('local')){
            $locall = Local::find($request->get('local'));
            $local = $request->get('local');
        }else{
            $locall = Local::where('user_id', Auth::user()->id)->first();
            $local = $locall->id;
        }

        $start = $request->get("start");
        $end   = $request->get("end");

        $weekDays = [];

        if(isset($local) && $locall->user_id == Auth::user()->id){
            if(isset($local)) {

                $weekStart = Carbon::createFromFormat('Y-m-d', $start);
                $weekEnd = Carbon::createFromFormat('Y-m-d', $end);

                while ($weekStart->lte($weekEnd)) {
                    $format = $weekStart->format('Y-m-d');

                    $weekDays[] = [
                        "day" => $format,
                        "timeSlots" => DB::select("select * from fn2_available_appointments_slots_for_date(cast('$weekStart' as date), $local, true, 0) where slot_type = ".$request->get("tipo"))
                    ];

                    $weekStart= $weekStart->addDay();
                }
            }

            if(!empty($weekDays)) {

                $weekEvents = [];

                $ids = array();

                foreach ($weekDays AS $weekDay) {
                    $day = $weekDay["day"];
                    $timeSlots = $weekDay["timeSlots"];
                    foreach($timeSlots AS $timeSlot) {

                        $timeStart = Carbon::createFromFormat('H:i:s O', $timeSlot->slot_time_start);
                        $timeEnd = Carbon::createFromFormat('H:i:s O',$timeSlot->slot_time_end);

                        $plan = HealthPlan::find($timeSlot->health_id);

                        //foi mudado aqui por enquanto
                        if(!in_array($timeSlot->id, $ids)) {
                            $weekEvents[] = [
                                "title" => "Horário",
                                "allDay" => false,
                                "start" => $day . ' ' . $timeSlot->slot_time_start,
                                "end" => $day . ' ' . $timeSlot->slot_time_end,
                                "id" => $timeSlot->id
                            ];

                            $ids[] = $timeSlot->id;
                        }
                    }
                }
            } else {
                $weekEvents = null;
            }

            return response()->json($weekEvents);
        }else{
            return "Local não existe ou o médico não pertence a esse local!";
        }
    }

    public function getDoctorAppointments(Request $request, $id){
        $start = $request->get("start");
        $end = $request->get("end");

        $weekDays = [];

        $weekStart = Carbon::createFromFormat('Y-m-d', $start);
        $weekEnd = Carbon::createFromFormat('Y-m-d', $end);

        while ($weekStart->lte($weekEnd)) {
            $format = $weekStart->format('Y-m-d');
            $weekDays[] = [
                "day" => $format,
//                "timeSlots" => \DB::select("SELECT * FROM fn_available_appointments_slots_for_date(CAST('$format' AS DATE ), $local) where slot_type = ".$request->get('tipo'))
            ];
            $weekStart= $weekStart->addDay();
        }

        if(!empty($weekDays)) {

            $weekEvents = [];

            foreach ($weekDays AS $weekDay) {
                $day = $weekDay["day"];
                $timeSlots = $weekDay["timeSlots"];

                foreach($timeSlots AS $timeSlot) {

                    $data = explode(' ', $timeSlot->appointment_start);

//                    $timeStart = Carbon::createFromFormat('H:i:s', $timeSlot->appointment_start);
//                    $timeEnd = Carbon::createFromFormat('H:i:s',$timeSlot->appointment_end);
//
//                    $weekEvents[] = [
//                        "title" => "",
//                        "allDay" => false,
//                        "start" => $day . ' ' . $timeSlot->appointment_start,
//                        "end" => $day . ' ' . $timeSlot->appointment_end,
//                        "id" => $timeSlot->id
//                    ];
                }
            }
        } else {
            $weekEvents = null;
        }

        return response()->json($weekEvents);
    }
    
}