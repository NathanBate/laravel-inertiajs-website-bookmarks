<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

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

        if (User::userIsUnapproved($request->user())) {
            if (User::userIsWaitingApproval($request->user())) {
                return $request->expectsJson()
                    ? abort(403, 'Your profile has not been approved yet.')
                    : Redirect::route('waiting.approval');
            } elseif (User::userIsDeniedorDisabled($request->user())) {
                return $request->expectsJson()
                    ? abort(403, 'Your profile has been denied approval.')
                    : Redirect::route('denied.approval');
            }
        }
        return $next($request);
    }
}
