@extends('layouts.admin')

@section('title', 'Nova Questão')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Cadastrar Nova Questão</h2>
        <p class="text-gray-600 mt-1">Preencha o enunciado, adicione uma imagem (opcional) e marque a alternativa correta.</p>
    </div>
    <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-lg shadow-sm hover:bg-gray-300 transition-colors">
        Voltar
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-4xl">
    
    <!-- O enctype é OBRIGATÓRIO para enviar imagens -->
    <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
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
            <textarea id="statement" name="statement" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('statement') }}</textarea>
            @error('statement') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Imagem de Apoio -->
        <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-lg">
            <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Imagem de Apoio (Opcional)</label>
            <input type="file" id="image" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200">
            <p class="text-xs text-gray-500 mt-2">Formatos aceitos: JPG, PNG, WEBP. Tamanho máximo: 2MB.</p>
            @error('image') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <hr class="my-6 border-gray-200">

        <h3 class="text-lg font-bold text-gray-800 mb-4">Alternativas</h3>
        <p class="text-sm text-gray-500 mb-6">Preencha as 5 alternativas e selecione o botão na coluna da direita para indicar qual é o <strong>Gabarito</strong>.</p>

        <!-- Container das Alternativas -->
        <div class="space-y-4">
            @php $letras = ['A', 'B', 'C', 'D', 'E']; @endphp
            
            @foreach($letras as $index => $letra)
                <div class="flex items-center gap-4 p-3 rounded-lg border {{ old('correct_option') == $index ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200' }}">
                    
                    <!-- Letra -->
                    <span class="w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-700 font-bold rounded-md">
                        {{ $letra }}
                    </span>
                    
                    <!-- Campo de Texto -->
                    <input type="text" name="options[{{ $index }}]" value="{{ old('options.'.$index) }}" placeholder="Texto da alternativa {{ $letra }}..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    
                    <!-- Radio Button da Correta -->
                    <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                        <input type="radio" name="correct_option" value="{{ $index }}" {{ old('correct_option') == $index ? 'checked' : '' }} class="w-5 h-5 text-green-600 focus:ring-green-500" required>
                        <span class="text-sm font-bold text-gray-700">Correta</span>
                    </label>

                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 transition-colors">
                Salvar Questão
            </button>
        </div>

    </form>
</div>
@endsection