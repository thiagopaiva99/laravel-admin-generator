<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ClosedDateDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateClosedDateRequest;
use App\Http\Requests\UpdateClosedDateRequest;
use App\Models\ClosedDate;
use App\Models\User;
use App\Repositories\ClosedDateRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Flash;
use Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Carbon\Carbon;

class ClosedDateController extends InfyOmBaseController
{
    /** @var  ClosedDateRepository */
    private $closedDateRepository;

    public function __construct(ClosedDateRepository $closedDateRepo)
    {
        $this->closedDateRepository = $closedDateRepo;
    }

    /**
     * Display a listing of the HealthPlan.
     *
     * @param ClosedDateDataTable $closedDateDataTable
     * @return Response
     */
    public function index(ClosedDateDataTable $closedDateDataTable)
    {
        return $closedDateDataTable->render('admin.closedDates.index');
    }

    /**
     * Show the form for creating a new ClosedDate.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $locals = \Auth::user()->locals->pluck("name","id");
        if($request->hasCookie("local")) {
            $local = $request->cookie("local");
        } else {
            $local = null;
        }

        if ($request->has('start')) {
            $startEpoch = $request->get('start') / 1000;
            $start = Carbon::createFromTimestampUTC($startEpoch)->setTimezone('UTC');;
//            $start->setTimezone('America/Sao_Paulo');
        } else {
            $start = null;
        }

        if ($request->has('end')) {
            $endEpoch = $request->get('end') / 1000;
            $end = Carbon::createFromTimestampUTC($endEpoch)->setTimezone('UTC');;
//            $end->setTimezone('America/Sao_Paulo');
        } else {
            $end = null;
        }

        return view('admin.closedDates.create')
            ->with('local', $local)
            ->with('start', $start)
            ->with('end', $end)
            ->with("locals", $locals);
    }

    /**
     * Store a newly created ClosedDate in storage.
     *
     * @param CreateClosedDateRequest $request
     *
     * @return Response
     */
    public function store(CreateClosedDateRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();

//        esta dando erro ao inserir, vou ver com alguem depois pra nao ficar preso nisso
        $closedDate = $this->closedDateRepository->create($input);

        Flash::success('Horário fechado salvo com sucesso!');

        return redirect(route('admin.calendar.index', ['local' => $closedDate->local_id]));
    }

    public function inserir(CreateClosedDateRequest $request)
    {
        $input = $request->all();

        if(Auth::user()->user_type == 2){
            $input['user_id'] = Auth::user()->id;
        }

        $closedDate = $this->closedDateRepository->create($input);

        return $closedDate;
    }

    /**
     * Display the specified ClosedDate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $closedDate = $this->closedDateRepository->findWithoutFail($id);

        if (empty($closedDate)) {
            Flash::error('Horário fechado não encontrado');

        return redirect(route('admin.calendar.index'));
        }

        return view('admin.closedDates.show')->with('closedDate', $closedDate);
    }

    /**
     * Show the form for editing the specified ClosedDate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $closedDate = $this->closedDateRepository->findWithoutFail($id);

        if (empty($closedDate)) {
            Flash::error('Horário fechado não encontrado');

            return redirect(route('admin.calendar.index'));
        }
        $locals = \Auth::user()->locals->pluck("name","id");
        return view('admin.closedDates.edit')
            ->with('closedDate', $closedDate)
            ->with("locals", $locals);
    }

    /**
     * Update the specified ClosedDate in storage.
     *
     * @param  int              $id
     * @param UpdateClosedDateRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClosedDateRequest $request)
    {
        $closedDate = $this->closedDateRepository->findWithoutFail($id);

        if (empty($closedDate)) {
            Flash::error('Horário fechado não encontrado');

            return redirect(route('admin.calendar.index'));
        }

        $closedDate = $this->closedDateRepository->update($request->all(), $id);

        Flash::success('Horário fechado atualizado com sucesso!');

        return redirect(route('admin.calendar.index', ['local' => $closedDate->local_id]));
    }

    /**
     * Remove the specified ClosedDate from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $closedDate = $this->closedDateRepository->findWithoutFail($id);

        if (empty($closedDate)) {
            Flash::error('Horário fechado não encontrado');

            return redirect(route('admin.calendar.index'));
        }

        $this->closedDateRepository->delete($id);

        Flash::success('Horário fechado deletado com sucesso!');

        if(Auth::user()->user_type == User::UserTypeClinic){
            return redirect(route('admin.clinic.index'));
        }else{
            return redirect(route('admin.calendar.index'));
        }
    }

    public function destroyClosedDate($id)
    {
        $closedDate = $this->closedDateRepository->findWithoutFail($id);

        if (empty($closedDate)) {
            Flash::error('Horário fechado não encontrado');

            return redirect(\URL::previous());
        }

        $this->closedDateRepository->delete($id);

        Flash::success('Horário fechado deletado com sucesso!');

        return redirect(\URL::previous());
    }

    public function deletar($id)
    {
        $closedDate = $this->closedDateRepository->findWithoutFail($id);

        if (empty($closedDate)) {
            return "nao deletou";
        }

        $this->closedDateRepository->delete($id);

        return "deletou";
    }
}
