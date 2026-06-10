<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class PremiumSchoolController extends Controller
{
    /**
     * 1. TELA INICIAL: Lista os Colégios
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->input('search');

        // Busca com filtro e paginação (12 por página para fechar certinho num grid de 3)
        $schools = School::withCount('classes')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('city', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(12);
        
        return view('admin.schools.index', compact('schools', 'search'));
    }

    /**
     * 2. TELA DE TURMAS: Lista as turmas de um colégio específico
     */
    public function classes(School $school)
    {
        // Busca as turmas deste colégio e conta quantos alunos têm em cada turma
        // O "withCount" cria a variável "users_count"
        $classes = $school->classes()->withCount('users')->orderBy('name', 'asc')->get();

        return view('admin.schools.classes', compact('school', 'classes'));
    }

    /**
     * 3. TELA DE RELATÓRIO: O Dashboard de Desempenho da Turma
     */
    public function report(School $school, SchoolClass $schoolClass)
    {
        // TRAVA DE SEGURANÇA: Impede que alguém manipule a URL
        if ($schoolClass->school_id !== $school->id) {
            abort(404, 'Esta turma não pertence a este colégio.');
        }

        // Puxa todos os alunos da turma, junto com os exames concluídos deles
        $students = $schoolClass->users()->with(['exams' => function ($query) {
            $query->whereNotNull('completed_at')->orderBy('completed_at', 'desc');
        }])->get();

        // Variáveis base para o nosso Gráfico e Relatório
        $totalStudents = $students->count();
        $studentsWithExams = 0;
        $globalAverage = 0;
        $totalScores = 0;
        
        // Estrutura para o Gráfico de Teia (Radar)
        $areaStats = [];

        // Loop para varrer os alunos e extrair as estatísticas reais
        foreach ($students as $student) {
            $bestExam = $student->exams->sortByDesc('score')->first();

            if ($bestExam) {
                $studentsWithExams++;
                $totalScores += $bestExam->score;

                // Carrega as questões para descobrir o desempenho por área do ENEM
                $bestExam->load('answers.question');

                foreach ($bestExam->answers as $answer) {
                    if ($answer->question) {
                        $areaName = trim($answer->question->area);
                        
                        if (!isset($areaStats[$areaName])) {
                            $areaStats[$areaName] = ['total_questions' => 0, 'correct_answers' => 0];
                        }

                        $areaStats[$areaName]['total_questions']++;
                        
                        if ($answer->is_correct) {
                            $areaStats[$areaName]['correct_answers']++;
                        }
                    }
                }
            }
        }

        // Calcula a média da turma (0 a 10)
        if ($studentsWithExams > 0) {
            $globalAverage = $totalScores / $studentsWithExams;
        }

        // Transforma os dados das áreas em porcentagem para injetar no Chart.js
        $radarLabels = [];
        $radarValues = [];

        foreach ($areaStats as $area => $stats) {
            $radarLabels[] = $area;
            $radarValues[] = $stats['total_questions'] > 0 
                ? round(($stats['correct_answers'] / $stats['total_questions']) * 100) 
                : 0;
        }

        return view('admin.schools.report', compact(
            'school', 
            'schoolClass', 
            'students', 
            'totalStudents', 
            'studentsWithExams', 
            'globalAverage',
            'radarLabels',
            'radarValues'
        ));
    }
}