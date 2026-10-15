<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserBlocked
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->is_blocked) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('customer.login')->withErrors([
                    'email' => 'আপনার অ্যাকাউন্টটি অ্যাডমিন কর্তৃক ব্লক করা হয়েছে। লগইন করতে পারবেন না।'
                ]);
            }
        }

        return $next($request);
    }
}
