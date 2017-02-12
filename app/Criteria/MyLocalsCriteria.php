<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\User;
use Auth;

/**
 * Class MyLocalsCriteria
 * @package namespace App\Criteria;
 */
class MyLocalsCriteria implements CriteriaInterface
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
        if ($user->user_type == User::UserTypeDoctor) {
            $model = $model->where("user_id", $user->id);
            return $model;
        }

        return null;
    }
}
