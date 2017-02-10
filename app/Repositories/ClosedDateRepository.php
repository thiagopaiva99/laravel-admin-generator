<?php

namespace App\Repositories;

use App\Criteria\MyClosedDatesCriteria;
use App\Models\ClosedDate;
use InfyOm\Generator\Common\BaseRepository;

class ClosedDateRepository extends BaseRepository
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
        return ClosedDate::class;
    }

    /**
     * Método inicializador da classe
     */
    public function boot()
    {
        parent::boot();

        $this->pushCriteria(MyClosedDatesCriteria::class);
    }
}
