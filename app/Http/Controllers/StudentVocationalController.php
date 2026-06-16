<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VocationalQuestion;
use App\Models\VocationalOption;
use App\Models\VocationalResult;

class StudentVocationalController extends Controller
{
    // 1. Exibe a prova com todas as perguntas
   public function start()
    {

        // Se ele já fez o teste, manda direto pro resultado dele
        if (auth()->user()->vocationalResult()->exists()) {
            return redirect()->route('vocational.result');
        }

        // Pega as perguntas ativas com suas alternativas
        $questions = VocationalQuestion::where('is_active', true)->with('options')->get();

        return view('vocational.start', compact('questions'));
    }

    // 2. Recebe as respostas e calcula o perfil
    public function submit(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            // Garante que respondeu todas (opcional, dependendo de quantas você tem)
        ]);

        $scores = [];

        // Varre cada resposta enviada
        foreach ($request->answers as $questionId => $optionId) {
            $option = VocationalOption::find($optionId);
            
            if ($option) {
                $area = $option->area;
                // Soma o ponto na área correspondente (ex: Exatas => 1, Humanas => 2...)
                if (!isset($scores[$area])) {
                    $scores[$area] = 0;
                }
                $scores[$area] += $option->points;
            }
        }

        // Se não respondeu nada válido, volta.
        if (empty($scores)) {
            return back()->with('error', 'Não foi possível calcular seu perfil. Responda as perguntas.');
        }

        // Ordena o array do maior para o menor (Ex: Exatas[5], Artes[2])
        arsort($scores);
        
        // Pega o nome da primeira área (a vencedora)
        $recommendedArea = array_key_first($scores);

        // Salva no banco de dados para o painel Admin poder ver
        VocationalResult::create([
            'user_id' => auth()->id(),
            'recommended_area' => $recommendedArea,
            'scores' => $scores,
        ]);

        return redirect()->route('vocational.result')->with('success', 'Perfil mapeado com sucesso!');
    }

    // 3. Exibe o resultado final para o aluno
    public function result()
    {
        $result = auth()->user()->vocationalResult;

        if (!$result) {
            return redirect()->route('vocational.start');
        }

        $scores = $result->scores;
        $chartLabels = array_keys($scores);
        $chartValues = array_values($scores);

        return view('vocational.result', compact('result', 'chartLabels', 'chartValues', 'scores'));
    }
}