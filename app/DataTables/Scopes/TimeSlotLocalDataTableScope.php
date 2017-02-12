<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 26/08/16
 * Time: 14:41
 */

namespace App\DataTables\Scopes;

use \Yajra\Datatables\Contracts\DataTableScopeContract;
use Auth;
use App\Models\User;

class TimeSlotLocalDataTableScope implements DataTableScopeContract {

    /**
     * @var Integer
     */
    protected $localId = 0;

    /**
     * @param $userType
     */
    public function __construct($localId) {
        $this->localId  = $localId;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where("local_id", $this->localId);
    }
}