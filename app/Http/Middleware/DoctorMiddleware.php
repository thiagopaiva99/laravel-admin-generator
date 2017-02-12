<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Auth;
class DoctorMiddleware
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
            if ($user->user_type == User::UserTypeDoctor) {
                return $next($request);
            }
        }

        return redirect("admin/home");
    }
}
