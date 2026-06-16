<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meu Painel') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(
                in_array(\App\Models\Setting::where('key', 'module_vocational')->value('value'), ['1', 'true']) && 
                $exams->whereNotNull('completed_at')->count() > 0 && 
                !auth()->user()->vocationalResult()->exists()
            )
                <div class="mb-8 relative overflow-hidden bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl shadow-lg border border-emerald-400 text-white p-6 md:p-8 flex flex-col md:flex-row items-center justify-between group">
                    <div class="absolute -right-10 -top-10 bg-white opacity-10 w-48 h-48 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="relative z-10 flex items-start gap-6">
                        <div class="hidden md:flex p-4 bg-white/20 rounded-2xl backdrop-blur-sm">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black mb-1">Parabéns pelo seu esforço no Simulado! 🎉</h3>
                            <p class="text-emerald-50 text-sm md:text-base font-medium max-w-xl">Agora que testamos seus conhecimentos, que tal descobrirmos qual área profissional mais combina com o seu perfil? Leva só 3 minutinhos.</p>
                        </div>
                    </div>
                    
                    <div class="relative z-10 mt-6 md:mt-0 w-full md:w-auto">
                        <a href="{{ route('vocational.start') }}" class="block w-full md:w-auto text-center bg-white text-emerald-600 px-6 py-3.5 rounded-xl font-black text-sm hover:bg-emerald-50 hover:scale-105 transition-all shadow-md">
                            Descobrir minha Área
                        </a>
                    </div>
                </div>
            @endif
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
                    // 1. Puxa a porcentagem máxima de bolsa definida no painel Admin (padrão 50%)
                    $maxDiscount = \App\Models\Setting::where('key', 'max_scholarship')->value('value') ?? 50;

                    // 2. Calcula a porcentagem real de acertos (Score / Total de Questões * 100)
                    $correctCount = $bestExam->answers->where('is_correct', true)->count();
                    $totalQuestions = $bestExam->answers->count() > 0 ? $bestExam->answers->count() : 1;

                    $score100 = round(($correctCount / $totalQuestions) * 100);
                    
                    // Puxa os prêmios do banco de dados (com defaults de segurança)
                    $gift1 = \App\Models\Setting::where('key', 'reward_tier_1')->value('value') ?? '1 Figurinha';
                    $gift2 = \App\Models\Setting::where('key', 'reward_tier_2')->value('value') ?? '2 Figurinhas';
                    $gift3 = \App\Models\Setting::where('key', 'reward_tier_3')->value('value') ?? '3 Figurinhas';
                    $gift4 = \App\Models\Setting::where('key', 'reward_tier_4')->value('value') ?? '4 Figurinhas';
                    $gift5 = \App\Models\Setting::where('key', 'reward_tier_5')->value('value') ?? '5 Figurinhas';
                    $gift6 = \App\Models\Setting::where('key', 'reward_tier_6')->value('value') ?? '1 Pacote de Figurinhas';

                    // 3. Lógica de prêmios base injetando as variáveis dinâmicas
                    if ($score100 <= 30) { 
                        $baseDiscount = 30; $gift = $gift1; 
                    } elseif ($score100 <= 40) { 
                        $baseDiscount = 35; $gift = $gift2; 
                    } elseif ($score100 <= 50) { 
                        $baseDiscount = 40; $gift = $gift3; 
                    } elseif ($score100 <= 60) { 
                        $baseDiscount = 45; $gift = $gift4; 
                    } elseif ($score100 <= 99) { 
                        $baseDiscount = 50; $gift = $gift5; 
                    } else { 
                        $baseDiscount = 100; $gift = $gift6; 
                    }

                    // 4. TRAVA DE SEGURANÇA: O desconto final nunca será maior que o limite da Instituição
                    $discount = min($baseDiscount, $maxDiscount);

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

                    // Monta os arrays finais transformando em porcentagem (com proteção contra divisão por zero)
                    foreach ($dynamicAreas as $area => $stats) {
                        $radarLabels[] = $area;
                        $radarValues[] = $stats['total'] > 0 ? round(($stats['correct'] / $stats['total']) * 100) : 0;
                    }
                }
            @endphp

            {{-- MÓDULO LADO A LADO: Banner + Gráfico --}}
            @if($bestExam)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    
                    {{-- LADO ESQUERDO: Banner de Prêmio (Ocupa 2/3 da tela) --}}
                    <div class="lg:col-span-2 bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 rounded-2xl shadow-sm border border-transparent overflow-hidden text-white flex flex-col justify-center relative">
                        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="p-8 flex flex-col md:flex-row items-center justify-between h-full gap-8 relative z-10">
                            
                            <div class="flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
                                <div class="flex-shrink-0 w-28 h-28 bg-white rounded-full flex flex-col items-center justify-center text-indigo-600 shadow-xl border-4 border-indigo-300/30">
                                    <span class="text-[10px] font-black uppercase tracking-tighter text-indigo-400 mb-1">Acertos</span>
                                    <span class="text-4xl font-black leading-none">{{ $score100 }}<span class="text-2xl">%</span></span>
                                </div>
                                
                                <div>
                                    <h3 class="text-2xl font-black mb-2">Parabéns, {{ explode(' ', Auth::user()->name)[0] }}! 🎉</h3>
                                    <p class="text-indigo-100 text-sm mb-3">Com seu desempenho, você garantiu:</p>
                                    
                                    <ul class="space-y-2 font-bold text-sm sm:text-base">
                                        <li class="flex items-center justify-center sm:justify-start gap-2 bg-white/10 px-4 py-2 rounded-lg w-fit mx-auto sm:mx-0 border border-white/10 shadow-sm">
                                            <svg class="w-5 h-5 text-yellow-400 drop-shadow" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Bolsa de {{ $discount }}%
                                        </li>
                                        <li class="flex items-center justify-center sm:justify-start gap-2 bg-white/10 px-4 py-2 rounded-lg w-fit mx-auto sm:mx-0 border border-white/10 shadow-sm">
                                            <svg class="w-5 h-5 text-yellow-400 drop-shadow" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            {{ $gift }}
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @php
                                $waNumber = \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '5541998131679';
                                $waRawMessage = \App\Models\Setting::where('key', 'whatsapp_message')->value('value') ?? 'Olá! Fiz o Simulado, tive {nota}% de aproveitamento e garanti minha bolsa de {bolsa}% e {premio}.';
                                $waReplacedMessage = str_replace(['{nota}', '{bolsa}', '{premio}'], [$score100, $discount, $gift], $waRawMessage);
                                $waMessage = urlencode($waReplacedMessage);
                            @endphp

                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="px-6 py-4 bg-[#25D366] text-white font-black rounded-xl shadow-lg hover:bg-[#20bd5a] hover:scale-105 transition-all flex items-center gap-2 whitespace-nowrap border-b-4 border-[#1da851]">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-5.824 4.74-10.563 10.581-10.563 5.824 0 10.563 4.74 10.564 10.563 0 5.824-4.74 10.563-10.563 10.563z"/></svg>
                                Agendar Retirada
                            </a>
                        </div>
                    </div>

                    {{-- LADO DIREITO: Gráfico de Radar Ajustado --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center h-full">
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 text-center">Desempenho por Área</h4>
                        
                        <div class="relative h-72 w-full flex justify-center pb-4">
                            <canvas id="radarChart"></canvas>
                        </div>
                    </div>

                </div>
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
                                <p class="text-gray-500 text-sm mb-1">Nota (0 a 10)</p>
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
                        layout: {
                            padding: 10
                        },
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
                                    font: { size: 9, weight: 'bold', family: "'Figtree', sans-serif" }, // Fonte reduzida
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