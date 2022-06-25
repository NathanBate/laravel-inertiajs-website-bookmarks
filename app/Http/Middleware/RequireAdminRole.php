<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RequireAdminRole
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
        if (($request->user()->toArray()['role'] == "Subscriber") || ($request->user()->toArray()['role'] == "Editor")) {
            return $request->expectsJson()
                ? abort(403, 'You do not have admin access.')
                : Redirect::route('logout');
        }
        return $next($request);
    }
}
