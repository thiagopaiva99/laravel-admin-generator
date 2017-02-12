<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Specialization;
use App\Models\HealthPlan;
use App\Models\Exam;
use App\Models\ClosedDate;

use DB;

/**
 * PostGIS
 */
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;


/**
 * @SWG\Definition(
 *      definition="Local",
 *      required={"name", "address", "appointment_duration_in_minutes"},
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
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image_src",
 *          description="image_src",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
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
class Local extends Model
{
    /**
     * Este modelo usa soft delete
     */
    use SoftDeletes;


    /**
     * Este modelo usa PostGis
     */
    use PostgisTrait;

    /**
     * Nome da tabela
     *
     * @var string
     */
    public $table = 'locals';


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
        'name',
        'address',
        'image_src',
        'phone',
        'amount',
        'appointment_duration_in_minutes',
        'location',
        'user_id',
        'address_complement',
        'clinic_id'
    ];


    /**
     * Regras de validação do modelo
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string',
        'address' => 'required|string',
        'amount' => 'required|numeric',
        'appointment_duration_in_minutes' => 'required|integer',
        'address_complement' => 'string'
    ];


    /**
     * Nome dos atributos que devem ser escondidos na hora de converter para JSON
     *
     * @var array
     */
    protected $hidden = [
        "image_src",
        "amount",
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Nome dos atributos que devem ser convertidos para PostGis
     *
     * @var array
     */
    protected $postgisFields = [
        'location' => Point::class
    ];

    /**
     * Nome dos atributos que devem ser adicionados na hora de converter para JSON
     *
     * @var array
     */
    protected $appends = [
        "image_url",
        "amount_str"
    ];


    /**
     * Formatador de Endereço
     *
     * @param Local $local
     * @return string
     */
    public static function formatAddress(Local $local)
    {
        if ($local != null) {
            if (isset($local->attributes['address']) && $local->attributes['address'] != "") {
                $endereco = $local->attributes['address'];
                if (isset($local->attributes['address_complement']) && $local->attributes['address_complement'] != "") {
                    $endereco .= "\n" . $local->attributes['address_complement'];
                }
                return $endereco;
            }
        }
        return "";
    }

    /**
     * Atributo do endereço com o complemento
     *
     * @return null|string
     */
    public function getAddressAttribute() {
        if (strpos(\Request::fullUrl(), 'api/v1') !== false) {
            return Local::formatAddress($this);
        }
        return $this->attributes['address'];
        //$this->address, $this->address_complement);
    }

    /**
     * Atributo da URL externa da Imagem do local
     *
     * @return null|string
     */
    public function getImageURLAttribute() {
        if (!empty($this->user)) {
            return $this->user->image_url;
        } else {
            return null;
        }
    }

    /**
     * Atributo do valor da consulta formatado
     *
     * @return null|string
     */
    public function getAmountStrAttribute() {
        if ($this->amount) {
            // money_format()
            return "R$ " . number_format($this->amount, 2, ',', '.');
        }
        return null;
    }

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
     * Este modelo possui vários closed dates
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function closedDates() {
        return $this->hasMany(ClosedDate::class);
    }


    /**
     * Este modelo possui vários time slots
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeSlots() {
        return $this->hasMany(TimeSlot::class);
    }


    /**
     * Este modelo pertence à vários exames
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exams() {
        return $this->belongsToMany(Exam::class);
    }


    /**
     * Este modelo pertence à várias especializações
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function specializations() {
        return $this->belongsToMany(Specialization::class);
    }


    /**
     * Este modelo pertence à vários planos de saúde
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function healthPlans() {
        return $this->belongsToMany(HealthPlan::class);
    }
    
    /*
     * SCOPES
     */


    /**
     * Função que formata os locais para o formato da timeline
     *
     * @param $query
     * @param $lat
     * @param $lng
     * @param $user_id
     * @param int $maxDistanceInMeters
     * @return $this
     */
    public function scopeTimeline($query, $lat, $lng, $user_id, $maxDistanceInMeters = 9999999, $forDate, $minDate, $maxDate) {
        $dateSQL = "";

        if($forDate == null) {
            if($minDate != null && $maxDate != null) {
                $dateSQL = "WHERE to_timestamp(time_epoch)::DATE BETWEEN '".$minDate->format('Y-m-d')."' AND '".$maxDate->format('Y-m-d')."'";
            } else if ($minDate != null) {
                $dateSQL = "WHERE to_timestamp(time_epoch)::DATE >= '".$minDate->format('Y-m-d')."'";
            } else if ($maxDate != null) {
                $dateSQL = "WHERE to_timestamp(time_epoch)::DATE <= '".$maxDate->format('Y-m-d')."'";
            }
        } else {
            $dateSQL = "WHERE to_timestamp(time_epoch)::DATE = '".$forDate->format('Y-m-d')."'";
        }

        DB::statement("SET LC_TIME = 'pt_BR.UTF8';");
        return $query
            ->leftJoin("specializations", "specializations.id", "IN", DB::raw("(SELECT local_specialization.specialization_id FROM local_specialization WHERE local_specialization.local_id = locals.id)"))
            ->crossJoin(DB::raw("(SELECT ST_GeomFromText('POINT($lng $lat)', 4326) ) AS t(x)"))
            ->join("users", "locals.user_id", "=", "users.id")
            ->join(DB::raw("LATERAL (SELECT * FROM fn2_next_availables_appointments_for_user($user_id, locals.id, 1) " . $dateSQL . " LIMIT 1) next_appointment"), "next_appointment.time_slot_id", ">", DB::raw("0"))
            ->select(
                "locals.id AS id",
                "users.name AS title",
                "users.preferred_user",
                "locals.user_id",
                DB::raw("string_agg(specializations.name, ', ') AS subtitle"),
                DB::raw("CAST(round(CAST((ST_Distance(t.x, location)/1000.0) AS NUMERIC),1) || ' Km' AS VARCHAR(20)) AS distance_str"),
                DB::raw("next_appointment.time_header AS header_title"),
                DB::raw("SUBSTR(next_appointment.time_label,1,5) AS time_str"),
                DB::raw("next_appointment.time_slot_for_queue AS queue")
            )
            ->whereRaw("ST_Distance(t.x, location) < $maxDistanceInMeters")
            ->where("users.approval_status", User::ApprovalStatusAccepted)
            ->groupBy("locals.id", "locals.user_id", "users.name", "t.x", "users.preferred_user","next_appointment.time_header", "next_appointment.time_label", "next_appointment.time_epoch", "next_appointment.time_slot_for_queue")
            ->orderBy("users.preferred_user", "desc")
            ->orderBy("next_appointment.time_epoch");
    }

    /**
     * Função que filtra os locais para que possuem as especializações especificadas
     *
     * @param $query
     * @param $specializations
     * @return mixed
     */
    public function scopeSpecializationsIn($query, $specializations) {
        return $query->whereIn("specializations.id", $specializations);
    }

    /**
     * Função que filtra os locais para que possuem os exames especificados
     *
     * @param $query
     * @param $exams
     * @return mixed
     */
    public function scopeExamsIn($query, $exams) {
        $ids = implode(",", $exams);
        return $query->whereRaw("locals.id IN (SELECT exam_local.local_id FROM exam_local WHERE exam_local.exam_id IN ($ids))");
    }

    /**
     * Função que filtra os locais para que possuem os planos de saúde especificados
     *
     * @param $query
     * @param $healthPlans
     * @return mixed
     */
    public function scopeHealthPlansIn($query, $healthPlans) {
        $ids = implode(",", $healthPlans);
        return $query->whereRaw("locals.id IN (SELECT health_plan_local.local_id FROM health_plan_local WHERE health_plan_local.health_plan_id IN ($ids))");
    }

    /**
     * Função que filtra os locais pelo valor de consulta máximo especificado
     *
     * @param $query
     * @param $maxAmount
     * @return mixed
     */
    public function scopeMaxAmount($query, $maxAmount) {
        return $query->whereRaw("(locals.amount IS NULL OR locals.amount <= $maxAmount)");
    }

    /**
     * Função que filtra os locais pelo termo (string)
     *
     * @param $query
     * @param $term
     * @return mixed
     */
    public function scopeSearchForTerms($query, $term) {
        return $query->whereRaw("(
            users.name ~* '$term' OR
            locals.id IN (SELECT exam_local.local_id FROM exam_local WHERE exam_local.exam_id IN (SELECT exams.id FROM exams WHERE exams.name ~* '$term')) OR
            locals.id IN (SELECT health_plan_local.local_id FROM health_plan_local WHERE health_plan_local.health_plan_id IN (SELECT health_plans.id FROM health_plans WHERE health_plans.name ~* '$term')) OR
            locals.name ~* '$term' OR
            locals.address ~* '$term' OR
            specializations.name ~* '$term')");
    }
}
