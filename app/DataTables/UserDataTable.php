<?php

namespace App\DataTables;

use App\Helpers\StringHelper;
use App\Models\User;
use Form;
use Auth;

use App\DataTables\Scopes\UserDataTableScope;

use \Yajra\Datatables\Datatables;
use \Illuminate\Contracts\View\Factory;

class UserDataTable extends DataTable
{
    public function __construct(Datatables $datatables, Factory $viewFactory) {
        parent::__construct($datatables, $viewFactory);

        $this->addScope(new UserDataTableScope);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.users.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $users = User::query();

        return $this->applyScopes($users);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        $buttons = [
            'create',
            'print',
            'reset',
            'reload',
            'excel',

        ];

        return $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '10%', 'title' => 'Ação'])
            ->ajax('')
            ->parameters([
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'buttons' => $buttons,
                'stateSave' => true,
                'language' => $this->languageOptions
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'name'          => ['name' => 'name', 'data' => 'name', "title" => "Nome"],
            'email'         => ['name' => 'email', 'data' => 'email', "title" => "E-mail"],
            'phone'         => ['name' => 'phone', 'data' => 'phone', "title" => "Telefone"],
            'address'       => ['name' => 'address', 'data' => 'address', "title" => "Endereço"],
            'user_type'     => ['name' => 'user_type', 'data' => 'user_type', "title" => "Tipo"]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users';
    }
}
