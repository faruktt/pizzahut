<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        if (!$admin->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You do not have permission to perform this action.'], 403);
            }

            return redirect()->route('admin.dashboard')->with('error', 'আপনার এই অপশনে প্রবেশের অনুমতি নেই (Permission Denied: ' . $permission . ')');
        }

        return $next($request);
    }
}
