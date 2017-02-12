<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 24/08/16
 * Time: 16:14
 */
namespace App\DataTables\Scopes;

use Carbon\Carbon;
use \Yajra\Datatables\Contracts\DataTableScopeContract;
use Auth;
use App\Models\User;

class AppointmentDataTableScope implements DataTableScopeContract {

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
            $query = $query->where("time_slot_user_id", $user->id)->where("appointment_start", ">=", new Carbon());

            return $query;
        }

        return null;
    }
}