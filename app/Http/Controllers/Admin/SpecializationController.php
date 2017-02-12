<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SpecializationDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSpecializationRequest;
use App\Http\Requests\UpdateSpecializationRequest;
use App\Repositories\SpecializationRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\User;

class SpecializationController extends InfyOmBaseController
{
    /** @var  SpecializationRepository */
    private $specializationRepository;

    public function __construct(SpecializationRepository $specializationRepo)
    {
        $this->specializationRepository = $specializationRepo;
    }

    /**
     * Display a listing of the HealthPlan.
     *
     * @param SpecializationDataTable $specializationDataTable
     * @return Response
     */
    public function index(SpecializationDataTable $specializationDataTable)
    {
        return $specializationDataTable->render('admin.specializations.index');
    }


    /**
     * Show the form for creating a new Specialization.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.specializations.create');
    }

    /**
     * Store a newly created Specialization in storage.
     *
     * @param CreateSpecializationRequest $request
     *
     * @return Response
     */
    public function store(CreateSpecializationRequest $request)
    {
        $input = $request->all();

        $specialization = $this->specializationRepository->create($input);

        Flash::success('Especialização salva com sucesso!');

        /// envio de email para todos medicos quando cadastrar novo especialização
        $options = array();
        $options['type'] = 'new_specialization';
        $options['specialization'] = $input['name'];
        EmailController::sendMultipleEmails(User::where('user_type', '2')->pluck('email', 'id'), $options);

        return redirect(route('admin.specializations.index'));
    }

    /**
     * Display the specified Specialization.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $specialization = $this->specializationRepository->findWithoutFail($id);

        if (empty($specialization)) {
            Flash::error('Especialização não encontrada');

            return redirect(route('admin.specializations.index'));
        }

        return view('admin.specializations.show')->with('specialization', $specialization);
    }

    /**
     * Show the form for editing the specified Specialization.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $specialization = $this->specializationRepository->findWithoutFail($id);

        if (empty($specialization)) {
            Flash::error('Especialização não encontrada');

            return redirect(route('admin.specializations.index'));
        }

        return view('admin.specializations.edit')->with('specialization', $specialization);
    }

    /**
     * Update the specified Specialization in storage.
     *
     * @param  int              $id
     * @param UpdateSpecializationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSpecializationRequest $request)
    {
        $specialization = $this->specializationRepository->findWithoutFail($id);

        if (empty($specialization)) {
            Flash::error('Especialização não encontrada');

            return redirect(route('admin.specializations.index'));
        }

        $specialization = $this->specializationRepository->update($request->all(), $id);

        Flash::success('Especialização atualizada com sucesso!');

        return redirect(route('admin.specializations.index'));
    }

    /**
     * Remove the specified Specialization from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $specialization = $this->specializationRepository->findWithoutFail($id);

        if (empty($specialization)) {
            Flash::error('Especialização não encontrada');

            return redirect(route('admin.specializations.index'));
        }

        $this->specializationRepository->delete($id);

        Flash::success('Especialização deletada com sucesso!');

        return redirect(route('admin.specializations.index'));
    }
}
