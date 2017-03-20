<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Menu",
 *      required={"menu", "icon", "active", "appears_to"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="menu",
 *          description="menu",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="icon",
 *          description="icon",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="active",
 *          description="active",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="menu_root",
 *          description="menu_root",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="appears_to",
 *          description="appears_to",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="order",
 *          description="order",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Menu extends Model
{
    use SoftDeletes;

    public $table = 'menus';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'menu',
        'icon',
        'active',
        'menu_root',
        'appears_to',
        'order',
        'link_to'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'menu' => 'string',
        'icon' => 'string',
        'active' => 'string',
        'menu_root' => 'integer',
        'appears_to' => 'integer',
        'order' => 'integer',
        'link_to' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'menu' => 'required',
        'icon' => 'required',
        'active' => 'required',
        'appears_to' => 'required',
        'link_to' => 'required'
    ];
}
