<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Local;
use App\Models\User;

/**
 * @SWG\Definition(
 *      definition="ClosedDate",
 *      required={"start_datetime", "end_datetime"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
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
class ClosedDate extends Model implements \MaddHatter\LaravelFullcalendar\IdentifiableEvent
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
    public $table = 'closed_dates';


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
        'start_datetime',
        'end_datetime',
        'local_id',
        'user_id'
    ];

    /*
     * Attributes
     */
    public function getStartDateTimeAttribute() {
        $value = $this->attributes['start_datetime'];
        if (is_string($value)) {
            return Carbon::createFromFormat('Y-m-d H:i:s O', $value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            return $value;
        }

        return null;
    }

    public function setStartDateTimeAttribute($value) {
        if(is_string($value)) {
            $this->attributes['start_datetime'] = Carbon::parse($value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            $this->attributes['start_datetime'] = $value;
        }
    }

    public function getEndDateTimeAttribute() {
        $value = $this->attributes['end_datetime'];
        if (is_string($value)) {
            return Carbon::createFromFormat('Y-m-d H:i:s O', $value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            return $value;
        }

        return null;
    }

    public function setEndDateTimeAttribute($value) {
        if(is_string($value)) {
            $this->attributes['end_datetime'] = Carbon::parse($value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            $this->attributes['end_datetime'] = $value;
        }
    }

    /**
     * Regras de validação do modelo
     *
     * @var array
     */
    public static $rules = [
        //'user_id' => 'required',
        'local_id' => 'required',
        'start_datetime' => 'required|date',
        'end_datetime' => 'required|date'
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
     * Este modelo pertence à um usuário "médico"
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }


    /**
     * Este modelo pertence à um local
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function local() {
        return $this->belongsTo(Local::class);
    }


    /*
     * implementacao \MaddHatter\LaravelFullcalendar\IdentifiableEvent
     */

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        // TODO: Implement isAllDay() method.
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        // TODO: Implement getStart() method.
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        // TODO: Implement getEnd() method.
    }

    /**
     * Get the event's ID
     *
     * @return int|string|null
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }
}
