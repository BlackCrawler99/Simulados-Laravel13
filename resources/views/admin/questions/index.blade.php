@extends('layouts.admin')

@section('title', 'Banco de Questões')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Banco de Questões</h2>
        <p class="text-gray-600 mt-1">Gerencie as perguntas e alternativas do simulado.</p>
    </div>
    
    <div class="flex gap-4">
        <!-- Botão Importar Excel -->
        <button onclick="document.getElementById('excelModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 text-white font-bold rounded-lg shadow hover:bg-green-700 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Importar Excel
        </button>
        
        <!-- Botão Criar Manual -->
        <a href="{{ route('admin.questions.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow hover:bg-indigo-700 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nova Questão
        </a>
    </div>
</div>

<!-- Modal do Excel (Escondido por padrão) -->
<div id="excelModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full relative">
        
        <!-- Botão Fechar no canto superior -->
        <button onclick="document.getElementById('excelModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <h3 class="text-xl font-bold mb-4 text-gray-800">Importar Questões via Excel</h3>
        <p class="text-sm text-gray-600 mb-4">Envie uma planilha com as colunas: <strong>Enunciado, Alternativas, Correta e Link da Imagem</strong>.</p>
        
        <!-- Botão de Baixar o Modelo -->
        <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 mb-6 flex flex-col items-center justify-center text-center">
            <h4 class="text-sm font-bold text-indigo-800 mb-2">Precisa do modelo no novo formato?</h4>
            <a href="{{ route('admin.questions.template') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded shadow hover:bg-indigo-700 transition-colors w-full">
                Baixar Modelo.xlsx
            </a>
        </div>
        
        <form action="{{ route('admin.questions.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="excel_file" accept=".xlsx, .xls, .csv" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 mb-6" required>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('excelModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Iniciar Importação</button>
            </div>
        </form>
    </div>
</div>

@if($questions->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Área</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Enunciado</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Contém Imagem?</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Gabarito</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($questions as $question)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            #{{ $question->id }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-md border border-gray-200">
                                {{ $question->area }}
                            </span>
                        </td>
                        <!-- Limita o texto do enunciado para não quebrar a tabela -->
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-md truncate" title="{{ $question->statement }}">
                            {{ Str::limit($question->statement, 80) }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            @if($question->image)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Sim
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                    Não
                                </span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                // Procura a opção correta para mostrar na tabela
                                $correct = $question->options->where('is_correct', true)->first();
                            @endphp
                            <span class="font-bold text-indigo-600" title="{{ $correct ? $correct->text : '' }}">
                                {{ $correct ? Str::limit($correct->text, 40) : 'Sem gabarito' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-3">
                            <!-- Botão Editar / Ver -->
                            <a href="{{ route('admin.questions.edit', $question->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                Ver / Editar
                            </a>

                            <!-- Botão Excluir -->
                            <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta questão permanentemente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Paginação -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $questions->links() }}
        </div>
    </div>
@else
    <div class="bg-white rounded-lg shadow p-8 text-center border border-gray-200">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma questão cadastrada</h3>
        <p class="mt-1 text-sm text-gray-500">Comece adicionando uma nova questão manualmente ou via Excel.</p>
    </div>
@endif
@endsection