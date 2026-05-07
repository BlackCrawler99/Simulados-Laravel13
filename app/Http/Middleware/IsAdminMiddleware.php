<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está logado E se a coluna is_admin é verdadeira
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request); // Deixa passar!
        }

        // Se não for admin, aborta com erro 403 (Acesso Negado) ou redireciona
        abort(403, 'Acesso restrito apenas para administradores.');
    }
}