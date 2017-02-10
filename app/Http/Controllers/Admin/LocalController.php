<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LocalDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateLocalRequest;
use App\Http\Requests\UpdateLocalRequest;
use App\Models\Local;
use App\Models\User;
use App\Models\Exam;
use App\Models\HealthPlan;
use App\Models\Specialization;
use App\Repositories\LocalRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Auth;
use Illuminate\Http\Request;
use Flash;
use Phaza\LaravelPostgis\Geometries\Point;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class LocalController extends InfyOmBaseController
{
    /** @var  LocalRepository */
    private $localRepository;

    public function __construct(LocalRepository $localRepo)
    {
        $this->localRepository = $localRepo;
    }

    /**
     * Display a listing of the HealthPlan.
     *
     * @param LocalDataTable $localDataTable
     * @return Response
     */
    public function index(LocalDataTable $localDataTable)
    {
//        return dump($localDataTable->ajax());
        return $localDataTable->render('admin.locals.index');
    }

    public function getLocalsForMultipleMedics(Request $request){
        $locals = [];

        for($i = 0; $i < count($request->get("users_id")); $i++){
            $locals[] = Local::where('user_id', $request->get("users_id")[$i])->first();
        }

        return $locals;
    }

    /**
     * Show the form for creating a new Local.
     *
     * @return Response
     */
    public function create()
    {
        $clinics = User::where('user_type', '4')->pluck('name', 'id');
        $exams = Exam::pluck("name", "id");
        $healthPlans = HealthPlan::pluck("name", "id");
        $specializations = Specialization::pluck("name", "id");
        $locals = Local::where('clinic_id', Auth::user()->id)->pluck('name', 'id');
        $medics = User::select('users.name', 'users.id')->where('user_type', 2)->join('clinic_user', function($join){
            $join->on('users.id', '=', 'clinic_user.user_id')->where('clinic_id', '=', Auth::user()->id);
        })->get();

        return view('admin.locals.create')
            ->with('exams', $exams)
            ->with('health_plans', $healthPlans)
            ->with('specializations', $specializations)
            ->with('clinics', $clinics)
            ->with('locals', $locals)
            ->with('medics', $medics);
    }

    /**
     * Store a newly created Local in storage.
     *
     * @param CreateLocalRequest $request
     *
     * @return Response
     */
    public function store(CreateLocalRequest $request)
    {
        $input = $request->all();

        $input['user_id']  = $input['user_idd'];
        $input['user_idd'] = null; unset($input['user_idd']);

        $lat = $input["lat"];
        $lng = $input["lng"];

        $input["lat"] = null;
        $input["lng"] = null;

        $input["location"] = new Point($lat, $lng);

        if(Auth::user()->user_type == User::UserTypeClinic){
            $input["clinic_id"] = Auth::user()->id;
        }else{
            $input['clinic_id'] = (int)$input['clinic_id'];
            $input['user_id'] = Auth::user()->id;
        }

        $local = $this->localRepository->create($input);

        if ($request->has("exams")) {
            $local->exams()->sync($request->get('exams'));
        }

        if ($request->has("healthPlans")) {
            $local->healthPlans()->sync($request->get('healthPlans'));
        }

        if ($request->has("specializations")) {
            $local->specializations()->sync($request->get('specializations'));
        }

        Flash::success('Local de atendimento salvo com sucesso!');

        return redirect(route('admin.locals.index'));
    }

    /**
     * Display the specified Local.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $local = Local::find($id);

        if (empty($local)) {
            Flash::error('Local de atendimento não encontrado');

            return redirect(route('admin.locals.index'));
        }

        return view('admin.locals.show')->with('local', $local);
    }

    /**
     * Show the form for editing the specified Local.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $local = Local::find($id);

        if (empty($local)) {
            Flash::error('Local de atendimento não encontrado');

            return redirect(route('admin.locals.index'));
        }

        $clinics = User::where('user_type', '4')->pluck('name', 'id');
        $exams = Exam::pluck("name", "id");
        $healthPlans = HealthPlan::pluck("name", "id");
        $specializations = Specialization::pluck("name", "id");
        $locals = Local::where('clinic_id', Auth::user()->id)->pluck('name', 'id');
        $medics = User::select('users.name', 'users.id')->where('user_type', 2)->join('clinic_user', function($join){
            $join->on('users.id', '=', 'clinic_user.user_id')->where('clinic_id', '=', Auth::user()->id);
        })->get();

        return view('admin.locals.edit')
            ->with('local', $local)
            ->with('exams', $exams)
            ->with('health_plans', $healthPlans)
            ->with('specializations', $specializations)
            ->with('clinics', $clinics)
            ->with('locals', $locals)
            ->with('medics', $medics);
    }

    /**
     * Update the specified Local in storage.
     *
     * @param  int              $id
     * @param UpdateLocalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLocalRequest $request)
    {
        $local = Local::find($id);

        if (empty($local)) {
            Flash::error('Local de atendimento não encontrado');

            return redirect(route('admin.locals.index'));
        }

        $input = $request->all();

        $input['user_id']  = $input['user_idd'];
        $input['user_idd'] = null; unset($input['user_idd']);

        $lat = $input["lat"];
        $lng = $input["lng"];

        $input["lat"] = null;
        $input["lng"] = null;

        $input["location"] = new Point($lat, $lng);

        if(Auth::user()->user_type == User::UserTypeClinic){
            $input["clinic_id"] = Auth::user()->id;
        }else{
            $input["clinic_id"] = "";
        }

        $local = $this->localRepository->update($input, $id);

        if ($request->has("exams")) {
            $local->exams()->sync($request->get('exams'));
        }

        if ($request->has("healthPlans")) {
            $local->healthPlans()->sync($request->get('healthPlans'));
        }

        if ($request->has("specializations")) {
            $local->specializations()->sync($request->get('specializations'));
        }

        Flash::success('Local de atendimento atualizado com sucesso!');

        return redirect(route('admin.locals.index'));
    }

    /**
     * Remove the specified Local from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $local = Local::find($id);

        if (empty($local)) {
            Flash::error('Local de atendimento não encontrado');

            return redirect(route('admin.locals.index'));
        }

        $local->delete();

        Flash::success('Local de atendimento deletado com sucesso!');

        return redirect(route('admin.locals.index'));
    }

    function getLocalDetails(Request $request){
        $id = $request->get("id");

        $local = Local::where('id', $id)->first()->toArray();
        $exams = \DB::select(\DB::raw("select exams.* from exam_local inner join exams on exam_local.exam_id = exams.id where local_id = ".$local['id']));
        $health_plans = \DB::select(\DB::raw("select health_plans.* from health_plan_local inner join health_plans on health_plan_local.health_plan_id = health_plans.id where local_id = ".$local['id']));
        $specializations = \DB::select(\DB::raw("select specializations.* from local_specialization inner join specializations on local_specialization.specialization_id = specializations.id where local_id = ".$local['id']));

        $local['exams']           = $exams;
        $local['health_plans']    = $health_plans;
        $local['specializations'] = $specializations;

        return $local;
    }
}
