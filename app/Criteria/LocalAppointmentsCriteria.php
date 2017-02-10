<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class LocalAppointmentsCriteria
 * @package namespace App\Criteria;
 */
class LocalAppointmentsCriteria implements CriteriaInterface
{
    /**
     * id do local para filtrar os agendamentos
     *
     * @var null|integer
     */
    private $localId = null;

    /**
     * LocalAppointmentsCriteria constructor.
     * @param $localId
     */
    public function __construct($localId)
    {
        $this->localId = $localId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($this->localId) {
            $model = $model->where("time_slot_local_id", $this->localId);
        }
        return $model;
    }
}
