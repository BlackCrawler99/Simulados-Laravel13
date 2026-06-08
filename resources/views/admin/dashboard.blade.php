@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Inteligência e Captação</h2>
        <p class="text-gray-600 mt-1 font-medium">Métricas em tempo real de engajamento e desempenho geral.</p>
    </div>
    
    <a href="{{ route('admin.dashboard.export') }}" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Exportar Relatório Geral
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow relative overflow-hidden">
        <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-bl-full -z-10"></div>
        <div class="p-4 rounded-full bg-blue-100 text-blue-600 mr-5 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total de Leads</p>
            <p class="text-3xl font-black text-gray-900">{{ $totalLeads }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow relative overflow-hidden">
        <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 rounded-bl-full -z-10"></div>
        <div class="p-4 rounded-full bg-indigo-100 text-indigo-600 mr-5 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Finalizados</p>
            <p class="text-3xl font-black text-gray-900">{{ $totalExams }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow relative overflow-hidden">
        <div class="absolute right-0 top-0 w-24 h-24 bg-green-50 rounded-bl-full -z-10"></div>
        <div class="p-4 rounded-full bg-green-100 text-green-600 mr-5 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Média Geral</p>
            <p class="text-3xl font-black text-gray-900">{{ number_format($avgScore, 1, ',', '') }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Captação de Novos Leads</h3>
        <p class="text-sm text-gray-500 mb-4">Volume de cadastros nos últimos 7 dias</p>
        <div class="relative h-72 w-full">
            <canvas id="leadsChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1 text-center">Taxa de Conclusão</h3>
        <p class="text-sm text-gray-500 mb-4 text-center">Iniciados vs. Finalizados</p>
        <div class="relative h-64 w-full flex justify-center">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Distribuição de Desempenho</h3>
        <p class="text-sm text-gray-500 mb-4">Quantidade de alunos por faixa de nota (0 a 10)</p>
        <div class="relative h-80 w-full">
            <canvas id="notasChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Média por Área</h3>
            <p class="text-sm text-gray-500 mb-6">Desempenho geral agrupado</p>
            
            <div class="space-y-5">
                @forelse($avgAreas as $areaName => $media)
                    <div>
                        <div class="flex justify-between text-sm font-bold text-gray-700 mb-1">
                            <span>{{ $areaName }}</span>
                            <span class="text-indigo-600">{{ number_format($media, 1, ',', '') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $media * 10 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Nenhum dado de simulado disponível.</p>
                @endforelse
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 mt-6 text-center">
            <span class="text-xs text-gray-400 font-medium">Dados atualizados com base nos últimos simulados</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 w-full">

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-gray-800">Cursos Mais Procurados</h3>
            <span class="text-xs font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-full">Oficiais</span>
        </div>
        <div class="space-y-5">
            @forelse($topCourses as $course)
                @php $percentage = $maxCourseCount > 0 ? ($course->total / $maxCourseCount) * 100 : 0; @endphp
                <div class="relative">
                    <div class="flex justify-between text-sm font-bold mb-1.5">
                        <span class="text-gray-700 truncate pr-4" title="{{ $course->desired_course }}">{{ $course->desired_course }}</span>
                        <span class="text-blue-600 tabular-nums">{{ $course->total }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-400 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-4">Nenhum dado registrado.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-gray-800">Demanda Reprimida</h3>
            <span class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full">Sugeridos</span>
        </div>
        <div class="space-y-5">
            @forelse($topInterestedCourses as $course)
                @php $percentage = $maxInterested > 0 ? ($course->total / $maxInterested) * 100 : 0; @endphp
                <div class="relative">
                    <div class="flex justify-between text-sm font-bold mb-1.5">
                        <span class="text-gray-700 truncate pr-4" title="{{ $course->interested_course }}">{{ $course->interested_course }}</span>
                        <span class="text-green-600 tabular-nums">{{ $course->total }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-400 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-4">Nenhum curso extra sugerido.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-gray-800">Situação Escolar</h3>
            <span class="text-xs font-bold text-purple-700 bg-purple-100 px-3 py-1 rounded-full">Perfil</span>
        </div>
        <div class="space-y-5">
            @forelse($schoolYears as $year)
                @php $percentage = $maxSchoolYear > 0 ? ($year->total / $maxSchoolYear) * 100 : 0; @endphp
                <div class="relative">
                    <div class="flex justify-between text-sm font-bold mb-1.5">
                        <span class="text-gray-700 truncate pr-4" title="{{ $year->school_year }}">{{ $year->school_year }}</span>
                        <span class="text-purple-600 tabular-nums">{{ $year->total }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-400 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-4">Nenhum perfil registrado.</p>
            @endforelse
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. GRÁFICO DE LEADS (LINHA DINÂMICA)
        const ctxLeads = document.getElementById('leadsChart').getContext('2d');
        const labelsLeads = {!! json_encode($chartLabels) !!};
        const dataLeads = {!! json_encode($chartData) !!};

        let gradientLeads = ctxLeads.createLinearGradient(0, 0, 0, 300);
        gradientLeads.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
        gradientLeads.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        new Chart(ctxLeads, {
            type: 'line',
            data: {
                labels: labelsLeads,
                datasets: [{
                    label: 'Novos Cadastros',
                    data: dataLeads,
                    borderColor: '#4f46e5',
                    backgroundColor: gradientLeads,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 10 }, grid: { borderDash: [4, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. GRÁFICO DE TAXA DE CONCLUSÃO (DINÂMICO)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Finalizados', 'Incompletos'],
                datasets: [{
                    data: [{{ $totalExams }}, {{ $totalIncompleteExams }}], 
                    backgroundColor: ['#10b981', '#f59e0b'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 20, font: { weight: 'bold' } } }
                }
            }
        });

        // 3. GRÁFICO DE DISTRIBUIÇÃO DE NOTAS (DINÂMICO)
        const ctxNotas = document.getElementById('notasChart').getContext('2d');
        
        new Chart(ctxNotas, {
            type: 'bar',
            data: {
                labels: ['0-2', '2-4', '4-6', '6-8', '8-10 (Destaques)'],
                datasets: [{
                    label: 'Quantidade de Alunos',
                    data: {!! json_encode($notasDistribution) !!}, 
                    backgroundColor: ['#ef4444', '#f97316', '#eab308', '#3b82f6', '#10b981'],
                    borderRadius: 6,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

    });
</script>
@endsection