<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class NoTouchSuper
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if ((User::userIsAdminOnly($request->user())) && (User::userIsSuper($request->user))) {
            return $request->expectsJson()
                ? abort(403, 'You do not have access to this profile')
                : Redirect::route('logout');
        }
        return $next($request);
    }
}
