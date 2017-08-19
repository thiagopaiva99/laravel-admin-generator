<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests;
use App\Models\Admin\Menu;
use App\Models\Admin\Options;
use App\Models\Admin\Pages;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends AppBaseController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users   = User::count();
        $pages   = Pages::count();
        $options = Options::count();
        $menus   = Menu::count();

        return view('home', compact('users', 'pages', 'options', 'menus'));
    }
}
