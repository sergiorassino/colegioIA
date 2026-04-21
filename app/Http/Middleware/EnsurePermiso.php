<?php

namespace App\Http\Middleware;

use App\Models\Auth\Profesor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermiso
{
    public function handle(Request $request, Closure $next, string $slugOrOrden): Response
    {
        if (! Auth::guard('staff')->check()) {
            return redirect()->route('staff.login');
        }

        /** @var Profesor $profesor */
        $profesor = Auth::guard('staff')->user();

        // Si se pasa un número, verificar directamente por orden
        if (is_numeric($slugOrOrden)) {
            if (! $profesor->tienePermiso((int) $slugOrOrden)) {
                abort(403, 'Sin permiso para acceder a este módulo.');
            }

            return $next($request);
        }

        // Si es un slug, verificar mediante Gate
        if (! Gate::forUser($profesor)->allows($slugOrOrden)) {
            abort(403, 'Sin permiso para acceder a este módulo.');
        }

        return $next($request);
    }
}
