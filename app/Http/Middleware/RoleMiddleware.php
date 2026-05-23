<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage: middleware('role:admin') or middleware('role:borrower').
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        if (empty($roles)) {
            abort(403, 'Role is required.');
        }

        $normalizedRoles = array_map(static fn (string $role): string => strtolower(trim($role)), $roles);
        $userRole = strtolower((string) $user->role);

        if (! in_array($userRole, $normalizedRoles, true)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}

