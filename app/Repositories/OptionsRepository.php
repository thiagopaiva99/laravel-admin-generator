<?php

namespace App\Repositories;

use App\Models\Options;
use InfyOm\Generator\Common\BaseRepository;

class OptionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'key',
        'value'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Options::class;
    }
}
