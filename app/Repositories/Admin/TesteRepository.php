<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Teste;
use InfyOm\Generator\Common\BaseRepository;

class TesteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'aaaa'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Teste::class;
    }
}
