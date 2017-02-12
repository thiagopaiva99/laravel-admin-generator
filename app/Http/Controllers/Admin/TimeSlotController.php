<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Scopes\TimeSlotLocalDataTableScope;
use App\DataTables\TimeSlotDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTimeSlotRequest;
use App\Http\Requests\UpdateTimeSlotRequest;
use App\Models\HealthPlan;
use App\Models\Local;
use App\Models\TimeSlot;
use App\Models\TimeSlotDetail;
use App\Models\User;
use App\Repositories\TimeSlotRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\URL;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Cookie\CookieJar;
use Auth;
use DB;

class TimeSlotController extends InfyOmBaseController
{
    /** @var  TimeSlotRepository */
    private $timeSlotRepository;

    public function __construct(TimeSlotRepository $timeSlotRepo)
    {
        $this->timeSlotRepository = $timeSlotRepo;
    }

    /**
     * Display a listing of the HealthPlan.
     *
     * @param TimeSlotDataTable $timeSlotDataTable
     * @param Request $request
     * @return Response
     */
    public function index(CookieJar $cookieJar, TimeSlotDataTable $timeSlotDataTable, Request $request)
    {
        if ($request->has('local')) {
            $local = $request->get("local");
            $cookieJar->queue(cookie('local', $local, 60 * 60 * 24));
        } else if($request->hasCookie("local")) {
            $local = $request->cookie("local");
        } else {
            $local = null;
        }

        if($local != null) {
            $timeSlotDataTable->addScope(new TimeSlotLocalDataTableScope($local));
        }

        $locals = \Auth::user()->locals->pluck("name","id");

        return $timeSlotDataTable->render('admin.timeSlots.index', ["local" => $local, "locals" => $locals]);
    }

    /**
     * Show the form for creating a new TimeSlot.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        if(Auth::user()->user_type != User::UserTypeClinic){
            $locals      = Local::select("locals.id", "locals.name")->where('user_id', Auth::user()->id)->get();
        }else{
            $locals      = Local::where('clinic_id', Auth::user()->id)->get();
        }

        $slotTypes   = [ TimeSlot::TimeSlotDefault => "Semanal", TimeSlot::TimeSlotCustom => "Dia Específico" ];
        $healthPlans = User::where('user_type', '4')->pluck('name', 'id');

        if(Auth::user()->user_type == User::UserTypeClinic){
            $doctors = sizeof($locals) > 0 ? User::selectRaw("locals.name || ' - ' || users.name as name, users.id as user_id, locals.id as local_id")->whereRaw('users.deleted_at is null and locals.deleted_at is null and users.user_type = 2')->join('locals', function($join){
                $join->on('users.id', '=', 'locals.user_id')->where('locals.clinic_id', '=', Auth::user()->id);
            })->get() : [];
        }else{
            $doctors = Local::where('user_id', Auth::user()->id)->get();
        }

        //garantindo q os locais serão um array
        $locals = $locals->pluck("name", "id")->toArray();

        $days = [
            Carbon::MONDAY => "Segunda-feira",
            Carbon::TUESDAY => "Terça-feira",
            Carbon::WEDNESDAY => "Quarta-feira",
            Carbon::THURSDAY => "Quinta-feira",
            Carbon::FRIDAY => "Sexta-feira",
            Carbon::SATURDAY => "Sábado",
        ];

        $interval = \Auth::user()->locals->pluck("appointment_duration_in_minutes", "id")->toArray();

        if($request->hasCookie("local")) {
            $local = $request->cookie("local");
        } else {
            $local = null;
        }

        $count = \Auth::user()->locals->pluck("name","id")->count();

        if ($request->has('start')) {
            $startEpoch = $request->get('start') / 1000;
            $start = Carbon::createFromTimestampUTC($startEpoch);
        } else {
            $start = null;
        }

        if ($request->has('end')) {
            $endEpoch = $request->get('end') / 1000;
            $end = Carbon::createFromTimestampUTC($endEpoch);
        } else {
            $end = null;
        }

        return view('admin.timeSlots.create')
            ->with("local", $local)
            ->with("locals", $locals)
            ->with("slotTypes", $slotTypes)
            ->with("days", $days)
            ->with('start', $start)
            ->with('end', $end)
            ->with("interval", $interval)
            ->with("healthPlans", $healthPlans)
            ->with("doctors", $doctors);
    }

    /**
     * Show the form for creating a new TimeSlot.
     *
     * @return Response
     */
    public function createMultiple(Request $request){
        $slotTypes   = [ TimeSlot::TimeSlotDefault => "Semanal", TimeSlot::TimeSlotCustom => "Dia Específico" ];

        $doctors = User::select('users.name', 'users.id')->join('clinic_user', function($join){
            $join->on('users.id', '=', 'clinic_user.user_id')->where('clinic_id', '=', Auth::user()->id);
        })->whereRaw('user_type = 2 and deleted_at is null')->has('locals')->get();

        $days = [
            Carbon::MONDAY => "Segunda-feira",
            Carbon::TUESDAY => "Terça-feira",
            Carbon::WEDNESDAY => "Quarta-feira",
            Carbon::THURSDAY => "Quinta-feira",
            Carbon::FRIDAY => "Sexta-feira",
            Carbon::SATURDAY => "Sábado",
        ];

        return view('admin.timeSlots.create-multiple')
            ->with("slotTypes", $slotTypes)
            ->with("days", $days)
            ->with("doctors", $doctors);
    }

