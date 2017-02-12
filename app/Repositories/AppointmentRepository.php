<?php

namespace App\Repositories;

use App\Criteria\MyAppointmentsCriteria;
use App\Models\Appointment;
use InfyOm\Generator\Common\BaseRepository;

class AppointmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Appointment::class;
    }

    /**
     * Método inicializador da classe
     */
    public function boot()
    {
        parent::boot();

        $this->pushCriteria(MyAppointmentsCriteria::class);
    }
}
