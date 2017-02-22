<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DoctorDataTable;
use App\DataTables\Scopes\DoctorDataTableScope;
use App\DataTables\Scopes\UserDataTableScope;
use App\DataTables\Scopes\UserTypeDataTableScope;
use App\DataTables\UserDataTable;
use App\DataTables\UserWithoutClinicDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Appointment;
use App\Models\ClosedDate;
use App\Models\Local;
use App\Models\TimeSlot;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
//use App\Http\Controllers\Admin\
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
//use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\User;
use Mail;
use URL;
use App\Models\ClinicUser;
use Auth;
use DB;
use Maatwebsite\Excel\Facades\Excel;

use Yajra\Datatables\Facades\Datatables;

class UserController extends InfyOmBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        if(\Auth::check() && \Auth::user()->user_type == \App\Models\User::UserTypeDoctor) {
            $this->middleware('auth.adminOrSelfDoctor')->only(['edit','update']);
            $this->middleware('auth.admin')->only(['create', 'store', 'delete']);
        }

        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, UserDataTable $userDataTable) //, Request $request)
    {
        if(Auth::user()->user_type != 4){
            $user_type = $request->get("user_type");
            $user_preferred = $request->get('user_preferred');
            $select_status = $request->get('select_status');

            if($user_type != null) {

                if($user_type == 2 && isset($user_preferred)){
                    $userDataTable->addScope(new \App\DataTables\Scopes\PreferredUserDataTableScope($user_preferred));
                }else{
                    $userDataTable->addScope(new UserTypeDataTableScope($user_type));
                }
            }

            if(isset($select_status)){
                $userDataTable->addScope(new \App\DataTables\Scopes\ApprovalStatusUserDataTableScope($select_status));
            }

            return $userDataTable->render('admin.users.index', ["user_type" => $user_type]);
        }else{
            $userDataTable->addScope(new \App\DataTables\Scopes\DoctorsDataTableScope());
            return $userDataTable->render('admin.users.index');
        }
    }

    public function getPreferredUsers(UserDataTable $userDataTable, Request $r){

        $preferred = $r->get('user_preferred');

        if($preferred != null){
            $userDataTable->addScope(new \App\DataTables\Scopes\PreferredUserDataTableScope($preferred));
        }

        return $userDataTable->render('admin.users.index', ["user_type" => $r->get('user_type'), "user_preferred" => $preferred]);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        if(Auth::user()->user_type == User::UserTypeClinic){
            return view('admin.users.create');
        }else{
            $approvalStatus = [
                User::ApprovalStatusPending => "Aguardando",
                User::ApprovalStatusAccepted => "Aprovado",
                User::ApprovalStatusDenied => "Negado"
            ];

            $clinics = User::where('user_type', '4')->pluck('name', 'id');

            return view('admin.users.create', compact('clinics'))->with("approvalStatus", $approvalStatus);
        }
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $input['password'] = bcrypt($input['password']);
        $input['image_src'] = 'http://embelezzo.aiolia.inf.br/assets/site/images/img_medicos.jpg';

        $go = false;

        if(User::where('email', $input['email'])->count() < 1){

            if(Auth::user()->user_type == User::UserTypeClinic){
                $input['user_type'] = 2;
                $input['clinic_id'] = Auth::user()->id;
            }

            $user = $this->userRepository->create($input);

            if($input['user_type'] == 2){

                if(isset($input['clinics'])){
                    for($i = 0; $i < count($input['clinics']); $i++){
                        $clinic_user = new ClinicUser;

                        $clinic_user->clinic_id = $input['clinics'][$i];
                        $clinic_user->user_id = $user->id;

                        $clinic_user->save();
                    }
                }else if(isset($input['clinic_id'])){
                    $clinic_user = new ClinicUser;

                    $clinic_user->clinic_id = $input['clinic_id'];
                    $clinic_user->user_id = $user->id;

                    $clinic_user->save();
                }
            }
        }else{
            $input['user_type'] = 2;

            $newUser = User::where('email', $input['email'])->first();
            $newUser->update($input);

            if(Auth::user()->user_type == 4){
                $clinicUser = new ClinicUser;

                $clinicUser->clinic_id = Auth::user()->id;
                $clinicUser->user_id   = $newUser->id;

                $clinicUser->save();
            }

            $go = true;
        }

        // mandando email para o usuário
        $options = array();
        $options['type'] = 'new_user';
        $options['to'] = 'user';

        EmailController::sendEmail(User::where('email', $input['email'])->get(), $options);

        Flash::success('Usuário salvo com sucesso!');

        if(Auth::user()->user_type == User::UserTypeClinic){
            if($go){
                return redirect('admin/users');
            }else{
                return redirect('admin/locals/create?user_id='.$user->id);
            }
        }else{
            return redirect(route('admin.users.index'));
        }
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {

        $user = $this->userRepository->findWithoutFail($id);
        // dd($user);

        if (empty($user) && $id != \Auth::id()) {
            Flash::error('Usuário não encontrado.');
            return redirect(route('admin.users.index'));
        }

        if (empty($user)) {
            // carregar o próprio usuário
            $user = User::find($id);

            if (empty($user)) {
                Flash::error('Usuário não encontrado');
                return redirect(route('admin.users.index'));
            }
        }

        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        $clinics = User::where('user_type', '4')->pluck('name', 'id');

        if (empty($user) && $id != \Auth::id()) {
            Flash::error('Usuário não encontrado.');
            return redirect(route('admin.users.index'));
        }

        if (empty($user)) {
            // carregar o próprio usuário
            $user = User::find($id);

            if (empty($user)) {
                Flash::error('Usuário não encontrado');
                return redirect(route('admin.users.index'));
            }
        }

        $approvalStatus = [
            User::ApprovalStatusPending => "Aguardando",
            User::ApprovalStatusAccepted => "Aprovado",
            User::ApprovalStatusDenied => "Negado"
        ];

        return view('admin.users.edit', compact('clinics'))
            ->with('user', $user)
            ->with("approvalStatus", $approvalStatus);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user) && $id != \Auth::id()) {
            Flash::error('Usuário não encontrado');
            return redirect(route('admin.users.index'));
        }

        if (empty($user)) {
            // carregar o próprio usuário
            $user = User::find($id);

            if (empty($user)) {
                Flash::error('Usuário não encontrado');
                return redirect(route('admin.users.index'));
            }
        }

        if($request['password'] == ""){
            $request['password'] = $user->password;
        }else{
            $request['password'] = bcrypt($request['password']);
        }

        if(isset($request['clinics']) && $request['clinics'] != ""){
            $clinicUser = new ClinicUser;

            $clinicUser->clinic_id = $request['clinics'];
            $clinicUser->user_id   = $id;

            $clinicUser->save();
        }

        $user = $user->update($request->all());

        if($request->approval_status == User::ApprovalStatusAccepted){
            $options = array();
            $options['type'] = 'approval_medic';
            $options['to'] = 'user';

            EmailController::sendEmail(User::find($id), $options);
        }

        if($id == \Auth::id()) {
            Flash::success('Perfil atualizado.');
            return redirect(route('admin.users.show', [$id]));
        } else {
            Flash::success('Usuário atualizado.');
            return redirect(route('admin.users.index'));
        }
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Usuário não encontrado');

            return redirect(route('admin.users.index'));
        }

        $this->userRepository->delete($id);

        if($user->user_type == User::UserTypeDoctor){
            // deletando o resto das coisas do user

            $appointments     = Appointment::where('time_slot_user_id', $id)->delete();
            $locals           = Local::where('user_id', $id)->delete();
            $closed_dates     = ClosedDate::where('user_id', $id)->delete();
            $timeslots        = TimeSlot::where('user_id', $id)->delete();

        }

        

        Flash::success('Usuário deletado com sucesso!');

        return redirect(route('admin.users.index'));
    }

    /**
     * Index of Clinics
     */

    public function getClinicIndex(Request $request){
        //extras
        $slotTypes   = [ TimeSlot::TimeSlotDefault => "Semanal", TimeSlot::TimeSlotCustom => "Dia Específico" ];
        $days = [
            Carbon::MONDAY => "Segunda-feira",
            Carbon::TUESDAY => "Terça-feira",
            Carbon::WEDNESDAY => "Quarta-feira",
            Carbon::THURSDAY => "Quinta-feira",
            Carbon::FRIDAY => "Sexta-feira",
            Carbon::SATURDAY => "Sábado",
        ];

        // buscando locias que pertencem aquela clinica
        $locals[0] = "Selecione um local";
        $locals    = Local::select('locals.name', 'locals.id')->where('clinic_id', Auth::user()->id)->pluck('name', 'id');
        $local    = $request->get("local") != "" ? $request->get("local") : (isset(Local::where('clinic_id', Auth::user()->id)->first()->id) ? Local::where('clinic_id', Auth::user()->id)->first()->id : 0);

        // buscando os medicos que pertencem aquela clinica
        $medics = sizeof($local) > 0 ? User::selectRaw("locals.name || ' - ' || users.name as name, users.id as user_id, locals.id as local_id")->whereRaw('users.deleted_at is null and locals.deleted_at is null and users.user_type = 2')->join('locals', function($join) use ($local, $request){
            $join->on('users.id', '=', 'locals.user_id')->where('locals.clinic_id', '=', Auth::user()->id);
        })->get() : [];


//        return $medics;

        return view("admin.clinic.index", ["local" => $local], compact('locals', 'slotTypes', 'days'))->with('doctors', $medics);
    }

    /**
     * Retornando detalhes do medico para a clinica
     *
     * $id => Id do médico
     */

    public function getDoctorDetails(Request $request, $id){
        $doctor = User::select('user_type')->where('id', $id)->first();

        if(isset($doctor) && $doctor->user_type == 2){

            $doc          = User::where('id', $id)->first();
            $clinic       = User::select('users.*')->join('clinic_user', 'clinic_user.clinic_id', '=', 'users.id')->first();
            $agenda       = TimeSlot::select('time_slots.*', 'time_slot_details.*')->where('user_id', $doc->id)->join('time_slot_details', 'time_slots.id', '=', 'time_slot_details.time_slot_id')->get();
            $locals        = Local::where('user_id', $doc->id)->get();
            $appointments = Appointment::where('user_id', $doc->id)->get();

            return view('admin.users.doctor')
                ->with('doctor', $doc)
                ->with('clinic', $clinic)
                ->with('agenda', $agenda)
                ->with('locals', $locals)
                ->with('appointments', $appointments);
        }else{
            return redirect(route("admin.users.index"));
        }
    }

    function getDoctors($id){
        $doctors     = User::select("users.id", "users.name")->where('user_type', 2)->join('clinic_user', function($join) {
            $join->on('users.id', '=', 'clinic_user.user_id')->where('clinic_id', '=', Auth::user()->id);
        })->join('locals', function($join) use ($id){
            $join->on('locals.user_id', '=', 'users.id')->where('locals.id', '=', $id);
        })->get();

        return $doctors;
    }

    function getIndexReport(){
        $doctor = "";
        if(Auth::user()->user_type == User::UserTypeAdmin){
            $doctor = User::where('user_type', 4)->pluck("name", "id");
        }else if(Auth::user()->user_type == User::UserTypeClinic){
            $doctor     = User::select("users.id", "users.name")->where('user_type', 2)->join('clinic_user', function($join) {
                $join->on('users.id', '=', 'clinic_user.user_id')->where('clinic_id', '=', Auth::user()->id);
            })->pluck("name", "id");
        }

        return view('admin.users.reports')
                ->with('doctors', $doctor);
    }

    public function getDoctorsByClinic(Request $request){
        return $doctor     = DB::select(DB::raw('select users.* from users inner join clinic_user on users.id = clinic_user.user_id where clinic_id = '.$request->get("id").' and deleted_at is null'));
    }

    public function getClinicAddress(Request $request){
        $address = User::select('address')->where('id', '=', $request['clinic_id'])->get();

        return $address[0];
    }

    public function getUsersWithoutClinic(Request $request, UserWithoutClinicDataTable $userDataTable){
        return $userDataTable->render('admin.users.doctors-without-clinic');
    }

    public function addDoctorToClinic(Request $request, $id){
        $newRelation = new ClinicUser;

        $newRelation->user_id = $id;
        $newRelation->clinic_id = Auth::user()->id;

        $newRelation->save();

        Flash::success("Médico adicionado á clinica!");

        return redirect("admin/users-without-clinic");
    }

    function getReport(Request $request){
        Excel::create('agendamentos', function($excel) use ($request) {
            $excel->sheet('agendamentos', function($sheet) use ($request){
                $ids = ""; $i = 0;
                foreach($request->get("doctors") as $doctor){
                    if($i == (count($request->get("doctors")) - 1)){
                        $ids .= $doctor;
                    }else{
                        $ids .= $doctor.",";
                    }

                    $i++;
                }

                $appointments = Appointment::whereRaw("time_slot_user_id in(".$ids.") and appointment_start >= '".$request->get('start_datetime')."' and appointment_end <= '".$request->get('end_datetime')."'")->orderby("appointment_start", "desc")->get()->toArray();
                $sheet->fromArray($appointments);

                $sheet->cell('A1', function($cell) {
                    $cell->setValue('ID');
                });

                $sheet->cell('B1', function($cell) {
                    $cell->setValue('ID do usuário');
                });

                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Inicio da consulta');
                });

                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Final da consulta');
                });

                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Nome do paciente');
                });

                $sheet->cell('F1', function($cell) {
                    $cell->setValue('Telefone do paciente');
                });

                $sheet->cell('G1', function($cell) {
                    $cell->setValue('Email do paciente');
                });

                $sheet->cell('H1', function($cell) {
                    $cell->setValue('ID da consulta');
                });

                $sheet->cell('I1', function($cell) {
                    $cell->setValue('Nome do médico');
                });

                $sheet->cell('J1', function($cell) {
                    $cell->setValue('Especialdiades');
                });

                $sheet->cell('K1', function($cell) {
                    $cell->setValue('Dia');
                });

                $sheet->cell('L1', function($cell) {
                    $cell->setValue('Hora');
                });

                $sheet->cell('A1:L1', function($cells){
                    $cells->setBackground('#000000');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontWeight('bold');
                    $cells->setValignment('center');
                    $cells->setAlignment('center');
                });
            });
        })->download('xls');

        return redirect('admin/relatorios');
    }
}
