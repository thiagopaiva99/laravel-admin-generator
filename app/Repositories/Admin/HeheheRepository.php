<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Hehehe;
use InfyOm\Generator\Common\BaseRepository;

class HeheheRepository extends BaseRepository
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
        return Hehehe::class;
    }
}
