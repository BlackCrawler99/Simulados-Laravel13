@extends('layouts.admin')

@section('title', 'Desempenho - ' . $schoolClass->name)

@section('content')
<div class="mb-8">
    <nav class="flex mb-4 text-gray-500 text-sm font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.colegios.index') }}" class="hover:text-indigo-600 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Rede de Colégios
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('admin.colegios.classes', $school->id) }}" class="ml-1 md:ml-2 hover:text-indigo-600">{{ $school->name }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 md:ml-2 text-gray-400">{{ $schoolClass->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Diagnóstico de Turma: {{ $schoolClass->name }}</h2>
            <p class="text-gray-600 mt-1 font-medium">{{ $school->name }} • Período {{ $schoolClass->period }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="p-4 bg-indigo-50 text-indigo-600 rounded-xl">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Alunos Vinculados</p>
            <h4 class="text-3xl font-black text-gray-800">{{ $totalStudents }}</h4>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="p-4 bg-green-50 text-green-600 rounded-xl">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Participantes Ativos</p>
            <h4 class="text-3xl font-black text-gray-800">{{ $studentsWithExams }}</h4>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="p-4 bg-amber-50 text-amber-600 rounded-xl">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Média Geral (Melhor Simulado)</p>
            <h4 class="text-3xl font-black text-gray-800">{{ number_format($globalAverage, 1, ',', '.') }} <span class="text-sm font-normal text-gray-400">/ 100</span></h4>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
        <h3 class="text-lg font-bold text-gray-800 mb-4 w-full text-left">Desempenho por Área de Conhecimento (ENEM)</h3>
        <div class="w-full max-w-md relative flex justify-center items-center">
            @if($studentsWithExams > 0)
                <canvas id="radarChart" height="280"></canvas>
            @else
                <div class="h-64 flex items-center justify-center text-gray-400 font-medium">
                    Nenhum aluno realizou provas ainda.
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Alunos com Maiores Notas</h3>
            <span class="text-xs font-black uppercase tracking-wider text-gray-400 bg-gray-50 px-3 py-1 rounded-full">Destaques</span>
        </div>
        <div class="divide-y divide-gray-50 overflow-y-auto max-h-96">
            @forelse($students->sortByDesc(function($user) { return $user->exams->max('score'); })->take(8) as $student)
                @php
                    $bestScore = $student->exams->max('score');
                @endphp
                @if($bestScore !== null)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center font-black text-gray-600 text-sm">
                                {{ substr($student->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $student->name }}</p>
                                <p class="text-[11px] text-gray-400 font-medium">{{ $student->email }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-black rounded-lg">
                            {{ number_format($bestScore, 1) }} pts
                        </span>
                    </div>
                @endif
            @empty
                <div class="p-12 text-center text-gray-400 font-medium">
                    Nenhum aluno desta turma concluiu simulados.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($studentsWithExams > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('radarChart').getContext('2d');
    
    // Injeta os dados gerados no Controller via PHP
    const labels = @json($radarLabels);
    const dataValues = @json($radarValues);

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Aproveitamento Médio (%)',
                data: dataValues,
                backgroundColor: 'rgba(99, 102, 241, 0.2)', // Cor Indigo Tailwind
                borderColor: 'rgb(99, 102, 241)',
                pointBackgroundColor: 'rgb(99, 102, 241)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(99, 102, 241)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    angleLines: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endif
@endsection