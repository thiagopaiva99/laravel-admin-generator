<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Options;
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
