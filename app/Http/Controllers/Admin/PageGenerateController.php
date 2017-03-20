<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

class PageGenerateController extends Controller
{
    public function index(){
        return view('admin.pageGenerator.index');
    }

    public function create(){
        return view('admin.pageGenerator.create');
    }

    public function store(Request $request){

        $shell = "cd .. && echo '[".$request->get("str")."]' > infyom_json.json && cat infyom_json.json && php artisan infyom:scaffold ".ucwords($request->name)." --prefix=admin --paginate=10 --datatables=true --fieldsFile=infyom_json.json";

        return dump(shell_exec($shell));

        return dump($request->all());
    }
}
