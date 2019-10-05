<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\UserHelper;
use Auth;
use DB;

class Maintenance
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
        $mt = DB::table('maintenances')->where('active', 1)->first();
        if (!count($mt)) {
            return $next($request);
        }

        if (Auth::check() and !UserHelper::haveAdminPerm(Auth::user()->userid, 1)) {
            return redirect()->route('getMaintenance');
        } else {
            if (!Auth::check()) {
                return redirect()->route('getMaintenance');
            }

            return $next($request);
        }
    }
}
