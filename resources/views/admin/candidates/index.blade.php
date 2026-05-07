@extends('layouts.admin')

@section('title', 'Leads')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Candidatos</h2>
        <p class="text-gray-600 mt-1">Lista de candidatos.</p>
    </div>
</div>

<!-- Barra de Busca -->
<form method="GET" action="{{ route('admin.candidates.index') }}" class="mb-6 flex space-x-3">
    <!-- Preservamos os parâmetros de ordenação na hora da busca -->
    @if(request('sort'))
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
    @endif

    <div class="flex-1">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome, e-mail ou telefone..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-2.5 text-gray-900 border">
    </div>
    
    <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-md shadow-sm hover:bg-indigo-700 transition-colors font-medium">
        Buscar
    </button>
    
    @if(request('search'))
        <a href="{{ route('admin.candidates.index') }}" class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded-md shadow-sm hover:bg-gray-50 transition-colors font-medium">
            Limpar
        </a>
    @endif
</form>

<!-- Container da Tabela -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-4">
    <table class="min-w-full divide-y divide-gray-200 text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-indigo-600">
                    <a href="{{ route('admin.candidates.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                        <span>Candidato</span>
                        @if(request('sort') === 'name')
                            <span>{!! request('direction') == 'asc' ? '↑' : '↓' !!}</span>
                        @endif
                    </a>
                </th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-indigo-600">
                    <a href="{{ route('admin.candidates.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                        <span>Contato</span>
                        @if(request('sort') === 'email')
                            <span>{!! request('direction') == 'asc' ? '↑' : '↓' !!}</span>
                        @endif
                    </a>
                </th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-indigo-600">
                    <a href="{{ route('admin.candidates.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                        <span>Data de Cadastro</span>
                        @if(request('sort') === 'created_at' || !request('sort'))
                            <span>{!! request('direction') == 'asc' ? '↑' : '↓' !!}</span>
                        @endif
                    </a>
                </th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Desempenho
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($candidates as $candidate)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $candidate->name }}</div>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $candidate->email }}</div>
                        <div class="text-sm font-medium text-indigo-600">{{ $candidate->phone ?? 'Não informado' }}</div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($candidate->exams_count > 0)
                            <div class="text-sm text-gray-900">
                                <strong>{{ $candidate->exams_count }}</strong> simulado(s) feito(s)
                            </div>
                            <div class="text-sm text-gray-500">
                                Média: 
                                <span class="font-bold {{ $candidate->exams_avg_score >= 6 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ number_format($candidate->exams_avg_score, 1, ',', '') }}
                                </span>
                            </div>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Nenhum simulado
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        Nenhum candidato encontrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginação do Laravel usando Tailwind -->
<div class="mt-4">
    {{ $candidates->links() }}
</div>
@endsection