<?php
Route::get('/admin/users/test', 'Admin\UserController@test');

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

Route::get('/', function() {
    if( getenv("HOLDER") != "" ) return view('holder.holder');
    else return Auth::check() ? redirect('/admin') : redirect('/login');
});

Route::get('/home', function(){ return Auth::check() ? redirect('/admin') : redirect('/login');});

/*
 * *****************************************
 * *      CONFIGURAÇOES DO ROBOTS.TXT      *
 * *****************************************
 */
Route::get('robots.txt', function (){
    return 'soon';
});

/*
|--------------------------------------------------------------------------
| Painel Administrativo
|--------------------------------------------------------------------------
*/

Route::group(["prefix" => "admin", 'namespace' => 'Admin', "middleware" => "auth"],function() {
    Route::get("/", function () { return redirect('admin/home'); });
    Route::get('home', 'HomeController@index');
    Route::get('get-menus', 'MenuController@getMenus');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    // ROUTES WITHOUT RESOURCES
    // ONLY GETS
    Route::get('menus/order', 'MenuController@getViewOrder');
    Route::get('api/get-attributes', 'APIGeneratorController@getAttributes');
    Route::get('api/generate', 'APIGeneratorController@generateMethod');

    // ONLY POSTS
    Route::post('menus/order', 'MenuController@postViewOrder');

    // RESOURCE ROUTES
    Route::resource('options', 'OptionsController');
    Route::resource('pages', 'PagesController');
    Route::resource('menus', 'MenuController');
    Route::resource('holder', 'HolderController');
    Route::resource('users', 'UserController');
    Route::resource('slack', 'SlackController');
    Route::resource('api', 'APIGeneratorController');
});

/*
 * *************************************
 * *         ROUTES FOR AUTH           *
 * *************************************
 */

Route::auth();

