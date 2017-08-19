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
        if( !config("services.facebook") ) abort('404');
        session(['previous_url' => URL::previous()]);
        return Socialite::driver('facebook')->redirect();
    }

    public function getSocialAuthCallback(Request $request){
        try{
            $user = Socialite::with('facebook')->user();
        }catch(\Exception $e) {
            return session()->get('previous_url') ? redirect(session()->get('previous_url')) : redirect('/');
        }

        // return with your own action
        return;
    }

    public function getSDKSocialAuth(Request $request, LaravelFacebookSdk $fb) {
        return redirect($fb->getLoginUrl());
    }}

