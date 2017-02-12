<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 24/08/16
 * Time: 11:06
 */

namespace App\DataTables\Scopes;

use \Yajra\Datatables\Contracts\DataTableScopeContract;
use Auth;
use App\Models\User;

class UserDataTableScope implements DataTableScopeContract {

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        $user = User::find(Auth::user()->id);
        if ($user->user_type == User::UserTypeDoctor) {
            $query = $query->whereRaw("id IN (SELECT user_id FROM appointments WHERE time_slot_user_id = $user->id)");
        }

        return $query;
    }
}