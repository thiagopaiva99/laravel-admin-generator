<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 24/08/16
 * Time: 15:59
 */
namespace App\DataTables;

use App\DataTables\Scopes\TimeSlotDataTableScope;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Form;
use Auth;

use \Yajra\Datatables\Datatables;
use \Illuminate\Contracts\View\Factory;

class TimeSlotDataTable extends DataTable
{
    public function __construct(Datatables $datatables, Factory $viewFactory) {
        parent::__construct($datatables, $viewFactory);

        $this->addScope(new TimeSlotDataTableScope());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.timeSlots.datatables_actions')
            ->editColumn('slot_type', function($timeSlot) {
                return $timeSlot->slot_type = TimeSlot::TimeSlotDefault ? "Padrão" : "Customizado";
            })
            ->editColumn('day_of_week', function($timeSlot) {
                return TimeSlot::diaDaSemana($timeSlot->day_of_week);
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $timeSlots = TimeSlot::query();
        return $this->applyScopes($timeSlots);
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
            //'local_id' => ['name' => 'local_id', 'data' => 'local_id'],
            'slot_type' => ['name' => 'slot_type', 'data' => 'slot_type', 'title' => 'Tipo'],
            'day_of_week' => ['name' => 'day_of_week', 'data' => 'day_of_week', 'title' => 'Dia da Semana'],
            'slot_time_start' => ['name' => 'slot_time_start', 'data' => 'slot_time_start', 'title' => 'Início'],
            'slot_time_end' => ['name' => 'slot_time_end', 'data' => 'slot_time_end', 'title' => 'Fim'],
            'slot_date' => ['name' => 'slot_date', 'data' => 'slot_date', 'title' => 'Data Customizada']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'time_slots';
    }
}
