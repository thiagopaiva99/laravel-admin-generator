<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Elijah;
use InfyOm\Generator\Common\BaseRepository;

class ElijahRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Saepe'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Elijah::class;
    }
}
