<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Pages;
use InfyOm\Generator\Common\BaseRepository;

class PagesRepository extends BaseRepository
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
        return Pages::class;
    }
}
