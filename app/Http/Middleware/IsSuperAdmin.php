<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->is_super_admin) {
            return $next($request);
        }

        // Se um administrador comum ou aluno tentar acessar, dá erro 403 (Proibido) ou joga pro painel dele
        abort(403, 'Acesso restrito a desenvolvedores.');
    }
}