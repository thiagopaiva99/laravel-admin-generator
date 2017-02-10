<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 24/08/16
 * Time: 16:11
 */
namespace App\DataTables;

use App\DataTables\Scopes\AppointmentDataTableScope;
use App\Models\Appointment;
use Form;
use Auth;

use \Yajra\Datatables\Datatables;
use \Illuminate\Contracts\View\Factory;


class AppointmentDataTable extends DataTable
{
    public function __construct(Datatables $datatables, Factory $viewFactory) {
        parent::__construct($datatables, $viewFactory);

        $this->addScope(new AppointmentDataTableScope());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.appointments.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $appointments = Appointment::query();
        return $this->applyScopes($appointments);
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
                    //'create',
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
            'appointment_start' => ['name' => 'appointment_start', 'data' => 'appointment_start'],
            'appointment_end' => ['name' => 'appointment_end', 'data' => 'appointment_end'],
            'patient_name' => ['name' => 'patient_name', 'data' => 'patient_name'],
            'patient_phone' => ['name' => 'patient_phone', 'data' => 'patient_phone'],
            'patient_email' => ['name' => 'patient_email', 'data' => 'patient_email'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'appointments';
    }
}