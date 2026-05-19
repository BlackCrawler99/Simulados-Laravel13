<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meu Painel') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alerta de Sucesso após finalizar o simulado --}}
            @if(session('status'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg shadow-sm" role="alert">
                    <p class="font-bold">Sucesso!</p>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            {{-- Área de Ação Principal --}}
            @if($exams->whereNotNull('completed_at')->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-8 flex flex-col md:flex-row items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Pronto para testar seus conhecimentos?</h3>
                            <p class="text-gray-600 mt-2">Inicie seu simulado com questões aleatórias e prepare-se para o ENEM.</p>
                        </div>
                        <form method="POST" action="{{ route('exams.start') }}" class="mt-4 md:mt-0">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow hover:bg-indigo-700 transition-colors">
                                Iniciar Simulado
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Lógica de Premiação e Gráfico de Teia 100% Dinâmico --}}
            @php
                $bestExam = $exams->whereNotNull('score')->first();
                $discount = 0;
                $gift = '';
                $score100 = 0;

                // Arrays que vão alimentar o Chart.js dinamicamente
                $radarLabels = [];
                $radarValues = [];

                if ($bestExam) {
                    $score100 = $bestExam->score * 10;
                    
                    // Lógica de prêmios
                    if ($score100 <= 30) { $discount = 30; $gift = '1 Figurinha'; } 
                    elseif ($score100 <= 40) { $discount = 35; $gift = '2 Figurinhas'; } 
                    elseif ($score100 <= 50) { $discount = 40; $gift = '3 Figurinhas'; } 
                    elseif ($score100 <= 60) { $discount = 45; $gift = '4 Figurinhas'; } 
                    elseif ($score100 <= 99) { $discount = 50; $gift = '5 Figurinhas'; } 
                    else { $discount = 60; $gift = '1 Pacote de Figurinhas'; }

                    // Carrega as respostas e questões do simulado
                    $bestExam->load('answers.question');

                    $dynamicAreas = [];

                    // O sistema varre as questões e descobre os nomes das áreas sozinho
                    foreach ($bestExam->answers as $answer) {
                        if ($answer->question) {
                            $areaName = trim($answer->question->area); 
                            
                            if (!isset($dynamicAreas[$areaName])) {
                                $dynamicAreas[$areaName] = ['total' => 0, 'correct' => 0];
                            }

                            $dynamicAreas[$areaName]['total']++;
                            if ($answer->is_correct) {
                                $dynamicAreas[$areaName]['correct']++;
                            }
                        }
                    }

                    // Monta os arrays finais transformando em porcentagem
                    foreach ($dynamicAreas as $area => $stats) {
                        $radarLabels[] = $area;
                        $radarValues[] = round(($stats['correct'] / $stats['total']) * 100);
                    }
                }
            @endphp

            {{-- MÓDULO LADO A LADO: Banner + Gráfico --}}
            @if($bestExam)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    
                    {{-- LADO ESQUERDO: Banner de Prêmio (Ocupa 2/3 da tela) --}}
                    <div class="lg:col-span-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-sm border border-transparent overflow-hidden text-white flex flex-col justify-center">
                        <div class="p-8 flex flex-col md:flex-row items-center justify-between h-full gap-6">
                            
                            <div class="flex items-center gap-6">
                                <div class="flex-shrink-0 w-24 h-24 bg-white rounded-full flex flex-col items-center justify-center text-indigo-600 shadow-inner">
                                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wide">Sua Nota</span>
                                    <span class="text-3xl font-extrabold">{{ $score100 }}</span>
                                </div>
                                
                                <div>
                                    <h3 class="text-2xl font-bold mb-1">Parabéns, {{ explode(' ', Auth::user()->name)[0] }}! 🎉</h3>
                                    <p class="text-indigo-100 text-sm">Com base no seu melhor desempenho, você garantiu:</p>
                                    <ul class="mt-2 space-y-1 font-semibold text-lg">
                                        <li class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Bolsa de {{ $discount }}%
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            {{ $gift }}
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @php
                                $waMessage = urlencode("Olá! Fiz o Simulado ENEM da UniEnsino, tirei a nota {$score100} e garanti minha bolsa de {$discount}% e {$gift}. Gostaria de agendar a retirada e minha matrícula!");
                                $waNumber = "5541998131679"; 
                            @endphp
                            
                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="px-6 py-3 bg-green-500 text-white font-bold rounded-full shadow-lg hover:bg-green-400 hover:scale-105 transition-all flex items-center gap-2 text-base whitespace-nowrap">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-5.824 4.74-10.563 10.581-10.563 5.824 0 10.563 4.74 10.564 10.563 0 5.824-4.74 10.563-10.563 10.563z"/></svg>
                                Agendar Retirada
                            </a>
                        </div>
                    </div>

                    {{-- LADO DIREITO: Gráfico de Radar (Ocupa 1/3 da tela) --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center h-full">
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2 text-center">Desempenho por Área</h4>
                        
                        <div class="relative h-48 w-full flex justify-center">
                            <canvas id="radarChart"></canvas>
                        </div>
                    </div>

                </div> {{-- Fecha o grid principal contendo os dois blocos --}}
            @endif

            {{-- Histórico de Avaliações em Cards --}}
            <h3 class="text-xl font-bold text-gray-800 mb-4">Meu Histórico de Avaliações</h3>
            
            @if($exams->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow-sm text-center text-gray-500 border border-gray-200">
                    Você ainda não realizou nenhum simulado.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($exams as $exam)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col transition-transform hover:-translate-y-1 hover:shadow-md">
                            
                            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <span class="text-sm text-gray-500">
                                    {{ $exam->created_at->format('d/m/Y \à\s H:i') }}
                                </span>
                                @if($exam->score !== null)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">Concluído</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">Incompleto</span>
                                @endif
                            </div>
                            
                            <div class="px-6 py-6 flex-grow text-center">
                                <p class="text-gray-500 text-sm mb-1">Nota Final</p>
                                <p class="text-4xl font-extrabold {{ $exam->score >= 6 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $exam->score !== null ? number_format($exam->score, 1, ',', '') : '--' }}
                                </p>
                            </div>
                            
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-2">
                                @if($exam->score !== null)
                                    <a href="{{ route('exams.result', $exam->id) }}" class="flex-1 text-center bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                                        Rever
                                    </a>
                                    <a href="{{ route('exams.pdf', $exam->id) }}" class="flex-1 text-center bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        PDF
                                    </a>
                                @else
                                    <a href="{{ route('exams.show', $exam->id) }}" class="w-full text-center bg-yellow-500 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-yellow-600 transition-colors">
                                        Continuar Simulado
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    {{-- Script do Gráfico de Radar 100% Dinâmico --}}
    @if($bestExam && count($radarLabels) > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctxRadar = document.getElementById('radarChart').getContext('2d');
                
                new Chart(ctxRadar, {
                    type: 'radar',
                    data: {
                        labels: {!! json_encode($radarLabels) !!},
                        datasets: [{
                            label: 'Aproveitamento',
                            data: {!! json_encode($radarValues) !!}, 
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: '#4f46e5',
                            borderWidth: 2,
                            pointBackgroundColor: '#4f46e5',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#4f46e5',
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) { return context.raw + '%'; }
                                }
                            }
                        },
                        scales: {
                            r: {
                                angleLines: { color: 'rgba(0, 0, 0, 0.05)' },
                                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                                pointLabels: {
                                    font: { size: 10, weight: 'bold', family: "'Figtree', sans-serif" },
                                    color: '#6b7280'
                                },
                                ticks: {
                                    display: false,
                                    min: 0,
                                    max: 100,
                                    stepSize: 25
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endif
</x-app-layout>