<?php

namespace App\Repositories;

use App\Models\HealthPlan;
use InfyOm\Generator\Common\BaseRepository;

class HealthPlanRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'health_plan_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return HealthPlan::class;
    }
}
