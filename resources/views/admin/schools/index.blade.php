@extends('layouts.admin')

@section('title', 'Rede de Colégios')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Rede de Colégios</h2>
        <p class="text-gray-600 mt-1 font-medium">Mapeamento e diagnóstico de desempenho por instituição.</p>
    </div>
    
    <div>
        <a href="#" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Cadastrar Colégio
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.colegios.index') }}" class="mb-8">
    <div class="relative max-w-lg">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nome ou cidade..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow shadow-sm">
    </div>
</form>

@if($schools->isEmpty())
    <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center">
        <div class="p-4 bg-gray-50 rounded-full mb-4">
            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-1">Nenhum colégio cadastrado</h3>
        <p class="text-gray-500 max-w-md mx-auto">Os colégios da rede parceira aparecerão aqui. Você pode importá-los via Seeder ou banco de dados.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($schools as $school)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col group">
                
                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-start mb-5">
                        <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full uppercase tracking-wider">
                            {{ $school->city }}
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-800 mb-2 leading-tight">
                        {{ $school->name }}
                    </h3>
                    
                    <div class="flex items-center text-gray-500 text-sm font-medium mt-4">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        {{ $school->classes_count }} {{ $school->classes_count === 1 ? 'turma mapeada' : 'turmas mapeadas' }}
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('admin.colegios.classes', $school->id) }}" class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-300 transition-all">
                        Gerenciar Turmas
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

            </div>
        @endforeach
    </div>
    <div class="mt-8">
        {{ $schools->appends(['search' => $search])->links() }}
    </div>
@endif
@endsection