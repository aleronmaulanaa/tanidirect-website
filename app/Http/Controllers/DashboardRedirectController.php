<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect()->route(match ($request->user()->role) {
            'produsen' => 'producer.dashboard',
            'admin' => 'admin.dashboard',
            default => 'buyer.dashboard',
        });
    }
}
