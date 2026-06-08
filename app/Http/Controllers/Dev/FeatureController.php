<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class FeatureController extends Controller
{
    /**
     * Exibe o painel de controle de módulos do Super Admin.
     */
    public function index()
    {
        // Busca o status atual das chaves no banco. Se não existir, o padrão é 'false'.
        $features = [
            'module_vocational'     => Setting::where('key', 'module_vocational')->value('value') === 'true',
            'module_school_reports' => Setting::where('key', 'module_school_reports')->value('value') === 'true',
            // No futuro, se criar o módulo de redação, é só adicionar a linha aqui:
            // 'module_essay'       => Setting::where('key', 'module_essay')->value('value') === 'true',
        ];

        return view('dev.features.index', compact('features'));
    }

    /**
     * Salva as alterações no banco de dados.
     */
    public function store(Request $request)
    {
        // Lista de todos os módulos premium mapeados no sistema
        $availableModules = [
            'module_vocational',
            'module_school_reports',
            // 'module_essay',
        ];

        foreach ($availableModules as $module) {
            // Se o checkbox foi marcado, salva como 'true', senão 'false'
            $status = $request->has($module) ? 'true' : 'false';

            // Atualiza a chave se existir, ou cria se for a primeira vez
            Setting::updateOrCreate(
                ['key' => $module],
                ['value' => $status]
            );
        }

        return redirect()->route('admin.features.index')
                         ->with('status', 'Configurações de módulos atualizadas com sucesso!');
    }
}