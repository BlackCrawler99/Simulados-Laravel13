<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seu Perfil Profissional') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h1 class="text-3xl font-black text-gray-900">Resultado Concluído!</h1>
                <p class="text-gray-600 mt-2 text-lg">Com base nas suas respostas, identificamos uma afinidade maior com a área de:</p>
                <div class="mt-6 inline-block px-8 py-3 bg-emerald-600 text-white text-2xl font-black rounded-full shadow-lg">
                    {{ $result->recommended_area }}
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Seu Radar de Afinidades</h3>
                    <div class="relative h-72 w-full">
                        <canvas id="vocationalChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Detalhamento por Área</h3>
                    <div class="space-y-4">
                        @foreach(collect($result->scores)->sortDesc() as $area => $pontos)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="font-bold text-gray-700">{{ $area }}</span>
                                <span class="px-3 py-1 bg-white text-emerald-600 font-black rounded-md border border-gray-100 shadow-sm text-sm">
                                    {{ $pontos }} {{ $pontos == 1 ? 'ponto' : 'pontos' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-800 text-white font-bold rounded-xl hover:bg-gray-900 transition-colors">
                    Voltar para o Painel
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('vocationalChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Pontuação',
                        data: {!! json_encode($chartValues) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y', // Barras horizontais para facilitar leitura de nomes longos
                    scales: {
                        x: { beginAtZero: true },
                        y: { grid: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>