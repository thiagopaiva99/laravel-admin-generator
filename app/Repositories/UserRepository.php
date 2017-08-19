<?php

namespace App\Repositories;

use App\Criteria\UsersCriteria;
use App\Models\User;
use InfyOm\Generator\Common\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    /**
     * Método inicializador da classe
     */
    public function boot()
    {
        parent::boot();

        $this->pushCriteria(UsersCriteria::class);
    }
}
