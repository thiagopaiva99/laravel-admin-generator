<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Admin\EmailController;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API;
use Auth;
use Laracasts\Flash\Flash;
use URL;
use Input;
use Mail;
use App\Models\User;
use Intervention\Image\Facades\Image;
use Aws\S3;
use Illuminate\Support\Facades\Storage;

class SiteRegisterController extends Controller
{

    public function register(){

        if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){

            $request = new Requests\API\HomeAPIRequest;
            $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
            $health_plans = $search_fields['health_plans'];

            if (!Auth::check()) {

                $title = [
                    'big' => 'Cadastre-se ou faça <strong>login</strong>'
                ];

                return view('site.pages.register', compact('title', 'health_plans'));

            } else {

                $title = [
                    'big' => 'Meu <strong>cadastro</strong>'
                ];

                $user = User::where('id', Auth::user()->id)->with('healthPlans')->first();
                $my_health_plans = [];

                foreach ($user->healthPlans as $healthPlan) $my_health_plans[] = $healthPlan->id;

                return view('site.pages.update', compact('title', 'health_plans', 'my_health_plans'));

            }

        }else{

            return redirect(URL::previous());

        }


    }

    public function store(Requests\registerUserSITERequest $registerUserSITERequest){

        $parameters = [
            'name'      => $registerUserSITERequest->get('name'),
            'email'     => $registerUserSITERequest->get('email'),
            'password'  => $registerUserSITERequest->get('password'),
            'image_src' => 'assets/site/images/img_medicos.jpg'
        ];

        if ($registerUserSITERequest->has('private_health_plan')) {

            $parameters['private_health_plan'] = $registerUserSITERequest->get('private_health_plan') == 'private';

        }

        if ($registerUserSITERequest->has('health_plans') && !isset($parameters['private_health_plan'])) {

            $parameters['health_plans'] = $registerUserSITERequest->get('health_plans');

        }

        $request = Requests\API\UserAPIRequest::create(
            url('cadastro'),
            'POST',
            $parameters
        );

        $response = app('App\Http\Controllers\API\UserAPIController')->registerUser($request, false);

        $request = new Requests\API\HomeAPIRequest;
        $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
        $health_plans = $search_fields['health_plans'];

        if($response == 'E-mail já cadastrado no sistema.'):

            flash('Email ja cadastrado no sistema!', 'teal');
            return view('site.pages.register', compact('response', 'health_plans'));

        elseif($response == 'Usuário cadastrado com sucesso.'):

            flash('Usuário cadastrado com sucesso.', 'teal');
//            return view('site.pages.register', compact('response'));

        elseif($response == 'Houve um erro ao salvar o usuário. Por favor tente novamente.'):

            flash('Houve um erro ao salvar o usuário. Por favor tente novamente.', 'teal');
            return view('site.pages.register', compact('response', 'health_plans'));

        endif;

        if($response == 'Usuário cadastrado com sucesso.'){

            $request = Requests\API\UserLoginAPIRequest::create(
                url('api/v1/login'),
                'POST',
                [
                    'email'     => $request->get('email'),
                    'password'  => $request->get('password')
                ]
            );

            $user = app('App\Http\Controllers\API\UserAPIController')->loginUser($request, false);

            return redirect(URL::previous());
        }

    }

    public function forgotPassword(){

        if(Auth::guest()){

            $input = Input::all();
            $user  = User::where('email', $input['forgot_email'])->first();

            if ($user && isset($user->user_type) && $user->user_type == 3) {

                $password = rand(1000000000, 9999999999);
                $user->password = bcrypt($password);

                if ($user->save()) {

                    $msg = true;

                    Mail::send('email.password', ['password' => $password, 'user' => $user], function ($m) use ($user) {

                        $m->from('contato@drsaude.com.br', 'DocSaúde')->sender('contato@drsaude.com.br', 'DocSaúde');
                        $m->to($user->email, $user->name)->subject('DocSaúde - Nova senha');

                    });

                }

                $request = new Requests\API\HomeAPIRequest;
                $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
                $health_plans = $search_fields['health_plans'];

                flash('Sua nova senha foi gerada e enviada por email!', 'teal');

                return view('site.pages.register', compact('msg', 'health_plans'));

            } else {
                flash('Usuário inexistente!', 'teal');
                return redirect(URL::previous());
            }

        } else{
            return redirect(URL::previous());
        }
    }

    public function update(){

        if(Auth::check() && Auth::user()->user_type == 3){

            $user = \App\Models\User::find(Auth::user()->id);
            $input = Input::all();

            $parameters = [];

            $parameters['name'] = isset($input['name']) && $input['name'] != '' ? $input['name'] : Auth::user()->name;
            $parameters['email'] = Auth::user()->email;

            if (isset($input['password']) && $input['password'] != '') $parameters['password'] = $input['password'];
            if (isset($input['phone']) && $input['phone'] != '') $parameters['phone'] = $input['phone'];
            if (isset($input['address']) && $input['address'] != '') $parameters['address'] = $input['address'];

            if (isset($input['private_health_plan'])) {

                $parameters['private_health_plan'] = $input['private_health_plan'] == 'private';

            }

            if (isset($input['health_plans']) && $input['health_plans'] != '' && !isset($parameters['private_health_plan'])) {

                $parameters['health_plans'] = $input['health_plans'];

            }

            // upload
            if(isset($input['featured_upload'])){

                $timestamp = time();
                $extension = pathinfo($_FILES['featured_upload']['name'], PATHINFO_EXTENSION);
                $uploadfile = 'doctor_' . $timestamp . '.' . $extension;

                if(Storage::disk('s3')->put($uploadfile, fopen($_FILES['featured_upload']['tmp_name'], 'r'), 'public')){
                    $parameters['image_src'] = Storage::disk('s3')->url($uploadfile);
                }
            }

            $request = \App\Http\Requests\API\UserUpdateAPIRequest::create(
                url('cadastro'),
                'POST',
                $parameters
            );

            $response = app('App\Http\Controllers\API\UserAPIController')->updateUser($user, $request, false);

            if($response){
                flash('Seus dados foram atualizdos com sucesso!', 'teal');
                return redirect('cadastro');
            }

        } else return redirect('cadastro');

    }

    public function login(Requests\LoginUserSITERequest $request){

        $request = Requests\API\UserLoginAPIRequest::create(
            url('api/v1/login'),
            'POST',
            [
                'email'     => $request->get('email'),
                'password'  => $request->get('password')
            ]
        );

        $user = app('App\Http\Controllers\API\UserAPIController')->loginUser($request, false);
        $error = "Credenciais inválidas";

//        if($user == 'invalid credentials'){
//            flash('Usuário e/ou senha inválidos!', 'teal');
//        }

        $request = new Requests\API\HomeAPIRequest;
        $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
        $health_plans = $search_fields['health_plans'];

        if($user){

            if(Auth::user()->user_type != 3) return redirect('admin'); // Usuário não é paciente
            else return $user ? back() : view('site.pages.register', compact('error', 'health_plans'));

        }else{

            flash('Dados inválidos!', 'teal');
            return view('site.pages.register', compact('error', 'health_plans'));

        }

    }

    public function logout(){

        if(Auth::check()) Auth::logout();

        return back();

    }
    
    public function storeMedics(){

        if(Auth::guest()){

            $user  = new \App\Models\User;
            $input = Input::all();

            $user->name      = $input['name'];
            $user->email     = $input['email'];
            $user->password  = bcrypt($input['password']);
            $user->phone     = $input['phone'];
            $user->user_type = 2;

            if(User::where('email', '=', $input['email'])->get()->count() == 0){

                $type = 'new_medic';

                if($user->save()){

                    // algumas opções para a função de email
                    $options = array();
                    $options['type'] = 'new_medic';
                    $options['to'] = 'admin';

                    EmailController::sendEmail($user, $options);

                    if(Auth::attempt(['email' => $user->email, 'password' => $input['password']])){
                        return redirect('admin/calendar');
                    }else{
                        flash("Algo aconteceu e não foi possivel loga-lo no momento, tente mais tarde!", "teal");
                        return redirect(URL::previous());
                    }
                } else {
                    return redirect('/');
                }
            }else{
                flash("Este email ja esta cadastrado no sistema!", "teal");
                return redirect(URL::previous());
            }

        }else{

            return redirect('/');

        }

    }
}
