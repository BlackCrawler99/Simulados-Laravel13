@extends('layouts.admin')

@section('title', 'Gestão do Teste Vocacional')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Teste Vocacional</h2>
        <p class="text-gray-600 mt-1 font-medium">Gestão de perguntas e mapeamento de perfis.</p>
    </div>
    
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.vocational.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            
            <a href="{{ route('admin.vocational.template') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold bg-indigo-50 px-4 py-2.5 rounded-xl transition-colors" title="Baixar Modelo CSV">
                Baixar Modelo
            </a>

            <div class="relative overflow-hidden inline-block">
                <button type="button" class="bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-50 transition-colors flex items-center gap-2 shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Importar CSV
                </button>
                <input type="file" name="file" accept=".csv" onchange="this.form.submit()" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
            </div>
        </form>

        <a href="{{ route('admin.vocational.create') }}" class="bg-gray-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-600 transition-colors flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova Pergunta
        </a>
    </div>
</div>

@if(session('status'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-medium">
        {{ session('status') }}
    </div>
@endif

@if($questions->isEmpty())
    <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center">
        <div class="p-4 bg-gray-50 rounded-full mb-4">
            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-1">Nenhuma pergunta cadastrada</h3>
        <p class="text-gray-500 max-w-md mx-auto">Cadastre manualmente ou baixe o modelo CSV para importar as perguntas e alternativas do seu teste vocacional.</p>
    </div>
@else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Pergunta</th>
                    <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Alternativas</th>
                    <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($questions as $question)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <p class="text-sm font-bold text-gray-800 line-clamp-2">{{ $question->text }}</p>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-medium">
                            {{ $question->options_count }} opções
                        </td>
                        <td class="py-4 px-6">
                            @if($question->is_active)
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[11px] font-black uppercase tracking-widest rounded-full">Ativo</span>
                            @else
                                <span class="px-3 py-1 bg-red-50 text-red-600 text-[11px] font-black uppercase tracking-widest rounded-full">Inativo</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm font-bold">Editar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $questions->links() }}
    </div>
@endif
@endsection