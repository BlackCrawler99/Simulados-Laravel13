@extends('layouts.admin')

@section('title', 'Configurações')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Configurações do Sistema</h2>
    <p class="text-gray-600 mt-1">Gerencie a aparência e comportamento do portal.</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Regras do Simulado</h3>
    
    <form action="{{ route('admin.settings.update-rules') }}" method="POST">
        @csrf
        <div class="max-w-xs">
            <label class="block text-sm font-bold text-gray-700 mb-1">Total de Questões por Prova</label>
            <input type="number" name="exam_question_count" 
                   value="{{ \App\Models\Setting::where('key', 'exam_question_count')->value('value') ?? 20 }}" 
                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <p class="text-xs text-gray-500 mt-2">Alterar este valor não afetará a nota das provas já finalizadas.</p>
        </div>

        <div class="mb-4">
            <label for="max_scholarship" class="block text-sm font-bold text-gray-700">Porcentagem Máxima de Bolsa (%)</label>
            <p class="text-xs text-gray-500 mb-2">Define o teto de desconto que um aluno pode alcançar ao gabaritar o simulado.</p>
            
            <div class="relative">
                <input type="number" name="max_scholarship" id="max_scholarship" min="0" max="100" 
                    value="{{ \App\Models\Setting::where('key', 'max_scholarship')->value('value') ?? 50 }}"
                    class="mt-1 block w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div class="absolute inset-y-0 left-auto right-0 md:right-2/3 pr-3 flex items-center pointer-events-none mt-1">
                    <span class="text-gray-500 sm:text-sm">%</span>
                </div>
            </div>
        </div>

            <h3 class="font-bold text-lg mb-4">Configurar Promoção</h3>
            
            <div class="flex items-center gap-4 mb-4">
                <input type="checkbox" name="promotion_active" id="promotion_active" 
                    {{ \App\Models\Setting::where('key', 'promotion_active')->value('value') === 'true' ? 'checked' : '' }}>
                <label for="promotion_active" class="font-bold">Ativar contagem regressiva da promoção</label>
            </div>

            <div>
                <label class="block text-sm font-bold">Data e Hora de Encerramento</label>
                <input type="datetime-local" name="promotion_end_time" 
                    value="{{ \App\Models\Setting::where('key', 'promotion_end_time')->value('value') }}"
                    class="mt-1 block w-full md:w-1/4 rounded-md border-gray-300 shadow-sm">
            </div>

        <div class="mb-4">
            <label for="whatsapp_number" class="block text-sm font-bold text-gray-700">Número do WhatsApp (Atendimento)</label>
            <p class="text-xs text-gray-500 mb-2">Digite apenas números, incluindo o código do país (ex: 55) e o DDD.</p>
            <input type="text" name="whatsapp_number" id="whatsapp_number" 
                value="{{ \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '5541998131679' }}"
                class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="5541999999999">
        </div>

        <div class="mb-4">
            <label for="whatsapp_message" class="block text-sm font-bold text-gray-700">Mensagem Padrão de Contato</label>
            <p class="text-xs text-gray-500 mb-2">
                Use as tags <span class="font-bold text-indigo-600">{nota}</span>, 
                <span class="font-bold text-indigo-600">{bolsa}</span> e 
                <span class="font-bold text-indigo-600">{premio}</span> para que o sistema substitua automaticamente pelos dados do aluno.
            </p>
            <textarea name="whatsapp_message" id="whatsapp_message" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ \App\Models\Setting::where('key', 'whatsapp_message')->value('value') ?? 'Olá! Fiz o Simulado da Instituição, tirei a nota {nota} e garanti minha bolsa de {bolsa}% e {premio}. Gostaria de agendar minha matrícula!' }}</textarea>
        </div>

        <hr class="my-8 border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Prêmios por Faixa de Acerto</h3>
            <p class="text-sm text-gray-500 mb-6">Defina o texto do brinde que o aluno ganha ao atingir cada faixa de nota no simulado.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">
                <div>
                    <label for="reward_tier_1" class="block text-sm font-bold text-gray-700">Faixa 1 (0% a 30%)</label>
                    <input type="text" name="reward_tier_1" id="reward_tier_1" 
                        value="{{ \App\Models\Setting::where('key', 'reward_tier_1')->value('value') ?? '1 Figurinha' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="reward_tier_2" class="block text-sm font-bold text-gray-700">Faixa 2 (31% a 40%)</label>
                    <input type="text" name="reward_tier_2" id="reward_tier_2" 
                        value="{{ \App\Models\Setting::where('key', 'reward_tier_2')->value('value') ?? '2 Figurinhas' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="reward_tier_3" class="block text-sm font-bold text-gray-700">Faixa 3 (41% a 50%)</label>
                    <input type="text" name="reward_tier_3" id="reward_tier_3" 
                        value="{{ \App\Models\Setting::where('key', 'reward_tier_3')->value('value') ?? '3 Figurinhas' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="reward_tier_4" class="block text-sm font-bold text-gray-700">Faixa 4 (51% a 60%)</label>
                    <input type="text" name="reward_tier_4" id="reward_tier_4" 
                        value="{{ \App\Models\Setting::where('key', 'reward_tier_4')->value('value') ?? '4 Figurinhas' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="reward_tier_5" class="block text-sm font-bold text-gray-700">Faixa 5 (61% a 99%)</label>
                    <input type="text" name="reward_tier_5" id="reward_tier_5" 
                        value="{{ \App\Models\Setting::where('key', 'reward_tier_5')->value('value') ?? '5 Figurinhas' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="reward_tier_6" class="block text-sm font-bold text-indigo-700">Faixa 6 (Gabarito - 100%)</label>
                    <input type="text" name="reward_tier_6" id="reward_tier_6" 
                        value="{{ \App\Models\Setting::where('key', 'reward_tier_6')->value('value') ?? '1 Pacote de Figurinhas' }}"
                        class="mt-1 block w-full rounded-md border-indigo-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-indigo-50">
                </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-gray-900 text-white font-bold rounded-lg hover:bg-gray-800">
            Salvar Regra
        </button>
    </form>
