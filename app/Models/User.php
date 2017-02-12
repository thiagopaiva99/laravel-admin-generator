<?php

namespace App\Models;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\CanResetPassword;

use App\Models\Specialization;
use App\Models\HealthPlan;
use App\Models\Exam;
use App\Models\ClosedDate;
use App\Models\Local;

use Auth;

/**
 * @SWG\Definition(
 *      definition="User",
 *      required={"name", "email", "user_type"},
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
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="facebook_id",
 *          description="facebook_id",
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
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_type",
 *          description="user_type",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="preferred_user",
 *          description="preferred_user",
 *          type="boolean",
 *          format="boolean"
 *      ),
 *      @SWG\Property(
 *          property="approval_status",
 *          description="approval_status",
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
class User extends Authenticatable
{
    /**
     * Tipos de usuários
     */
    const UserTypeAdmin = 1;
    const UserTypeDoctor = 2;
    const UserTypePatient = 3;
    const UserTypeClinic = 4;

    /**
     * Status de Aprovação
     */
    const ApprovalStatusPending = 1;
    const ApprovalStatusAccepted = 2;
    const ApprovalStatusDenied = 3;

    /**
     * Este modelo usa soft delete
     */
    use SoftDeletes;


    /**
     * Nome da tabela
     *
     * @var string
     */
    public $table = 'users';


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
        'name',
        'email',
        'facebook_id',
        'image_src',
        'phone',
        'address',
        'password',
        'user_type',
        'preferred_user',
        'approval_status',
        'private_health_plan',
        'user_id',
        'crm',
        'cpf'
    ];

    /**
     * Nome dos atributos que devem ser escondidos na hora de transformar para JSON
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /*
     * Atributos calculados
     */

    /**
     * Retorna o tipo de usuário
     *
     * @return string
     */
    public function getUserTypeStrAttribute() {
        return User::getUserTypeName($this->user_type);
    }

    /**
     * Retorna a url externa da imagem do usuário
     *
     * @return string
     */
    public function getImageURLAttribute() {
        if (isset($this->image_src) && $this->image_src != "") {
            return $this->image_src;
        }

        // TODO: criar um avatar para os usuários!
        return null; //url("avatar.png");
    }

    /**
     * Retorna o telefone formatado do usuário
     *
     * @return string
     */
    public function getTelefoneAttribute() {
        if(isset($this->phone) && $this->phone != "") {
            return StringHelper::phoneFormat(StringHelper::onlyNumbers($this->phone));
        }
        return "";
    }

    /**
     * Função que traduz o user_type (integer) para string
     * 
     * @param $user_type
     * @return string
     */
    static function getUserTypeName($user_type) {
        switch ($user_type) {
            case User::UserTypeAdmin:
                return "Admin";
            case User::UserTypeDoctor:
                return "Profissional";
            case User::UserTypePatient:
                return "Usuário";
            case User::UserTypeClinic:
                return "Estabelecimento";
            default:
                return "";
        }
    }

    /*
     * Relacionamentos entre modelos
     */


    /**
     * Este modelo pertence à vários planos de saúde (um paciente possui vários planos de saúde)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function healthPlans()
    {
        return $this->belongsToMany(HealthPlan::class);
    }

    /**
     * Este modelo possui vários locais (médicos possuem locais de atendimento)
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function locals()
    {
        return $this->hasMany(Local::class);
    }

    /**
     * Este modelo possui vários time slots (médicos possuem vários time slots)
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    /**
     * Este modelo possui vários agendamentos (pacientes possuem agendamentos)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class)->orderBy("appointment_start", "desc");
    }

    /**
     * Este modelo possui vários outros usuários (ex.: uma clínica possui usuários)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany(User::class, 'clinic_user', 'clinic_id');
    }

    /**
     * Este modelo possui várias clinicas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clinics() {
        return $this->belongsToMany(User::class, 'clinic_user', 'user_id', 'clinic_id');
    }

    /**
     * Este modelo possui vários closed dates (médicos possuem closed dates)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closedDates()
    {
        return $this->hasMany(ClosedDate::class);
    }

    /**
     * Este modelo possui vários locais
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clinicLocals() {
        return $this->hasMany(Local::class, "clinic_id");
    }


    /*
     * Métodos do Eloquent
     */

    /**
     * Save the model to the database.
     *
     * @param  array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // verificar se é um médico
        if ($this->user_type == User::UserTypeDoctor) {
            // verificar se esta sendo inserido um novo médico
            if (!isset($this->id)) {
                // definir o status de aprovação para "pendente"
                $this->approval_status = User::ApprovalStatusPending;
            }

            if (!isset($this->preferred_user)) {
                $this->preferred_user = false;
            }
        }

        if(Auth::check() && Auth::user()->user_type == User::UserTypeClinic){
            $this->approval_status = User::ApprovalStatusAccepted;
        }

        return parent::save($options);
    }
}
