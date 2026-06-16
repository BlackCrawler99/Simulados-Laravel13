<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Busca os cursos em ordem alfabética para o Select
        $courses = Course::orderBy('name')->get();
        
        return view('auth.register', compact('courses'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Defina as regras base que são obrigatórias para todos
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'uf' => ['required', 'string', 'size:2'],
            'desired_course' => ['required', 'string', 'max:100'],
            'school_year' => ['required', 'string', 'max:50'],
            'interested_course' => ['nullable', 'string', 'max:255'],
        ];

        // 2. Adicione as regras dinâmicas do módulo premium, se ativo
        $isModuleActive = \App\Models\Setting::where('key', 'module_colegios')->value('value') == '1';

        if ($isModuleActive) {
            $rules['school_id'] = ['required', 'exists:schools,id'];
            $rules['school_class_id'] = ['required', 'exists:school_classes,id'];
        }

        // 3. Valide tudo de uma só vez
        $request->validate($rules);

        // 4. Padronização do campo (agora com os dados validados)
        $cursoPadronizado = null;
        if ($request->filled('interested_course')) {
            $cursoPadronizado = Str::title(Str::lower(trim($request->interested_course)));
        }

        // 5. Criação do usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'city' => $request->city,
            'uf' => strtoupper($request->uf),
            'desired_course' => $request->desired_course,
            'school_year' => $request->school_year,
            'accepts_info' => $request->has('accepts_info'),
            'interested_course' => $cursoPadronizado,
            // Adicione os campos do módulo premium aqui
            'school_id' => $isModuleActive ? $request->school_id : null,
            'school_class_id' => $isModuleActive ? $request->school_class_id : null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
