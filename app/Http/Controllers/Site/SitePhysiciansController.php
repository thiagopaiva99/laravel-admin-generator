<?php

namespace App\Http\Controllers\Site;

use App\Models\Local;
use App\Models\Site\SitePhysician;
use Guzzle\Http\Message\Response;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Stevebauman\Location\Facades\Location;
use Auth;
use URL;


class SitePhysiciansController extends Controller
{
    public function index() {

        if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){

            $title = [
                'big' => 'Seja <strong>bem-vindo</strong> à nossa <strong>equipe de especialistas</strong>',
                'small' => "Médicos do docsaúde"
            ];

            $ip = $_SERVER['REMOTE_ADDR'] == '::1' ? '179.219.186.149' : $_SERVER['REMOTE_ADDR'];
            $location = Location::get($ip);

            $parameters = [
                'lat' => $location->latitude,
                'lng' => $location->longitude,
            ];

            if (Auth::check()) $parameters['id'] = Auth::user()->id;

            $request = Requests\API\TimelineAPIRequest::create(
                url('medicos'),
                'POST',
                $parameters
            );

            $physicians = app('App\Http\Controllers\API\TimelineAPIController')->showTimeline($request, false);
            $physicians = $physicians['locals_by_date'];

            $variables = $this->setPhysiciansViewVariables($physicians);
            $week = $variables['week'];
            $last_day = $variables['last_day'];
            $active = $variables['active'];
            $date = $variables['date'];

            $request = new Requests\API\HomeAPIRequest;
            $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
            $health_plans = $search_fields['health_plans'];

            return view('site.pages.physicians', compact('physicians', 'week', 'last_day', 'active', 'title', 'date', 'health_plans'));
        }else{
            return redirect(URL::previous());
        }

    }
    
    public function show($physician_id) {

        if((Auth::check() && Auth::user()->user_type == 3)){

            $local = Local::find($physician_id);
            $physician = app('App\Http\Controllers\API\PlaceAPIController')->showPlace($local, false);

            $title = [
                'big' => $physician['place']->user->name,
                'small' => $physician['place']->name
            ];

            $button_class = \Auth::check() ? "dark-btn" : "call-popup popup1";
            $button_href = \Auth::check() ?
                url('agendar/'.
                    $physician['place']->id."/".
                    $physician['place']->next_available_appointment_id."/".
                    $physician['place']->next_available_appointment_epoch."/".
                    $physician['place']->next_available_appointment_detail_id)
                : "#";
            $map = 'https://www.google.com/maps/place/' . urlencode($physician['place']->name) . '/@' .
                $physician['place']->location->getLat() . ',' . $physician['place']->location->getLng() . ',15z';

            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');

            $request = new Requests\API\HomeAPIRequest;
            $next = app('App\Http\Controllers\API\PlaceAPIController')->showAvailableAppointments($local, $request, false);

            $request = new Requests\API\HomeAPIRequest;
            $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
            $health_plans = $search_fields['health_plans'];

            return view('site.pages.singlephysician', compact('physician', 'button_class', 'button_href', 'title', 'map', 'next', 'health_plans'));

        }else{

            return redirect('medicos');

        }

    }
    
    public function search() {

        if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){

            $title = [
                'big' => 'Seja <strong>bem-vindo</strong> à nossa <strong>equipe de especialistas</strong>',
                'small' => "Médicos do Docsaúde"
            ];

            $request = new Requests\API\HomeAPIRequest;
            $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
            $health_plans = $search_fields['health_plans'];

            if (Auth::guest()) return view('site.pages.register', compact('title', 'health_plans'));

            $labels = $this->getSelectLabelsValues();

            return view('site.pages.search', compact('title', 'search_fields', 'labels', 'health_plans'));

        }else{

            return redirect(URL::previous());

        }

    }
    
    public function results() {

        if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){

            $title = [
                'big' => 'Seja <strong>bem-vindo</strong> à nossa <strong>equipe de especialistas</strong>',
                'small' => "Médicos do Doc. Saúde"
            ];

            $request = new Requests\API\HomeAPIRequest;
            $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
            $health_plans = $search_fields['health_plans'];

            if (Auth::guest()) return view('site.pages.register', compact('title', 'health_plans'));

            $old = Input::all();

            $ip = $_SERVER['REMOTE_ADDR'] == '::1' ? '179.219.186.149' : $_SERVER['REMOTE_ADDR'];
            $location = Location::get($ip);

            $parameters = [
                'lat' => $location->latitude,
                'lng' => $location->longitude
            ];

            $parameters['id'] = Auth::check() ? Auth::user()->id : 111;

            if (isset($old['term']) && $old['term']) $parameters['term'] = $old['term'];
            if (isset($old['distance']) && $old['distance']) $parameters['distance'] = $old['distance'] * 1000;
            if (isset($old['max_amount']) && $old['max_amount']) $parameters['max_amount'] = $old['max_amount'];

            if (isset($old['min_date']) && $old['min_date']){
                $min_date = explode('/', $old['min_date']);
                $parameters['min_date'] = strtotime($min_date[1].'/'.$min_date[0]);
            }

            if (isset($old['max_date']) && $old['max_date']){
                $max_date = explode('/', $old['max_date']);
                $parameters['max_date'] = strtotime($max_date[1].'/'.$max_date[0]);
            }

            if (isset($old['min_date_day']) && $old['min_date_day']) {

                $min_date_day = explode('/', $old['min_date_day']);

                if (isset($old['min_date_time']) && $old['min_date_time']){

                    $min_date_time = explode(':', $old['min_date_time']);

                    $parameters['min_date'] = strtotime($min_date_day[1].'/'.$min_date_day[0].'/'.$min_date_day[2]." ".$min_date_time[0].':'.$min_date_time[1]);
                }else{
                    $parameters['min_date'] = strtotime($min_date_day[1].'/'.$min_date_day[0].'/'.$min_date_day[2]);
                }

                $old['min_date'] = $old['min_date_day'];

            } else if (isset($old['min_date_time']) && $old['min_date_time']) {

                $parameters['min_date'] = strtotime('today '.$old['min_date']);

                $old['min_date'] = date("d/m/Y", $parameters['min_date']);

            }

            if (isset($old['specializations']) && $old['specializations']) $parameters['specializations'] = [$old['specializations']];
            if (isset($old['health_plans']) && $old['health_plans']) $parameters['health_plans'] = [$old['health_plans']];
            if (isset($old['exams']) && $old['exams']) $parameters['exams'] = [$old['exams']];

            $request = Requests\API\TimelineAPIRequest::create(
                url('medicos'),
                'GET',
                $parameters
            );

            $physicians_php = app('App\Http\Controllers\API\TimelineAPIController')->showTimeline($request, false);
            $physicians_php = $physicians_php['locals_by_date'];

            $min_date = isset($parameters['min_date']) ? $parameters['min_date'] : false;
            $max_date = isset($parameters['max_date']) ? $parameters['max_date'] : false;

            $variables = $this->setPhysiciansViewVariables($physicians_php, $min_date, $max_date);
            $week = $variables['week'];
            $last_day = $variables['last_day'];
            $active = $variables['active'];
            $date = $variables['date'];

            $request = new Requests\API\HomeAPIRequest;
            $search_fields = app('App\Http\Controllers\API\HomeAPIController')->showHome($request, false);
            $health_plans = $search_fields['health_plans'];

            if (Auth::guest()) return view('site.pages.register', compact('title', 'health_plans'));

            $labels = $this->getSelectLabelsValues();

            return view('site.pages.results', compact('physicians_php', 'week', 'last_day', 'active', 'title', 'date', 'search_fields', 'labels', 'old', 'health_plans'));

        }else{

            return redirect(URL::previous());

        }


    }

    public function getConsultations(Request $r){

        if((Auth::check() && Auth::user()->user_type == 3) || Auth::guest()){

            $ip = $_SERVER['REMOTE_ADDR'] == '::1' ? '179.219.186.149' : $_SERVER['REMOTE_ADDR'];
            $location = Location::get($ip);

            $time = explode('/', rawurldecode ($r->data));
            $search_date_begin = max(strtotime($time[1]."/".$time[0]), time());
            $search_date_end = strtotime($time[1]."/".$time[0] . ' + 1 day') - 1;

            $limit = 80;

            $parameters = [
                'lat' => $location->latitude,
                'lng' => $location->longitude,
                'limit' => $limit,
                'min_date' => $search_date_begin,
                'max_date' => $search_date_end
            ];

            if (Auth::check()) $parameters['id'] = Auth::user()->id;

            $request = Requests\API\TimelineAPIRequest::create(
                url('timeline'),
                'POST',
                $parameters
            );

            $physicians = app('App\Http\Controllers\API\TimelineAPIController')->showTimeline($request, false);

            return [
                'physicians' => $physicians,
                'auth' => Auth::check(),
                'private' => Auth::check() && Auth::user()->private_health_plan
            ];

        }else{

            return redirect(URL::previous());

        }

    }

    private function setPhysiciansViewVariables($physicians, $min_date = false, $max_date = false) {

        $week = [];
        $date = [];

        $last_day = 'SEMANA';
        $limit = 49;

        if ($max_date) {

            if ($min_date) $limit = ceil(($max_date - $min_date) / (60 * 60 * 24)) + 1;
            else $limit = ceil(($max_date - strtotime('now')) / (60 * 60 * 24)) + 1;

        }

        if ($limit < 0) $limit = 49;

        if (sizeof($physicians) > 0) {

            $active = explode(", ", $physicians[0]->header_title);
            $active = sizeof($active) > 0 ? $active[1] : 'SEGUNDA';

            if ($min_date) $active = [0 => date('m/d', $min_date), 1 => 'tf'];

            for ($i = 0; $i < $limit; $i++) {

                $key = strtolower(date("D", strtotime(((sizeof($active) > 0 ? $active[0] : 'today') . ' + ' . $i . ' day'))));
                $value = date("d/m", strtotime(((sizeof($active) > 0 ? $active[0] : 'today') . ' + ' . $i . ' day')));

                if ($key == 'sun') $week[$key.'-'.str_replace("/", "", $value)] = 'DOMINGO';
                if ($key == 'mon') $week[$key.'-'.str_replace("/", "", $value)] = 'SEGUNDA';
                if ($key == 'tue') $week[$key.'-'.str_replace("/", "", $value)] = 'TERçA';
                if ($key == 'wed') $week[$key.'-'.str_replace("/", "", $value)] = 'QUARTA';
                if ($key == 'thu') $week[$key.'-'.str_replace("/", "", $value)] = 'QUINTA';
                if ($key == 'fri') $week[$key.'-'.str_replace("/", "", $value)] = 'SEXTA';
                if ($key == 'sat') $week[$key.'-'.str_replace("/", "", $value)] = 'SáBADO';

                $date[$key.'-'.str_replace("/","",$value)] = $value;

            }

        } else {

            $active = 'SEGUNDA';

            for ($i = 0; $i < $limit; $i++) {

                $key = strtolower(date("D", strtotime('today + ' . $i . ' day')));
                $value = date("d/m", strtotime('today + ' . $i . ' day'));

                if ($key == 'sun') $week[$key.'-'.str_replace("/", "", $value)] = 'DOMINGO';
                if ($key == 'mon') $week[$key.'-'.str_replace("/", "", $value)] = 'SEGUNDA';
                if ($key == 'tue') $week[$key.'-'.str_replace("/", "", $value)] = 'TERçA';
                if ($key == 'wed') $week[$key.'-'.str_replace("/", "", $value)] = 'QUARTA';
                if ($key == 'thu') $week[$key.'-'.str_replace("/", "", $value)] = 'QUINTA';
                if ($key == 'fri') $week[$key.'-'.str_replace("/", "", $value)] = 'SEXTA';
                if ($key == 'sat') $week[$key.'-'.str_replace("/", "", $value)] = 'SáBADO';

                $date[$key.'-'.str_replace("/","",$value)] = $value;

            }

        }

        $active = array_search($active, $week);

        return [
            'week'      => $week,
            'last_day'  => $last_day,
            'active'    => $active,
            'date'      => $date
        ];

    }
    
    private function getSelectLabelsValues() {
        
        return [
            'specializations'   => 'Especialização:',
            'health_plans'      => 'Plano de Saúde:',
            'exams'             => 'Exame:'
        ];
    
    }
    
}
