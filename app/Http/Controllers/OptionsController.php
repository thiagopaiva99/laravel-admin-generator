<?php

namespace App\Http\Controllers;

use App\DataTables\OptionsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateOptionsRequest;
use App\Http\Requests\UpdateOptionsRequest;
use App\Repositories\OptionsRepository;
use Flash;
use InfyOm\Generator\Controller\AppBaseController;
use Response;

class OptionsController extends AppBaseController
{
    /** @var  OptionsRepository */
    private $optionsRepository;

    public function __construct(OptionsRepository $optionsRepo)
    {
        $this->optionsRepository = $optionsRepo;
    }

    /**
     * Display a listing of the Options.
     *
     * @param OptionsDataTable $optionsDataTable
     * @return Response
     */
    public function index(OptionsDataTable $optionsDataTable)
    {
        return $optionsDataTable->render('options.index');
    }

    /**
     * Show the form for creating a new Options.
     *
     * @return Response
     */
    public function create()
    {
        return view('options.create');
    }

    /**
     * Store a newly created Options in storage.
     *
     * @param CreateOptionsRequest $request
     *
     * @return Response
     */
    public function store(CreateOptionsRequest $request)
    {
        $input = $request->all();

        $options = $this->optionsRepository->create($input);

        Flash::success('Options saved successfully.');

        return redirect(route('options.index'));
    }

    /**
     * Display the specified Options.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $options = $this->optionsRepository->findWithoutFail($id);

        if (empty($options)) {
            Flash::error('Options not found');

            return redirect(route('options.index'));
        }

        return view('options.show')->with('options', $options);
    }

    /**
     * Show the form for editing the specified Options.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $options = $this->optionsRepository->findWithoutFail($id);

        if (empty($options)) {
            Flash::error('Options not found');

            return redirect(route('options.index'));
        }

        return view('options.edit')->with('options', $options);
    }

    /**
     * Update the specified Options in storage.
     *
     * @param  int              $id
     * @param UpdateOptionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOptionsRequest $request)
    {
        $options = $this->optionsRepository->findWithoutFail($id);

        if (empty($options)) {
            Flash::error('Options not found');

            return redirect(route('options.index'));
        }

        $options = $this->optionsRepository->update($request->all(), $id);

        Flash::success('Options updated successfully.');

        return redirect(route('options.index'));
    }

    /**
     * Remove the specified Options from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $options = $this->optionsRepository->findWithoutFail($id);

        if (empty($options)) {
            Flash::error('Options not found');

            return redirect(route('options.index'));
        }

        $this->optionsRepository->delete($id);

        Flash::success('Options deleted successfully.');

        return redirect(route('options.index'));
    }
}
