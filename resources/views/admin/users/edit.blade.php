@extends('layouts.admin')

@section('title', 'Editar Usuário')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Editar Cadastro</h2>
        <p class="text-gray-600 mt-1">Atualize os dados de {{ $user->name }}.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-lg shadow-sm hover:bg-gray-300 transition-colors">
        Voltar
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
    
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Obrigatório para atualização -->

        <!-- Nome -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nome Completo</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- E-mail -->
        <div class="mb-8">
            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Endereço de E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 transition-colors">
                Salvar Alterações
            </button>
        </div>
    </form>

</div>
@endsection