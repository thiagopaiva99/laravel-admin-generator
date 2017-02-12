<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\TimeSlot;
use App\Models\Local;
use App\Models\User;
use App\Models\TimeSlotDetail;

use DB;

/**
 * @SWG\Definition(
 *      definition="Appointment",
 *      required={"appointment_start", "appointment_end"},
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
class Appointment extends Model implements \MaddHatter\LaravelFullcalendar\IdentifiableEvent
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
    public $table = 'appointments';


    /**
     * Nome dos atributos que devem ser convertidos para o tipo Carbon\Carbon
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];


    /**
     * Nome dos atributos que podem ser atribuídos nos métodos de create/fill
     *
     * @var array
     */
    public $fillable = [
        'appointment_start',
        'appointment_end'
    ];


    /**
     * Regras de validação do modelo
     *
     * @var array
     */
    public static $rules = [
        'time_slot_id' => 'required|integer',
        'time_slot_local_id' => 'required|integer',
        'time_slot_user_id' => 'required|integer',
        'user_id' => 'required|integer',
        'appointment_start' => 'required|date',
        'appointment_end' => 'required|date'
    ];


    /**
     * Nome dos atributos que devem ser escondidos na hora de transformar para JSON
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'time_slot_id',
        'time_slot_local_id',
        'time_slot_user_id',

        'time_slot',
        'time_slot_user',
        'time_slot_local',
        'user'
    ];

    /**
     * Nome dos atributos que devem ser adicionamos ao JSON
     *
     * @var array
     */
    protected $appends = [
        "title",
        "subtitle",
        "dia",
        "hora"
    ];

    /*
     * Atributos
     */

    public function getAppointmentStartAttribute() {
        $value = $this->attributes['appointment_start'];

        if (is_string($value)) {
            return Carbon::createFromFormat('Y-m-d H:i:s O', $value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            return $value;
        }

        return null;
    }

    public function setAppointmentStartAttribute($value) {
        if (is_string($value)) {
            $this->attributes['appointment_start'] = Carbon::parse($value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            $this->attributes['appointment_start'] = $value;
        }
    }

    public function getAppointmentEndAttribute() {
        $value = $this->attributes['appointment_end'];
        if (is_string($value)) {
            return Carbon::createFromFormat('Y-m-d H:i:s O', $value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            return $value;
        }

        return null;
    }

    public function setAppointmentEndAttribute($value) {
        if (is_string($value)) {
            $this->attributes['appointment_end'] = Carbon::parse($value);
        } else if (get_class($value) == 'Carbon\Carbon' || get_class($value) == 'Carbon') {
            $this->attributes['appointment_end'] = $value;
        }
    }

    public function getTitleAttribute() {
        return $this->timeSlotUser->name;
    }

    public function getSubtitleAttribute() {
        $subtitle = "";

        if(isset($this->timeSlotLocal->specializations)) {
            foreach ($this->timeSlotLocal->specializations AS $specialization) {
                if ($subtitle != "") {
                    $subtitle .= ", ";
                }
                $subtitle .= $specialization->name;
            }
        }

        return $subtitle;
    }

    public function getDiaAttribute() {
        return $this->appointment_start->format('d/m/Y');
    }

    public function getHoraAttribute() {
        return $this->appointment_start->format('H:i');
    }


    /*
     * Relacionamentos entre modelos
     */

    /**
     * Um agendamento pertence à um time slot
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    /**
     * Um agendamento pertence à um local
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeSlotLocal()
    {
        return $this->belongsTo(Local::class);
    }

    /**
     * Um agendamento pertence à um usuário médico
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeSlotUser()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Um agendamento pertence à um usuário paciente
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Um agendamento pertence à um detalhamento de horário
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeSlotDetail() {
        return $this->belongsTo(TimeSlotDetail::class);
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
        $time_slot_local_id = $this->time_slot_local_id;
        $appointment_start = $this->appointment_start;
        $appointment_end = $this->appointment_end;

//        $exists = DB::select("SELECT 1 FROM appointments WHERE appointments.time_slot_local_id = ? AND TSTZRANGE(appointment_start,appointment_end) && TSTZRANGE(?,?) AND deleted_at IS NULL", [$time_slot_local_id, $appointment_start, $appointment_end]);

        $exists = DB::select("select fn2_check_available_slot_count(?) as count", [$this->time_slot_detail_id]);

        if (is_array($exists) && sizeof($exists) > 0) {
            $count = $exists[0]->count;

            if($count == 0){
                return false;
            }
        }

        return parent::save($options);
    }

    /*
     * Métodos do calendário
     * MaddHatter\LaravelFullcalendar\Event
     */

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->patient_name;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return false;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->appointment_start;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->appointment_end;
    }

    /**
     * Get the event's ID
     *
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Optional FullCalendar.io settings for this event
     *
     * @return array
     */
    public function getEventOptions()
    {
        return [
            //etc
        ];
    }
}