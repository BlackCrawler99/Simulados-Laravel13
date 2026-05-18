<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulado ENEM - Especial de Natal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animação suave de nevasca caindo */
        @keyframes snowfall {
            0% { transform: translateY(-10vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(110vh) rotate(360deg); opacity: 0.3; }
        }
        .animate-snow { animation: snowfall 10s linear infinite; }
        .animate-snow-delayed { animation: snowfall 12s linear 5s infinite; }
        .animate-snow-fast { animation: snowfall 8s linear 2s infinite; }
    </style>
</head>
<body class="antialiased bg-red-50 text-gray-900 font-sans overflow-x-hidden">

    <header class="bg-white shadow-md border-b-4 border-emerald-600 relative z-50">
        


        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between pl-16 sm:pl-24">
            
            <div class="flex-shrink-0 flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo da Instituição" class="h-12 w-auto">
                <span class="font-black text-2xl text-red-800 tracking-tight uppercase">Simulado<span class="text-emerald-600">ENEM</span></span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.questions.index') }}" class="font-bold text-red-700 hover:text-red-900 transition-colors flex items-center gap-2">
                                <span>Fábrica (Admin)</span> &rarr;
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="font-bold text-red-700 hover:text-red-900 transition-colors flex items-center gap-2">
                                <span>Abrir Meus Presentes</span> &rarr;
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-600 hover:text-red-700 transition-colors">
                            Entrar
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 border border-transparent rounded-lg font-black text-white hover:bg-emerald-500 focus:ring-4 focus:ring-emerald-200 transition-all shadow-md uppercase text-sm tracking-wide">
                                Desembrulhar Simulado
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main>
        <div class="relative overflow-hidden bg-gradient-to-br from-red-900 via-red-800 to-emerald-950">
            
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <svg class="absolute top-10 left-[10%] w-8 h-8 text-white opacity-40 animate-snow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m8-10H4m14.14-7.07l-14.14 14.14m0-14.14l14.14 14.14"></path></svg>
                <svg class="absolute top-0 left-[30%] w-6 h-6 text-white opacity-20 animate-snow-delayed" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m8-10H4m14.14-7.07l-14.14 14.14m0-14.14l14.14 14.14"></path></svg>
                <svg class="absolute -top-10 left-[60%] w-10 h-10 text-white opacity-50 animate-snow-fast" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m8-10H4m14.14-7.07l-14.14 14.14m0-14.14l14.14 14.14"></path></svg>
                <svg class="absolute top-20 right-[15%] w-8 h-8 text-white opacity-30 animate-snow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m8-10H4m14.14-7.07l-14.14 14.14m0-14.14l14.14 14.14"></path></svg>

                <svg class="absolute top-10 right-10 w-64 h-64 text-yellow-300 opacity-10 animate-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 text-center lg:pt-32">
                
                <div class="inline-block px-5 py-1 bg-white/10 backdrop-blur-sm border border-red-400 text-white font-black rounded-full text-sm mb-6 shadow-sm uppercase tracking-wider">
                    Especial Noite Feliz 🎄
                </div>

                <h1 class="mx-auto max-w-4xl font-black text-5xl sm:text-6xl tracking-tight text-white mb-6 drop-shadow-lg">
                    O seu maior presente este ano será a <span class="text-yellow-400 drop-shadow-[0_0_20px_rgba(250,204,21,0.6)]">Aprovação</span>.
                </h1>
                
                <p class="mx-auto max-w-2xl text-lg sm:text-xl text-red-100 mb-10 leading-relaxed font-medium">
                    Prepare-se para o ENEM com a magia dos nossos simulados. Antecipe seus estudos e termine o ano com a certeza do dever cumprido!
                </p>
                
                <div class="flex justify-center gap-4 flex-col sm:flex-row">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-black text-emerald-900 transition-all bg-yellow-400 rounded-full hover:bg-yellow-300 focus:outline-none focus:ring-4 focus:ring-yellow-200 shadow-xl hover:shadow-2xl hover:-translate-y-1 uppercase">
                        Garantir meu Presente 🎁
                    </a>
                    <a href="#magia" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all bg-transparent border-2 border-red-400 rounded-full hover:bg-red-700 hover:border-red-300 focus:outline-none focus:ring-4 focus:ring-red-500 backdrop-blur-sm">
                        Ver a Magia Acontecer
                    </a>
                </div>
            </div>
            
            <div class="absolute bottom-0 w-full overflow-hidden leading-none">
                <svg class="relative block w-full h-[50px] sm:h-[100px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,120.22,189.9,109.24,233.15,101.21,277.14,83.52,321.39,56.44Z" fill="#f9fafb"></path>
                </svg>
            </div>
        </div>

        <div id="magia" class="bg-gray-50 py-20 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-black text-red-800 uppercase tracking-tight">A Magia da Nossa Plataforma</h2>
                    <div class="w-24 h-1 bg-emerald-500 mx-auto mt-4 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    
                    <div class="bg-white p-8 rounded-3xl shadow-sm border-b-4 border-red-600 hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-3">Fábrica de Questões</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">As questões mais fresquinhas e atualizadas, produzidas pelos nossos duendes especialistas em ENEM.</p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border-b-4 border-emerald-500 hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-3">A Lista do Bom Velhinho</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">Baixe o PDF com seu desempenho completo e descubra se você foi um bom aluno durante a prova.</p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border-b-4 border-yellow-400 hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="w-14 h-14 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-3">Estude em Qualquer Trenó</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">100% responsivo. Teste seus conhecimentos do computador, tablet ou pelo celular durante a ceia!</p>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer class="bg-red-950 border-t border-red-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-red-300 text-sm font-bold tracking-wide">
                &copy; {{ date('Y') }} Uniensino. Ho Ho Ho! Feliz Aprovação! 🎅
            </p>
        </div>
    </footer>

</body>
</html>