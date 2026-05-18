<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulado ENEM - Rumo à Aprovação</title>

    <!-- Carrega o Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-green-50 text-gray-900 font-sans">

    <!-- Cabeçalho / Navegação com Borda Temática -->
    <header class="bg-white shadow-sm border-b-4 border-yellow-400 relative z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo / Nome da Instituição -->
            <div class="flex-shrink-0 flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo da Instituição" class="h-12 w-auto">
                <span class="font-black text-2xl text-gray-800 tracking-tight uppercase">Simulado<span class="text-green-600">ENEM</span></span>
            </div>

            <!-- Botões de Autenticação -->
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.questions.index') }}" class="font-bold text-green-700 hover:text-green-900 transition-colors flex items-center gap-2">
                                <span>Painel Administrativo</span> &rarr;
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="font-bold text-green-700 hover:text-green-900 transition-colors flex items-center gap-2">
                                <span>Ir para a Concentração</span> &rarr;
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-600 hover:text-green-700 transition-colors">
                            Entrar
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-yellow-400 border border-transparent rounded-lg font-black text-green-900 hover:bg-yellow-300 focus:ring-4 focus:ring-yellow-200 transition-all shadow-sm uppercase text-sm tracking-wide">
                                Fazer Convocação
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Seção Principal (Hero - O Gramado) -->
    <main>
        <div class="relative overflow-hidden bg-gradient-to-br from-green-800 via-green-700 to-green-900">
            <!-- Estrelas de fundo (Decoração) -->
            <div class="absolute inset-0 opacity-10 flex justify-around items-center pointer-events-none">
                <svg class="w-32 h-32 text-yellow-100 transform -rotate-12" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-48 h-48 text-yellow-100 transform rotate-12" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 text-center lg:pt-32">
                
                <div class="inline-block px-4 py-1 bg-yellow-400 text-green-900 font-black rounded-full text-sm mb-6 shadow-sm uppercase tracking-wider">
                    Projeto Hexa 🇧🇷
                </div>

                <h1 class="mx-auto max-w-4xl font-black text-5xl sm:text-6xl tracking-tight text-white mb-6 drop-shadow-lg">
                    Convoque seu potencial e levante a taça da <span class="text-yellow-400">Aprovação</span>.
                </h1>
                
                <p class="mx-auto max-w-2xl text-lg sm:text-xl text-green-100 mb-10 leading-relaxed font-medium">
                    Entre em campo com simulados de alto nível. Identifique suas falhas na zaga, fortaleça seu ataque e garanta a sua vaga no ENEM!
                </p>
                
                <div class="flex justify-center gap-4 flex-col sm:flex-row">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-black text-green-900 transition-all bg-yellow-400 rounded-full hover:bg-yellow-300 focus:outline-none focus:ring-4 focus:ring-yellow-200 shadow-xl hover:shadow-2xl hover:-translate-y-1 uppercase">
                        Entrar em Campo ⚽
                    </a>
                    <a href="#taticas" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all bg-transparent border-2 border-green-400 rounded-full hover:bg-green-600 hover:border-green-300 focus:outline-none focus:ring-4 focus:ring-green-500 backdrop-blur-sm">
                        Ver Táticas do Jogo
                    </a>
                </div>
            </div>
        </div>

        <!-- Seção de Benefícios (Táticas) -->
        <div id="taticas" class="bg-gray-50 py-20 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-black text-green-800 uppercase tracking-tight">O Esquema Tático da Vitória</h2>
                    <div class="w-24 h-1 bg-yellow-400 mx-auto mt-4 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    
                    <!-- Card 1 -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm border-b-4 border-green-500 hover:shadow-xl transition-shadow">
                        <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-3">Treino de Elite</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">Banco de questões selecionadas a dedo pelos nossos técnicos. Reflete exatamente o nível de exigência da prova real.</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm border-b-4 border-yellow-400 hover:shadow-xl transition-shadow">
                        <div class="w-14 h-14 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-3">Estatísticas da Partida</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">Baixe o seu PDF de rendimento, veja seus índices de acerto e descubra exatamente onde você precisa focar para não levar gol.</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm border-b-4 border-blue-500 hover:shadow-xl transition-shadow">
                        <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-3">Jogue em Qualquer Campo</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">100% responsivo. Faça seu treino do computador, tablet ou celular. O estádio é onde você estiver!</p>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-green-900 border-t border-green-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-green-300 text-sm font-bold tracking-wide">
                &copy; {{ date('Y') }} Uniensino. O Hexa vem aí! 🏆
            </p>
        </div>
    </footer>

</body>
</html>