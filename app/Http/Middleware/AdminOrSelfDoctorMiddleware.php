<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class AdminOrSelfDoctorMiddleware
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
            if ($user->user_type == User::UserTypeAdmin) {
                return $next($request);
            } else if ($user->user_type == User::UserTypeDoctor) {
                $userId = $request->route("users");
                if (\Auth::id() == $userId) {
                    return $next($request);
                }
            }
        }
        return redirect("admin/home");
    }
}