    /**
     * Store a newly created TimeSlot in storage.
     *
     * @param CreateTimeSlotRequest $request
     *
     * @return Response
     *
     *  Faz a inserção via CreateTimeSlotRequest - Sem Javascript
     *
     */
    public function store(CreateTimeSlotRequest $request)
    {
        $input = $request->all();

        if(Auth::user()->user_type == User::UserTypeClinic){
            $input['user_id'] = $request->get("user_id");
        }else{
            $input["user_id"] = \Auth::id();
        }

        if($input["slot_type"] == TimeSlot::TimeSlotDefault) {
            $input["slot_date"] = null;
        } else if($input["slot_type"] == TimeSlot::TimeSlotCustom) {
            if($input["slot_date"] == null) {
                return redirect(route('admin.timeSlots.create'))->withErrors(["Data do Agendamento" => "Por favor, informe a data do agendamento customizado."])->withInput($input);
            }
            $input["day_of_week"] = null;
        }

        $timeSlot = new TimeSlot;

        $timeSlot->local_id         = (int)$input['local_id'];
        $timeSlot->user_id          = (int)$input['user_id'];
        $timeSlot->slot_type        = (int)$input['slot_type'];
        $timeSlot->day_of_week      = (int)$input['day_of_week'];
        $timeSlot->slot_time_start  = $input['slot_time_start'];
        $timeSlot->slot_time_end    = $input['slot_time_end'];
        $timeSlot->slot_date        = $input['slot_date'];
        $timeSlot->queue_type       = (int)$input['queue'];

        $timeSlot->save();

        if(isset($timeSlot) && isset($timeSlot->id) && $timeSlot->id > 0){
            $tsd = new TimeSlotDetail;

            $tsd->time_slot_id   = $timeSlot->id;
            $tsd->health_plan_id = 0;
            $tsd->private        = true;
            $tsd->slot_count     = $timeSlot->queue_type == 2 ? '1' : $input['slot_count'];

            $tsd->save();

            if (isset($id) && $id > 0) {
                return $timeSlot;
            } else {
                return "conflito de agendamentos";
            }
        }else{
            return $timeSlot;
        }
    }

    /**
     * @param Request $request
     * @return TimeSlot
     */

