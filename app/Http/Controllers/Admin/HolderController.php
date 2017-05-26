<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

use App\Http\Requests;

class HolderController extends AppBaseController
{
    public function index(){
        return view('holder.create');
    }

    public function store(Request $request){
        $title  = $request->get('title');
        $phrase = $request->get('phrase');
        $color  = $request->get('color');
        $email  = $request->get('contact');
        $image  = $request->get('image');

        $shell = "cd .. && cat .env && echo ".strtoupper("holder_title")."='".$title."' >> .env";
        shell_exec($shell);

        $shell = "cd .. && cat .env && echo ".strtoupper("holder_phrase")."='".$phrase."' >> .env";
        shell_exec($shell);

        $shell = "cd .. && cat .env && echo ".strtoupper("holder_color")."='".$color."' >> .env";
        shell_exec($shell);

        $shell = "cd .. && cat .env && echo ".strtoupper("holder_contact")."='".$email."' >> .env";
        shell_exec($shell);

        $shell = "cd .. && cat .env && echo ".strtoupper("holder_image")."='".$image."' >> .env";
        shell_exec($shell);

        return redirect(route('admin.options.index'));
    }
}
