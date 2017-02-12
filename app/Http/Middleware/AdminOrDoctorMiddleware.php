<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminOrDoctorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if($user) {
            if ($user->user_type == User::UserTypeDoctor || $user->user_type == User::UserTypeAdmin) {
                return $next($request);
            }
        }

        return redirect("admin/home");
    }
}
