<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Grid Container principal -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Nome Completo (Ocupa as duas colunas no desktop) -->
            <div class="md:col-span-2">
                <x-input-label for="name" value="Nome Completo" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- WhatsApp -->
            <div>
                <x-input-label for="phone" value="WhatsApp" />
                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required placeholder="(00) 00000-0000" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Senha -->
            <div>
                <x-input-label for="password" value="Senha" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirmar Senha -->
            <div>
                <x-input-label for="password_confirmation" value="Confirmar Senha" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Cidade e UF (Sub-grid para ficarem na mesma linha) -->
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">    
                <div>
                    <label for="uf" class="block text-sm font-medium text-gray-700">Estado</label>
                    <input type="text" id="uf" name="uf" list="uf-list" autocomplete="off"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                        placeholder="Digite a sigla ou nome do Estado...">
                    <datalist id="uf-list"></datalist>
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">Cidade</label>
                    <input type="text" id="city" name="city" list="city-list" autocomplete="off" disabled
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-400" 
                        placeholder="Escolha o Estado primeiro...">
                    <datalist id="city-list"></datalist>
                </div>
            </div>

            <!-- Ano Escolar -->
            <div class="md:col-span-2">
                <x-input-label for="school_year" value="Ano Escolar" />
                <select id="school_year" name="school_year" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Selecione...</option>
                    <option value="1º Ano do Ensino Médio">1º Ano do Ensino Médio</option>
                    <option value="2º Ano do Ensino Médio">2º Ano do Ensino Médio</option>
                    <option value="3º Ano do Ensino Médio/Terceirão">3º Ano do Ensino Médio</option>
                    <option value="Já concluí o Ensino Médio">Já concluí o Ensino Médio</option>
                </select>
                <x-input-error :messages="$errors->get('school_year')" class="mt-2" />
            </div>
            <!-- Módulo de Colegios (Aparece apenas se o módulo estiver ativo) -->
            @if(in_array(\App\Models\Setting::where('key', 'module_colegios')->value('value'), ['1', 'true']))
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div>
                        <label for="school_search" class="block text-sm font-medium text-gray-700">
                            Colégio / Instituição <span class="text-indigo-600 font-bold">*</span>
                        </label>
                        <input type="text" id="school_search" name="school_search" list="schools-list" autocomplete="off"
                            placeholder="Digite ou selecione o colégio..." required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        
                        <datalist id="schools-list">
                            @foreach(\App\Models\School::where('module_colegios', true)->orderBy('name', 'asc')->get() as $school)
                                <option value="{{ $school->name }}" data-id="{{ $school->id }}"></option>
                            @endforeach
                        </datalist>
                        
                        <input type="hidden" id="school_id" name="school_id" required>
                    </div>

                    <div>
                        <label for="class_search" class="block text-sm font-medium text-gray-700">
                            Turma <span class="text-indigo-600 font-bold">*</span>
                        </label>
                        <input type="text" id="class_search" name="class_search" list="classes-list" autocomplete="off" disabled
                            placeholder="Selecione o colégio primeiro..." required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-400">
                        
                        <datalist id="classes-list"></datalist>
                        
                        <input type="hidden" id="school_class_id" name="school_class_id" required>
                    </div>

                </div>
            @endif

            <!-- Curso de Interesse (Ocupa as duas colunas no desktop) -->
            <div class="md:col-span-2">
                <x-input-label for="desired_course" value="Curso de Interesse" />
                <select id="desired_course" name="desired_course" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Selecione o curso...</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->name }}">{{ $course->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('desired_course')" class="mt-2" />
            </div>

            <!-- Campo de Curso de Interesse -->
            <div class="md:col-span-2">
                <label for="interested_course" class="block text-sm font-bold text-gray-700">
                    Não encontrou seu curso na lista? Digite o curso de interesse:
                </label>
                <input id="interested_course" type="text" name="interested_course" value="{{ old('interested_course') }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                    placeholder="Ex: Engenharia Aeroespacial">
                <p class="text-xs text-gray-500 mt-1">Deixe em branco se já selecionou um curso acima.</p>
            </div>

        </div> <!-- Fim do Grid Container -->

        <!-- Checkbox de Aceite -->
        <div class="block mt-6 border-t border-gray-200 pt-4">
            <label for="accepts_info" class="inline-flex items-center">
                <input id="accepts_info" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="accepts_info" value="1" checked>
                <span class="ms-2 text-sm text-gray-600">Aceito receber informações sobre bolsas, cursos e benefícios exclusivos da instituição.</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Já possui cadastro?
            </a>

            <x-primary-button class="ms-4 px-6 py-3 text-base">
                Concluir Cadastro
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ufInput = document.getElementById('uf');
    const ufList = document.getElementById('uf-list');
    const cityInput = document.getElementById('city');
    const cityList = document.getElementById('city-list');
    
    // Objeto para mapear o que o usuário digita para a Sigla oficial (ex: 'Paraná' -> 'PR')
    let mapEstados = {}; 

    // 1. Carrega os Estados ao abrir a página
    fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome')
        .then(response => response.json())
        .then(estados => {
            estados.forEach(estado => {
                // Mapeia tanto a sigla quanto o nome para facilitar a busca
                mapEstados[estado.sigla.toLowerCase()] = estado.sigla;
                mapEstados[estado.nome.toLowerCase()] = estado.sigla;

                const option = document.createElement('option');
                option.value = estado.sigla; // Deixa a sigla como valor principal
                option.textContent = estado.nome;
                ufList.appendChild(option);
            });
        });

    // 2. Escuta a digitação no input de Estado
    ufInput.addEventListener('input', function() {
        const valorDigitado = this.value.toLowerCase().trim();
        const sigla = mapEstados[valorDigitado]; // Tenta achar a sigla correspondente

        if (sigla) {
            // Se achou um estado válido, trava a cidade temporariamente e avisa que está carregando
            cityInput.disabled = true;
            cityInput.placeholder = "Carregando cidades...";
            cityInput.value = '';
            
            // Busca as cidades daquele Estado no IBGE
            fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${sigla}/municipios`)
                .then(response => response.json())
                .then(cidades => {
                    cityList.innerHTML = ''; // Limpa a lista anterior
                    
                    cidades.forEach(cidade => {
                        const option = document.createElement('option');
                        option.value = cidade.nome;
                        cityList.appendChild(option);
                    });
                    
                    // Libera o input de cidade para o aluno digitar
                    cityInput.disabled = false;
                    cityInput.placeholder = "Digite ou selecione sua cidade...";
                    cityInput.focus(); // Joga o cursor do mouse pra lá automaticamente
                });
        } else {
            // Se o estado não for válido ou foi apagado, bloqueia a cidade
            cityList.innerHTML = '';
            cityInput.disabled = true;
            cityInput.value = '';
            cityInput.placeholder = "Escolha o Estado primeiro...";
        }
    });


    const schoolSearch = document.getElementById('school_search');
    const schoolIdInput = document.getElementById('school_id');
    const classSearch = document.getElementById('class_search');
    const classList = document.getElementById('classes-list');
    const classIdInput = document.getElementById('school_class_id');

    // Mapeia as opções do datalist de escolas para capturar o ID (Nome -> ID)
    const schoolOptions = document.querySelectorAll('#schools-list option');
    const schoolMap = {};
    schoolOptions.forEach(option => {
        schoolMap[option.value.toLowerCase()] = option.getAttribute('data-id');
    });

    // 1. Escuta a seleção/digitação do Colégio
    schoolSearch.addEventListener('input', function() {
        const valorDigitado = this.value.trim().toLowerCase();
        const schoolId = schoolMap[valorDigitado];

        // Reseta o campo de turmas
        classSearch.value = '';
        classIdInput.value = '';
        classList.innerHTML = '';

        if (schoolId) {
            // Seta o ID oculto da escola
            schoolIdInput.value = schoolId;
            
            // Libera e avisa carregamento das turmas
            classSearch.disabled = true;
            classSearch.placeholder = "Carregando turmas...";
            
            // Requisita as turmas via endpoint criado no Laravel
            fetch(`/api/escolas/${schoolId}/turmas`)
                .then(response => response.json())
                .then(turmas => {
                    classList.innerHTML = '';
                    
                    // Guarda o mapeamento dinâmico de turmas desta escola
                    window.turmaMap = {};

                    turmas.forEach(turma => {
                        window.turmaMap[turma.name.toLowerCase()] = turma.id;
                        
                        const option = document.createElement('option');
                        option.value = turma.name;
                        classList.appendChild(option);
                    });

                    classSearch.disabled = false;
                    classSearch.placeholder = "Digite ou selecione sua turma...";
                    classSearch.focus();
                });
        } else {
            // Bloqueia novamente se limpar o campo
            schoolIdInput.value = '';
            classSearch.disabled = true;
            classSearch.value = '';
            classSearch.placeholder = "Selecione o colégio primeiro...";
        }
    });

    // 2. Escuta a seleção/digitação da Turma
    classSearch.addEventListener('input', function() {
        const valorDigitado = this.value.trim().toLowerCase();
        if (window.turmaMap && window.turmaMap[valorDigitado]) {
            classIdInput.value = window.turmaMap[valorDigitado];
        } else {
            classIdInput.value = '';
        }
    });

});
</script>