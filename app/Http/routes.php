<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
 *  ********************************************************
 *  *     PÁGINA PRINCIPAL - HOMEPAGE - SITE APLICAÇÃO     *
 *  ********************************************************
 */

Route::get('/', 'Site\SiteController@homepage');
Route::get('/home', 'Site\SiteController@homepage');
Route::get('contato-app/{user_id}', 'Site\SiteController@returnViewContato');
Route::post('contato-app', 'Admin\ContactController@sendMail');



/*
 * **********************************************************************************************
 * *      AS ROTAS DA API ESTAO EM OUTRO ARQUIVO E NAO DEVEM SER ADICIONADAS NO routes.php      *
 * **********************************************************************************************
 */


/*
|--------------------------------------------------------------------------
| Painel Administrativo
|--------------------------------------------------------------------------
*/

Route::group(["prefix" => "admin", 'namespace' => 'Admin'],function() {
    Route::group(["middleware" => "auth"], function() {

        Route::get("/", function () {
            if(Auth::user()->user_type == \App\Models\User::UserTypeDoctor) {
                return redirect("admin/calendar");
            } else if(Auth::user()->user_type == \App\Models\User::UserTypeClinic) {
                return redirect("admin/clinic");
            } else {
                if(Auth::user()->user_type == \App\Models\User::UserTypeAdmin){
                    return redirect("admin/home");
                }else{
                    return redirect('/home');
                }
            }
        });

        // ADMIN E MEDICO
        Route::group(['middleware' => ['auth.admin']], function(){
            Route::get('home', 'HomeController@index');
            Route::get('get-doctors-by-clinic', 'UserController@getDoctorsByClinic');
        });

        // ADMIN MEDICO E CLINICA
        Route::group(['middleware' => ['auth.adminDoctorOrClinic']], function(){
            Route::resource('users', 'UserController');
            Route::get('relatorios', 'UserController@getIndexReport');
            Route::post('reports', 'UserController@getReport');;
        });

        // ADMIN
        Route::group(["middleware" => "auth.admin"], function() {
            Route::resource('healthPlans', 'HealthPlanController');
            Route::resource('specializations', 'SpecializationController');
            Route::resource('exams', 'ExamController');
        });

        // MEDICO E CLINICA
        Route::group(["middleware" => "auth.doctorOrClinic"], function(){
            Route::resource('appointments', 'AppointmentController');
            Route::get('appointments-list', 'AppointmentController@getViewCalendar');
            Route::get('appointments', ['uses' => 'AppointmentController@index', 'as' => 'admin.appointments.index']);
            Route::get('appointments/{appointments}', ['uses' => 'AppointmentController@show', 'as' => 'admin.appointments.show']);
            Route::resource('timeSlots', 'TimeSlotController');
            Route::resource('locals', 'LocalController');
            Route::get('get-doctors/{id}', 'UserController@getDoctors');
            Route::get("closedDates/deletar/{id}", "ClosedDateController@deletar");
            // essa rota esta com '-' por causa que estava tendo interferencia com outra rota, mas td bem :D
            Route::get("time-slots/inserir", "TimeSlotController@inserir");
            Route::post('closedDates/inserir', 'ClosedDateController@inserir');
            Route::resource('closedDates', 'ClosedDateController');
            Route::get('admin/get-time-slot/{id}', 'TimeSlotController@getTimeSlot');
        });

        // MEDICO
        Route::group(["middleware" => "auth.doctor"], function() {
            Route::get("calendar", ['uses' => "CalendarController@index", 'as' => 'admin.calendar.index']);
            Route::get("calendar/feed", "CalendarController@feed");
            Route::get('/calendar/feed-closed-dates', "CalendarController@feedClosedDates");
            Route::get('/calendar/feed-calendar', "CalendarController@feedCalendar");
        });

        // CLINICA
        Route::group(["middleware" => "auth.clinic"], function(){
            // ROUTES GET FOR CLINIC
            Route::get("clinic", ["uses" => "UserController@getClinicIndex", "as" => "admin.clinic.index"]);
            Route::get("users/{id}/doctor", ["uses" => "UserController@getDoctorDetails", "as" => "admin.users.doctor"]);
            Route::get("calendar/{id}/feed-calendar-doctor", "CalendarController@getDoctorAppointments");
            Route::get("calendar/clinic-appointments", "CalendarController@getClinicAppointments");
            Route::get("calendar/clinic-time-slots", "CalendarController@getClinicTimeSlots");
            Route::get("calendar/clinic-closed-dates", "CalendarController@feedClosedDatesClinic");
            Route::get("get-local-details", "LocalController@getLocalDetails");
            Route::get("time-slots/create-multiple", "TimeSlotController@createMultiple");
            Route::get('get-health-plans-for-multiple-locals', 'HealthPlanController@getMultiplePlansForLocals');
            Route::get('users-without-clinic', 'UserController@getUsersWithoutClinic');
            Route::get('add-doctor-to-clinic/{id}', 'UserController@addDoctorToClinic');

            // ROUTES POST FOR CLINIC
            Route::post('time-slots/create', 'TimeSlotController@storeMultiple');
        });

        // ROTA DE AJAX --ignore
        Route::get('get-health-plans/{id}', 'HealthPlanController@getPlans');
        Route::get('get-locals-for-multiple-medics', 'LocalController@getLocalsForMultipleMedics');
        Route::get('get-clinic-address', 'UserController@getClinicAddress');
        Route::get('get-details-by-id', 'CalendarController@getDetailsTimeSlot');
        Route::get('close-plan', 'TimeSlotController@closePlan');
        Route::get('delete-time-slot', 'TimeSlotController@deleteTimeSlot');
        Route::get('closed-date-destroy/{id}/', 'ClosedDateController@destroyClosedDate');
    });
});

