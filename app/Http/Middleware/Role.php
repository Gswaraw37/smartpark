<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        if (!Auth::check()) {
            abort(403, 'Belum Memiliki Akun');
        }
        $roles = explode('|', $roles);

        $user = Auth::user();

        foreach ($roles as $role) {
            if ($user->role($role)) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'Anda Bukan Admin/Staff');
    }
}
