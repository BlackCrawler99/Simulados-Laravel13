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
        /* // =====================================================================
        // LÓGICA ANTIGA (MÚLTIPLAS TENTATIVAS) - Guardado para uso futuro
        // =====================================================================
        // Verifica se o usuário já tem um simulado não finalizado
        $pendingExam = auth()->user()->exams()->whereNull('completed_at')->first();

        if ($pendingExam) {
            // Se tiver, apenas redireciona de volta para a prova pendente
            return redirect()->route('exams.show', $pendingExam->id)
                             ->with('status', 'Você tem um simulado em andamento. Termine-o primeiro!');
        }

        // Pega o número de questões configurado no painel admin
        $limit = \App\Models\Setting::where('key', 'exam_question_count')->value('value') ?? 20;

        $questions = \App\Models\Question::inRandomOrder()->take($limit)->get();
        if ($questions->isEmpty()) {
            return back()->with('status', 'O banco de questões está vazio no momento.');
        }

        // Se não tiver, cria um novo salvando o total de questões configurado
        $exam = auth()->user()->exams()->create([
            'total_questions' => $limit,
            'score' => null,
            'completed_at' => null,
        ]);

        // Sorteia e grampeia as questões na prova (F5 Seguro)
        foreach ($questions as $question) {
            $exam->answers()->create([
                'question_id' => $question->id,
                'option_id'   => null,
            ]);
        }

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
            
            // Se não terminou (fechou a aba sem querer), obriga a voltar pra prova antiga
            // Nota: Como as questões já foram salvas na primeira vez, o F5 aqui está 100% protegido
            return redirect()->route('exams.show', $existingExam->id)
                             ->with('status', 'Você tem um simulado em andamento. Termine-o para ver sua nota!');
        }

        // 1. Pega o limite de questões dinâmico do painel administrativo (Se não achar, usa 20)
        $limit = \App\Models\Setting::where('key', 'exam_question_count')->value('value') ?? 20;

        // 2. Busca as questões em ordem aleatória baseando-se no limite configurado
        $questions = \App\Models\Question::inRandomOrder()->take($limit)->get();

        // 3. Trava de segurança pedagógica: Evita criar uma prova vazia se o banco estiver zerado
        if ($questions->isEmpty()) {
            return back()->with('status', 'Opa! O simulado está sendo preparado. O banco de questões está vazio no momento.');
        }

        // 4. Se passou pelas validações, cria o primeiro e único registro de simulado do aluno
        $exam = auth()->user()->exams()->create([
            'total_questions' => $limit, // Congela a quantidade de questões desta tentativa aqui
            'score'           => null,
            'completed_at'    => null,
        ]);

        // 5. Vincula imediatamente as questões sorteadas gerando as respostas em branco no banco
        foreach ($questions as $question) {
            $exam->answers()->create([
                'question_id' => $question->id,
                'option_id'   => null, // Começa em branco esperando o clique do aluno
            ]);
        }

        return redirect()->route('exams.show', $exam->id);
    }

    // 2. Carrega as questões e exibe a tela da prova
    public function show(Exam $exam)
    {
        if ($exam->user_id != auth()->id()) {
            abort(403);
        }
        $exam->load('answers.question.options');
        $questions = $exam->answers->map(function ($answer) {
            $question = $answer->question;
            
            // Embaralha a coleção de alternativas dela para o aluno
            if ($question) {
                $question->setRelation('options', $question->options->shuffle());
            }   
            return $question;
        })->filter(); // O filter() remove qualquer item nulo caso alguma questão tenha sido deletada

        return view('exams.run', compact('exam', 'questions'));
    }

    // 3. Recebe o formulário, corrige a prova e dá a nota
    public function submit(Request $request, Exam $exam)
    {
        if ($exam->user_id != auth()->id()) abort(403);

        $userAnswers = $request->input('answers', []); 
        $correctCount = 0;
        
        // RECUPERA O TOTAL CONGELADO (A mágica que protege o histórico acontece aqui)
        $totalQuestions = $exam->total_questions;

        foreach ($userAnswers as $questionId => $optionId) {
            $option = Option::find($optionId);
            $isCorrect = $option ? $option->is_correct : false;

            if ($isCorrect) {
                $correctCount++;
            }

            // ATUALIZA a resposta que já existia em branco
            $exam->answers()->where('question_id', $questionId)->update([
                'option_id'  => $optionId,
                'is_correct' => $isCorrect,
            ]);
        }

        // Calcula a nota de forma dinâmica usando o total histórico daquela prova
        // Usamos max(1, $total) para evitar erro de divisão por zero caso o admin coloque 0 lá na configuração
        $score = ($correctCount / max(1, $totalQuestions)) * 10;
        
        $exam->update([
            'score' => $score,
            'completed_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('status', "Simulado finalizado! Sua nota foi: " . number_format($score, 1, ',', ''));
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
        if ($exam->user_id != auth()->id()) {
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