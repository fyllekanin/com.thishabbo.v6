<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\UserHelper;
use Auth;

class Feature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $feature)
    {
        if (Auth::check()) {
            if (UserHelper::haveSubFeature(Auth::user()->userid, $feature)) {
                return $next($request);
            }
        }

        return $next($request);
    }
}
