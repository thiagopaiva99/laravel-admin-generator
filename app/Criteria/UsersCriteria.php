<?php

namespace App\Criteria;

use App\Models\User;
use Auth;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class MyUsersCriteria
 * @package namespace App\Criteria;
 */
class UsersCriteria implements CriteriaInterface
{
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
        $user = Auth::user();

        $model = $model->whereRaw("id != $user->id");

        return $model;
    }
}
