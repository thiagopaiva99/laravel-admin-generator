<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicUser extends Model
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    public $table = 'clinic_user';


    /**
     * Nome dos atributos que devem ser convertidos para o tipo Carbon\Carbon
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];


    /**
     * Nome dos atributos que podem ser atribuídos nos métodos de create/fill
     *
     * @var array
     */
    public $fillable = [
        'clinic_id',
        'user_id'
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
}
