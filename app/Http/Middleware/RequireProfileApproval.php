<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RequireProfileApproval
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

        if (! $request->user() ||
            ($request->user()->toArray()['role'] == "Waiting Approval")) {
            return $request->expectsJson()
                ? abort(403, 'Your profile has not been approved yet.')
                : Redirect::route('waiting.approval');
        }

        return $next($request);
    }
}
