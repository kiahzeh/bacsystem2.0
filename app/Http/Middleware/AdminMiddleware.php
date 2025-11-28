<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();
        $isAdmin = false;
        if ($user) {
            // Prefer explicit role or flag
            if (isset($user->role) && $user->role === 'admin') {
                $isAdmin = true;
            } elseif (isset($user->is_admin) && ((int) $user->is_admin === 1 || $user->is_admin === true)) {
                $isAdmin = true;
            } elseif (method_exists($user, 'isAdmin')) {
                // Fallback to method if present
                try {
                    $isAdmin = (bool) $user->isAdmin();
                } catch (\Throwable $e) {
                    // Use exception to avoid IDE warnings and aid debugging
                    report($e);
                    $isAdmin = false;
                }
            }
        }

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
