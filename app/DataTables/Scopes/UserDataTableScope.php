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
        return $query;
    }
}