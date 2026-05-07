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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-8 flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Pronto para testar seus conhecimentos?</h3>
                        <p class="text-gray-600 mt-2">Inicie um novo simulado com questões aleatórias e prepare-se para o ENEM.</p>
                    </div>
                    <form method="POST" action="{{ route('exams.start') }}" class="mt-4 md:mt-0">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow hover:bg-indigo-700 transition-colors">
                            Iniciar Novo Simulado
                        </button>
                    </form>
                </div>
            </div>

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
                            
                            {{-- Cabeçalho do Card --}}
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
                            
                            {{-- Corpo do Card --}}
                            <div class="px-6 py-6 flex-grow text-center">
                                <p class="text-gray-500 text-sm mb-1">Nota Final</p>
                                <p class="text-4xl font-extrabold {{ $exam->score >= 6 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $exam->score !== null ? number_format($exam->score, 1, ',', '') : '--' }}
                                </p>
                            </div>
                            {{-- Rodapé do Card (Botões de Ação) --}}
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-2">
                                @if($exam->score !== null)
                                    {{-- Botões para Provas Concluídas --}}
                                    <a href="#" class="flex-1 text-center bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                                        Rever
                                    </a>
                                    {{-- Já inserindo a rota do PDF que criaremos no Passo 4 --}}
                                    <a href="{{ route('exams.pdf', $exam->id) }}" class="flex-1 text-center bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        PDF
                                    </a>
                                @else
                                    {{-- Botão Único para Provas Incompletas --}}
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
</x-app-layout>