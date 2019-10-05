<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\UserHelper;
use Auth;
use cookie;

class AccessSite
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
        if ($request->cookie('access_page') === "THIS_IS_THE_COOKIE") {
            return $next($request);
        }

        return redirect()->route('loginForAccess');
    }
}
