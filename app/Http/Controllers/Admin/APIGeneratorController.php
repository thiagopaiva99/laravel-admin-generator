<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;

class APIGeneratorController extends Controller
{
    public function index(Request $request){
        return view('admin.api.index');
    }

    public function create(Request $request){
        $tablesDB = DB::select(DB::raw("SELECT table_name FROM information_schema.tables WHERE table_schema='public'"));

        $tables = [];
        foreach($tablesDB as $value) $tables[] = $value;

        return view('admin.api.create', compact('tables'));
    }

    public function getAttributes(Request $request){
        $table = $request->get("table");
        return DB::raw(DB::select("
            SELECT DISTINCT
                a.attnum as num,
                a.attname as name,
                format_type(a.atttypid, a.atttypmod) as typ,
                a.attnotnull as notnull, 
                com.description as comment,
                coalesce(i.indisprimary,false) as primary_key,
                def.adsrc as default
            FROM pg_attribute a 
            JOIN pg_class pgc ON pgc.oid = a.attrelid
            LEFT JOIN pg_index i ON 
                (pgc.oid = i.indrelid AND i.indkey[0] = a.attnum)
            LEFT JOIN pg_description com on 
                (pgc.oid = com.objoid AND a.attnum = com.objsubid)
            LEFT JOIN pg_attrdef def ON 
                (a.attrelid = def.adrelid AND a.attnum = def.adnum)
            WHERE a.attnum > 0 AND pgc.oid = a.attrelid
            AND pg_table_is_visible(pgc.oid)
            AND NOT a.attisdropped
            AND pgc.relname = '$table'
            ORDER BY a.attnum;
        "))->getValue();
    }

    public function generateMethod(Request $request){
        $type = $request->get("type");
        switch ($type){
            case "login":
                return $this->generateLogin();
            break;
        }
    }

    public function generateStore(){

    }

    public function generateLogin(){
        return "public function login(UserLoginAPIRequest \$request, \$response = true) {\n
                    \$email    = \$request->get(\"email\");\n
                    \$password = \$request->get(\"password\");\n

                    if (Auth::attempt(['email' => \$email, 'password' => \$password], true)) {\n
                        // Authentication passed...\n
                        \$user = User::find(Auth::id());\n
                        return \$response ? response()->json(\$user) : true;\n
                    } else {\n
                        return \$response ? response()->json(\"invalid credentials\", 401) : false;\n
                    }\n
                }\n";
    }
}