</div>
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
<!--  EXCLUSIVO PARA FUTURAS EDIÇÕES - OPÇÕES DE TEMAS SAZONAIS
            Opção Tema Halloween 
            <label class="cursor-pointer relative">
                <input type="radio" name="theme" value="halloween" class="peer sr-only" {{ $activeTheme === 'halloween' ? 'checked' : '' }}>
                <div class="rounded-lg border-2 p-6 transition-all {{ $activeTheme === 'halloween' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-orange-300' }} peer-checked:border-orange-500 peer-checked:bg-orange-50">
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Tema Halloween 🎃</h4>
                    <p class="text-sm text-gray-600">Layout sombrio e divertido em tons de laranja e roxo para o mês das bruxas.</p>
                    
                    <div class="absolute top-4 right-4 hidden peer-checked:block text-orange-500">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </label>

            Opção Tema Natal 
            <label class="cursor-pointer relative">
                <input type="radio" name="theme" value="natal" class="peer sr-only" {{ $activeTheme === 'natal' ? 'checked' : '' }}>
                <div class="rounded-lg border-2 p-6 transition-all {{ $activeTheme === 'natal' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300' }} peer-checked:border-red-500 peer-checked:bg-red-50">
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Tema Natal 🎄</h4>
                    <p class="text-sm text-gray-600">Layout festivo com tons de vermelho e branco para a temporada de natal.</p>
                    
                    <div class="absolute top-4 right-4 hidden peer-checked:block text-red-500">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </label>
-->
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gray-900 text-white font-bold rounded-lg shadow-lg hover:bg-gray-800 transition-colors">
                Salvar Tema Ativo
            </button>
        </div>
    </form>
</div>
@endsection