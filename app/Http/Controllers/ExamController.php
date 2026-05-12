<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamController extends Controller
{
    // 1. Cria o registro do simulado e redireciona para a prova
    public function start()
    {
        /* 
        // =====================================================================
        // LÓGICA ANTIGA (MÚLTIPLAS TENTATIVAS) - Guardado para uso futuro
        // =====================================================================
        // Verifica se o usuário já tem um simulado não finalizado
        $pendingExam = auth()->user()->exams()->whereNull('completed_at')->first();

        if ($pendingExam) {
            // Se tiver, apenas redireciona de volta para a prova pendente
            return redirect()->route('exams.show', $pendingExam->id)
                             ->with('status', 'Você tem um simulado em andamento. Termine-o primeiro!');
        }

        // Se não tiver, cria um novo normalmente
        $exam = auth()->user()->exams()->create([
            'score' => null,
        ]);

        return redirect()->route('exams.show', $exam->id);
        */
        // =====================================================================
        // LÓGICA ATUAL (TENTATIVA ÚNICA)
        // =====================================================================
        // Verifica se o aluno já tem algum simulado no banco (concluído ou não)
        $existingExam = auth()->user()->exams()->first();

        if ($existingExam) {
            // Se já tem nota/terminou, bloqueia e manda de volta pra dashboard
            if ($existingExam->completed_at !== null) {
                return redirect()->route('dashboard')
                                 ->with('status', 'Você já realizou o seu simulado. Apenas uma tentativa é permitida!');
            }
            
            // Se não terminou (fechou a aba sem querer), obriga a voltar pra prova
            return redirect()->route('exams.show', $existingExam->id)
                             ->with('status', 'Você tem um simulado em andamento. Termine-o para ver sua nota!');
        }

        // Se não tem nenhum registro, cria o primeiro e único simulado
        $exam = auth()->user()->exams()->create([
            'score' => null,
        ]);

        return redirect()->route('exams.show', $exam->id);
    }

    // 2. Carrega as questões e exibe a tela da prova
    public function show(Exam $exam)
    {
        if ($exam->user_id != auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        // Puxa 5 questões aleatórias
        $questions = Question::with('options')->inRandomOrder()->take(5)->get();

        // Percorre cada questão e embaralha a coleção de alternativas dela
        $questions->each(function ($question) {
            $question->setRelation('options', $question->options->shuffle());
        });

        return view('exams.run', compact('exam', 'questions'));
    }

    // 3. Recebe o formulário, corrige a prova e dá a nota
    public function submit(Request $request, Exam $exam)
    {
        if ($exam->user_id != auth()->id()) {
            abort(403);
        }

        // Vem do formulário no formato: ['id_da_questao' => 'id_da_alternativa']
        $userAnswers = $request->input('answers', []); 
        $correctCount = 0;
        $totalQuestions = 5;

        foreach ($userAnswers as $questionId => $optionId) {
            // Busca a alternativa escolhida para ver se é a certa
            $option = Option::find($optionId);
            $isCorrect = $option ? $option->is_correct : false;

            if ($isCorrect) {
                $correctCount++;
            }

            // Salva a resposta no banco para o aluno poder revisar depois
            $exam->answers()->create([
                'question_id' => $questionId,
                'option_id'   => $optionId,
                'is_correct'  => $isCorrect,
            ]);
        }

        // Calcula a nota (ex: acertou 3 de 5 = nota 6)
        $score = ($correctCount / $totalQuestions) * 10;
        
        $exam->update([
            'score' => $score,
            'completed_at' => now(),
        ]);

        // Manda de volta pro painel do aluno com a nota
        return redirect()->route('dashboard')->with('status', "Simulado finalizado! Sua nota foi: {$score}");
    }

    public function downloadPdf(Exam $exam)
    {
        // Trava: verifica se o exame pertence ao aluno E se já foi finalizado
        if ($exam->user_id != auth()->id() || $exam->completed_at == null) {
            abort(403, 'Acesso negado ou simulado incompleto.');
        }

        // Carrega as respostas do aluno, trazendo junto as informações da questão e da alternativa
        $exam->load('user', 'answers.question.options', 'answers.option');
        // Gera o PDF apontando para uma view que vamos criar
        $pdf = Pdf::loadView('exams.pdf', compact('exam'));
        
        // Retorna o arquivo para download
        return $pdf->download("resultado-simulado-{$exam->id}.pdf");
    }

    public function result($id)
    {
        // Busca o simulado e já carrega as respostas, as questões e as alternativas
        // Adapte os nomes 'answers', 'question' e 'option' para os nomes reais dos seus relacionamentos, se forem diferentes
        $exam = \App\Models\Exam::with(['answers.question.options'])->findOrFail($id);

        // Trava de segurança: o aluno logado só pode ver o PRÓPRIO simulado
        if ($exam->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para visualizar este simulado.');
        }

        // Calcula a pontuação dinamicamente (ou você pode puxar direto se já tiver uma coluna 'score' no seu BD)
        $totalQuestions = $exam->answers->count();
        $correctAnswers = $exam->answers->filter(function ($answer) {
            // Verifica se a opção que o aluno marcou é a correta
            $selectedOption = $answer->question->options->where('id', $answer->option_id)->first();
            return $selectedOption && $selectedOption->is_correct;
        })->count();

        return view('exams.result', compact('exam', 'totalQuestions', 'correctAnswers'));
    }

}