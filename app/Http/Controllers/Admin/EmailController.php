<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;

set_time_limit(60);

class EmailController extends Controller
{

    // por enquanto esta tudo comentado pois tem que ver o servidor de email na amazon

    /**
     * @param $user -> É o usuário para quem vai ser enviado o email
     * @param $array -> são algumas configurações para o email, [to] => se e para o admin ou para o usuario, [type] => e para ver na view qual o tipo de email é
     */
    public static function sendEmail($user, $array){
//        if($array['to'] == 'admin'){
//            Mail::send('email.password', ['user' => $user, 'type' => $array['type'], 'array' => $array], function ($m) use ($user) {
//
//                $m->from($user->email, $user->name)->sender($user->email, $user->name);
//                $m->to('contato@docsaude.com')->subject('docsaúde!');                // por enquanto vai icar meu email para podermos receber os emails xD
//
//            });
//        }else{
//            Mail::send('email.password', ['user' => $user, 'type' => $array['type'], 'array' => $array], function ($m) use ($user) {
//
//                $m->from('contato@docsaude.com', 'docsaúde')->sender('contato@docsaude.com', 'docsaúde');
//                if(isset($user->email)){
//                    $m->to($user->email)->subject('Docsaúde!');                // por enquanto vai icar meu email para podermos receber os emails xD
//                }else{
//                    $m->to($user[0]->email)->subject('Docsaúde!');                // por enquanto vai icar meu email para podermos receber os emails xD
//                }
//            });
//        }
    }

    /**
     * @param $to -> todos os usuarios que irão receber o email
     * @param $array
     */
    public static function sendMultipleEmails($to, $array){
//        Mail::send('email.password', ['type' => $array['type'], 'array' => $array], function ($m) use ($to) {
//
//            $m->from('contato@docsaude.com', 'Docsaúde')->sender('contato@docsaude.com', 'docsaúde');
//            $m->to('contato@docsaude.com')->subject('Docsaúde!');                // por enquanto vai icar meu email para podermos receber os emails xD
//            $m->cc(array_values($to->toArray()));
//
//        });
    }
}
