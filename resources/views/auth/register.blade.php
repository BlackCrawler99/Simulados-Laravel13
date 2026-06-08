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
            <div class="grid grid-cols-4 gap-2">
                <div class="col-span-3">
                    <x-input-label for="city" value="Cidade" />
                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-input-label for="uf" value="UF" />
                    <select id="uf" name="uf" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="PR">PR</option>
                        <option value="SC">SC</option>
                        <option value="SP">SP</option>
                        <option value="RS">RS</option>
                        <!-- Adicione os outros estados se desejar -->
                    </select>
                    <x-input-error :messages="$errors->get('uf')" class="mt-2" />
                </div>
            </div>

            <!-- Ano Escolar -->
            <div>
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
            <div class="mt-4">
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
