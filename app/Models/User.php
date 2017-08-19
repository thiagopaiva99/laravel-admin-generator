<?php

namespace App\Models;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\CanResetPassword;

use Auth;

class User extends Authenticatable
{
    /**
     * Tipos de usuários
     */
    const UserTypeAdmin = 1;

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
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];


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
                return "Administrador";
            default:
                return "";
        }
    }
}
