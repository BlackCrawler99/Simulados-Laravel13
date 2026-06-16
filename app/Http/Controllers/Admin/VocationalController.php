<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VocationalQuestion;
use App\Models\VocationalResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\VocationalTemplateExport;
use App\Imports\VocationalImport;
use Maatwebsite\Excel\Facades\Excel;

class VocationalController extends Controller
{
    // 1. TELA DOS CARDS (Dashboard)
    public function index()
    {
        $questionsCount = VocationalQuestion::count(); // Contagem de questões cadastradas
        $studentsCount = VocationalResult::count(); // Contagem de alunos que fizeram o teste
        
        return view('admin.vocational.index', compact('questionsCount', 'studentsCount'));
    }

    // 2. TELA DA TABELA DE QUESTÕES
    public function questions()
    {
        $questions = VocationalQuestion::withCount('options')->latest()->paginate(15);
        return view('admin.vocational.questions', compact('questions'));
    }

   // 3. TELA DOS ALUNOS (Listagem)
    public function students()
    {
        // Busca os resultados trazendo os dados do aluno atrelado (Eager Loading)
        $results = \App\Models\VocationalResult::with('user')->latest()->paginate(15);
        
        return view('admin.vocational.students', compact('results'));
    }

    // 4. TELA DE RELATÓRIO INDIVIDUAL (Gráfico do Aluno)
    public function report(\App\Models\VocationalResult $result)
    {
        // Carrega o usuário atrelado ao resultado
        $result->load('user');

        // Como nós colocamos o 'casts' => ['scores' => 'array'] no Model, 
        // o Laravel já transforma o JSON do banco nisso: ['Exatas' => 10, 'Humanas' => 5]
        $scores = $result->scores ?? [];
        
        // Separa os nomes das áreas e as pontuações para injetar no Chart.js
        $chartLabels = array_keys($scores);
        $chartValues = array_values($scores);

        return view('admin.vocational.report', compact('result', 'chartLabels', 'chartValues', 'scores'));
    }

    public function downloadTemplate()
    {
        return Excel::download(new VocationalTemplateExport, 'modelo_importacao_vocacional.xlsx');
    }

    public function import(Request $request)
    {
        // Certifique-se de que no formulário da View o input file se chama 'file' (ou mude aqui para 'excel_file' se preferir padronizar)
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new VocationalImport, $request->file('file'));
            return redirect()->route('admin.vocational.questions')->with('status', 'Questões vocacionais importadas com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.vocational.questions')->withErrors(['error' => 'Erro ao importar: ' . $e->getMessage()]);
        }
    }
}