/*
 * *************************************
 * *     ROTAS PARA SITE APLICAÇÃO     *
 * *************************************
 */

// LOGIN

Route::post('site/login', 'Site\SiteRegisterController@login');
Route::get('site/logout', 'Site\SiteRegisterController@logout');
Route::post('site/recuperar-senha', 'Site\SiteRegisterController@forgotPassword');

// LOGIN / CADASTRO VIA FACEBOOK

Route::get('login/facebook', ['as' => 'login.facebook', 'uses' => 'Site\FacebookController@getSocialAuth']);
Route::get('login/callback/facebook', 'Site\FacebookController@getSocialAuthCallback');
Route::get('login/facebook/sdk', ['as' => 'login.facebook.sdk', 'uses' => 'Site\FacebookController@getSDKSocialAuth']);

// MÉDICOS

Route::get('medicos', 'Site\SitePhysiciansController@index');
Route::get('medicos/{physician}', 'Site\SitePhysiciansController@show');

// BUSCA DE MÉDICOS

Route::get('encontre-um-medico', 'Site\SitePhysiciansController@search');
Route::post('encontre-um-medico', 'Site\SitePhysiciansController@results');

// AGENDAMENTOS

Route::get('agendamentos', 'Site\SiteAppointmentController@index');
Route::get('agendamentos/{id}', 'Site\SiteAppointmentController@show');
Route::get('agendamentos/cancelar/{id}', 'Site\SiteAppointmentController@cancel');
Route::get('agendar/{place_id}/{place_next}/{place_next_epoch}/{detail_id}', 'Site\SiteAppointmentController@makeMark');
Route::get('consultas/', 'Site\SitePhysiciansController@getConsultations');

// CADASTRO VIA SITE / COM LOGIN

Route::get('cadastro', 'Site\SiteRegisterController@register');
Route::post('cadastro', 'Site\SiteRegisterController@store');
Route::post('atualizar', 'Site\SiteRegisterController@update');

// CADASTRO DE MÉDICO / COM LOGIN NO PAINEL ADMINISTRATIVO

Route::post('medicos-cadastro', 'Site\SiteRegisterController@storeMedics');

// OUTRAS

Route::get('baixar-app', 'Site\SiteController@download');
Route::get('contato', 'Site\SiteController@contact');
Route::post('contato', 'Site\SiteController@emailAndContact');

// RECUPEREADNO A LOCALIZAÇÃO DO USUARIO PARA O JAVASCRIPT

Route::get('get-ip-location', 'Site\SiteController@getLocation');

// Recover password

Route::auth();