    public function inserir(Request $request)
    {
        $input = $request->all();

        if(Auth::user()->user_type == User::UserTypeClinic){
            $input['user_id'] = $request->get("user_id");
        }else{
            $input["user_id"] = \Auth::id();
        }

        if($input["slot_type"] == TimeSlot::TimeSlotDefault) {
            $input["slot_date"] = null;
        } else if($input["slot_type"] == TimeSlot::TimeSlotCustom) {
            if($input["slot_date"] == null) {
                return redirect(route('admin.timeSlots.create'))->withErrors(["Data do Agendamento" => "Por favor, informe a data do agendamento customizado."])->withInput($input);
            }
            $input["day_of_week"] = null;
        }

        if($input['queue'] == 2) {
            $time_in_minutes = Local::select('appointment_duration_in_minutes as minutes')->where('id', $input['local_id'])->first();

            $start = date("H", strtotime($input['slot_time_start']));
            $end   = date("H", strtotime($input['slot_time_end']));

            $start_minutes = date("i", strtotime($input['slot_time_start']));
            $end_minutes = date("i", strtotime($input['slot_time_end']));

            $times = ceil((($end - $start) * 60) / $time_in_minutes->minutes);

            $time_start = Carbon::createFromTime($start, $start_minutes, 0, 'America/Sao_Paulo');
            $time_medium = Carbon::createFromTime($start, $start_minutes, 0, 'America/Sao_Paulo');
            $time_end = Carbon::createFromTime($end, $end_minutes, 0, 'America/Sao_Paulo');

            for ($i = 0; $i < $times; $i++) {
                $time_medium->addMinutes($time_in_minutes->minutes);

                if ($i != 0) {
                    $time_start->addMinutes($time_in_minutes->minutes);
                }

                $timeSlot = new TimeSlot;

                $timeSlot->local_id         = (int)$input['local_id'];
                $timeSlot->user_id          = (int)$input['user_id'];
                $timeSlot->slot_type        = (int)$input['slot_type'];
                $timeSlot->day_of_week      = (int)$input['day_of_week'];
                $timeSlot->slot_time_start  = $time_start;
                $timeSlot->slot_time_end    = $time_medium;
                $timeSlot->slot_date        = $input['slot_date'];
                $timeSlot->queue_type       = (int)$input['queue'];

                $timeSlot->save();

                if(isset($timeSlot) && isset($timeSlot->id) && $timeSlot->id > 0){
                    $tsd = new TimeSlotDetail;

                    $tsd->time_slot_id   = $timeSlot->id;
                    $tsd->health_plan_id = 0;
                    $tsd->private        = true;
                    $tsd->slot_count     = 1;

                    $tsd->save();

                    if (isset($tsd) && $tsd->id > 0) {
                        return $timeSlot;
                    } else {
                        return "conflito de agendamentos";
                    }
                }else{
                    return $timeSlot;
                }
            }
        }else{
            $timeSlot = new TimeSlot;

            $timeSlot->local_id         = (int)$input['local_id'];
            $timeSlot->user_id          = (int)$input['user_id'];
            $timeSlot->slot_type        = (int)$input['slot_type'];
            $timeSlot->day_of_week      = (int)$input['day_of_week'];
            $timeSlot->slot_time_start  = $input['slot_time_start'];
            $timeSlot->slot_time_end    = $input['slot_time_end'];
            $timeSlot->slot_date        = $input['slot_date'];
            $timeSlot->queue_type       = (int)$input['queue'];

            $timeSlot->save();

            if(isset($timeSlot) && isset($timeSlot->id) && $timeSlot->id > 0){
                $tsd = new TimeSlotDetail;

                $tsd->time_slot_id   = $timeSlot->id;
                $tsd->health_plan_id = 0;
                $tsd->private        = true;
                $tsd->slot_count     = $timeSlot->queue_type == 2 ? '1' : $input['slot_count'];

                $tsd->save();

                if (isset($tsd) && $tsd->id > 0) {
                    return $timeSlot;
                } else {
                    return "conflito de agendamentos";
                }
            }else{
                return $timeSlot;
            }
        }
    }
    
    
    
