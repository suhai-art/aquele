<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * The $role parameter accepts a single role or a comma-separated list
     * of roles. Access is granted when the authenticated user has any of
     * the given roles (powered by spatie/laravel-permission).
     *
     * A user holding the "root" role bypasses any role restriction.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            throw new AuthenticationException;
        }

        // Super-admin role bypasses every restriction.
        if ($user->hasRole('root')) {
            return $next($request);
        }

        $roles = str_contains($role, ',') ? explode(',', $role) : $role;

        if (! $user->hasAnyRole($roles)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Acesso negado. Permissão insuficiente.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
