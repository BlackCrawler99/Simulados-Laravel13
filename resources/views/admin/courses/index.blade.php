@extends('layouts.admin')

@section('title', 'Gerenciar Cursos')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Cursos Ofertados</h2>
    <p class="text-gray-600 mt-1">Gerencie a lista de cursos que os alunos podem selecionar no momento do cadastro.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- Coluna 1: Formulário Rápido de Cadastro -->
    <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Adicionar Novo</h3>
            
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome do Curso</label>
                    <input type="text" id="name" name="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: Engenharia Civil" required>
                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-bold rounded-md hover:bg-indigo-700 transition-colors">
                    Salvar Curso
                </button>
            </form>
        </div>
    </div>

    <!-- Coluna 2 e 3: Tabela de Cursos -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nome do Curso</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($courses as $course)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            
                            <!-- Formulário de Edição (Aparece ao clicar em editar) -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div id="view-mode-{{ $course->id }}" class="flex items-center">
                                    {{ $course->name }}
                                </div>
                                
                                <form id="edit-mode-{{ $course->id }}" action="{{ route('admin.courses.update', $course->id) }}" method="POST" class="hidden flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $course->name }}" class="rounded border-gray-300 py-1 text-sm w-full" required>
                                    <button type="submit" class="text-green-600 hover:text-green-800 font-bold px-2">✓</button>
                                    <button type="button" onclick="toggleEdit({{ $course->id }})" class="text-gray-400 hover:text-gray-600 font-bold px-2">✕</button>
                                </form>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <!-- Removemos as classes opacity-0 e group-hover:opacity-100 para ficar sempre visível -->
                                <div id="actions-{{ $course->id }}" class="flex justify-end gap-3">
                                    
                                    <!-- Botão Editar Inline -->
                                    <button type="button" onclick="toggleEdit({{ $course->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold transition-colors">
                                        Editar
                                    </button>
                                    
                                    <!-- Botão Excluir -->
                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este curso?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition-colors">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-8 text-center text-gray-500">
                                Nenhum curso cadastrado ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script para a edição inline -->
<script>
    function toggleEdit(id) {
        const viewMode = document.getElementById(`view-mode-${id}`);
        const editMode = document.getElementById(`edit-mode-${id}`);
        const actions = document.getElementById(`actions-${id}`);

        if (viewMode.classList.contains('hidden')) {
            viewMode.classList.remove('hidden');
            actions.classList.remove('hidden');
            editMode.classList.add('hidden');
        } else {
            viewMode.classList.add('hidden');
            actions.classList.add('hidden');
            editMode.classList.remove('hidden');
            editMode.classList.add('flex');
            // Foca no input
            editMode.querySelector('input').focus();
        }
    }
</script>
@endsection