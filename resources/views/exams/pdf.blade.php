<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Resultado do Simulado</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        .score-box { background: #f3f4f6; padding: 15px; text-align: center; font-size: 18px; margin-bottom: 20px; font-weight: bold; border-radius: 5px; }
        .question { margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .correct { color: green; font-weight: bold; }
        .wrong { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Simulado Conhecimentos Gerais</h1>
        <p>Candidato: {{ $exam->user->name ?? 'Aluno Removido' }} | Data: {{ $exam->completed_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="score-box">
        Nota Final: {{ number_format($exam->score, 1, ',', '') }} / 10,0
    </div>
    <h2>Detalhes das Respostas</h2>
    @foreach($exam->answers as $index => $answer)
        <div class="question">
            <p><strong>Questão {{ $index + 1 }}:</strong> {{ $answer->question->statement }}</p>        
            <p>
                Sua resposta: 
                @if($answer->is_correct)
                    <span class="correct">{{ $answer->option->text }} (Correta)</span>
                @else
                    <span class="wrong">{{ $answer->option ? $answer->option->text : 'Deixou em branco' }} (Incorreta)</span>
                    
                    <br>
                    <!-- Lógica do Gabarito -->
                    @php
                        // Procura dentro das opções daquela questão a que tem is_correct == true
                        $correctOption = $answer->question->options->where('is_correct', true)->first();
                    @endphp     
                    <span style="color: #4f46e5; font-weight: bold; margin-top: 5px; display: inline-block;">
                        Gabarito: {{ $correctOption ? $correctOption->text : 'Alternativa não encontrada' }}
                    </span>
                @endif
            </p>
        </div>
    @endforeach
</body>
</html>