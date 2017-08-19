<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Teste",
 *      required={"aaaa"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="aaaa",
 *          description="aaaa",
 *          type="string"
 *      )
 * )
 */
class Teste extends Model
{
    use SoftDeletes;

    public $table = 'testes';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'aaaa'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'aaaa' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'aaaa' => 'required'
    ];
}
