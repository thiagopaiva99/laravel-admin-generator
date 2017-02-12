<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 24/08/16
 * Time: 14:07
 */
namespace App\DataTables;

use App\DataTables\Scopes\LocalDataTableScope;
use App\Helpers\StringHelper;
use App\Models\Local;
use App\Models\User;
use Form;
use Auth;

use \Yajra\Datatables\Datatables;
use \Illuminate\Contracts\View\Factory;

class LocalDataTable extends DataTable
{
    public function __construct(Datatables $datatables, Factory $viewFactory) {
        parent::__construct($datatables, $viewFactory);

        $this->addScope(new LocalDataTableScope());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('phone', function($local) {
                if(isset($local->phone)) {
                    $phone = $local->phone;
                    $phoneNumbers = StringHelper::onlyNumbers($phone);
                    return StringHelper::phoneFormat($phoneNumbers);
                }
                return "";
            })
            ->editColumn('address', function($local) {
                return Local::formatAddress($local);
            })
            ->editColumn('amount', function($local) {
                if(isset($local->amount)) {
                    return "R$ " . number_format($local->amount, 2, ",", ".");
                }
                return "";
            })
            ->addColumn('action', 'admin.locals.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (Auth::user()->user_type == User::UserTypeClinic){
            $locals = Local::where('clinic_id', Auth::user()->id);

            //$locals = Local::select('locals.id', 'locals.name', 'locals.address', 'locals.phone', 'locals.amount', 'users.name as user')->join('users', function($join){
            //    $join->on('users.id', '=', 'locals.user_id')->where('clinic_id', '=', Auth::user()->id);
            //});
        }else{
            $locals = Local::query();
        }

        return $this->applyScopes($locals);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        if(Auth::user()->user_type == User::UserTypeClinic){
            return $this->builder()
                ->columns($this->getColumns())
                ->addAction(['width' => '10%', 'title' => 'Ação'])
                ->ajax('')
                ->parameters([
                    'dom' => 'Bfrtip',
                    'scrollX' => false,
                    'buttons' => [
                        'create',
                        'print',
                        'reset',
                        'reload',
                        'excel',
                    ],
                    'stateSave' => true,
                    'language' => $this->languageOptions
                ]);
        }else{
            return $this->builder()
                ->columns($this->getColumns())
                ->addAction(['width' => '10%', 'title' => 'Ação'])
                ->ajax('')
                ->parameters([
                    'dom' => 'Bfrtip',
                    'scrollX' => false,
                    'buttons' => [
                        'print',
                        'reset',
                        'reload',
                        'excel',
                    ],
                    'stateSave' => true,
                    'language' => $this->languageOptions
                ]);
        }
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        if(Auth::user()->user_type == User::UserTypeClinic){
            return [
                'name' => ['name' => 'name', 'data' => 'name', "title" => "Nome"],
                'user' => ['user' => 'user.name', 'data' => 'user.name', 'title' => "Usuário"],
                'address' => ['name' => 'address', 'data' => 'address', "title" => "Endereço"],
                'phone' => ['name' => 'phone', 'data' => 'phone', "title" => "Telefone"],
                'amount' => ['name' => 'amount', 'data' => 'amount', "title" => "Valor"],
                // 'appointment_duration_in_minutes' => ['name' => 'appointment_duration_in_minutes', 'data' => 'appointment_duration_in_minutes', "title" => "Intervalo de consultas"],
            ];
        }else{
            return [
                'name' => ['name' => 'name', 'data' => 'name', "title" => "Nome"],
                'address' => ['name' => 'address', 'data' => 'address', "title" => "Endereço"],
                'phone' => ['name' => 'phone', 'data' => 'phone', "title" => "Telefone"],
                'amount' => ['name' => 'amount', 'data' => 'amount', "title" => "Valor"],
                // 'appointment_duration_in_minutes' => ['name' => 'appointment_duration_in_minutes', 'data' => 'appointment_duration_in_minutes', "title" => "Intervalo de consultas"],
            ];
        }
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'locals';
    }
}