<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Local;
use Illuminate\Support\Facades\App;

/**
 * @SWG\Definition(
 *      definition="TimeSlot",
 *      required={"slot_time", "day_of_week", "slot_time_start", "slot_time_end"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slot_time",
 *          description="slot_time",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="day_of_week",
 *          description="day_of_week",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slot_date",
 *          description="slot_date",
 *          type="string",
 *          format="date"
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
class TimeSlot extends Model
{
    /**
     * Tipos de Time Slot
     */
    const TimeSlotDefault = 1;
    const TimeSlotCustom = 2;

    /**
     * Tipos de Atendimentos
     */
    const TimeSlotTypeQueue = 1;
    const TimeSlotTypeAppointment = 2;

    /**
     * Este modelo usa soft delete
     */
    use SoftDeletes;


    /**
     * Nome da tabela
     *
     * @var string
     */
    public $table = 'time_slots';


    /**
     * Nome dos atributos que devem ser convertidos para o tipo Carbon\Carbon
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'slot_date'
    ];


    /**
     * Nome dos atributos que podem ser atribuídos nos métodos de create/fill
     *
     * @var array
     */
    public $fillable = [
        'local_id',
        'user_id',
        'slot_type',
        'day_of_week',
        'slot_time_start',
        'slot_time_end',
        'slot_date',
        'queue_type'
    ];


    /**
     * Regras de validação do modelo
     *
     * @var array
     */
    public static $rules = [
        'local_id' => 'required|integer',
        'slot_type' => 'required|integer',
        'day_of_week' => 'integer',
        'slot_time_start' => 'required',
        'slot_time_end' => 'required',
        'slot_date' => 'date',

        // esses atributosd o request são para fins do detail
//        'medics_select' => '',
//        'slot_count' => '',
//        'plans_selected' => '',
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

    public static function diaDaSemana($weekDay) {
        switch($weekDay) {
            case Carbon::MONDAY:
                return "Segunda-feira";
            case Carbon::TUESDAY:
                return "Terça-feira";
            case Carbon::WEDNESDAY:
                return "Quarta-feira";
            case Carbon::THURSDAY:
                return "Quinta-feira";
            case Carbon::FRIDAY:
                return "Sexta-feira";
            case Carbon::SATURDAY:
                return "Sábado";
            case Carbon::SUNDAY:
                return "Domingo";
            default:
                return "";
        }
    }


    /*
     * Attributes
     */

    public function getSlotTimeStartAttribute() {
        $value = $this->attributes['slot_time_start'];
        if (is_string($value)) {
            return Carbon::createFromFormat('H:i:s O', $value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            return $value;
        }

        return null;
    }

    public function setSlotTimeStartAttribute($value) {
        if(is_string($value)) {
            $this->attributes['slot_time_start'] = Carbon::parse($value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            $this->attributes['slot_time_start'] = $value;
        }
    }

    public function getSlotTimeEndAttribute() {
        $value = $this->attributes['slot_time_end'];
        if (is_string($value)) {
            return Carbon::createFromFormat('H:i:s O', $value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            return $value;
        }

        return null;
    }

    public function setSlotTimeEndAttribute($value) {
        if(is_string($value)) {
            $this->attributes['slot_time_end'] = Carbon::parse($value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            $this->attributes['slot_time_end'] = $value;
        }
    }



    /*
     * Relacionamentos entre modelos
     */


    /**
     * Este modelo pertence à um local
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function local() {
        return $this->belongsTo(Local::class);
    }


    /**
     * Este modelo pertence à um usuário "médico"
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users() {
        return $this->belongsTo(User::class);
    }


    /**
     * Este modelo possui vários agendamentos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Este modelo possui vários TimeSlotDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeSlotDetails() {
        return $this->hasMany(TimeSlotDetail::class);
    }


    /*
     * Override dos métodos já existentes na classe
     *
     */

    /**
     * Verifica se a consulta não é "conflitante" e salva no banco de dados
     *
     * @param  array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $slot_time_start = $this->slot_time_start;
        $slot_time_end = $this->slot_time_end;
        $user_id = $this->user_id;

        if ($this->slot_type == TimeSlot::TimeSlotDefault) {
            $day_of_week = $this->day_of_week;

            $id = $this->id;

            if(isset($id) && $id > 0) {
                $exists = \DB::select("SELECT 1 FROM time_slots WHERE id != ? AND user_id = ? AND slot_type = ? AND day_of_week = ? AND numrange(CAST(EXTRACT(EPOCH FROM slot_time_start) AS NUMERIC), CAST(EXTRACT(EPOCH FROM slot_time_end) AS NUMERIC)) && numrange(CAST(EXTRACT(EPOCH FROM CAST(? AS TIME WITH TIME ZONE)) AS NUMERIC), CAST(EXTRACT(EPOCH FROM CAST(? AS TIME WITH TIME ZONE)) AS NUMERIC)) and deleted_at is null LIMIT 1",
                    [$id, $user_id, TimeSlot::TimeSlotDefault, $day_of_week, $slot_time_start, $slot_time_end]
                );
            } else {
                $exists = \DB::select("SELECT 1 FROM time_slots WHERE user_id = ? AND slot_type = ? AND day_of_week = ? AND numrange(CAST(EXTRACT(EPOCH FROM slot_time_start) AS NUMERIC), CAST(EXTRACT(EPOCH FROM slot_time_end) AS NUMERIC)) && numrange(CAST(EXTRACT(EPOCH FROM CAST(? AS TIME WITH TIME ZONE)) AS NUMERIC), CAST(EXTRACT(EPOCH FROM CAST(? AS TIME WITH TIME ZONE)) AS NUMERIC)) and deleted_at is null LIMIT 1",
                    [$user_id, TimeSlot::TimeSlotDefault, $day_of_week, $slot_time_start, $slot_time_end]
                );
            }

            if (is_array($exists) && sizeof($exists) > 0) {
                return false;
            }
        } else if ($this->slot_type == TimeSlot::TimeSlotCustom) {
            // TODO: verificar!
//            $slot_date = $this->slot_date;
//
//            $start = $slot_date + start_time;
//
//            $end = $slot_date + end_time;
//
//            $exists = \DB::select("
//                SELECT 1
//                FROM time_slots
//                WHERE
//                  user_id = ? AND
//                  slot_type = ? AND
//                  TSRANGE(slot_date + slot_time_start, slot_date + slot_time_end) && TSRANGE(?, ?)
//                LIMIT 1", [
//                $user_id,
//                TimeSlot::TimeSlotCustom,
//                $start,
//                $end
//            ]);
//
//            if (is_array($exists) && sizeof($exists) > 0) {
//                return false;
//            }
        }

        return parent::save($options);
    }
}
