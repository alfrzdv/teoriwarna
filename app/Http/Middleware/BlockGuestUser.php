<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockGuestUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is the guest user
        if (auth()->check() && auth()->user()->email === 'guest@teoriwarna.com') {
            return redirect()->route('login')
                ->with('error', 'Guest users cannot access this page. Please create a real account.');
        }

        return $next($request);
    }
}
