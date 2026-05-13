@extends('layouts.admin')

@section('title', 'Configurações')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Configurações do Sistema</h2>
    <p class="text-gray-600 mt-1">Gerencie a aparência e comportamento do portal.</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 max-w-4xl">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Tema da Página Inicial (Landing Page)</h3>
    
    <form action="{{ route('admin.settings.update-theme') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <!-- Opção Tema Padrão -->
            <label class="cursor-pointer relative">
                <input type="radio" name="theme" value="default" class="peer sr-only" {{ $activeTheme === 'default' ? 'checked' : '' }}>
                <div class="rounded-lg border-2 p-6 transition-all {{ $activeTheme === 'default' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300' }} peer-checked:border-indigo-600 peer-checked:bg-indigo-50">
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Tema Padrão</h4>
                    <p class="text-sm text-gray-600">O layout original com a identidade visual clássica da instituição.</p>
                    
                    <div class="absolute top-4 right-4 hidden peer-checked:block text-indigo-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </label>

            <!-- Opção Tema Copa -->
            <label class="cursor-pointer relative">
                <input type="radio" name="theme" value="copa" class="peer sr-only" {{ $activeTheme === 'copa' ? 'checked' : '' }}>
                <div class="rounded-lg border-2 p-6 transition-all {{ $activeTheme === 'copa' ? 'border-green-600 bg-green-50' : 'border-gray-200 hover:border-green-300' }} peer-checked:border-green-600 peer-checked:bg-green-50">
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Tema Copa do Mundo 🇧🇷</h4>
                    <p class="text-sm text-gray-600">Landing page especial com cores do Brasil e chamadas focadas no mundial.</p>
                    
                    <div class="absolute top-4 right-4 hidden peer-checked:block text-green-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </label>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gray-900 text-white font-bold rounded-lg shadow-lg hover:bg-gray-800 transition-colors">
                Salvar Tema Ativo
            </button>
        </div>
    </form>
</div>
@endsection