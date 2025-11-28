<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

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
        if ($user instanceof User) {
            if ($user->role === 'admin') {
                $isAdmin = true;
            } elseif (((int) $user->is_admin === 1) || ($user->is_admin === true)) {
                $isAdmin = true;
            } else {
                try {
                    $isAdmin = (bool) $user->isAdmin();
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
