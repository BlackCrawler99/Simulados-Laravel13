<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\PremiumSchoolController;
use App\Models\School;
use Illuminate\Support\Facades\Route;

// =========================================================
// ROTA RETORNO DE TURMAS VIA AJAX (Usada no Formulário de Registro para popular o dropdown de turmas com base na escola selecionada)
// =========================================================
Route::get('/api/escolas/{school}/turmas', function (School $school) {
    // Retorna apenas as turmas pertencentes a essa escola em formato JSON
    return response()->json($school->classes()->select('id', 'name')->orderBy('name', 'asc')->get());
});

Route::get('/', function () {
    // Tenta buscar o tema no banco. Se falhar (banco vazio ou sem a tabela), usa o 'default'
    try {
        $theme = \App\Models\Setting::where('key', 'active_theme')->value('value') ?? 'default';
    } catch (\Exception $e) {
        $theme = 'default';
    }

    // Se o tema escolhido não existir na pasta, volta por segurança para o padrão
    if (!view()->exists("themes.{$theme}")) {
        $theme = 'default';
    }

    return view("themes.{$theme}");
})->name('welcome');

Route::get('/dashboard', function () {
    // 1. SE FOR ADMIN (ROOT): Desvia o fluxo imediatamente para o painel administrativo
    if (auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    // 2. SE FOR ALUNO: Segue o seu fluxo original idêntico ao que já estava funcionando
    $exams = auth()->user()->exams()->orderBy('created_at', 'desc')->get();
    
    return view('dashboard', compact('exams'));
})->middleware(['auth', 'verified'])->name('dashboard');

// --- ROTAS DO ALUNO LOGADO ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do Simulado
    Route::post('/simulado/iniciar', [App\Http\Controllers\ExamController::class, 'start'])->name('exams.start');
    Route::post('/simulado/{exam}/finalizar', [App\Http\Controllers\ExamController::class, 'submit'])->name('exams.submit');
    Route::get('/simulado/{exam}', [App\Http\Controllers\ExamController::class, 'show'])->name('exams.show');
    Route::get('/simulado/{exam}/pdf', [App\Http\Controllers\ExamController::class, 'downloadPdf'])->name('exams.pdf');
    Route::get('/simulado/{exam}/resultado', [App\Http\Controllers\ExamController::class, 'result'])->name('exams.result');

    // =========================================================
    // MÓDULOS PREMIUM DO ALUNO (Protegidos pelo Middleware)
    // =========================================================
    Route::middleware(['module.enabled'])->group(function () {
        
        // --- TESTE VOCACIONAL ---
        Route::prefix('vocacional')->name('vocational.')->group(function () {
            Route::get('/', [App\Http\Controllers\StudentVocationalController::class, 'start'])->name('start');
            Route::post('/', [App\Http\Controllers\StudentVocationalController::class, 'submit'])->name('submit');
            Route::get('/resultado', [App\Http\Controllers\StudentVocationalController::class, 'result'])->name('result');
        });

    });
});

