<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            $loginRoute = in_array('produsen', $roles, true)
                ? 'producer.login'
                : 'buyer.login';

            return redirect()->guest(route($loginRoute));
        }

        if (! in_array($user->role, $roles, true)) {
            return redirect()->route($this->dashboardRouteFor($user->role))
                ->with('error', 'Halaman ini tidak tersedia untuk jenis akun Anda.');
        }

        return $next($request);
    }

    private function dashboardRouteFor(string $role): string
    {
        return match ($role) {
            'produsen' => 'producer.dashboard',
            'admin' => 'admin.dashboard',
            default => 'buyer.dashboard',
        };
    }
}
