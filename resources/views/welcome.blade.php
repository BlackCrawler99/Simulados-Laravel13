<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulado ENEM - Prepare-se para o Futuro</title>

    <!-- Carrega o Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans">

    <!-- Cabeçalho / Navegação -->
    <header class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo / Nome da Instituição -->
            <div class="flex-shrink-0 flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo da Instituição" class="h-12 w-auto">
                <span class="font-bold text-2xl text-gray-800 tracking-tight">Simulado<span class="text-indigo-600">ENEM</span></span>
            </div>

            <!-- Botões de Autenticação -->
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <!-- Se já estiver logado, mostra o botão para a Dashboard -->
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                            Acessar meu Painel &rarr;
                        </a>
                    @else
                        <!-- Se não estiver logado, mostra Login e Cadastro -->
                        <a href="{{ route('login') }}" class="font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            Entrar
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition-all shadow-sm">
                                Cadastre-se Grátis
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Seção Principal (Hero) -->
    <main>
        <div class="relative overflow-hidden bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 text-center lg:pt-32">
                
                <h1 class="mx-auto max-w-4xl font-extrabold text-5xl sm:text-6xl tracking-tight text-slate-900 mb-6">
                    Teste seus conhecimentos para o <span class="text-indigo-600">ENEM</span>.
                </h1>
                
                <p class="mx-auto max-w-2xl text-lg sm:text-xl text-slate-600 mb-10 leading-relaxed">
                    Faça simulados com questões reais, identifique seus pontos fortes e receba um relatório detalhado de desempenho para direcionar seus estudos.
                </p>
                
                <div class="flex justify-center gap-4 flex-col sm:flex-row">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Começar Agora
                    </a>
                    <a href="#como-funciona" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-slate-700 transition-all bg-white border-2 border-slate-200 rounded-xl hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        Como funciona?
                    </a>
                </div>
            </div>
        </div>

        <!-- Seção de Benefícios (Features) -->
        <div id="como-funciona" class="bg-gray-50 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    
                    <!-- Card 1 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Questões Inéditas</h3>
                        <p class="text-gray-600 leading-relaxed">Banco de questões atualizado constantemente para refletir o nível de exigência das provas mais recentes.</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Relatório de Desempenho</h3>
                        <p class="text-gray-600 leading-relaxed">Ao finalizar, baixe seu gabarito em PDF e descubra exatamente onde você precisa focar seus estudos.</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">100% Responsivo</h3>
                        <p class="text-gray-600 leading-relaxed">Faça os simulados de qualquer lugar. Nossa plataforma funciona perfeitamente no seu computador, tablet ou celular.</p>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Uniensino. Todos os direitos reservados.
            </p>
        </div>
    </footer>

</body>
</html>