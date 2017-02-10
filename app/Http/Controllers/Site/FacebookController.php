<?php
namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Socialite;
use URL;
use Log;
use Flash;

class FacebookController extends Controller {
    public function __construct(){
        $this->middleware('guest');
    }

    public function getSocialAuth(Request $request){

        if(!config("services.facebook")){
            abort('404');
        }

        session(['previous_url' => URL::previous()]);

        return Socialite::driver('facebook')->redirect();


//        return redirect('https://www.facebook.com/dialog/oauth?client_id=1186499724747753&redirect_uri=http://drsaude.aioria.com.br/login/callback/facebook&scope=email,user_website,user_location,public_profile');
    }

    public function getSocialAuthCallback(Request $request){

        try {
            $user = Socialite::with('facebook')->user();
        } catch (\Exception $e) {
            return session()->get('previous_url') ? redirect(session()->get('previous_url')) : redirect('/');
        }

        if(isset($user) && $user->id != "" && $user->name != "" && $user->email != ""){

            if(User::where('email', '=', $user->email)->get()->count() > 0){

                $usuario = User::where('email', '=', $user->email)->first();

                $usuario->facebook_id = (isset($user->id)) ? $user->id : '';
                $usuario->phone       = (isset($user->phone)) ? $user->phone : '';
                $usuario->address     = (isset($user->address)) ? $user->address : '';

                $usuario->facebook_id = isset($user->id) ? $user->id : $usuario->facebook_id;
                $usuario->save();

                $request = Requests\API\UserLoginAPIRequest::create(
                    url('login'),
                    'POST',
                    [
                        'email'     => $user->email,
                        'password'  => $user->id
                    ]
                );

                $login = app('App\Http\Controllers\API\UserAPIController')->loginUser($request, false);
                $error = "Credenciais inválidas";
                $url   = session()->get('previous_url');

                $request = new Requests\API\HomeAPIRequest;
                $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
                $health_plans = $search_fields['health_plans'];

                return $login ? redirect($url) : view('site.pages.register', compact('error', 'health_plans'));

            }else{

                if(isset($user)){

                    $request = Requests\API\UserAPIRequest::create(
                        url('cadastro'),
                        'POST',
                        [
                            'name'        => $user->name,
                            'email'       => $user->email,
                            'facebook_id' => $user->id,
                            'image_src'   => $user->avatar,
                            'password'    => $user->id
                        ]
                    );

                    $response = app('App\Http\Controllers\API\UserAPIController')->registerUser($request, false);
                    $url      = session()->get('previous_url');

                    if ($response == 'Usuário cadastrado com sucesso.') {

                        $request = Requests\API\UserLoginAPIRequest::create(
                            url('login'),
                            'POST',
                            [
                                'email'     => $user->email,
                                'password'  => $user->id
                            ]
                        );

                        app('App\Http\Controllers\API\UserAPIController')->loginUser($request, false);

                    }

                    return redirect($url);

                }else{
                    return 'Erro ao cadastrar usuário pelo Facebook!';
                }
            }
        }else{
            $errorFacebook = "Você não concedeu acesso a alguma informação necessária, libere o acesso ou cadastre-se pelo formulário!";
            return view('site.pages.register', compact('errorFacebook'));
        }

    }

    public function getSDKSocialAuth(Request $request, LaravelFacebookSdk $fb) {

        return redirect($fb->getLoginUrl());

    }
    
}

