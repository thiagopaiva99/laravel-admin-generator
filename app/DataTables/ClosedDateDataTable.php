<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 24/08/16
 * Time: 14:32
 */
namespace App\DataTables;

use App\DataTables\Scopes\ClosedDateDataTableScope;
use App\Models\ClosedDate;
use Form;
use Auth;

use \Yajra\Datatables\Datatables;
use \Illuminate\Contracts\View\Factory;


class ClosedDateDataTable extends DataTable
{
    public function __construct(Datatables $datatables, Factory $viewFactory) {
        parent::__construct($datatables, $viewFactory);

        $this->addScope(new ClosedDateDataTableScope());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.closedDates.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $closedDates = ClosedDate::query();
        return $this->applyScopes($closedDates);
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
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'start_datetime' => ['name' => 'start_datetime', 'data' => 'start_datetime'],
            'end_datetime' => ['name' => 'end_datetime', 'data' => 'end_datetime'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'closed_dates';
    }
}