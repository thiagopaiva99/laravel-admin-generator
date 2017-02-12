<?php

namespace App\Repositories;

use App\Criteria\MyLocalsCriteria;
use App\Models\Local;
use InfyOm\Generator\Common\BaseRepository;

class LocalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Local::class;
    }

    /**
     * Método inicializador da classe
     */
    public function boot()
    {
        parent::boot();

        $this->pushCriteria(MyLocalsCriteria::class);
    }
}
