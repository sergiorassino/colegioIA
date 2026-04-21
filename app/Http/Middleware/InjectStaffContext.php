<?php

namespace App\Http\Middleware;

use App\Auth\ContextoStaff;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectStaffContext
{
    public function __construct(private readonly ContextoStaff $contexto) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->contexto->cargarDesdeSession();

        return $next($request);
    }
}