    public function storeMultiple(Request $request){
        $locals_with_users  = explode(",", $request->get("locals_selected"));
        $input              = $request->all();
        $private            = $request->get("private");

        $time_slot_detail = array(
            'health_plan_id' => $request->get('plans_selected'),
            'private'        => isset($private) ? $private : "false",
            'slot_count'     => $request->get('slot_count')
        );

        unset($input['medics_select']);  $input['medics_select']   = null;
        unset($input['private']);        $input['private']         = null;
        unset($input['slot_count']);     $input['slot_count']      = null;
        unset($input['plans_selected']); $input['plans_selected']  = null;

        if($input["slot_type"] == TimeSlot::TimeSlotDefault) {
            $input["slot_date"] = null;
        } else if($input["slot_type"] == TimeSlot::TimeSlotCustom) {
            if($input["slot_date"] == null) {
                return redirect(route('admin.timeSlots.create'))->withErrors(["Data do Agendamento" => "Por favor, informe a data do agendamento customizado."])->withInput($input);
            }
            $input["day_of_week"] = null;
        }

        $status = "";

        foreach($locals_with_users as $locals) {
            $user = explode("-", $locals)[0];

            if ($user != "") {
                $local = explode("-", $locals)[1];

                // starting the object feed
                $timeslot = new TimeSlot;

                $timeslot->local_id = $local;
                $timeslot->user_id = $user;
                $timeslot->slot_type = $input['slot_type'];
                $timeslot->day_of_week = $input['day_of_week'];
                $timeslot->slot_time_start = $input['slot_time_start'];
                $timeslot->slot_time_end = $input['slot_time_end'];
                $timeslot->slot_date = $input['slot_date'];
                $timeslot->queue_type = $input['queue'];

                $timeslot->save();

                if (isset($timeslot) && isset($timeslot->id) && $timeslot->id > 0) {
                    $plans = explode(',', $time_slot_detail['health_plan_id']);

                    $id = 0;
                    foreach ($plans as $plan) {
                        $tsd = new TimeSlotDetail;

                        $tsd->time_slot_id   = $timeslot->id;
                        $tsd->health_plan_id = $plan;
                        $tsd->private        = $time_slot_detail['private'];
                        $tsd->slot_count     = $timeslot->queue_type == 2 ? '1' : $time_slot_detail['slot_count'];

                        $tsd->save();

                        $id = $tsd->id;

                        if (isset($tsd) && isset($tsd->id) && $tsd->id > 0) {
                            $status .= "0";
                        } else {
                            $status .= "1";
                        }
                    }
                } else {
                    $status .= "1";
                }
            }
        }

        if(strrpos($status, "1") === false){
            Flash::success("Horários salvos com sucesso!");
            return redirect('admin/clinic');
        }else{
            Flash::error("Nem todos horários puderam ser salvos!");
            return redirect(URL::previous());
        }
    }

    /**
     * Display the specified TimeSlot.
     *
     * @param  int $id
     *
     * @return Response
     */
//    public function show($id)
//    {
//        $timeSlot = $this->timeSlotRepository->findWithoutFail($id);
//
//        if (empty($timeSlot)) {
//            Flash::error('TimeSlot not found');
//
//            return redirect(route('admin.timeSlots.index'));
//        }
//
//        return view('admin.timeSlots.show')->with('timeSlot', $timeSlot);
//    }

