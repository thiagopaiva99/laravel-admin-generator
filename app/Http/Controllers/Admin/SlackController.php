<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maknz\Slack\Facades\Slack;

class SlackController extends Controller
{
    public function index(Request $request){
        // Send a message to the default channel
        Slack::send('Hello world!');

        return $request->all();
    }
}
