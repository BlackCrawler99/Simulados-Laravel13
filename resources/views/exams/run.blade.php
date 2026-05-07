<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Simulado de Conhecimentos Gerais') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Card de Instruções -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-indigo-500">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-2">Instruções:</h3>
                    <ul class="list-disc list-inside text-gray-600 space-y-1">
                        <li>Leia atentamente cada questão antes de responder.</li>
                        <li>Todas as questões são de múltipla escolha com apenas uma alternativa correta.</li>
                        <li>Certifique-se de marcar todas as respostas antes de finalizar.</li>
                    </ul>
                </div>
            </div>

            <!-- Formulário da Prova -->
            <form method="POST" action="{{ route('exams.submit', $exam->id) }}">
                @csrf

                @foreach($questions as $index => $question)
                    <div class="bg-white shadow-sm sm:rounded-lg mb-8 overflow-hidden">
                        
                        <!-- Cabeçalho da Questão -->
                        <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                            <h3 class="text-lg font-medium text-gray-900">
                                <span class="font-extrabold text-indigo-600 mr-2">Questão {{ $index + 1 }}</span> 
                            </h3>
                        </div>

                        <!-- Enunciado e Alternativas -->
                        <div class="p-6">
                            <p class="text-gray-800 text-lg mb-6">{{ $question->statement }}</p>
                            
                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                    <!-- Transformamos a label em um "botão" clicável gigante -->
                                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-indigo-50 hover:border-indigo-400 transition-all duration-200">
                                        <input type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               value="{{ $option->id }}" 
                                               required
                                               class="w-5 h-5 text-indigo-600 bg-white border-gray-300 focus:ring-indigo-500">
                                        <span class="ml-3 text-gray-700 text-base">{{ $option->text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Botão de Envio -->
                <div class="flex justify-end mt-8 mb-12">
                    <button type="submit" class="px-8 py-4 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all text-lg w-full sm:w-auto flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Finalizar Simulado
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>