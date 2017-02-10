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

class UserTypeDataTableScope implements DataTableScopeContract {

    /**
     * @var Integer
     */
    protected $userType = 0;

    /**
     * @param $userType
     */
    public function __construct($userType) {
        $this->userType  = $userType;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where("user_type", $this->userType);
    }
}