@extends('layouts.admin')

@section('title', 'Editar Questão')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Visualizar/Editar Questão #{{ $question->id }}</h2>
    </div>
    <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-lg shadow-sm hover:bg-gray-300 transition-colors">
        Voltar
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-4xl">
    
    <form action="{{ route('admin.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Área de Conhecimento -->
        <div class="mb-6">
            <label for="area" class="block text-sm font-bold text-gray-700 mb-2">Área de Conhecimento</label>
            
            <input type="text" name="area" id="area" list="areas-list"
                value="{{ isset($question) ? $question->area : old('area', 'Geral') }}"
                placeholder="Ex: Matemática, Ciências Humanas..."
                class="block w-full max-w-md rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-medium">
            
            <datalist id="areas-list">
                @foreach($areas as $areaOption)
                    <option value="{{ $areaOption }}">
                @endforeach
            </datalist>
            
            <p class="text-xs text-gray-400 mt-1">Digite uma nova área para criá-la ou selecione uma existente na lista.</p>
        </div>
        
        <!-- Enunciado -->
        <div class="mb-6">
            <label for="statement" class="block text-sm font-bold text-gray-700 mb-2">Enunciado da Questão *</label>
            <textarea id="statement" name="statement" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('statement', $question->statement) }}</textarea>
        </div>

        <!-- Imagem de Apoio -->
        <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-lg">
            <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Imagem de Apoio (Deixe em branco para manter a atual)</label>
            
            @if($question->image)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1">Imagem atual:</p>
                    
                    <!-- Verifica se começa com http. Se sim, é link externo. Se não, é upload do sistema -->
                    @if(Str::startsWith($question->image, ['http://', 'https://']))
                        <img src="{{ $question->image }}" alt="Imagem da questão" class="max-h-48 rounded border border-gray-300">
                    @else
                        <img src="{{ asset('storage/' . $question->image) }}" alt="Imagem da questão" class="max-h-48 rounded border border-gray-300">
                    @endif
                    
                </div>
            @endif

            <input type="file" id="image" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200">
        </div>

        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-bold text-gray-800 mb-4">Alternativas</h3>

        <!-- Container das Alternativas -->
        <div class="space-y-4">
            @php $letras = ['A', 'B', 'C', 'D', 'E']; @endphp
            
            @foreach($question->options as $index => $option)
                <!-- Lógica para manter marcado se houver erro de validação, ou pegar do banco -->
                @php
                    $isChecked = old('correct_option') !== null 
                        ? old('correct_option') == $index 
                        : $option->is_correct;
                @endphp

                <div class="flex items-center gap-4 p-3 rounded-lg border {{ $isChecked ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200' }}">
                    
                    <span class="w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-700 font-bold rounded-md">
                        {{ $letras[$index] ?? $index }}
                    </span>
                    
                    <input type="text" name="options[{{ $index }}]" value="{{ old('options.'.$index, $option->text) }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    
                    <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                        <input type="radio" name="correct_option" value="{{ $index }}" {{ $isChecked ? 'checked' : '' }} class="w-5 h-5 text-green-600 focus:ring-green-500" required>
                        <span class="text-sm font-bold text-gray-700">Correta</span>
                    </label>

                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 transition-colors">
                Salvar Alterações
            </button>
        </div>

    </form>
</div>
@endsection