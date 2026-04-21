<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAlumnoAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('alumno')->check()) {
            return redirect()->route('alumno.login');
        }

        return $next($request);
    }
}
