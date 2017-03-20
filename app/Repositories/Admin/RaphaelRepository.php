<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Raphael;
use InfyOm\Generator\Common\BaseRepository;

class RaphaelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Quae',
        'Expedita'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Raphael::class;
    }
}
