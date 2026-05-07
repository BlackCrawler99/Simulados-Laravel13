@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Visão Geral</h2>
    <p class="text-gray-600 mt-1">Acompanhe as métricas de captação e desempenho dos simulados.</p>
</div>

<!-- Cartões de Métricas (KPIs) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Total de Leads -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total de Leads</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalLeads }}</p>
        </div>
    </div>

    <!-- Simulados Realizados -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Simulados Finalizados</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalExams }}</p>
        </div>
    </div>

    <!-- Média Geral de Notas -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Média Geral</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($avgScore, 1, ',', '') }}</p>
        </div>
    </div>
</div>

<!-- Área do Gráfico -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full max-w-4xl">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Novos Leads (Últimos 7 Dias)</h3>
    <canvas id="leadsChart" height="100"></canvas>
</div>

<!-- Importando o Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('leadsChart').getContext('2d');
    
    // Recupera as variáveis do Laravel (arrays) e converte para Javascript
    const labels = {!! json_encode($chartLabels) !!};
    const data = {!! json_encode($chartData) !!};

    new Chart(ctx, {
        type: 'line', // Você pode mudar para 'bar' se preferir barras
        data: {
            labels: labels,
            datasets: [{
                label: 'Novos Cadastros',
                data: data,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3 // Deixa a linha curvada/suave
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 } // Força o eixo Y a usar números inteiros
                }
            }
        }
    });
</script>
@endsection