<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 25/08/16
 * Time: 16:21
 */

namespace App\DataTables\Scopes;

use \Yajra\Datatables\Contracts\DataTableScopeContract;
use Auth;
use App\Models\User;

class DoctorsDataTableScope implements DataTableScopeContract {

    /**
     * @var Integer
     */

    /**
     * @param $userType
     */
    public function __construct() {
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where('user_type', 2)->join('clinic_user', function($join){
            $join->on('users.id', '=', 'clinic_user.user_id')->where('clinic_id', '=', Auth::user()->id);
        });
    }
}