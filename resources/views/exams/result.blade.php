<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Resultado do Simulado #{{ $exam->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col md:flex-row items-center justify-between gap-6 border-l-4 {{ $correctAnswers >= ($totalQuestions / 2) ? 'border-green-500' : 'border-red-500' }}">
                
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-bold text-gray-800">Seu Desempenho</h3>
                    <p class="text-gray-600 mt-1">Realizado em {{ $exam->created_at->format('d/m/Y \à\s H:i') }}</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    
                    <div class="text-center bg-gray-50 rounded-xl p-4 min-w-[150px] shadow-inner border border-gray-200 flex-1">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total de Acertos</span>
                        <div>
                            <span class="text-4xl font-extrabold text-blue-600">{{ $correctAnswers }}</span>
                            <span class="text-gray-400 font-medium text-lg">/ {{ $totalQuestions }}</span>
                        </div>
                    </div>

                    <div class="text-center bg-gray-50 rounded-xl p-4 min-w-[150px] shadow-inner border border-gray-200 flex-1">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Nota Final</span>
                        <div>
                            <span class="text-4xl font-extrabold text-green-600">{{ number_format($exam->score, 1, ',', '') }}</span>
                            <span class="text-gray-400 font-medium text-lg">/ 10</span>
                        </div>
                    </div>

                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mt-8 mb-4">Gabarito Detalhado</h3>

            <div class="space-y-6">
                @foreach($exam->answers as $index => $answer)
                    @php
                        $question = $answer->question;
                        $selectedOption = $question->options->where('id', $answer->option_id)->first();
                        $correctOption = $question->options->where('is_correct', true)->first();
                        $isCorrect = $selectedOption && $selectedOption->is_correct;
                    @endphp

                    <div class="bg-white shadow-sm sm:rounded-lg p-6 border {{ $isCorrect ? 'border-green-200' : 'border-red-200' }}">
                        
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <h4 class="text-lg font-bold text-gray-800">
                                <span class="text-gray-400 mr-2">Q{{ $index + 1 }}.</span> 
                                {{ $question->statement }}
                            </h4>
                            
                            @if($isCorrect)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full whitespace-nowrap border border-green-200">ACERTOU</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full whitespace-nowrap border border-red-200">ERROU</span>
                            @endif
                        </div>

                        @if($question->image)
                            <div class="mb-6">
                                @if(Str::startsWith($question->image, ['http://', 'https://']))
                                    <img src="{{ $question->image }}" alt="Imagem da questão" class="max-h-64 rounded border border-gray-200 shadow-sm">
                                @else
                                    <img src="{{ asset('storage/' . $question->image) }}" alt="Imagem da questão" class="max-h-64 rounded border border-gray-200 shadow-sm">
                                @endif
                            </div>
                        @endif

                        <div class="space-y-2 mt-4 pl-8">
                            @foreach($question->options as $option)
                                @php
                                    $isStudentChoice = $selectedOption && $selectedOption->id === $option->id;
                                    $isThisCorrect = $option->is_correct;
                                    
                                    // Define as cores de fundo
                                    $bgClass = 'bg-gray-50 text-gray-600';
                                    $borderClass = 'border-gray-200';
                                    $icon = '';

                                    if ($isThisCorrect) {
                                        $bgClass = 'bg-green-50 text-green-800 font-bold shadow-sm';
                                        $borderClass = 'border-green-400';
                                        $icon = '✓';
                                    } elseif ($isStudentChoice && !$isThisCorrect) {
                                        $bgClass = 'bg-red-50 text-red-800 line-through opacity-80';
                                        $borderClass = 'border-red-300';
                                        $icon = '✕';
                                    }
                                @endphp

                                <div class="flex items-center p-3 rounded-lg border {{ $bgClass }} {{ $borderClass }} transition-colors">
                                    <div class="w-5 h-5 rounded-full border mr-3 flex items-center justify-center flex-shrink-0 {{ $isStudentChoice ? 'border-indigo-500 bg-indigo-500 text-white' : 'border-gray-400 bg-white' }}">
                                        @if($isStudentChoice) <span class="text-xs">●</span> @endif
                                    </div>
                                    
                                    <span class="flex-1">{{ $option->text }}</span>
                                    
                                    @if($icon)
                                        <span class="ml-2 {{ $isThisCorrect ? 'text-green-600' : 'text-red-600' }} font-bold text-lg">{{ $icon }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center pb-8">
                <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-full shadow-lg hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                    Voltar para o Painel
                </a>
            </div>

        </div>
    </div>
</x-app-layout>