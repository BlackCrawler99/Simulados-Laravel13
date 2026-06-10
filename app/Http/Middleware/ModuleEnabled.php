<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class ModuleEnabled
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Mapeamento: O que está na URL ou nome da rota => Chave na tabela Settings
        // Você pode mapear pelo nome da rota (mais seguro) ou URL (mais simples)
        $routeMap = [
            'admin.colegios'   => 'module_colegios',
            'admin.vocational' => 'module_vocational',
            'admin.reports'    => 'module_school_reports',
        ];

        $currentRouteName = $request->route()->getName();
        $moduleKey = null;

        // 2. Tenta encontrar a chave baseada no início do nome da rota
        foreach ($routeMap as $prefix => $key) {
            if (str_starts_with($currentRouteName, $prefix)) {
                $moduleKey = $key;
                break;
            }
        }

        // 3. Se achou um módulo, checa no banco
        if ($moduleKey) {
            $value = Setting::where('key', $moduleKey)->value('value');
            
            // ALTERAÇÃO AQUI: Aceita tanto '1' quanto 'true'
            if (!in_array($value, ['1', 'true'])) {
                abort(403, 'Acesso negado: Este recurso está desativado.');
            }
        }

        return $next($request);
    }
}