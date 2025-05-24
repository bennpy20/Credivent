<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $role = session('user.role');

        if (!$role) {
            return redirect()->route('login'); // belum login
        }

        if (!in_array($role, $roles)) {
            return abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}