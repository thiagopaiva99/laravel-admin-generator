<?php

namespace App\Repositories;

use App\Models\Exam;
use InfyOm\Generator\Common\BaseRepository;

class ExamRepository extends BaseRepository
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
        return Exam::class;
    }
}
