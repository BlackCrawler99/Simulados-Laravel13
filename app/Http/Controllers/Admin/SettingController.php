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
        //dd($request->all());
        // Valida se o professor digitou um número válido (entre 1 e 200 questões)
        $request->validate([
            'exam_question_count' => 'required|integer|min:1|max:200',
            'max_scholarship'     => 'required|integer|min:0|max:100',
            'whatsapp_number'     => 'required|string|max:20',
            'whatsapp_message'    => 'required|string',
            'promotion_active'   => 'nullable',
            'promotion_end_time' => 'required_if:promotion_active,on|nullable|date',
            // Validações dos prêmios (permitimos nullable caso a IES não queira dar brinde em alguma faixa)
            'reward_tier_1'       => 'nullable|string|max:100',
            'reward_tier_2'       => 'nullable|string|max:100',
            'reward_tier_3'       => 'nullable|string|max:100',
            'reward_tier_4'       => 'nullable|string|max:100',
            'reward_tier_5'       => 'nullable|string|max:100',
            'reward_tier_6'       => 'nullable|string|max:100',
        ]);
\App\Models\Setting::updateOrCreate(['key' => 'exam_question_count'], ['value' => $request->exam_question_count]);
    \App\Models\Setting::updateOrCreate(['key' => 'max_scholarship'], ['value' => $request->max_scholarship]);
    
    // 3. Atualização do CHECKBOX (A lógica de ouro)
    \App\Models\Setting::updateOrCreate(
        ['key' => 'promotion_active'], 
        ['value' => $request->has('promotion_active') ? 'true' : 'false']
    );

    // 4. Datas e WhatsApp
    \App\Models\Setting::updateOrCreate(['key' => 'promotion_end_time'], ['value' => $request->promotion_end_time]);
    \App\Models\Setting::updateOrCreate(['key' => 'whatsapp_number'], ['value' => preg_replace('/[^0-9]/', '', $request->whatsapp_number)]);
    \App\Models\Setting::updateOrCreate(['key' => 'whatsapp_message'], ['value' => $request->whatsapp_message]);

    // 5. Loop dos Prêmios
    for ($i = 1; $i <= 6; $i++) {
        \App\Models\Setting::updateOrCreate(
            ['key' => 'reward_tier_' . $i],
            ['value' => $request->input('reward_tier_' . $i) ?? '']
        );
    }

    return redirect()->back()->with('status', 'Regras salvas com sucesso!');
    }
}