<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\HealthPlanDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateHealthPlanRequest;
use App\Http\Requests\UpdateHealthPlanRequest;
use App\Models\HealthPlan;
use App\Repositories\HealthPlanRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use DB;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\User;

class HealthPlanController extends InfyOmBaseController
{
    /** @var  HealthPlanRepository */
    private $healthPlanRepository;

    public function __construct(HealthPlanRepository $healthPlanRepo)
    {
        $this->healthPlanRepository = $healthPlanRepo;
    }

    /**
     * Display a listing of the HealthPlan.
     *
     * @param HealthPlanDataTable $healthPlanDataTable
     * @return Response
     */
    public function index(HealthPlanDataTable $healthPlanDataTable)
    {
        return $healthPlanDataTable->render('admin.healthPlans.index');
    }

    /**
     * Show the form for creating a new HealthPlan.
     *
     * @return Response
     */
    public function create()
    {
        $healthPlans = HealthPlan::where('id', '!=', 0)->pluck('name', 'id');
        return view('admin.healthPlans.create', compact('healthPlans'));
    }

    public function getMultiplePlansForLocals(Request $request){
        $ids = "";

        for($i = 0; $i < count($request->get("locals_id")); $i++){
            if($i == (count($request->get("locals_id")) - 1)){
                $ids .= $request->get("locals_id")[$i];
            }else{
                $ids .= $request->get("locals_id")[$i].",";
            }
        }

        $plans[] = DB::select(DB::raw("select health_plans.* from health_plans inner join health_plan_local on health_plans.id = health_plan_local.health_plan_id and health_plan_local.local_id in($ids) group by health_plans.name, health_plans.id"));

        return $plans;
    }

    /**
     * Store a newly created HealthPlan in storage.
     *
     * @param CreateHealthPlanRequest $request
     *
     * @return Response
     */
    public function store(CreateHealthPlanRequest $request)
    {
        $input = $request->all();

        if($input['have_to'] == "false"){
            $input['health_plan_id'] = null;
        }else{
            $plan = HealthPlan::whereRaw("lower(name) = '".strtolower($input['health_plan_name'])."'")->get();

            if(count($plan) == 1){
                $input['health_plan_id'] = $plan[0]->id;
            }else{

                $hp = new HealthPlan;

                $hp->name = $input['health_plan_name'];
                $hp->health_plan_id = null;

                $hp->save();

                $input['health_plan_id'] = $hp->id;
            }
        }

        unset($input['have_plan']); unset($input['have_to']); unset($input['health_plan_name']);

        $healthPlan = $this->healthPlanRepository->create($input);

        Flash::success('Plano de saúde salvo com sucesso!');

        /// envio de email para todos medicos quando cadastrar novo plano de saude
        $options = array();
        $options['type'] = 'new_health_plan';
        $options['healthPlan'] = $input['name'];
//        EmailController::sendMultipleEmails(User::where('user_type', '2')->pluck('email', 'id'), $options);

        return redirect(route('admin.healthPlans.index'));
    }

    /**
     * Display the specified HealthPlan.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $healthPlan = $this->healthPlanRepository->findWithoutFail($id);

        if($healthPlan->health_plan_id != null){
            $healthPlanFather = $this->healthPlanRepository->findWithoutFail($healthPlan->health_plan_id);
        }else{
            $healthPlanFather = false;
        }

        if (empty($healthPlan)) {
            Flash::error('Plano de saúde não encontrado');

            return redirect(route('admin.healthPlans.index'));
        }

        return view('admin.healthPlans.show', compact('healthPlanFather'))->with('healthPlan', $healthPlan);
    }

    /**
     * Show the form for editing the specified HealthPlan.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $healthPlan = $this->healthPlanRepository->findWithoutFail($id);
        $healthPlans = HealthPlan::where('id', '!=', 0)->pluck('name', 'id');

        if (empty($healthPlan)) {
            Flash::error('Plano de saúde não encontrado');

            return redirect(route('admin.healthPlans.index'));
        }

        return view('admin.healthPlans.edit')->with('healthPlan', $healthPlan)->with('healthPlans', $healthPlans);
    }

    /**
     * Update the specified HealthPlan in storage.
     *
     * @param  int              $id
     * @param UpdateHealthPlanRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHealthPlanRequest $request)
    {
        $healthPlan = $this->healthPlanRepository->findWithoutFail($id);

        if (empty($healthPlan)) {
            Flash::error('Plano de saúde não encontrado');

            return redirect(route('admin.healthPlans.index'));
        }

        $healthPlan = $this->healthPlanRepository->update($request->all(), $id);

        Flash::success('Plano de saúde atualizado com sucesso!');

        return redirect(route('admin.healthPlans.index'));
    }

    /**
     * Remove the specified HealthPlan from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $healthPlan = $this->healthPlanRepository->findWithoutFail($id);

        if (empty($healthPlan)) {
            Flash::error('Plano de saúde não encontrado');

            return redirect(route('admin.healthPlans.index'));
        }

        $this->healthPlanRepository->delete($id);

        Flash::success('Plano de saúde deletado com sucesso!');

        return redirect(route('admin.healthPlans.index'));
    }

    public function getPlans($id){
        $plans = HealthPlan::select('health_plans.*')->join('health_plan_local', function($join) use ($id){
            $join->on('health_plans.id', '=', 'health_plan_local.health_plan_id')->where('health_plan_local.local_id', '=', $id);
        })->get();

        return $plans;
    }
}
