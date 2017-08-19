<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\OptionsDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateOptionsRequest;
use App\Http\Requests\Admin\UpdateOptionsRequest;
use App\Models\Admin\Options;
use App\Repositories\Admin\OptionsRepository;
use Flash;
use InfyOm\Generator\Controller\AppBaseController;
use Maknz\Slack\Facades\Slack;
use Response;
use Request;

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
        return $optionsDataTable->render('admin.options.index');
    }

    /**
     * Show the form for creating a new Options.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.options.create');
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

        $shell = "cd .. && cat .env && echo ".strtoupper($input['key'])."='".$input['value']."' >> .env";

        shell_exec($shell);

        // Send a message to the default channel
        Slack::send('Foi gerada a opçao '.$input['key'].' com o valor de "'.$input['value'].'"');

        Flash::success('Options saved successfully.');

        return redirect(route('admin.options.index'));
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

            return redirect(route('admin.options.index'));
        }

        return view('admin.options.show')->with('options', $options);
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

            return redirect(route('admin.options.index'));
        }

        return view('admin.options.edit')->with('options', $options);
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

        $option = $options;

//        $shell = "sed -i ''s|".strtoupper($option['key'])."=".strtoupper($option['value'])."|".strtoupper($option['key'])."=".strtoupper($option['value'])."|i' ../.env";
        $shell = "sed -i ''s|".strtoupper($option['key'])."='".$option['value']."'|".strtoupper($option['key'])."=AAAAA|i' ../.env";

        return $shell;

        if (empty($options)) {
            Flash::error('Options not found');

            return redirect(route('admin.options.index'));
        }

        $options = $this->optionsRepository->update($request->all(), $id);

        Flash::success('Options updated successfully.');

        return redirect(route('admin.options.index'));
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

            return redirect(route('admin.options.index'));
        }

        $option = Options::find($id)->key;

        shell_exec("cd .. && sed -i \"/$option/d\" .env");

        // Send a message to the default channel
        Slack::send('Foi deletada o opçao '.$option);

        $this->optionsRepository->delete($id);

        Flash::success('Options deleted successfully.');

        return redirect(route('admin.options.index'));
    }
}
