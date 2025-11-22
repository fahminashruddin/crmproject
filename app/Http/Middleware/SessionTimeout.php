<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     * If user was idle longer than session lifetime, logout and invalidate session.
     * Otherwise update last_activity timestamp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $lifetimeMinutes = (int) config('session.lifetime', 120);
            $lifetime = $lifetimeMinutes * 60; // seconds

            $last = $request->session()->get('last_activity');
            $now = time();

            if ($last !== null && ($now - $last) > $lifetime) {
                // Session expired due to inactivity
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Sesi Anda telah berakhir karena tidak aktif. Silakan login kembali.',
                ]);
            }

            // Update last activity timestamp
            $request->session()->put('last_activity', $now);
        } catch (\Throwable $e) {
            // If session or auth not available, continue without breaking app
        }

        return $next($request);
    }
}
