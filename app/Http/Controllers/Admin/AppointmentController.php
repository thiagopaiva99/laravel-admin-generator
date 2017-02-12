<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AppointmentDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Local;
use App\Models\User;
use App\Repositories\AppointmentRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Auth;
use URL;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AppointmentController extends InfyOmBaseController
{
    /** @var  AppointmentRepository */
    private $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->appointmentRepository = $appointmentRepo;
    }

    public function getViewCalendar(Request $request){
        // buscando locias que pertencem aquela clinica
        $locals[0] = "Selecione um local";
        $locals    = Local::select('locals.name', 'locals.id')->where('clinic_id', Auth::user()->id)->pluck('name', 'id');

        // buscando os medicos que pertencem aquela clinica
        $medics = sizeof($locals) > 0 ? User::selectRaw("locals.name || ' - ' || users.name as name, users.id as user_id, locals.id as local_id")->whereRaw('users.deleted_at is null and locals.deleted_at is null and users.user_type = 2')->join('locals', function($join){
            $join->on('users.id', '=', 'locals.user_id')->where('locals.clinic_id', '=', Auth::user()->id);
        })->get() : [];

        return view("admin.appointments.calendar", ["local" => $request->get("local")], compact('locals', 'medics'));
    }

    /**
     * Display a listing of the HealthPlan.
     *
     * @param AppointmentDataTable $appointmentDataTable
     * @return Response
     */
    public function index(AppointmentDataTable $appointmentDataTable)
    {
        return $appointmentDataTable->render('admin.appointments.index');
    }

    /**
     * Show the form for creating a new Appointment.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.appointments.create');
    }

    /**
     * Store a newly created Appointment in storage.
     *
     * @param CreateAppointmentRequest $request
     *
     * @return Response
     */
    public function store(CreateAppointmentRequest $request)
    {
        $input = $request->all();

        $appointment = $this->appointmentRepository->create($input);

        Flash::success('Agendamento salvo com sucesso!');

        return redirect(route('admin.appointments.index'));
    }

    /**
     * Display the specified Appointment.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);

        if (empty($appointment)) {
            Flash::error('Agendamento não encontrado');

            if(Auth::user()->user_type == User::UserTypeClinic){
                return redirect('admin/appointments-list');
            }else{
                return redirect(route('admin.appointments.index'));
            }
        }

        return view('admin.appointments.show')->with('appointment', $appointment);
    }

    /**
     * Show the form for editing the specified Appointment.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $appointment = $this->appointmentRepository->findWithoutFail($id);

        if (empty($appointment)) {
            Flash::error('Agendamento não encontrado');

            return redirect(route('admin.appointments.index'));
        }

        return view('admin.appointments.edit')->with('appointment', $appointment);
    }

    /**
     * Update the specified Appointment in storage.
     *
     * @param  int              $id
     * @param UpdateAppointmentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAppointmentRequest $request)
    {
        $appointment = $this->appointmentRepository->findWithoutFail($id);

        if (empty($appointment)) {
            Flash::error('Agendamento não encontrado');

            return redirect(route('admin.appointments.index'));
        }

        $appointment = $this->appointmentRepository->update($request->all(), $id);

        Flash::success('Agendamento atualizado com sucesso!');

        return redirect(route('admin.appointments.index'));
    }

    /**
     * Remove the specified Appointment from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id)->first();

        if (empty($appointment)) {
            Flash::error('Agendamento não encontrado');

            return redirect(URL::previous());
        }

        Appointment::destroy($appointment->id);

        Flash::success('Agendamento cancelado com sucesso!');

        return redirect('/admin/appointments-list');
    }
}
