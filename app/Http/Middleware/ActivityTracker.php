<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;

class ActivityTracker
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
        if (Auth::check()) {
            $time = strtotime("now");
            $ipaddress = $_SERVER["REMOTE_ADDR"];
            DB::table('users')->where('userid', Auth::user()->userid)->update([
                'lastactivity' => $time,
                'lastip' => $ipaddress
            ]);
        }
        return $next($request);
    }
}
