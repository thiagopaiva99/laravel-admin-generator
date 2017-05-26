<?php

namespace App\DataTables\Admin;

use App\DataTables\DataTable as DataTable;
use App\Models\Admin\Menu;
use \Illuminate\Contracts\View\Factory;

class MenuDataTable extends DataTable
{
    // para traduzir tem q ter o construtor
    public function __construct(\Yajra\Datatables\Datatables $datatables, Factory $viewFactory) {
        parent::__construct($datatables, $viewFactory);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.menus.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $menus = Menu::query();

        return $this->applyScopes($menus);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'language' => $this->languageOptions,
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'buttons' => [
                    'create',
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
                             'pdf',
                         ],
                    ]
                ]
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
            'menu' => ['name' => 'menu', 'data' => 'menu'],
            'icon' => ['name' => 'icon', 'data' => 'icon'],
            'active' => ['name' => 'active', 'data' => 'active'],
            'menu_root' => ['name' => 'menu_root', 'data' => 'menu_root'],
            'appears_to' => ['name' => 'appears_to', 'data' => 'appears_to'],
            'order' => ['name' => 'order', 'data' => 'order']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'menus';
    }
}
