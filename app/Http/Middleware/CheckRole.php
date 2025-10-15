<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        // Pastikan pengguna terautentikasi dan memiliki peran yang sesuai
        if (Auth::check() && Auth::user()->role == $role) {
            return $next($request);  // Izinkan permintaan dilanjutkan
        }

        // Jika pengguna tidak memiliki peran yang sesuai, arahkan ke halaman login
        return redirect()->route('login');
    }
}