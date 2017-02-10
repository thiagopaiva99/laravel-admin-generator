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
class MyUsersCriteria implements CriteriaInterface
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
            $model = $model->whereRaw("id IN (SELECT user_id FROM appointments WHERE time_slot_user_id = $user->id)");
        }

        return $model;
    }
}
