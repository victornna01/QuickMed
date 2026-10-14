<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }
        abort_unless(in_array(auth()->user()->role, $roles, true), 403, 'You are not allowed to access this page.');
        return $next($request);
    }
}
