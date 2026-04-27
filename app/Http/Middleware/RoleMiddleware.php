<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Hierarchical RBAC levels.
     * Higher numbers can access lower-level resources.
     */
    private const ROLE_LEVELS = [
        'CIVILIAN' => 1,
        'SUPERVISOR' => 2,
        'ADMIN' => 3,
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $requiredRole): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login-civilian');
        }

        $userRole = strtoupper((string) $user->role);
        $requiredRole = strtoupper($requiredRole);

        $userLevel = self::ROLE_LEVELS[$userRole] ?? 0;
        $requiredLevel = self::ROLE_LEVELS[$requiredRole] ?? PHP_INT_MAX;

        if ($userLevel < $requiredLevel) {
            $redirectRoute = $this->dashboardForRole($userRole);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Forbidden: insufficient role privileges.',
                ], 403);
            }

            return redirect()->route($redirectRoute)->with('error', 'You are not authorized to access that page.');
        }

        return $next($request);
    }

    private function dashboardForRole(string $role): string
    {
        return match ($role) {
            'ADMIN' => 'admin-dashboard',
            'SUPERVISOR' => 'supervisor-dashboard',
            default => 'civilian-dashboard',
        };
    }
}
