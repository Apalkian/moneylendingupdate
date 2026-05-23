<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()?->isBorrower()) {
            return redirect()->route('borrower.portal');
        }

        if (Auth::check() && Auth::user()?->isAdmin()) {
            if (! session()->has('admin_id')) {
                session(['admin_id' => Auth::id()]);
            }

            return $next($request);
        }

        if (! session()->has('admin_id')) {
            return redirect()->route('login')->withErrors([
                'login' => 'Please log in to access the system.',
            ]);
        }

        return $next($request);
    }
}
