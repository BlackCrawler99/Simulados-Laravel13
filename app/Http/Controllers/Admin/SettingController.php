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
}