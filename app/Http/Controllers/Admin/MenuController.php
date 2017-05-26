<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\MenuDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Http\Requests\Request;
use App\Models\Admin\Menu;
use App\Repositories\Admin\MenuRepository;
use Flash;
use InfyOm\Generator\Controller\AppBaseController;
use Maknz\Slack\Facades\Slack;
use Response;
use Auth;

class MenuController extends AppBaseController
{
    /** @var  MenuRepository */
    private $menuRepository;

    public function __construct(MenuRepository $menuRepo)
    {
        $this->menuRepository = $menuRepo;
    }

    /**
     * Display a listing of the Menu.
     *
     * @param MenuDataTable $menuDataTable
     * @return Response
     */
    public function index(MenuDataTable $menuDataTable)
    {
        return $menuDataTable->render('admin.menus.index');
    }

    /**
     * Show the form for creating a new Menu.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created Menu in storage.
     *
     * @param CreateMenuRequest $request
     *
     * @return Response
     */
    public function store(CreateMenuRequest $request)
    {
        $input = $request->all();

        if($input['menu_root'] == ""){
            unset($input['menu_root']);
        }

        if($input['order'] == ""){
            unset($input['order']);
        }

        $menu = $this->menuRepository->create($input);

        Flash::success('Menu saved successfully.');

        // Send a message to the default channel
        Slack::send('Foi gerado o menu: '.$menu->menu);

        return redirect(route('admin.menus.index'));
    }

    /**
     * Display the specified Menu.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $menu = $this->menuRepository->findWithoutFail($id);

        if (empty($menu)) {
            Flash::error('Menu not found');

            return redirect(route('admin.menus.index'));
        }

        return view('admin.menus.show')->with('menu', $menu);
    }

    /**
     * Show the form for editing the specified Menu.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $menu = $this->menuRepository->findWithoutFail($id);

        if (empty($menu)) {
            Flash::error('Menu not found');

            return redirect(route('admin.menus.index'));
        }

        return view('admin.menus.edit')->with('menu', $menu);
    }

    /**
     * Update the specified Menu in storage.
     *
     * @param  int              $id
     * @param UpdateMenuRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMenuRequest $request)
    {
        $menu = $this->menuRepository->findWithoutFail($id);
        $input = $request->all();

        if (empty($menu)) {
            Flash::error('Menu not found');

            return redirect(route('admin.menus.index'));
        }

        $menu = $this->menuRepository->update($input, $id);

        Flash::success('Menu updated successfully.');

        return redirect(route('admin.menus.index'));
    }

    /**
     * Remove the specified Menu from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $menu = $this->menuRepository->findWithoutFail($id);

        if (empty($menu)) {
            Flash::error('Menu not found');

            return redirect(route('admin.menus.index'));
        }

        if( $this->menuRepository->delete($id) ){
            return response()->json($menu, 200);
        }else{
            return response()->json($menu, 500);
        }
    }

    public function getMenus(){
        $menus = Menu::where('appears_to', Auth::user()->id)->orderby('order')->get();

        return response()->json($menus);
    }

    public function getViewOrder(){
        $menus = Menu::orderBy('order')->get();
        return view('admin.menus.order')->with('menus', $menus);
    }

    public function postViewOrder(Request $request){
        return dump($request);
    }
}
