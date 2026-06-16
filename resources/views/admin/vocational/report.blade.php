@extends('layouts.admin')

@section('title', 'Relatório Vocacional - ' . ($result->user->name ?? 'Aluno'))

@section('content')
<div class="mb-8">
    <nav class="flex mb-4 text-gray-500 text-sm font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.vocational.students') }}" class="hover:text-emerald-600 flex items-center">
                    Lista de Alunos
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 md:ml-2 text-gray-400">Diagnóstico Individual</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">{{ $result->user->name ?? 'Aluno Removido' }}</h2>
            <p class="text-gray-600 mt-1 font-medium">{{ $result->user->email ?? 'N/A' }} • Teste realizado em {{ $result->created_at->format('d/m/Y') }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Área de Maior Afinidade</h3>
            
            <div class="inline-flex items-center justify-center p-4 bg-emerald-50 text-emerald-600 rounded-full mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            
            <h4 class="text-3xl font-black text-gray-900 mb-2">{{ $result->recommended_area }}</h4>
            <p class="text-sm text-gray-500 font-medium">Este é o perfil dominante com base nas respostas e pesos do questionário.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50">
                <h3 class="text-sm font-bold text-gray-800 uppercase">Detalhamento de Pontos</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach(collect($scores)->sortDesc() as $area => $pontos)
                    <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span class="font-bold text-gray-700">{{ $area }}</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-black rounded-lg">
                            {{ $pontos }} {{ $pontos == 1 ? 'ponto' : 'pontos' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col">
            <h3 class="text-lg font-bold text-gray-800 mb-6 w-full text-left">Gráfico de Afinidade Vocacional</h3>
            <div class="flex-grow relative flex justify-center items-center w-full min-h-[300px]">
                <canvas id="vocationalChart"></canvas>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('vocationalChart').getContext('2d');
    
    const labels = @json($chartLabels);
    const dataValues = @json($chartValues);

    new Chart(ctx, {
        type: 'bar', // Gráfico de barras horizontais é excelente para ranking
        data: {
            labels: labels,
            datasets: [{
                label: 'Pontuação de Afinidade',
                data: dataValues,
                backgroundColor: 'rgba(16, 185, 129, 0.2)', // Emerald 500 com transparência
                borderColor: 'rgb(16, 185, 129)', // Emerald 500
                borderWidth: 2,
                borderRadius: 8,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Deixa as barras horizontais para facilitar a leitura dos nomes longos das áreas
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)', // Gray 900
                    padding: 12,
                    titleFont: { size: 14 },
                    bodyFont: { size: 13, weight: 'bold' },
                    displayColors: false
                }
            }
        }
    });
});
</script>
@endsection