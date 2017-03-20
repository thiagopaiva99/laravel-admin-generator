<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Helen;
use InfyOm\Generator\Common\BaseRepository;

class HelenRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Aperiam',
        'Ea'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Helen::class;
    }
}
