<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->role === 'admin') {
            return $next($request);
        }

        $redirectRoute = match ($user?->role) {
            'produsen' => 'producer.dashboard',
            default => 'buyer.dashboard',
        };

        return redirect()->route($redirectRoute)
            ->with('error', 'Halaman ini hanya untuk administrator.');
    }
}
