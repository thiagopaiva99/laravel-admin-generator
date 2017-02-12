<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Specialization",
 *      required={"name"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
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
class Specialization extends Model
{
    /**
     * Este modelo usa soft delete
     */
    use SoftDeletes;


    /**
     * Nome da tabela
     *
     * @var string
     */
    public $table = 'specializations';


    /**
     * Nome dos atributos que devem ser convertidos para o tipo Carbon\Carbon
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * Nome dos atributos que podem ser atribuídos nos métodos de create/fill
     *
     * @var array
     */
    public $fillable = [
        'name'
    ];


    /**
     * Regras de validação do modelo
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];


    /**
     * Nome dos atributos que devem ser escondidos na hora de transformar para JSON
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    /*
     * Relacionamentos entre modelos
     */

    
    /**
     * Este modelo pertence à vários locais
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function locals() {
        return $this->belongsToMany(Local::class);
    }

}
