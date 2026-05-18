<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulado ENEM | Especial Halloween UniEnsino</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">

    <style>
        .font-terror { font-family: 'Creepster', cursive; letter-spacing: 2px; }
        .bg-cemiterio { background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 50%, #431407 100%); }
        /* Animação suave de flutuar para fantasmas/morcegos */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 6s ease-in-out 3s infinite; }
    </style>
</head>
<body class="antialiased bg-gray-950 text-gray-100 font-sans overflow-x-hidden">

    <header class="bg-gray-950/80 backdrop-blur-md shadow-2xl border-b-2 border-orange-600 relative z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <div class="flex-shrink-0 flex items-center gap-2 relative">
                <svg class="absolute -top-4 -left-6 w-12 h-12 text-gray-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 2v20M2 12h20M4.929 4.929l14.142 14.142M4.929 19.071L19.071 4.929M12 6a6 6 0 00-6 6M12 6a6 6 0 016 6M12 18a6 6 0 00-6-6M12 18a6 6 0 016-6"></path></svg>
                
                <img src="{{ asset('images/logo_branca.png') }}" alt="Logo da Instituição" class="h-12 w-auto relative z-10">
                <span class="font-black text-2xl text-white tracking-tight uppercase relative z-10">Simulado<span class="text-orange-500 font-terror text-3xl ml-1">ENEM</span></span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-4 relative z-10">
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.questions.index') }}" class="font-bold text-purple-400 hover:text-purple-300 transition-colors flex items-center gap-2">
                                <span>Torre do Mago (Admin)</span> &rarr;
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="font-bold text-orange-400 hover:text-orange-300 transition-colors flex items-center gap-2 drop-shadow-md">
                                <span>Voltar ao Caldeirão (Painel)</span> &rarr;
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-400 hover:text-orange-500 transition-colors">
                            Entrar na Cripta
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-orange-600 border border-transparent rounded-lg font-black text-white hover:bg-orange-500 focus:ring-4 focus:ring-orange-900 transition-all shadow-[0_0_15px_rgba(234,88,12,0.5)] uppercase text-sm tracking-wide">
                                Desvendar Simulado
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main>
        <div class="relative overflow-hidden bg-cemiterio min-h-[90vh] flex items-center">
            
            <svg class="absolute top-0 left-0 w-64 h-64 text-gray-700 opacity-30 transform -translate-x-10 -translate-y-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M12 2v20M2 12h20M4.929 4.929l14.142 14.142M4.929 19.071L19.071 4.929M12 6a6 6 0 00-6 6M12 6a6 6 0 016 6M12 18a6 6 0 00-6-6M12 18a6 6 0 016-6M12 2a10 10 0 00-10 10M12 2a10 10 0 0110 10M12 22a10 10 0 00-10-10M12 22a10 10 0 0110-10"></path></svg>
            
            <svg class="absolute top-0 right-0 w-96 h-96 text-gray-700 opacity-20 transform translate-x-20 -translate-y-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M12 2v20M2 12h20M4.929 4.929l14.142 14.142M4.929 19.071L19.071 4.929M12 6a6 6 0 00-6 6M12 6a6 6 0 016 6M12 18a6 6 0 00-6-6M12 18a6 6 0 016-6M12 2a10 10 0 00-10 10M12 2a10 10 0 0110 10M12 22a10 10 0 00-10-10M12 22a10 10 0 0110-10"></path></svg>

            <div class="absolute top-32 left-20 animate-float opacity-40">
                <svg class="w-16 h-16 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 10.5c-1.5 0-2.5.5-3.5 1.5-2.5-1.5-5-2-7-1.5 1.5 2 2.5 4.5 1 7 3.5-.5 5.5-2.5 7-4.5 1 1.5 1.5 2.5 2.5 2.5s1.5-1 2.5-2.5c1.5 2 3.5 4 7 4.5-1.5-2.5-2.5-5-1-7-2-.5-4.5 0-7 1.5-1-1-2-1.5-3.5-1.5z"/></svg>
            </div>
            <div class="absolute top-20 right-32 animate-float-delayed opacity-60">
                <svg class="w-24 h-24 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 10.5c-1.5 0-2.5.5-3.5 1.5-2.5-1.5-5-2-7-1.5 1.5 2 2.5 4.5 1 7 3.5-.5 5.5-2.5 7-4.5 1 1.5 1.5 2.5 2.5 2.5s1.5-1 2.5-2.5c1.5 2 3.5 4 7 4.5-1.5-2.5-2.5-5-1-7-2-.5-4.5 0-7 1.5-1-1-2-1.5-3.5-1.5z"/></svg>
            </div>
            <div class="absolute bottom-40 right-10 animate-float opacity-30 transform scale-75">
                <svg class="w-12 h-12 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 10.5c-1.5 0-2.5.5-3.5 1.5-2.5-1.5-5-2-7-1.5 1.5 2 2.5 4.5 1 7 3.5-.5 5.5-2.5 7-4.5 1 1.5 1.5 2.5 2.5 2.5s1.5-1 2.5-2.5c1.5 2 3.5 4 7 4.5-1.5-2.5-2.5-5-1-7-2-.5-4.5 0-7 1.5-1-1-2-1.5-3.5-1.5z"/></svg>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10 w-full">
                
                <div class="inline-flex items-center gap-2 px-5 py-2 bg-purple-900/50 border border-purple-500 text-purple-200 font-bold rounded-full text-sm mb-8 shadow-[0_0_20px_rgba(147,51,234,0.3)] backdrop-blur-sm">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    A Temporada de Sustos Começou
                </div>

                <h1 class="mx-auto max-w-5xl font-black text-5xl sm:text-7xl tracking-tight text-white mb-6 drop-shadow-[0_5px_5px_rgba(0,0,0,0.8)]">
                    Não deixe o <b class="font-black text-purple-500 uppercase tracking-tight drop-shadow-md font-terror">ENEM</b> ser um <br>
                    <span class="text-orange-500 font-terror text-6xl sm:text-8xl drop-shadow-[0_0_20px_rgba(234,88,12,0.6)]">PESADELO!</span>
                </h1>
                
                <p class="mx-auto max-w-2xl text-lg sm:text-2xl text-gray-300 mb-12 leading-relaxed font-medium drop-shadow-md">
                    Encare seus medos. Faça o simulado agora, revele seus pontos fracos antes da prova oficial e garanta a sua aprovação.
                </p>
                
                <div class="flex justify-center items-center gap-6 flex-col sm:flex-row relative">
                    
                    <div class="hidden sm:block absolute -left-16 bottom-0 animate-pulse">
                        <svg class="w-20 h-20 text-orange-600 drop-shadow-[0_0_15px_rgba(234,88,12,0.5)]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2c-4.4 0-8 4.5-8 10 0 5.5 3.6 10 8 10s8-4.5 8-10c0-5.5-3.6-10-8-10zm-3 8l2 3h-4l2-3zm6 0l2 3h-4l2-3zm-3 8c-2.5 0-4.5-1.5-5-3h10c-.5 1.5-2.5 3-5 3zM12 1c1.1 0 2-1 2-1s-1 2-2 2c-1 0-2-1-2-1s.9 1 2 1z"/>
                        </svg>
                    </div>

                    <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-10 py-5 text-xl font-black text-white transition-all bg-orange-600 border-2 border-orange-500 rounded-full hover:bg-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-900 shadow-[0_0_30px_rgba(234,88,12,0.4)] hover:shadow-[0_0_40px_rgba(234,88,12,0.8)] hover:-translate-y-1 uppercase overflow-hidden">
                        <span class="relative z-10">Gostosuras ou Questões? 🍬</span>
                        <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-20 group-hover:animate-[shimmer_1.5s_infinite] -skew-x-12"></div>
                    </a>

                    <a href="#grimorio" class="inline-flex items-center justify-center px-8 py-5 text-lg font-bold text-gray-300 transition-all bg-gray-900/50 border-2 border-purple-800 rounded-full hover:bg-purple-900 hover:border-purple-500 hover:text-white focus:outline-none focus:ring-4 focus:ring-purple-900 backdrop-blur-sm">
                        Abrir o Grimório (Vantagens)
                    </a>
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-gray-950 to-transparent"></div>
        </div>

        <div id="grimorio" class="bg-gray-950 py-24 relative border-t-4 border-purple-900">
            
            <div class="absolute top-10 left-10 opacity-30 animate-pulse">
                <svg class="w-12 h-20 text-yellow-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1 2-3 4-3 6s2 3 3 3 3-1 3-3-2-4-3-6zm-2 10h4v12h-4V12z"/></svg>
            </div>
            <div class="absolute top-20 right-10 opacity-30 animate-pulse" style="animation-delay: 1s;">
                <svg class="w-10 h-16 text-yellow-600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1 2-3 4-3 6s2 3 3 3 3-1 3-3-2-4-3-6zm-2 10h4v12h-4V12z"/></svg>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                
                <div class="text-center mb-20">
                    <h2 class="text-4xl font-black text-purple-500 uppercase tracking-tight drop-shadow-md font-terror">Feitiços da Plataforma</h2>
                    <div class="w-32 h-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent mx-auto mt-6 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="bg-gray-900 p-10 rounded-3xl shadow-2xl border border-gray-800 border-t-4 border-t-orange-600 hover:-translate-y-2 transition-transform relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-600/10 rounded-full blur-2xl group-hover:bg-orange-600/20 transition-all"></div>
                        <div class="w-16 h-16 bg-gray-950 border border-orange-900 text-orange-500 rounded-2xl flex items-center justify-center mb-8 shadow-inner shadow-orange-900/50">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v1a6 6 0 01-6 6H9a6 6 0 01-6-6v-1a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4">Questões Assustadoras</h3>
                        <p class="text-gray-400 leading-relaxed font-medium text-lg">Questões garimpadas diretamente das masmorras do INEP. Encare o nível real da prova sem ilusões.</p>
                    </div>

                    <div class="bg-gray-900 p-10 rounded-3xl shadow-2xl border border-gray-800 border-t-4 border-t-purple-500 hover:-translate-y-2 transition-transform relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"></div>
                        <div class="w-16 h-16 bg-gray-950 border border-purple-900 text-purple-500 rounded-2xl flex items-center justify-center mb-8 shadow-inner shadow-purple-900/50">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4">Pergaminho de Sangue</h3>
                        <p class="text-gray-400 leading-relaxed font-medium text-lg">Ao final, receba um PDF detalhado. Descubra onde seus conhecimentos foram estripados e o que precisa curar.</p>
                    </div>

                    <div class="bg-gray-900 p-10 rounded-3xl shadow-2xl border border-gray-800 border-t-4 border-t-indigo-600 hover:-translate-y-2 transition-transform relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-600/10 rounded-full blur-2xl group-hover:bg-indigo-600/20 transition-all"></div>
                        <div class="w-16 h-16 bg-gray-950 border border-indigo-900 text-indigo-500 rounded-2xl flex items-center justify-center mb-8 shadow-inner shadow-indigo-900/50">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4">Estude nas Sombras</h3>
                        <p class="text-gray-400 leading-relaxed font-medium text-lg">Nosso simulado se adapta à sua tela. Treine escondido embaixo das cobertas pelo celular ou no seu PC.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-black border-t border-purple-900 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            <svg class="w-8 h-20 text-orange-700 mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0v8.5c-1.5 0-2.5 1-3 2.5l-3-2-1 2 3.5 2c-.5 1-1 2.5-1 4.5l-4-1-1 2 5 2c0 1.5.5 2.5 1.5 3.5l-3 3 1.5 1.5 2.5-2.5c1 .5 2 .5 3 .5s2 0 3-.5l2.5 2.5 1.5-1.5-3-3c1-1 1.5-2 1.5-3.5l5-2-1-2-4 1c0-2-.5-3.5-1-4.5l3.5-2-1-2-3 2c-.5-1.5-1.5-2.5-3-2.5V0h-2z"/></svg>
            
            <p class="text-purple-400 text-sm font-bold tracking-widest uppercase">
                &copy; {{ date('Y') }} Uniensino. Fuja da ignorância. 🧟‍♂️
            </p>
        </div>
    </footer>

</body>
</html>