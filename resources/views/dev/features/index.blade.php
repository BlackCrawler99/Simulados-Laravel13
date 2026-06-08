@extends('layouts.admin')

@section('title', 'Gerenciar Módulos Premium')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-black text-gray-800 tracking-tight">Módulos do Sistema</h2>
    <p class="text-gray-600 mt-1 font-medium">Ative ou desative recursos premium. Esta configuração sobrepõe as telas do cliente.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('admin.features.store') }}" method="POST">
        @csrf

        <div class="space-y-4">
            <label class="flex items-center space-x-4 cursor-pointer p-5 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <input type="checkbox" name="module_vocational" class="form-checkbox h-6 w-6 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" 
                       {{ $features['module_vocational'] ? 'checked' : '' }}>
                <div>
                    <span class="text-gray-900 font-bold text-lg block">Teste Vocacional</span>
                    <span class="text-gray-500 text-sm">Habilita a aba de análise de perfil e escolha de cursos para os alunos desta instituição.</span>
                </div>
            </label>

            <label class="flex items-center space-x-4 cursor-pointer p-5 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <input type="checkbox" name="module_school_reports" class="form-checkbox h-6 w-6 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" 
                       {{ $features['module_school_reports'] ? 'checked' : '' }}>
                <div>
                    <span class="text-gray-900 font-bold text-lg block">Relatórios de Escolas (Drilldown)</span>
                    <span class="text-gray-500 text-sm">Permite aos diretores da escola verem estatísticas aprofundadas por turmas e cadernos.</span>
                </div>
            </label>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition-colors shadow-sm">
                Salvar Configurações
            </button>
        </div>
    </form>
</div>
@endsection