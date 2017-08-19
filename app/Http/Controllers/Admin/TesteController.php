<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TesteDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateTesteRequest;
use App\Http\Requests\Admin\UpdateTesteRequest;
use App\Repositories\Admin\TesteRepository;
use Flash;
use InfyOm\Generator\Controller\AppBaseController;
use Response;

class TesteController extends AppBaseController
{
    /** @var  TesteRepository */
    private $testeRepository;

    public function __construct(TesteRepository $testeRepo)
    {
        $this->testeRepository = $testeRepo;
    }

    /**
     * Display a listing of the Teste.
     *
     * @param TesteDataTable $testeDataTable
     * @return Response
     */
    public function index(TesteDataTable $testeDataTable)
    {
        return $testeDataTable->render('admin.testes.index');
    }

    /**
     * Show the form for creating a new Teste.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.testes.create');
    }

    /**
     * Store a newly created Teste in storage.
     *
     * @param CreateTesteRequest $request
     *
     * @return Response
     */
    public function store(CreateTesteRequest $request)
    {
        $input = $request->all();

        $teste = $this->testeRepository->create($input);

        Flash::success('Teste saved successfully.');

        return redirect(route('admin.testes.index'));
    }

    /**
     * Display the specified Teste.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $teste = $this->testeRepository->findWithoutFail($id);

        if (empty($teste)) {
            Flash::error('Teste not found');

            return redirect(route('admin.testes.index'));
        }

        return view('admin.testes.show')->with('teste', $teste);
    }

    /**
     * Show the form for editing the specified Teste.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $teste = $this->testeRepository->findWithoutFail($id);

        if (empty($teste)) {
            Flash::error('Teste not found');

            return redirect(route('admin.testes.index'));
        }

        return view('admin.testes.edit')->with('teste', $teste);
    }

    /**
     * Update the specified Teste in storage.
     *
     * @param  int              $id
     * @param UpdateTesteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTesteRequest $request)
    {
        $teste = $this->testeRepository->findWithoutFail($id);

        if (empty($teste)) {
            Flash::error('Teste not found');

            return redirect(route('admin.testes.index'));
        }

        $teste = $this->testeRepository->update($request->all(), $id);

        Flash::success('Teste updated successfully.');

        return redirect(route('admin.testes.index'));
    }

    /**
     * Remove the specified Teste from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $teste = $this->testeRepository->findWithoutFail($id);

        if (empty($teste)) {
            Flash::error('Teste not found');

            return redirect(route('admin.testes.index'));
        }

        $this->testeRepository->delete($id);

        Flash::success('Teste deleted successfully.');

        return redirect(route('admin.testes.index'));
    }
}
