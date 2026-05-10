<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {// If there is no admin_id in the session, kick them back to the login page
    if (!session()->has('admin_id')) {
        return redirect('/login')->withErrors(['login' => 'Please log in to access the system.']);return $next($request);
    }
    return $next($request);
}
}
