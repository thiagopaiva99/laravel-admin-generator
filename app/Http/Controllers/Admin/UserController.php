<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Scopes\UserDataTableScope;
use App\DataTables\UserDataTable;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Barryvdh\Debugbar\Middleware\Debugbar;
use DB;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\User;
use Auth;

class UserController extends InfyOmBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @internal param Request $request
     */
    public function index(UserDataTable $userDataTable) //, Request $request)
    {
        $userDataTable->addScope(new UserDataTableScope);

        return $userDataTable->render('admin.users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $approvalStatus = [
            User::ApprovalStatusPending => "Aguardando",
            User::ApprovalStatusAccepted => "Aprovado",
            User::ApprovalStatusDenied => "Negado"
        ];

//        Debugbar::error($approvalStatus);

        return view('admin.users.create', compact('clinics', 'approvalStatus'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $input['password']  = bcrypt($input['password']);
        $input['image_src'] = 'http://embelezzo.aiolia.inf.br/assets/site/images/img_medicos.jpg';

        if( User::where('email', $input['email'])->count() == 0 ){
            $this->userRepository->create($input);
        }else{
            // something
        }

        Flash::success('Usuário salvo com sucesso!');
        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {

        $user = $this->userRepository->findWithoutFail($id);
        // dd($user);

        if (empty($user) && $id != \Auth::id()) {
            Flash::error('Usuário não encontrado.');
            return redirect(route('admin.users.index'));
        }

        if (empty($user)) {
            // carregar o próprio usuário
            $user = User::find(Auth::user()->id);

            if (empty($user)) {
                Flash::error('Usuário não encontrado');
                return redirect(route('admin.users.index'));
            }
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user) && $id != Auth::user()->id) {
            Flash::error('Usuário não encontrado.');
            return redirect(route('admin.users.index'));
        }

        if (empty($user)) {
            // carregar o próprio usuário
            $user = User::find(Auth::user()->id);

            if (empty($user)) {
                Flash::error('Usuário não encontrado');
                return redirect(route('admin.users.index'));
            }
        }

        $approvalStatus = [
            User::ApprovalStatusPending => "Aguardando",
            User::ApprovalStatusAccepted => "Aprovado",
            User::ApprovalStatusDenied => "Negado"
        ];

        return view('admin.users.edit', compact('user', 'approvalStatus'));
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param Request $request
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        $input = $request->all();

        if (empty($user) && $id != Auth::user()->id) {
            Flash::error('Usuário não encontrado');
            return redirect(route('admin.users.index'));
        }

        if (empty($user)) {
            // carregar o próprio usuário
            $user = User::find(Auth::user()->id);

            if (empty($user)) {
                Flash::error('Usuário não encontrado');
                return redirect(route('admin.users.index'));
            }
        }

        if($input['password'] == "") $input['password'] = $user->password;
        else $input['password'] = bcrypt($input['password']);

        $user = $user->update($input);

        if($id == \Auth::id()) {
            Flash::success('Perfil atualizado.');
            return redirect(route('admin.users.show', [$id]));
        } else {
            Flash::success('Usuário atualizado.');
            return redirect(route('admin.users.index'));
        }
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Usuário não encontrado');

            return redirect(route('admin.users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('Usuário deletado com sucesso!');

        return redirect(route('admin.users.index'));
    }

    public function test(){
        $tables = DB::raw(DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'"));

        dump($tables);

        $table = DB::raw(DB::select("
            SELECT DISTINCT
                a.attnum as num,
                a.attname as name,
                format_type(a.atttypid, a.atttypmod) as typ,
                a.attnotnull as notnull, 
                com.description as comment,
                coalesce(i.indisprimary,false) as primary_key,
                def.adsrc as default
            FROM pg_attribute a 
            JOIN pg_class pgc ON pgc.oid = a.attrelid
            LEFT JOIN pg_index i ON 
                (pgc.oid = i.indrelid AND i.indkey[0] = a.attnum)
            LEFT JOIN pg_description com on 
                (pgc.oid = com.objoid AND a.attnum = com.objsubid)
            LEFT JOIN pg_attrdef def ON 
                (a.attrelid = def.adrelid AND a.attnum = def.adnum)
            WHERE a.attnum > 0 AND pgc.oid = a.attrelid
            AND pg_table_is_visible(pgc.oid)
            AND NOT a.attisdropped
            AND pgc.relname = 'users'  -- Your table name here
            ORDER BY a.attnum;
        "))->getValue();
        return;
    }
}
