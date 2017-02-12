<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use Input;
use Mail;
use Flash;
use Auth;
use URL;

class SiteController extends Controller
{

    public function getLocation(){
        return response()->json(Location::get($_SERVER['REMOTE_ADDR']));
    }

    public function homepage(){

        //dd(Auth::user());

        //if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){

        $ip = $_SERVER['REMOTE_ADDR'] == '::1' ? '179.219.186.149' : $_SERVER['REMOTE_ADDR'];
        $location = Location::get($ip);

        $parameters = [
            'lat' => $location->latitude,
            'lng' => $location->longitude,
        ];

        if (Auth::check()) $parameters['id'] = Auth::user()->id;

        $request = \App\Http\Requests\API\TimelineAPIRequest::create(
            url('medicos'),
            'POST',
            $parameters
        );

        $physicians = app('App\Http\Controllers\API\TimelineAPIController')->showTimeline($request, false);
        $physicians = $physicians['locals_by_date'];

        /*
         *  Horas de início e fim de consulta
         *  Passo de 0.5
         */

        $begin = 5;
        $end = 22;

        $schedule = [];
        $hour = $begin;

        $max = ($end * 2) - ($begin * 2) + 2;
        $max = $max > 1 ? $max : 2;

        for ($i = 1; $i < $max; $i++) {

            $this_hour = (floor($hour) < 10 ? ('0'.floor($hour)) : floor($hour)) . ":" . (floor(($hour * 10)) - (10 * floor($hour)) != 0 ? "30" : "00");
            $schedule[$this_hour] = $this_hour;
            $hour = $begin + ($i * 0.5);

        }

        $labels = [
            'specializations'   => 'Especialidade',
            'health_plans'      => 'Plano de Saúde',
            'exams'             => 'Exame'
        ];

        $request = new Requests\API\HomeAPIRequest;
        $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
        $health_plans = $search_fields['health_plans'];
        $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false, true);

        return view('site.home', compact('search_fields', 'labels', 'schedule', 'physicians', 'health_plans'));
        //}else{
        //    return redirect(URL::previous());
        //}

    }



    public function returnViewContato($user_id)
    {

        if(isset($user_id) && !is_null($user_id) && $user_id != '')
        {
            return view('admin.webview.contato', compact('user_id'));
        }
        else
        {
            return view('admin.webview.contato');
        }


    }

    public function contact(){

        if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){

            $title = [
                'big' => 'Contato'
            ];

            $request = new Requests\API\HomeAPIRequest;
            $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
            $health_plans = $search_fields['health_plans'];

            return view('site.pages.contact', compact('title', 'health_plans'));
        }else{
            return redirect(URL::previous());
        }
	}

    public function emailAndContact(){

        if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){
            $input = Input::all();

            Mail::send('email.contact', ['data' => $input], function($m) use ($input){
                $m->from($input['email'], $input['name'])->sender($input['email'], $input['name']);
                $m->to('contato@docsaude.com.br', 'Dr. Saúde')->subject($input['subject']);
            });

            flash('Email enviado com sucesso para contato@drsaude.com.br!',' teal');
            return redirect('contato');
        }else{
            return redirect(URL::previous());
        }
    }

    public function download(){

        if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){

            $title = [
                'big' => 'Baixe o <strong>aplicativo</strong>'
            ];

            $request = new Requests\API\HomeAPIRequest;
            $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
            $health_plans = $search_fields['health_plans'];

            return view('site.pages.download', compact('title', 'health_plans'));
        }else{
            return redirect(URL::previous());
        }
    }

}
