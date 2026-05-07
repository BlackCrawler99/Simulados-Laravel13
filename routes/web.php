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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas do Simulado
    Route::post('/simulado/iniciar', [App\Http\Controllers\ExamController::class, 'start'])->name('exams.start');
    Route::post('/simulado/{exam}/finalizar', [App\Http\Controllers\ExamController::class, 'submit'])->name('exams.submit');
    Route::get('/simulado/{exam}', [App\Http\Controllers\ExamController::class, 'show'])->name('exams.show');
    Route::get('/simulado/{exam}/pdf', [App\Http\Controllers\ExamController::class, 'downloadPdf'])->name('exams.pdf');
});

Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {    
    // Rota da Dashboard Administrativa
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    // Rota que lista os leads/candidatos
    Route::get('/candidatos', [CandidateController::class, 'index'])->name('admin.candidates.index');
});

require __DIR__.'/auth.php';