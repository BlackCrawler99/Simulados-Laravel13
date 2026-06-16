@extends('layouts.admin')

@section('title', 'Painel Vocacional')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-black text-gray-800 tracking-tight">Teste Vocacional</h2>
    <p class="text-gray-600 mt-1 font-medium">Gestão de carreiras e mapeamento do perfil dos alunos.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('admin.vocational.questions') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between min-h-[160px]">
        <div class="absolute -right-6 -top-6 bg-indigo-50 w-32 h-32 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
        
        <div class="relative z-10 flex justify-between items-start">
            <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <div class="relative z-10 mt-4">
            <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">Banco de Questões</h3>
            <p class="text-sm text-gray-500 font-medium mt-1">
                {{ $questionsCount }} {{ $questionsCount == 1 ? 'pergunta cadastrada' : 'perguntas cadastradas' }}
            </p>
        </div>
    </a>

    <a href="{{ route('admin.vocational.students') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-emerald-300 hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between min-h-[160px]">
        <div class="absolute -right-6 -top-6 bg-emerald-50 w-32 h-32 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
        
        <div class="relative z-10 flex justify-between items-start">
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>

        <div class="relative z-10 mt-4">
            <h3 class="text-xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">Desempenho dos Alunos</h3>
            <p class="text-sm text-gray-500 font-medium mt-1">
                {{ $studentsCount }} {{ $studentsCount == 1 ? 'teste concluído' : 'testes concluídos' }}
            </p>
        </div>
    </a>
</div>
@endsection