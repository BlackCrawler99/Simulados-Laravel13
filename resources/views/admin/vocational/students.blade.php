@extends('layouts.admin')

@section('title', 'Alunos - Teste Vocacional')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <nav class="flex mb-4 text-gray-500 text-sm font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.vocational.index') }}" class="hover:text-emerald-600 flex items-center">
                        Painel Vocacional
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 md:ml-2 text-gray-400">Resultados</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Desempenho dos Alunos</h2>
        <p class="text-gray-600 mt-1 font-medium">Veja quem já concluiu o mapeamento de perfil.</p>
    </div>
</div>

@if($results->isEmpty())
    <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center">
        <div class="p-4 bg-emerald-50 rounded-full mb-4 text-emerald-500">
            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-1">Nenhum teste concluído</h3>
        <p class="text-gray-500 max-w-md mx-auto">Os alunos que finalizarem o mapeamento vocacional aparecerão aqui automaticamente.</p>
    </div>
@else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Aluno</th>
                    <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Data do Teste</th>
                    <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Área Recomendada</th>
                    <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider text-right">Ação</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($results as $result)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-800">{{ $result->user->name ?? 'Usuário Removido' }}</span>
                                <span class="text-xs text-gray-500">{{ $result->user->email ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-medium">
                            {{ $result->created_at->format('d/m/Y \à\s H:i') }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[11px] font-black uppercase tracking-widest rounded-full border border-emerald-100">
                                {{ $result->recommended_area }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('admin.vocational.report', $result->id) }}" class="text-emerald-600 hover:text-emerald-900 text-sm font-bold flex items-center justify-end gap-1">
                                Ver Relatório
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $results->links() }}
    </div>
@endif
@endsection