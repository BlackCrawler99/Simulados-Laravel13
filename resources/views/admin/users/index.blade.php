@extends('layouts.admin')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Controle de Acessos e Usuários</h2>
    <p class="text-gray-600 mt-1">Gerencie permissões, edite cadastros e pesquise por usuários.</p>
</div>

<!-- Barra de Pesquisa -->
<form action="{{ route('admin.users.index') }}" method="GET" class="mb-6 flex gap-4 max-w-2xl">
    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nome ou e-mail..." class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow hover:bg-indigo-700 transition-colors">
        Pesquisar
    </button>
    @if(request('search'))
        <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors">
            Limpar
        </a>
    @endif
</form>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nome / Email</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nível</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->is_super_admin)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-blue rounded-full bg-blue-100 text-blue-600 border border-blue-200">
                                Desenvolvedor
                            </span>
                        @elseif($user->is_admin)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                Administrador
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                Aluno/Candidato
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-4">
                            
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 font-bold">
                                Editar
                            </a>

                            @if(auth()->id() !== $user->id)
                                
                                @if(!$user->is_super_admin)
                                    
                                    {{-- Botão Toggle Admin --}}
                                    <form action="{{ route('admin.users.toggle-admin', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $user->is_admin ? 'text-orange-600 hover:text-orange-900' : 'text-indigo-600 hover:text-indigo-900' }} font-bold">
                                            {{ $user->is_admin ? 'Remover Admin' : 'Tornar Admin' }}
                                        </button>
                                    </form>

                                    {{-- Botão Excluir --}}
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('ATENÇÃO: Isso excluirá o usuário e TODOS os seus simulados. Confirmar?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Excluir</button>
                                    </form>

                                @else
                                    <span class="text-gray-400 text-xs uppercase bg-gray-100 px-2 py-1 rounded cursor-not-allowed" title="Contas de Desenvolvedor não podem ser alteradas ou excluídas por aqui.">
                                        Protegido
                                    </span>
                                @endif

                            @else
                                <span class="text-gray-400 text-xs uppercase cursor-not-allowed bg-gray-100 px-2 py-1 rounded" title="Você não pode alterar seu próprio privilégio">
                                    Este é você
                                </span>
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                        Nenhum usuário encontrado na pesquisa.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
</div>
@endsection