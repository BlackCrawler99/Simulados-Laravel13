<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CandidateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Busca todos os simulados do usuário logado, do mais recente para o mais antigo
    $exams = auth()->user()->exams()->orderBy('created_at', 'desc')->get();
    
    return view('dashboard', compact('exams'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Comentando a rota abaixo para impedir exclusão da conta pelo próprio usuário,
        //  o que pode causar perda de leads e fraudes. 
        // Se necessário, a exclusão deve ser feita manualmente pelo admin.
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do Simulado
    Route::post('/simulado/iniciar', [App\Http\Controllers\ExamController::class, 'start'])->name('exams.start');
    Route::post('/simulado/{exam}/finalizar', [App\Http\Controllers\ExamController::class, 'submit'])->name('exams.submit');
    Route::get('/simulado/{exam}', [App\Http\Controllers\ExamController::class, 'show'])->name('exams.show');
    Route::get('/simulado/{exam}/pdf', [App\Http\Controllers\ExamController::class, 'downloadPdf'])->name('exams.pdf');
    Route::get('/simulado/{exam}/resultado', [App\Http\Controllers\ExamController::class, 'result'])->name('exams.result');
});
################# ADMIN #################
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {    
    // Rota da Dashboard Administrativa
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    // Rota que lista os leads/candidatos
    Route::get('/candidatos', [CandidateController::class, 'index'])->name('admin.candidates.index');
    
    // Rotas de Questões
    Route::get('/questoes', [App\Http\Controllers\Admin\QuestionController::class, 'index'])->name('admin.questions.index');
    Route::get('/questoes/criar', [App\Http\Controllers\Admin\QuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/questoes', [App\Http\Controllers\Admin\QuestionController::class, 'store'])->name('admin.questions.store');
    Route::post('/questoes/importar', [App\Http\Controllers\Admin\QuestionController::class, 'import'])->name('admin.questions.import');
    Route::get('/questoes/modelo', [App\Http\Controllers\Admin\QuestionController::class, 'downloadTemplate'])->name('admin.questions.template');
    
    // Novas rotas para Editar, Atualizar e Excluir:
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
    Route::patch('/usuarios/{user}/toggle-admin', [App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
    Route::delete('/usuarios/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

    // Gestão de Usuários/Administradores
    Route::get('/usuarios', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/usuarios/{user}/editar', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/usuarios/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::patch('/usuarios/{user}/toggle-admin', [App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
    Route::delete('/usuarios/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';