    /**
     * Show the form for editing the specified TimeSlot.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $timeSlot = $this->timeSlotRepository->findWithoutFail($id);

        if (empty($timeSlot)) {
            Flash::error('Horário não encontrado');

            return redirect(route('admin.calendar.index'));
        }

        $details = TimeSlotDetail::where('time_slot_id', $id)->get();

        if(Auth::user()->user_type == User::UserTypeClinic){
            $doctors = User::selectRaw("locals.name || ' - ' || users.name as name, users.id as user_id, locals.id as local_id")->whereRaw('users.deleted_at is null and locals.deleted_at is null and users.user_type = 2')->join('locals', function($join){
                $join->on('users.id', '=', 'locals.user_id')->where('locals.clinic_id', '=', Auth::user()->id);
            })->get();
        }else{
            $doctors = Local::where('user_id', Auth::user()->id)->get();
        }

        //$locals[] = [null => "Escolha..."];

        $slotTypes = [ TimeSlot::TimeSlotDefault => "Padrão", TimeSlot::TimeSlotCustom => "Customizado" ];
        $days = [
            Carbon::MONDAY => "Segunda-feira",
            Carbon::TUESDAY => "Terça-feira",
            Carbon::WEDNESDAY => "Quarta-feira",
            Carbon::THURSDAY => "Quinta-feira",
            Carbon::FRIDAY => "Sexta-feira",
            Carbon::SATURDAY => "Sábado",
        ];

        return view('admin.timeSlots.edit')
            ->with('timeSlot', $timeSlot)
            ->with("slotTypes", $slotTypes)
            ->with("days", $days)
            ->with("details", $details)
            ->with("doctors", $doctors);
    }

    /**
     * Update the specified TimeSlot in storage.
     *
     * @param  int              $id
     * @param UpdateTimeSlotRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimeSlotRequest $request)
    {
        $timeSlot = TimeSlot::find($id);

        $details = TimeSlotDetail::where('time_slot_id', $id)->get();

        if(isset($timeSlot) && $timeSlot != null){
            $input = $request->all();

            $time_slot_detail = array(
                'health_plan_id' => ($request->get('private') == "true") ? "null" : $request->get('plans_selected'),
                'private' => ($request->get('private') == null) ? "false" : "true",
                'slot_count' => $request->get('private') == "true" ? '1' : $request->get('slot_count')
            );

            unset($input['medics_select']);  $input['medics_select']   = null;
            unset($input['private']);        $input['private']         = null;
            unset($input['slot_count']);     $input['slot_count']      = null;
            unset($input['plans_selected']); $input['plans_selected']  = null;

//            $input["user_id"] = \Auth::id();

            if($input["slot_type"] == TimeSlot::TimeSlotDefault) {
                $input["slot_date"] = null;
            } else if($input["slot_type"] == TimeSlot::TimeSlotCustom) {
                if($input["slot_date"] == null) {
                    return redirect(route('admin.timeSlots.create'))->withErrors(["Data do Agendamento" => "Por favor, informe a data do agendamento customizado."])->withInput($input);
                }
                $input["day_of_week"] = null;
            }

            $timeSlot->local_id         = $input['local_id'];
            $timeSlot->user_id          = $input['user_id'];
            $timeSlot->slot_type        = $input['slot_type'];
            $timeSlot->day_of_week      = $input['day_of_week'];
            $timeSlot->slot_time_start  = $input['slot_time_start'];
            $timeSlot->slot_time_end    = $input['slot_time_end'];
            $timeSlot->slot_date        = $input['slot_date'];
            $timeSlot->queue_type       = $input['queue'];

            $timeSlot->save();

            if(isset($timeSlot) && isset($timeSlot->id) && $timeSlot->id > 0){

                foreach($details as $detail){
                    $detail->delete();
                }

                if($time_slot_detail['private'] == "false"){
                    $plans = explode(',', $time_slot_detail['health_plan_id']);

                    foreach ($plans as $plan){
                        $tsd = new TimeSlotDetail;

                        (isset($input['private']) || $input['private'] != null ? $input['private'] = "true" : $input['private'] = "false");

                        $tsd->time_slot_id   = $timeSlot->id;
                        $tsd->health_plan_id = $plan;
                        $tsd->private        = false;
                        $tsd->slot_count     = $time_slot_detail['slot_count'];

                        $tsd->save();
                    }
                }else{
                    $tsd = new TimeSlotDetail;

                    (isset($input['private']) || $input['private'] != null ? $input['private'] = "true" : $input['private'] = "false");

                    $tsd->time_slot_id   = $timeSlot->id;
                    $tsd->health_plan_id = null;
                    $tsd->private        = true;
                    $tsd->slot_count     = 1;

                    $tsd->save();
                }

                if (isset($tsd) && isset($tsd->id) && $tsd->id > 0) {
                    Flash::success('Horário atualizado com sucesso!');

                    if(Auth::user()->user_type == User::UserTypeClinic){
                        return redirect(route('admin.clinic.index'));
                    }else{
                        return redirect(route('admin.calendar.index'));
                    }
                } else {
                    return redirect(route('admin.timeSlots.create'))->withErrors(["Conflito" => "Há um conflito de agendamentos."])->withInput($input);
                }
            }else{
                return redirect(route('admin.timeSlots.create'))->withErrors(["Conflito" => "Não foi possivel salvar seu horário"])->withInput($input);
            }
        }
    }

    /**
     * Remove the specified TimeSlot from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $timeSlot = $this->timeSlotRepository->findWithoutFail($id);

        if (empty($timeSlot)) {
            Flash::error('Horário não encontrado');

            return redirect(route('admin.calendar.index'));
        }

        $this->timeSlotRepository->delete($id);

        Flash::success('Horário deletado com sucesso!');

        if(Auth::user()->user_type == User::UserTypeClinic){
            return redirect(route('admin.clinic.index'));
        }else{
            return redirect(route('admin.calendar.index'));
        }
    }

    public function deleteTimeSlot(Request $request){
        $timeSlot = $this->timeSlotRepository->findWithoutFail($request->get("time_slot_id"));

        if (empty($timeSlot)) {
            Flash::error('Horário não encontrado');

            return redirect(route('admin.calendar.index'));
        }

        $this->timeSlotRepository->delete($request->get("time_slot_id"));

        return 'ok';
    }

    public function getTimeSlot($id){
        return $id;
    }

    public function closePlan(Request $request){
        //buscando o time_slot que ira servir de base
        $time_slot = TimeSlot::find($request->get("time_slot_id"));

        //clonando o objeto do time_slot para um novo objeto e assim poder modifica-lo
        $new_time_slot = new TimeSlot;

        //setando o tipo do novo objeto como custom e removendo o atributo id do objeto original
        $new_time_slot->local_id        = $time_slot->local_id;
        $new_time_slot->user_id         = $time_slot->user_id;
        $new_time_slot->slot_type       = 2;
        $new_time_slot->day_of_week     = null;
        $new_time_slot->slot_time_start = $time_slot->slot_time_start;
        $new_time_slot->slot_time_end   = $time_slot->slot_time_end;
        $new_time_slot->slot_date       = $request->get("new_slot_date");

        //buscando outros time_slots padroes para transformar para customizados
        if($time_slot->slot_type == 1){
            $time_slots = TimeSlot::whereRaw('user_id = '.$time_slot->user_id.' and slot_type = 1 and id <> '.$time_slot->id.' and day_of_week = '.$time_slot->day_of_week)->get();

            if(count($time_slots) > 0){
                foreach($time_slots as $time){
                    $novo_time_slot = new TimeSlot;

                    $novo_time_slot->local_id    = $time->local_id;
                    $novo_time_slot->user_id     = $time->user_id;
                    $novo_time_slot->slot_type   = 2;
                    $novo_time_slot->day_of_week = null;
                    $novo_time_slot->slot_time_start = $time->slot_time_start;
                    $novo_time_slot->slot_time_end = $time->slot_time_end;
                    $novo_time_slot->slot_date   = $request->get("new_slot_date");

                    $novo_time_slot->save();

                    $details2 = DB::select(DB::raw("select * from time_slot_details where time_slot_details.time_slot_id = $time->id"));

                    if(count($details2) > 0){
                        foreach($details2 as $detail){
                            $newDetail = new TimeSlotDetail;

                            $newDetail->time_slot_id   = $novo_time_slot->id;
                            $newDetail->health_plan_id = $detail->health_plan_id;
                            $newDetail->private        = $detail->private;
                            $newDetail->slot_count     = $detail->slot_count;

                            $newDetail->save();
                        }
                    }else{
                        $novo_time_slot->delete();
                    }
                }
            }
        }

        if($time_slot->slot_type == 2){
            $time_slot->delete();
        }

        $new_time_slot->save();

        //buscando os detalhes do time_slot
        $details = DB::select(DB::raw("select * from time_slot_details where time_slot_details.time_slot_id = $time_slot->id and time_slot_details.id <> ".$request->get("time_slot_detail_id")));

        if(count($details) > 0){
            foreach($details as $detail){
                $newDetail = new TimeSlotDetail;

                $newDetail->time_slot_id   = $new_time_slot->id;
                $newDetail->health_plan_id = $detail->health_plan_id;
                $newDetail->private        = $detail->private;
                $newDetail->slot_count     = $detail->slot_count;

                $newDetail->save();
            }
        }else{
            $new_time_slot->delete();
        }

        return $new_time_slot->id;
    }
}


