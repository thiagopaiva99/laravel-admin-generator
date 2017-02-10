<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class DoctorOrClinicMiddleware
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
        $user = User::find(\Auth::user()->id);

        if($user) {
            if ($user->user_type == User::UserTypeDoctor || $user->user_type == User::UserTypeClinic) {
                return $next($request);
            }
        }

        return redirect("admin/home");
    }
}
