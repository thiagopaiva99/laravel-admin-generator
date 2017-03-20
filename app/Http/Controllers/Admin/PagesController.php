<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PagesDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreatePagesRequest;
use App\Http\Requests\Admin\UpdatePagesRequest;
use App\Models\Admin\Pages;
use App\Repositories\Admin\PagesRepository;
use Flash;
use InfyOm\Generator\Controller\AppBaseController;
use Response;

class PagesController extends AppBaseController
{
    /** @var  PagesRepository */
    private $pagesRepository;

    public function __construct(PagesRepository $pagesRepo)
    {
        $this->pagesRepository = $pagesRepo;
    }

    /**
     * Display a listing of the Pages.
     *
     * @param PagesDataTable $pagesDataTable
     * @return Response
     */
    public function index(PagesDataTable $pagesDataTable)
    {
        return $pagesDataTable->render('admin.pages.index');
    }

    /**
     * Show the form for creating a new Pages.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created Pages in storage.
     *
     * @param CreatePagesRequest $request
     *
     * @return Response
     */
    public function store(CreatePagesRequest $request)
    {
        $input = $request->all();

        $page = Pages::create([
           'name' => $input['name']
        ])->save();

//        $pages = $this->pagesRepository->create($input);

        $shell = "cd .. && echo '[".$request->get("str")."]' > infyom_json.json && php artisan infyom:scaffold ".ucwords($request->name)." --prefix=admin --paginate=10 --datatables=true --fieldsFile=infyom_json.json";

        shell_exec($shell);

        Flash::success('Pages saved successfully.');

        return redirect(route('admin.pages.index'));
    }

    /**
     * Display the specified Pages.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $pages = $this->pagesRepository->findWithoutFail($id);

        if (empty($pages)) {
            Flash::error('Pages not found');

            return redirect(route('admin.pages.index'));
        }

        return view('admin.pages.show')->with('pages', $pages);
    }

    /**
     * Show the form for editing the specified Pages.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pages = $this->pagesRepository->findWithoutFail($id);

        if (empty($pages)) {
            Flash::error('Pages not found');

            return redirect(route('admin.pages.index'));
        }

        return view('admin.pages.edit')->with('pages', $pages);
    }

    /**
     * Update the specified Pages in storage.
     *
     * @param  int              $id
     * @param UpdatePagesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePagesRequest $request)
    {
        $pages = $this->pagesRepository->findWithoutFail($id);

        if (empty($pages)) {
            Flash::error('Pages not found');

            return redirect(route('admin.pages.index'));
        }

        $pages = $this->pagesRepository->update($request->all(), $id);

        Flash::success('Pages updated successfully.');

        return redirect(route('admin.pages.index'));
    }

    /**
     * Remove the specified Pages from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pages = $this->pagesRepository->findWithoutFail($id);

        if (empty($pages)) {
            Flash::error('Pages not found');

            return redirect(route('admin.pages.index'));
        }

        $this->pagesRepository->delete($id);

        Flash::success('Pages deleted successfully.');

        return redirect(route('admin.pages.index'));
    }
}
