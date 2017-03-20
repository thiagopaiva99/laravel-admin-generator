<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Jennifer;
use InfyOm\Generator\Common\BaseRepository;

class JenniferRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Qui',
        'Nulla'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Jennifer::class;
    }
}
