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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'uf' => ['required', 'string', 'size:2'],
            'desired_course' => ['required', 'string', 'max:100'],
            'school_year' => ['required', 'string', 'max:50'],
            'interested_course' => ['nullable', 'string', 'max:255'],
        ]);
    
        // Padronização do campo
        $cursoPadronizado = null;
        if ($request->filled('interested_course')) {
            // 1. trim() tira espaços no começo e fim
            // 2. Str::lower() deixa tudo minúsculo para evitar "eNgEnHaRiA"
            // 3. Str::title() capitaliza a primeira letra de cada palavra
            $cursoPadronizado = Str::title(Str::lower(trim($request->interested_course)));
        }

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
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
