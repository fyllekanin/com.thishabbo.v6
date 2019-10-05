<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\UserHelper;
use Auth;

class Staff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $perm)
    {
        if (Auth::check()) {
            if (UserHelper::haveStaffPerm(Auth::user()->userid, $perm)) {
                return $next($request);
            }
        }

        return redirect()->route('getErrorPerm');
    }
}
