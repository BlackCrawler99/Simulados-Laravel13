<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $activeTheme = Setting::where('key', 'active_theme')->value('value') ?? 'default';
        return view('admin.settings.index', compact('activeTheme'));
    }

    public function updateTheme(Request $request)
    {
        $request->validate(['theme' => 'required|string']);

        Setting::updateOrCreate(
            ['key' => 'active_theme'],
            ['value' => $request->theme]
        );

        return back()->with('status', 'Tema da página inicial atualizado com sucesso!');
    }

    public function updateRules(\Illuminate\Http\Request $request)
    {
        // Valida se o professor digitou um número válido (entre 1 e 200 questões)
        $request->validate([
            'exam_question_count' => 'required|integer|min:1|max:200',
        ]);

        // Cria a configuração ou atualiza se ela já existir
        \App\Models\Setting::updateOrCreate(
            ['key' => 'exam_question_count'],
            ['value' => $request->exam_question_count]
        );

        return back()->with('status', 'Regras do simulado atualizadas com sucesso!');
    }
}