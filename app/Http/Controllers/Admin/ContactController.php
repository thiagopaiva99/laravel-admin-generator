<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Mail;
use URL;
use App\Models\User;

class ContactController extends Controller
{
    public function sendMail(Request $request)
    {

        if($request->user == 0){
            $mensagem = "";

            if($request->motivo == 1){
                $mensagem = "Foram encontrados bugs no aplicativo - Mensagem de usuário anônimo - ".$request->coment;
            }else if($request->motivo == 2){
                $mensagem = "Foram feitas reclamações via contato - Mensagem de usuário anônimo - ".$request->coment;
            }else if($request->motivo == 3){
                $mensagem = "Foram solicitadas informações sobre o aplicativo - Mensagem de usuário anônimo - ".$request->coment;
            }else{
                $mensagem = "Motivo não informado";
            }

            Mail::send('email.contact-app', ['mensagem' => $mensagem], function ($m) /*use ($user)*/ {

                $m->from('user@anonymous.com.br', 'embelezzô')->sender('user@anonymous.com.br', 'Embelezzô');
                $m->to('contato@embelezzo.com', 'embelezzô')->subject('Embelezzô - Contato');

            });
        }else{
            $mensagem = "";

            $user = User::find($request->user);

            if($request->motivo == 1){
                $mensagem = "Foram encontrados bugs no aplicativo - ".$request->coment;
            }else if($request->motivo == 2){
                $mensagem = "Foram feitas reclamações via contato - ".$request->coment;
            }else if($request->motivo == 3){
                $mensagem = "Foram solicitadas informações sobre o aplicativo - ".$request->coment;
            }else{
                $mensagem = "Motivo não informado";
            }

            Mail::send('email.contact-app', ['mensagem' => $mensagem], function ($m) use ($user) {

                $m->from($user->email, $user->name)->sender($user->email, $user->name);
                $m->to('contato@embelezzô.com', 'Embelezzô')->subject('Embelezzô- Contato');

            });
        }
    }
}
