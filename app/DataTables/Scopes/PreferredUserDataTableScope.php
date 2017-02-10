<?php
/**
 * Created by PhpStorm.
 * User: thiagopaiva21
 * Date: 25/08/16
 * Time: 16:21
 */

namespace App\DataTables\Scopes;

use \Yajra\Datatables\Contracts\DataTableScopeContract;
use Auth;
use App\Models\User;

class PreferredUserDataTableScope implements DataTableScopeContract {

    /**
     * @var Integer
     */
    protected $userType = false;

    /**
     * @param $userType
     */
    public function __construct($preferred) {
        $this->userType  = $preferred;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        if($this->userType == "all"){
            return $query->where('id', '!=', Auth::user()->id);
        }else{
            return $query->where("preferred_user", $this->userType);
        }
    }
}