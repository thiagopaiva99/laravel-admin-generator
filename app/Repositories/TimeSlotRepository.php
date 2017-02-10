<?php

namespace App\Repositories;

use App\Criteria\MySlotTimesCriteria;
use App\Models\TimeSlot;
use InfyOm\Generator\Common\BaseRepository;

class TimeSlotRepository extends BaseRepository
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
        return TimeSlot::class;
    }

    /**
     * Método inicializador da classe
     */
    public function boot()
    {
        parent::boot();

        $this->pushCriteria(MySlotTimesCriteria::class);
    }
}
