<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsPelanggan
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'pelanggan') {
            abort(403, 'Akses ditolak. Hanya pelanggan yang dapat masuk.');
        }

        return $next($request);
    }
}