// --- ROTAS DO ADMIN ---
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    
    // --- ÁREA EXCLUSIVA DO DESENVOLVEDOR ---
    // Mesmo estando dentro do /admin, só o super_admin passa daqui
    Route::middleware(['super_admin'])->group(function () {
        Route::get('/modulos', [App\Http\Controllers\Dev\FeatureController::class, 'index'])->name('admin.features.index');
        Route::post('/modulos', [App\Http\Controllers\Dev\FeatureController::class, 'store'])->name('admin.features.store');
    });

    // Rota da Dashboard Administrativa
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    // Rota que lista os leads/candidatos
    Route::get('/candidatos', [CandidateController::class, 'index'])->name('admin.candidates.index');
    // Rota para exportar os leads/candidatos para Excel
    Route::get('/dashboard/exportar-leads', [App\Http\Controllers\Admin\DashboardController::class, 'export'])->name('admin.dashboard.export');
    
    // Rotas de Questões
    Route::get('/questoes', [App\Http\Controllers\Admin\QuestionController::class, 'index'])->name('admin.questions.index');
    Route::get('/questoes/criar', [App\Http\Controllers\Admin\QuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/questoes', [App\Http\Controllers\Admin\QuestionController::class, 'store'])->name('admin.questions.store');
    Route::post('/questoes/importar', [App\Http\Controllers\Admin\QuestionController::class, 'import'])->name('admin.questions.import');
    Route::get('/questoes/modelo', [App\Http\Controllers\Admin\QuestionController::class, 'downloadTemplate'])->name('admin.questions.template');
    Route::get('/questoes/{question}/editar', [App\Http\Controllers\Admin\QuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::put('/questoes/{question}', [App\Http\Controllers\Admin\QuestionController::class, 'update'])->name('admin.questions.update');
    Route::delete('/questoes/{question}', [App\Http\Controllers\Admin\QuestionController::class, 'destroy'])->name('admin.questions.destroy');

    // Rotas de Cursos
    Route::get('/cursos', [App\Http\Controllers\Admin\CourseController::class, 'index'])->name('admin.courses.index');
    Route::post('/cursos', [App\Http\Controllers\Admin\CourseController::class, 'store'])->name('admin.courses.store');
    Route::put('/cursos/{course}', [App\Http\Controllers\Admin\CourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('/cursos/{course}', [App\Http\Controllers\Admin\CourseController::class, 'destroy'])->name('admin.courses.destroy');

    // Gestão de Usuários/Administradores
    Route::get('/usuarios', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/usuarios/{user}/editar', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/usuarios/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::patch('/usuarios/{user}/toggle-admin', [App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
    Route::delete('/usuarios/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

    // Configurações do Sistema
    Route::get('/configuracoes', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/configuracoes/tema', [App\Http\Controllers\Admin\SettingController::class, 'updateTheme'])->name('admin.settings.update-theme');
    Route::post('/configuracoes/regras', [App\Http\Controllers\Admin\SettingController::class, 'updateRules'])->name('admin.settings.update-rules');


    // =========================================================
    // MÓDULOS PREMIUM (Protegidos pelo Middleware Inteligente)
    // =========================================================
    Route::middleware(['module.enabled'])->group(function () {
        
        // --- MÓDULO COLÉGIOS ---
        Route::prefix('colegios')->name('admin.colegios.')->group(function () {
            Route::get('/', [PremiumSchoolController::class, 'index'])->name('index');
            Route::get('/{school}/turmas', [PremiumSchoolController::class, 'classes'])->name('classes');            
            Route::get('/{school}/turmas/{schoolClass}/relatorio', [PremiumSchoolController::class, 'report'])->name('report');
        });

        // --- MÓDULO VOCACIONAL ---
        Route::prefix('vocacional')->name('admin.vocational.')->group(function () {
            // Dashboard Inicial (Cards)
            Route::get('/', [App\Http\Controllers\Admin\VocationalController::class, 'index'])->name('index');
            
            // Gestão de Questões
            Route::get('/questoes', [App\Http\Controllers\Admin\VocationalController::class, 'questions'])->name('questions');
            Route::get('/questoes/modelo', [App\Http\Controllers\Admin\VocationalController::class, 'downloadTemplate'])->name('template');
            Route::post('/questoes/importar', [App\Http\Controllers\Admin\VocationalController::class, 'import'])->name('import');
            Route::get('/questoes/criar', [App\Http\Controllers\Admin\VocationalController::class, 'create'])->name('create');
            Route::post('/questoes', [App\Http\Controllers\Admin\VocationalController::class, 'store'])->name('store');
            
            // Gestão de Alunos (Resultados)
            Route::get('/alunos', [App\Http\Controllers\Admin\VocationalController::class, 'students'])->name('students');
            Route::get('/alunos/{result}/relatorio', [App\Http\Controllers\Admin\VocationalController::class, 'report'])->name('report');
        });

    });

});

require __DIR__.'/auth.php';