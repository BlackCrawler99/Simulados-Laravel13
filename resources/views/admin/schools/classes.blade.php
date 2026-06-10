@extends('layouts.admin')

@section('title', 'Turmas - ' . $school->name)

@section('content')
<div class="mb-8">
    <nav class="flex mb-4 text-gray-500 text-sm font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.colegios.index') }}" class="hover:text-indigo-600 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Rede de Colégios
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 md:ml-2 text-gray-400">{{ $school->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">{{ $school->name }}</h2>
            <p class="text-gray-600 mt-1 font-medium italic">{{ $school->city }} — Gerenciamento de Turmas e Grupos.</p>
        </div>
        
        <div>
            <a href="#" class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-50 transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Turma
            </a>
        </div>
    </div>
</div>

@if($classes->isEmpty())
    <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center">
        <div class="p-4 bg-indigo-50 rounded-full mb-4">
            <svg class="h-12 w-12 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-1">Nenhuma turma encontrada</h3>
        <p class="text-gray-500">Cadastre as turmas (ex: 3º Ano A) para começar a vincular os alunos e gerar relatórios.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($classes as $class)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:border-indigo-300 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2.5 bg-indigo-50 rounded-lg text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 px-2 py-1 rounded">
                        {{ $class->period }}
                    </span>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $class->name }}</h3>
                <p class="text-sm text-gray-500 font-medium mb-6">
                    {{ $class->users_count }} {{ $class->users_count == 1 ? 'aluno vinculado' : 'alunos vinculados' }}
                </p>

                <a href="{{ route('admin.colegios.report', [$school->id, $class->id]) }}" class="mt-auto flex items-center justify-center gap-2 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-600 transition-all shadow-sm">
                    Ver Desempenho
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </a>
            </div>
        @endforeach
    </div>
@endif
@endsection